<?php
class Categories_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function lists($parent,$page)
    {
        global $Status;

        $perpage = SHOW_LIST_PAGE;

        if($form = $this->input->post('s')) {
            $ids = $this->input->post('id');
            if(!$form['mainpage']) $form['mainpage'] = 0;
            if(!$form['status']) $form['status'] = 0;

            if($ids) {
                $this->db->update('categories')->where('id',$ids)->set($form);
            } else {
                if(!$form['mainpage_rows']) $form['mainpage_rows'] = 999;
                if(!$form['rows']) $form['rows'] = 999;
                $this->db->insert('categories')->set($form);
                $ids = $this->db->lastId();
            }

            parent::saveURL([
                'url' => convertstring($form['name']).'.html',
                'controller' => 'categories',
                'method' => 'view',
                'record_id' => $ids,
                'lang' => Session::fetch('ActiveAdminLang','flag'),
            ]);
            $this->setMessage('Bilgiler başarı ile kayıt edildi.','success');
        }

        $WHERE[] = ["parent_id",$parent,'='];

        if($f = $this->input->get('f'))
        {
            if($f['status']!='all') $WHERE[] = ["status",($f['status']),'='];
            if($f['name']) $WHERE[] = ["name",$f['name'],'LIKE'];
        }

        if(intval($_GET['perpage'])) {
            $perpage = intval($_GET['perpage']);
        }

        $CNT = $this->db->from('categories')->select('COUNT(id) Total');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2]);
        $CNT->limit(0,1);
        $count = $CNT->first();

        $pagi = new Pagination();
        $pagi->link = ADMINBASEURL.'categories/main/'.$parent.'/';
        $pagi->count = $count['Total'];
        $pagi->perpage = $perpage;
        $pagi->pg = $page;
        $pagi->startPage();

        $CNT = $this->db->from('categories');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2]);
        $results = $CNT->orderby('rows','ASC')->limit($pagi->start,$pagi->perpage)->run();

        foreach($results as $key => $result)
        {
            $results[$key]['create_date'] = date("d.m.Y H:i",$result['create_time']);
            $results[$key]['mainpage_name'] = $Status[$result['mainpage']];
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
            $data = $this->db->from('categories')->where('id',$id)->first();
            $data['url'] = BASEURL . parent::getURL('categories',$id);
            return $data;
        }
    }

    public function remove($id){
        $record = self::get_record($id);
        if($record) {
            $ids = parent::catIDList($id);
            array_push($ids,$id);
            $this->db->delete('seo_links')->in('record_id',$ids)->where('controller','categories')->done();
            $this->db->delete('categories')->in('id',$ids)->done();
            $this->setMessage('Kategori(ler) başarı ile silinmiştir !','success');
        }
    }
}