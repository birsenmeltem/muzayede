<?php
class Model
{
    public $db;
    public $input;
    public $session;
    public $settings;

    public function __construct(){
        $this->session = json_decode(json_encode($_SESSION));
        $this->cookie = json_decode(json_encode($_COOKIE));

        $this->db = new Database();
        $this->settings = $this->db->from('settings')->where('id',1)->first();
        $this->MainLang = $this->db->from('langs')->where('status',1)->where('main',1)->first();
        $this->ActiveLang = ($this->cookie->langs) ? $this->cookie->langs :  $this->MainLang['flag'];
        if(Session::fetch('langs')) $this->ActiveLang = Session::fetch('langs');
        $this->db->MainLang($this->MainLang['flag']);
        $this->db->SetLang($this->ActiveLang);
        $this->input = new Input();

        define('CACHE_TIME',$this->settings['cache_time']);

        if($this->settings['SMTP']) {
            define('SMTPHEADER',$this->settings['seo_title']);
            define('SMTPHOST',$this->settings['SMTPhost']);
            define('SMTPPORT',$this->settings['SMTPport']);
            define('SMTPUSER',$this->settings['SMTPusername']);
            define('SMTPPASS',$this->settings['SMTPpassword']);
        }

        if($this->settings['sms_username']) {
            define('SMSUSER',$this->settings['sms_username']);
            define('SMSPASSWORD',$this->settings['sms_password']);
            define('SMSHEADER',$this->settings['sms_header']);
        }

        if(!file_exists('langs/'. $this->ActiveLang . '.php')) {
            if($this->ActiveLang == $this->MainLang['flag'])
                die(strtoupper($this->ActiveLang).' dil dosyası bulunamadı!');
            else $this->ActiveLang = $this->MainLang['flag'];
        }
        define('ACTIVELANG',$this->ActiveLang);
        include_once 'langs/'. $this->ActiveLang . '.php';
        $this->vars = $translate;

        $this->ActiveCurrency = $this->db->from('currencys')->select('id,name,code,prefix_symbol, suffix_symbol')->where("id = (SELECT currency_id FROM langs WHERE flag='{$this->ActiveLang}' LIMIT 1)",null)->first();
        $this->MainCurrency = $this->db->from('currencys')->select('id,name,code,prefix_symbol, suffix_symbol')->where("id = (SELECT currency_id FROM langs WHERE flag='{$this->MainLang['flag']}' LIMIT 1)",null)->first();

        setlocale(LC_ALL, ACTIVELANG.'_'.strtoupper(ACTIVELANG).'.UTF-8', ACTIVELANG.'_'.strtoupper(ACTIVELANG), ACTIVELANG);
    }

    public function get_main_categories() {
        $cats = self::get_categories();
        foreach((array)$cats as $k => $val) {
            $val['url'] = self::get_seo_link('categories',$val['id']);
            unset($val['children']);
            $cats[$k] = $val;
        }

        return $cats;
    }

    public function getLangs(){
        return $this->db->from('langs')->where('status',1)->orderby('rows','ASC')->run();
    }

    public function get_seo_link($controller,$record_id, $method = 'view'){
        $url = $this->db->from('seo_links')->select('url')->where('controller',$controller)
        ->where('method',$method)
        ->where('record_id',$record_id)
            ->orderby("CASE WHEN lang LIKE '%".$this->ActiveLang."%' then 0 else 100 END, id",'DESC')
            ->first();
        return $url['url'];
    }

    public function topmenu($parent=0){
        $values = $this->db->from('pages')->where('yer',0,'=')->where('parent',$parent,'=')->where('status',1)->orderby('rows','ASC')->run();
        if($values)
        {
            if($parent) $tmp[] = '<ul>';
            foreach($values as $value)
            {
                $link = self::get_seo_link('pages',$value['id']);
                $tmp[] = '<li><a href="'.rtrim(BASEURL,'/').'/'.$link.'">'.$value['name'].'</a>';
                $tmp[] = self::topmenu($value['id']);
                $tmp[] = '</li>';
            }
            if($parent) $tmp[] = '</ul>';

            if($tmp) return implode('',$tmp);
        }
    }

