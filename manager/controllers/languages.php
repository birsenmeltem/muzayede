<?php
class Languages extends Controller
{
    public function __construct()
    {
        Auth::handle();
        parent::__construct();
        $this->view->assign('active_menu','languages');
    }

    public function main($page=0){
        global $Status;

        $data = $this->model->lists($page);
        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('Status',$Status);
        $this->view->assign('data',$data['result']);
        $this->view->assign('pagination',$data['pagi']);
        $this->view->assign('content',$this->view->draw('langs',true));
        $this->view->draw('index');
        return true;
    }

    public function add()
    {
        global $Status;

        $this->view->assign('currencys',$this->model->currencys());
        $this->view->assign('Status',$Status);
        $this->view->draw('langs_edit');
        return true;
    }

    public function edit($id=0)
    {
        global $Status;

        $data = $this->model->get_record($id);
        if(!$data) parent::redirect('languages');

        $this->view->assign('currencys',$this->model->currencys());
        $this->view->assign('data',$data);
        $this->view->assign('Status',$Status);
        $this->view->draw('langs_edit');
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