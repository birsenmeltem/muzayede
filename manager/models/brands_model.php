<?php
class Brands_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function lists($page)
    {
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
                $this->db->update('brands')->where('id',$ids)->set($form);
            } else {
                if(!$form['rows']) $form['rows'] = 999;
                $this->db->insert('brands')->set($form);
                $ids = $this->db->lastId();
            }

            /*
            parent::saveURL([
                'url' => convertstring($form['name']).'.html',
                'controller' => 'brands',
                'method' => 'view',
                'record_id' => $ids,
                'lang' => Session::fetch('ActiveAdminLang','flag'),
            ]);
            */
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

        $CNT = $this->db->from('brands')->select('COUNT(id) Total');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2]);
        $CNT->limit(0,1);
        $count = $CNT->first();

        $pagi = new Pagination();
        $pagi->link = ADMINBASEURL.'brands/main/';
        $pagi->count = $count['Total'];
        $pagi->perpage = $perpage;
        $pagi->pg = $page;
        $pagi->startPage();

        $CNT = $this->db->from('brands');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2]);
        $results = $CNT->orderby('rows','ASC')->limit($pagi->start,$pagi->perpage)->run();

        foreach($results as $key => $result)
        {
            $results[$key]['cat'] = $this->db->from('categories')->select('id,name')->where('id',$result['category_id'])->first();
            $results[$key]['create_date'] = date("d.m.Y H:i",$result['create_time']);
            $results[$key]['status_name'] = $Status[$result['status']];
        }
        $data['result'] = $results;
        $data['count'] = $count['Total'];
        $data['pagi'] = $pagi->paginations();
        return $data;
    }

    public function get_record($id)
    {
        if($id) {
            return $this->db->from('brands')->where('id',$id)->first();
        }
    }

    public function remove($id){
        $record = self::get_record($id);
        if($record) {
            $this->db->delete('seo_links')->where('record_id',$id)->where('controller','brands')->done();
            $this->db->delete('brands')->where('id',$id)->done();
            $this->setMessage('Başarı ile silinmiştir !','success');
        }
    }
}