<?php
class Products extends Controller
{
    public function __construct() {
        parent::__construct();
    }

    public function main() { redirect('/'); }

    public function view($product_id = 0)
    {
        $data = $this->model->view($product_id);
        if(!$data) redirect('/');

        parent::set_meta_tags($data);

        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('data',$data);
        $this->view->assign('content',$this->view->draw('product',true));
        $this->view->draw('index');
    }

    public function picture() {
        @header('Content-Type: application/json');
        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die();

        if($id = $this->input->post('id')) {
            $this->model->picture($id);
        }
    }
}
?>
