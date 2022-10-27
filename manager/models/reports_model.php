<?php
class Reports_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_auctions($finish=false) {

        $this->db->from('auctions');
        if($finish) $this->db->where('status',3);

        $auctions =  $this->db->orderby('id','DESC')->run();

        foreach($auctions as $k => $v) {
            $v['start_date'] = date("d.m.Y H:i",$v['start_time']);
            $v['end_date'] = date("d.m.Y H:i",$v['end_time']);

            $auctions[$k] = $v;
        }

        return $auctions;

    }

    public function peys() {
        if($f = $this->input->post('f')) {

            if($f['date']) {
                list($start,$end) = explode('/',$f['date']);
                if($start && $end) {
                    $WHERE[] = ["o.create_time",strtotime(date(trim($start)." 00:00:00")),'>=','&&'];
                    $WHERE[] = ["o.create_time",strtotime(date(trim($end)." 23:59:59")),'<=','&&'];
                }
            }

            if($f['auction_id'] != 'all') {
                $ids[] = 0;
                $pros = $this->db->from('products')->where('auction_id',$f['auction_id'])->select('id')->run();
                foreach($pros as $pro) $ids[] = $pro['id'];
                $WHERE[] = ["o.product_id",$ids,'IN','&&'];
            }

            $this->db->from('offers o')->join('users u','u.id = o.user_id','LEFT')->select('o.user_id, o.create_time, u.name, u.surname, u.phone, u.username')->groupby('o.user_id');
            foreach($WHERE as $w) {
                $this->db->where($w[0],$w[1],$w[2],$w[3]);
            }

            $data = $this->db->orderby('o.create_time','ASC')->run();
            foreach($data as $k => $v) $data[$k]['create_date'] = date("d.m.Y H:i",$v['create_time']);

            return ['status' => true , 'data' => $data , 'querystring' => http_build_query(['f'=>$f])];
        }
    }

    public function mostpeys() {
        if($f = $this->input->post('f')) {

            if($f['date']) {
                list($start,$end) = explode('/',$f['date']);
                if($start && $end) {
                    $WHERE[] = ["o.create_time",strtotime(date(trim($start)." 00:00:00")),'>=','&&'];
                    $WHERE[] = ["o.create_time",strtotime(date(trim($end)." 23:59:59")),'<=','&&'];
                }
            }

            if($f['auction_id'] != 'all') {
                $ids[] = 0;
                $pros = $this->db->from('products')->where('auction_id',$f['auction_id'])->select('id')->run();
                foreach($pros as $pro) $ids[] = $pro['id'];
                $WHERE[] = ["o.product_id",$ids,'IN','&&'];
            }

            $this->db->from('offers o')->join('users u','u.id = o.user_id','LEFT')->select('COUNT(o.user_id) total, o.user_id, u.name, u.surname, u.phone, u.username')->where('u.username',' ','!=')->groupby('o.user_id');
            foreach($WHERE as $w) {
                $this->db->where($w[0],$w[1],$w[2],$w[3]);
            }

            $data = $this->db->orderby('total','DESC')->run();
            return ['status' => true , 'data' => $data , 'querystring' => http_build_query(['f'=>$f])];
        }
    }

    public function buyer() {
        if($f = $this->input->post('f')) {

            if($f['date']) {
                list($start,$end) = explode('/',$f['date']);
                if($start && $end) {
                    $WHERE[] = ["o.create_time",strtotime(date(trim($start)." 00:00:00")),'>=','&&'];
                    $WHERE[] = ["o.create_time",strtotime(date(trim($end)." 23:59:59")),'<=','&&'];
                }
            }

            if($f['auction_id'] != 'all') {
                $ids[] = 0;
                $pros = $this->db->from('products')->where('auction_id',$f['auction_id'])->select('id')->run();
                foreach($pros as $pro) $ids[] = $pro['id'];
                $WHERE[] = ["op.product_id",$ids,'IN','&&'];
            }

            $this->db->from('orders_products op')->join('orders o','op.order_id = o.id','LEFT')->join('users u','u.id = o.user_id','LEFT')->select('COUNT(op.product_id) total, o.user_id, u.name, u.surname, u.phone, u.username')->where('u.username',' ','!=')->groupby('o.user_id');
            foreach($WHERE as $w) {
                $this->db->where($w[0],$w[1],$w[2],$w[3]);
            }

            $data = $this->db->orderby('total','DESC')->run();
            return ['status' => true , 'data' => $data , 'querystring' => http_build_query(['f'=>$f])];
        }
    }

    public function mostbuyer() {
        if($f = $this->input->post('f')) {

            if($f['date']) {
                list($start,$end) = explode('/',$f['date']);
                if($start && $end) {
                    $WHERE[] = ["o.create_time",strtotime(date(trim($start)." 00:00:00")),'>=','&&'];
                    $WHERE[] = ["o.create_time",strtotime(date(trim($end)." 23:59:59")),'<=','&&'];
                }
            }

            if($f['auction_id'] != 'all') {
                $ids[] = 0;
                $pros = $this->db->from('products')->where('auction_id',$f['auction_id'])->select('id')->run();
                foreach($pros as $pro) $ids[] = $pro['id'];
                $WHERE[] = ["op.product_id",$ids,'IN','&&'];
            }

            $this->db->from('orders_products op')->join('orders o','op.order_id = o.id','LEFT')->join('users u','u.id = o.user_id','LEFT')->select('SUM(op.price) total, o.user_id, u.name, u.surname, u.phone, u.username')->where('u.username',' ','!=')->groupby('o.user_id');
            foreach($WHERE as $w) {
                $this->db->where($w[0],$w[1],$w[2],$w[3]);
            }

            $data = $this->db->orderby('total','DESC')->run();
            return ['status' => true , 'data' => $data , 'querystring' => http_build_query(['f'=>$f])];
        }
    }

    public function seller() {
        if($f = $this->input->post('f')) {

            if($f['date']) {
                list($start,$end) = explode('/',$f['date']);
                if($start && $end) {
                    $WHERE[] = ["o.create_time",strtotime(date(trim($start)." 00:00:00")),'>=','&&'];
                    $WHERE[] = ["o.create_time",strtotime(date(trim($end)." 23:59:59")),'<=','&&'];
                }
            }

            if($f['auction_id'] != 'all') {
                $ids[] = 0;
                $pros = $this->db->from('products')->where('auction_id',$f['auction_id'])->select('id')->run();
                foreach($pros as $pro) $ids[] = $pro['id'];
                $WHERE[] = ["op.product_id",$ids,'IN','&&'];
            }

            $this->db->from('orders_products op')->join('products p','op.product_id = p.id','LEFT')->join('orders o','op.order_id = o.id','LEFT')->join('users u','u.id = p.seller','LEFT')->select('COUNT(op.product_id) total, o.user_id, u.name, u.surname, u.phone, u.username')->where('u.username',' ','!=')->groupby('p.seller');
            foreach($WHERE as $w) {
                $this->db->where($w[0],$w[1],$w[2],$w[3]);
            }
            $this->db->where('p.seller',0,'>');

            $data = $this->db->orderby('total','DESC')->run();
            return ['status' => true , 'data' => $data , 'querystring' => http_build_query(['f'=>$f])];
        }
    }

    public function aresults() {
        if($f = $this->input->post('f')) {
            if($f['auction_id']) {

                $data = $this->db->from('auctions')->where('id',$f['auction_id'])->first();
                if(!$data['id']) return false;

                $ids[] = 0;
                $pros = $this->db->from('products')->where('auction_id',$f['auction_id'])->select('id')->run();
                foreach($pros as $pro) $ids[] = $pro['id'];

                $farkUye = $this->db->from('offers')->select("COUNT(DISTINCT(user_id)) total")->in('product_id',$ids)->first();
                $data['farkUye'] = $farkUye['total'];

                $uyeBuy = $this->db->from('products')->in('id',$ids)->where('sale',0,'>')->select('COUNT(DISTINCT(sale)) total')->first();
                $data['uyeBuy'] = $uyeBuy['total'];

                $uyeSell = $this->db->from('products')->in('id',$ids)->where('seller',0,'>')->select('COUNT(DISTINCT(seller)) total')->first();
                $data['uyeSell'] = $uyeSell['total'];

                $ciro = $this->db->from('products')->in('id',$ids)->select('SUM(price) total')->where('status',2)->first();
                $data['ciro'] = $ciro['total'];

                $peys = $this->db->from('offers o')->join('users u','u.id = o.user_id','LEFT')->select('o.user_id, o.create_time, u.name, u.surname, u.phone, u.username')->in('o.product_id',$ids)->groupby('o.user_id')->run();
                foreach($peys as $k => $v) $peys[$k]['create_date'] = date("d.m.Y H:i",$v['create_time']);

                $data['peys'] = $peys;

                $follows = $this->db->from('users_favorites uf')->join('users u','u.id = uf.user_id','LEFT')->select('COUNT(uf.user_id) total, u.name, u.surname, u.phone, u.username')->in('uf.product_id',$ids)->groupby('uf.user_id')->orderby('total','DESC')->run();
                $data['follows'] = $follows;

                $seller =  $this->db->from('products p')->join('users u','u.id = p.seller','LEFT')
                ->select('COUNT(p.seller) total, (SUM(p.price) -
                (SUM(p.price) * 0.'.intval($data['sell_comm']).')) total_price, u.id, p.seller, u.name, u.surname, u.phone, u.username')
                ->in('p.id',$ids)->where('p.sale',0,'>')->where('u.id',0,'>')->groupby('p.seller')->orderby('p.seller','ASC')->run();

                $data['seller'] = $seller;

                $buyer =  $this->db->from('products p')->join('users u','u.id = p.sale','LEFT')
                ->select('COUNT(p.sale) total, (SUM(p.price) +
                (SUM(p.price) * 0.'.intval($data['buy_comm']).')) 
                total_price,
                 u.id, p.sale, u.name, u.surname, u.phone, u.username')
                ->in('p.id',$ids)->where('p.sale',0,'>')->groupby('p.sale')->orderby('p.sale','ASC')->run();

                $data['buyer'] = $buyer;

                return ['status' => true , 'data' => $data , 'querystring' => http_build_query(['f'=>$f])];
            }
        }
    }

    public function aresult_buyer() {

        if($f = $this->input->post('f')) {

            $vals = $this->db->from('auctions')->where('id',$f['auction_id'])->first();
            $vals['start_date'] = date("d.m.Y H:i",$vals['start_time']);

            $data = $this->db->from('products')->where('auction_id',$f['auction_id'])->where('sale',0,'>')->run();
            foreach($data as $key => $val) {
                $data[$key]['user'] = $this->db->from('users')->where('id',$val['sale'])->first();
            }

            return ['values' => $data, 'data' => $vals];
        }
    }

    public function aresult_seller() {

        if($f = $this->input->post('f')) {

            $vals = $this->db->from('auctions')->where('id',$f['auction_id'])->first();
            $vals['start_date'] = date("d.m.Y H:i",$vals['start_time']);

            $data = $this->db->from('products')->where('auction_id',$f['auction_id'])->where('sale',0,'>')->run();
            foreach($data as $key => $val) {
                $data[$key]['user'] = $this->db->from('users')->where('id',$val['seller'])->first();
            }

            return ['values' => $data, 'data' => $vals];
        }

    }

    public function pro_buyer_pdf($id) {
        $values = $this->db->from('reports_pdf')->where('auction_id',$id)->where('seller',0)->select('filename')->run();
        if($values) {
            foreach($values as $key => $val) {
                $values[$key]['url'] = BASEURL . 'data/pdf/'.$val['filename'];
            }
            die(json_encode($values));
        }
    }

    public function pro_seller_pdf($id) {
        $values = $this->db->from('reports_pdf')->where('auction_id',$id)->where('seller',1)->select('id')->run();
        if($values) {
            foreach($values as $key => $val) {
                $values[$key]['url'] = ADMINBASEURL . 'reports/showseller/'.$val['id'];
            }
            die(json_encode($values));
        }
    }

    public function showseller($id) {
        $val = $this->db->from('reports_pdf')->where('id',$id)->select('html')->first();
        die(print($val['html']));
    }

    public function auctions() {
        if($f = $this->input->post('f')) {

        }
    }

    public function buyerseller() {
        if($f = $this->input->post('f')) {

            $WHERE[] = ['p.status',2,'=','&&'];

            if($f['buyer'] != 'all') {
                $WHERE[] = ["p.sale",$f['buyer'],'=','&&'];
            }
            if($f['seller'] != 'all') {
                $WHERE[] = ["p.seller",$f['seller'],'=','&&'];
            }
            if($f['auction_id'] != 'all') {
                $WHERE[] = ["p.auction_id",$f['auction_id'],'=','&&'];
            }

            $this->db->from('products p')
                ->join('auctions a','a.id = p.auction_id','LEFT')
                ->join('users ub','ub.id = p.sale','LEFT')
                ->join('users us','us.id = p.seller','LEFT')
                ->select('p.*, a.name auction_name, a.start_time, a.buy_comm, ub.name buyer_name, ub.surname buyer_surname, us.name seller_name, us.surname seller_surname');
            foreach($WHERE as $w) {
                $this->db->where($w[0],$w[1],$w[2],$w[3]);
            }

            $data = $this->db->orderby('p.sku','ASC')->run();
            foreach($data as $key => $val) {
                $data[$key]['auction_time'] = date("d.m.Y H:i",$val['start_time']);
                $komm = (($val['price'] * $val['buy_comm']) / 100);
                $data[$key]['priceKdv'] = numbers((($val['price'] * $val['kdv']) / 100));
                $data[$key]['komm'] = numbers($komm);
                $data[$key]['kommKdv'] = numbers(($data[$key]['komm'] * 18) / 100);
                $data[$key]['total_price'] = numbers($val['price'] + $data[$key]['priceKdv'] + $komm + $data[$key]['kommKdv']);
            }
            return ['status' => true , 'data' => $data , 'querystring' => http_build_query(['f'=>$f])];
        }
    }

    public function buyerseller_excel($f) {
        $_POST['f'] = $f;
        $data = $this->buyerseller();

        $html[] = 'Alıcı ID,Alıcı Adı,Müzayede Adı,Müzayede Tarihi,Lot No,Satıcı ID,Satıcı Adı,Pey,Pey KDV,Komisyon,Komisyon KDV,Tutar';
        foreach($data['data'] as $val) {
            $htmls[] = $val['sale'];
            $htmls[] = $val['buyer_name'].' '.$val['buyer_surname'];
            $htmls[] = $val['auction_name'];
            $htmls[] = $val['auction_time'];
            $htmls[] = $val['sku'];
            $htmls[] = $val['seller'];
            $htmls[] = $val['seller_name'].' '.$val['seller_surname'];
            $htmls[] = $val['price'];
            $htmls[] = $val['priceKdv'];
            $htmls[] = $val['komm'];
            $htmls[] = $val['kommKdv'];
            $htmls[] = $val['total_price'];
            $html[] = implode(',',$htmls);
            unset($htmls);
        }

        $file = 'data/excel/alici-satici-'.rand().'.csv';
        file_put_contents(BASE. $file,implode("\r\n",$html));
        @header("Location: ".BASEURL . $file);
        die;
    }

}