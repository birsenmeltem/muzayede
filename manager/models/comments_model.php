<?php
class Comments_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function lists($page)
    {
        global $CommentStatus;

        $perpage = SHOW_LIST_PAGE;

        if($form = $this->input->post('s')) {
            $ids = $this->input->post('id');
            if(!$form['status']) $form['status'] = 0;

            if($ids) {

                $this->db->update('comments')->where('id',$ids)->set($form);

                $avg = $this->db->from('comments')->where('product_id',$form['product_id'])->where('status',1)->select('AVG(rate) puan')->first();
                $this->db->update('products')->where('id',$form['product_id'])->set(['rate'=>$avg['puan']]);
                $this->setMessage('Bilgiler başarı ile kayıt edildi.','success');
            }


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

        $CNT = $this->db->from('comments')->select('COUNT(id) Total');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2]);
        $CNT->limit(0,1);
        $count = $CNT->first();

        $pagi = new Pagination();
        $pagi->link = ADMINBASEURL.'comments/main/';
        $pagi->count = $count['Total'];
        $pagi->perpage = $perpage;
        $pagi->pg = $page;
        $pagi->startPage();

        $CNT = $this->db->from('comments');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2]);
        $results = $CNT->orderby('status','ASC')->limit($pagi->start,$pagi->perpage)->run();

        foreach($results as $key => $result)
        {
            $results[$key]['product'] = $this->db->from('products')->select('id,name')->where('id',$result['product_id'])->first();
            $results[$key]['user'] = $this->db->from('users')->select('id,CONCAT(name, " ", surname) name,username')->where('id',$result['user_id'])->first();
            $results[$key]['create_date'] = date("d.m.Y H:i",$result['create_time']);
            $results[$key]['status_name'] = $CommentStatus[$result['status']];
        }
        $data['result'] = $results;
        $data['count'] = $count['Total'];
        $data['pagi'] = $pagi->paginations();
        return $data;
    }

    public function get_record($id)
    {
        if($id) {
            return $this->db->from('comments c')
            ->join('products p','p.id = c.product_id','LEFT')
            ->join('users u','u.id = c.user_id','LEFT')
            ->select('c.*, p.name product_name, CONCAT(u.name, " ",u.surname) user_name, u.username')
            ->where('c.id',$id)->first();
        }
    }

    public function remove($id){
        $record = self::get_record($id);
        if($record) {
            $this->db->delete('comments')->where('id',$id)->done();

            $avg = $this->db->from('comments')->where('product_id',$record['product_id'])->where('status',1)->select('AVG(rate) puan')->first();
            $this->db->update('products')->where('id',$record['product_id'])->set(['rate'=>$avg['puan']]);

            $this->setMessage('Başarı ile silinmiştir !','success');
        }
    }

    public function approve($id) {
        $record = self::get_record($id);
        if($record) {
            $this->db->update('comments')->where('id',$id)->set(['status'=>1]);

            $avg = $this->db->from('comments')->where('product_id',$record['product_id'])->where('status',1)->select('AVG(rate) puan')->first();
            $this->db->update('products')->where('id',$record['product_id'])->set(['rate'=>$avg['puan']]);

            $this->setMessage('Başarı ile onaylandı !','success');
        }
    }
}