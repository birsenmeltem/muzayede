<?php
class Peyler_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function lists($page)
    {
        $perpage = SHOW_LIST_PAGE;

        $WHERE[] = ["id",0,'!='];

        if($f = $this->input->get('f'))
        {
            if($f['name'] || $f['surname']) {
                $uye = $this->db->from('users')
                ->select('id')
                ->where('name',$f['name'],'LIKE')
                ->where('surname',$f['surname'],'LIKE','||')
                ->run();
                foreach($uye as $usr) {
                    $ids[] = $usr['id'];
                }
                if($ids) $WHERE[] = ["user_id",$ids,'IN','&&'];
                else $WHERE[] = ["user_id",[0],'IN','&&'];
            }
             if($f['product_id']) {
                $urun = $this->db->from('products')
                ->select('id')
                ->where('sku',$f['product_id'],'LIKE')
                ->where('name',$f['product_id'],'LIKE','||')
                ->run();
                foreach($urun as $usr) {
                    $idss[] = $usr['id'];
                }
                if($idss) $WHERE[] = ["product_id",$idss,'IN','&&'];
                else $WHERE[] = ["product_id",[0],'IN','&&'];
            }
        }

        if(intval($_GET['perpage'])) {
            $perpage = intval($_GET['perpage']);
        }

        $CNT = $this->db->from('offers')->select('COUNT(id) Total');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2]);
        $CNT->limit(0,1);
        $count = $CNT->first();

        $pagi = new Pagination();
        $pagi->link = ADMINBASEURL.'peyler/main/';
        $pagi->count = $count['Total'];
        $pagi->perpage = $perpage;
        $pagi->pg = $page;
        $pagi->startPage();

        $CNT = $this->db->from('offers');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2]);
        $results = $CNT->orderby('create_time DESC, id','DESC')->limit($pagi->start,$pagi->perpage)->run();

        foreach($results as $key => $result)
        {
            $results[$key]['product'] = $this->db->from('products')->select('id,name,sku')->where('id',$result['product_id'])->first();
            $results[$key]['user'] = $this->db->from('users')->select('id,name,surname,username')->where('id',$result['user_id'])->first();
            $results[$key]['create_date'] = date("d.m.Y H:i:s",$result['create_time']);
        }

        $data['result'] = $results;
        $data['count'] = $count['Total'];
        $data['pagi'] = $pagi->paginations();
        return $data;
    }

    public function get_record($id)
    {
        if($id) {
            $data = $this->db->from('offers')->where('id',$id)->first();
            return $data;
        }
    }

    public function remove($id){
        $record = self::get_record($id);
        if($record) {
            $this->db->delete('offers')->where('id',$id)->done();
            $this->setMessage('Başarı ile silinmiştir !','success');
        }
    }
}