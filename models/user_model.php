<?php
class User_Model extends Model
{
    public function __construct(){
        parent::__construct();
    }

    public function login($login){
        if($login['username'] && $login['password']) {
            $val = $this->db->from('users')->where('username',$login['username'])
                ->where('password',md5($login['password']))
                ->first();
            if($val) {
                if($val['status'] == 1) {

                    $rememberme = (int)$this->input->post('rememberme');

                    $this->db->update('users')->where('id',$val['id'])->set(['online'=>time()]);
                    Session::set('user',$val);
                    if($rememberme) {
                        setcookie('_user',encrypt($val['id']), time() + (60 * 60 * 24 * 360),'/');
                    }
                    if($url = Session::fetch('returnUrl')) {
                        Session::uset('returnUrl');
                        redirect($url);
                    }

                    redirect(($_SERVER['HTTP_REFERRER'] != FALSE));
                } else {
                    $this->setMessage($this->vars['uyelikonaylidegil'],'danger');
                }
            }
            else
            {
                $this->setMessage($this->vars['loginhatali'],'danger');
            }
        } else $this->setMessage($this->vars['eksikbilgi'],'danger');
    }

    public function register($register){
        $pass = $this->input->post('password');

        if($register['username'] && $register['password'] && $register['name'] && $register['surname'] && $pass) {

            $mobile = checkMobile($register['phone']);
            $exist = $this->db->from('users')->like('phone',substr($mobile,1))->first();
            if($exist['id']) {
                if($exist['status']) {
                    $this->setMessage($this->vars['uyevar'],'danger');
                } else $this->setMessage($this->vars['uyevarpasif'],'danger');
                return false;
            }

            if($register['password'] != $pass) {
                $this->setMessage($this->vars['sifreuyusmadi'],'danger');
                return false;
            }


            $register['pass'] = ($register['password']);
            $register['password'] = md5($register['password']);
            $register['status'] = 0;
            $register['sms'] = 1;
            $register['mail'] = 1;
            $register['phone'] = $mobile;
            $register['create_time'] = time();
            $register['create_ip'] = USER_IP;

            $group = $this->db->from('users_groups')->orderby('main','DESC')->first();
            if($group['id']) $register['group_id'] = $group['id'];

            if($register['phone']) {
                Session::set('user_temp',$register);
                redirect('user/sms');
            }
            else
            {
                $this->setMessage($this->vars['loginhatali'],'danger');
            }
        } else $this->setMessage($this->vars['eksikbilgi'],'danger');
    }

    public function sms() {

        if($sms = $this->input->post('sms')) {
            if($sms == $_SESSION['smsCheck']) {
                $register = Session::fetch('user_temp');
                if(!$register['username']) {
                    unset($_SESSION['user_temp']);
                    redirect('user/register');
                }
                $register['status'] = 1;
                $pass = $register['pass'];
                unset($register['pass']);
                $this->db->insert('users')->set($register);
                $uss = $this->db->from('users')->where('phone',$register['phone'])->select('id')->first();
                if($id = $uss['id']) {

                    $register['id'] = $id;

                    $search = ['{{USERNAME}}','{{PASSWORD}}','{{NAME}}','{{SURNAME}}','{{LOGIN_URL}}'];
                    $replace = [$register['username'],$pass,$register['name'],$register['surname'],BASEURL .'user/login'];

                    $this->settings['newuser_header'] = str_replace($search,$replace,$this->settings['newuser_header']);
                    $this->settings['newuser_html'] = str_replace($search,$replace,$this->settings['newuser_html']);

                    if($this->settings['newuser_header'])
                        sendMail($register['username'],$this->settings['newuser_header'],$this->settings['newuser_html']);

                    Session::set('user',$register);
                    $url = BASEURL . 'user/dashboard';
                    if(Session::fetch('returnUrl')) $url = Session::fetch('returnUrl');

                    Session::uset('returnUrl');
                    Session::uset('user_temp');
                    redirect($url);
                }

            } else {
                $this->setMessage($this->vars['smsyanlis'],'danger');
            }
        }

        if(!$_SESSION['smsCheck']) {
            $_SESSION['smsCheck'] = rand(1000,9999);
            // DONE: SMS Gönderilecek
            sendSMS(Session::fetch('user_temp','phone'),sprintf($this->vars['smstext'],$_SESSION['smsCheck']));
        }

    }

