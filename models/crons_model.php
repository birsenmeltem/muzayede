<?php
use Dompdf\Dompdf;
use Dompdf\Options;

class Crons_Model extends Model
{
    private $exchangeUrl = 'https://v6.exchangerate-api.com/v6/63c922fe566f4232a66aba6e/latest/';
    public function __construct()
    {
        parent::__construct();
        $this->db->SetLang('tr');
    }

    public function invoices() {
        $data = $this->db->from('invoices')->where('status',0)->orderby('id','ASC')->run();

        include_once BASE . 'libraries/Parasut.php';
        $Parasut = new Parasut();

        foreach($data as $i) {
            $auction = $this->db->from('auctions')->where('id',$i['auction_id'])->first();
            $user = $this->db->from('users')->where('id',$i['user_id'])->select('username,name')->first();
            if($user['username']) {
                $val = $Parasut->GetInvoiceInfo($i['parasut_id']);
                if($val) {
                    $Filename = $i['parasut_id'].'.pdf';

                    file_put_contents(BASE . 'data/pdf/invoices/'.$Filename, file_get_contents($val));

                    $this->db->update('invoices')->where('id',$i['id'])->set([
                        'status' => 1,
                        'pdf' => $Filename,
                    ]);

                    $html = 'Merhaba <b>'.$user['name'].'</b><br><br><p>Efaturanız ekte tarafınıza iletilmiştir.</p>';

                    $this->db->insert('crons')->set([
                        'username' => $user['username'],
                        'header' => $auction['name'].' Müzayedesi E-Faturanız ',
                        'html' => $html,
                        'filename' => 'invoices/'.$Filename,
                        'ty' => 0,
                        'status' => 0,
                    ]);

                }
            }
        }
    }

    public function mesut() {

        $brands = $this->db->from('brands')->run();
        foreach($brands as $br) {
            $BRANDS[$br['id']] = $br['name'];
        }

        $cats = $this->db->from('categories')->run();
        foreach($cats as $br) {
            $CT[$br['id']] = $br['name'];
        }
        $aucs = $this->db->from('auctions')->run();
        foreach($aucs as $br) {
            $AUC[$br['id']] = $br['name'];
            $AUCS[$br['id']] = date("d.m.Y H:i",$br['start_time']);
        }

        $data = $this->db->from('products')->where('sale',0,'>')->run();
        foreach($data as $key => $val) {
            $tmp[] = $AUCS[$val['auction_id']];
            $tmp[] = $AUC[$val['auction_id']];
            $tmp[] = $BRANDS[$val['category_id']];
            $tmp[] = $BRANDS[$val['brand_id']];
            $tmp[] = $val['sku'];
            $tmp[] = $val['name'];
            $tmp[] = $val['price'];
            $TMP[] = implode(';',$tmp);
            unset($tmp);
        }
        file_put_contents('sales.csv',implode("\r\n",$TMP));
        die;
    }
    public function currency_exchange()
    {
        foreach(['USD','EUR','GBP','TRY'] as $value)
        {
            $data = file_get_contents($this->exchangeUrl.$value);

            if($data)
            {
                $json = json_decode($data,true);
                if($json['conversion_rates']) {
                    file_put_contents(BASE.'data/currency/'.$value.'.cache',json_encode($json['conversion_rates']));
                }
            }
        }
    }

    public function auctions() {

        $sms_search = ['{{NAME}}','{{LOT}}', '{{URL}}'];

        $time = time();
        $data = $this->db->from('auctions')->select('id,remaining')->where('status',1)->where('live_start_time',$time,'<=')->run();
        foreach($data as $d) {
            $url = BASEURL.parent::get_seo_link('auctions',$d['id']);
            $products = $this->db->from('products')->select('id,sku,name')->where('auction_id',$d['id'])->where('status',1)->run();
            foreach($products as $pro) {
                if($pro['sku'] <= $this->settings['wait_live']) {
                    $lives = $this->db->from('users_live ul')->join('users u','u.id = ul.user_id','LEFT')->select('ul.id, u.name, u.surname, u.phone')->where('ul.product_id',$pro['id'])->where('ul.status',0)->where('u.sms',1)->run();
                    foreach($lives as $live) {
                        $sms_replace = [$live['name'].' '.$live['surname'], $pro['name'], $url];
                        $sms_html = str_replace($sms_search,$sms_replace,$this->settings['sms_wait_live']);
                        $this->db->insert('crons')->set([
                            'username' => $live['phone'],
                            'html' => $sms_html,
                            'ty' => 1,
                        ]);
                        $IDS[] = $live['id'];
                    }
                    $this->db->update('users_live')->where('id',$IDS,'IN')->set(['status'=>1]);
                    unset($IDS);
                }

                //$this->db->exec("UPDATE products SET remaining = ({$time} + (sku * {$d['remaining']})) WHERE id='{$pro['id']}'");
            }

        }
        $this->db->update('auctions')->where('status',1)->where('live_start_time',$time,'<=')->set(['status'=>2]);
    }

