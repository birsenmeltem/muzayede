<?php
class Categories extends Controller
{
    public function __construct()
    {
        Auth::handle();
        parent::__construct();
        $this->view->assign('active_menu','categories');
    }

    public function main($parent=0,$page=0){
        global $Status;

        if($parent) {
            $record = $this->model->get_record($parent);
            if(!$record) parent::redirect('categories');
        }
        $data = $this->model->lists($parent,$page);
        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('Status',$Status);
        $this->view->assign('parent',$parent);
        $this->view->assign('record',$record);
        $this->view->assign('data',$data['result']);
        $this->view->assign('pagination',$data['pagi']);
        $this->view->assign('content',$this->view->draw('categories',true));
        $this->view->draw('index');
        return true;
    }

    public function add($parent=0)
    {
        global $Status;

        $this->view->assign('catlist',$this->model->catOptionList([$parent]));
        $this->view->assign('data',['parent_id'=>$parent]);
        $this->view->assign('Status',$Status);
        $this->view->draw('categories_edit');
        return true;
    }

    public function edit($id=0)
    {
        global $Status;

        $data = $this->model->get_record($id);
        if(!$data) parent::redirect('categories');

        $this->view->assign('catlist',$this->model->catOptionList([$data['parent_id']],$data['id']));
        $this->view->assign('data',$data);
        $this->view->assign('Status',$Status);
        $this->view->draw('categories_edit');
        return true;
    }

    public function remove($id=0)
    {
        if($id) {
            $this->model->remove($id);
        }
        parent::goback();
    }
}