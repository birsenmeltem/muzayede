<?php
class Languages_Model extends Model
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

            if(!$ids) $isFlag = $this->db->from('langs')->where('flag',$form['flag'])->first();
            if(!$isFlag) {

                if($_FILES['img']['name'])
                {
                    $file = explode('.', $_FILES['img']['name']);
                    $ext = array_pop($file);
                    if(in_array(strtolower($ext),['jpg','jpeg','png'])) {
                        $NewFileName = convertstring($form['name']).'.'.$ext;
                        move_uploaded_file($_FILES['img']['tmp_name'],'../data/langs/'.$NewFileName);
                        $form['picture'] = $NewFileName;
                    }
                    else
                    {
                        $this->setMessage('Lütfen sadece JPG,JPEG ve PNG uzantılı resim yükleyiniz.!','danger');
                    }
                }

                if($ids) {
                    $this->db->update('langs')->where('id',$ids)->set($form);
                } else {
                    if(!$form['rows']) $form['rows'] = 999;
                    $this->db->insert('langs')->set($form);
                    $ids = $this->db->lastId();

                    if(file_exists(BASE . 'langs/'.$this->db->mainlang.'.php')) {

                        $seoLinks = $this->db->from('seo_links')->where('lang',$this->db->mainlang)->run();
                        foreach($seoLinks as $link) {
                            $link['lang'] = $form['flag'];
                            unset($link['id']);
                            $this->db->insert('seo_links')->set($link);
                        }
                        $datas = file_get_contents(BASE . 'langs/'.$this->db->mainlang.'.php');
                        file_put_contents(BASE . 'langs/'.$form['flag'].'.php',$datas);
                    }
                }

                $this->setMessage('Bilgiler başarı ile kayıt edildi.','success');
            }
            else
                $this->setMessage('Aynı dil kodundan 1 den fazla kayıt olamaz !','danger');
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

        $CNT = $this->db->from('langs')->select('COUNT(id) Total');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2]);
        $CNT->limit(0,1);
        $count = $CNT->first();

        $pagi = new Pagination();
        $pagi->link = ADMINBASEURL.'langs/main/';
        $pagi->count = $count['Total'];
        $pagi->perpage = $perpage;
        $pagi->pg = $page;
        $pagi->startPage();

        $CNT = $this->db->from('langs');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2]);
        $results = $CNT->orderby('id','ASC')->limit($pagi->start,$pagi->perpage)->run();

        foreach($results as $key => $result)
        {
            $results[$key]['currency'] = $this->db->from('currencys')->select('id,name')->where('id',$result['currency_id'])->first();
            $results[$key]['create_date'] = date("d.m.Y H:i",$result['create_time']);
            $results[$key]['status_name'] = $Status[$result['status']];
            $results[$key]['main_name'] = $Status[$result['main']];
        }
        $data['result'] = $results;
        $data['count'] = $count['Total'];
        $data['pagi'] = $pagi->paginations();
        return $data;
    }

    public function get_record($id)
    {
        if($id) {
            return $this->db->from('langs')->where('id',$id)->first();
        }
    }

    public function remove($id){
        $record = self::get_record($id);
        if($record) {
            $this->db->delete('seo_links')->where('lang',$record['flag'])->done();
            $this->db->delete('langs')->where('id',$id)->done();
            $this->db->delete('langs_translate')->where('lang',$record['flag'])->done();
            @unlink("../langs/{$record['flag']}.php");
            @unlink("../data/langs/{$record['flag']}.php");
            $this->setMessage('Başarı ile silinmiştir !','success');
        }
    }

    public function currencys(){
        return $this->db->from('currencys')->orderby('id','ASC')->run();
    }
}