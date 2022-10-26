<?php
class Auctions extends Controller
{
    public function __construct()
    {
        Auth::handle();
        parent::__construct();
        $this->view->assign('active_menu','auctions');
    }

    public function main($page=0){
        global $Status;

        $data = $this->model->lists($page);
        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('Status',$Status);
        $this->view->assign('data',$data['result']);
        $this->view->assign('pagination',$data['pagi']);
        $this->view->assign('content',$this->view->draw('auctions',true));
        $this->view->draw('index');
        return true;
    }

    public function add()
    {
        global $Status;

        $this->view->assign('catlist',$this->model->catOptionList());
        $this->view->assign('Status',$Status);
        $this->view->draw('auctions_edit');
        return true;
    }

    public function edit($id=0)
    {
        global $Status;

        $data = $this->model->get_record($id);
        if(!$data) parent::redirect('auctions');

        $this->view->assign('catlist',$this->model->catOptionList([$data['category_id']]));
        $this->view->assign('data',$data);
        $this->view->assign('Status',$Status);
        $this->view->draw('auctions_edit');
        return true;
    }

    public function remove($id=0)
    {
        if($id) {
            $this->model->remove($id);
        }
        parent::goback();
    }

    public function excel() {

        if($this->input->post('auction_id')) {
            $auc_id = $this->model->excel();
            $this->view->assign('auction_id', $auc_id);
        }

        if($this->input->post('finish')) {
            $this->model->setMessage('Yükleme tamamlanmıştır !','success');
        }
        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('auctions',$this->model->get_auctions());
        $this->view->assign('content',$this->view->draw('auctions_excel',true));
        $this->view->draw('index');
        return true;

    }

    public function uploadimage(){
        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die;

        $this->model->uploadimage();
    }

    public function sellers($id=0)
    {
        global $Status;

        $data = $this->model->get_sellers($id);

        $this->view->assign('data',$data);
        $this->view->assign('Status',$Status);
        $this->view->draw('sellers_list');
        return true;
    }
}