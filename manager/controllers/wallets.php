<?php
class Wallets extends Controller
{
    public function __construct()
    {
        Auth::handle();
        parent::__construct();
        $this->view->assign('active_menu','wallets');
    }

    public function main(){redirect('wallets/deposits');}

    public function deposits($page=0){
        global $Status;

        $data = $this->model->deposits($page);
        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('Status',$Status);
        $this->view->assign('data',$data['result']);
        $this->view->assign('pagination',$data['pagi']);
        $this->view->assign('content',$this->view->draw('wallets_deposits',true));
        $this->view->draw('index');
        return true;
    }

    public function withdrawals($page=0){
        global $Status;

        $data = $this->model->withdrawals($page);
        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('Status',$Status);
        $this->view->assign('data',$data['result']);
        $this->view->assign('pagination',$data['pagi']);
        $this->view->assign('content',$this->view->draw('wallets_withdrawals',true));
        $this->view->draw('index');
        return true;
    }

    public function payments($page=0){
        global $Status;

        $data = $this->model->payments($page);
        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('Status',$Status);
        $this->view->assign('data',$data['result']);
        $this->view->assign('pagination',$data['pagi']);
        $this->view->assign('content',$this->view->draw('wallets_payments',true));
        $this->view->draw('index');
        return true;
    }

    public function add($user_id = 0)
    {
        global $Status;

        $this->view->assign('userList',$this->model->userList());
        $this->view->assign('user_id',$user_id);
        $this->view->assign('Status',$Status);
        $this->view->draw('payments_edit');
        return true;
    }

    public function deposit_edit($id=0)
    {
        $data = $this->model->get_record($id);
        if(!$data) parent::redirect('wallets/deposits');

        $this->view->assign('data',$data);
        $this->view->draw('wallet_edit');
        return true;
    }

    public function withdrawal_edit($id=0)
    {
        $data = $this->model->get_record($id);
        if(!$data) parent::redirect('wallets/withdrawals');

        $this->view->assign('data',$data);
        $this->view->draw('wallet_edit');
        return true;
    }

    public function payment_edit($id=0)
    {
        global $PaymentPriceType;

        $data = $this->model->get_record($id);
        if(!$data) parent::redirect('wallets/payments');

        $this->view->assign('PaymentPriceType',$PaymentPriceType);
        $this->view->assign('data',$data);
        $this->view->draw('wallet_edit');
        return true;
    }

    public function remove($id=0)
    {
        if($id) {
            $this->model->remove($id);
        }
        parent::goback();
    }

    public function accounts($page=0){
        global $Status;

        $this->view->assign('f',$this->input->get('f'));
        $this->view->assign('userList',$this->model->userList());
        $data = $this->model->accounts($page);
        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('Status',$Status);
        $this->view->assign('data',$data['result']);
        $this->view->assign('pagination',$data['pagi']);
        $this->view->assign('content',$this->view->draw('wallets_accounts',true));
        $this->view->draw('index');
        return true;
    }

    public function views($user_id){

        global $TransactionType,$PaymentPriceType;

        $data = $this->model->views($user_id);
        if(!$data) parent::redirect('wallets/accounts');

        $this->view->assign('f',$this->input->get('f'));
        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('Type',$TransactionType);
        $this->view->assign('PaymentPriceType',$PaymentPriceType);
        $this->view->assign('data',$data);
        $this->view->assign('content',$this->view->draw('wallets_views',true));
        $this->view->draw('index');
        return true;

    }

    public function savestatus(){
        $id = $this->input->post('id');
        $val = $this->input->post('val');
        if($id) {
            $this->model->db->update('balances')->where('id',$id)->set([
                'status' => $val,
                'create_user' => Session::fetch('admin','id'),
            ]);
            if($val == 3) {

                $v = $this->model->db->from('balances')->select('product_id')->where('id',$id)->first();
                $this->model->db->update('balances')->where('id',$id,'!=')->where('product_id',$v['product_id'])->set([
                    'status' => $val,
                    'create_user' => Session::fetch('admin','id'),
                ]);

            }
        }
    }
}