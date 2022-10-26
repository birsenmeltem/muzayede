<?php
class Wallets_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function deposits($page)
    {
        $perpage = SHOW_LIST_PAGE;

        if($form = $this->input->post('s')) {
            $ids = $this->input->post('id');
            $form['price'] = str_replace(',','.',$form['price']);
            $form['create_user'] = Session::fetch('admin','id');

            if($ids) {
                $this->db->update('balances')->where('id',$ids)->set($form);
            } else {
                $this->db->insert('balances')->set($form);
                $ids = $this->db->lastId();
            }
            $this->setMessage('Bilgiler başarı ile kayıt edildi.','success');
        }

        $WHERE[] = ["ty",0,'='];

        if($f = $this->input->get('f'))
        {
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
            if($f['detail']) $WHERE[] = ["detail",$f['detail'],'LIKE','&&'];
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

        $CNT = $this->db->from('balances')->select('COUNT(id) Total');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2]);
        $CNT->limit(0,1);
        $count = $CNT->first();

        $pagi = new Pagination();
        $pagi->link = ADMINBASEURL.'wallets/deposits/';
        $pagi->count = $count['Total'];
        $pagi->perpage = $perpage;
        $pagi->pg = $page;
        $pagi->startPage();

        $CNT = $this->db->from('balances');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2]);
        $results = $CNT->orderby('id','DESC')->limit($pagi->start,$pagi->perpage)->run();

        foreach($results as $key => $result)
        {
            $results[$key]['user'] = $this->db->from('users')->where('id',$result['user_id'])->first();
            $results[$key]['create_date'] = date("d.m.Y H:i",$result['create_time']);
        }
        $data['result'] = $results;
        $data['count'] = $count['Total'];
        $data['pagi'] = $pagi->paginations();
        return $data;
    }

    public function withdrawals($page)
    {
        $perpage = SHOW_LIST_PAGE;

        if($form = $this->input->post('s')) {
            $ids = $this->input->post('id');
            $form['price'] = str_replace(',','.',$form['price']);
            $form['create_user'] = Session::fetch('admin','id');

            if($ids) {
                $this->db->update('balances')->where('id',$ids)->set($form);
            } else {
                $this->db->insert('balances')->set($form);
                $ids = $this->db->lastId();
            }
            $this->setMessage('Bilgiler başarı ile kayıt edildi.','success');
        }

        $WHERE[] = ["ty",1,'='];

        if($f = $this->input->get('f'))
        {
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
            if($f['detail']) $WHERE[] = ["detail",$f['detail'],'LIKE','&&'];
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

        $CNT = $this->db->from('balances')->select('COUNT(id) Total');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2]);
        $CNT->limit(0,1);
        $count = $CNT->first();

        $pagi = new Pagination();
        $pagi->link = ADMINBASEURL.'wallets/withdrawals/';
        $pagi->count = $count['Total'];
        $pagi->perpage = $perpage;
        $pagi->pg = $page;
        $pagi->startPage();

        $CNT = $this->db->from('balances');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2]);
        $results = $CNT->orderby('id','DESC')->limit($pagi->start,$pagi->perpage)->run();

        foreach($results as $key => $result)
        {
            $results[$key]['user'] = $this->db->from('users')->where('id',$result['user_id'])->first();
            $results[$key]['create_date'] = date("d.m.Y H:i",$result['create_time']);
        }
        $data['result'] = $results;
        $data['count'] = $count['Total'];
        $data['pagi'] = $pagi->paginations();
        return $data;
    }

    public function payments($page)
    {
        $perpage = SHOW_LIST_PAGE;

        if($form = $this->input->post('s')) {
            $ids = $this->input->post('id');
            $tip = $this->input->post('tip');
            if($tip && $tip == 'other') $form['user_id'] = 0;
            if($form['create_time']) $form['create_time'] = strtotime($form['create_time']);
            $form['price'] = str_replace(',','.',$form['price']);
            $form['create_user'] = Session::fetch('admin','id');

            if($ids) {
                $this->db->update('balances')->where('id',$ids)->set($form);
            } else {
                if(!$form['create_time']) $form['create_time'] = time();
                $this->db->insert('balances')->set($form);
                $ids = $this->db->lastId();
            }
            $this->setMessage('Bilgiler başarı ile kayıt edildi.','success');
        }

        $WHERE[] = ["ty",2,'='];

        if($f = $this->input->get('f'))
        {
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
            if($f['detail']) $WHERE[] = ["detail",$f['detail'],'LIKE','&&'];
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

        $CNT = $this->db->from('balances')->select('COUNT(id) Total');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2]);
        $CNT->limit(0,1);
        $count = $CNT->first();

        $pagi = new Pagination();
        $pagi->link = ADMINBASEURL.'wallets/payments/';
        $pagi->count = $count['Total'];
        $pagi->perpage = $perpage;
        $pagi->pg = $page;
        $pagi->startPage();

        $CNT = $this->db->from('balances');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2]);
        $results = $CNT->orderby('id','DESC')->limit($pagi->start,$pagi->perpage)->run();

        foreach($results as $key => $result)
        {
            if($result['user_id']) $results[$key]['user'] = $this->db->from('users')->where('id',$result['user_id'])->first();
            $results[$key]['create_date'] = date("d.m.Y H:i",$result['create_time']);
        }
        $data['result'] = $results;
        $data['count'] = $count['Total'];
        $data['pagi'] = $pagi->paginations();
        return $data;
    }

    public function get_record($id) {
        $data = $this->db->from('balances')->where('id',$id)->first();
        if($data['user_id']) {
            $data['user'] = $this->db->from('users')->where('id',$data['user_id'])->first();
        }
        $data['create_date'] = date("d.m.Y H:i",$data['create_time']);
        return $data;
    }

    public function userList() {
        return $this->db->from('users')->where('status',1)->run();
    }

    public function accounts($page)
    {
        $perpage = SHOW_LIST_PAGE;

        if($form = $this->input->post('s')) {
            $ids = $this->input->post('id');
            $tip = $this->input->post('tip');
            if($tip && $tip == 'other') $form['user_id'] = 0;
            if($form['create_time']) $form['create_time'] = strtotime($form['create_time']);
            $form['price'] = str_replace(',','.',$form['price']);

            if($ids) {
                $this->db->update('balances')->where('id',$ids)->set($form);
            } else {
                if(!$form['create_time']) $form['create_time'] = time();
                $this->db->insert('balances')->set($form);
                $ids = $this->db->lastId();
            }
            $this->setMessage('Bilgiler başarı ile kayıt edildi.','success');
        }


        $WHERE[] = ["id",0,'!=','&&'];

        if($f = $this->input->get('f'))
        {
            if($f['user_id']) {
                $WHERE[] = ["id",$f['user_id'],'=','&&'];
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

        $CNT = $this->db->from('users')->select('COUNT(id) Total');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2],$wh[3]);
        $CNT->limit(0,1);
        $count = $CNT->first();

        $pagi = new Pagination();
        $pagi->link = ADMINBASEURL.'wallets/accounts/';
        $pagi->count = $count['Total'];
        $pagi->perpage = $perpage;
        $pagi->pg = $page;
        $pagi->startPage();

        $CNT = $this->db->from('users');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2]);
        $results = $CNT->orderby('id','DESC')->limit($pagi->start,$pagi->perpage)->run();

        foreach($results as $key => $result)
        {
            $balance = $this->db->from('balances b')->where('b.user_id',$result['id'])->where('b.status',3,'!=')->select("(SELECT SUM(price) FROM balances WHERE user_id=b.user_id && ty=1) Gelir, (SELECT SUM(price) FROM balances WHERE user_id=b.user_id && ty IN (0,2)) Gider")->first();
            $balance['total'] = $balance['Gelir']-$balance['Gider'];
            $results[$key]['balance'] = $balance;
            $results[$key]['create_date'] = date("d.m.Y H:i",$result['create_time']);
        }
        $data['result'] = $results;
        $data['count'] = $count['Total'];
        $data['pagi'] = $pagi->paginations();
        return $data;
    }

    public function remove($id){
        $record = self::get_record($id);
        if($record) {
            $this->db->delete('balances')->where('id',$id)->done();
            $this->setMessage('Başarı ile silinmiştir !','success');
        }
    }

    public function views($user_id) {

        if($form = $this->input->post('s')) {
            $ids = $this->input->post('id');
            $tip = $this->input->post('tip');
            if($tip && $tip == 'other') $form['user_id'] = 0;
            if($form['create_time']) $form['create_time'] = strtotime($form['create_time']);
            $form['price'] = str_replace(',','.',$form['price']);
            $form['create_user'] = Session::fetch('admin','id');

            if($ids) {
                $this->db->update('balances')->where('id',$ids)->set($form);
            } else {
                if(!$form['create_time']) $form['create_time'] = time();
                $this->db->insert('balances')->set($form);
                $ids = $this->db->lastId();
            }
            $this->setMessage('Bilgiler başarı ile kayıt edildi.','success');
        }


        global $TransactionType, $PaymentPriceType;

        $data = $this->db->from('users')->where('id',$user_id)->first();
        if(!$data) return false;

        $this->db->from('balances');

        $WHERE[] = ["user_id",$user_id,'=','&&'];
        $WHERE['ty'] = ["ty",[0,1,2],'IN','&&'];

        if($f = $this->input->get('f'))
        {
            if($f['ty']) $WHERE['ty'] = ["ty",$f['ty'],'=','&&'];
            if($f['status']) $WHERE['status'] = ["status",$f['status'],'=','&&'];

            if($f['date']) {
                list($start,$end) = explode('/',$f['date']);
                if($start && $end) {
                    $WHERE[] = ["create_time",strtotime(date(trim($start)." 00:00:00")),'>=','&&'];
                    $WHERE[] = ["create_time",strtotime(date(trim($end)." 23:59:59")),'<=','&&'];
                }
            }
        }

        foreach($WHERE as $wh) $this->db->where($wh[0],$wh[1],$wh[2],$wh[3]);


        $transactions = $this->db->orderby('id','ASC')->run();
        $balance = [];
        foreach($transactions as $key => $t) {
            if($t['product_id']) {
                $t['pro'] = $this->db->from('products p')->join('auctions a','a.id = p.auction_id','LEFT')->select('p.id,p.name,p.sku,a.name auction_name,p.auction_id')->where('p.id',$t['product_id'])->first();
                $t['pro']['name'] = mb_substr($t['pro']['name'],0,60,"UTF-8").'...';
            } else $t['pro'] = ['sku'=>'-','name'=>'-'];
            if($t['create_user']) {
                $t['create'] = $this->db->from('admins')->select('id,name')->where('id',$t['create_user'])->first();
            } else $t['create']['name'] = '-';
            $t['create_date'] = date("d.m.Y H:i",$t['create_time']);
            $t['type'] = $TransactionType[$t['ty']];
            if(isset($f)) {
                $t['balance'] = false;
            } else {
                if($t['status']!=3) {
                    switch ($t['ty']) {
                        case '1':
                            $balance[] = $t['price'];

                            break;
                        case '0':
                        case '2':
                            $balance[] = '-' . $t['price'];
                            $t['price'] = '-' . $t['price'];
                            break;
                    }
                }
                $t['balance'] = array_sum($balance);

            }
            $transactions[$key] = $t;
        }

        $data['balance'] = $t['balance'];
        $data['tactions'] = $transactions;

        return $data;
    }
}