    public function auctions_info() {
        $time = strtotime(date("d.m.Y H:i"));
        //if(USER_IP == '82.222.29.82') $time = strtotime('13.11.2020 10:07');

        $data = $this->db->from('auctions')->where('start_time',$time,'=')->run();
        $users = $this->db->from('users')->where('status',1)->select('username,sms,mail,phone,CONCAT(name," ",surname) names')->run();

        $mail_search = ['{{DATE}}', '{{TIME}}', '{{NAME}}', '{{MEZAT}}', '{{CANLITIME}}', '{{LOTTIME}}', '{{URL}}', '{{LOGO}}','{{ENDTIME}}','{{ENDDATE}}'];
        $sms_search = ['{{DATE}}', '{{MEZAT}}', '{{URL}}'];


        foreach($data as $dat) {
            self::CheckAlarm($dat['id']);
           /* $url = BASEURL.parent::get_seo_link('auctions',$dat['id']);

            foreach($users as $user) {

                $mail_replace = [date("d.m.Y",$dat['start_time']),date("H:i",$dat['start_time']),$user['names'], $dat['name'], date("H:i",$dat['live_start_time']), $dat['remaining'], $url, BASEURL . 'data/uploads/'.$this->settings['logo'],date("H:i",$dat['end_time']),date("d.m.Y",$dat['end_time'])];
                $sms_replace = [date("d.m.Y H:i",$dat['end_time']), $dat['name'], $url];

                $mail_header = str_replace($mail_search,$mail_replace,$this->settings['basladi_header']);
                $mail_html = str_replace($mail_search,$mail_replace,$this->settings['basladi_html']);

                $sms_html = str_replace($sms_search,$sms_replace,$this->settings['sms_all']);

                if($user['username'] && $user['mail']) {
                    $this->db->insert('crons')->set([
                        'username' => $user['username'],
                        'header' => $mail_header,
                        'html' => $mail_html,
                        'ty' => 0,
                    ]);
                }
                if($user['phone'] && $user['sms']) {
                    $this->db->insert('crons')->set([
                        'username' => $user['phone'],
                        'html' => $sms_html,
                        'ty' => 1,
                    ]);
                }

            }
           */

        }
    }

    public function auctions_end() {
        $time = strtotime(date("d.m.Y H:i",strtotime("+7 hours")));
        dump(date("d.m.Y H:i",$time));

        $data = $this->db->from('auctions')->where('status',1)->where('end_time',$time,'=')->run();

        $sms_search = ['{{NAME}}','{{DATE}}', '{{MEZAT}}', '{{URL}}'];

        foreach($data as $dat) {

            $url = BASEURL.parent::get_seo_link('auctions',$dat['id']);
            $users = $this->db->from('users')->where('status',1)->select('sms,mail,phone,CONCAT(name," ",surname) names')->run();
            foreach($users as $user) {
                $sms_replace = [$user['names'], date("d.m.Y H:i",$dat['end_time']), $dat['name'], $url];

                $sms_html = str_replace($sms_search,$sms_replace,$this->settings['sms_pey']);

                if($user['phone'] && $user['sms']) {
                    $this->db->insert('crons')->set([
                        'username' => $user['phone'],
                        'html' => $sms_html,
                        'ty' => 1,
                    ]);
                }

            }

            unset($ids,$usIds);
        }

    }

    public function auctions_end20() {
        $time = strtotime(date("d.m.Y H:i",strtotime("+20 minutes")));
        dump(date("d.m.Y H:i",$time));

        $data = $this->db->from('auctions')->where('status',1)->where('end_time',$time,'=')->run();

        $sms_search = ['{{NAME}}','{{DATE}}', '{{MEZAT}}', '{{URL}}'];

        foreach($data as $dat) {

            $url = BASEURL.parent::get_seo_link('auctions',$dat['id']);
            $users = $this->db->from('users')->where('status',1)->select('sms,mail,phone,CONCAT(name," ",surname) names')->run();
            foreach($users as $user) {
                $sms_replace = [$user['names'], date("d.m.Y H:i",$dat['end_time']), $dat['name'], $url];

                $sms_html = str_replace($sms_search,$sms_replace,$this->settings['sms_pey']);

                if($user['phone'] && $user['sms']) {
                    $this->db->insert('crons')->set([
                        'username' => $user['phone'],
                        'html' => $sms_html,
                        'ty' => 1,
                    ]);
                }

            }

            unset($ids,$usIds);
        }

    }

    public function sendmail() {
        $data = $this->db->from('crons')->where('ty',0)->where('status',0)->limit(0,10)->orderby('id','ASC')->run();
        foreach($data as $dat) {
            $file = ($dat['filename']) ? 'data/pdf/'.$dat['filename'] : false;

            sendMail($dat['username'],$dat['header'],$dat['html'],'./',$file);
            $ids[] = $dat['id'];
        }
        $this->db->update('crons')->in('id',$ids)->set([
            'send_time' => time(),
            'status' => 1,
        ]);
    }

    public function sendsms() {
        $data = $this->db->from('crons')->where('ty',1)->where('status',0)->limit(0,10)->orderby('id','ASC')->run();
        foreach($data as $dat) {
            sendSMS($dat['username'],$dat['html']);
            $ids[] = $dat['id'];
        }
        $this->db->update('crons')->in('id',$ids)->set([
            'send_time' => time(),
            'status' => 1,
        ]);
    }

