<?php
class Cart_Model extends Model
{
    public function __construct(){
        parent::__construct();
    }

    public function main(){

        $data = self::quickcart();

        $data['genel_total'] = $data['total'];

        $code = ($this->input->post('coupon',true)) ? $this->input->post('coupon',true) : Session::fetch('coupon','code');

        if($code) {
            $coupon = parent::CheckCoupon($code,$data['total']);
            if($coupon) {
                Session::set('coupon',$coupon);
                $data['discount'] = $coupon['price'];
                $data['genel_total'] -= $coupon['price'];
            } else {
                Session::uset('coupon');
            }
        }

        return $data;

    }

    public function addcart(){
        $json['status'] = 'failed';
        $json['head'] = $this->vars['hatali'];
        $json['message'] = $this->vars['gecersizurun'];

        if($product_id = $this->input->post('ids')){
            $products = $this->db->from('products')->in('id',$product_id)->where('status',2)->where('sale',Session::fetch('user','id'))->where('sold',0)->run();

            if($products){
                foreach($products as $product) {
                    $product['stock'] = 1;
                    $product['cart_sku'] = $product['id'];

                    $CART = (array)Session::fetch('CART');
                    $CART[$product['cart_sku']] = [
                        'id' => $product['id'],
                        'quantity' => 1,
                    ];

                    Session::set('CART',$CART);
                }
            }
        }

        return $json;
    }

    public function removecart(){
        $json['status'] = 'failed';
        $json['head'] = $this->vars['hatali'];
        $json['message'] = $this->vars['gecersizislem'];

        if($sku = $this->input->post('id',true)){
            $CART = (array)Session::fetch('CART');
            if($CART[$sku]) {
                $reload = $this->input->post('reload');
                unset($CART[$sku]);
                Session::set('CART',$CART);
                $json['count'] = count($CART);
                $json['status'] = 'success';
                $json['head'] = $this->vars['basarili'];
                $json['message'] = $this->vars['urunsepetsil'];
                if($reload) $this->setMessage($this->vars['urunsepetsil'],'success');
                return $json;
            }
        }

        return $json;
    }

    public function updatecart(){
        if($q = $this->input->post('quantity')){
            $CART = Session::fetch('CART');
            foreach($q as $sku => $adet) {
                if($CART[$sku]) {
                    $CART[$sku]['quantity'] = $adet;
                }
            }
            Session::set('CART',$CART);
        }
    }

