<?php
class Payments extends Controller
{
    public function __construct()
    {
        Auth::handle();
        parent::__construct();
        $this->view->assign('active_menu','payments');
    }

    public function main() {parent::redirect('payments/pos');}

    public function pos(){
        global $Status;

        $data = $this->model->pos();
        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('Status',$Status);
        $this->view->assign('data',$data);
        $this->view->assign('content',$this->view->draw('pos',true));
        $this->view->draw('index');
        return true;

    }

    public function banks(){
        global $Status;

        $data = $this->model->banks();
        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('Status',$Status);
        $this->view->assign('data',$data);
        $this->view->assign('content',$this->view->draw('banks',true));
        $this->view->draw('index');
        return true;
    }

    public function bank_add(){
        $this->view->draw('banks_edit');
        return true;
    }

    public function bank_edit($id=0){
        $data = $this->model->bank_edit($id);
        if(!$data) parent::redirect('payments/banks');

        $this->view->assign('data',$data);
        $this->view->draw('banks_edit');
        return true;
    }

    public function bank_remove($id=0){
        if($id) {
            $this->model->bank_remove($id);
        }
        parent::goback();
    }

    public function installment($pos_id=0){
        global $Status;

        $data = $this->model->installment($pos_id);
        if(!$data) parent::redirect('payments/pos');

        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('Status',$Status);
        $this->view->assign('data',$data);
        $this->view->assign('content',$this->view->draw('installment',true));
        $this->view->draw('index');
        return true;
    }

    public function installment_add($pos_id){
        $this->view->assign('data',['vpos_id'=>$pos_id]);
        $this->view->draw('installment_edit');
        return true;
    }

    public function installment_edit($id=0){
        $data = $this->model->installment_edit($id);
        if(!$data) parent::redirect('payments/pos');

        $this->view->assign('data',$data);
        $this->view->draw('installment_edit');
        return true;
    }

    public function installment_remove($id=0){
        if($id) {
            $this->model->installment_remove($id);
        }
        parent::goback();
    }
}