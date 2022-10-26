<?php
class Cargocompanys_Model extends Model
{
    public function __construct(){
        parent::__construct();
    }

    public function lists($page){
        global $Status;

        $perpage = SHOW_LIST_PAGE;

        if($form = $this->input->post('s')) {
            $ids = $this->input->post('id');
            if(!$form['status']) $form['status'] = 0;

            if($_FILES['img']['name'])
            {
                $file = explode('.', $_FILES['img']['name']);
                $ext = array_pop($file);
                if(in_array(strtolower($ext),['jpg','jpeg','png'])) {
                    $NewFileName = rand().'_'.convertstring($form['name'],'_').'.'.$ext;
                    move_uploaded_file($_FILES['img']['tmp_name'],'../data/uploads/'.$NewFileName);
                    $form['picture'] = $NewFileName;
                }
                else
                {
                    $this->setMessage('Lütfen sadece JPG,JPEG ve PNG uzantılı resim yükleyiniz.!','danger');
                }
            }

            if($ids) {
                $this->db->update('cargo_companys')->where('id',$ids)->set($form);
            } else {
                $this->db->insert('cargo_companys')->set($form);
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

        $CNT = $this->db->from('cargo_companys')->select('COUNT(id) Total');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2]);
        $CNT->limit(0,1);
        $count = $CNT->first();

        $pagi = new Pagination();
        $pagi->link = ADMINBASEURL.'cargocompanys/main/';
        $pagi->count = $count['Total'];
        $pagi->perpage = $perpage;
        $pagi->pg = $page;
        $pagi->startPage();

        $CNT = $this->db->from('cargo_companys');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2]);
        $results = $CNT->orderby('id','DESC')->limit($pagi->start,$pagi->perpage)->run();

        foreach($results as $key => $result)
        {
            $results[$key]['integration']['name'] = '-';
            if($result['integration_id']) {
                $results[$key]['integration'] = $this->db->from('cargo_integrations')->where('id',$result['integration_id'])->select('id,name')->first();
            }
            if($result['free_cargo_price']==0.00) $results[$key]['free_cargo_price'] = '-';
            $results[$key]['status_name'] = $Status[$result['status']];
        }
        $data['result'] = $results;
        $data['count'] = $count['Total'];
        $data['pagi'] = $pagi->paginations();
        return $data;
    }

    public function get_record($id){
        if($id) {
            return $this->db->from('cargo_companys')->where('id',$id)->first();
        }
    }

    public function remove($id){
        $record = self::get_record($id);
        if($record) {
            $this->db->delete('cargo_companys')->where('id',$id)->done();
            $this->setMessage('Başarı ile silinmiştir !','success');
        }
    }

    public function integrations(){
        return $this->db->from('cargo_integrations')->orderby('name','ASC')->run();
    }

    public function zones() {
        if($form = $this->input->post('s')) {
            $ids = $this->input->post('id');

            if($ids) {
                $this->db->update('geo_zones')->where('id',$ids)->set($form);
            } else {
                $this->db->insert('geo_zones')->set($form);
                $ids = $this->db->lastId();
            }


            $this->setMessage('Bilgiler başarı ile kayıt edildi.','success');
        }
        return $this->db->from('geo_zones')->orderby('id','ASC')->run();
    }

    public function zone_edit($id){
        if($id) {
            return $this->db->from('geo_zones')->where('id',$id)->first();
        }
    }

    public function zone_remove($id){
        $record = self::zone_edit($id);
        if($record) {
            $this->db->update('citys')->where('zone_id',$id)->set(['zone_id'=>0]);
            $this->db->delete('geo_zone_desi')->where('zone_id',$id)->done();
            $this->db->delete('geo_zones')->where('id',$id)->done();
            $this->setMessage('Başarı ile silinmiştir !','success');
        }
    }

    public function zone_calc($zone_id){
        $zone = self::zone_edit($zone_id);
        if(!$zone) return false;

        if($p = $this->input->post('p')) {

            $ids = $this->input->post('id');

            if($ids) {
                $this->db->update('geo_zone_desi')->where('id',$ids)->set($p);
            } else {
                $this->db->insert('geo_zone_desi')->set($p);
            }

            $this->setMessage('Bilgi başarı ile eklenmiştir','success');
        }

        $desi = $this->db->from('geo_zone_desi gd')->join('cargo_companys cc','gd.cargo_id = cc.id','LEFT')
        ->select('gd.*, cc.name cargo_name')
        ->where('gd.zone_id',$zone['id'])->orderby('gd.cargo_id ASC, gd.s_desi','ASC')->run();

        return compact('zone','desi');

    }

    public function cargolist(){
        return $this->db->from('cargo_companys')->orderby('id','ASC')->run();
    }

    public function zone_calc_edit($id){
        if($id) {
            return $this->db->from('geo_zone_desi')->where('id',$id)->first();
        }
    }

    public function zone_calc_remove($id){
        $record = self::zone_calc_edit($id);
        if($record) {
            $this->db->delete('geo_zone_desi')->where('id',$id)->done();
            $this->setMessage('Başarı ile silinmiştir !','success');
        }
    }

    public function zone_citys($zone_id){
        $zone = self::zone_edit($zone_id);
        if(!$zone) return false;

        if($citys = $this->input->post('citys')) {
            $zone_id = $this->input->post('zone_id');
            $this->db->update('citys')->in('id',$citys)->set(['zone_id'=>$zone_id]);
            $this->setMessage('Eşleşme başarı ile sağlanmıştır.','success');
        }

        $citys = $this->db->from('citys ci')->join('countrys cu','ci.country_id = cu.id','LEFT')->select("ci.id, GROUP_CONCAT(ci.name SEPARATOR ', ') names, cu.name country_name")->where('ci.zone_id',$zone['id'])->orderby('ci.country_id ASC, ci.id','ASC')->run();
        return compact('zone','citys');
    }

    public function zone_city_remove($id){
        $val = $this->db->from('citys')->where('id',$id)->select('country_id,zone_id')->first();
        if($val) {
            $this->db->update('citys')->where('country_id',$val['country_id'])->where('zone_id',$val['zone_id'])
            ->set(['zone_id'=>0]);
            $this->setMessage('Eşleşme başarı ile kaldırıldı !','success');
        }
    }
}