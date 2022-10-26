<?php

global $paytr;

class Paytr
{

    public $merchant_id = '';
    public $merchant_salt = '';
    public $merchant_key = '';

    public function __set($key,$value)
    {
        $this->$key = $value;
    }

    public function action() {

        if(!$this->name) $this->name = 'İsim Soyisim Girilmemiş';
        if(!$this->address) $this->address = 'Adres bilgisi girilmemiştir.';
        if(!$this->username) $this->username = 'user@email.com';
        if(!$this->phone) $this->phone = '05000000000';

        $ip = USER_IP;

        $payment_amount = numbers($this->amount);

        $user_basket = htmlentities(json_encode(
            $this->products
        ));

        $debug_on = 1;
        $installment_count = $this->taksit;
        if($installment_count == 1) $installment_count = 0;
        $non_3d = 0;
        $currency = "TL";
        $test_mode = 0;
        $payment_type = "card";

        $cardType =  self::checkBIN($this->CCno);
        if(!$cardType && $installment_count>1)  {
            return  ['status' => 'failed','reason' => 'Kredi kartınız taksit için geçerli değildir. Lütfen Tek Çekim deneyiniz !'];
        }

        $hash_str = $this->merchant_id .$ip.$this->code .$this->username .$payment_amount. $payment_type. $installment_count.$currency.$test_mode. $non_3d;
        $paytr_token=base64_encode(hash_hmac('sha256',$hash_str.$this->merchant_salt,$this->merchant_key,true));

        $post_vals=array(
            'merchant_id'=>$this->merchant_id,
            'user_ip'=>$ip,
            'merchant_oid'=>$this->code,
            'email'=>$this->username,
            'payment_amount'=>$payment_amount,
            'paytr_token'=>$paytr_token,
            'user_basket'=>$user_basket,
            'debug_on'=>$debug_on,
            'installment_count'=>$installment_count,
            'user_name'=>$this->name,
            'user_address'=>$this->address,
            'user_phone'=>str_replace(array(' ','(',')'),'',$this->phone),
            'merchant_ok_url'=>$this->okURL,
            'merchant_fail_url'=>$this->failURL,
            'timeout_limit'=>30,
            'currency'=>$currency,
            'test_mode'=>$test_mode,
            'payment_type'=>$payment_type,
            'cc_owner'=>trim($this->CCName),
            'card_number'=>str_replace(' ','',trim($this->CCno)),
            'expiry_month'=>$this->CCmonth,
            'expiry_year'=>$this->CCyear,
            'cvv'=>$this->CCccv,
            'non3d_test_failed'=>0,
            'non_3d'=>$non_3d,
            'card_type'=>$cardType['brand'],
        );

        $post_url = "https://www.paytr.com/odeme";
        //$post_url = "https://www.bebex.net/odeme.php";

        $form[] = '<script type="text/javascript">
        function moveWindow() {
        document.pay_form.submit();
        }
        </script>
        <body onLoad="javascript:moveWindow()">
        <form method="post" action="'.$post_url.'" name="pay_form">';
        foreach($post_vals as $key => $val){
            $form[] = '<input type="hidden" name="'.$key.'" value="'.$val.'" /><br />';
        }
        $form[] = ' </form>
        </body>';

        return ['status' => 'success' , 'html' => implode('',$form)];

    }

    public function checkBIN($number)
    {
        $hash_str = substr($number,0,6) . $this->merchant_id . $this->merchant_salt;
        $paytr_token=base64_encode(hash_hmac('sha256', $hash_str, $this->merchant_key, true));
        $post_vals=array(
            'merchant_id'=>$this->merchant_id,
            'bin_number'=>substr($number,0,6),
            'paytr_token'=>$paytr_token
        );
        ############################################################################################

        $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.paytr.com/odeme/api/bin-detail");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1) ;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vals);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $result = @curl_exec($ch);

        curl_close($ch);

        $result=json_decode($result,1);

        if($result['status']=='error') return false;
        if($result['status']=='failed') return false;

        return $result;
    }
}

if($data) {
    $paytrCard = new Paytr();
    $paytrCard->merchant_id = $paytr['id'];
    $paytrCard->merchant_key = $paytr['key'];
    $paytrCard->merchant_salt = $paytr['salt'];
    $paytrCard->amount = $data['price'];
    $paytrCard->name = $billing['name'];
    $paytrCard->address = $billing['address'];
    $paytrCard->phone = $billing['phone'];
    $paytrCard->username = Session::fetch('user','username');
    $paytrCard->products = $CheckoutProducts;
    $paytrCard->taksit = $data['installment'];
    $paytrCard->CCName = $payment['cardname'];
    $paytrCard->CCno = $payment['cardnumber'];
    $paytrCard->CCmonth = $payment['cardmonth'];
    $paytrCard->CCyear = $payment['cardyear'];
    $paytrCard->CCccv = $payment['cardcvc'];
    $paytrCard->code = $data['code'];
    $paytrCard->okURL = BASEURL . 'payments/paytr_success';
    $paytrCard->failURL = BASEURL . 'payments/paytr_fail';
    return $paytrCard->action();
}