    public function CheckAlarm($auction_id) {

        $mail_search = ['{{KEYWORD}}', '{{PRODUCTS}}', '{{NAME}}', '{{URL}}', '{{LOGO}}'];

        $alarms = $this->db->from('users_alarm')->where('status',1)->orderby('user_id','ASC')->run();
        foreach($alarms as $al) {

            $keyword = ($al['ep']) ? $al['keyword'] : str_replace(' ','%',$al['keyword']);

            $arg = [
                'orderby' => [
                    'p.sku','ASC'
                ],
                'perpage' => 3,
                'where' => [
                    ['p.auction_id',$auction_id,'=','&&'],
                    ['(p.name',$keyword,'LIKE','&&'],
                    ['p.shortdetail',$keyword,'LIKE','||'],
                    ['p.detail',$keyword,'LIKE','||'],
                ]
            ];

            $arg['where'][] = ['p.status',1,'=',') &&'];

            if($al['min_price']!='0.00') {
                $arg['where'][] = ['p.price',$al['min_price'],'>=','&&'];
            }

            if($al['max_price']!='0.00') {
                $arg['where'][] = ['p.price',$al['max_price'],'<=','&&'];
            }

            $products = parent::GetProducts($arg);

            if($products['records']) {

                $search = [
                    'q'=>$keyword,
                    'active'=>1,
                ];
                if($al['min_price']!=0.00) $search['min'] = $al['min_price'];
                if($al['max_price']!=0.00) $search['max'] = $al['max_price'];

                $URL = BASEURL . 'search?'.http_build_query($search);


                foreach($products['records'] as $pro)
                {
                    $pros[] = '<tr style="background:white;font-size:12px;line-height:2em">
                    <td style="width:40px"></td>
                    <td style="padding:20px 0px 20px 20px;border:none;border-bottom:1px solid #eee">
                    <img src="'.BASEURL.'data/products/'.$pro['id'].'/'.$pro['pictures'][0]['picture'].'" style="width:120px;border-radius:4px">
                    </td>
                    <td style="padding:20px 0px 20px 20px;border:none;border-bottom:1px solid #eee">
                    <a href="'.$URL.'" target="_blank" style="text-decoration:none;color:#2f3b59"><b>'.$al['keyword'] .'</b></a>
                    <p></p>
                    <a href="'.BASEURL.$pro['url'].'" style="text-decoration:none;color:#888da0;font-size:10px" target="_blank">
                    <b>'.$pro['name'].'</b>
                    </a>
                    </td>
                    <td style="padding:20px 0px 20px 20px;border:none;border-bottom:1px solid #eee">
                    <p>'.$pro['old_price'].'</p>
                    </td>
                    <td style="padding:20px 0px 20px 20px;border:none;border-bottom:1px solid #eee">
                    <a href="'.BASEURL.$pro['url'].'" style="text-decoration:none;color:#2f3b59" target="_blank">
                    <b>İncele</b>
                    </td>
                    <td style="width:40px"></td>
                    </tr>';
                }

                $data = [
                    'keyword' => $keyword,
                    'user_id' => $al['user_id'],
                    'result' => implode('',$pros),
                ];

                $user = $this->db->from('users')->where('status',1)->where('mail',1)->select('username,phone,CONCAT(name," ",surname) names')->where('id',$al['user_id'])->first();

                $mail_replace = [$al['keyword'],implode('',$pros),$user['names'], $URL, BASEURL . 'data/uploads/'.$this->settings['logo']];

                $mail_header = str_replace($mail_search,$mail_replace,$this->settings['alarm_header']);
                $mail_html = str_replace($mail_search,$mail_replace,$this->settings['alarm_html']);

                if($user['username']) {
                    $this->db->insert('crons')->set([
                        'username' => $user['username'],
                        'header' => $mail_header,
                        'html' => $mail_html,
                        'ty' => 0,
                    ]);
                }

                unset($pros,$arg);
            }

        }
    }

