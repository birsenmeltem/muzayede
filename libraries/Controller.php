<?php
class Controller
{
    public $settings;
    public $ActiveLang;

    public function __construct(){
        $this->view = new View();
    }

    public function loadModel($file){
        $filename = BASE.'models/'.$file.'_model.php';
        if(file_exists($filename))
        {
            require_once $filename;
            $modelName = $file . '_Model';
            $this->model = new $modelName();
            $this->view->assign('version',time());
            $this->input = $this->model->input;
            $this->session = $this->model->session;
            $this->cookie = $this->model->cookie;
            self::variables();
        }
    }

    public function variables(){
        $this->settings = $this->model->settings;
        if(Session::fetch('admin','id')) unset($this->settings['footercode'],$this->settings['metacode']);
        $this->view->assign('settings',$this->settings);

        $this->ActiveLang = $this->model->ActiveLang;
        $this->ActiveCurrency = $this->model->ActiveCurrency;
        $this->MainLang = $this->model->mainLang;

        $this->view->assign('lang',$this->model->vars);

        $langs = $this->model->getLangs();
        foreach($langs as $key => $val) {
            $val['url'] = BASEURL . 'lang/'.$val['flag'];
            if($this->ActiveLang == $val['flag']) $ActiveLang =  $val;
            $langs[$key] = $val;
        }

        if(!Session::fetch('user','id') && $_COOKIE['_user']) {
            $id_hashed = (int)decrypt($_COOKIE['_user']);
            if($id_hashed) {
                $us = $this->model->db->from('users')->where('id',$id_hashed)->where('status',1)->first();
                if($us) {
                    Session::set('user',$us);
                }
            }
        }

        if(Session::fetch('user','id')) {
            if(Session::fetch('user','id') == 546) Session::uset('user');
            if(Session::fetch('user','id') == 547) Session::uset('user');
            $this->model->db->update('users')->where('id',Session::fetch('user','id'))->set(['online'=>time()]);
            $this->view->assign('user',Session::fetch('user'));

        }
        $this->view->assign('ActiveLang',$ActiveLang);
        $this->view->assign('ActiveCurrency',$this->model->ActiveCurrency);
        $this->view->assign('LANGS',$langs);

        $CART = (array)Session::fetch('CART');
        $this->view->assign('totalCart',count($CART));

        $cats = $this->model->get_main_categories();
        $this->view->assign('categories',$cats);

        $this->view->assign('topmenuhtml',$this->topmenuhtml());
        $this->view->assign('altmenuhtml',$this->altmenuhtml());

        $this->view->assign('currency_exchange',self::currency_exchange());
    }

    public function goback(){
        $url = $_SERVER['HTTP_REFERER'];
        @header("Location: ".$url);
        exit;
    }

    public function set_meta_tags(array $meta){
        if(!$meta['seo_title']) $meta['seo_title'] = (($meta['name']) ? $meta['name'].' | ' : '').$this->settings['seo_title'];
        if(!$meta['seo_keywords']) $meta['seo_keywords'] = $this->settings['seo_keywords'];
        if(!$meta['seo_desc']) $meta['seo_desc'] = $this->settings['seo_desc'];
        $this->view->assign('seo_title',$meta['seo_title']);
        $this->view->assign('seo_keywords',$meta['seo_keywords']);
        $this->view->assign('seo_desc',$meta['seo_desc']);
        if($meta['url']) $this->view->assign('seo_url',$meta['url']);
        if($meta['image']) $this->view->assign('seo_image',$meta['image']);
        $this->view->assign('noindex',$meta['noindex']);
    }

    public function lang($ln){
        $ln = $this->input->xss_clean($ln);

        $lang = $this->model->db->from('langs')->where('flag',$ln)->where('status',1)->first();
        if($lang && file_exists('langs/'. strtolower($lang['flag']).'.php')) {
            Session::set('langs',$lang['flag']);
            setcookie('langs',$lang['flag'], time() + 7960000 , '/');
        }

        $this->goback();
    }

    public function clearfilter(){
        Session::uset('filter');
        $this->goback();
    }

    public function topmenuhtml(){
        return $this->model->topmenu();
    }

    public function altmenuhtml(){
        return $this->model->altmenu();
    }

    /**
     * Google Merchant Ürünler
     *
     */
    public function google_ads(){
        $this->model->google_ads();
    }

    /**
     * Facebook Pixel Ürünler
     *
     */
    public function facebook_ads(){
        $this->model->facebook_ads();
    }

    public function currency_exchange() {

        $EXCHANGE = [];

        $data =  $this->model->GetExchangeRate('TRY',$EXCHANGE);

        foreach(['USD','EUR','GBP'] as $code) {
            $values[$code] = numbers($data[$code]);
        }

        return $values;

    }

}
?>