    public function address(){

        if($ad = $this->input->post('ad',true)){
            $ids = $this->input->post('ids');
            if(!$ids) {
                $ad['user_id'] = Session::fetch('user','id');
                $ad['main'] = 1;
                $ad['name'] = $ad['name'].' '.$ad['surname'];
                unset($ad['surname']);
                $this->db->update('users_address')->where('user_id',$ad['user_id']);
                if($ad['billing']) $this->db->where('billing',1);
                if($ad['shipping']) $this->db->where('shipping',1);
                $this->db->set(['main'=>0]);
                $this->db->insert('users_address')->set($ad);
                $this->setMessage($this->vars['adreseklendi'],'success');

                $user_update = [
                    'phone' => $ad['phone'],
                    'address' => $ad['address'],
                    'state' => $ad['state'],
                    'city_id' => $ad['city_id'],
                    'country_id' => $ad['country_id'],
                ];
                $this->db->update('users')->where('id',Session::fetch('user','id'))->set($user_update);
                $user = $this->db->from('users')->where('id',Session::fetch('user','id'))->first();
                Session::set('user',$user);

            } else {
                $ad['name'] = $ad['name'].' '.$ad['surname'];
                unset($ad['surname']);
                $this->db->update('users_address')->where('user_id',Session::fetch('user','id'))->where('id',$ids)->set($ad);
                $this->setMessage($this->vars['adresguncellendi'],'success');
            }
            redirect($_SERVER['HTTP_REFERER']);
        }

        $shippings = $this->db->from('users_address ua')
            ->join('citys','citys.id = ua.city_id','LEFT')
            ->join('countrys','countrys.id = ua.country_id','LEFT')
            ->where('ua.user_id',Session::fetch('user','id'))
            ->where('ua.shipping',1)
            ->select('ua.*, citys.name city_name, countrys.name country_name')
            ->run();

        $billings = $this->db->from('users_address ua')
            ->join('citys','citys.id = ua.city_id','LEFT')
            ->join('countrys','countrys.id = ua.country_id','LEFT')
            ->where('ua.user_id',Session::fetch('user','id'))
            ->where('ua.billing',1)
            ->select('ua.*, citys.name city_name, countrys.name country_name')
            ->run();

        return compact('shippings','billings');
    }

    public function editaddress($id){
        $data = $this->db->from('users_address')->where('user_id',Session::fetch('user','id'))->where('id',$id)->first();
        if(!$data) return false;

        $data['type'] = ($data['shipping']) ? 'shipping':'billing';

        if(!$data['a_type']) {
            $name = explode(' ',$data['name']);
            $data['surname'] = array_pop($name);
            $data['name'] = implode(' ',$name);
        }
        return $data;
    }

    public function remaddress($id) {
        $data = $this->db->from('users_address')->where('user_id',Session::fetch('user','id'))->where('id',$id)->first();
        if(!$data)  {
            $this->setMessage($this->vars['gecersizislem'],'danger');
            return false;
        }
        $this->db->delete('users_address')->where('user_id',Session::fetch('user','id'))->where('id',$id)->done();
        if($data['main']) {
            $this->db->update('users_address')->where('user_id',Session::fetch('user','id'));
            if($data['shipping']) $this->db->where('shipping',1);
            if($data['billing']) $this->db->where('billing',1);
            $this->db->orderby('id','ASC')->limit(0,1)->set(['main'=>1]);
        }
        $this->setMessage($this->vars['islembasarili'],'success');
    }

    public function mainaddress($id) {
        $data = $this->db->from('users_address')->where('user_id',Session::fetch('user','id'))->where('id',$id)->first();
        if(!$data)  {
            $this->setMessage($this->vars['gecersizislem'],'danger');
            return false;
        }

        $this->db->update('users_address')->where('user_id',Session::fetch('user','id'));
        if($data['shipping']) $this->db->where('shipping',1);
        if($data['billing']) $this->db->where('billing',1);
        $this->db->set(['main'=>0]);

        $this->db->update('users_address')->where('id',$id)->set(['main'=>1]);

        $this->setMessage($this->vars['islembasarili'],'success');
    }