    public function sellermail() {

        $stime = date("d.m.Y H:i:00");
        $etime = date("d.m.Y H:i:59");
        $stime = strtotime("-1 minute",strtotime($stime));
        $etime = strtotime("-1 minute",strtotime($etime));

        $auctions = $this->db->from('auctions')->where('status',3)->where('live_end_time',$stime,'>=')->where('live_end_time',$etime,'<=')->run();
        //$auctions = $this->db->from('auctions')->where('id',31)->run();

        $search = ['{{NAME}}','{{PHONE}}','{{MAIL}}','{{MEZAT}}','{{DATE}}','{{TOTALURUN}}','{{SALEURUN}}','{{TOTALPRICE}}','{{COMMISSION}}','{{COMMISSIONKDV}}','{{GAIN}}','{{PRODUCTS}}'];

        foreach($auctions as $auc) {
            $products = $this->db->from('products')->where('sale',0,'>')->where('auction_id',$auc['id'])->where('status',2)->run();
            foreach($products as $pro) {
                $komm = (($pro['price'] * $auc['sell_comm']) / 100);
                //$komm_kdv = ($komm * 0.18);

                $sellers[$pro['seller']]['products'][] = [
                    'sku' => $pro['sku'],
                    'name' => $pro['name'],
                    'price' => $pro['price'],
                    'old_price' => $pro['old_price'],
                    'comm' => numbers($komm),
                    'comm_kdv' => numbers($komm_kdv),
                    'gain' => numbers($pro['price'] - (numbers($komm + $komm_kdv))),
                ];

                $sellers[$pro['seller']]['totalprice'][] = numbers($pro['price']);
                $sellers[$pro['seller']]['commission'][] = numbers($komm);
                $sellers[$pro['seller']]['commissionkdv'][] = numbers($komm_kdv);
                $sellers[$pro['seller']]['gain'][] = $gain = numbers($pro['price'] - (numbers($komm + $komm_kdv)));

                $this->db->insert('balances')->set([
                    'user_id' => $pro['seller'],
                    'product_id' => $pro['id'],
                    'ty' => 1,
                    'create_time' => time(),
                    'price' => $gain
                ]);
            }



            foreach($sellers as $seller_id => $values) {
                $user = $this->db->from('users')->where('id',$seller_id)->first();

                $tmp = [];

                foreach($values['products'] as $p) {
                    $tmp[] = '<tr>
                    <td>'.$p['sku'].'</td>
                    <td title="">'.$p['name'].'</td>
                    <td align="right">'.numbers_dot($p['old_price']).'</td>
                    <td align="right">'.numbers_dot($p['price']).'</td>
                    <td align="right">'.numbers_dot($p['comm']).'</td>
                    <td align="right">'.numbers_dot($p['comm_kdv']).'</td>
                    <td align="right">'.numbers_dot($p['gain']).'</td>
                    </tr>';


                }

                $totalProduct = $this->db->from('products')->where('auction_id',$auc['id'])->select("COUNT(id) total")->where('seller',$seller_id)->total();

                $name = $user['id'].' - '.$user['name'].' '.$user['surname'];
                $mezat = $auc['id'].' - '.$auc['name'];

                $replace = [$name,$user['phone'],$user['username'],$mezat,date("d.m.Y H:i",$auc['end_time']),$totalProduct,count($values['products']),
                    numbers_dot(array_sum($values['totalprice'])),
                    numbers_dot(array_sum($values['commission'])),
                    numbers_dot(array_sum($values['commissionkdv'])),
                    numbers_dot(array_sum($values['gain'])),
                    implode('',$tmp)
                ];


                $html = file_get_contents('views/email/tr/seller.html');

                $html = str_replace($search,$replace,$html);

                if($user['username']) {
                    $this->db->insert('crons')->set([
                        'username' => $user['username'],
                        'header' => 'Müzayede Satılanlarınız',
                        'html' => $html,
                        'ty' => 0,
                    ]);
                    /*$this->db->insert('crons')->set([
                    'username' => $this->settings['company_email'],
                    'header' => $auc['name'].' Müzayede Satıcı Raporu ('.$user['username'].')',
                    'html' => $html,
                    'ty' => 0,
                    ]);*/

                    $this->db->insert('reports_pdf')->set([
                        'auction_id' => $auc['id'],
                        'user_id' => $user['id'],
                        'username' => $user['username'],
                        'name' => $user['name'].' '.$user['surname'],
                        'html' => $html,
                        'seller' => 1,
                        'create_time' => time(),
                    ]);
                }

            }

            unset($sellers);
        }


    }

