<?php
class Orders_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function lists($page){
        global $OrderStatus, $PaymentType;

        $perpage = 100;

        $WHERE[] = ["id",0,'!=','&&'];
        $WHERE['s'] = ["status",'-1','!=','&&'];

        if($f = $this->input->get('f'))
        {
            if(isset($f['status']) && $f['status']!='all') $WHERE['s'] = ["status",($f['status']),'=','&&'];
            if($f['user_id']) {
                $uye = $this->db->from('users')
                ->select('id')
                ->where('name',$f['user_id'],'LIKE')
                ->where('surname',$f['user_id'],'LIKE','||')
                ->where('username',$f['user_id'],'LIKE','||')
                ->run();
                foreach($uye as $usr) {
                    $ids[] = $usr['id'];
                }
                if($ids) $WHERE[] = ["user_id",$ids,'IN','&&'];
                else $WHERE[] = ["user_id",[0],'IN','&&'];
            }
            if($f['code']) $WHERE[] = ["code",strtoupper($f['code']),'LIKE','&&'];
            if($f['date']) {
                list($start,$end) = explode('/',$f['date']);
                if($start && $end) {
                    $WHERE[] = ["create_time",strtotime(date(trim($start)." 00:00:00")),'>=','&&'];
                    $WHERE[] = ["create_time",strtotime(date(trim($end)." 23:59:59")),'<=','&&'];
                }
            }
        }

        if(intval($_GET['perpage'])) {
            $perpage = intval($_GET['perpage']);
        }

