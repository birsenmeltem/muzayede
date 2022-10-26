<?php
class Payments_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function pos(){
        return $this->db->from('virtual_pos')->orderby('id','ASC')->run();
    }

    public function banks(){
        if($form = $this->input->post('s')) {
            $ids = $this->input->post('id');

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
                $this->db->update('payment_banks')->where('id',$ids)->set($form);
            } else {
                $this->db->insert('payment_banks')->set($form);
                $ids = $this->db->lastId();
            }


            $this->setMessage('Bilgiler başarı ile kayıt edildi.','success');
        }
        return $this->db->from('payment_banks')->orderby('id','ASC')->run();
    }

    public function bank_edit($id){
        if($id) {
            return $this->db->from('payment_banks')->where('id',$id)->first();
        }
    }

    public function bank_remove($id){
        $record = self::bank_edit($id);
        if($record) {
            $this->db->delete('payment_banks')->where('id',$id)->done();
            $this->setMessage('Başarı ile silinmiştir !','success');
        }
    }

    public function installment($pos_id){
        $data['pos'] = $this->db->from('virtual_pos')->where('id',$pos_id)->first();
        if(!$data['pos']) return false;

        if($form = $this->input->post('s')) {
            $ids = $this->input->post('id');

            if($ids) {
                $this->db->update('installments')->where('id',$ids)->set($form);
            } else {
                $this->db->insert('installments')->set($form);
                $ids = $this->db->lastId();
            }
            $this->setMessage('Bilgiler başarı ile kayıt edildi.','success');
        }
        $data['record'] = $this->db->from('installments')->where('vpos_id',$pos_id)->orderby('installment','ASC')->run();

        return $data;
    }

    public function installment_edit($id){
        if($id) {
            return $this->db->from('installments')->where('id',$id)->first();
        }
    }

    public function installment_remove($id){
        $record = self::installment_edit($id);
        if($record) {
            $this->db->delete('installments')->where('id',$id)->done();
            $this->setMessage('Başarı ile silinmiştir !','success');
        }
    }
}