<?php
class Cargocompanys extends Controller
{
    public function __construct(){
        Auth::handle();
        parent::__construct();
        $this->view->assign('active_menu','cargo');
    }

    public function main($page=0){
        global $Status;

        $data = $this->model->lists($page);
        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('Status',$Status);
        $this->view->assign('data',$data['result']);
        $this->view->assign('pagination',$data['pagi']);
        $this->view->assign('content',$this->view->draw('cargocompanys',true));
        $this->view->draw('index');
        return true;
    }

    public function add(){
        global $Status;

        $this->view->assign('integrations',$this->model->integrations());
        $this->view->assign('Status',$Status);
        $this->view->draw('cargocompanys_edit');
        return true;
    }

    public function edit($id=0){
        global $Status;

        $data = $this->model->get_record($id);
        if(!$data) parent::redirect('cargocompanys');

        $this->view->assign('integrations',$this->model->integrations());
        $this->view->assign('data',$data);
        $this->view->assign('Status',$Status);
        $this->view->draw('cargocompanys_edit');
        return true;
    }

    public function remove($id=0){
        if($id) {
            $this->model->remove($id);
        }
        parent::goback();
    }

    public function zones() {
        $data = $this->model->zones();
        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('data',$data);
        $this->view->assign('content',$this->view->draw('zones',true));
        $this->view->draw('index');
        return true;
    }

    public function zone_add(){
        global $Status;

        $this->view->draw('zone_edit');
        return true;
    }

    public function zone_edit($id=0){
        global $Status;

        $data = $this->model->zone_edit($id);
        if(!$data) parent::redirect('cargocompanys/zones');

        $this->view->assign('data',$data);
        $this->view->draw('zone_edit');
        return true;
    }

    public function zone_remove($id=0){
        if($id) {
            $this->model->zone_remove($id);
        }
        parent::goback();
    }

    public function zone_calc($zone_id = null) {
        $data = $this->model->zone_calc($zone_id);
        if(!$data) parent::redirect('cargocompanys/zones');

        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('data',$data);
        $this->view->assign('content',$this->view->draw('zone_calc',true));
        $this->view->draw('index');
        return true;
    }

    public function zone_calc_add($zone_id = 0){
        $this->view->assign('cargo',$this->model->cargolist());
        $this->view->assign('data',['zone_id'=>$zone_id]);
        $this->view->draw('zone_calc_edit');
        return true;
    }

    public function zone_calc_edit($zone_calc_id = 0){
        $data = $this->model->zone_calc_edit($zone_calc_id);
        if(!$data) parent::redirect('cargocompanys/zones');

        $this->view->assign('cargo',$this->model->cargolist());
        $this->view->assign('data',$data);
        $this->view->draw('zone_calc_edit');
        return true;
    }

    public function zone_calc_remove($id=0){
        if($id) {
            $this->model->zone_calc_remove($id);
        }
        parent::goback();
    }

    public function zone_citys($zone_id = 0){

        $data = $this->model->zone_citys($zone_id);
        if(!$data) parent::redirect('cargocompanys/zones');

        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('countrys',$this->model->GetCountrys());
        $this->view->assign('data',$data);
        $this->view->assign('content',$this->view->draw('zone_city',true));
        $this->view->draw('index');
        return true;
    }

    public function zone_city_remove($id=0){
        if($id) {
            $this->model->zone_city_remove($id);
        }
        parent::goback();
    }
}