        $CNT = $this->db->from('orders')->select('COUNT(id) Total');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2],$wh[3]);
        $CNT->limit(0,1);
        $count = $CNT->first();

        $pagi = new Pagination();
        $pagi->link = ADMINBASEURL.'orders/main/';
        $pagi->count = $count['Total'];
        $pagi->perpage = $perpage;
        $pagi->pg = $page;
        $pagi->startPage();

        $CNT = $this->db->from('orders');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2],$wh[3]);
        $results = $CNT->orderby('id','DESC')->limit($pagi->start,$pagi->perpage)->run();

        $CURRENCY = [];

        foreach($results as $key => $result)
        {
            $result['create_date'] = date("d.m.Y H:i",$result['create_time']);
            if($CURRENCY[$result['currency_id']]) {
                $result['currency'] = $CURRENCY[$result['currency_id']];
            } else {
                $CURRENCY[$result['currency_id']] = $result['currency'] = $this->db->from('currencys')->select('prefix_symbol,suffix_symbol,code')->where('id',$result['currency_id'])->first();
            }

            if($result['user_id']) {
                $result['user'] = $this->db->from('users')->select('id,name,surname,username')->where('id',$result['user_id'])->first();
            }

            if($result['payment_id']) {
                if($result['payment_type'] == 1) $result['payment_bank'] = $this->db->from('virtual_pos')->select('id,name')->where('id',$result['payment_id'])->first();
                else $result['payment_bank'] = $this->db->from('payment_banks')->select('id,bank_name name')->where('id',$result['payment_id'])->first();
            }

            $result['payment_type_name'] = $PaymentType[$result['payment_type']];
            $result['status_name'] = $OrderStatus[$result['status']];

            $results[$key] = $result;

        }
        $data['result'] = $results;
        $data['count'] = $count['Total'];
        $data['pagi'] = $pagi->paginations();
        return $data;
    }

    public function edit($id){

        global $PaymentType, $CargoStatus;

        $data = $this->db->from('orders')->where('id',$id)->first();
        if(!$data) return false;

        if($p = $this->input->post('p',true)) {
            switch($p['status']) {
                case '':
                    break;
            }
            $this->db->update('orders')->where('id',$id)->set($p);
            $this->setMessage('Sipariş başarı ile güncellendi ','success');

            $data = $this->db->from('orders')->where('id',$id)->first();
        }

        if($ad = $this->input->post('ad',true)) {
            $idd = $this->input->post('id');
            $this->db->update('orders_address')->where('id',$idd)->set($ad);
            $this->setMessage('Adres başarı ile güncellendi ','success');
            if($data['cargo_status'] == 3) {
                $this->db->update('orders')->where('id',$id)->set(['cargo_status'=>1,'cargo_message'=>'']);
                $data['cargo_status'] = 0;
                $data['cargo_message'] = '';
            }
        }

        $data['create_date'] = date("d.m.Y H:i",$data['create_time']);
        if($data['invoice_time']) $data['invoice_date'] = date("d.m.Y H:i",$data['invoice_time']);

        $data['currency'] = $this->db->from('currencys')->select('prefix_symbol,suffix_symbol,code')->where('id',$data['currency_id'])->first();
        $data['user'] = $this->db->from('users')->select('id,name,surname,username')->where('id',$data['user_id'])->first();

        if($data['payment_id']) {
            if($data['payment_type'] == 1) $data['payment_bank'] = $this->db->from('virtual_pos')->select('id,name')->where('id',$data['payment_id'])->first();
            else $data['payment_bank'] = $this->db->from('payment_banks')->select('id,bank_name name')->where('id',$data['payment_id'])->first();
        }

        $data['payment_type_name'] = $PaymentType[$data['payment_type']];

        $data['cargo'] = $this->db->from('cargo_companys')->select('id,name')->where('id',$data['cargo_id'])->first();

        $data['shipping'] = $this->db->from('orders_address ua')
        ->join('citys','citys.id = ua.city_id','LEFT')
        ->join('countrys','countrys.id = ua.country_id','LEFT')
        ->where('ua.order_id',$data['id'])
        ->where('ua.shipping',1)
        ->select('ua.*, citys.name city_name, countrys.name country_name')
        ->first();

        $data['billing'] = $this->db->from('orders_address ua')
        ->join('citys','citys.id = ua.city_id','LEFT')
        ->join('countrys','countrys.id = ua.country_id','LEFT')
        ->where('ua.order_id',$data['id'])
        ->where('ua.billing',1)
        ->select('ua.*, citys.name city_name, countrys.name country_name')
        ->first();

        $products = $this->db->from('orders_products op')->join('products p','p.id = op.product_id','LEFT')->select('op.*, p.old_price, p.price sale_price, (SELECT code FROM currencys WHERE id=p.currency_id LIMIT 1) product_currency')->where('op.order_id',$data['id'])->run();

        $pro_total_price = 0;
        foreach($products as $key => $pro) {
            $picture = $this->db->from('products_images')->select('picture')->where('product_id',$pro['product_id'])->orderby('rows','ASC')->first();
            $pro['seller'] = $this->db->from('users')->select('id,name,surname,username')->where('id',$pro['seller'])->first();
            if($picture) $pro['picture'] = $picture['picture'];
            $pro_total_price += $pro['total_price'] = ($pro['quantity'] * $pro['price']);

            $pro_names[$key] = $pro['name']. ' - '.$pro['model'];
            $pro_quan[$key] = $pro['quantity'];
            $pro_price[$key] = $pro['price'];
            $pro_tprice[$key] = numbers_dot($pro['total_price']);
            $products[$key] = $pro;
        }

        if($products) {
            $data['pro_names'] = implode('<br>',$pro_names);
            $data['pro_quan'] = implode('<br>',$pro_quan);
            $data['pro_price'] = implode('<br>',$pro_price);
            $data['pro_tprice'] = implode('<br>',$pro_tprice);
        }
        if($data['coupon_code']) {
            switch($data['coupon_type']) {
                //Yüzdelik
                case '0':
                    $data['coupon_order_price'] = (($pro_total_price * $data['coupon_price']) / 100);
                    break;
                    //Net
                case '1':
                    $data['coupon_order_price'] = $data['coupon_price'];
                    break;
            }
        }
        $data['total'] = $pro_total_price;

        $data['total_kdvsiz'] = ($data['total']/"1.18");
        $data['total_kdv'] = numbers_dot($data['total']-$data['total_kdvsiz']);
        $data['total_kdvsiz'] = numbers_dot($data['total_kdvsiz']);
        $data['products'] = $products;

        if($data['cargo_integration_id']) {
            $data['cargo_status_name'] = $CargoStatus[$data['cargo_status']];
        }


        $data['barcode'] = preg_replace('#[^0-9]+#','',$data['code']);
        $data['yaziyla'] = money2text($data['price']);
        return $data;

    }

    public function sendcargo($order_id){
        $data = $this->db->from('orders o')
        ->join('cargo_integrations ci','ci.id = o.cargo_integration_id','LEFT')
        ->select('o.id,o.code, o.payment_type, o.price, o.cargo_integration_id,o.cargo_status, ci.name ci_name, ci.customer_code, ci.api_key, ci.api_password, ci.username, ci.password, ci.apifile')
        ->where('o.id',$order_id)->first();
        if(!$data) return false;

        if($data['cargo_integration_id']) {
            if($data['cargo_status'] == 0) {

                $data['barcode'] = preg_replace('#[^0-9]+#','',$data['code']);

                $data['shipping'] = $this->db->from('orders_address ua')
                ->join('citys','citys.id = ua.city_id','LEFT')
                ->join('countrys','countrys.id = ua.country_id','LEFT')
                ->where('ua.order_id',$data['id'])
                ->where('ua.shipping',1)
                ->select('ua.*, citys.name city_name, countrys.name country_name')
                ->first();

                $Cargo = include_once 'libraries/'.$data['apifile'];

                $result = $Cargo->KargoGonder($data);
                if($result['status'] == 'success') {
                    $update = [
                        'cargo_status' => 1,
                        'cargo_message' => '',
                        'status' => 1,
                    ];
                } else {
                    $update = [
                        'cargo_status' => 3,
                        'status' => 0,
                        'cargo_message' => $result['reason'],
                    ];
                }

                $this->db->update('orders')->where('id',$data['id'])->set($update);
            }
        }
    }

    public function cargo($page){
        global $CargoStatus, $PaymentType;

        $perpage = SHOW_LIST_PAGE;

        $WHERE['s'] = ["cargo_status",[1,3],'IN','&&'];

        if($f = $this->input->get('f'))
        {
            if(isset($f['cargo_status']) && $f['cargo_status']!='all') $WHERE['s'] = ["cargo_status",($f['cargo_status']),'=','&&'];
            if($f['user_id']) $WHERE[] = ["user_id",$f['user_id'],'=','&&'];
            if($f['code']) {
                $WHERE[] = ["code",$f['code'],'LIKE','&&'];
                $WHERE['s'] = ["cargo_status",0,'!=','&&'];
            }
            if($f['date']) {
                list($start,$end) = explode('/',$f['date']);
                if($start && $end) {
                    $WHERE[] = ["create_time",strtotime(date(trim($start)." 00:00:00")),'>=','&&'];
                    $WHERE[] = ["create_time",strtotime(date(trim($end)." 23:59:59")),'<=','&&'];
                }
            }
        }

        if(intval($_GET['perpage'])) {
            $perpage = intval($_GET['perpage']);
        }

        $CNT = $this->db->from('orders')->select('COUNT(id) Total');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2],$wh[3]);
        $CNT->limit(0,1);
        $count = $CNT->first();

        $pagi = new Pagination();
        $pagi->link = ADMINBASEURL.'orders/cargo/';
        $pagi->count = $count['Total'];
        $pagi->perpage = $perpage;
        $pagi->pg = $page;
        $pagi->startPage();

        $CNT = $this->db->from('orders');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2],$wh[3]);
        $results = $CNT->orderby('id','DESC')->limit($pagi->start,$pagi->perpage)->run();

        $CURRENCY = [];

        foreach($results as $key => $result)
        {
            $result['create_date'] = date("d.m.Y H:i",$result['create_time']);
            if($CURRENCY[$result['currency_id']]) {
                $result['currency'] = $CURRENCY[$result['currency_id']];
            } else {
                $CURRENCY[$result['currency_id']] = $result['currency'] = $this->db->from('currencys')->select('prefix_symbol,suffix_symbol,code')->where('id',$result['currency_id'])->first();
            }

            if($result['user_id']) {
                $result['user'] = $this->db->from('users')->select('id,name,surname,username')->where('id',$result['user_id'])->first();
            }

            if($result['payment_id']) {
                if($result['payment_type'] == 1) $result['payment_bank'] = $this->db->from('virtual_pos')->select('id,name')->where('id',$result['payment_id'])->first();
                else $result['payment_bank'] = $this->db->from('payment_banks')->select('id,bank_name name')->where('id',$result['payment_id'])->first();
            }

            $result['cargo_integration_company'] = $this->db->from('cargo_integrations')->select('id,name')->where('id',$result['cargo_integration_id'])->first();

            $result['payment_type_name'] = $PaymentType[$result['payment_type']];
            $result['cargo_status_name'] = $CargoStatus[$result['cargo_status']];

            $results[$key] = $result;

        }
        $data['result'] = $results;
        $data['count'] = $count['Total'];
        $data['pagi'] = $pagi->paginations();
        return $data;
    }

    public function cargo_remove($order_id){
        $data = $this->db->from('orders')->where('id',$order_id)->first();
        if(!$data) return false;

        if($data['cargo_integration_id']) {
            if($data['cargo_status'] != 0) {
                $this->db->update('orders')->where('id',$data['id'])->set([
                    'cargo_status' => 0,
                    'status' => 0,
                ]);
                $this->setMessage('Sipariş başarı ile kargo entegrasyon listesine çıkartıldı !','success');
            } else {
                $this->setMessage('Sipariş entegrasyona gönderilmemiştir ! Lütfen önce entegrasyona gönderiniz.','danger');
            }
        } else {
            $this->setMessage('Kargo entegrasyonu bulunamadı !','danger');
        }

    }

    public function importcode(){

        $time = time();

        $data = $this->db->from('orders o')
        ->join('cargo_integrations ci','ci.id = o.cargo_integration_id','LEFT')
        ->join('cargo_companys c','c.id = o.cargo_id','LEFT')
        ->select('o.id,o.code, c.name cargo_name, o.cargo_integration_id,o.cargo_status, ci.name ci_name, ci.customer_code, ci.api_key, ci.api_password, ci.username, ci.password, ci.apifile')
        ->where('o.cargo_status',1)
        ->where('o.cargo_updatetime',$time,'<=')
        ->first();

        $json['finish'] = 1;

        if($data) {
            if($data['cargo_integration_id']) {
                $data['barcode'] = preg_replace('#[^0-9]+#','',$data['code']);
                $Cargo = include_once 'libraries/'.$data['apifile'];
                $result = $Cargo->KargoTakip($data['barcode']);
                if($result['TakipNo']) {
                    $this->db->update('orders')->where('id',$data['id'])->set([
                        'tracking_no' => $result['TakipNo'],
                        'tracking_url' => $result['TakipUrl'],
                        'cargo_status' => 2,
                        'status' => 2,
                        'cargo_message' => '',
                        'cargo_updatetime' => (time() + 300),
                    ]);

                    $data['shipping'] = $this->db->from('orders_address ua')
                    ->where('ua.order_id',$data['id'])
                    ->where('ua.shipping',1)
                    ->select('ua.phone, ua.name')
                    ->first();

                    if($data['shipping']['phone']) {
                        $smsText = 'Merhaba {AdSoyad}, {SiparisNo} numaralı siparişiniz {KargoTakipNo} takip numarası ile {KargoFirmasi} aracılığıyla yola çıkmıştır. Mutlu günler dileriz.';
                        $smsText = str_replace(['{AdSoyad}','{SiparisNo}','{KargoTakipNo}','{KargoFirmasi}'],[$data['shipping']['name'],$data['code'],$result['TakipNo'],$data['cargo_name']],$smsText);
                        sendSMS($data['shipping']['phone'],$smsText);
                    }
                    $json['message'] = '-<code>'.$data['code'].'</code> Nolu Sipariş bilgisi gönderildi.';
                } else {
                    $this->db->update('orders')->where('id',$data['id'])->set([
                        'cargo_updatetime' => (time() + 300),
                    ]);
                }
            }
            $json['finish'] = 0;
        } else {
            $this->setMessage('Güncelleme tamamlanmıştır. Takip numarası oluşan kargolar başarı ile girilmiştir.','success');
        }

        die(json_encode($json));
    }

    public function remove($id){
        if($id) {
            $this->db->delete('orders')->where('id',$id)->done();
            $this->db->delete('orders_products')->where('order_id',$id)->done();
            $this->db->delete('orders_address')->where('order_id',$id)->done();
            $this->setMessage('Başarı ile silinmiştir !','success');
        }
    }

    public function address_edit($id){
        return $this->db->from('orders_address')->where('id',$id)->first();
    }

    public function CreateKasa($order)
    {
        $products = $this->db->from('orders_products')->where('order_id',$order['id'])->run();
        $this->db->insert('balances')->set([
            'user_id' => $order['user_id'],
            'price' => $order['price'],
            'create_time' => time(),
            'detail' => $order['code'].' nolu sipariş faturası',
            'ty' => 0,
        ]);

        foreach($products as $pro) {
            $seller[$pro['seller']] += ($pro['price'] - $pro['comm']);
        }

        foreach($seller as $s_id => $price) {
            $pr = $price - (($price * $this->settings['seller_commission']) / 100);
            $this->db->insert('balances')->set([
                'user_id' => $s_id,
                'price' => $pr,
                'create_time' => time(),
                'detail' => $order['code'].' nolu sipariş hakediş',
                'ty' => 1,
            ]);
        }
    }

    public function parasut($auction_id, $user_id, $tip = 0) {

        $data = $this->db->from('invoices')->where('tip',$tip)->where('auction_id',$auction_id)->where('user_id',$user_id)->first();

        if(!$data) {

            include_once BASE . 'libraries/Parasut.php';
            $Parasut = new Parasut();

            $auction = $this->db->from('auctions')->where('id',$auction_id)->first();

            $user = $this->db->from('users')->where('id',$user_id)->first();

            if(!$user['state'] || !$user['city_id']) {
                $json['cls'] = 'error';
                $json['head'] = 'HATA';
                $json['message'] = 'Adres bilgileri eksiktir ! Lütfen adres bilgilerini güncelleyiniz !';

                die(json_encode($json));

            }

            $city = $this->db->from('citys')->where('id',$user['city_id'])->first();
            $bill = $this->db->from('users_address')->where('user_id',$user_id)->where('billing',1)->orderby('main','DESC')->first();

            if(!$bill['id']) {
                $bill = [
                    'tax_no' => '11111111111',
                    'name' => $user['name'].' '.$user['surname'],
                ];
                $data['efatura'] = 0;
            } else {
                if($bill['tax_no']) {
                    $Efatura = $Parasut->FindEfatura($bill['tax_no']);
                    $this->db->update('users_address')->where('id',$bill['id'])->set([
                        'efatura' => ($Efatura['e_invoice_address'])
                    ]);
                }
                $data['efatura'] = $Efatura['e_invoice_address'];
            }

            $data['bill'] = [
                'username' => $user['username'],
                'name' => $bill['name'],
                'tax_name' => $bill['tax_name'],
                'tax_no' => $bill['tax_no'],
                'state' => $user['state'],
                'city_name' => $city['name'],
                'address' => $user['address'],
                'phone' => $user['phone'],
            ];

            if(!$user['parasut_id']) {
                $user['parasut_id'] = $Parasut->createCustomer($data['bill']);
                $this->db->update('users')->where('id',$user['id'])->set(['parasut_id'=>$user['parasut_id']]);
            }

            $data['code'] = 'PH'.$auction_id.'-'.$user_id.'-'.$tip;
            $data['user'] = $user;
            $data['invoice_time'] = time();
            $data['create_time'] = $auction['end_time'];

            switch($tip) {
                case '0':

                    $products = $this->db->from('products')->where('sale', $user_id)->where('auction_id', $auction_id)->where('status', 2)->run();
                    foreach ($products as $pro) {
                        $komm = (($pro['price'] * $this->settings['commission']) / 100);
                        $komm_kdv = ($komm * 0.18);
                        $comm += ($komm);
                        $total += ($komm + $komm_kdv);
                    }

                    break;
                case '1':

                    $products = $this->db->from('products')->where('sale',0,'>')->where('auction_id',$auction_id)->where('seller',$user_id)->where('status',2)->run();
                    foreach($products as $pro) {
                        $komm = (($pro['price'] * $this->settings['seller_commission']) / 100);
                        $komm_kdv = ($komm * 0.18);
                        $comm += ($komm);
                        $total += ($komm + $komm_kdv);
                    }

                    break;
            }



            $data['product']['price'] = numbers($comm);
            $data['price'] = numbers($total);

            $data['parasut_id'] = $Parasut->createInvoce($data);
            if($data['parasut_id']) {

                $this->db->insert('invoices')->set([

                        'auction_id' => $auction_id,
                        'user_id' => $user_id,
                        'tip' => $tip,
                        'parasut_id' => $data['parasut_id'],
                        'create_time' => time(),
                        'status' => 0,

                ]);
                $invoice_id = $this->db->lastId();

                $Parasut->createPay($data);
                $data['job_id'] = $Parasut->sendInvoice($data);

                if($data['job_id']) {
                    $this->db->update('invoices')->where('id',$invoice_id)->set([
                        'job_id' => $data['job_id']
                    ]);

                    $json['cls'] = 'success';
                    $json['head'] = 'Başarılı';
                    $json['message'] = 'Efatura için kuyruğa eklendi.';
                }

            }

            if($Parasut->errors) {
                $json['cls'] = 'error';
                $json['head'] = 'HATA';
                $json['message'] = $Parasut->errors['detail'];
            }

        } else {

            if($data['status'] == 0) {
                $json['cls'] = 'info';
                $json['head'] = 'Bekliyor';
                $json['message'] = 'Fatura henüz kuyruktadır. Bir kaç dakika sonra tekrar deneyiniz';
            } else {
                $json['cls'] = 'success';
                $json['redirect'] = BASEURL . 'data/pdf/invoices/'.$data['pdf'];
            }
        }

        die(json_encode($json));
    }
}