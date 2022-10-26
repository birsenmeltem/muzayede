<?php
class Index extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function main()
    {
        parent::set_meta_tags($this->settings);

        $auctions = $this->model->mainpage_auctions();

        $mainpage = $this->model->mainpage_products($auctions);
        $this->view->assign('mainpage_products',$mainpage);

        $this->view->assign('banners',$this->model->banners());
        $this->view->assign('mainpage_auctions',$auctions);

        $this->view->assign('content',$this->view->draw('main',true));
        $this->view->draw('index');
        return true;
    }

    public function search(){

        if(!$q = $this->input->get('q',true)) redirect();

        $data = $this->model->search($q);

        parent::set_meta_tags($data);

        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('search',true);
        $this->view->assign('data',$data);
        $this->view->assign('content',$this->view->draw('search',true));
        $this->view->draw('index');
        return true;
    }

    public function search_archive(){

        if(!$q = $this->input->get('q',true)) redirect();

        $data = $this->model->search_archive($q);

        parent::set_meta_tags($data);

        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('search',true);
        $this->view->assign('data',$data);
        $this->view->assign('content',$this->view->draw('search',true));
        $this->view->draw('index');
        return true;
    }

    public function alarm(){

        if(!$q = $this->input->get('q',true)) redirect();

        $data = $this->model->alarm($q);

        parent::set_meta_tags($data);

        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('search',true);
        $this->view->assign('data',$data);
        $this->view->assign('content',$this->view->draw('alarm',true));
        $this->view->draw('index');
        return true;
    }

    public function muzayede_arsivi()
    {
        parent::set_meta_tags([
            'seo_title' => $this->model->vars['muzayedearsivi']
        ]);

        $data = $this->model->muzayede_arsivi();

        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('data',$data);
        $this->view->assign('content',$this->view->draw('muzayede_arsivi',true));
        $this->view->draw('index');
        return true;
    }

    public function ekspertiz_formu()
    {
        parent::set_meta_tags([
            'seo_title' => $this->model->vars['ekspertizformu']
        ]);

        $data = $this->model->ekspertiz_formu();

        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('content',$this->view->draw('ekspertiz_formu',true));
        $this->view->draw('index');
        return true;
    }

    public function iletisim()
    {
        parent::set_meta_tags([
            'seo_title' => $this->model->vars['iletisim']
        ]);

        if($_POST['i']) $this->model->iletisim();

        $this->view->assign('info',$this->model->showMessages());
        $this->view->assign('content',$this->view->draw('contact',true));
        $this->view->draw('index');
    }

    public function satis_sozlesmesi()
    {
        $data = $this->model->db->from('pages')->where('id',3)->first();
        $this->view->assign('data',$data);
        $this->view->draw('sozlesme');
    }

    public function kvkk()
    {
        $this->view->assign('data',[
            'name' => 'KVKK',
            'detail' => $this->model->settings['kvkk']
        ]);
        $this->view->draw('sozlesme');
    }

    public function checkonline() {
        if(Session::fetch('user','id')) {
            $this->model->db->update('users')->where('id',Session::fetch('user','id'))->set(['online'=>time()]);
        }
        die(json_encode(['time'=>time()]));
    }

    public function auc_sitemap() {
        $this->model->auc_sitemap();
    }

    public function page_sitemap() {
        $this->model->page_sitemap();
    }

    public function cat_sitemap() {
        $this->model->cat_sitemap();
    }
    
    public function pro_sitemap() {
        $this->model->pro_sitemap();
    }

    public function fakeProduct()
    {
        $this->model->fakeProduct();
    }

    public function mesut()
    {
        $this->model->mesut();

    }

}
