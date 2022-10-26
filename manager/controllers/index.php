<?php
class Index extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function main()
    {
        Auth::handle();

        $this->redirect('dashboard');
    }

    public function dashboard($page=1)
    {
        Auth::handle();

        $data = $this->model->dashboard();

        $this->view->assign('bgcolor',['success','danger','info','warning','brand']);
        $this->view->assign('color',['success','danger','info','warning','brand']);
        $this->view->assign('data',$data);
        $this->view->assign('sales',range(1,10));
        $this->view->assign('payments',range(1,7));
        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('content',$this->view->draw('dashboard',true));
        $this->view->draw('index');
    }

    public function login()
    {
        if($_POST['login'])
        {
            $result = $this->model->login();
            if($result) {
                $this->redirect('dashboard');
            }
            $this->view->assign('info',$this->model->showMessages());
        }
        $this->view->draw('login');
    }

    public function lostpass()
    {
        $this->view->assign('meta_title','Şifremi Unuttum?');

        if($_POST['username']) {
            $result = $this->model->lostpass();
        }
        $this->view->assign('info',$this->model->showMessages());
        $this->view->draw('lostpass');
        return true;
    }

    public function logout()
    {
        Session::uset('admin');
        $this->redirect('login');
    }

    public function get_citys() {

        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die;

        if($country_id = $this->input->post('country_id')) {
            $result = $this->model->get_citys($country_id);
            if(!$this->input->post('all'))
                echo '<option value="0">Seçiniz</option>';

            foreach($result as $r) {
                echo '<option value="'.$r['id'].'">'.$r['name'].'</option>';
            }
        }
    }
    public function changelang($id)
    {
        $this->model->changelang($id);
        $this->goback();
    }
}