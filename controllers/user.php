<?php
class User extends Controller
{
    public function __construct() {
        parent::__construct();
    }

    public function login(){

        if(Session::fetch('user','id')) redirect();

        parent::set_meta_tags([
            'seo_title' => $this->model->vars['uyegirisi']
        ]);

        if($returnUrl = $this->input->get('returnUrl')) {
            if(filter_var($returnUrl,FILTER_VALIDATE_URL)) {
                Session::set('returnUrl',$returnUrl);
            }
        }

        if($returnUrl = Session::fetch('returnUrl')) {
            $this->view->assign('returnUrl',$returnUrl);
        }

        if($login = $this->input->post('login',true)) {
            $this->model->login($login);
        }

        $this->view->assign('basket',Session::fetch('CART'));
        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('data',$data);
        $this->view->assign('content',$this->view->draw('login',true));
        $this->view->draw('index');
        return;
    }

    public function register(){
        if(Session::fetch('user','id')) redirect();

        parent::set_meta_tags([
            'seo_title' => $this->model->vars['uyeol']
        ]);

        unset($_SESSION['smsCheck']);

        if($returnUrl = Session::fetch('returnUrl')) {
            $this->view->assign('returnUrl',$returnUrl);
        }

        if($register = $this->input->post('register',true)) {
            $this->model->register($register);
        }

        $this->view->assign('basket',Session::fetch('CART'));
        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('countrys',$this->model->GetCountrys());
        $this->view->assign('data',$data);
        $this->view->assign('content',$this->view->draw('register',true));
        $this->view->draw('index');
        return;
    }

    public function sms(){

        parent::set_meta_tags([
            'seo_title' => $this->model->vars['smsgir']
        ]);


        $data = $this->model->sms();

        $this->view->assign('mobile','******'.substr(Session::fetch('user','mobile'),-4));

        $this->view->assign('basket',Session::fetch('CART'));
        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('content',$this->view->draw('sms',true));
        $this->view->draw('index');
        return;
    }

    public function dashboard() {
        Auth::handle();

        parent::set_meta_tags([
            'seo_title' => $this->model->vars['hesabim']
        ]);

        $data = $this->model->dashboard();

        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('data',$data);
        $this->view->assign('countrys',$this->model->GetCountrys());
        if($data['country_id']) $this->view->assign('citys',$this->model->get_citys($data['country_id']));
        $this->view->assign('active_menu','dashboard');
        $this->view->assign('content',$this->view->draw('user_dashboard',true));
        $this->view->draw('index');
        return;
    }

    public function winners() {
        Auth::handle();

        parent::set_meta_tags([
            'seo_title' => $this->model->vars['kazandiklarim']
        ]);

        $data = $this->model->winners();

        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('data',$data);
        $this->view->assign('active_menu','winners');
        $this->view->assign('content',$this->view->draw('user_winners',true));
        $this->view->draw('index');
        return;
    }

    public function orders() {
        Auth::handle();

        parent::set_meta_tags([
            'seo_title' => $this->model->vars['siparislerim']
        ]);

        $data = $this->model->orders();

        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('data',$data);
        $this->view->assign('active_menu','orders');
        $this->view->assign('content',$this->view->draw('user_orders',true));
        $this->view->draw('index');
        return;
    }

    public function order_detail($id = null) {
        Auth::handle();

        parent::set_meta_tags([
            'seo_title' => $this->model->vars['siparislerim']
        ]);

        $data = $this->model->order_detail($id);
        if(!$data) redirect('user/orders');

        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('data',$data);
        $this->view->assign('active_menu','orders');
        $this->view->assign('content',$this->view->draw('user_order_detail',true));
        $this->view->draw('index');
        return;

    }

    public function editaddress($id = null) {
        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die;

        Auth::handle();

        $data = $this->model->editaddress($id);
        if(!$data) redirect('user/address');

        $this->view->assign('data',$data);
        $this->view->assign('type',$data['type']);
        $this->view->assign('countrys',$this->model->GetCountrys());
        $this->view->assign('citys',$this->model->get_citys($data['country_id']));
        $this->view->draw('modal_address');
        exit;
    }

    public function newaddress($type = 'shipping') {
        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die;

        Auth::handle();

        if(!in_array($type,['billing','shipping'])) $type = 'shipping';

        $this->view->assign('countrys',$this->model->GetCountrys());
        $this->view->assign('type',$type);
        $this->view->draw('modal_address');
        exit;
    }

    public function remaddress($id = null) {
        Auth::handle();

        $this->model->remaddress($id);
        parent::goback();
    }

    public function mainaddress($id = null) {
        Auth::handle();

        $this->model->mainaddress($id);
        parent::goback();
    }