    public function buyermail() {

        $stime = date("d.m.Y H:i:00");
        $etime = date("d.m.Y H:i:59");
        $stime = strtotime("-1 minute",strtotime($stime));
        $etime = strtotime("-1 minute",strtotime($etime));

        include_once 'libraries/dompdf/autoload.inc.php';

        $options    =   new Options();
        $options->setIsHtml5ParserEnabled(true);
        $options->setTempDir('data/');

        $auctions = $this->db->from('auctions')->where('status',3)->where('live_end_time',$stime,'>=')->where('live_end_time',$etime,'<=')->run();
        //$auctions = $this->db->from('auctions')->where('id',31)->run();

        $search = ['{{NAME}}','{{CNAME}}','{{MEZAT}}','{{DATE}}','{{ALICIADI}}','{{BANKA}}','{{HESAPNO}}','{{SUBEADI}}','{{SUBEKODU}}','{{IBAN}}'];

        $pdfsearch = ['{{LOGO}}','{{CADDRESS}}','{{CPHONE}}','{{CMAIL}}','{{NAME}}','{{PHONE}}','{{MAIL}}','{{ADDRESS}}','{{MEZAT}}','{{DATE}}','{{TOTALURUN}}','{{SALEURUN}}','{{PRICE}}','{{TOTALPRICE}}','{{COMMISSION}}','{{COMMISSIONKDV}}','{{SALEPRODUCTS}}','{{PRODUCTS}}'];

        $LOGO = 'data/uploads/'.$this->settings['logo'];

        foreach($auctions as $auc) {
            $products = $this->db->from('products')->where('sale',0,'>')->where('auction_id',$auc['id'])->where('status',2)->run();
            foreach($products as $pro) {
                $PROID[] = $pro['id'];
                $SALED[$pro['sale']][] = $pro['id'];
                $komm = (($pro['price'] * $auc['buy_comm']) / 100);
                //$komm_kdv = ($komm * 0.18);

                $buyers[$pro['sale']]['products'][] = [
                    'sku' => $pro['sku'],
                    'name' => $pro['name'],
                    'price' => $pro['price'],
                    'old_price' => $pro['old_price'],
                    'comm' => numbers($komm),
                    'comm_kdv' => numbers($komm_kdv),
                    'gain' => numbers($pro['price'] + (numbers($komm + $komm_kdv))),
                ];

                $buyers[$pro['sale']]['price'][] = numbers($pro['price']);
                $buyers[$pro['sale']]['commission'][] = numbers($komm);
                $buyers[$pro['sale']]['commissionkdv'][] = numbers($komm_kdv);
                $buyers[$pro['sale']]['totalprice'][] = $gain = numbers($pro['price'] + (numbers($komm + $komm_kdv)));

                $this->db->insert('balances')->set([
                    'user_id' => $pro['sale'],
                    'product_id' => $pro['id'],
                    'ty' => 0,
                    'create_time' => time(),
                    'price' => $gain
                ]);
            }

            foreach($buyers as $buyer_id => $values) {
                $diff = array_diff($PROID,$SALED[$buyer_id]);
                $values['peys'] = $this->db->from('offers o')->join('products p','o.product_id = p.id','LEFT')->in('o.product_id',$diff)->where('o.user_id',$buyer_id)->select('DISTINCT(o.product_id), o.user_id,p.id, p.sku, p.name, p.price, p.old_price')->groupby('o.product_id')->orderby('o.price','DESC')->run();

                $user = $this->db->from('users ua')
                    ->join('citys','citys.id = ua.city_id','LEFT')
                    ->where('ua.id', $buyer_id)->select('ua.*, citys.name city_name')->first();


                $tmp = [];
                $tmp1 = [];

                foreach($values['products'] as $p) {
                    $tmp[] = '<tr>
                    <td>'.$p['sku'].'</td>
                    <td title="">'.$p['name'].'</td>
                    <td align="right">'.numbers_dot($p['old_price']).'</td>
                    <td align="right">'.numbers_dot($p['price']).'</td>
                    <td align="right">'.numbers_dot($p['comm']).'</td>
                    <td align="right">'.numbers_dot($p['comm_kdv']).'</td>
                    <td align="right">'.numbers_dot($p['gain']).'</td>
                    </tr>';
                }

                foreach($values['peys'] as $p) {
                    $offer = $this->db->from('offers')->where('product_id',$p['product_id'])->where('user_id',$p['user_id'])->orderby('price','DESC')->select('price')->first();

                    $tmp1[] = '<tr>
                    <td>'.$p['sku'].'</td>
                    <td title="">'.$p['name'].'</td>
                    <td align="right">'.numbers_dot($p['old_price']).'</td>
                    <td align="right">'.numbers_dot($offer['price']).'</td>
                    <td align="right">'.numbers_dot($p['price']).'</td>
                    <td align="right"></td>
                    <td align="right"></td>
                    </tr>';
                }

                $name = $user['id'].' - '.$user['name'].' '.$user['surname'];
                $mezat = $auc['id'].' - '.$auc['name'];

                $user['address'] .= ' '.$user['state'].' / '.$user['city_name'];

                $pdfreplace = [$LOGO, $this->settings['company_address'],$this->settings['company_phone'],$this->settings['company_email'],$name,$user['phone'],$user['username'],$user['address'],
                    $mezat,date("d.m.Y H:i",$auc['end_time']),
                    (count($values['peys']) + count($SALED[$buyer_id])),
                    count($values['products']),
                    numbers_dot(array_sum($values['price'])),
                    numbers_dot(array_sum($values['totalprice'])),
                    numbers_dot(array_sum($values['commission'])),
                    numbers_dot(array_sum($values['commissionkdv'])),
                    implode('',$tmp),
                    implode('',$tmp1),
                ];

                $pdfhtml = file_get_contents('views/email/tr/buyer_pdf.html');

                $pdfhtml = str_replace($pdfsearch,$pdfreplace,$pdfhtml);

                $dompdf = new Dompdf($options);
                $dompdf->setPaper('A4', 'orientation');
                $dompdf->loadHtml(mb_convert_encoding($pdfhtml, 'HTML-ENTITIES', 'UTF-8'));
                $dompdf->render();

                $pdf_gen = $dompdf->output();
                $filename = md5($auc['id'].'_'.$user['id']).'.pdf';
                file_put_contents('data/pdf/'.$filename,$pdf_gen);

                $bank = $this->db->from('payment_banks')->where('status',1)->first();

                $replace = [$user['name'].' '.$user['surname'], $this->settings['company_name'], $auc['name'], date("d.m.Y H:i",$auc['end_time']),
                    $bank['name'],$bank['bank_name'],$bank['hesap_no'],$bank['sube_name'],$bank['sube_code'],$bank['iban']
                ];

                $html = file_get_contents('views/email/tr/buyer.html');

                $html = str_replace($search,$replace,$html);

                if($user['username']) {
                    $this->db->insert('crons')->set([
                        'username' => $user['username'],
                        'header' => $auc['name']. ' / '.date("d.m.Y H:i", $auc['start_time']).' - Alıcı Raporu',
                        'html' => $html,
                        'filename' => $filename,
                        'ty' => 0,
                        'status' => 0,
                    ]);
                    /* $this->db->insert('crons')->set([
                    'username' => $this->settings['company_email'],
                    'header' => $auc['name']. ' / '.date("d.m.Y H:i", $auc['start_time']).' - Alıcı Raporu ('.$user['username'].')',
                    'html' => $html,
                    'filename' => $filename,
                    'ty' => 0,
                    'status' => 0,
                    ]);*/

                    $this->db->insert('reports_pdf')->set([
                        'auction_id' => $auc['id'],
                        'user_id' => $user['id'],
                        'username' => $user['username'],
                        'name' => $user['name'].' '.$user['surname'],
                        'filename' => $filename,
                        'seller' => 0,
                        'create_time' => time(),
                    ]);
                }

            }
        }
    }

