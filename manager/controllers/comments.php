<?php
class Comments extends Controller
{
    public function __construct()
    {
        Auth::handle();
        parent::__construct();
        $this->view->assign('active_menu','comments');
    }

    public function main($page=0){
        global $CommentStatus;

        $data = $this->model->lists($page);
        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('Status',$CommentStatus);
        $this->view->assign('data',$data['result']);
        $this->view->assign('pagination',$data['pagi']);
        $this->view->assign('content',$this->view->draw('comments',true));
        $this->view->draw('index');
        return true;
    }

    public function approve($id) {
        $this->model->approve($id);
        parent::goback();
    }

    public function edit($id=0)
    {
        global $CommentStatus;

        $data = $this->model->get_record($id);
        if(!$data) parent::redirect('comments');

        $this->view->assign('data',$data);
        $this->view->assign('Status',$CommentStatus);
        $this->view->draw('comments_edit');
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