    public function dashboard() {
        if($u = $this->input->post('u',true)) {
            if(!$u['sms']) $u['sms'] = 0;
            if(!$u['mail']) $u['mail'] = 0;
            $this->db->update('users')->where('id',Session::fetch('user','id'))->set($u);
            $this->setMessage($this->vars['basariileguncellendi'],'success');
        }

        if($s = $this->input->post('s',true)) {
            if(Session::fetch('user','password') == md5($s['oldpass'])) {
                if($s['newpass'] == $s['newpass1']) {
                    $this->db->update('users')->where('id',Session::fetch('user','id'))->set([
                        'password' => md5($s['newpass'])
                    ]);
                    $this->setMessage($this->vars['sifrebasariiledegisti'],'success');
                }
                else $this->setMessage($this->vars['yenisifreleruyusmadi'],'danger');
            } else {
                $this->setMessage($this->vars['mevcutsifreuyusmadi'],'danger');
            }
        }
        $user = $this->db->from('users')->where('id',Session::fetch('user','id'))->first();
        Session::set('user',$user);
        return $user;
    }

    public function orders() {

        $paymentType = [
            1 => $this->vars['kredikartiodeme'],
            2 => $this->vars['havaleodeme'],
            3 => $this->vars['kapidanakitodeme'],
            4 => $this->vars['kapidakredikartiodeme'],
        ];
        $orders = $this->db->from('orders')->where('status',0,'>=')->where('user_id',Session::fetch('user','id'))->orderby('id','DESC')->run();

        foreach($orders as $key => $val) {
            $val['create_date'] = date("d.m.Y H:i",$val['create_time']);
            $val['payment_type_name'] = $paymentType[$val['payment_type']];
            $val['status_name'] = $this->vars['orderstatus'][$val['status']];
            if($val['payment_id']) {
                switch($val['payment_type']) {
                    case '1':
                        $val['payment_bank'] = $this->db->from('virtual_pos')->where('id',$val['payment_id'])->first();
                        break;
                    case '2':
                        $val['payment_bank'] = $this->db->from('payment_banks')->where('id',$val['payment_id'])->first();
                        break;
                }
            }
            $val['currency'] = $this->db->from('currencys')->where('id',$val['currency_id'])->first();
            $orders[$key] = $val;
        }

        return $orders;
    }

    public function order_detail($id){

        $paymentType = [
            1 => $this->vars['kredikartiodeme'],
            2 => $this->vars['havaleodeme'],
            3 => $this->vars['kapidanakitodeme'],
            4 => $this->vars['kapidakredikartiodeme'],
        ];

        $data = $this->db->from('orders')->where('id',$id)->where('user_id',Session::fetch('user','id'))->where('status',0,'>=')->first();
        if(!$data) return false;

        if($data['payment_id']) {
            switch($data['payment_type']) {
                case '1':
                    $data['payment_bank'] = $this->db->from('virtual_pos')->where('id',$data['payment_id'])->first();
                    break;
                case '2':
                    $data['payment_bank'] = $this->db->from('payment_banks')->where('id',$data['payment_id'])->first();
                    break;
            }
        }

        $data['currency'] = $this->db->from('currencys')->where('id',$data['currency_id'])->first();
        $data['cargo'] = $this->db->from('cargo_companys')->select('id,name')->where('id',$data['cargo_id'])->first();

        $data['shipping'] = $this->db->from('orders_address ua')
            ->join('citys','citys.id = ua.city_id','LEFT')
            ->join('countrys','countrys.id = ua.country_id','LEFT')
            ->where('ua.order_id',$data['id'])
            ->where('ua.shipping',1)
            ->select('ua.*, citys.name city_name, countrys.name country_name')
            ->first();

        $data['billing'] = $this->db->from('orders_address ua')
            ->join('citys','citys.id = ua.city_id','LEFT')
            ->join('countrys','countrys.id = ua.country_id','LEFT')
            ->where('ua.order_id',$data['id'])
            ->where('ua.billing',1)
            ->select('ua.*, citys.name city_name, countrys.name country_name')
            ->first();

        $products = $this->db->from('orders_products')->where('order_id',$data['id'])->run();

        $data['create_date'] = date("d.m.Y H:i",$data['create_time']);
        $data['payment_type_name'] = $paymentType[$data['payment_type']];
        $data['status_name'] = $this->vars['orderstatus'][$data['status']];

        $pro_total_price = 0;
        foreach($products as $key => $pro) {
            $picture = $this->db->from('products_images')->select('picture')->where('product_id',$pro['product_id'])->orderby('rows','ASC')->first();
            if($picture) $pro['picture'] = $picture['picture'];
            $pro_total_price += $pro['total_price'] = ($pro['quantity'] * $pro['price']);
            $products[$key] = $pro;
        }

        if($data['coupon_code']) {
            switch($data['coupon_type']) {
                //Yüzdelik
                case '0':
                    $data['coupon_order_price'] = (($pro_total_price * $data['coupon_price']) / 100);
                    break;
                //Net
                case '1':
                    $data['coupon_order_price'] = $data['coupon_price'];
                    break;
            }
        }
        $data['total'] = $pro_total_price;
        $data['products'] = $products;
        return $data;
    }

