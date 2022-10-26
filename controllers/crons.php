<?php
class Crons extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function invoices()
    {
        $this->model->invoices();
    }

    public function currency_exchange()
    {
        $this->model->currency_exchange();
    }

    public function auctions() {
        $this->model->auctions();
    }

    public function auctions_info() {
        $this->model->auctions_info();
    }

    public function auctions_end() {
        $this->model->auctions_end();
    }

    public function auctions_end20() {
        $this->model->auctions_end20();
    }

    public function sendmail() {
        $this->model->sendmail();
    }

    public function sendsms() {
        $this->model->sendsms();
    }

    public function sellermail() {
        $this->model->sellermail();
    }

    public function buyermail() {
        $this->model->buyermail();
    }

    public function toplusms() {
        $this->model->toplusms();
    }

    public function dondur() {
        $this->model->dondur();
    }

    public function manuelbuyer($id=0,$userid=0, $sendemail = false) {
        $this->model->manuelbuyer($id,$userid, $sendemail);
    }

    public function manuelseller($id=0,$userid=0) {
        $this->model->manuelseller($id,$userid);
    }

    public function calcwallet($id){
        $this->model->calcbuyer($id);
        $this->model->calcseller($id);
    }

    public function checklot() {
        if($id = $this->input->post('id')) {
            $this->model->checklot();
        }
    }

    public function mesut() {
        $this->model->mesut();
    }
}