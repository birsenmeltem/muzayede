<?php
class Pages extends Controller
{
    public function __construct()
    {
        Auth::handle();
        parent::__construct();
        $this->view->assign('active_menu','pages');
    }

    public function main($yer=0,$page=0){

        global $ProductStatus;

        if(!in_array($yer,[0,1,2])) $yer = 0;

        $data = $this->model->lists($yer,$page);
        $this->view->assign('info',$this->model->showMessages());

        $this->view->assign('Status',$ProductStatus);
        $this->view->assign('yer',$yer);
        $this->view->assign('data',$data['result']);
        $this->view->assign('pagination',$data['pagi']);
        $this->view->assign('content',$this->view->draw('pages',true));
        $this->view->draw('index');
        return true;
    }

    public function add($yer=0)
    {
        global $ProductStatus;

        if($p = $this->input->post('p')) {
            $id = $this->model->add();
            if($id) parent::redirect('pages/edit/'.$id);
        }

        $this->view->assign('data',['yer'=>$yer]);
        $this->view->assign('Status',$ProductStatus);
        $this->view->assign('content',$this->view->draw('pages_edit',true));
        $this->view->draw('index');
        return true;
    }

    public function edit($id=0)
    {
        global $ProductStatus;

        $data = $this->model->edit($id);
        if(!$data) parent::goback();

        $this->view->assign('data',$data);
        $this->view->assign('Status',$ProductStatus);
        $this->view->assign('content',$this->view->draw('pages_edit',true));
        $this->view->draw('index');
        return true;
    }

    public function remove($id=0)
    {
        if($id) {
            $this->model->remove($id);
        }
        parent::goback();
    }

    public function banners($page=0){

        global $ProductStatus;

        $data = $this->model->banners($page);
        $this->view->assign('info',$this->model->showMessages());

        $this->view->assign('Status',$ProductStatus);
        $this->view->assign('data',$data['result']);
        $this->view->assign('pagination',$data['pagi']);
        $this->view->assign('content',$this->view->draw('banners',true));
        $this->view->draw('index');
        return true;
    }

    public function banner_add()
    {
        global $ProductStatus;

        $this->view->assign('Status',$ProductStatus);
        $this->view->draw('banners_edit');
        return true;
    }

    public function banner_edit($id=0)
    {
        global $ProductStatus;

        $data = $this->model->banner_edit($id);
        if(!$data) parent::redirect('banners');

        $this->view->assign('data',$data);
        $this->view->assign('Status',$ProductStatus);
        $this->view->draw('banners_edit');
        return true;
    }

    public function banner_remove($id=0)
    {
        if($id) {
            $this->model->banner_remove($id);
        }
        parent::goback();
    }
}