    public function coupons() {

        $coupons = $this->db->from('coupons')->where('user_id',Session::fetch('user','id'))->orderby('id','DESC')->run();

        foreach($coupons as $key => $val) {
            $val['start_date'] = date("d.m.Y",$val['start_time']);
            $val['end_date'] = ($val['end_time']) ? date("d.m.Y",$val['end_time']) : $this->vars['suresiz'];

            $val['status_name'] = ($val['used']) ? $this->vars['kullanildi'] : $this->vars['kullanilmadi'];

            switch($val['d_type']) {
                case '0':
                    $val['d_type_name_p'] = '%';
                    $val['d_type_name'] = '';
                    break;
                case '1':
                    $val['d_type_name_p'] = $this->ActiveCurrency['prefix_symbol'];
                    $val['d_type_name'] = $this->ActiveCurrency['suffix_symbol'];
                    break;
            }
            $coupons[$key] = $val;
        }

        return $coupons;
    }

    public function wishlist() {

        $EXCHANGE = [];

        $data = $this->db->from('users_favorites')->where('user_id',Session::fetch('user','id'))->select("product_id")->run();
        if(!$data) return false;

        foreach($data as $ids) $IDS[] = $ids['product_id'];
        if(!$IDS) return false;

        $auctions = $this->db->from('auctions a')->join('products p','a.id = p.auction_id','LEFT')
            ->select('a.*')
            ->in('p.id',$IDS)
            ->in('a.status',[1,2,3])
            ->groupby('a.id')
            ->orderby('a.end_time','DESC')->run();


        foreach($auctions as $key => $auction) {

            $auction['end_date'] = strftime("%d %B, %H:%M",$auction['end_time']);
            $auction['start_live'] = date("d.m.Y H:i",strtotime("+2 minutes",$auction['end_time']));
            $auction['date'] = date("Y/m/d H:i:s",strtotime("+2 minutes",$auction['end_time']));
            $auction['url'] = BASEURL . parent::get_seo_link('auctions',$auction['id']);
            $auction['live_url'] = BASEURL . parent::get_seo_link('auctions',$auction['id'],'live');
            if($auction['status'] == 2) $auction['url'] = $auction['live_url'];

            $results[$auction['id']]['info'] = $auction;
            $results[$auction['id']]['lost'] = $this->db->from('products')->where('auction_id',$auction['id'])->select('COUNT(id) total')->in('id',$IDS)->where('sale',Session::fetch('user','id'),'!=')->total();
            $results[$auction['id']]['win'] = $this->db->from('products')->where('auction_id',$auction['id'])->select('COUNT(id) total')->in('id',$IDS)->where('sale',Session::fetch('user','id'))->total();
        }

        return $results;
    }

