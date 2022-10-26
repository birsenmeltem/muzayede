<?php
class Controller
{
    public function __construct()
    {
        $template = new View();
        $template::configure(array(
            'tpl_dir'=> ADMIN_TEMPLATE
        ));
        $this->view = $template;
    }

    public function loadModel($file)
    {
        $filename = ADMINBASE.'models/'.$file.'_model.php';
        if(file_exists($filename))
        {
            require_once $filename;
            $modelName = $file . '_Model';
            $this->model = new $modelName();
            $this->input = $this->model->input;

            $this->view->assign('meta_title','Yönetici Paneli | '.$this->model->settings['seo_title']);
            $this->view->assign('meta_keyword',$this->model->settings['seo_keyword']);
            $this->view->assign('meta_desc',$this->model->settings['seo_desc']);
            self::variables();
            $this->view->assign('version',time());
        }
    }

    public function variables()
    {
        global $YER;

        $this->view->assign('ActiveAdminLang',Session::fetch('ActiveAdminLang'));
        $this->view->assign('Langs',$this->model->Langs());
        $this->view->assign('YER',$YER);

        $user = Session::fetch('admin');
        if($user) {
            $user = $this->model->db->from('admins')->where('id',$user['id'])->first();
            Session::set('admin',$user);
            $this->view->assign('user_harf',substr($user['name'],0,1));
            $this->view->assign('user',$user);

            $waitOrder = $this->model->db->from('orders')->where('status','0','=')->select('COUNT(id) total')->total();
            $this->view->assign('waitOrder',max(0,$waitOrder));

            $waitCom = $this->model->db->from('comments')->where('status','0','=')->select('COUNT(id) total')->total();
            $this->view->assign('waitCom',max(0,$waitCom));
        }
    }

    public function goback()
    {
        $url = $_SERVER['HTTP_REFERER'];
        @header("Location: ".$url);
        exit;
    }

    public function redirect($url = '/')
    {
        @header("Location: ".ADMINBASEURL . $url);
        exit;
    }

    public function showMessages()
    {
        return $this->model->showMessages();
    }
}
?>