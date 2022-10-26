<?php
class Products extends Controller
{
    public function __construct()
    {
        Auth::handle();
        parent::__construct();
        $this->view->assign('active_menu','products');
    }

    public function main($page=0){
        global $ProductStatus;

        $data = $this->model->lists($page);
        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('Cats',$this->model->catOptionList());
        $this->view->assign('Brands',$this->model->BrandList());
        $this->view->assign('Status',$ProductStatus);
        $this->view->assign('data',$data['result']);
        $this->view->assign('pagination',$data['pagi']);
        $this->view->assign('content',$this->view->draw('products',true));
        $this->view->draw('index');
        return true;
    }

    public function add(){
        global $ProductStatus;

        if($p = $this->input->post('p')) {
            $id = $this->model->add();
            if($id) parent::redirect('products/edit/'.$id);
        }

        $this->view->assign('Auctions',$this->model->get_auctions());
        $this->view->assign('Cats',$this->model->catOptionList());
        $this->view->assign('Brands',$this->model->brands());
        $this->view->assign('Currencys',$this->model->currencys());
        $this->view->assign('StockType',$this->model->stocktypes());
        $this->view->assign('Status',$ProductStatus);
        $this->view->assign('content',$this->view->draw('products_edit',true));
        $this->view->draw('index');
        return true;
    }

    public function edit($id = null){
        global $ProductStatus;

        $data = $this->model->edit($id);
        if(!$data) parent::redirect('products');

        $this->view->assign('Auctions',$this->model->get_auctions());
        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('Cats',$this->model->catOptionList(explode(',',$data['category_id'])));
        $this->view->assign('Brands',$this->model->brands());
        $this->view->assign('Currencys',$this->model->currencys());
        $this->view->assign('StockType',$this->model->stocktypes());
        $this->view->assign('Status',$ProductStatus);
        $this->view->assign('data',$data);
        $this->view->assign('content',$this->view->draw('products_edit',true));
        $this->view->draw('index');
        return true;
    }

    public function remove($id) {
        if($id) {
            $this->model->remove($id);
        }
        parent::goback();
    }

    public function uploadimage(){
        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die;

        $this->model->uploadimage();
    }

    public function removeimage(){
        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die;

        $this->model->removeimage();
    }

    public function picturerows(){
        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die;

        if($rows = $this->input->post('picrows'))
            $this->model->picturerows($rows);
    }

    public function addvariants($product_id = null){

        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die;

        $data = $this->model->addvariants($product_id);
        if(!$data) parent::redirect('products');

        $this->view->assign('data',$data);
        $this->view->draw('products_addvariants');
        exit;
    }

    public function saveprovariants(){
        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die;

        if($id = intval("0".$this->input->post('id')))
            $this->model->saveprovariants($id);
    }

    public function productsvariants(){
        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die;

        if($id = intval("0".$this->input->post('id'))) {
            $data = $this->model->edit($id);
            $this->view->assign('data',$data);
            $this->view->draw('products_variants');
        }
    }

    public function addmodelstock(){
        @header("Content-Type: application/json");

        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die;

        if($this->input->post('v') && $this->input->post('product_id')) {
            $this->model->addmodelstock();
        }
    }

    public function editmodelstock($id = null){
        @header("Content-Type: application/json");

        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die;

        $data = $this->model->editmodelstock($id);
        $this->view->assign('data',$data);
        $this->view->draw('products_variants_edit');
    }

    public function savemodelstock(){
        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die;

        if($id = intval("0".$this->input->post('id')))
            $this->model->savemodelstock($id);
    }

    public function removemodelstock(){
        @header("Content-Type: application/json");

        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die;

        if($this->input->post('id')) {
            $this->model->removemodelstock();
        }
    }

    public function variants($page=0){

        global $Status;

        $data = $this->model->variants($page);
        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('Status',$Status);
        $this->view->assign('data',$data['result']);
        $this->view->assign('pagination',$data['pagi']);
        $this->view->assign('content',$this->view->draw('variants',true));
        $this->view->draw('index');
        return true;
    }

    public function variant_add(){
        global $Status;

        $this->view->assign('catlist',$this->model->catOptionList());
        $this->view->assign('Status',$Status);
        $this->view->draw('variant_edit');
        return true;
    }

    public function variant_edit($id=0){
        global $Status;

        $data = $this->model->variant_edit($id);
        if(!$data) parent::redirect('variants');

        $this->view->assign('catlist',$this->model->catOptionList(explode(',',$data['category_id'])));
        $this->view->assign('data',$data);
        $this->view->assign('Status',$Status);
        $this->view->draw('variant_edit');
        return true;
    }

    public function variant_remove($id=0){
        if($id) {
            $this->model->variant_remove($id);
        }
        parent::goback();
    }

    public function variant_values($variant_id = 0,$page=0){

        $variant = $this->model->variant_edit($variant_id);
        if(!$variant) parent::redirect('variants');

        $data = $this->model->variant_values($variant_id,$page);
        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('variant',$variant);
        $this->view->assign('data',$data['result']);
        $this->view->assign('pagination',$data['pagi']);
        $this->view->assign('content',$this->view->draw('variant_values',true));
        $this->view->draw('index');
        return true;
    }

    public function variant_value_add($variant_id=0){
        $this->view->assign('data',['variant_id'=>$variant_id]);
        $this->view->draw('variant_value_edit');
        return true;
    }

    public function variant_value_edit($id=0){
        $data = $this->model->variant_value_edit($id);
        if(!$data) parent::redirect('variants');

        $this->view->assign('data',$data);
        $this->view->draw('variant_value_edit');
        return true;
    }

    public function variant_value_remove($id=0){
        if($id) {
            $this->model->variant_value_remove($id);
        }
        parent::goback();
    }

    public function stocktypes($page=0){

        $data = $this->model->stocktypes_list($page);
        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('data',$data['result']);
        $this->view->assign('pagination',$data['pagi']);
        $this->view->assign('content',$this->view->draw('stocktypes',true));
        $this->view->draw('index');
        return true;
    }

    public function stocktype_add(){
        $this->view->draw('stocktype_edit');
        return true;
    }

    public function stocktype_edit($id=0){
        $data = $this->model->stocktype_edit($id);
        if(!$data) parent::redirect('stocktypes');

        $this->view->assign('data',$data);
        $this->view->draw('stocktype_edit');
        return true;
    }

    public function stocktype_remove($id=0){
        if($id) {
            $this->model->stocktype_remove($id);
        }
        parent::goback();
    }

    public function changefield(){
        if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') die;

        if($pid = $this->input->post('pid'))
            $this->model->changefield($pid);
        exit;
    }


    public function fakeSeoProduct()
    {
        $this->model->fakeSeoProduct();
    }
}