    public function peylerim() {

        $EXCHANGE = [];
        $data = $this->db->from('offers')->where('user_id',Session::fetch('user','id'))->select("product_id")->groupby('product_id')->run();
        if(!$data) return false;

        foreach($data as $ids) $IDS[] = $ids['product_id'];
        if(!$IDS) return false;

        $auctions = $this->db->from('auctions a')->join('products p','a.id = p.auction_id','LEFT')
            ->select('a.*')
            ->in('p.id',$IDS)
            ->in('a.status',[1,2,3])
            ->groupby('a.id')
            ->orderby('a.end_time','DESC')->run();


        foreach($auctions as $key => $auction) {

            $auction['end_date'] = strftime("%d %B, %H:%M",$auction['end_time']);
            $auction['start_live'] = date("d.m.Y H:i",strtotime("+2 minutes",$auction['end_time']));
            $auction['date'] = date("Y/m/d H:i:s",strtotime("+2 minutes",$auction['end_time']));
            $auction['url'] = BASEURL . parent::get_seo_link('auctions',$auction['id']);
            $auction['live_url'] = BASEURL . parent::get_seo_link('auctions',$auction['id'],'live');
            if($auction['status'] == 2) $auction['url'] = $auction['live_url'];

            $results[$auction['id']]['info'] = $auction;
            $results[$auction['id']]['lost'] = $this->db->from('products')->where('auction_id',$auction['id'])->select('COUNT(id) total')->in('id',$IDS)->where('sale',Session::fetch('user','id'),'!=')->total();
            $results[$auction['id']]['win'] = $this->db->from('products')->where('auction_id',$auction['id'])->select('COUNT(id) total')->in('id',$IDS)->where('sale',Session::fetch('user','id'))->total();
        }

        return $results;

        /*        $products = $this->db->from('auctions a')->select('p.id,p.sku,p.category_id,p.brand_id,p.auction_id, p.shortdetail, p.name,p.price,p.old_price,p.currency_id, p.sale, p.status,
                    c.code currency_code, o.total peyTotal, f.total followTotal, pi.picture')
                    ->join('products p','p.auction_id = a.id','LEFT')
                ->join('currencys c','c.id = p.currency_id','LEFT')
                ->join('total_offers o','o.product_id = p.id','LEFT')

                ->join('products_images pi','pi.product_id = p.id','LEFT')
                ->join('total_favorites f','f.product_id = p.id','LEFT')
                    ->in('p.id',array_filter(explode(',',$data['ids'])))
                    ->in('p.status',[1,2,3])
                    ->groupby('p.auction_id')
                    ->orderby('a.status ASC, a.end_time','ASC')->run();

                foreach($products as $value) {
                    $auction = $this->db->from('auctions')->where('id',$value['auction_id'])->first();
                    if($auction['id']) {

                        $auction['end_date'] = strftime("%d %B, %H:%M",$auction['end_time']);
                        $auction['start_live'] = date("d.m.Y H:i",strtotime("+2 minutes",$auction['end_time']));
                        $auction['date'] = date("Y/m/d H:i:s",strtotime("+2 minutes",$auction['end_time']));
                        $auction['url'] = BASEURL . parent::get_seo_link('auctions',$auction['id']);
                        $auction['live_url'] = BASEURL . parent::get_seo_link('auctions',$auction['id'],'live');
                        if($auction['status'] == 2) $auction['url'] = $auction['live_url'];

                        $value['url'] = self::get_seo_link('products',$value['id']);
                        $value['brand'] = $this->db->from('brands')->where('id',$value['brand_id'])->first();
                        if(Session::fetch('user','id')) $value['me_follow'] = $this->db->from('users_favorites')->select('COUNT(id) total')->where('product_id',$value['id'])->where('user_id',Session::fetch('user','id'))->total();
                        $value['lotown'] = ($value['sale'] == Session::fetch('user','id')) ? 1 : 0;
                        if(Session::fetch('user','id')) $value['pey'] = $this->db->from('offers')->select('id,price')->where('product_id',$value['id'])->where('user_id',Session::fetch('user','id'))->orderby('price','DESC')->first();
                        self::calcProductPrice($value,$EXCHANGE);

                        $results[$auction['id']]['info'] = $auction;
                        if($value['lotown']) $results[$auction['id']]['win'] += 1;
                        else $results[$auction['id']]['lost'] += 1;
                        $results[$auction['id']]['records'][] = $value;
                    }
                }*/
    }

    public function winners() {

        $EXCHANGE = [];

        $data = $this->db->from('products')->where('sale',Session::fetch('user','id'))->select("auction_id")->run();
        if(!$data) return false;

        foreach($data as $ids) $IDS[] = $ids['auction_id'];
        if(!$IDS) return false;

        $auctions = $this->db->from('auctions')
            ->in('id',$IDS)
            ->in('status',[1,2,3])
            ->orderby('end_time','DESC')->run();
        foreach($auctions as $auction) {
            if($auction['id']) {

                $auction['end_date'] = strftime("%d %B, %H:%M",$auction['end_time']);
                $auction['start_live'] = date("d.m.Y H:i",strtotime("+2 minutes",$auction['end_time']));
                $auction['date'] = date("Y/m/d H:i:s",strtotime("+2 minutes",$auction['end_time']));
                $auction['url'] = BASEURL . parent::get_seo_link('auctions',$auction['id']);
                $auction['live_url'] = BASEURL . parent::get_seo_link('auctions',$auction['id'],'live');
                if($auction['status'] == 2) $auction['url'] = $auction['live_url'];

                $results[$auction['id']]['info'] = $auction;
            }
        }

        return $results;
    }

