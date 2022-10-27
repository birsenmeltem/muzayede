<?php
class Auctions extends Controller
{
    public function __construct(){
        parent::__construct();
    }

    public function main() {redirect('/');}

    public function view($category_id = 0){
        $FILTER = $this->input->get_post('filter');
        if(!$FILTER) $FILTER = [];

        if($FILTER['category_id'] != $category_id)
        {
            unset($FILTER);
            Session::uset('filter');
        }

        $data = $this->model->view($category_id,$FILTER);
        if(!$data) redirect('/');

        parent::set_meta_tags($data);

        $this->view->assign('filter',$FILTER);
        $this->view->assign('data',$data);
        $this->view->assign('content',$this->view->draw('category',true));
        $this->view->draw('index');
    }

    public function ajax(){
        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die();

        $post = $this->input->post('filter',true);
        if(!$post) die;


        $data = $this->model->view($post['category_id'],$post);
        if($data) {
            $this->view->assign('data',$data);
            $this->view->draw('category_right_block');
        }
        return;
    }

    public function price($return = false){
        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die();

        if($user_id = $this->input->post('user_id')) {
            $user = $this->model->db->from('users')->where('id',$user_id)->first();
            Session::set('user',$user);
        }

        $price = $this->input->post('price',true);
        $product_id = (int) $this->input->post('product_id',true);
        if(!$product_id) die;

        $product = $this->model->db->from('products')->where('id',$product_id)->select('old_price,price')->first();
        if(!$product) die();

        if($user_id) {
            @header('Content-Type: application/json');
            if(!$price && $product['price'] == '0.00')
                die(json_encode(['status' => 'success' , 'price' => number_format($product['old_price'],2,'.','')]));
        }

        if(!$price && $product['price'] == '0.00') die(print_r($product['old_price'],1));

        if($price < $product['old_price'])  $price = $product['old_price'];
        if($price < $product['price'])  $price = $product['price'];

        $price = GetPey($price);

        if($user_id) {
            @header('Content-Type: application/json');
            die(json_encode(['status' => 'success' , 'price' => number_format($price,2,'.','')]));
        }


        if($return) return $price;

        die(print_r($price,1));
    }

    public function pey(){
        @header('Content-Type: application/json');
        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die();

        $output = [
            'header' => $this->model->vars['hatali'],
            'status' => 'failed',
            'html' => $this->model->vars['gecerlipeyverin'],
        ];

        if($user_id = $this->input->post('user_id')) {
            $user = $this->model->db->from('users')->where('id',$user_id)->first();
            Session::set('user',$user);
        }


        if(Session::fetch('user','id')) {
            if(Session::fetch('user','status')) {
                $price = round($this->input->post('price',true));
                $product_id = (int) $this->input->post('product_id',true);
                if(!$product_id) die;

                $product = $this->model->db->from('products')->where('id',$product_id)->select('price,sku,old_price,seller,kdv,auction_id')->first();
                if(!$product) die();

                if(Session::fetch('user','id') != $product['seller']) {

                    $lastOfferOwn = $this->model->db->from('offers')->where('product_id',$product_id)->where('user_id',Session::fetch('user','id'))->orderby('price','DESC')->first();

                    if($product['price'] != '0.00' && $price < $product['price']) {
                        $output['html'] = sprintf($this->model->vars['peydusuk'],$product['price']);
                        $price = null;
                    } else {

                        if($price < $product['old_price']) {
                            $output['html'] = sprintf($this->model->vars['peydusuk'],$product['old_price']);
                            $price = null;
                        } else {
                            if($price == $product['price']) {
                                $output['html'] = ($this->model->vars['peyayni']);
                                $price = null;
                            } else {
                                if($lastOfferOwn['price'] && $price <= $lastOfferOwn['price']) {
                                    $output['html'] = sprintf($this->model->vars['peydusuk'],$lastOfferOwn['price']);
                                    $price = null;
                                }
                            }
                        }
                    }
                    if($product['price'] == '0.00') $product['price'] = $product['old_price'];

                    $auc = $this->model->db->from('auctions')->where('id',$product['auction_id'])->first();

                    if($price) {
                        $oldprice = $price;
                        $price = PeyCheck($price);

                        $total['PriceKdv'] = ($price * $product['kdv']) / 100;

                        $total['comm'] = ((($price * $auc['buy_comm']) / 100));
                        $total['comm'] += $total['comm'] * 18 / 100;
                        $total['priceCommTotal'] = ($price);

                        $output = [
                            'header' => $this->model->vars['eminmisin'],
                            'status' => 'success',
                            'price' => $price,
                            'html' => sprintf($this->model->vars['peyhtml'],$auc['name'],$product['sku'],$price, array_sum($total)),
                        ];

                        if($oldprice != $price) {
                            $output['html'] = sprintf($this->model->vars['peydegisti'],$oldprice,$price).$output['html'];
                        }
                    }
                }
                else {
                    $output['html'] = $this->model->vars['kendiurunpey'];
                }
            } else {
                $output['html'] = $this->model->vars['peyicinonay'];
            }
        } else {
            $output['html'] = $this->model->vars['peyicinuye'];
            $output['login'] = true;
            $output['loginbtn'] = $this->model->vars['uyegirisi'];
            $output['regbtn'] = $this->model->vars['uyeol'];
        }
        die(json_encode($output));
    }

