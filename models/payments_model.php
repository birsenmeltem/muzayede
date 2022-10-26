<?php
class Payments_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function paytr(){
        $merchant_oid = $this->input->post('merchant_oid');

        $order = $this->db->from('orders')
        ->where('code',$merchant_oid)
        ->first();

        if($order) {
            global $paytr;

            $hash = $this->input->post('hash');
            $status = $this->input->post('status');
            $total_amount = $this->input->post('total_amount');

            $paytrHash = base64_encode(
                hash_hmac('sha256',
                    join('',
                        array(
                            $merchant_oid,
                            $paytr['salt'],
                            $status,
                            $total_amount
                        )
                    ),
                    $paytr['key'], true
                )
            );

            if($hash == $paytrHash) {
                if($status == 'success') {
                    $update = [
                        'status' => 0,
                    ];
                } else {
                    $resultCode = $this->input->post('failed_reason_code');
                    $resultMessage = $this->input->post('failed_reason_msg');

                    $update = [
                        'err_message' => strip_tags(str_replace(array("\r","\n"),'',$resultMessage)),
                    ];
                }
                $this->db->update('orders')->where('id',$order['id'])->set($update);
                die('OK');
            }
        }
    }

    public function paytr_success() {
        $data = Session::fetch('order_data');
        if($data) {
            $this->db->update('orders')->where('id',$data['id'])->set([
                'status' => 0
            ]);

            $products = $this->db->from('orders_products')->where('order_id',$data['id'])->run();
            foreach($products as $pro)  {
                if($pro['stocksku'] != $pro['sku']) {
                    $this->db->exec("UPDATE products_variants_stocks SET stock = stock - {$pro['quantity']} WHERE sku='{$pro['stocksku']}' LIMIT 1");
                }

                $this->db->exec("UPDATE products SET stock = stock - {$pro['quantity']} WHERE id='{$pro['product_id']}' LIMIT 1");
            }
            parent::CreateOrderMail($data['id']);
            redirect('cart/success/'.$data['code']);
        }

        redirect('cart');
    }

    public function paytr_fail() {
        $data = Session::fetch('order_data');
        if($data) {
            $resultMessage = strip_tags(str_replace(array("\r","\n"),'',$this->input->post('fail_message')));
            $this->db->update('orders')->where('id',$data['id'])->set([
                'err_message' => $resultMessage
            ]);
            $this->setMessage($resultMessage,'danger');
        }
        redirect('cart/checkout');

    }
}