    public function addwish(){
        $id = intval("0".$this->input->post('id'));
        $json['cls'] = 'warning';
        $json['head'] = $this->vars['hatali'];
        $json['message'] = $this->vars['uyegirisiyapin'];

        if(Session::fetch('user','id')) {
            $val = $this->db->from('users_favorites')->where('user_id',Session::fetch('user','id'))->where('product_id',$id)->first();
            if(!$val) {
                $this->db->insert('users_favorites')->set([
                    'user_id' => Session::fetch('user','id'),
                    'product_id' => $id
                ]);
                $json['cls'] = 'success';
                $json['add'] = '1';
                $json['head'] = $this->vars['basarili'];
                $json['message'] = $this->vars['basarilistede'];
            }
            else {
                $this->db->delete('users_favorites')->where('user_id',Session::fetch('user','id'))->where('product_id',$id)->done();
                $json['cls'] = 'success';
                $json['head'] = $this->vars['basarili'];
                $json['message'] = $this->vars['basarilistecikti'];
            }
        }

        $adet = $this->db->from('users_favorites')->select('COUNT(id) total')->where('product_id',$id)->total();
        $json['adet'] = $adet;
        return $json;

    }

    public function removewish($id) {
        $data = $this->db->from('users_favorites')->where('user_id',Session::fetch('user','id'))->where('id',$id)->first();
        if(!$data)  {
            $this->setMessage($this->vars['gecersizislem'],'danger');
            return false;
        }
        $this->db->delete('users_favorites')->where('user_id',Session::fetch('user','id'))->where('id',$id)->done();
        $this->setMessage($this->vars['islembasarili'],'success');
    }

    public function forgetpassword($pass){
        if($pass['username']) {
            $val = $this->db->from('users')->where('username',$pass['username'])
                ->where('status',1)
                ->first();
            if($val) {
                $newpass = rand(10000,99999);

                $search = ['{{USERNAME}}','{{PASSWORD}}','{{NAME}}','{{SURNAME}}','{{LOGIN_URL}}'];
                $replace = [$val['username'],$newpass,$val['name'],$val['surname'],BASEURL .'user/login'];

                $this->settings['forgetpassword_header'] = str_replace($search,$replace,$this->settings['forgetpassword_header']);
                $this->settings['forgetpassword_html'] = str_replace($search,$replace,$this->settings['forgetpassword_html']);

                if($this->settings['forgetpassword_header'])
                    sendMail($val['username'],$this->settings['forgetpassword_header'],$this->settings['forgetpassword_html']);

                $this->db->update('users')->where('id',$val['id'])->set([
                    'password' => md5($newpass)
                ]);

                $this->setMessage($this->vars['sifregonderidi'],'success');
            }
            else
            {
                $this->setMessage($this->vars['loginhatali'],'danger');
            }
        } else $this->setMessage($this->vars['eksikbilgi'],'danger');
    }

    public function twitter(){
        if($t = $this->input->post('t')) {
            if($t['twitter'] && $t['email']) {
                $varm = $this->db->from('users')->where('twitter',$t['twitter'])->where('username',$t['email'])->first();
                if($varm['id']) {
                    if($varm['status'] == 1) {
                        Session::set('user',$varm);
                    } else {
                        return $this->vars['uyelikonaylidegil'];
                    }
                } else {
                    $city = $this->db->from('citys')->where('name',$t['city'])->first();

                    $group = $this->db->from('users_groups')->orderby('main','DESC')->first();

                    $name = explode(' ',$t['name']);
                    $surname = array_pop($name);

                    $arr = [
                        'username' => $t['email'],
                        'name' => implode(' ',$name),
                        'surname' => $surname,
                        'twitter' => $t['twitter'],
                        'city_id' => $city['id'],
                        'country_id' => $city['country_id'],
                        'create_time' => time(),
                        'create_ip' => USER_IP,
                        'status' => 1,
                        'group_id' => $group['id'],
                    ];

                    $this->db->insert('users')->set($arr);
                    $arr['id'] = $this->db->lastId();
                    Session::set('user',$arr);
                }
            }
        }
    }

    public function facebook(){
        if($t = $this->input->post('f')) {
            if($t['facebook'] && $t['email']) {
                $varm = $this->db->from('users')->where('facebook',$t['facebook'])->where('username',$t['email'])->first();
                if($varm['id']) {
                    if($varm['status'] == 1) {
                        Session::set('user',$varm);
                    } else {
                        return $this->vars['uyelikonaylidegil'];
                    }
                } else {

                    $group = $this->db->from('users_groups')->orderby('main','DESC')->first();

                    $arr = [
                        'username' => $t['email'],
                        'name' => $t['name'],
                        'surname' => $t['surname'],
                        'facebook' => $t['facebook'],
                        'create_time' => time(),
                        'create_ip' => USER_IP,
                        'status' => 1,
                        'group_id' => $group['id'],
                    ];

                    $this->db->insert('users')->set($arr);
                    $arr['id'] = $this->db->lastId();
                    Session::set('user',$arr);
                }
            }
        }
    }

