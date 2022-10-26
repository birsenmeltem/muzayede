<?php
class Reports extends Controller
{
    public function __construct() {
        Auth::handle();
        parent::__construct();
        $this->view->assign('active_menu','reports');
    }

    public function peys() {

        $data = $this->model->peys();

        $this->view->assign('f',$this->input->post('f'));
        $this->view->assign('auctions',$this->model->get_auctions());
        $this->view->assign('data',$data);
        $this->view->assign('content',$this->view->draw('reports_pey',true));
        $this->view->draw('index');
        return true;
    }

    public function peys_print() {
        $_POST['f'] = $this->input->get('f');
        $data = $this->model->peys();

        $this->view->assign('data',$data);
        $this->view->assign('f',$_POST['f']);
        $this->view->draw('reports_pey_print');
        exit;
    }

    public function mostpeys() {

        $data = $this->model->mostpeys();

        $this->view->assign('f',$this->input->post('f'));
        $this->view->assign('auctions',$this->model->get_auctions());
        $this->view->assign('data',$data);
        $this->view->assign('content',$this->view->draw('reports_mostpey',true));
        $this->view->draw('index');
        return true;
    }

    public function mostpeys_print() {
        $_POST['f'] = $this->input->get('f');
        $data = $this->model->mostpeys();

        $this->view->assign('data',$data);
        $this->view->assign('f',$_POST['f']);
        $this->view->draw('reports_mostpey_print');
        exit;
    }

    public function buyer() {

        $data = $this->model->buyer();

        $this->view->assign('f',$this->input->post('f'));
        $this->view->assign('auctions',$this->model->get_auctions());
        $this->view->assign('data',$data);
        $this->view->assign('content',$this->view->draw('reports_buyer',true));
        $this->view->draw('index');
        return true;
    }

    public function buyer_print() {
        $_POST['f'] = $this->input->get('f');
        $data = $this->model->buyer();

        $this->view->assign('data',$data);
        $this->view->assign('f',$_POST['f']);
        $this->view->draw('reports_buyer_print');
        exit;
    }

    public function mostbuyer() {

        $data = $this->model->mostbuyer();

        $this->view->assign('f',$this->input->post('f'));
        $this->view->assign('auctions',$this->model->get_auctions());
        $this->view->assign('data',$data);
        $this->view->assign('content',$this->view->draw('reports_mostbuyer',true));
        $this->view->draw('index');
        return true;
    }

    public function mostbuyer_print() {
        $_POST['f'] = $this->input->get('f');
        $data = $this->model->mostbuyer();

        $this->view->assign('data',$data);
        $this->view->assign('f',$_POST['f']);
        $this->view->draw('reports_mostbuyer_print');
        exit;
    }

    public function seller() {

        $data = $this->model->seller();

        $this->view->assign('f',$this->input->post('f'));
        $this->view->assign('auctions',$this->model->get_auctions());
        $this->view->assign('data',$data);
        $this->view->assign('content',$this->view->draw('reports_seller',true));
        $this->view->draw('index');
        return true;
    }

    public function seller_print() {
        $_POST['f'] = $this->input->get('f');
        $data = $this->model->seller();

        $this->view->assign('data',$data);
        $this->view->assign('f',$_POST['f']);
        $this->view->draw('reports_seller_print');
        exit;
    }

    public function aresults() {

        $data = $this->model->aresults();

        $this->view->assign('f',$this->input->post('f'));
        $this->view->assign('auctions',$this->model->get_auctions(true));
        $this->view->assign('data',$data['data']);
        $this->view->assign('querystring',$data['querystring']);
        $this->view->assign('content',$this->view->draw('reports_aresult',true));
        $this->view->draw('index');
        return true;
    }

    public function aresult_buyer() {
        $_POST['f'] = $this->input->get('f');
        $data = $this->model->aresult_buyer();

        $this->view->assign('data',$data);
        $this->view->assign('f',$_POST['f']);
        $this->view->draw('reports_aresult_buyer');
        exit;
    }

    public function aresult_seller() {
        $_POST['f'] = $this->input->get('f');
        $data = $this->model->aresult_seller();

        $this->view->assign('data',$data);
        $this->view->assign('f',$_POST['f']);
        $this->view->draw('reports_aresult_seller');
        exit;
    }

    public function pro_buyer_pdf() {

        @header("Content-Type: application/json");

        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die;

        $id = $this->input->get('id');
        if($id) $this->model->pro_buyer_pdf($id);
    }

    public function pro_seller_pdf() {

        @header("Content-Type: application/json");

        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die;

        $id = $this->input->get('id');
        if($id) $this->model->pro_seller_pdf($id);
    }

    public function showseller($id) {
        $this->model->showseller($id);
    }

    public function auctions() {

        $data = $this->model->auctions();

        $this->view->assign('f',$this->input->post('f'));

        $this->view->assign('auctions',$this->model->get_auctions());
        $this->view->assign('data',$data);
        $this->view->assign('content',$this->view->draw('reports_auctions',true));
        $this->view->draw('index');
        return true;
    }


    public function buyerseller() {

        $data = $this->model->buyerseller();

        $this->view->assign('f',$this->input->post('f'));
        $this->view->assign('users',$this->model->db->from('users')->where('status',1)->orderby('id','ASC')->run());
        $this->view->assign('auctions',$this->model->get_auctions());
        $this->view->assign('data',$data);
        $this->view->assign('content',$this->view->draw('reports_buyerseller',true));
        $this->view->draw('index');
        return true;
    }

    public function buyerseller_excel() {

        $f = $this->input->get('f');
        if($f) $this->model->buyerseller_excel($f);
    }
}