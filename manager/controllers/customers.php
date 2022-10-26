<?php
class Customers extends Controller
{
    public function __construct()
    {
        Auth::handle();
        parent::__construct();
        $this->view->assign('active_menu','customers');
    }

    public function main($page=0){
        global $Status;

        $data = $this->model->lists($page);
        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('groups',$this->model->grouplist());
        $this->view->assign('Status',$Status);
        $this->view->assign('f',$_GET['f']);
        $this->view->assign('data',$data['result']);
        $this->view->assign('pagination',$data['pagi']);
        $this->view->assign('content',$this->view->draw('customers',true));
        $this->view->draw('index');
        return true;
    }

    public function add()
    {
        global $Status;

        $this->view->assign('countrys',$this->model->GetCountrys());
        $this->view->assign('groups',$this->model->grouplist());
        $this->view->assign('Status',$Status);
        $this->view->draw('customers_edit');
        return true;
    }

    public function edit($id=0)
    {
        global $Status;

        $data = $this->model->get_record($id);
        if(!$data) parent::redirect('customers');

        $this->view->assign('countrys',$this->model->GetCountrys());
        $this->view->assign('citys',$this->model->get_citys($data['country_id']));
        $this->view->assign('groups',$this->model->grouplist());
        $this->view->assign('data',$data);
        $this->view->assign('Status',$Status);
        $this->view->draw('customers_edit');
        return true;
    }

    public function remove($id=0)
    {
        if($id) {
            $this->model->remove($id);
        }
        parent::goback();
    }

    public function go2user($id=0)
    {
        if($id) {
            $user = $this->model->db->from('users')->where('id',$id)->first();
            Session::set('user',$user);
            redirect(BASEURL);
        }
        parent::goback();
    }

    public function export()
    {
        $this->view->assign('countrys',$this->model->GetCountrys());
        $this->view->assign('groups',$this->model->grouplist());
        $this->view->assign('content',$this->view->draw('customers_export',true));
        $this->view->draw('index');
        return true;
    }

    public function download($d = null) {
        if($d) {
            $dosya = BASE . 'data/customers_export.txt';
            header('Content-type: application/octet-stream');
            header('Content-Disposition: attachment; filename="customers_export.txt"');
            readfile($dosya);
            exit;
        }
        @header("Content-Type: application/json");

        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die;

        $this->model->download();
        exit;
    }
}