    public function toplusms() {
        return;

        $users = $this->db->from('users')->where('status',1)->select('username,phone,CONCAT(name," ",surname) names')->run();


        $msg = 'PHEBUS 》Oyuncak ve Kumbara müzayedemiz bu akşam milli maç nedeniyle 20:02 de canlı olarak yapılacak. www.phebusmuzayede.com';
        foreach($users as $user) {

            $message = 'Sayın '.$user['names'].'
Teknik bir arıza nedeniyle bu akşam gerçekleşecek SAHAFİYE müzayedemiz 21 Şubat Pazartesi 21:02 tarihine ertelenmiştir. Az önce gönderilen sonuç mailleri teknik bir arıza nedeniyle otomatik gönderilmiştir. Müzayedemiz açılış fiyatları üzerinden baştan başlayacaktır.
Özür dileyerek, anlayışınız için teşekkür eder, iyi akşamlar dileriz.';
            sendMail($user['username'],'SAHAFİYE Müzayedeski Hakkında',$message);
            //sendSMS($user['phone'],$msg);
        }
    }

    public function manuelbuyer($id,$userid, $sendemail = false) {
        if(!$id) return;

        ini_set('display_errors',1);

        $filename = md5($id.'_'.$userid).'.pdf';
        if(file_exists('data/pdf/'.$filename)) $beforeFile = true;


        include_once 'libraries/dompdf/autoload.inc.php';

        $options = new Options();
        $options->setIsHtml5ParserEnabled(true);
        $options->setTempDir('data/');

        $auctions = $this->db->from('auctions')->where('id', $id)->run();


        $search = ['{{NAME}}', '{{CNAME}}', '{{MEZAT}}', '{{DATE}}', '{{ALICIADI}}', '{{BANKA}}', '{{HESAPNO}}', '{{SUBEADI}}', '{{SUBEKODU}}', '{{IBAN}}'];

        $pdfsearch = ['{{LOGO}}', '{{CADDRESS}}', '{{CPHONE}}', '{{CMAIL}}', '{{NAME}}', '{{PHONE}}', '{{MAIL}}', '{{ADDRESS}}', '{{MEZAT}}', '{{DATE}}', '{{TOTALURUN}}', '{{SALEURUN}}', '{{PRICE}}', '{{TOTALPRICE}}', '{{COMMISSION}}', '{{COMMISSIONKDV}}', '{{SALEPRODUCTS}}', '{{PRODUCTS}}'];

        $LOGO = 'data/uploads/' . $this->settings['logo'];

        foreach ($auctions as $auc) {
            $products = $this->db->from('products')->where('sale', 0, '>')->where('auction_id', $auc['id'])->where('status', 2)->run();
            foreach ($products as $pro) {
                $PROID[] = $pro['id'];
                $SALED[$pro['sale']][] = $pro['id'];
                $komm = (($pro['price'] * $auc['buy_comm']) / 100);
                //$komm_kdv = ($komm * 0.18);

                $buyers[$pro['sale']]['products'][] = [
                    'sku' => $pro['sku'],
                    'name' => $pro['name'],
                    'price' => $pro['price'],
                    'old_price' => $pro['old_price'],
                    'comm' => numbers($komm),
                    'comm_kdv' => numbers($komm_kdv),
                    'gain' => numbers($pro['price'] + (numbers($komm + $komm_kdv))),
                ];

                $buyers[$pro['sale']]['price'][] = numbers($pro['price']);
                $buyers[$pro['sale']]['commission'][] = numbers($komm);
                $buyers[$pro['sale']]['commissionkdv'][] = numbers($komm_kdv);
                $buyers[$pro['sale']]['totalprice'][] = numbers($pro['price'] + (numbers($komm + $komm_kdv)));
            }


            foreach ($buyers as $buyer_id => $values) {

                if ($userid && $buyer_id != $userid) continue;
                $diff = array_diff($PROID, $SALED[$buyer_id]);

                $values['peys'] = $this->db->from('offers o')->join('products p', 'o.product_id = p.id', 'LEFT')->in('o.product_id', $diff)->where('o.user_id', $buyer_id)->select('DISTINCT(o.product_id), o.user_id,p.id, p.sku, p.name, p.price, p.old_price')->groupby('o.product_id')->orderby('o.price', 'DESC')->run();

                $user = $this->db->from('users ua')
                    ->join('citys','citys.id = ua.city_id','LEFT')
                    ->where('ua.id', $buyer_id)->select('ua.*, citys.name city_name')->first();

                $tmp = [];
                $tmp1 = [];

                foreach ($values['products'] as $p) {
                    $tmp[] = '<tr>
                        <td>' . $p['sku'] . '</td>
                        <td title="">' . $p['name'] . '</td>
                        <td align="right">' . numbers_dot($p['old_price']) . '</td>
                        <td align="right">' . numbers_dot($p['price']) . '</td>
                        <td align="right">' . numbers_dot($p['comm']) . '</td>
                        <td align="right">' . numbers_dot($p['comm_kdv']) . '</td>
                        <td align="right">' . numbers_dot($p['gain']) . '</td>
                        </tr>';
                }

                foreach ($values['peys'] as $p) {
                    $offer = $this->db->from('offers')->where('product_id', $p['product_id'])->where('user_id', $p['user_id'])->orderby('price', 'DESC')->select('price')->first();

                    $tmp1[] = '<tr>
                        <td>' . $p['sku'] . '</td>
                        <td title="">' . $p['name'] . '</td>
                        <td align="right">' . numbers_dot($p['old_price']) . '</td>
                        <td align="right">' . numbers_dot($offer['price']) . '</td>
                        <td align="right">' . numbers_dot($p['price']) . '</td>
                        <td align="right"></td>
                        <td align="right"></td>
                        </tr>';
                }

                $name = $user['id'] . ' - ' . $user['name'] . ' ' . $user['surname'];
                $mezat = $auc['id'] . ' - ' . $auc['name'];

                $user['address'] .= ' '.$user['state']. ' / '.$user['city_name'];

                $pdfreplace = [$LOGO, $this->settings['company_address'], $this->settings['company_phone'], $this->settings['company_email'], $name, $user['phone'], $user['username'], $user['address'],
                    $mezat, date("d.m.Y H:i", $auc['start_time']),
                    (count($values['peys']) + count($SALED[$buyer_id])),
                    count($values['products']),
                    numbers_dot(array_sum($values['price'])),
                    numbers_dot(array_sum($values['totalprice'])),
                    numbers_dot(array_sum($values['commission'])),
                    numbers_dot(array_sum($values['commissionkdv'])),
                    implode('', $tmp),
                    implode('', $tmp1),
                ];

                $pdfhtml = file_get_contents('views/email/tr/buyer_pdf.html');

                $pdfhtml = str_replace($pdfsearch, $pdfreplace, $pdfhtml);

                $dompdf = new Dompdf($options);
                $dompdf->setPaper('A4', 'orientation');
                $dompdf->loadHtml(mb_convert_encoding($pdfhtml, 'HTML-ENTITIES', 'UTF-8'));
                $dompdf->render();

                $pdf_gen = $dompdf->output();

                file_put_contents('data/pdf/' . $filename, $pdf_gen);

                $bank = $this->db->from('payment_banks')->where('status', 1)->first();

                $replace = [$user['name'] . ' ' . $user['surname'], $this->settings['company_name'], $auc['name'], date("d.m.Y H:i", $auc['start_time']),
                    $bank['name'], $bank['bank_name'], $bank['hesap_no'], $bank['sube_name'], $bank['sube_code'], $bank['iban']
                ];

                $html = file_get_contents('views/email/tr/buyer.html');

                $html = str_replace($search, $replace, $html);

                if ($user['username']) {
                    if($sendemail) {
                        $this->db->insert('crons')->set([
                            'username' => $user['username'],
                            'header' => $auc['name'] . ' / ' . date("d.m.Y H:i", $auc['start_time']) . ' - Alıcı Raporu',
                            'html' => $html,
                            'filename' => $filename,
                            'ty' => 0,
                            'status' => 0,
                        ]);
                    }
                    /* $this->db->insert('crons')->set([
                    'username' => $this->settings['company_email'],
                    'header' => $auc['name']. ' / '.date("d.m.Y H:i", $auc['start_time']).' - Alıcı Raporu ('.$user['username'].')',
                    'html' => $html,
                    'filename' => $filename,
                    'ty' => 0,
                    'status' => 0,
                    ]);*/

                    if(!$beforeFile) {
                        $this->db->insert('reports_pdf')->set([
                            'auction_id' => $auc['id'],
                            'user_id' => $user['id'],
                            'username' => $user['username'],
                            'name' => $user['name'] . ' ' . $user['surname'],
                            'filename' => $filename,
                            'seller' => 0,
                            'create_time' => time(),
                        ]);
                    }
                }


            }
        }


        @header("Location: ".BASEURL."data/pdf/".$filename.'?'.time());
        exit;

    }

