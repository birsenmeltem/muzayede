<?php
class Pages extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function view($id = null)
    {
        $data = $this->model->view($id);
        if(!$data) redirect();

        parent::set_meta_tags($data);

        $this->view->assign('info',$this->model->showMessages());

        $this->view->assign('data',$data);
        $this->view->assign('content',$this->view->draw('pages',true));
        $this->view->draw('index');
        return true;
    }
}