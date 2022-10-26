<?php
class Products_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function main() { redirect('/');}

    public function view($product_id) {
        $data = $this->db->from('products p')
        ->select('p.*,
        (SELECT code FROM currencys WHERE id=p.currency_id) currency_code')
        ->where('id',$product_id)->where('status',0,'!=')->first();
        if(!$data['id']) return false;

        $data['url'] = BASEURL . parent::get_seo_link('products',$data['id']);
        $data['stock'] = 1;

        $auction = $this->db->from('auctions')->where('id',$data['auction_id'])->first();

        if(time() >= $auction['end_time'] && $auction['status'] == 1) $auction['status'] = 2;

        if($auction['live_end_time']) {
            $last24 =  $auction['live_end_time'] + 86400;
            if(time() > $last24) {
                $data['pricehide'] = true;
            }
        }

         if($data['status'] == 2) $data['stock'] = 0;
         if($auction['status']!=1) $data['stock'] = 0;

        $data['auction_url'] = BASEURL . parent::get_seo_link('auctions',$data['auction_id']);

        $auction['end_date'] = strftime("%d %B, %H:%M",$auction['end_time']);
        $auction['start_live'] = strftime("%d %B, %H:%M",strtotime("+2 minutes",$auction['end_time']));
        $auction['date'] = date("Y/m/d H:i:s",strtotime("+2 minutes",$auction['end_time']));
        $auction['url'] = BASEURL . parent::get_seo_link('auctions',$auction['id']);
        $auction['live_url'] = BASEURL . parent::get_seo_link('auctions',$auction['id'],'live');
        $data['auction'] = $auction;

        if(Session::fetch('user','id')) $data['me_follow'] = $this->db->from('users_favorites')->select('COUNT(id) total')->where('product_id',$data['id'])->where('user_id',Session::fetch('user','id'))->total();
        if(Session::fetch('user','id')) $data['me_live'] = $this->db->from('users_live')->select('COUNT(id) total')->where('product_id',$data['id'])->where('user_id',Session::fetch('user','id'))->total();
        $data['follows'] = self::GetProductReviews($data['id']);
        $data['offers'] = self::GetProductOffers($data['id']);
        $data['lotown'] = ($data['sale'] == Session::fetch('user','id')) ? 1 : 0;
        if(Session::fetch('user','id')) $data['pey'] = $this->db->from('offers')->select('id,price')->where('product_id',$data['id'])->where('user_id',Session::fetch('user','id'))->orderby('price','DESC')->first();

        $cats = $cat_ids = explode(',',$data['category_id']);
        if($cats) {
            $cats = $this->db->from('categories')->select('id,name')->in('id',$cats)->orderby('id','ASC')->run();
            foreach($cats as $key => $cat) {
                $cats[$key]['url'] = parent::get_seo_link('categories',$cat['id']);
            }
            if($cats) {
                $data['cats'] = $cats;
            }
        }

        $EXCHANGE = [];

        parent::calcProductPrice($data,$EXCHANGE);

        $data['pictures'] = $this->db->from('products_images')->where('product_id',$data['id'])->orderby('rows','ASC')->run();

        if($data['price']) {
            $data['comm'] = ((($data['price'] * $auction['buy_comm']) / 100));
            $data['total'] = ($data['price'] + $data['comm']);
        }

        //SEO
        $data['seo_title'] = sprintf($this->vars['urunseotitle'],$data['name']);
        $data['seo_desc'] = sprintf($this->vars['urunseodesc'],$data['name'],$data['name'],$data['name'],$data['name']);
        $data['today_date'] = date("Y-m-d");

        return $data;
    }

    public function comments($product_id){

        if(Session::fetch('user','id') && $comment = $this->input->post('comment',true)){
            $time = time()+ 1000;
            $sess = Session::fetch('comment_');
            if(!$sess || $sess<$time) {
                $comment = strip_tags($comment);
                if(strlen($comment)>=5) {

                    $rate = $this->input->post('rate',true);
                    $arr = [
                        'product_id' => $product_id,
                        'user_id' => Session::fetch('user','id'),
                        'detail' => $comment,
                        'rate' => max(1,$rate),
                        'create_time' => time(),
                        'ipaddress' => USER_IP,
                        'status' => 0,
                    ];

                    $this->db->insert('comments')->set($arr);
                    $this->setMessage($this->vars['yorumbasarili'],'success');
                    Session::set('comment_',$time + 120);

                } else $this->setMessage($this->vars['min5karakter'],'danger');
            } else {
                $this->setMessage(sprintf($this->vars['arkaarkayayorum'],ceil(($sess - $time)  / 60)),'danger');
            }
        }

        $data = $this->db->from('comments c')->join('users u','c.user_id = u.id','LEFT')->select('c.*,u.name, u.surname')->where('c.product_id',$product_id)->where('c.status',1)->orderby('c.id','ASC')->run();
        foreach($data as $key => $value) {
            $data[$key]['rate_width'] = (20 * intval($value['rate']));
            $data[$key]['name_short'] = $value['name'].' '.substr($value['surname'],0,1).'*****';
            $data[$key]['create_date'] = date("d.m.Y",$value['create_time']);
        }

        return $data;
    }

    public function picture($id) {
        $pictures = $this->db->from('products_images')->where('product_id',$id)->orderby('rows','ASC')->run();
        if(!$pictures) $pictures[] = ['id' => 0, 'product_id' => $id,  'picture' => 'noimg.jpg'];
        die(json_encode($pictures));
    }

}
