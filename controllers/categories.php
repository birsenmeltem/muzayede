<?php
class Categories extends Controller
{
    public function __construct(){
        parent::__construct();
    }

    public function main() {redirect('/');}

    public function view($category_id = 0){
        $FILTER = $this->input->get_post('filter');
        if(!$FILTER) $FILTER = [];

        if($FILTER['category_id'] != $category_id)
        {
            unset($FILTER);
            Session::uset('filter');
        }

        $data = $this->model->view($category_id,$FILTER);
        if(!$data) redirect('/');

        parent::set_meta_tags($data);

        $this->view->assign('filter',$FILTER);
        $this->view->assign('data',$data);
        $this->view->assign('content',$this->view->draw('category',true));
        $this->view->draw('index');
    }

    public function ajax(){
        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die();

        $post = $this->input->post('filter',true);
        if(!$post) die;


        $data = $this->model->view($post['category_id'],$post);
        if($data) {
            $this->view->assign('data',$data);
            $this->view->draw('category_right_block');
        }
        return;
    }

    public function getshow() {

        $product_id = (int) $this->input->post('product_id',true);
        $type = $this->input->post('type',true);

        $data = $this->model->getshow($product_id);

        $this->view->assign('data',$data);
        $this->view->assign('type',$type);
        $this->view->draw('getshow');
        exit;

    }


}