    public function altmenu($parent=0){
        $values = $this->db->from('pages')->where('yer',1)->where('parent',$parent)->where('status',1)->orderby('rows','ASC')->run();
        if($values)
        {
            if($parent) $tmp[] = '<ul>';
            foreach($values as $value)
            {
                $link = self::get_seo_link('pages',$value['id']);
                $tmp[] = '<li><a href="'.rtrim(BASEURL,'/').'/'.$link.'">'.$value['name'].'</a>';
                $tmp[] = self::topmenu($value['id']);
                $tmp[] = '</li>';
            }
            if($parent) $tmp[] = '</ul>';

            if($tmp) return implode('',$tmp);
        }
    }

    public function get_pro_pictures($id,$limit=1){
        $val = $this->db->from('products_images')->where('product_id',$id)->orderby('rows','ASC')->limit(0,$limit)->run();
        if(!$val) $val[] = ['id' => 0, 'picture' => 'noimg.jpg'];
        return $val;
    }

    public function get_categories(){
        if(!$values = get_static_cache(__METHOD__)) {
            $data = $this->db->from('categories')->select('id,name,parent_id,texts,mainpage,mainpage_rows,status')->where('status',1)->orderby('rows','ASC')->run();
            $values = GenerateCategoryList($data);
            set_static_cache(__METHOD__,$values);
        }

        return $values;
    }

    public function parentCat($id){
        $sonuc = 0;
        $ust = $this->db->from('categories')->select('parent_id')->where('id',$id)->where('status',1)->first();
        return $ust['parent_id'];

    }

    public function parentsCat($id){
        $sonuc = array();
        $sonUst = $id;

        do {
            $sonUst = $this->parentCat($sonUst);
            if($sonUst != 0){
                array_push($sonuc, $sonUst);
            }
        } while($sonUst != 0);

        return $sonuc;
    }

    public function catIDList($id=0){
        $results = $this->db->from('categories')->select("id, FIND_IN_SET(".$id.",parent_id) AS seviye")->having("seviye=1")->run();

        $kategoriid_dizi = array();

        foreach($results as $sonuc)
        {
            $kategori_id       = $sonuc["id"];
            $kategoriid_dizi[] = $kategori_id;
            $b = $this->catIDList($kategori_id);
            for ($j=0; $j<count($b); $j++)
            {
                $kategoriid_dizi[] = $b[$j];
            }
        }
        return $kategoriid_dizi;
    }

    public function save_comments($page_id){
        $time = time();

        $sess = Session::fetch('comment_'.$page_id);
        $c = $this->input->post('c',true);

        if(!$sess || $sess<$time) {
            if($c['subject'] && $c['detail'])
            {
                $c['name'] = Session::fetch('user','name').' '.Session::fetch('user','surname');
                $c['email'] = Session::fetch('user','username');
                $c['product_id'] = $page_id;
                $c['user_id'] = Session::fetch('user','id');
                $c['create_time'] =time();
                $c['status'] = 0;
                $c['ipaddress'] = USER_IP;
                $this->db->insert('comments')->set($c);
                $this->setMessage('Yorumunuz başarı ile kayıt edilmiştir. Yönetici onayından sonra yayınlanacaktır.','success');
                Session::set('comment_'.$page_id,$time + 120);
                return true;
            } else {
                $this->setMessage('Lütfen bütün alanları doldurunuz !','danger');
            }
        }
        else
        {
            $this->setMessage('Arka arkaya yorum yapamazsınız. Lütfen '.round(($sess - $time)  / 60).' dk. geçmesini bekleyiniz.!','danger');
        }

        return $c;
    }

