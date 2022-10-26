<?php
class Customers_Model extends Model
{
    public function __construct(){
        parent::__construct();
    }

    public function lists($page){
        global $Status;

        $perpage = SHOW_LIST_PAGE;

        if($form = $this->input->post('u')) {
            $ids = $this->input->post('id');
            $password = $this->input->post('password');
            if($password) $form['password'] = md5($password);

            if(!$form['status']) $form['status'] = 0;
            if(!$form['city_id']) $form['city_id'] = 0;
            if(!$form['country_id']) $form['country_id'] = 0;

            if($ids) {
                $this->db->update('users')->where('id',$ids)->set($form);
            } else {
                $form['create_time'] = time();
                $form['create_ip'] = USER_IP;
                $this->db->insert('users')->set($form);
                $ids = $this->db->lastId();
            }

            $this->setMessage('Bilgiler başarı ile kayıt edildi.','success');
        }

        $WHERE[] = ["users.id",0,'!=','&&'];

        if($f = $this->input->get('f'))
        {
            if($f['status']!='all') $WHERE[] = ["users.status",($f['status']),'=','&&'];
            if($f['username'])  $WHERE[] = ["users.username",str_replace(' ','%',$f['username']),'LIKE', '&&'];
            if($f['name'])  $WHERE[] = ["users.name",str_replace(' ','%',$f['name']),'LIKE', '&&'];
            if($f['surname'])  $WHERE[] = ["users.surname",str_replace(' ','%',$f['surname']),'LIKE', '&&'];
            if($f['phone'])  $WHERE[] = ["users.phone",str_replace(' ','%',$f['phone']),'LIKE', '&&'];
            if($f['id'])  $WHERE[] = ["users.id",str_replace(' ','%',$f['id']),'=', '&&'];

            //$page = 1;
        }

        if(intval($_GET['perpage'])) {
            $perpage = intval($_GET['perpage']);
        }

        $CNT = $this->db->from('users')->select('COUNT(users.id) Total');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2],$wh[3]);
        $CNT->limit(0,1);
        $count = $CNT->first();
        //dump($CNT->getSqlString());

        $pagi = new Pagination();
        $pagi->link = ADMINBASEURL.'customers/main/';
        $pagi->count = $count['Total'];
        $pagi->perpage = $perpage;
        $pagi->pg = $page;
        $pagi->startPage();

        $CNT = $this->db->from('users');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2],$wh[3]);
        $CNT->join('countrys','users.country_id = countrys.id','LEFT');
        $CNT->join('citys','users.city_id = citys.id','LEFT');
        $CNT->select('users.*, citys.name city_name, countrys.name country_name');
        $results = $CNT->orderby('users.id','DESC')->limit($pagi->start,$pagi->perpage)->run();
        //dump($CNT->getSqlString());
        foreach($results as $key => $result)
        {
            $balance = $this->db->from('balances b')->where('b.user_id',$result['id'])->where('b.status',3,'!=')->select("(SELECT SUM(price) FROM balances WHERE user_id=b.user_id && ty=1) Gelir, (SELECT SUM(price) FROM balances WHERE user_id=b.user_id && ty IN (0,2)) Gider")->first();
            $balance['total'] = $balance['Gelir']-$balance['Gider'];
            $results[$key]['balance'] = $balance;
            $results[$key]['group'] = $this->db->from('users_groups')->select('id,name')->where('id',$result['group_id'])->first();
            $results[$key]['create_date'] = date("d.m.Y H:i",$result['create_time']);
            $results[$key]['status_name'] = $Status[$result['status']];
        }
        $data['result'] = $results;
        $data['count'] = $count['Total'];
        $data['pagi'] = $pagi->paginations();
        return $data;
    }

    public function get_record($id){
        if($id) {
            return $this->db->from('users')->where('id',$id)->first();
        }
    }

    public function remove($id){
        $record = self::get_record($id);
        if($record) {
            $this->db->delete('users')->where('id',$id)->done();
            $this->db->delete('users_favorites')->where('user_id',$id)->done();
            $this->db->delete('offers')->where('user_id',$id)->done();
            $this->db->delete('users_address')->where('user_id',$id)->done();
            $this->db->delete('orders')->where('user_id',$id)->done();
            $this->setMessage('Başarı ile silinmiştir !','success');
        }
    }

    public function grouplist(){
        return $this->db->from('users_groups')->orderby('id','ASC')->run();
    }

    public function download(){
        if($p = $this->input->post('p')) {
            $this->db->from('users');
            if($p['date']) {
                list($start,$end) = explode(' / ',$p['date']);
                if($start) {
                    $this->db->where('create_time',strtotime($start. " 00:00:00"),'>=');
                }

                if($end) {
                    $this->db->where('create_time',strtotime($end. " 23:59:59"),'<=');
                }
            }

            if($p['group_id']) $this->db->in('group_id',$p['group_id']);
            if($p['city_id']) $this->db->in('city_id',$p['city_id']);
            if($p['country_id']) $this->db->where('country_id',$p['country_id']);

            $this->db->select('CONCAT(name," ",surname) name, phone,username,id');
            $values = $this->db->run();

            if($values) {
                 $data[] = 'ID;Kullanıcı Adı;Adı Soyadı;Telefon';
                foreach($values as $val) {
                    $data[] = $val['id'].';'.$val['username'].';'.$val['name'].';'.$val['phone'];
                }
                $dosya = BASE . 'data/customers_export.txt';
                file_put_contents($dosya,implode("\r\n",$data));

                $json['status'] = 'success';

            }
            else
            {
                $json = [
                    'status' => 'failed',
                    'message' => 'Aradığınız kriterlerde üye bulunamadı !',
                ];
            }
            die(json_encode($json));
        }
    }
}