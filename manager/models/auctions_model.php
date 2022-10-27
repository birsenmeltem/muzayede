<?php
class Auctions_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function lists($page)
    {
        global $MuzayedeStatus;

        $perpage = SHOW_LIST_PAGE;

        if($form = $this->input->post('s')) {
            $ids = $this->input->post('id');
            if(!$form['status']) $form['status'] = 0;
            $form['start_time'] = strtotime($form['start_time']);
            $form['end_time'] = strtotime($form['end_time']);
            $form['live_start_time'] = ($form['end_time'] + 120);

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
                $this->db->update('auctions')->where('id',$ids)->set($form);
            } else {
                $this->db->insert('auctions')->set($form);
                $ids = $this->db->lastId();
            }

            parent::saveURL([
                'url' => 'muzayede/'.convertstring($form['name']).'-'.$ids.'.html',
                'controller' => 'auctions',
                'method' => 'view',
                'record_id' => $ids,
                'lang' => Session::fetch('ActiveAdminLang','flag'),
            ]);
            parent::saveURL([
                'url' => 'canli-muzayede/'.convertstring($form['name']).'-'.$ids.'.html',
                'controller' => 'auctions',
                'method' => 'live',
                'record_id' => $ids,
                'lang' => Session::fetch('ActiveAdminLang','flag'),
            ]);
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

        $CNT = $this->db->from('auctions')->select('COUNT(id) Total');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2]);
        $CNT->limit(0,1);
        $count = $CNT->first();

        $pagi = new Pagination();
        $pagi->link = ADMINBASEURL.'auctions/main/';
        $pagi->count = $count['Total'];
        $pagi->perpage = $perpage;
        $pagi->pg = $page;
        $pagi->startPage();

        $CNT = $this->db->from('auctions');
        foreach($WHERE as $wh) $CNT->where($wh[0],$wh[1],$wh[2]);
        $results = $CNT->orderby('id','DESC')->limit($pagi->start,$pagi->perpage)->run();

        foreach($results as $key => $result)
        {
            $results[$key]['cat'] = $this->db->from('categories')->select('id,name')->where('id',$result['category_id'])->first();
            $results[$key]['start_date'] = date("d.m.Y H:i",$result['start_time']);
            $results[$key]['end_date'] = date("d.m.Y H:i",$result['end_time']);
            $results[$key]['status_name'] = $MuzayedeStatus[$result['status']];
        }
        $data['result'] = $results;
        $data['count'] = $count['Total'];
        $data['pagi'] = $pagi->paginations();
        return $data;
    }

    public function get_record($id)
    {
        if($id) {
            $data = $this->db->from('auctions')->where('id',$id)->first();
            $data['start_date'] = date("d.m.Y H:i",$data['start_time']);
            $data['end_date'] = date("d.m.Y H:i",$data['end_time']);
            return $data;
        }
    }

    public function remove($id){
        $record = self::get_record($id);
        if($record) {
            $this->db->delete('seo_links')->where('record_id',$id)->where('controller','auctions')->done();
            $this->db->delete('auctions')->where('id',$id)->done();
            $this->setMessage('Başarı ile silinmiştir !','success');
        }
    }

    public function get_auctions() {

        $auctions =  $this->db->from('auctions')->in('status',[0,1])->orderby('name','ASC')->run();

        foreach($auctions as $k => $v) {
            $v['start_date'] = date("d.m.Y H:i",$v['start_time']);
            $v['end_date'] = date("d.m.Y H:i",$v['end_time']);

            $auctions[$k] = $v;
        }

        return $auctions;

    }

    public function excel() {
        $auction_id = $this->input->post('auction_id');
        if($auction_id) {

            if($_FILES['excel']['name']) {
                $ext = strtolower(pathinfo($_FILES['excel']['name'], PATHINFO_EXTENSION));
                if(in_array($ext,['xls','xlsx'])) {

                    $filename = date("d-m-Y-H-i-s").'.'.$ext;

                    move_uploaded_file($_FILES['excel']['tmp_name'],'../data/excel/'.$filename);

                    include_once 'libraries/PHPExcel.php';

                    $objPHPExcel = PHPExcel_IOFactory::load('../data/excel/'.$filename);

                    $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
                    $header = array_shift($sheetData);

                    $CAT = [];
                    $TYPE = [];
                    $i = 0;
                    foreach($sheetData as $val) {
                        if(!trim($val['D'])) continue;
                        $pro = $this->db->from('products')->select('id')->where('sku',trim($val['A']))->where('auction_id',$auction_id)->first();
                        if($pro['id']) continue;
                        $tmp = [
                            'sku' => trim($val['A']),
                            'category_id' => self::getcat(trim($val['B']),$CAT),
                            'brand_id' => self::getcins(trim($val['C']),$TYPE),
                            'auction_id' => $auction_id,
                            'name' => str_replace("'",'´',(trim($val['D']))),
                            'shortdetail' => str_replace("'",'´',(trim($val['E']))),
                            'detail' => str_replace("'",'´',trim($val['F'])),
                            'price' => 0,
                            'old_price' => floatval(trim($val['G'])),
                            'seller' => trim($val['H']),
                            'status' => 1,
                            'kdv' => 18,
                            'currency_id' => 1,
                        ];
                        if(in_array($tmp['category_id'],[7,8])) {
                            $tmp['kdv'] = 0;
                        }
                        $values = [
                            'name' => trim($tmp['name']),
                            'shortdetail' => trim($tmp['shortdetail']),
                            'detail' => trim($tmp['detail']),
                        ];
                        $this->db->insert('products')->set($tmp);
                        $id = $this->db->lastId();
                        parent::saveURL([
                            'url' => $id.'-'.convertstring($tmp['name']).'.html',
                            'controller' => 'products',
                            'method' => 'view',
                            'record_id' => $id,
                            'lang' => Session::fetch('ActiveAdminLang','flag'),
                        ]);
                        foreach(['name','shortdetail','detail'] as $key) {
                            if(!$values[$key]) continue;
                            $this->db->insert('langs_translate')->set([
                                'lang' => 'en',
                                'lang_table' => 'products',
                                'lang_field' => $key,
                                'lang_content' => $values[$key],
                                'record_id' => $id,
                            ]);
                        }
                        $i++;
                    }

                    $this->setMessage('Ürünler başarı ile aktarılmıştır ! Toplam : '.$i,'success');
                    return $auction_id;
                } else {
                    $this->setMessage('Lütfen geçerli bir uzantı yükleyiniz. (XLS, XLSX)','danger');
                }
            }
        }

        return false;
    }

    private function getcat($name,&$DATA) {
        if($DATA[$name]) return $DATA[$name];

        $val = $this->db->from('categories')->like('name',$name)->first();
        if($val['id'])  {
            $DATA[$name] = $val['id'];
            return $val['id'];
        } else {
            $this->db->insert('categories')->set([
                'name' => $name,
                'status' => 1,
                'parent_id' => 0,
            ]);
            $DATA[$name] = $this->db->lastId();
            return $DATA[$name];
        }
    }

    private function getcins($name,&$DATA) {
        if($DATA[$name]) return $DATA[$name];

        $val = $this->db->from('brands')->like('name',$name)->first();
        if($val['id'])  {
            $DATA[$name] = $val['id'];
            return $val['id'];
        } else {
            $this->db->insert('brands')->set([
                'name' => $name,
                'status' => 1,
            ]);
            $DATA[$name] = $this->db->lastId();
            return $DATA[$name];
        }
    }

    public function uploadimage(){
        $id = intval("0".$this->input->post('id'));
        if($id) {
            $allowed = explode(',','jpg,jpeg,png');
            $ext = pathinfo($_FILES['file']['name'][0],PATHINFO_EXTENSION);
            if(in_array(strtolower($ext),$allowed))
            {
                include 'libraries/Upload.php';

                foreach ($_FILES["file"]["error"] as $key => $error) {
                    if ($error == UPLOAD_ERR_OK) {

                        $name = explode('.',$_FILES["file"]["name"][$key]);
                        list($sku,$row) = explode('-',$name[0]);
                        $pro = $this->db->from('products')->where('auction_id',$id)->where('sku',$sku)->select('id')->first();
                        if($pro) {
                            $upload = new Upload([
                                'name' =>  $_FILES["file"]["name"][$key],
                                'tmp_name' =>  $_FILES["file"]["tmp_name"][$key],
                            ]);
                            $upload->randname = false;
                            $upload->tmpfolder = '../data/temp/'.$id.'/';
                            if(!is_dir('../data/products/'.$pro['id'])) mkdir('../data/products/'.$pro['id']);
                            if($upload->handle())
                            {
                                $upload->resize('../data/products/'.$pro['id'].'/',$upload->newfilename,1500,1500,false,100,$this->settings['watermark']);
                                $upload->resize('../data/products/'.$pro['id'].'/','s_'.$upload->newfilename,600,600,true,100);
                                $p['product_id'] = $pro['id'];
                                $p['picture'] = 's_'.$upload->newfilename;
                                $p['big_picture'] = $upload->newfilename;
                                $p['rows'] = $row;
                                $this->db->insert('products_images')->set($p);
                                @unlink('../data/temp/'.$id.'/'.$upload->newfilename);
                                die($this->db->lastId());
                            }
                        } else {
                            http_response_code(500);
                            exit;
                        }
                    }
                }
            }
        }
    }

    public function get_sellers($id) {

        $ids[] = 0;
        $pros = $this->db->from('products')->where('auction_id',$id)->select('id')->run();
        foreach($pros as $pro) $ids[] = $pro['id'];

        $auction = $this->db->from('auctions')->where('id',$id)->first();
        $seller =  $this->db->from('products p')
            ->join('users u','u.id = p.seller','LEFT')
            ->select('COUNT(p.seller) total, (SUM(p.price) -
                ((SUM(p.price) * 0.'.intval($auction['sell_comm']).') +
                ((SUM(p.price) * 0.'.intval($auction['sell_comm']).') * 0.18))) total_price, u.id, p.seller, u.name, u.surname, u.phone, u.username')
            ->in('p.id',$ids)->where('p.sale',0,'>')->where('u.id',0,'>')->groupby('p.seller')->orderby('p.seller','ASC')->run();

        $data['seller'] = $seller;

        return $data;
    }

}