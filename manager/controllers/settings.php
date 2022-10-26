<?php
class Settings extends Controller
{
    public function __construct()
    {
        Auth::handle();
        parent::__construct();
        $this->view->assign('active_menu','settings');
    }

    public function main(){

        $data = $this->model->main();
        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('data',$data);
        $this->view->assign('content',$this->view->draw('settings',true));
        $this->view->draw('index');
        return true;
    }

    public function admins($page=0){
        global $Status;

        $data = $this->model->admins($page);
        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('Status',$Status);
        $this->view->assign('data',$data['result']);
        $this->view->assign('pagination',$data['pagi']);
        $this->view->assign('content',$this->view->draw('admins',true));
        $this->view->draw('index');
        return true;
    }

    public function admin_add()
    {
        global $Status;

        $this->view->assign('Status',$Status);
        $this->view->draw('admins_edit');
        return true;
    }

    public function admin_edit($id=0)
    {
        global $Status;

        $data = $this->model->admin_edit($id);
        if(!$data) parent::redirect('settings/admins');

        $this->view->assign('data',$data);
        $this->view->assign('Status',$Status);
        $this->view->draw('admins_edit');
        return true;
    }

    public function admin_remove($id=0)
    {
        if($id) {
            $this->model->admin_remove($id);
        }
        parent::goback();
    }

    public function currencys($page=0){
        global $Status;

        $data = $this->model->currencys($page);
        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('Status',$Status);
        $this->view->assign('data',$data['result']);
        $this->view->assign('pagination',$data['pagi']);
        $this->view->assign('content',$this->view->draw('currencys',true));
        $this->view->draw('index');
        return true;
    }

    public function currency_add()
    {
        global $Status;

        $this->view->assign('Status',$Status);
        $this->view->draw('currencys_edit');
        return true;
    }

    public function currency_edit($id=0)
    {
        global $Status;

        $data = $this->model->currency_edit($id);
        if(!$data) parent::redirect('settings/currencys');

        $this->view->assign('data',$data);
        $this->view->assign('Status',$Status);
        $this->view->draw('currencys_edit');
        return true;
    }

    public function currency_remove($id=0)
    {
        if($id) {
            $this->model->currency_remove($id);
        }
        parent::goback();
    }

    public function popup(){

        $data = $this->model->popup();
        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('data',$data);
        $this->view->assign('content',$this->view->draw('popup',true));
        $this->view->draw('index');
        return true;
    }

    public function coupons($page=0){
        global $Status;

        $data = $this->model->coupons($page);
        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('Status',$Status);
        $this->view->assign('data',$data['result']);
        $this->view->assign('pagination',$data['pagi']);
        $this->view->assign('content',$this->view->draw('coupons',true));
        $this->view->draw('index');
        return true;
    }

    public function coupon_add()
    {
        global $Status;

        $this->view->assign('Status',$Status);
        $this->view->draw('coupons_edit');
        return true;
    }

    public function coupon_remove($id=0)
    {
        if($id) {
            $this->model->coupon_remove($id);
        }
        parent::goback();
    }

    public function removewatermark()
    {
        $data = $this->model->main();
        if($data['watermark']) {
            @unlink('../data/uploads/'.$data['watermark']);
            $this->model->db->update('settings')->where('id',1)->set(['watermark'=>'']);
        }
        parent::goback();
    }

    public function removelogo()
    {
        $data = $this->model->main();
        if($data['logo']) {
            @unlink('../data/uploads/'.$data['logo']);
            $this->model->db->update('settings')->where('id',1)->set(['logo'=>'']);
        }
        parent::goback();
    }

    public function removefavicon()
    {
        $data = $this->model->main();
        if($data['favicon']) {
            @unlink('../data/uploads/'.$data['favicon']);
            $this->model->db->update('settings')->where('id',1)->set(['favicon'=>'']);
        }
        parent::goback();
    }
}