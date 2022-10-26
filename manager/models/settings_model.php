<?php
class Settings_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function main() {

        if($p = $this->input->post('p')) {
            if(!$p['SMTP']) $p['SMTP'] = 0;

            if($_FILES['img']['name'])
            {
                $file = explode('.', $_FILES['img']['name']);
                $ext = array_pop($file);
                if(in_array(strtolower($ext),['jpg','jpeg','png'])) {
                    $NewFileName = rand().'_logo.'.$ext;
                    move_uploaded_file($_FILES['img']['tmp_name'],'../data/uploads/'.$NewFileName);
                    $p['logo'] = $NewFileName;
                }
                else
                {
                    $this->setMessage('Lütfen sadece JPG,JPEG ve PNG uzantılı resim yükleyiniz.!','danger');
                }
            }

            if($_FILES['watermark']['name'])
            {
                $file = explode('.', $_FILES['watermark']['name']);
                $ext = array_pop($file);
                if(in_array(strtolower($ext),['png'])) {
                    $NewFileName = rand().'_logo.'.$ext;
                    move_uploaded_file($_FILES['watermark']['tmp_name'],'../data/uploads/'.$NewFileName);
                    $p['watermark'] = $NewFileName;
                }
                else
                {
                    $this->setMessage('Lütfen sadece PNG uzantılı resim yükleyiniz.!','danger');
                }
            }

            if($_FILES['favicon']['name'])
            {
                $file = explode('.', $_FILES['favicon']['name']);
                $ext = array_pop($file);
                if(in_array(strtolower($ext),['ico','png'])) {
                    $NewFileName = rand().'_favicon.'.$ext;
                    move_uploaded_file($_FILES['favicon']['tmp_name'],'../data/uploads/'.$NewFileName);
                    $p['favicon'] = $NewFileName;
                }
                else
                {
                    $this->setMessage('Lütfen sadece ICO ve PNG uzantılı resim yükleyiniz.!','danger');
                }
            }

            $this->db->update('settings')->where('id',1)->set($p);
            $this->setMessage('Bilgiler başarı ile kayıt edildi.','success');

        }

        $data = $this->db->from('settings')->where('id',1)->first();

