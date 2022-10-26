<?php
class Payments extends Controller
{
    public function __construct(){
        parent::__construct();
    }

    public function paytr(){
        $this->model->paytr();
    }

    public function paytr_success(){
        $this->model->paytr_success();
    }

    public function paytr_fail(){
        $this->model->paytr_fail();
    }

}