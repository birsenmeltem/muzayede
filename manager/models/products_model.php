<?php
class Products_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function lists($page){
        global $ProductStatus;

        $perpage = SHOW_LIST_PAGE;

        $WHERE[] = ["id",0,'!=','&&'];

        if($f = $this->input->get('f'))
        {
            if($f['status']!='all') $WHERE[] = ["status",($f['status']),'=','&&'];
            if($f['name']) {
                $WHERE[] = ["name",$f['name'],'LIKE','&&'];
                $WHERE[] = ["sku",$f['name'],'LIKE','||'];
                $WHERE[] = ["barcode",$f['name'],'LIKE','||'];
            }
        }

        if($this->input->post('pid')) {

            $pid = $this->input->post('pid');
            if($pid) {
                foreach($pid as $pp) { self::remove($pp); }
            }
        }

        if(intval($_GET['perpage'])) {
            $perpage = intval($_GET['perpage']);
        }

        $CNT = $this->db->from('products')->select('COUNT(id) Total');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2],$wh[3]);
        $CNT->limit(0,1);
        $count = $CNT->first();

        $pagi = new Pagination();
        $pagi->link = ADMINBASEURL.'products/main/';
        $pagi->count = $count['Total'];
        $pagi->perpage = $perpage;
        $pagi->pg = $page;
        $pagi->startPage();

        $CNT = $this->db->from('products');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2],$wh[3]);
        $results = $CNT->orderby('id','DESC')->limit($pagi->start,$pagi->perpage)->run();

        foreach($results as $key => $result)
        {
            if($result['category_id']) $result['cat'] = $this->db->from('categories')->select("GROUP_CONCAT(name SEPARATOR ', ') names")->in('id',explode(',',$result['category_id']))->first();
            if($result['brand_id']) $result['brand'] = $this->db->from('brands')->select('id,name')->where('id',$result['brand_id'])->first();
            if($result['currency_id']) $result['currency'] = $this->db->from('currencys')->select('prefix_symbol,suffix_symbol')->where('id',$result['currency_id'])->first();

            $picture = $this->db->from('products_images')->select('picture')->where('product_id',$result['id'])->orderby('rows','ASC')->first();
            if($picture) $result['picture'] = $picture['picture'];

            $result['status_name'] = $ProductStatus[$result['status']];

            $results[$key] = $result;

        }
        $data['result'] = $results;
        $data['count'] = $count['Total'];
        $data['pagi'] = $pagi->paginations();
        return $data;
    }

    public function add() {
        if($p = $this->input->post('p')) {
            if(!$isSku) {
                if($p['category_id']) $p['category_id'] = implode(',',$p['category_id']);
                if(!$p['rows']) $p['rows'] = 999;
                if(!$p['price']) $p['price'] = 0;
                if(!$p['old_price']) $p['old_price'] = 0;
                if(!$p['buy_price']) $p['buy_price'] = 0;
                if(!$p['kdv']) $p['kdv'] = 0;
                if(!$p['status']) $p['status'] = 0;
                if(!$p['desi']) $p['desi'] = 0;
                if(!$p['seller']) $p['seller'] = 0;

                $this->db->insert('products')->set($p);
                $this->setMessage('Ürün başarı ile eklenmiştir !','success');

                $id = $this->db->lastId();
                parent::saveURL([
                    'url' => $id.'-'.convertstring($p['name']).'.html',
                    'controller' => 'products',
                    'method' => 'view',
                    'record_id' => $id,
                    'lang' => Session::fetch('ActiveAdminLang','flag'),
                ]);

                mkdir('../data/products/'.$id);
                return $id;

            }
            else
                $this->setMessage('Girdiğiniz Ürün Kodu ile daha önce bir ürün kayıt edilmiştir !','danger');
        }
    }

    public function edit($id) {

        if($p = $this->input->post('p')) {
            if($p['category_id']) $p['category_id'] = implode(',',$p['category_id']);
            if(!$p['price']) $p['price'] = 0;
            if(!$p['old_price']) $p['old_price'] = 0;
            if(!$p['buy_price']) $p['buy_price'] = 0;
            if(!$p['kdv']) $p['kdv'] = 0;
            if(!$p['status']) $p['status'] = 0;
            if(!$p['google_ads']) $p['google_ads'] = 0;
            if(!$p['facebook_ads']) $p['facebook_ads'] = 0;
            if(!$p['desi']) $p['desi'] = 0;
            if(!$p['seller']) $p['seller'] = 0;


            $this->db->update('products')->where('id',$id)->set($p);


            parent::saveURL([
                'url' => $id.'-'.convertstring($p['name']).'.html',
                'controller' => 'products',
                'method' => 'view',
                'record_id' => $id,
                'lang' => Session::fetch('ActiveAdminLang','flag'),
            ]);

            $this->setMessage('Ürün başarı ile güncellenmiştir !','success');
        }
        $data = $this->db->from('products')->where('id',$id)->first();
        if(!$data) return false;

        $data['name'] = htmlentities($data['name']);


        $data['pictures'] = $this->db->from('products_images')->where('product_id',$data['id'])->orderby('rows','ASC')->run();

        $data['url'] = BASEURL . parent::getURL('products',$data['id']);

        return $data;
    }

    public function remove($id){
        $record = self::edit($id);
        if($record) {
            $this->db->delete('seo_links')->where('record_id',$id)->where('controller','products')->done();
            $this->db->delete('products')->where('id',$id)->done();
            $this->db->delete('products_images')->where('product_id',$id)->done();
            $this->db->delete('comments')->where('product_id',$id)->done();
            $this->db->delete('users_favorites')->where('product_id',$id)->done();
            shell_exec('rm -rf ../data/products/'.$id);
            $this->setMessage('Ürün başarı ile silinmiştir !','success');
        }
    }

    public function brands(){
        $brands = $this->db->from('brands')->join('categories','categories.id = brands.category_id','LEFT')->select('brands.id, brands.name, categories.name category_name')->orderby('brands.rows','ASC')->run();

        foreach($brands as $b) {
            $data[$b['category_name']][] = $b;
        }

        return $data;
    }

    public function currencys(){
        return $this->db->from('currencys')->where('status',1)->orderby('id','ASC')->run();
    }

    public function stocktypes(){
        return $this->db->from('stock_types')->orderby('id','ASC')->run();
    }

    public function uploadimage(){
        $id = intval("0".$this->input->post('id'));
        if($id) {
            $allowed = explode(',','jpg,jpeg,gif,png');
            $ext = pathinfo($_FILES['file']['name'][0],PATHINFO_EXTENSION);
            if(in_array(strtolower($ext),$allowed))
            {
                include 'libraries/Upload.php';

                foreach ($_FILES["file"]["error"] as $key => $error) {
                    if ($error == UPLOAD_ERR_OK) {

                        $upload = new Upload([
                            'name' =>  $_FILES["file"]["name"][$key],
                            'tmp_name' =>  $_FILES["file"]["tmp_name"][$key],
                        ]);
                        $upload->tmpfolder = '../data/products/'.$id.'/';
                        if($upload->handle())
                        {
                            $upload->resize($upload->tmpfolder,$upload->newfilename,1500,1500,false,100,$this->settings['watermark']);
                            $upload->resize($upload->tmpfolder,'s_'.$upload->newfilename,600,600,true,100);

                            $p['product_id'] = $id;
                            $p['picture'] = 's_'.$upload->newfilename;
                            $p['big_picture'] = $upload->newfilename;
                            $p['rows'] = 999;
                            $this->db->insert('products_images')->set($p);
                            die($this->db->lastId());
                        }
                    }
                }
            }
        }
    }

    public function removeimage() {
        $id = intval("0".$this->input->post('id'));
        if($id) {
            $picture = $this->db->from('products_images')->where('id',$id)->first();
            if($picture) {
                @unlink('../data/products/'.$picture['product_id'].'/'.$picture['picture']);
                @unlink('../data/products/'.$picture['product_id'].'/'.$picture['big_picture']);
                $this->db->delete('products_images')->where('id',$id)->done();
            }
        }
    }

    public function picturerows($rows){
        if(is_array($rows)) {
            foreach($rows as $r => $id) $this->db->update('products_images')->where('id',$id)->set(['rows'=>$r]);
        }
    }

    public function addvariants($product_id){

        $data = $this->db->from('products')->select('id, category_id')->where('id',$product_id)->first();

        $CATLIST = [];

        $cats = explode(',',$data['category_id']);
        foreach($cats as $cat) {
            $List = parent::parentsCat($cat);
            array_push($List,$cat);
            $CATLIST += $List;
        }

        $variants = $this->db->from('variants')->where('category_id',$CATLIST,'REGEXP REPLACE')->run();

        $var = [];
        $dv = $this->db->from('products_variants')->select('variant_id')->where('product_id',$product_id)->orderby('id','ASC')->run();
        foreach($dv as $d) $var[] = $d['variant_id'];

        return compact('product_id','variants', 'var');

    }

    public function saveprovariants($product_id){

        $this->db->delete('products_variants')->where('product_id',$product_id)->done();
        $this->db->delete('products_variants_stocks')->where('product_id',$product_id)->done();

        $variant = $this->input->post('variant');
        if($variant) {
            foreach($variant as $var) $this->db->insert('products_variants')->set([
                'product_id' => $product_id,
                'variant_id' => $var,
                ]);
        }
    }

    public function products_variants(&$data){
        $data['variants'] = $this->db->from('products_variants pv')->join('variants v','pv.variant_id = v.id','LEFT')->select('pv.*, v.name variant_name')->where('pv.product_id',$data['id'])->orderby('pv.id','ASC')->run();

        if($data['variants']) {
            foreach($data['variants'] as $kv => $vv) {
                $vv['values'] = $this->db->from('variants_values')->where('variant_id',$vv['variant_id'])->orderby('rows ASC, name','ASC')->run();
                $data['variants'][$kv] = $vv;
            }

            $stocks = $this->db->from('products_variants_stocks')->where('product_id',$data['id'])->orderby('id','ASC')->run();

            foreach($stocks as $key => $val) {
                $values = $this->db->from('variants_values')->select("GROUP_CONCAT(name SEPARATOR ' - ') name")->in('id',explode(',',$val['values_ids']))->orderby('id','ASC')->first();
                $stocks[$key]['name'] = $values['name'];
            }

            $data['model_stocks'] = $stocks;

        } else {

            $CATLIST = [];

            $cats = explode(',',$data['category_id']);
            foreach($cats as $cat) {
                $List = parent::parentsCat($cat);
                array_push($List,$cat);
                $CATLIST += $List;
            }

            $gb = $this->db->from('variants')->select('COUNT(id) total')->where('category_id',$CATLIST,'REGEXP REPLACE')->first();
            $data['global_variants'] = $gb['total'];

        }
    }

    public function addmodelstock(){
        $product_id = intval("0".$this->input->post('product_id'));

        $json['status'] = 'failed';
        $json['message'] = 'Geçersiz bir işlem denediniz !';

        if($product_id) {
            $v = $this->input->post('v',true);
            if($v['values_ids']) {
                ksort($v['values_ids']);

                $val = [
                    'product_id' => $product_id,
                    'values_ids' => implode(',',$v['values_ids']),
                    'stock' => intval("0".$v['stock']),
                    'barcode' => $v['barcode'],
                    'sku' => (($v['sku']) ? $v['sku'] : strtoupper(generateRandomString())),
                ];

                $issku = $this->db->from('products_variants_stocks')->where('sku',$val['sku'])->first();
                if(!$issku) {
                    $isIds = $this->db->from('products_variants_stocks')->where('values_ids',$val['values_ids'])->where('product_id',$product_id)->first();
                    if(!$isIds) {
                        $this->db->insert('products_variants_stocks')->set($val);
                        if($this->db->lastId()) {

                            $totalStock = $this->db->from('products_variants_stocks')->select('SUM(stock) Adet')->where('product_id',$product_id)->first();
                            $this->db->update('products')->where('id',$product_id)->set(['stock'=>max(0,$totalStock['Adet'])]);
                            $json['status'] = 'success';
                            unset($json['message']);
                        }
                        else {
                            $json['message'] = 'Sistemsel hata oluştu ! Lütfen yapılan işlemi yöneticiye bildiriniz.';
                        }
                    } else {
                        $json['message'] = 'Bu stok eşleşmesi daha önce yapılmış ! Lütfen "<b>düzenle</b>" butonunu kullanınız';
                    }
                } else {
                    $json['message'] = 'Bu stok kodu bu veya başka üründe daha önce kayıt edilmiştir !';
                }
            }
        }

        die(json_encode($json));
    }

    public function editmodelstock($id) {
        $stock = $this->db->from('products_variants_stocks')->where('id',$id)->first();
        if(!$stock) return false;

        $values = $this->db->from('variants_values')->select("GROUP_CONCAT(name SEPARATOR ' - ') name")->in('id',explode(',',$stock['values_ids']))->orderby('id','ASC')->first();
        $stock['name'] = $values['name'];

        return $stock;

    }

    public function savemodelstock($id) {

        $stock = $this->db->from('products_variants_stocks')->where('id',$id)->first();
        if($stock) {
            $ms = $this->input->post('ms');
            if(!$ms['sku']) $ms['sku'] = strtoupper(generateRandomString());
            $this->db->update('products_variants_stocks')->where('id',$id)->set($ms);

            $totalStock = $this->db->from('products_variants_stocks')->select('SUM(stock) Adet')->where('product_id',$stock['product_id'])->first();
            $this->db->update('products')->where('id',$stock['product_id'])->set(['stock'=>max(0,$totalStock['Adet'])]);

        }
    }

    public function removemodelstock() {
        $id = intval("0".$this->input->post('id'));

        $json['status'] = 'failed';
        $json['message'] = 'Geçersiz bir işlem denediniz !';

        if($id) {
            $stock = $this->db->from('products_variants_stocks')->where('id',$id)->first();
            if($stock) {
                $json['product_id'] = $stock['product_id'];
                $this->db->delete('products_variants_stocks')->where('id',$id)->done();
                $totalStock = $this->db->from('products_variants_stocks')->select('SUM(stock) Adet')->where('product_id',$stock['product_id'])->first();
                $this->db->update('products')->where('id',$stock['product_id'])->set(['stock'=>max(0,$totalStock['Adet'])]);
                $json['status'] = 'success';
                unset($json['message']);
            }
        }
        die(json_encode($json));
    }

    public function variants($page){
        global $Status;

        $perpage = SHOW_LIST_PAGE;

        if($form = $this->input->post('s')) {
            $ids = $this->input->post('id');
            if(!$form['show_filter']) $form['show_filter'] = 0;
            $form['category_id'] = implode(',',$form['category_id']);

            if($ids) {
                $this->db->update('variants')->where('id',$ids)->set($form);
            } else {
                $this->db->insert('variants')->set($form);
                $ids = $this->db->lastId();
            }
            $this->setMessage('Bilgiler başarı ile kayıt edildi.','success');
        }

        $WHERE[] = ["id",0,'!=','&&'];

        if($f = $this->input->get('f'))
        {
            if($f['show_filter']!='all') $WHERE[] = ["show_filter",($f['show_filter']),'=','&&'];
            if($f['name']) {
                $WHERE[] = ["name",$f['name'],'LIKE','&&'];
            }
        }

        if(intval($_GET['perpage'])) {
            $perpage = intval($_GET['perpage']);
        }

        $CNT = $this->db->from('variants')->select('COUNT(id) Total');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2],$wh[3]);
        $CNT->limit(0,1);
        $count = $CNT->first();

        $pagi = new Pagination();
        $pagi->link = ADMINBASEURL.'products/variants/';
        $pagi->count = $count['Total'];
        $pagi->perpage = $perpage;
        $pagi->pg = $page;
        $pagi->startPage();

        $CNT = $this->db->from('variants');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2],$wh[3]);
        $results = $CNT->orderby('id','ASC')->limit($pagi->start,$pagi->perpage)->run();

        foreach($results as $key => $result)
        {
            if($result['category_id']) $result['cat'] = $this->db->from('categories')->select("GROUP_CONCAT(name SEPARATOR ', ') names")->in('id',explode(',',$result['category_id']))->first();
            $result['show_filter_name'] = $Status[$result['show_filter']];

            $results[$key] = $result;

        }
        $data['result'] = $results;
        $data['count'] = $count['Total'];
        $data['pagi'] = $pagi->paginations();
        return $data;
    }

    public function variant_edit($id){
        if($id) {
            return $this->db->from('variants')->where('id',$id)->first();
        }
    }

    public function variant_remove($id){
        $record = self::variant_edit($id);
        if($record) {
            $this->db->delete('variants_values')->where('variant_id',$id)->done();
            $this->db->delete('products_variants')->where('variant_id',$id)->done();
            $this->db->delete('variants')->where('id',$id)->done();
            $this->setMessage('Başarı ile silinmiştir !','success');
        }
    }

    public function variant_values($variant_id, $page){
        global $Status;

        $perpage = SHOW_LIST_PAGE;

        if($form = $this->input->post('s')) {
            $ids = $this->input->post('id');

            if($ids) {
                $this->db->update('variants_values')->where('id',$ids)->set($form);
            } else {
                if(!$form['rows']) $form['rows'] = 999;
                $this->db->insert('variants_values')->set($form);
                $ids = $this->db->lastId();
            }
            $this->setMessage('Bilgiler başarı ile kayıt edildi.','success');
        }

        $WHERE[] = ["variant_id",$variant_id,'=','&&'];

        if($f = $this->input->get('f'))
        {
            if($f['name']) {
                $WHERE[] = ["name",$f['name'],'LIKE','&&'];
            }
        }

        if(intval($_GET['perpage'])) {
            $perpage = intval($_GET['perpage']);
        }

        $CNT = $this->db->from('variants_values')->select('COUNT(id) Total');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2],$wh[3]);
        $CNT->limit(0,1);
        $count = $CNT->first();

        $pagi = new Pagination();
        $pagi->link = ADMINBASEURL.'products/variant_values/'.$variant_id.'/';
        $pagi->count = $count['Total'];
        $pagi->perpage = $perpage;
        $pagi->pg = $page;
        $pagi->startPage();

        $CNT = $this->db->from('variants_values');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2],$wh[3]);
        $results = $CNT->orderby('rows','ASC')->limit($pagi->start,$pagi->perpage)->run();


        $data['result'] = $results;
        $data['count'] = $count['Total'];
        $data['pagi'] = $pagi->paginations();
        return $data;
    }

    public function variant_value_edit($id){
        if($id) {
            return $this->db->from('variants_values')->where('id',$id)->first();
        }
    }

    public function variant_value_remove($id){
        $record = self::variant_value_edit($id);
        if($record) {
            $this->db->delete('products_variants_stocks')->where('values_ids',[$id],'REGEXP REPLACE')->done();
            $this->db->delete('variants_values')->where('id',$id)->done();
            $this->setMessage('Başarı ile silinmiştir !','success');
        }
    }

    public function stocktypes_list($page){

        $perpage = SHOW_LIST_PAGE;

        if($form = $this->input->post('s')) {
            $ids = $this->input->post('id');

            if($ids) {
                $this->db->update('stock_types')->where('id',$ids)->set($form);
            } else {
                $this->db->insert('stock_types')->set($form);
                $ids = $this->db->lastId();
            }
            $this->setMessage('Bilgiler başarı ile kayıt edildi.','success');
        }

        $WHERE[] = ["id",0,'!=','&&'];

        if($f = $this->input->get('f'))
        {
            if($f['name']) {
                $WHERE[] = ["name",$f['name'],'LIKE','&&'];
            }
        }

        if(intval($_GET['perpage'])) {
            $perpage = intval($_GET['perpage']);
        }

        $CNT = $this->db->from('stock_types')->select('COUNT(id) Total');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2],$wh[3]);
        $CNT->limit(0,1);
        $count = $CNT->first();

        $pagi = new Pagination();
        $pagi->link = ADMINBASEURL.'products/stocktypes/';
        $pagi->count = $count['Total'];
        $pagi->perpage = $perpage;
        $pagi->pg = $page;
        $pagi->startPage();

        $CNT = $this->db->from('stock_types');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2],$wh[3]);
        $results = $CNT->orderby('id','DESC')->limit($pagi->start,$pagi->perpage)->run();


        $data['result'] = $results;
        $data['count'] = $count['Total'];
        $data['pagi'] = $pagi->paginations();
        return $data;
    }

    public function stocktype_edit($id){
        if($id) {
            return $this->db->from('stock_types')->where('id',$id)->first();
        }
    }

    public function stocktype_remove($id){
        $record = self::stocktype_edit($id);
        if($record) {
            $this->db->delete('stock_types')->where('id',$id)->done();
            $this->setMessage('Başarı ile silinmiştir !','success');
        }
    }

    public function changefield($pid) {
        $data = $this->db->from('products')->select('id')->where('id',$pid)->first();
        if($data) {
            $field = $this->input->post('f',true);
            $val = $this->input->post('val',true);
            if($val != 'true') $val = '0';
            else $val = 1;
            $this->db->update('products')->where('id',$data['id'])->set([
                $field => $val
            ]);
            die('ok');
        }
    }

    public function get_auctions() {

        $auctions =  $this->db->from('auctions')->orderby('id','DESC')->run();

        foreach($auctions as $k => $v) {
            $v['start_date'] = date("d.m.Y H:i",$v['start_time']);
            $v['end_date'] = date("d.m.Y H:i",$v['end_time']);

            $auctions[$k] = $v;
        }

        return $auctions;

    }


    public function fakeSeoProduct() {
        $data = $this->db->from('products')->run();

        foreach($data as $val) {

            parent::saveURL([
                'url' => convertstring($val['name']).'.html',
                'controller' => 'products',
                'method' => 'view',
                'record_id' => $val['id'],
                'lang' => 'tr',
            ]);

        }
    }
}