    public function address() {
        Auth::handle();

        parent::set_meta_tags([
            'seo_title' => $this->model->vars['adresbilgileri']
        ]);

        $data = $this->model->address();

        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('data',$data);
        $this->view->assign('active_menu','address');
        $this->view->assign('content',$this->view->draw('user_address',true));
        $this->view->draw('index');
        return;
    }

    public function coupons() {
        Auth::handle();

        parent::set_meta_tags([
            'seo_title' => $this->model->vars['kuponlarim']
        ]);

        $data = $this->model->coupons();

        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('data',$data);
        $this->view->assign('active_menu','coupons');
        $this->view->assign('content',$this->view->draw('user_coupons',true));
        $this->view->draw('index');
        return;
    }

    public function wishlist() {
        Auth::handle();

        parent::set_meta_tags([
            'seo_title' => $this->model->vars['favorilistem']
        ]);

        $data = $this->model->wishlist();

        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('data',$data);
        $this->view->assign('active_menu','wishlist');
        $this->view->assign('content',$this->view->draw('user_wishlist',true));
        $this->view->draw('index');
        return;
    }

    public function addwish() {
        @header("Content-Type: application/json");

        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die;

        if(intval("0".$_POST['id'])) {
            $result = $this->model->addwish();
            die(json_encode($result));
        }

    }
    public function removewish($id = null) {
        Auth::handle();

        $this->model->removewish($id);
        parent::goback();
    }

    public function logout() {
        Session::uset('user');
        setcookie('_user',false, time() - (60 * 60 * 24 * 360),'/');
        redirect();
    }

    public function forgetpassword(){
        if(Session::fetch('user','id')) redirect();

        parent::set_meta_tags([
            'seo_title' => $this->model->vars['sifremiunuttum']
        ]);

        if($pass = $this->input->post('pass',true)) {
            $this->model->forgetpassword($pass);
        }

        $this->view->assign('basket',Session::fetch('CART'));
        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('data',$data);
        $this->view->assign('content',$this->view->draw('forgetpassword',true));
        $this->view->draw('index');
        return;
    }

    public function twitter(){
        echo "<script type=\"text/javascript\">";
        if($this->input->post('t')) {
            $result = $this->model->twitter();
            if($result) echo "window.opener.parent.alert('{$result}');";
            else echo 'window.opener.parent.top.location.reload();';
        }
        echo 'window.close();</script>';
    }

    public function facebook(){
        echo "<script type=\"text/javascript\">";
        if($this->input->post('f')) {
            $result = $this->model->facebook();
            if($result) echo "window.opener.parent.alert('{$result}');";
            else echo 'window.opener.parent.top.location.reload();';
        }
        echo 'window.close();</script>';
    }

    public function peylerim() {
        Auth::handle();

        parent::set_meta_tags([
            'seo_title' => $this->model->vars['peylerim']
        ]);

        $data = $this->model->peylerim();

        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('data',$data);
        $this->view->assign('active_menu','peylerim');
        $this->view->assign('content',$this->view->draw('user_peylerim',true));
        $this->view->draw('index');
        exit;
    }

    public function selling() {
        Auth::handle();

        parent::set_meta_tags([
            'seo_title' => $this->model->vars['satistakiurunler']
        ]);

        $data = $this->model->selling();

        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('data',$data);
        $this->view->assign('active_menu','selling');
        $this->view->assign('content',$this->view->draw('user_selling',true));
        $this->view->draw('index');
        return;
    }

    public function alarm() {
        Auth::handle();

        parent::set_meta_tags([
            'seo_title' => $this->model->vars['alarmsistemi']
        ]);

        $data = $this->model->alarm();

        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('data',$data);
        $this->view->assign('active_menu','alarm');
        $this->view->assign('content',$this->view->draw('user_alarm',true));
        $this->view->draw('index');
        return;
    }

    public function newalarm($id = null) {

        if($id) {
            $data = $this->model->newalarm($id);
        }

        $this->view->assign('data',$data);
        $this->view->draw('user_newalarm');
        exit;
    }

    public function savealarm() {

        if($s = $this->input->post('s')) {
            $this->model->savealarm($s);
        }
        $this->goback();
    }

    public function remalart($id=null) {

        if($id) {
            $this->model->remalart($id);
        }
        $this->goback();
    }

    public function auctiondetail($id = 0, $type = null) {

        if($id) {
            $data = $this->model->auctiondetail($id, $type);
            $this->view->assign('data',$data);
            $this->view->draw('auctiondetail');
            exit;
        }
    }

    public function addlive() {
        @header("Content-Type: application/json");

        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die;

        if(intval("0".$_POST['id'])) {
            $result = $this->model->addlive();
            die(json_encode($result));
        }

    }
}