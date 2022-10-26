<?php
class Index_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login()
    {
        $post = $this->input->post('login');
        if($post)
        {
            $user = $this->db->from('admins')->where('username',$post['username'])
            ->where('password',md5($post['password']))->where('status',1)->first();

            if($user)
            {

                Session::set('admin',$user);
                return $user;
            }
            else
            {
                $this->setMessage('Girdiğiniz bilgiler hatalıdır !','danger');
            }
        }
        else
        {
            $this->setMessage('Lütfen bilgileri eksiksiz giriniz','danger');
        }
        return false;
    }

    public function dashboard()
    {
        global $OrderStatus, $PaymentType, $CommentStatus;

        $on = time() - 180;
        $Online = $this->db->from('users')->where('online',$on,'>=')->select('COUNT(id) total')->total();

        $activeAuctions = $this->db->from('products p')->join('auctions a','a.id = p.auction_id','LEFT')->where('a.status',1)->where('a.start_time',$on,'<')->select('a.name, a.id, SUM(p.price) total')->groupby('p.auction_id')->run();

        foreach((array)$activeAuctions as $val) {
            $activeMuz[$val['id'].'-'.$val['name']] = $val['total'];
        }

        foreach((array)$activeMuz as $tt) $aktifmuzTotal += $tt;

        /*
        if(!$chart15days = get_static_cache('chart15',300)) {
            $e_time = strtotime(date("d.m.Y 23:59:59"));
            $s_time = strtotime("-15 days",$e_time);

            $last15 = $this->db->from('orders')->in('status',[0,1,2,3])->where('create_time',$s_time,'>=')->where('create_time',$e_time,'<=')
            ->orderby('create_time','ASC')
            ->run();

            foreach((array)$last15 as $val) {
                $day = date("d-m-Y",$val['create_time']);
                $chart15days[$day] += $val['price'];
            }

            set_static_cache('chart15',$chart15days);
        }
*/
        $sonSatis = $this->db->from('orders o')
        ->join('users u','u.id = o.user_id','LEFT')
        ->select('o.id, o.create_time, o.code, CONCAT(u.name, " ", u.surname) name, o.payment_type, o.price, o.status')
        ->orderby('o.id','DESC')->limit(0,10)->run();
        foreach((array)$sonSatis as $sk => $sval) {
            $sval['payment_type_name'] = $PaymentType[$sval['payment_type']];
            $sval['status_name'] = $OrderStatus[$sval['status']];
            $sval['create_date'] = date("d.m.Y H:i",$sval['create_time']);
            $sonSatis[$sk] = $sval;
        }

        if(!$sonPeyler = get_static_cache('sonPeyler',300)) {
            $sonPeyler = $this->db->from('offers c')
            ->join('users u','u.id = c.user_id','LEFT')
            ->join('products p','p.id = c.product_id','LEFT')
            ->select('CONCAT(u.name, " ", u.surname) name, c.create_time, p.name product_name, c.id, c.price')
            ->orderby('p.id','DESC')->limit(0,7)->run();

            foreach((array)$sonPeyler as $sk => $val) {
                $val['create_date'] = date("d.m.Y",$val['create_time']);
                $sonPeyler[$sk] = $val;
            }

            set_static_cache('sonPeyler',$sonPeyler);
        }

        if(!$aktifWeb = get_static_cache('aktifweb')) {
            $s_time = strtotime(date("01.m.Y 00:00:00",strtotime("-30 days")));
            $e_time = strtotime(date("d.m.Y 23:59:59"));
            $values = $this->db->from('offers o')
            ->join('users u','u.id = o.user_id','LEFT')
            ->where('o.create_time',$s_time,'>=')->where('o.create_time',$e_time,'<=')
            ->select('COUNT(o.id) Total, CONCAT(u.name," ",u.surname) name')
            ->groupby('o.user_id')
            ->orderby('Total','DESC')
            ->limit(0,5)
            ->run();
            foreach((array)$values as $val) {
                $aktifWeb[$val['name']] = $val['Total'];
            }

            set_static_cache('aktifweb',$aktifWeb);
        }

        foreach((array)$aktifWeb as $tt) $aktifTotal += $tt;

        if(!$aktifSale = get_static_cache('aktifsale')) {
            $s_time = strtotime(date("01.m.Y 00:00:00",strtotime("-30 days")));
            $e_time = strtotime(date("d.m.Y 23:59:59"));
            $values = $this->db->from('offers o')
            ->join('products p','p.id = o.product_id','LEFT')
            ->where('o.create_time',$s_time,'>=')->where('o.create_time',$e_time,'<=')
            ->select('COUNT(o.product_id) Total, p.name')
            ->groupby('o.product_id')
            ->orderby('Total','DESC')
            ->limit(0,5)
            ->run();
            foreach((array)$values as $val) {
                $aktifSale[] = ['label'=>$val['name'], 'value'=>$val['Total']];
            }

            set_static_cache('aktifsale',$aktifSale);
        }

        if(!$chart15days) $chart15days = [];
        if(!$aktifSale) $aktifSale = [];
        if(!$aktifWeb) $aktifWeb = [];
        if(!$activeMuz) $activeMuz = [];

        return [
            //'chart15' => ($chart15days) ? ['label'=>'"'.implode('","',array_keys($chart15days)).'"','val' => implode(',',array_values($chart15days))]:'',
            'aktifsale_c' => ($aktifSale) ? json_encode($aktifSale) : '',
            'aktifsale' => $aktifSale,
            'aktifweb_c' => ($aktifWeb) ? ['label'=>'"'.implode('","',array_keys($aktifWeb)).'"','val' => implode(',',array_values($aktifWeb))]: '',
            'aktifweb' => $aktifWeb,
            'aktifmuz_c' => ($activeMuz) ? ['label'=>'"'.implode('","',array_keys($activeMuz)).'"','val' => implode(',',array_values($activeMuz))]: '',
            'aktifmuz' => $activeMuz,
            'aktifTotal' => $aktifTotal,
            'aktifmuzTotal' => $aktifmuzTotal,
            'sonSatis' => $sonSatis,
            'sonOdeme' => $sonPeyler,
            'canli' => $Online,
        ];
    }

    public function changelang($id)
    {
        $lang = $this->db->from('langs')->where('id',$id)->first();
        if($lang) {
            $SistemDil = $lang;
            Session::set('ActiveAdminLang',$SistemDil);
        }
    }

}