    public function save(){
        @header('Content-Type: application/json');
        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die();

        $output = [
            'header' => $this->model->vars['hatali'],
            'status' => 'failed',
            'html' => $this->model->vars['gecerlipeyverin'],
        ];

        if($user_id = $this->input->post('user_id')) {
            $user = $this->model->db->from('users')->where('id',$user_id)->first();
            Session::set('user',$user);
        }


        if(Session::fetch('user','id')) {
            if(Session::fetch('user','status')) {

                $price = round($this->input->post('price',true));
                $product_id = (int) $this->input->post('product_id',true);
                if(!$product_id) die;

                $product = $this->model->db->from('products')->where('id',$product_id)->select('price,old_price,seller,sale,auction_id,sku,name')->first();
                if(!$product) die();

                if($product['price']<$price){

                    if(Session::fetch('user','id') != $product['seller']) {

                        $lastOffer = $this->model->db->from('offers')->where('product_id',$product_id)->orderby('price DESC, id','ASC')->first();

                        $price = PeyCheck($price);

                        $vars = [
                            'user_id' => Session::fetch('user','id'),
                            'product_id' => $product_id,
                            'price' => $price,
                            'create_time' => time(),
                        ];


                        $this->model->db->insert('offers')->set($vars);

                        if($lastOffer['price'] < $price && $lastOffer['user_id'] != Session::fetch('user','id')) $update['price'] = $lastOffer['price'];
                        else  $update['price'] = GetPey($product['price']);

                        if($lastOffer['user_id'] == Session::fetch('user','id')) {
                            $update['sale'] = Session::fetch('user','id');
                            unset($update['price']);
                        } else {
                            if($lastOffer['price'] == $price && $lastOffer['user_id'] != Session::fetch('user','id')) $update['price'] = $lastOffer['price'];
                            else $update['price'] = GetPey($update['price']);
                            if($product['price'] == '0.00') $update['price'] = $product['old_price'];
                            if($price >= $update['price'] && $lastOffer['price']<$price) $update['sale'] = Session::fetch('user','id');
                            else $update['sale'] = $lastOffer['user_id'];

                            if($lastOffer['price'] != $price && $update['sale'] != Session::fetch('user','id')) $update['price'] = GetPey($price);
                        }

                        $this->model->db->update('products')->where('id',$product_id)->set($update);

                        if($product['sale']) {
                            if($product['sale'] != $update['sale']) {
                                $oldUser = $this->model->db->from('users')->select('CONCAT(name," ",surname) names,username')->where('id',$product['sale'])->first();
                                $auctions = $this->model->db->from('auctions')->select('name')->where('id',$product['auction_id'])->first();

                                $search = ['{{NAME}}', '{{MEZAT}}', '{{LOTNO}}', '{{PEY}}', '{{URUNADI}}', '{{FIYAT}}', '{{GUNCELFIYAT}}', '{{URL}}', '{{LOGO}}'];
                                $replace = [$oldUser['names'], $auctions['name'], $product['sku'], $product['price'], $product['name'], $product['old_price'], $update['price'], BASEURL.$this->model->get_seo_link('products',$product_id), BASEURL . 'data/uplaods/'.$this->model->settings['logo']];
                                $this->settings['peygecildi_header'] = str_replace($search,$replace,$this->settings['peygecildi_header']);
                                $this->settings['peygecildi_html'] = str_replace($search,$replace,$this->settings['peygecildi_html']);

                                if($this->settings['peygecildi_header']) {
                                    $this->model->db->insert('crons')->set([
                                        'username' => $oldUser['username'],
                                        'header' => $this->settings['peygecildi_header'],
                                        'html' => $this->settings['peygecildi_html'],
                                        'ty' => 0,
                                    ]);
                                }
                            }
                        }
                        $output['status'] = 'success';
                        $output['html'] = '';
                        $output['header'] = '';
                    }
                    else {
                        $output['html'] = $this->model->vars['kendiurunpey'];
                    }
                } else {
                   $output['html'] = sprintf($this->model->vars['peydusuk'],$product['price']);
                }
            }else {
                $output['html'] = $this->model->vars['peyicinonay'];
            }
        } else {
            $output['html'] = $this->model->vars['peyicinuye'];
            $output['login'] = true;
            $output['loginbtn'] = $this->model->vars['uyegirisi'];
            $output['regbtn'] = $this->model->vars['uyeol'];
        }

        die(json_encode($output));

    }

    public function getshow() {

        $product_id = (int) $this->input->post('product_id',true);
        $type = $this->input->post('type',true);

        $data = $this->model->getshow($product_id);

        $this->view->assign('data',$data);
        $this->view->assign('type',$type);
        $this->view->draw('getshow');
        exit;

    }

    public function live($category_id = 0){
        $data = $this->model->live($category_id);
        if(!$data) redirect('/');

        parent::set_meta_tags($data);

        $firstProduct = current($data['records']);

        $data['records'] = array_map(function($row){
            if (!empty($row['sale'])) {
                $row['sale'] = md5($row['sale']);
            }
            return $row;
            }, $data['records']);

        $this->view->assign('data',$data);
        $this->view->assign('product',$firstProduct);
        $this->view->assign('content',$this->view->draw('live',true));
        $this->view->draw('index');
    }

    public function getpey() {
        $product_id = intval($this->input->post('product_id'));
        if($product_id) {
            die($this->model->GetProductOffers($product_id));
        }
    }

    public function winner($auction_id) {
        if(!Session::fetch('user','id')) die();
        if($auction_id) {
            $data = $this->model->winner($auction_id);
            $this->view->assign('data',$data);
            $this->view->draw('winner_detail');
            exit;
        }
    }

    public function seller($auction_id) {
        if(!Session::fetch('user','id')) die();
        if($auction_id) {
            $data = $this->model->seller($auction_id);
            $this->view->assign('data',$data);
            $this->view->draw('seller_detail');
            exit;
        }
    }

}
