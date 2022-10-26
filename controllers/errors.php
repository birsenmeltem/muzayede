<?php
class Errors extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function main() {}
    public function __call($key=null,$value=null) {
        @header("HTTP/1.0 404 Not Found",true,404);
        parent::set_meta_tags([
            'seo_title' => $this->model->vars['sayfabulunamadi']
        ]);
        $this->view->assign('noindex',true);
        $this->view->assign('content',$this->view->draw('404',true));
        $this->view->draw('index');
        die();
    }
}