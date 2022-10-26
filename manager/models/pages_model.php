<?php
class Pages_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function lists($yer,$page)
    {
        global $ProductStatus;

        $perpage = SHOW_LIST_PAGE;

        $WHERE[] = ["yer",$yer,'='];

        if($f = $this->input->get('f'))
        {
            if($f['status']!='all') $WHERE[] = ["status",($f['status']),'='];
            if($f['name']) $WHERE[] = ["name",$f['name'],'LIKE'];
        }

        if(intval($_GET['perpage'])) {
            $perpage = intval($_GET['perpage']);
        }

        $CNT = $this->db->from('pages')->select('COUNT(id) Total');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2]);
        $CNT->limit(0,1);
        $count = $CNT->first();

        $pagi = new Pagination();
        $pagi->link = ADMINBASEURL.'pages/main/'.$yer.'/';
        $pagi->count = $count['Total'];
        $pagi->perpage = $perpage;
        $pagi->pg = $page;
        $pagi->startPage();

        $CNT = $this->db->from('pages');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2]);
        $results = $CNT->orderby('rows','ASC')->limit($pagi->start,$pagi->perpage)->run();

        foreach($results as $key => $result)
        {
            $results[$key]['create_date'] = date("d.m.Y H:i",$result['create_time']);
            $results[$key]['update_date'] = date("d.m.Y H:i",$result['update_time']);
            $results[$key]['status_name'] = $ProductStatus[$result['status']];
        }
        $data['result'] = $results;
        $data['count'] = $count['Total'];
        $data['pagi'] = $pagi->paginations();
        return $data;
    }

    public function add() {
        if($p = $this->input->post('p')) {
            if(!$p['rows']) $p['rows'] = 99;
            if(!$p['status']) $p['status'] = 0;

            $p['create_time'] = $p['update_time'] = time();

            $this->db->insert('pages')->set($p);
            $this->setMessage('Sayfa başarı ile eklenmiştir !','success');

            $id = $this->db->lastId();
            parent::saveURL([
                'url' => convertstring($p['name']).'.html',
                'controller' => 'pages',
                'method' => 'view',
                'record_id' => $id,
                'lang' => Session::fetch('ActiveAdminLang','flag'),
            ]);

            return $id;
        }
    }
    public function edit($id) {

        if($p = $this->input->post('p')) {
            $p['update_time'] = time();
            $this->db->update('pages')->where('id',$id)->set($p);

            parent::saveURL([
                'url' => convertstring($p['name']).'.html',
                'controller' => 'pages',
                'method' => 'view',
                'record_id' => $id,
                'lang' => Session::fetch('ActiveAdminLang','flag'),
            ]);

            $this->setMessage('Sayfa başarı ile güncellenmiştir !','success');
        }
        $data = $this->db->from('pages')->where('id',$id)->first();
        if(!$data) return false;

        $data['url'] = BASEURL . parent::getURL('pages',$id);
        return $data;
    }

    public function remove($id){
        $record = self::edit($id);
        if($record) {
            $this->db->delete('seo_links')->where('record_id',$id)->where('controller','pages')->done();
            $this->db->delete('pages')->where('id',$id)->done();
            $this->setMessage('Sayfa başarı ile silinmiştir !','success');
        }
    }

    public function banners($page)
    {
        global $ProductStatus;

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
                $this->db->update('banners')->where('id',$ids)->set($form);
            } else {
                if(!$form['rows']) $form['rows'] = 999;
                $this->db->insert('banners')->set($form);
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

        $CNT = $this->db->from('banners')->select('COUNT(id) Total');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2]);
        $CNT->limit(0,1);
        $count = $CNT->first();

        $pagi = new Pagination();
        $pagi->link = ADMINBASEURL.'pages/banners/';
        $pagi->count = $count['Total'];
        $pagi->perpage = $perpage;
        $pagi->pg = $page;
        $pagi->startPage();

        $CNT = $this->db->from('banners');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2]);
        $results = $CNT->orderby('rows','ASC')->limit($pagi->start,$pagi->perpage)->run();

        foreach($results as $key => $result)
        {
            $results[$key]['status_name'] = $ProductStatus[$result['status']];
        }
        $data['result'] = $results;
        $data['count'] = $count['Total'];
        $data['pagi'] = $pagi->paginations();
        return $data;
    }

    public function banner_edit($id)
    {
        if($id) {
            return $this->db->from('banners')->where('id',$id)->first();
        }
    }

    public function banner_remove($id){
        $record = self::banner_edit($id);
        if($record) {
            $this->db->delete('banners')->where('id',$id)->done();
            $this->setMessage('Başarı ile silinmiştir !','success');
        }
    }
}