    public function mainpage_products($auctions = null){

        if($auctions) {
            foreach($auctions as $au) $category_ids[] = $au['id'];
        }
        if($category_ids) {
            $this->db->from('products p')->select('a.end_time,a.status a_status,p.auction_id, p.id,p.sku,p.category_id,p.brand_id,p.name,p.price,p.old_price,p.currency_id, (SELECT picture FROM products_images WHERE product_id=p.id ORDER BY `rows` ASC, id ASC LIMIT 1) picture,
            (SELECT code FROM currencys WHERE id=p.currency_id) currency_code')
            ->join('auctions a','p.auction_id = a.id','LEFT')
            ->join('total_favorites tf','p.id = tf.product_id','LEFT');

            $this->db->where('p.auction_id',$category_ids,'IN')
            ->where('a.status',1);
            $this->db->where('p.status',1)
            ->orderby('tf.total DESC, a.end_time','ASC')
            ->limit(0,20);

            $data = $this->db->run();
            $EXCHANGE = [];

            foreach($data as $key => $value){
                //if($value['price'] == '0.00') $value['price'] = $value['old_price'];
                $value['url'] = self::get_seo_link('products',$value['id']);
                $value['end_date'] = strftime("%d %B, %H:%M",$value['end_time']);
                $value['date'] = date("Y/m/d H:i:s",$value['end_time']);
                $value['start_live'] = strftime("%d %B, %H:%M",strtotime("+2 minutes",$value['end_time']));
                self::calcProductPrice($value,$EXCHANGE);
                $data[$key] = $value;
            }
            return $data;
        }
    }

    public function GetCountrys() {
        return $this->db->from('countrys')->where('status',1)->orderby('rows ASC,name','ASC')->run();
    }

    public function get_citys($country_id) {
        return $this->db->from('citys')->where('status',1)->where('country_id',$country_id)->orderby('name','ASC')->run();
    }

    public function get_payment_banks(){
        return $this->db->from('payment_banks')->where('status',1)->run();
    }

    public function google_ads(){

        header("Content-type: text/xml; charset=utf-8");

        $tmp[] = '<?xml version="1.0"?>
        <rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
        <channel>
        <title>'.$this->settings['seo_title'].'</title>
        <link>'.BASEURL.'</link>
        <description>'.$this->settings['seo_desc'].'</description>';

        $arg = [
            'where' => [
                ['google_ads',1,'=','&&'],
                ['status',1,'=','&&'],
            ],
            'perpage' => 100000
        ];

        $values = self::GetProducts($arg);

        foreach($values['records'] as $pro) {
            if($pro['sale_currency_code'] == 'TRY') $pro['sale_currency_code'] = 'TL';
            $cats = explode(',',$pro['category_id']);
            if(!$pro['shortdetail']) $pro['shortdetail'] = 'Ürün açıklaması bulunamadı !';
            $tmp[] = '<item>
            <id>'.$pro['sku'].'</id>
            <title>'.str_replace('&','&amp;',$pro['name']).'</title>
            <description>'.strip_tags($pro['shortdetail']).'</description>
            <link>'.BASEURL . $pro['url'].'</link>
            <g:image_link>'.BASEURL.'data/products/'.$pro['id'].'/'.$pro['picture'].'</g:image_link>
            <g:condition>new</g:condition>
            <g:availability>in stock</g:availability>
            <g:price>'.$pro['sale_price'].' '.$pro['sale_currency_code'].'</g:price>';
            if($pro['gtin']) {
                $tmp[] = '<g:gtin>'.$pro['gtin'].'</g:gtin>';
            }
            if($pro['brand_id']) {
                $brand= $this->db->from('brands')->select('name')->where('id',$pro['brand_id'])->first();
                $tmp[] = '<g:brand>'.$brand['name'].'</g:brand>';
            }
            if($cats[0]) {

                $cat= $this->db->from('categories')->select('name')->where('id',$cats[0])->first();
                if($cat) {
                    $tmp[] = '<g:product_type>'.str_replace('&','&amp;',$cat['name']).'</g:product_type>';
                }
            }

            $tmp[] = '</item>';
        }

        $tmp[] = '</channel>
        </rss>';
        echo implode("\r\n",$tmp);
    }

    public function facebook_ads(){
        header("Content-type: text/xml; charset=utf-8");

        $tmp[] = '<?xml version="1.0"?>
        <rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
        <channel>
        <title>'.$this->settings['seo_title'].'</title>
        <link>'.BASEURL.'</link>
        <description>'.$this->settings['seo_desc'].'</description>';

        $arg = [
            'where' => [
                ['facebook_ads',1,'=','&&'],
                ['status',1,'=','&&'],
            ],
            'perpage' => 100000
        ];

        $values = self::GetProducts($arg);

        foreach($values['records'] as $pro) {

            $cats = explode(',',$pro['category_id']);
            if(!$pro['shortdetail']) $pro['shortdetail'] = 'Ürün açıklaması bulunamadı !';
            $tmp[] = '<item>
            <id>'.$pro['sku'].'</id>
            <title>'.str_replace('&','&amp;',$pro['name']).'</title>
            <description>'.strip_tags($pro['shortdetail']).'</description>
            <link>'.BASEURL . $pro['url'].'</link>
            <g:image_link>'.BASEURL.'data/products/'.$pro['id'].'/'.$pro['picture'].'</g:image_link>
            <g:condition>new</g:condition>
            <g:availability>in stock</g:availability>
            <g:price>'.$pro['sale_price'].' '.$pro['sale_currency_code'].'</g:price>';
            if($pro['gtin']) {
                $tmp[] = '<g:gtin>'.$pro['gtin'].'</g:gtin>';
            }
            if($pro['brand_id']) {
                $brand= $this->db->from('brands')->select('name')->where('id',$pro['brand_id'])->first();
                $tmp[] = '<g:brand>'.$brand['name'].'</g:brand>';
            }
            if($cats[0]) {

                $cat= $this->db->from('categories')->select('name')->where('id',$cats[0])->first();
                if($cat) {
                    $tmp[] = '<g:product_type>'.str_replace('&','&amp;',$cat['name']).'</g:product_type>';
                }
            }

            $tmp[] = '</item>';
        }

        $tmp[] = '</channel>
        </rss>';
        echo implode("\r\n",$tmp);
    }

    protected function calcCargoCompanys($data = null) {

        $cargoCompany = Cargo::calculate($data, $this->db);

        return $cargoCompany;
    }

    protected function GetProducts($arg = []){

        $perpage = $this->settings['show_product_page'];
        $activepage = max(1,intval($this->input->get('page')));

        $TT = $this->db->from('products p')->where('p.status',0,'!=');


        if($arg['where']) {
            if(is_array($arg['where'])) {
                foreach($arg['where'] as $wh) {
                    $TT->where($wh[0],$wh[1],$wh[2],$wh[3]);
                }
            }
        }

        $TT->select('COUNT(id) total');
        $total = $TT->first();

        $this->db->from('products p')->where('p.status',0,'!=');


        if($arg['where']) {
            if(is_array($arg['where'])) {
                foreach($arg['where'] as $wh) {
                    $this->db->where($wh[0],$wh[1],$wh[2],$wh[3]);
                }
            }
        }

        if($arg['orderby']) {
            $this->db->orderby($arg['orderby'][0],$arg['orderby'][1]);
        }

        if(intval($arg['perpage'])) {
            $perpage = $arg['perpage'];
        }

        if(isset($arg['page'])) {
            $activepage = $arg['page'];
        }

        $limit = $this->db->pagination($total['total'], $perpage, $activepage );

        $this->db->join('currencys c','c.id = p.currency_id','LEFT')
        ->join('total_offers o','o.product_id = p.id','LEFT')
        ->join('total_favorites f','f.product_id = p.id','LEFT');

        $this->db->select('p.id,p.sku,p.category_id,p.brand_id,p.auction_id, p.shortdetail, p.name,p.price,p.old_price,p.currency_id, p.sale, p.status,
             c.code currency_code, o.total peyTotal, f.total followTotal
        ');

        $this->db->limit($limit['start'],min(500,$limit['limit']));

        $data = $this->db->run();
        $EXCHANGE = [];

        foreach($data as $key => $value){
            $value['url'] = self::get_seo_link('products',$value['id']);
            $value['brand'] = $this->db->from('brands')->where('id',$value['brand_id'])->first();
            //$value['peyTotal'] = $this->db->from('offers')->select('COUNT(product_id) Total')->where('product_id',$value['id'])->total();
            //$value['followTotal'] = $this->db->from('users_favorites')->select('COUNT(product_id) Total')->where('product_id',$value['id'])->total();
            if(Session::fetch('user','id')) $value['me_follow'] = $this->db->from('users_favorites')->select('COUNT(id) total')->where('product_id',$value['id'])->where('user_id',Session::fetch('user','id'))->total();
            if(Session::fetch('user','id')) $value['me_live'] = $this->db->from('users_live')->select('COUNT(id) total')->where('product_id',$value['id'])->where('user_id',Session::fetch('user','id'))->total();
            $value['follows'] = self::GetProductReviews($value['id']);
            $value['offers'] = self::GetProductOffers($value['id']);
            $value['lotown'] = ($value['sale'] == Session::fetch('user','id')) ? 1 : 0;
            if(Session::fetch('user','id')) $value['pey'] = $this->db->from('offers')->select('id,price')->where('product_id',$value['id'])->where('user_id',Session::fetch('user','id'))->orderby('create_time','DESC')->first();
            self::calcProductPrice($value,$EXCHANGE);
            $value['pictures'] = self::get_pro_pictures($value['id'],100);
            $data[$key] = $value;
        }

        return  [
            'total' => $total['total'],
            'records' => $data,
            'pagination' => $this->db->showPagination($arg['url']),
        ];
    }

    protected function calcProductPrice(&$product, &$EXCHANGE){
        if($this->ActiveCurrency['id'] != $product['currency_id']){

            if($exchange_rate = self::GetExchangeRate($product['currency_code'],$EXCHANGE)){

                $rate = $exchange_rate[$this->ActiveCurrency['code']];
                $product['sale_price'] = numbers($product['price'] * $rate);
                if($product['old_price'] && $product['old_price']!=0.00) $product['sale_old_price'] = numbers($product['old_price'] * $rate);
                $product['sale_currency_id'] = $this->ActiveCurrency['id'];
                $product['sale_currency_code'] = $this->ActiveCurrency['code'];
                $product['sale_currency_prefix'] = $this->ActiveCurrency['prefix_symbol'];
                $product['sale_currency_suffix'] = $this->ActiveCurrency['suffix_symbol'];
                $product['sale_exchange_rate'] = $rate;
            }
        } else {
            $product['sale_price'] = numbers($product['price']);
            if($product['old_price'] && $product['old_price']!=0.00)  $product['sale_old_price'] = numbers($product['old_price']);
            $product['sale_currency_id'] = $this->ActiveCurrency['id'];
            $product['sale_currency_code'] = $this->ActiveCurrency['code'];
            $product['sale_currency_prefix'] = $this->ActiveCurrency['prefix_symbol'];
            $product['sale_currency_suffix'] = $this->ActiveCurrency['suffix_symbol'];
            $product['sale_exchange_rate'] = 1;
        }
    }

    public function CreateOrderMail($order_id) {

        $MailFile = BASE . 'views/email/'.ACTIVELANG . '/order.html';
        if(file_exists($MailFile)) {
            $order = $this->db->from('orders')->where('id',$order_id)->first();
            if($order) {
                $currency = $this->db->from('currencys')->where('id',$order['currency_id'])->first();

                $KDV = $pro_html = [];

                $products = $this->db->from('orders_products')->where('order_id',$order['id'])->run();
                foreach($products as $pro) {
                    $picture = $this->db->from('products_images')->where('product_id',$pro['product_id'])->orderby('rows','ASC')->first();
                    $total_price = ($pro['price']*$pro['quantity']);

                    $komm = (($total_price * $this->settings['commission']) / 100);
                    $komm_kdv = ($komm * 0.18);

                    $total_price = $total_price + numbers($komm + $komm_kdv);


                    $pro_html[] = '<tr align="center" style=" font-size:12px; font-weight:bold;border-top:1px solid #e5e5e5; ">
                    <td style="padding:15px 0; vertical-align:middle;">
                    <img style="display:block;" height="105" src="'.BASEURL.'data/products/'.$pro['product_id'].'/'.$picture['picture'].'" />
                    </td>
                    <td style="vertical-align:middle">'.$pro['name'].'</td>
                    <td style="vertical-align:middle">'.$pro['sku'].'</td>
                    <td style="vertical-align:middle">'.$pro['quantity'].'</td>
                    <td style="vertical-align:middle">'.numbers_dot($komm).'</td>
                    <td style="vertical-align:middle">'.numbers_dot($komm_kdv).'</td>
                    <td style="vertical-align:middle">'.$currency['prefix_symbol'].''.numbers_dot($total_price).''.$currency['suffix_symbol'].'</td>
                    </tr>';
                    if(!$pro['kdv']) $pro['kdv'] = 18;

                    $kdvsiz = ($total_price/"1.{$pro['kdv']}");
                    $KDV[] = ($total_price-$kdvsiz);
                }

                $shipping  = $this->db->from('orders_address oa')
                ->join('citys c','c.id = oa.city_id','LEFT')
                ->join('countrys co','co.id = oa.country_id','LEFT')
                ->select('oa.user_id,oa.name,oa.address,oa.postacode,c.name city_name,co.name country_name,oa.state,oa.phone')
                ->where('oa.order_id',$order['id'])
                ->where('oa.shipping',1)
                ->first();
                $billing  = $this->db->from('orders_address oa')
                ->join('citys c','c.id = oa.city_id','LEFT')
                ->join('countrys co','co.id = oa.country_id','LEFT')
                ->select('oa.user_id,oa.name,oa.address,oa.postacode,c.name city_name,co.name country_name,oa.state,oa.phone')
                ->where('oa.order_id',$order['id'])
                ->where('oa.billing',1)
                ->first();

                $ship_address = $shipping['address'].' '.$shipping['state'].' / '.$shipping['city_name'].' / '.$shipping['country_name'];
                $bill_address = $billing['address'].' '.$billing['state'].' / '.$billing['city_name'].' / '.$billing['country_name'];

                switch($order['payment_type']) {
                    case '1':
                        $payment_type = $this->vars['kredikartiodeme'];
                        $bank = $this->db->from('virtual_pos')->select('name')->where('id',$order['payment_id'])->first();
                        break;
                    case '2':
                        $payment_type = $this->vars['havaleodeme'];
                        $bank = $this->db->from('payment_banks')->select('bank_name name')->where('id',$order['payment_id'])->first();
                        break;
                    case '3':
                        $payment_type = $this->vars['kapidanakitodeme'];
                        break;
                    case '4':
                        $payment_type = $this->vars['kapidakredikartiodeme'];
                        break;
                }
                $search = [
                    '{{BASEURL}}','{{LOGO}}','{{NAME}}','{{SURNAME}}','{{DATE}}','{{CODE}}','{{PRODUCTLIST}}',
                    '{{CARGO_PRICE}}','{{KDV_PRICE}}','{{TOTAL_PRICE}}',
                    '{{BILL_NAME}}','{{BILL_PHONE}}','{{BILL_ADDRESS}}','{{SHIP_NAME}}','{{SHIP_PHONE}}','{{SHIP_ADDRESS}}',
                    '{{PAYMENT_TYPE}}','{{PAYMENT_PRICE}}','{{BANK_NAME}}','{{INSTALLMENT}}','{{CURR_PREFIX}}','{{CURR_SUFFIX}}'
                ];

                $replace = [
                    BASEURL,BASEURL . 'data/uploads/'.$this->settings['logo'],Session::fetch('user','name'),Session::fetch('user','surname'),date("d.m.Y H:i",$order['create_time']),$order['code'],implode('',$pro_html),
                    $order['cargo_price'],numbers(array_sum($KDV)),$order['price'],
                    $billing['name'], $billing['phone'],$bill_address,$shipping['name'], $shipping['phone'],$ship_address,
                    $payment_type,$order['payment_price'],$bank['name'],$order['installment'],$currency['prefix_symbol'],$currency['suffix_symbol']
                ];


                $content = file_get_contents($MailFile);
                $content = str_replace($search,$replace,$content);
                $subject = str_replace($search,$replace,$this->vars['order_mail_subject']);
                sendMail(Session::fetch('user','username'),$subject,$content);
            }
        }
    }

    /**
    * todo: Optimize edilecek (Precalc işlemi uygulanacak)
    *
    * @param mixed $product_id
    */
    public function GetProductReviews($product_id){
        $val = $this->db->from('users_favorites')
        ->select('COUNT(id) total')
        ->where('product_id',$product_id)->total();

        return max(0,$val);
    }

    public function GetProductOffers($product_id){
        $val = $this->db->from('offers')
        ->select('COUNT(id) total')
        ->where('product_id',$product_id)->total();

        return max(0,$val);
    }

    protected function filter_append($FILTER,&$WHERE){
        if(is_array($FILTER))
        {

            if($FILTER['brand']) {
                $WHERE[] = ['p.brand_id',$FILTER['brand'],'IN','&&'];
            }
            if($FILTER['name']) {
                $WHERE[] = ['(p.name',addslashes($FILTER['name']),'LIKE','&&'];
                $WHERE[] = ['p.shortdetail',addslashes($FILTER['name']),'LIKE','||'];
                $WHERE[] = ['p.detail',addslashes($FILTER['name']),'LIKE','||'];
                $WHERE[] = ['p.id',0,'!=',') &&'];
            }

            if($FILTER['sku']) {
                $WHERE[] = ['p.sku',$FILTER['sku'],'=','&&'];
            }

            unset($FILTER['brand'],
                $FILTER['category_id'],
                $FILTER['SAVE']
            );

        }
    }

    protected function combinations($arrays, $i = 0){
        if (!isset($arrays[$i])) {
            return array();
        }
        if ($i == count($arrays) - 1) {
            return $arrays[$i];
        }

        $tmp = $this->combinations($arrays, $i + 1);

        $result = array();

        foreach ($arrays[$i] as $v) {
            foreach ($tmp as $t) {
                $result[] = is_array($t) ?
                array_merge(array($v), $t) :
                array($v, $t);
            }
        }

        return $result;
    }

    public function setMessage($text,$class='success'){
        $info = Session::fetch('info');
        $info[] = '<div class="alert alert-'.$class.'" style="margin:10px 0">
        '.$text.'
        </div>';
        Session::set('info',$info);
    }

    public function showMessages(){
        $info = Session::fetch('info');
        if(is_array($info))
        {
            Session::uset('info');
            return implode('',$info);
        }
    }

    public function GoogleReCaptchaCheck($response){
        $arr = array(
            'secret' => '6Leki7oSAAAAAPRqFrWNGJlhNqiVXVfoiUelxxFd',
            'response' => $response,
            'remoteip' => USER_IP,
        );

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify?'.http_build_query($arr));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
        $curlData = json_decode(curl_exec($curl),true);
        curl_close($curl);
        return ($curlData['success']) ? true : false;
    }

    public function CheckCoupon($code, $totalPrice) {
        if($code) {
            $val = $this->db->from('coupons')->where('code',$code)->where('used','1','!=')->first();
            if($val) {
                if($val['user_id']) {
                    if($val['user_id'] != Session::fetch('user','id')) {
                        $this->setMessage($this->vars['kuponsizeaitdegil'],'danger');
                        return false;
                    }
                }
                if($val['start_time'] > time() || ($val['end_time']!=0 && $val['end_time'] < time())) {
                    $this->setMessage($this->vars['hatalikuponkodu'],'danger');
                    return false;
                }

                $EXCHANGE = [];
                $exchange_rate = self::GetExchangeRate($this->MainCurrency['code'],$EXCHANGE);
                $rate = $exchange_rate[$this->ActiveCurrency['code']];

                $val['cart_limit'] = numbers(($val['cart_limit'] * $rate));

                if($val['cart_limit'] && $val['cart_limit']!=0.00) {
                    if($val['cart_limit']> $totalPrice) {
                        $this->setMessage(sprintf($this->vars['kuponlimit'],$this->ActiveCurrency['prefix_symbol'].$val['cart_limit'].$this->ActiveCurrency['suffix_symbol']),'danger');
                        return false;
                    }
                }

                switch($val['d_type']) {
                    //Yüzdelik
                    case '0':
                        $val['price'] = (($totalPrice * $val['discount']) / 100);
                        break;
                        //Net
                    case '1':
                        $val['price'] = $val['discount'];
                        break;
                }

                $val['currency_code'] = $this->ActiveCurrency['code'];

                return $val;

            } else {
                $this->setMessage($this->vars['hatalikuponkodu'],'danger');
            }
        }
        return false;
    }

    public function GetExchangeRate($code, &$EXCHANGE){
        if($EXCHANGE[$code]) return $EXCHANGE[$code];
        $ex_file = BASE.'data/currency/'.$code.'.cache';
        if(file_exists($ex_file)) {
            return $EXCHANGE[$code] = json_decode(file_get_contents($ex_file),true);
        }
        return $EXCHANGE[$code] = 1;
    }

}
?>