    public function alarm() {

        $values = $this->db->from('users_alarm')->where('user_id',Session::fetch('user','id'))->orderby('id','ASC')->run();

        foreach($values as $key => $val) {
            $val['keyword'] = $keyword = ($val['ep']) ? '"'.$val['keyword'].'"' : $val['keyword'];
            $search = [
                'q'=>$keyword,
                'active'=>1,
            ];
            if($val['min_price']!=0.00) $search['min'] = $val['min_price'];
            if($val['max_price']!=0.00) $search['max'] = $val['max_price'];
            $val['search'] = http_build_query($search);
            $val['status_name'] = ($val['status']) ? $this->vars['aktif'] : $this->vars['pasif'];
            $values[$key] = $val;
        }
        return $values;
    }

    public function newalarm($id) {
        $data = $this->db->from('users_alarm')->where('user_id',Session::fetch('user','id'))->where('id',$id)->first();

        return $data;
    }

    public function remalart($id) {
        $data = $this->db->from('users_alarm')->where('user_id',Session::fetch('user','id'))->where('id',$id)->first();
        if($data) {
            $this->db->delete('users_alarm')->where('id',$id)->done();
        }
    }

    public function savealarm($s) {

        if($s['keyword']) {
            if(!$s['ep']) $s['ep'] = 0;
            if(!$s['status']) $s['status'] = 0;
            if(!$s['min_price']) $s['min_price'] = 0;
            if(!$s['max_price']) $s['max_price'] = 0;

            if(!$s['name']) $s['name'] = $s['keyword'];
            $s['user_id'] = Session::fetch('user','id');

            if($ids = $this->input->post('ids')) {
                $before = $this->db->from('users_alarm')->where('user_id',$s['user_id'])->where('id',$ids)->first();
                if($before) {
                    $this->db->update('users_alarm')->where('id',$ids)->set($s);
                }
            } else {
                $before = $this->db->from('users_alarm')->where('user_id',$s['user_id'])->where('keyword',$s['keyword'])->first();
                if(!$before['id']) {
                    $s['create_time'] = time();

                    $this->db->insert('users_alarm')->set($s);

                } else  {
                    $this->setMessage(sprintf($this->vars['kelimedahaonce'],$s['keyword']),'danger');
                }
            }
        }
    }


    public function selling() {

        $EXCHANGE = [];
        $data = $this->db->from('products')->where('seller',Session::fetch('user','id'))->select("auction_id")->run();
        if(!$data) return false;

        foreach($data as $ids) $IDS[] = $ids['auction_id'];
        if(!$IDS) return false;

        $auctions = $this->db->from('auctions')
            ->in('id',$IDS)
            ->in('status',[1,2,3])
            ->orderby('end_time','DESC')->run();
        foreach($auctions as $auction) {
            if($auction['id']) {

                $auction['end_date'] = strftime("%d %B, %H:%M",$auction['end_time']);
                $auction['start_live'] = date("d.m.Y H:i",strtotime("+2 minutes",$auction['end_time']));
                $auction['date'] = date("Y/m/d H:i:s",strtotime("+2 minutes",$auction['end_time']));
                $auction['url'] = BASEURL . parent::get_seo_link('auctions',$auction['id']);
                $auction['live_url'] = BASEURL . parent::get_seo_link('auctions',$auction['id'],'live');
                if($auction['status'] == 2) $auction['url'] = $auction['live_url'];


                $results[$auction['id']]['info'] = $auction;
                $results[$auction['id']]['lost'] = $this->db->from('products')->where('auction_id',$auction['id'])->select('COUNT(id) total')->where('seller',Session::fetch('user','id'))->where('sale',1,'<')->total();
                $results[$auction['id']]['win'] = $this->db->from('products')->where('auction_id',$auction['id'])->select('COUNT(id) total')->where('seller',Session::fetch('user','id'))->where('sale',0,'>')->total();
            }
        }

        return $results;
    }