    public function manuelseller($id,$userid) {

        $auctions = $this->db->from('auctions')->where('id',$id)->run();

        $search = ['{{NAME}}','{{PHONE}}','{{MAIL}}','{{MEZAT}}','{{DATE}}','{{TOTALURUN}}','{{SALEURUN}}','{{TOTALPRICE}}','{{COMMISSION}}','{{COMMISSIONKDV}}','{{GAIN}}','{{PRODUCTS}}'];

        foreach($auctions as $auc) {
            $products = $this->db->from('products')->where('sale',0,'>')->where('auction_id',$auc['id'])->where('status',2)->run();
            foreach($products as $pro) {
                $komm = (($pro['price'] * $auc['sell_comm']) / 100);
               // $komm_kdv = ($komm * 0.18);

                $sellers[$pro['seller']]['products'][] = [
                    'sku' => $pro['sku'],
                    'name' => $pro['name'],
                    'price' => $pro['price'],
                    'old_price' => $pro['old_price'],
                    'comm' => numbers($komm),
                    'comm_kdv' => numbers($komm_kdv),
                    'gain' => numbers($pro['price'] - (numbers($komm + $komm_kdv))),
                ];

                $sellers[$pro['seller']]['totalprice'][] = numbers($pro['price']);
                $sellers[$pro['seller']]['commission'][] = numbers($komm);
                $sellers[$pro['seller']]['commissionkdv'][] = numbers($komm_kdv);
                $sellers[$pro['seller']]['gain'][] = numbers($pro['price'] - (numbers($komm + $komm_kdv)));
            }



            foreach($sellers as $seller_id => $values) {
                if($userid && $seller_id != $userid) continue;
                $user = $this->db->from('users')->where('id',$seller_id)->first();

                $tmp = [];

                foreach($values['products'] as $p) {
                    $tmp[] = '<tr>
                    <td>'.$p['sku'].'</td>
                    <td title="">'.$p['name'].'</td>
                    <td align="right">'.numbers_dot($p['old_price']).'</td>
                    <td align="right">'.numbers_dot($p['price']).'</td>
                    <td align="right">'.numbers_dot($p['comm']).'</td>
                    <td align="right">'.numbers_dot($p['comm_kdv']).'</td>
                    <td align="right">'.numbers_dot($p['gain']).'</td>
                    </tr>';
                }

                $totalProduct = $this->db->from('products')->where('auction_id',$auc['id'])->select("COUNT(id) total")->where('seller',$seller_id)->total();

                $name = $user['id'].' - '.$user['name'].' '.$user['surname'];
                $mezat = $auc['id'].' - '.$auc['name'];

                $replace = [$name,$user['phone'],$user['username'],$mezat,date("d.m.Y H:i",$auc['start_time']),$totalProduct,count($values['products']),
                    numbers_dot(array_sum($values['totalprice'])),
                    numbers_dot(array_sum($values['commission'])),
                    numbers_dot(array_sum($values['commissionkdv'])),
                    numbers_dot(array_sum($values['gain'])),
                    implode('',$tmp)
                ];


                $html = file_get_contents('views/email/tr/seller.html');

                $html = str_replace($search,$replace,$html);
                echo $html;
                die;
               /*
                if($user['username']) {

                    $this->db->insert('crons')->set([
                        'username' => $user['username'],
                        'header' => 'Müzayede Satılanlarınız',
                        'html' => $html,
                        'ty' => 0,
                    ]);
                     $this->db->insert('crons')->set([
                    'username' => $this->settings['company_email'],
                    'header' => $auc['name'].' Müzayede Satıcı Raporu ('.$user['username'].')',
                    'html' => $html,
                    'ty' => 0,
                    ]);

                    $this->db->insert('reports_pdf')->set([
                        'auction_id' => $auc['id'],
                        'user_id' => $user['id'],
                        'username' => $user['username'],
                        'name' => $user['name'].' '.$user['surname'],
                        'html' => $html,
                        'seller' => 1,
                        'create_time' => time(),
                    ]);
                }
*/
            }

            unset($sellers);
        }


    }

