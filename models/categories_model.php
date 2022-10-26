<?php
class Categories_Model extends Model
{
    public function __construct(){
        parent::__construct();
    }

    public function view($category_id,&$FILTER){
        $data = $this->db->from('categories')->where('id',$category_id)->where('status',0,'!=')->first();
        if(!$data) return false;

        if($post = $this->input->post('filter',true)) {
            $post['SAVE'] = true;
            $FILTER = $post;
        }


        $data['url'] = BASEURL . parent::get_seo_link('categories',$data['id']);


        $arg = [
            'orderby' => [
                'p.status ASC, p.sku','ASC'
            ],
            'where' => [
                ['p.category_id',$category_id,'REGEXP REPLACE','&&'],
            ]
        ];

        switch($this->input->get_post('order'))
        {
            case 'price-DESC':
                $arg['orderby'] = ['p.status ASC,price','DESC'];
                break;
            case 'price-ASC':
                $arg['orderby'] = ['p.status ASC, price','ASC'];
                break;
            case 'old_price-DESC':
                $arg['orderby'] = ['p.status ASC, old_price','DESC'];
                break;
            case 'old_price-ASC':
                $arg['orderby'] = ['p.status ASC, old_price','ASC'];
                break;
            case 'sku-ASC':
                $arg['orderby'] = ['p.status ASC, sku','ASC'];
                break;
            case 'sku-DESC':
                $arg['orderby'] = ['p.status ASC, sku','DESC'];
                break;
            case 'peyTotal-DESC':
                $arg['orderby'] = ['p.status ASC, peyTotal','DESC'];
                break;
            case 'followTotal-DESC':
                $arg['orderby'] = ['p.status ASC, followTotal','DESC'];
                break;
        }

        parent::filter_append($FILTER,$arg['where']);

        $arg['url'] = $data['url'].'?order='.implode('-',$arg['orderby']).'&'.http_build_query(['filter'=>$FILTER]).'&page=[page]';
        $data += parent::GetProducts($arg);

        $data['brands'] = self::CategoryBrandList($category_id);
        $data['orderby'] = implode('-',$arg['orderby']);


        return $data;
    }

    protected function CategoryBrandList($category_id){
        if(!$category_id) return [];
        $val = $this->db->from('products')->select("GROUP_CONCAT(brand_id separator  ',') ids")->where('category_id',$category_id)->first();
        $variants = $this->db->from('brands')->in('id',explode(',',$val['ids']))->where('status',1)->orderby('rows','ASC')->run();
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
}