        return $data;
    }

    public function admins($page){
        global $Status;

        $perpage = SHOW_LIST_PAGE;

        if($form = $this->input->post('s')) {
            $ids = $this->input->post('id');
            $password = $this->input->post('password');
            if(!$form['status']) $form['status'] = 0;
            if($password) $form['password'] = md5($password);

            if($ids) {
                $this->db->update('admins')->where('id',$ids)->set($form);
            } else {
                $this->db->insert('admins')->set($form);
                $ids = $this->db->lastId();
            }

            $this->setMessage('Bilgiler başarı ile kayıt edildi.','success');
        }

        $WHERE[] = ["id",0,'!='];

        if($f = $this->input->get('f'))
        {
            if($f['status']!='all') $WHERE[] = ["status",($f['status']),'='];
            if($f['name']) $WHERE[] = ["name",$f['name'],'LIKE'];
        }

        if(intval($_GET['perpage'])) {
            $perpage = intval($_GET['perpage']);
        }

        $CNT = $this->db->from('admins')->select('COUNT(id) Total');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2]);
        $CNT->limit(0,1);
        $count = $CNT->first();

        $pagi = new Pagination();
        $pagi->link = ADMINBASEURL.'settings/admins/';
        $pagi->count = $count['Total'];
        $pagi->perpage = $perpage;
        $pagi->pg = $page;
        $pagi->startPage();

        $CNT = $this->db->from('admins');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2]);
        $results = $CNT->orderby('id','DESC')->limit($pagi->start,$pagi->perpage)->run();

        foreach($results as $key => $result)
        {
            $results[$key]['status_name'] = $Status[$result['status']];
        }
        $data['result'] = $results;
        $data['count'] = $count['Total'];
        $data['pagi'] = $pagi->paginations();
        return $data;
    }

    public function admin_edit($id){
        if($id) {
            return $this->db->from('admins')->where('id',$id)->first();
        }
    }

    public function admin_remove($id){
        $record = self::admin_edit($id);
        if($record) {
            $this->db->delete('admins')->where('id',$id)->done();
            $this->setMessage('Başarı ile silinmiştir !','success');
        }
    }

    public function currencys($page){
        global $Status;

        $perpage = SHOW_LIST_PAGE;

        if($form = $this->input->post('s')) {
            $ids = $this->input->post('id');
            if(!$form['status']) $form['status'] = 0;

            if($ids) {
                $this->db->update('currencys')->where('id',$ids)->set($form);
            } else {
                $this->db->insert('currencys')->set($form);
                $ids = $this->db->lastId();
            }

            $this->setMessage('Bilgiler başarı ile kayıt edildi.','success');
        }

        $WHERE[] = ["id",0,'!='];

        if($f = $this->input->get('f'))
        {
            if($f['status']!='all') $WHERE[] = ["status",($f['status']),'='];
            if($f['name']) $WHERE[] = ["name",$f['name'],'LIKE'];
        }

        if(intval($_GET['perpage'])) {
            $perpage = intval($_GET['perpage']);
        }

        $CNT = $this->db->from('currencys')->select('COUNT(id) Total');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2]);
        $CNT->limit(0,1);
        $count = $CNT->first();

        $pagi = new Pagination();
        $pagi->link = ADMINBASEURL.'settings/currencys/';
        $pagi->count = $count['Total'];
        $pagi->perpage = $perpage;
        $pagi->pg = $page;
        $pagi->startPage();

        $CNT = $this->db->from('currencys');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2]);
        $results = $CNT->orderby('id','DESC')->limit($pagi->start,$pagi->perpage)->run();

        foreach($results as $key => $result)
        {
            $results[$key]['status_name'] = $Status[$result['status']];
        }
        $data['result'] = $results;
        $data['count'] = $count['Total'];
        $data['pagi'] = $pagi->paginations();
        return $data;
    }

    public function currency_edit($id){
        if($id) {
            return $this->db->from('currencys')->where('id',$id)->first();
        }
    }

    public function currency_remove($id){
        $record = self::currency_edit($id);
        if($record) {
            $this->db->delete('currencys')->where('id',$id)->done();
            $this->setMessage('Başarı ile silinmiştir !','success');
        }
    }

    public function popup() {

        if($p = $this->input->post('p')) {
            if(!$p['status']) $p['status'] = 0;
            $this->db->update('popups')->where('id',1)->set($p);
            $this->setMessage('Bilgiler başarı ile kayıt edildi.','success');
        }

        $data = $this->db->from('popups')->where('id',1)->first();

        return $data;
    }

    public function coupons($page){
        global $Status;

        $Used = [
            0 => 'Kullanılmadı',
            1 => 'Kullanıldı'
        ];

        $Multi = [
            0 => 'Tek Kullanım',
            1 => 'Çoklu Kullanım'
        ];

        $perpage = SHOW_LIST_PAGE;

        if($form = $this->input->post('s')) {
            $form['start_time'] = time();
            $form['end_time'] = 0;
            if($form['start_date']) $form['start_time'] = strtotime($form['start_date']." 00:00:00");
            if($form['end_date']) $form['end_time'] = strtotime($form['end_date']." 23:59:59");
            if(!$form['code']) $form['code'] = strtoupper(generateRandomString());

            unset($form['start_date'],$form['end_date']);

            $this->db->insert('coupons')->set($form);
            $ids = $this->db->lastId();

            $this->setMessage('Bilgiler başarı ile kayıt edildi.','success');
        }

        $WHERE[] = ["id",0,'!='];

        if($f = $this->input->get('f'))
        {
            if($f['used']!='all') $WHERE[] = ["used",($f['used']),'='];
            if($f['multi']!='all') $WHERE[] = ["multi",($f['multi']),'='];
            if($f['code']) $WHERE[] = ["code",$f['code'],'LIKE'];
        }

        if(intval($_GET['perpage'])) {
            $perpage = intval($_GET['perpage']);
        }

        $CNT = $this->db->from('coupons')->select('COUNT(id) Total');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2]);
        $CNT->limit(0,1);
        $count = $CNT->first();

        $pagi = new Pagination();
        $pagi->link = ADMINBASEURL.'settings/coupons/';
        $pagi->count = $count['Total'];
        $pagi->perpage = $perpage;
        $pagi->pg = $page;
        $pagi->startPage();

        $CNT = $this->db->from('coupons');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2]);
        $results = $CNT->orderby('id','DESC')->limit($pagi->start,$pagi->perpage)->run();

        foreach($results as $key => $result)
        {
            $results[$key]['start_date'] = date("d.m.Y",$result['start_time']);
            if($result['user_id']) $results[$key]['user'] = $this->db->from('users')->where('id',$result['user_id'])->select('id,CONCAT(name , " ", surname) name, username')->first();
            $results[$key]['used_name'] = $Used[$result['used']];
            $results[$key]['multi_name'] = $Multi[$result['multi']];
            if($result['end_time']) $results[$key]['end_date'] = date("d.m.Y",$result['end_time']);
        }
        $data['result'] = $results;
        $data['count'] = $count['Total'];
        $data['pagi'] = $pagi->paginations();
        return $data;
    }

    public function coupon_remove($id){
        $this->db->delete('coupons')->where('id',$id)->done();
        $this->setMessage('Başarı ile silinmiştir !','success');
    }
}