    public function quickcart(){

        $CART = Session::fetch('CART');
        if(is_array($CART) && array_filter($CART)) {

            $EXCHANGE = [];

            $free_cargo = false;
            foreach($CART as $sku => $value)
            {
                $product = $this->db->from('products p')->select('p.id, p.sku, p.name, p.price, p.old_price, p.currency_id,p.kdv, p.seller,
                    (SELECT picture FROM products_images WHERE product_id=p.id ORDER BY `rows` ASC LIMIT 1) picture,
                    (SELECT code FROM currencys WHERE id=p.currency_id) currency_code
                ')->where('p.id',$value['id'])->where('p.status',2)->first();
                if(!$product) {
                    unset($CART[$sku]); continue;
                }
                $product['url'] = parent::get_seo_link('products',$product['id']);

                parent::calcProductPrice($product,$EXCHANGE);

                $product['comm'] = ((($product['sale_price'] * $this->settings['commission']) / 100) * 1.18);
                $product['total_price'] = ($product['sale_price'] + $product['comm']);

                $product['quantity'] = $value['quantity'];

                $product['total_desi'] = ($product['desi'] * $product['quantity']);
                $products[$sku] = $product;
                $total[] = $product['total_price'];
                $totalcomm[] = $product['comm'];
                $totaldesi[] = $product['total_desi'];
            }

            Session::set('CART',$CART);

            return ['total' => array_sum($total),'comm' => array_sum($totalcomm),'products'=>$products, 'free_cargo' => $free_cargo , 'total_desi' => array_sum($totaldesi)];

        } else {
            Session::uset('CART');
        }
    }

    public function guest(){

        $data = self::main();

        $data['cargoCompanys'] = parent::calcCargoCompanys($data);
        return $data;
    }

    public function address(){
        $user = Session::fetch('user');

        $address = $this->db->from('users_address')->where('user_id',$user['id'])->orderby('main','ASC')->run();

        foreach($address as $a) {
            if($a['billing']) {
                $billing[] = $a;
            } else {
                $shipping[] = $a;
                if($a['main']) $city_id = $a['city_id'];
            }
        }

        $data = self::main();

        $data['city_id'] = $city_id;
        $data['billing'] = $billing;
        $data['shipping'] = $shipping;

        $data['cargoCompanys'] = parent::calcCargoCompanys($data);

        return $data;
    }

    public function cargo_company() {
        $city_id = $this->input->post('city_id');

        if($address_id = $this->input->post('address_id')) {
            $a = $this->db->from('users_address')
            ->where('id',$address_id)->select('city_id')
            ->where('user_id',Session::fetch('user','id'))
            ->first();
            $city_id = $a['city_id'];
        }

        if($city_id && Session::fetch('CART')) {
            $data = self::main();
            $data['city_id'] = $city_id;
            return parent::calcCargoCompanys($data);
        }
    }

    public function cart_summary(&$data) {
        if(!$data['city_id'] && !$data['cargo_id']) return false;
        $cargo = parent::calcCargoCompanys($data);
        if($cargo) {
            $data['cargo'] = array_shift($cargo);
            $data['genel_total'] += $data['cargo']['price'];
        }

    }

    public function checkout() {

        $ad = $this->input->post('ad');

        $a = $this->input->post('a') ?? Session::fetch('address');

        if(!$ad && !$a) return false;

        $shippingCompanyId = $this->input->post('shippingCompanyId') ?? Session::fetch('shippingCompanyId');

        if(!$shippingCompanyId) {
            $this->setMessage($this->vars['gecersizkargofirma'],'danger');
            return false;
        }

        $user = Session::fetch('user');

        if(!$user['id']) {
            $email = $this->input->post('email');
            if(stripos($email,'@') === FALSE) {
                $this->setMessage($this->vars['gecersizemailadresi'],'danger');
                return false;
            }
            $user = $ad;
            unset($user['address_name'],$user['a_type'],$user['tax_name'],$user['tax_no'],$user['postacode']);

            $newpass = rand(10000,99999);

            $user['username'] = $email;
            $user['password'] = md5($newpass);
            $user['status'] = 1;
            $user['create_time'] = time();
            $user['create_ip'] = USER_IP;

            $group = $this->db->from('users_groups')->orderby('main','DESC')->first();
            if($group['id']) $user['group_id'] = $group['id'];

            $this->db->insert('users')->set($user);
            $id = $this->db->lastId();
            if($id) {
                $user['id'] = $id;
                Session::set('user',$user);

                $search = ['{{USERNAME}}','{{PASSWORD}}','{{NAME}}','{{SURNAME}}','{{LOGIN_URL}}'];
                $replace = [$user['username'],$newpass,$user['name'],$user['surname'],BASEURL .'user/login'];

                $this->settings['newuser_header'] = str_replace($search,$replace,$this->settings['newuser_header']);
                $this->settings['newuser_html'] = str_replace($search,$replace,$this->settings['newuser_html']);

                if($this->settings['newuser_header'])
                    sendMail($user['username'],$this->settings['newuser_header'],$this->settings['newuser_html']);
            }

        }

        if($ad) {
            $ad['user_id'] = Session::fetch('user','id');
            $ad['main'] = 1;
            $ad['name'] = $ad['name'].' '.$ad['surname'];

            $shipping = $billing = $ad;

            $shipping['shipping'] = 1;
            $billing['billing'] = 1;

            unset($shipping['a_type'],$shipping['tax_no'],$shipping['tax_name'],$shipping['surname'],$billing['surname']);

            // Teslimat Adresi
            $this->db->insert('users_address')->set($shipping);
            $a['ship_id'] = $this->db->lastId();

            // Fatura Adresi
            $this->db->insert('users_address')->set($billing);
            $a['bill_id'] = $this->db->lastId();
        }

        if(!$a['ship_id'] && !$a['bill_id']) {
            $this->setMessage($this->vars['gecersizadresbilgileri'],'danger');
            return false;
        }

        Session::set('address',$a);
        Session::set('shippingCompanyId',$shippingCompanyId);

        $ua  = $this->db->from('users_address')->select('city_id')->where('id',$a['ship_id'])->first();
        if(!$ua) return false;

        $data = self::main();

        $data['city_id'] = $ua['city_id'];
        $data['cargo_id'] = $shippingCompanyId;


        self::cart_summary($data);

        $data['installments'] = self::installments($data['genel_total']);

        return $data;

    }

    public function payment($payment){

        $data['create_time'] = time();
        $data['create_ip'] = USER_IP;
        $data['code'] = 'SP'.str_replace('.','',time());
        $data['payment_type'] = $payment['payment_type'];
        $data['installment'] = $payment['installment'];
        $data['user_id'] = Session::fetch('user','id');
        $data['status'] = 0;
        $data['note'] = $payment['note'];

        switch($payment['payment_type']){
            case '1':
                $data['payment_id'] = $payment['payment_bank'];
                $data['card_holdername'] = $payment['cardname'];
                $payment['cardnumber'] = preg_replace('#[^0-9]+#','',$payment['cardnumber']);
                $data['card_number'] = substr($payment['cardnumber'],0,6).'*******'.substr($payment['cardnumber'],-4);
                $data['status'] = -1;
                break;
            case '2':
                $data['payment_id'] = $payment['payment_bank'];
                break;
            case '3':
                $data['payment_price'] = $this->settings['kapidanakit_price'];
                break;
            case '4':
                $data['payment_price'] = $this->settings['kapidakart_price'];
                break;
        }

        $cart = self::main();
        if(!$cart['products']) redirect('cart');

        $shipping  = $this->db->from('users_address')
        ->select('user_id,name,address,postacode,city_id,country_id,state,phone,shipping')
        ->where('id',Session::fetch('address','ship_id'))
        ->first();
        $billing  = $this->db->from('users_address')
        ->select('user_id,name,address,postacode,city_id,country_id,state,phone,a_type,tax_name,tax_no,billing')
        ->where('id',Session::fetch('address','bill_id'))
        ->first();

        $cart['city_id'] = $shipping['city_id'];
        $cart['cargo_id'] = Session::fetch('shippingCompanyId');

        self::cart_summary($cart);

        if(Session::fetch('coupon')) {
            $data['coupon_code'] = Session::fetch('coupon','code');
            $data['coupon_type'] = Session::fetch('coupon','d_type');
            $data['coupon_price'] = Session::fetch('coupon','discount');
        }

        $data['price'] = ($cart['genel_total'] + $data['payment_price']);
        $data['currency_id'] = $this->ActiveCurrency['id'];
        $data['cargo_id'] = $cart['cargo']['id'];
        $data['cargo_price'] = $cart['cargo']['price'];
        $data['cargo_integration_id'] = $cart['cargo']['integration_id'];


        $this->db->insert('orders')->set($data);

        $data['id'] = $this->db->lastId();
        if($data['id']) {
            foreach($cart['products'] as $modelSku => $pro) {
                $pro['comm'] = ((($pro['sale_price'] * $this->settings['commission']) / 100) * 1.18);
                $pro['sale_price'] = ($pro['sale_price'] + $pro['comm']);

                $CheckoutProducts[] = array($pro['sku'].' '.$pro['name'], numbers($pro['sale_price']), $pro['quantity']);
                $this->db->insert('orders_products')->set([
                    'order_id' => $data['id'],
                    'product_id' => $pro['id'],
                    'sku' => $pro['sku'],
                    'stocksku' => ($modelSku != $pro['sku']) ? $modelSku : '',
                    'name' => $pro['name'],
                    'model' => $pro['variants'],
                    'quantity' => $pro['quantity'],
                    'price' => $pro['sale_price'],
                    'desi' => $pro['desi'],
                    'kdv' => $pro['kdv'],
                    'comm' => $pro['comm'],
                    'seller' => $pro['seller'],
                ]);

                $this->db->update('products')->where('id',$pro['id'])->set(['sold'=>1]);
            }

            unset($shipping['id'],$billing['id']);
            $shipping['order_id'] =  $billing['order_id'] = $data['id'];

            $this->db->insert('orders_address')->set($shipping);
            $this->db->insert('orders_address')->set($billing);

            if(Session::fetch('coupon','id') && !Session::fetch('coupon','multi')) {
                $this->db->update('coupons')->where('id',Session::fetch('coupon','id'))->set(['used'=>1,'user_id'=>$data['user_id']]);
            }

            // DONE: Sanalpos bağlanacak
            if($payment['payment_type'] == 1) {
                self::calcInstalmentPrice($data);
                $vpos = $this->db->from('virtual_pos')->where('id',$payment['payment_bank'])->first();
                $vpos_response = include_once 'payments/'.$vpos['apifile'];
                if($vpos_response['status'] == 'success') {
                    Session::set('order_data',$data);
                    $this->db->update('orders')->where('id',$data['id'])->set([
                        'price'=>$data['price'],
                        'payment_price'=>$data['payment_price'],
                    ]);
                    die($vpos_response['html']);
                } else {
                    $this->db->update('orders')->where('id',$data['id'])->set(['err_message'=>$vpos_response['reason']]);
                    $this->setMessage($vpos_response['reason'],'danger');
                    redirect('cart/checkout');
                }
            }

            // DONE: Mail Gönderim İşlemleri yapılacak
            parent::CreateOrderMail($data['id']);
            $smsText = str_replace(['{{NAME}}','{{CODE}}'],[Session::fetch('user','name').' '.Session::fetch('user','username'),$data['code']],$this->vars['ordersmstext']);
            sendSMS(Session::fetch('user','phone'),$smsText);
            return $data;

        }
        else{
            $this->setMessage($this->vars['islembasarisiz'],'danger');
            redirect('cart/checkout');
        }

    }

    public function success($code) {
        $order = $this->db->from('orders')
        ->where('user_id',Session::fetch('user','id'))
        ->where('code',$code)
        ->first();

        if($order) {
            Session::uset('CART');
            Session::uset('address');
            Session::uset('shippingCompanyId');
            Session::uset('returnUrl');
            Session::uset('coupon');

            $products = $this->db->from('orders_products')->where('order_id',$order['id'])->run();
            foreach($products as $product) {
                $URUN[] = '{id : \''.$product['sku'].'\',quantity : '.$product['quantity'].'}';
                $CODE[] = "'{$product['sku']}'";
            }

            $order['products_ids'] = implode(',',$URUN);
            $order['products_sku'] = implode(',',$CODE);
            $order['cartsuccesstext'] = str_replace(['{{CODE}}'],[$order['code']],$this->vars['cartsuccesstext']);
            return $order;
        }

        return false;
    }

    public function get_checkout_address() {

        $address = Session::fetch('address');

        $shipping = $this->db->from('users_address ua')
        ->join('citys','citys.id = ua.city_id','LEFT')
        ->join('countrys','countrys.id = ua.country_id','LEFT')
        ->where('ua.id',$address['ship_id'])
        ->select('ua.*, citys.name city_name, countrys.name country_name')
        ->first();
        $billing = $this->db->from('users_address ua')
        ->join('citys','citys.id = ua.city_id','LEFT')
        ->join('countrys','countrys.id = ua.country_id','LEFT')
        ->where('ua.id',$address['bill_id'])
        ->select('ua.*, citys.name city_name, countrys.name country_name')
        ->first();

        return compact('shipping','billing');
    }

    public function installments($cash) {
        $vals = $this->db->from('installments')->where('vpos_id',1)->orderby('installment','ASC')->run();

        foreach($vals as $key => $val) {
            $toplamTaksit = $val['installment'];

            $aylik  = round($cash / (1 - str_replace(",", ".", $val["com"]) / 100) / $toplamTaksit, 2);
            $toplam = round($cash / (1 - str_replace(",", ".", $val["com"]) / 100), 2);
            $detail['taksit'] = $toplamTaksit;
            $detail['aylik'] = numbers_dot($aylik);
            $detail['toplam'] = numbers_dot($toplam);
            $detail['aciklama'] = ($toplamTaksit < 2) ? 'Tek Çekim' : $toplamTaksit.' Taksit';
            $taksit[] = $detail;
        }

        return $taksit;
    }

    private function calcInstalmentPrice(&$data) {
        $installment = $this->db->from('installments')->where('vpos_id',$data['payment_id'])
        ->where('installment',$data['installment'])->first();
        if($installment) {
            if($installment['com'] != '0.00') {
                $price = round($data['price'] / (1 - str_replace(",", ".", $installment["com"]) / 100), 2);
                $data['payment_price'] = max(0,($price - $data['price']));
                $data['price'] = $price;
            }
        }
    }
}