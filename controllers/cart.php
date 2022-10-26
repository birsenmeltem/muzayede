<?php
class Cart extends Controller
{
    public function __construct(){
        parent::__construct();
    }

    public function main(){

        parent::set_meta_tags([
            'seo_title' => $this->model->vars['sepetim']
        ]);

        Session::uset('returnUrl');

        $data = $this->model->main();

        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('data',$data);
        $this->view->assign('content',$this->view->draw('cart',true));
        $this->view->draw('index');
        return;
    }

    public function addcart(){
        Session::uset('CART');
        if($this->input->post('ids')) {
            $result = $this->model->addcart();
            redirect('cart');
        }
        $this->goback();
    }

    public function updatecart(){
        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die;
        $this->model->updatecart();
        exit;
    }

    public function removecart(){
        @header("Content-Type: application/json");

        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die;

        $result = $this->model->removecart();
        die(json_encode($result));
    }

    public function cleancart(){
        Session::uset('CART');
        $this->model->setMessage($this->model->vars['sepetemizlendi'],'success');
        $this->goback();
    }

    public function cleancoupon(){
        Session::uset('coupon');
        $this->goback();
    }

    public function quickcart(){
        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die;

        $data = $this->model->quickcart();
        $this->view->assign('data',$data);
        $this->view->draw('quickcart');
        exit;
    }

    public function address(){
        Auth::handle();

        parent::set_meta_tags([
            'seo_title' => $this->model->vars['adressecimi']
        ]);

        Session::uset('address');
        Session::uset('shippingCompanyId');

        $data = $this->model->address();

        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('countrys',$this->model->GetCountrys());
        $this->view->assign('data',$data);
        $this->view->assign('cargoCompanys',$data['cargoCompanys']);
        $this->view->assign('content',$this->view->draw('cart_address',true));
        $this->view->draw('index');
        return;

        return true;
    }

    public function guest(){
        if(!Session::fetch('CART')) redirect('cart');

        parent::set_meta_tags([
            'seo_title' => $this->model->vars['uyeolmadandevamet']
        ]);

        $data = $this->model->guest();

        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('countrys',$this->model->GetCountrys());
        $this->view->assign('data',$data);
        $this->view->assign('cargoCompanys',$data['cargoCompanys']);
        $this->view->assign('content',$this->view->draw('cart_guest',true));
        $this->view->draw('index');
        return;
    }

    public function cargo_company(){
        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die;

        $data = $this->model->cargo_company();
        $this->view->assign('cargoCompanys',$data);
        $this->view->draw('cart_cargo_company');
        exit;
    }

    public function cart_summary(){
        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die;

        if(Session::fetch('CART')) {
            $cargo_company_id = $this->input->post('cargo_company_id') ?? Session::fetch('shippingCompanyId');
            if($cargo_company_id) {
                $city_id = $this->input->post('city_id');
                $address_id = $this->input->post('address_id');
                if(Session::fetch('address','ship_id')) $address_id = Session::fetch('address','ship_id');
                if($address_id) {
                    $a = $this->model->db->from('users_address')
                    ->where('id',$address_id)->select('city_id')
                    ->where('user_id',Session::fetch('user','id'))
                    ->first();
                    $city_id = $a['city_id'];
                }
            }

            $data = $this->model->main();
            if($city_id) $data['city_id'] = $city_id;
            if($cargo_company_id) $data['cargo_id'] = $cargo_company_id;

            $this->model->cart_summary($data);

            if($payment_type = $this->input->post('payment_type')) {
                switch($payment_type) {
                    case '3':
                        if($this->model->settings['kapidanakit_price'] != 0.00) {
                            $data['payment_name'] = $this->model->vars['kapidanakit'];
                            $data['payment_price'] = $this->model->settings['kapidanakit_price'];
                            $data['genel_total'] += $data['payment_price'];
                        }
                        break;
                    case '4':
                        if($this->model->settings['kapidakart_price'] != 0.00) {
                            $data['payment_name'] = $this->model->vars['kapidakart'];
                            $data['payment_price'] = $this->model->settings['kapidakart_price'];
                            $data['genel_total'] += $data['payment_price'];
                        }
                        break;
                }
            }

            $this->view->assign('data',$data);
            $this->view->draw('cart_summary');
        }
        exit;
    }

    public function checkout(){

        if(!Session::fetch('CART')) redirect('cart');

        parent::set_meta_tags([
            'seo_title' => $this->model->vars['odemeyap']
        ]);

        $data = $this->model->checkout();
        if(!$data) {
            redirect($_SERVER['HTTP_REFERER']);
        }

        $this->view->assign('user',Session::fetch('user'));

        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('data',$data);
        $this->view->assign('address',$this->model->get_checkout_address());
        $this->view->assign('banks',$this->model->get_payment_banks());
        $this->view->assign('card_month',range(1,12));
        $this->view->assign('card_year',range(date("Y"),date("Y",strtotime("+10 years"))));
        $this->view->assign('content',$this->view->draw('cart_checkout',true));
        $this->view->draw('index');
        return;

        return true;
    }

    public function payment(){
        if($payment = $this->input->post('payment')) {
            if(!$payment['payment_type']) {
                $this->model->setMessage($this->model->vars['odemetipiseciniz'],'danger');
                redirect('cart/checkout');
            }

            if($payment['payment_type'] == 2 && !$payment['payment_bank']) {
                $this->model->setMessage($this->model->vars['gecerlibankaseciniz'],'danger');
                redirect('cart/checkout');
            }


            $data = $this->model->payment($payment);
            if($data['id']) {
                redirect('cart/success/'.$data['code']);
            }

        }

        redirect('cart/');
    }

    public function success($code = null) {
        Auth::handle();

        if(!Session::fetch('CART')) redirect('cart');

        parent::set_meta_tags([
            'seo_title' => $this->model->vars['tebrikelr']
        ]);

        $data = $this->model->success($code);
        if(!$data) redirect('user/orders');

        $this->view->assign('totalCart',0);
        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('data',$data);
        $this->view->assign('content',$this->view->draw('cart_success',true));
        $this->view->draw('index');
        return;

    }

    public function get_citys(){
        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die;

        if($country_id = $this->input->post('country_id')) {
            $result = $this->model->get_citys($country_id);
            echo '<option value="">'.$this->model->vars['seciniz'].'</option>';
            foreach($result as $r) {
                echo '<option value="'.$r['id'].'">'.$r['name'].'</option>';
            }
        }
    }

}