    public function auctiondetail($id, $type = null) {

        switch ($type) {
            case 'winner':
                $WHERE[] = ['p.sale',Session::fetch('user','id'),'IN','&&'];
                break;
            case 'sell':
                $WHERE[] = ['p.seller',Session::fetch('user','id'),'IN','&&'];
                break;
            case 'pey':
                $data = $this->db->from('offers o')
                    ->join('products p','p.id = o.product_id','LEFT')
                    ->where('o.user_id',Session::fetch('user','id'))
                    ->where('p.auction_id',$id)
                    ->select("o.product_id")->groupby('o.product_id')->run();

                foreach((array)$data as $ids) $IDS[] = $ids['product_id'];

                if($IDS) $WHERE[] = ['p.id',$IDS,'IN','&&'];
                break;
            case 'wish':
                $data = $this->db->from('users_favorites o')
                    ->join('products p','p.id = o.product_id','LEFT')
                    ->where('o.user_id',Session::fetch('user','id'))
                    ->where('p.auction_id',$id)
                    ->select("o.product_id")->groupby('o.product_id')->run();

                foreach((array)$data as $ids) $IDS[] = $ids['product_id'];

                if($IDS) $WHERE[] = ['p.id',$IDS,'IN','&&'];
                break;

        }

        $EXCHANGE = [];
        $this->db->from('products p')->select('p.id,p.sku,p.category_id,p.brand_id,p.auction_id, p.shortdetail, p.name,p.price,p.old_price,p.currency_id, p.sale, p.status,
            c.code currency_code, o.total offers, f.total follows,
        (SELECT picture FROM products_images WHERE product_id=p.id ORDER BY `rows` ASC, id ASC LIMIT 1) picture')
            ->join('currencys c','c.id = p.currency_id','LEFT')
            ->join('total_offers o','o.product_id = p.id','LEFT')
            ->join('total_favorites f','f.product_id = p.id','LEFT');
        if($WHERE) {
            foreach($WHERE as $where) {
                $this->db->where($where[0],$where[1],$where[2],$where[3]);
            }
        }

        $products = $this->db->where('p.auction_id',$id)->in('p.status',[1,2,3])->orderby('p.sku','ASC')->run();
        foreach($products as $value) {
            $auction = $this->db->from('auctions')->where('id',$value['auction_id'])->first();
            if($auction['id']) {
                $value['cls'] = 'lost';
                if($value['sale'] == Session::fetch('user','id')) $value['cls'] = 'win';
                $auction['end_date'] = strftime("%d %B, %H:%M",$auction['end_time']);
                $auction['start_live'] = date("d.m.Y H:i",strtotime("+2 minutes",$auction['end_time']));
                $auction['date'] = date("Y/m/d H:i:s",strtotime("+2 minutes",$auction['end_time']));
                $auction['url'] = BASEURL . parent::get_seo_link('auctions',$auction['id']);
                $auction['live_url'] = BASEURL . parent::get_seo_link('auctions',$auction['id'],'live');
                if($auction['status'] == 2) $auction['url'] = $auction['live_url'];

                $value['url'] = self::get_seo_link('products',$value['id']);
                $value['brand'] = $this->db->from('brands')->where('id',$value['brand_id'])->first();
                if(Session::fetch('user','id')) $value['pey'] = $this->db->from('offers')->select('id,price')->where('product_id',$value['id'])->where('user_id',Session::fetch('user','id'))->orderby('price','DESC')->first();
                $value['lotown'] = ($value['sale'] == Session::fetch('user','id')) ? 1 : 0;
                self::calcProductPrice($value,$EXCHANGE);

                $results[$auction['id']]['info'] = $auction;
                if($value['sale']) $results[$auction['id']]['win'] += 1;
                else $results[$auction['id']]['lost'] += 1;
                $results[$auction['id']]['records'][] = $value;
            }
        }

        if($results) ksort($results,SORT_DESC);
        return $results;
    }

    public function addlive(){
        $id = intval("0".$this->input->post('id'));
        $json['cls'] = 'warning';
        $json['head'] = $this->vars['hatali'];
        $json['message'] = $this->vars['uyegirisiyapin'];

        if(Session::fetch('user','id')) {
            $val = $this->db->from('users_live')->where('user_id',Session::fetch('user','id'))->where('product_id',$id)->first();
            if(!$val) {
                $this->db->insert('users_live')->set([
                    'user_id' => Session::fetch('user','id'),
                    'product_id' => $id
                ]);
                $json['cls'] = 'success';
                $json['add'] = '1';
                $json['head'] = $this->vars['basarili'];
                $json['message'] = $this->vars['basarilistede'];
            }
            else {
                $this->db->delete('users_live')->where('user_id',Session::fetch('user','id'))->where('product_id',$id)->done();
                $json['cls'] = 'success';
                $json['head'] = $this->vars['basarili'];
                $json['message'] = $this->vars['basarilistecikti'];
            }
        }
        return $json;
    }

}