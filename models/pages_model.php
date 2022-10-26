<?php
class Pages_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function view($id) {
        $val = $this->db->from('pages')->where('status',1)->where('id',$id)->first();
        if(!$val) return false;

        $this->db->exec("UPDATE pages SET viewed = viewed + 1 WHERE id='{$id}'");

        if($_POST['i']) self::sendcontact($id);

        if($val['yer'] == 0) $val['menu'] = parent::topmenu($val['parent']);
        else $val['menu'] = parent::altmenu($val['parent']);

        return $val;

    }

    public function sendcontact($id = null) {
        $time = time();

        $sess = Session::fetch('page_'.$id);

        $i = $this->input->post('i',true);

        if(!$sess || $sess<$time) {

            if($i['name'] && $i['email'] && $i['message'])
            {
                $html[] = '<table border="1" width="800">';
                $html[] = '<tr>';
                $html[] = '<th>Adı Soyadı</th><td>'.$i['name'].'</td></tr>';
                $html[] = '<tr><th>Email</th><td>'.$i['email'].'</td></tr>';
                $html[] = '<tr><th>Telefon</th><td>'.$i['phone'].'</td></tr>';
                $html[] = '<tr><th>Konu</th><td>'.$i['subject'].'</td></tr>';
                $html[] = '<tr><th>Mesajı</th><td>'.$i['message'].'</td></tr>';
                $html[] = '<tr><th>IP</th><td>'.USER_IP.'</td>';
                $html[] = '</tr>';
                $html[] = '</table>';

                sendMail($this->settings['company_email'],'İletişim Formundan Mesaj Var',implode('',$html));
                $this->setMessage($this->vars['iformgonderildi'],'success');

                Session::set('page_'.$id,$time + 120);
                return true;
            } else {
                $this->setMessage($this->vars['eksikbilgi'],'danger');
            }
        }
        else
        {
            $this->setMessage(sprintf($this->vars['arkaarkayaform'],ceil(($sess - $time)  / 60)),'danger');
        }
    }
}