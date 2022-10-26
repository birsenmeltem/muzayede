<?php
class Peyler extends Controller
{
    public function __construct()
    {
        Auth::handle();
        parent::__construct();
        $this->view->assign('active_menu','peyler');
    }

    public function main($page=0){
        global $Status;

        $data = $this->model->lists($page);
        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('Status',$Status);
        $this->view->assign('f',$_GET['f']);
        $this->view->assign('data',$data['result']);
        $this->view->assign('pagination',$data['pagi']);
        $this->view->assign('content',$this->view->draw('peyler',true));
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
}