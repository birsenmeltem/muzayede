<?php
class Auctions_Model extends Model
{
    public function __construct(){
        parent::__construct();
    }

    public function view($category_id,&$FILTER){
        $data = $this->db->from('auctions')->where('id',$category_id)->where('status',0,'!=')->first();
        if(!$data) return false;

        if($post = $this->input->post('filter',true)) {
            $post['SAVE'] = true;
            $FILTER = $post;
        }

        if(time() >= $data['end_time'] && $data['status'] == 1) $data['status'] = 2;


        if($data['live_end_time']) {
            $last24 =  $data['live_end_time'] + 86400;
            if(time() > $last24) {
                $data['pricehide'] = true;
            }
        }

        $data['end_date'] = strftime("%d %B, %H:%M",$data['end_time']);
        $data['start_live'] = strftime("%d %B, %H:%M",strtotime("+2 minutes",$data['end_time']));
        $data['date'] = date("Y/m/d H:i:s",strtotime("+2 minutes",$data['end_time']));
        $data['url'] = BASEURL . parent::get_seo_link('auctions',$data['id']);
        $data['live_url'] = BASEURL . parent::get_seo_link('auctions',$data['id'],'live');
        if($data['status'] == 2) $data['url'] = $data['live_url'];

        //dump($data);
        $data['alt_text'] = sprintf($this->vars['muzayededetay'],$data['name'],date("d.m.Y",$data['end_time']),date("H:i",$data['end_time']),date("H:i",$data['live_start_time']),intval($data['buy_comm']));

        $arg = [
            'orderby' => [
                'p.sku','ASC'
            ],
            'where' => [
                ['p.auction_id',$category_id,'=','&&'],
            ]
        ];

        switch($this->input->get_post('order'))
        {
            case 'price-DESC':
                $arg['orderby'] = ['price','DESC'];
                break;
            case 'price-ASC':
                $arg['orderby'] = ['price','ASC'];
                break;
            case 'old_price-DESC':
                $arg['orderby'] = ['old_price','DESC'];
                break;
            case 'old_price-ASC':
                $arg['orderby'] = ['old_price','ASC'];
                break;
            case 'sku-ASC':
                $arg['orderby'] = ['sku','ASC'];
                break;
            case 'sku-DESC':
                $arg['orderby'] = ['sku','DESC'];
                break;
            case 'peyTotal-DESC':
                $arg['orderby'] = ['peyTotal','DESC'];
                break;
            case 'followTotal-DESC':
                $arg['orderby'] = ['followTotal','DESC'];
                break;
        }

        parent::filter_append($FILTER,$arg['where']);

        $arg['url'] = $data['url'].'?order='.implode('-',$arg['orderby']).'&'.http_build_query(['filter'=>$FILTER]).'&page=[page]';
        $data += parent::GetProducts($arg);

        $data['brands'] = self::CategoryBrandList($category_id);
        $data['orderby'] = implode('-',$arg['orderby']);

        //SEO
        $data['seo_title'] = sprintf($this->vars['catseotitle'],$data['name']);
        $data['seo_desc'] = sprintf($this->vars['catseodesc'],$data['name'],$data['name'],$data['name'],$data['name']);
        $data['image'] = BASEURL . 'data/uploads/'.$data['picture'];

        return $data;
    }

    protected function CategoryBrandList($auction_id){
        if(!$auction_id) return [];
        $val = $this->db->from('products')->select("brand_id")->where('auction_id',$auction_id)->groupby('brand_id')->run();
        foreach($val as $v) $ids[] = $v['brand_id'];
        if(!$ids) return [];
        $variants = $this->db->from('brands')->in('id',$ids)->where('status',1)->orderby('rows','ASC')->run();

        return $variants;
    }