    public function calcbuyer($id=0) {

        $auction = $this->db->from('auctions')->where('id',$id)->first();
        $products = $this->db->from('products')->where('sale',0,'>')->where('auction_id',$id)->where('status',2)->run();
        foreach($products as $pro) {

            $komm = (($pro['price'] * $auction['buy_comm']) / 100);
            $komm_kdv = ($komm * 0.18);

            $gain = numbers($pro['price'] + (numbers($komm + $komm_kdv)));

            $this->db->insert('balances')->set([
                'user_id' => $pro['sale'],
                'product_id' => $pro['id'],
                'ty' => 0,
                'create_time' => time(),
                'price' => $gain
            ]);
        }
    }

    public function calcseller($id=0) {

        $auction = $this->db->from('auctions')->where('id',$id)->first();
        $products = $this->db->from('products')->where('sale',0,'>')->where('auction_id',$id)->where('status',2)->run();
        foreach($products as $pro) {
            $komm = (($pro['price'] * $auction['sell_comm']) / 100);
            $komm_kdv = ($komm * 0.18);

            $gain = numbers($pro['price'] - (numbers($komm + $komm_kdv)));

            $this->db->insert('balances')->set([
                'user_id' => $pro['seller'],
                'product_id' => $pro['id'],
                'ty' => 1,
                'create_time' => time(),
                'price' => $gain
            ]);
        }
    }

    public function checklot() {
        if($id = $this->input->post('id')) {
            $nextLot = $this->settings['wait_live'];
            $pro = $this->db->from('products')->where('id',$id)->select('auction_id,sku')->first();
            file_put_contents('next.txt',print_r($pro,1), FILE_APPEND);
            if($pro) {
                $sku = ($pro['sku']+$nextLot);
                $val = $this->db->from('products')->where('sku',$sku,'=')->select('id,sku,name')->where('auction_id',$pro['auction_id'])->orderby('sku','ASC')->first();
                if($val['id']) {

                    $sms_search = ['{{NAME}}','{{LOT}}', '{{URL}}'];

                    $url = BASEURL.parent::get_seo_link('auctions',$pro['auction_id'],'live');

                    $lives = $this->db->from('users_live ul')->join('users u','u.id = ul.user_id','LEFT')->select('ul.id, u.name, u.surname, u.phone')->where('ul.product_id',$val['id'])->where('ul.status',0)->where('u.sms',1)->run();
                    foreach($lives as $live) { $IDS[] = $live['id']; }

                    if($IDS)  $this->db->update('users_live')->where('id',$IDS,'IN')->set(['status'=>1]);

                    foreach($lives as $live) {
                        $sms_replace = [$live['name'].' '.$live['surname'], $val['name'], $url];
                        $sms_html = str_replace($sms_search,$sms_replace,$this->settings['sms_wait_live']);

                        if($live['phone']) {
                            sendSMS($live['phone'],$sms_html);
                        }
                    }

                    unset($IDS);
                }
            }
        }
    }
}