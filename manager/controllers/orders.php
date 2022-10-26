<?php
class Orders extends Controller
{
    public function __construct()
    {
        Auth::handle();
        parent::__construct();
        $this->view->assign('active_menu','orders');
    }

    public function main($page=0){
        global $OrderStatus;

        unset($OrderStatus[-1]);
        $data = $this->model->lists($page);

        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('baslik','Tüm Siparişler');
        $this->view->assign('f',$this->input->get('f',true));
        $this->view->assign('Status',$OrderStatus);
        $this->view->assign('data',$data['result']);
        $this->view->assign('pagination',$data['pagi']);
        $this->view->assign('content',$this->view->draw('orders',true));
        $this->view->draw('index');
        return true;
    }

    public function waitings($page=0){
        global $OrderStatus;

        unset($OrderStatus[-1]);
        $_GET['f']['status'] = '0';

        $data = $this->model->lists($page);

        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('baslik','Bekleyen Siparişler');
        $this->view->assign('f',$this->input->get('f',true));
        $this->view->assign('Status',$OrderStatus);
        $this->view->assign('data',$data['result']);
        $this->view->assign('pagination',$data['pagi']);
        $this->view->assign('content',$this->view->draw('orders',true));
        $this->view->draw('index');
        return true;
    }

    public function notcomplete($page=0){
        global $OrderStatus;

        $_GET['f']['status'] = '-1';

        $data = $this->model->lists($page);

        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('baslik','Tamamlanmamış Siparişler');
        $this->view->assign('f',$this->input->get('f',true));
        $this->view->assign('Status',$OrderStatus);
        $this->view->assign('data',$data['result']);
        $this->view->assign('pagination',$data['pagi']);
        $this->view->assign('content',$this->view->draw('orders',true));
        $this->view->draw('index');
        return true;
    }

    public function edit($order_id = null){

        global $OrderStatus;

        $data = $this->model->edit($order_id);
        if(!$data) parent::redirect('orders');

        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('Status',$OrderStatus);
        $this->view->assign('data',$data);
        $this->view->assign('content',$this->view->draw('orders_edit',true));
        $this->view->draw('index');
        return true;
    }

    public function sendcargo($order_id = null){
        $this->model->sendcargo($order_id);

        $data = $this->model->edit($order_id);
        if(!$data) parent::redirect('orders');

        $this->view->assign('data',$data);
        $this->view->draw('cargo_print');
        exit;
    }

    public function cargo_remove($order_id = null){
        $this->model->cargo_remove($order_id);
        parent::goback();
    }

    public function cargo($page=0){
        global $CargoStatus;

        $data = $this->model->cargo($page);

        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('f',$this->input->get('f',true));
        $this->view->assign('Status',$CargoStatus);
        $this->view->assign('data',$data['result']);
        $this->view->assign('pagination',$data['pagi']);
        $this->view->assign('content',$this->view->draw('orders_cargo',true));
        $this->view->draw('index');
        return true;
    }

    public function invoice($order_id=0){
        $data = $this->model->edit($order_id);
        if(!$data) parent::redirect('orders');

        if(!$data['invoice_time']) {
            $this->model->CreateKasa($data);
            $this->model->db->update('orders')->where('id',$order_id)->set(['invoice_time'=>time()]);
        }
        $this->view->assign('data',$data);
        $this->view->draw('invoice_print');
        exit;
    }

    public function importcargo(){
        $this->view->draw('cargo_import');
        exit;
    }

    public function importcode(){
        $this->model->importcode();
    }

    public function remove($id){
        if($id) {
            $this->model->remove($id);
        }
        $this->goback();
    }

    public function sendsms($order_id){
        $data = $this->model->db->from('orders_address')->where('order_id',$order_id)->where('shipping',1)->first();
        $this->view->assign('data',$data);
        $this->view->draw('smssend');
    }

    public function sms() {
        $number = $this->input->post('number');
        $message = $this->input->post('message');
        if($number && $message) {
            sendSMS($number,$message);
        }
    }

    public function address_edit($id = null){
        $data = $this->model->address_edit($id);
        if(!$data) parent::redirect('orders');

        $this->view->assign('data',$data);
        $this->view->assign('countrys',$this->model->GetCountrys());
        $this->view->assign('citys',$this->model->get_citys($data['country_id']));
        $this->view->draw('orders_address_edit');
        exit;
    }

    public function parasut($auction_id, $user_id, $tip =0) {
        $this->model->parasut($auction_id, $user_id, $tip);
    }
}