    public function getshow($product_id) {

        $data = $this->db->from('products p')
        ->select('p.*,
        (SELECT code FROM currencys WHERE id=p.currency_id) currency_code')
        ->where('id',$product_id)->where('status',1)->first();

        $data['me_follow'] = $this->db->from('users_favorites')->select('COUNT(id) total')->where('product_id',$data['id'])->where('user_id',Session::fetch('user','id'))->total();
        $data['follows'] = self::GetProductReviews($data['id']);
        $data['offers'] = self::GetProductOffers($data['id']);
        $data['lotown'] = ($data['sale'] == Session::fetch('user','id')) ? 1 : 0;
        if(Session::fetch('user','id')) $data['pey'] = $this->db->from('offers')->select('id,price')->where('product_id',$data['id'])->where('user_id',Session::fetch('user','id'))->orderby('create_time','DESC')->first();

        $EXCHANGE = [];

        parent::calcProductPrice($data,$EXCHANGE);

        $data['comm'] = ($data['price'] * $this->settings['commission']) / 100;
        $data['total'] = ($data['price'] + $data['comm']);
        return $data;
    }

    public function live($category_id) {
        $data = $this->db->from('auctions')->where('id',$category_id)->where('status',0,'!=')->first();
        if(!$data) return false;

        $data['end_date'] = strftime("%d %B, %H:%M",$data['end_time']);
        $data['start_live'] = date("d.m.Y H:i",strtotime("+2 minutes",$data['end_time']));
        $data['date'] = date("Y/m/d H:i:s",strtotime("+2 minutes",$data['end_time']));
        $data['url'] = BASEURL . parent::get_seo_link('auctions',$data['id']);
        $data['live_url'] = BASEURL . parent::get_seo_link('auctions',$data['id'],'live');

        $arg = [
            'perpage' => 500,
            'orderby' => [
                'p.sku','ASC'
            ],
            'where' => [
                ['p.auction_id',$category_id,'=','&&'],
            ]
        ];


        $data += parent::GetProducts($arg);
        $data['winner'] = self::winners($category_id);
        return $data;
    }

    public function winners($category_id) {
        if(Session::fetch('user','id')) {
            $products = $this->db->from('products p')->select('id,sku,category_id,brand_id,auction_id, shortdetail, name,price,old_price,currency_id, sale, status,
                (SELECT picture FROM products_images WHERE product_id=p.id ORDER BY `rows` ASC, id ASC LIMIT 1) picture,
            (SELECT code FROM currencys WHERE id=p.currency_id) currency_code')->where('auction_id',$category_id)->where('sale',Session::fetch('user','id'))->in('status',[1,2])->run();

            foreach($products as $key => $value) {
                $value['url'] = self::get_seo_link('products',$value['id']);
                $products[$key] = $value;
            }
            return $products;
        }
    }

    public function winner($id) {
        $auc = $this->db->from('auctions')->where('id',$id)->first();

        $totalPro = $this->db->from('products')->where('auction_id',$auc['id'])->in('status',[1,2,3])->run();
        foreach($totalPro as $tp) $IDS[] = $tp['id'];

         $this->db->from('products')->where('sale',Session::fetch('user','id'))->where('auction_id',$auc['id']);
         $this->db->in('status',[1,2]);

        $products = $this->db->run();
        $buyers['pey'] = $this->db->from('offers')->in('product_id',$IDS)->where('user_id',Session::fetch('user','id'))->select("COUNT(DISTINCT(product_id)) total")->total();

        foreach($products as $pro) {
            $komm = (($pro['price'] * $auc['buy_comm']) / 100);
            //$komm_kdv = ($komm * 0.18);
            $buyers['sale'] += 1;
            $buyers['price'] += numbers($pro['price']);
            $buyers['commission'] += numbers($komm);
            $buyers['commissionkdv'] += numbers($komm_kdv);
            $buyers['totalprice'] += numbers($pro['price'] + (numbers($komm + $komm_kdv)));
        }

        return $buyers;
    }

    public function seller($id) {
        $auc = $this->db->from('auctions')->where('id',$id)->first();

        $totalPro = $this->db->from('products')->where('auction_id',$auc['id'])->run();
        foreach($totalPro as $tp) $IDS[] = $tp['id'];

        $products = $this->db->from('products')->where('seller',Session::fetch('user','id'))->where('sale',0,'>')->where('auction_id',$auc['id'])->in('status',[1,2])->run();
        $buyers['totalproduct'] = $this->db->from('products')->where('auction_id',$auc['id'])->where('seller',Session::fetch('user','id'))->select("COUNT(id) total")->total();

        foreach($products as $pro) {
            $komm = (($pro['price'] * $auc['sell_comm']) / 100);
            //$komm_kdv = ($komm * 0.18);
            $buyers['sale'] += 1;
            $buyers['price'] += numbers($pro['price']);
            $buyers['commission'] += numbers($komm);
            $buyers['commissionkdv'] += numbers($komm_kdv);
            $buyers['totalprice'] += numbers($pro['price'] - (numbers($komm + $komm_kdv)));
        }

        return $buyers;
    }
}