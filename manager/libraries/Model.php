<?php
class Model
{
    public $input;
    public $session;

    public function __construct()
    {
        $this->db = new Database();
        $this->db->Admin = true;

        $this->settings = $this->db->from('settings')->where('id',1)->first();

        define('CACHE_TIME',$this->settings['cache_time']);

        $lang = $this->db->from('langs')->where('main',1)->first();

        if(!$SistemDil = Session::fetch('ActiveAdminLang')) {
            $SistemDil = $lang;
            Session::set('ActiveAdminLang',$SistemDil);
        }

        define('ACTIVELANG',$lang['flag']);

        $this->db->MainLang($lang['flag']);
        $this->db->SetLang($SistemDil['flag']);

        $this->input = new Input();
    }

    public function saveURL($arr)
    {
        self::SaveURLOtherLang($arr);
        $this->db->insert('seo_links')->on_duplicate_key_update($arr)->set($arr);
    }

    public function Langs()
    {
        return $this->db->from('langs')->run();
    }

    public function getURL($controller,$id)
    {
        $url = $this->db->from('seo_links')->select('url')->where('controller',$controller)
        ->where('record_id',$id)->where('lang',Session::fetch('ActiveAdminLang','flag'))->first();
        return $url['url'];
    }

    public function get_categories(){
        if(!$values = get_static_cache(__METHOD__)) {
            $data = $this->db->from('categories')->select('id,name,parent_id,texts,mainpage,mainpage_rows,status,rows')->where('status',1)->orderby('rows','ASC')->run();
            $values = GenerateCategoryList($data);
            set_static_cache(__METHOD__,$values);
        }

        return $values;
    }

    public function GetCountrys() {
        return $this->db->from('countrys')->where('status',1)->orderby('rows ASC,name','ASC')->run();
    }

    public function get_citys($country_id) {
        return $this->db->from('citys')->where('status',1)->where('country_id',$country_id)->orderby('name','ASC')->run();
    }

    public function BrandList(){
        return $this->db->from('brands')->orderby('rows','ASC')->run();
    }

    public function catOptionList($active=NULL,$self=NULL)
    {
        $tmp = array();
        $active = (array)$active;
        $kategoriler_dizi = $this->categoriesList(0,0,$self);
        for ($i=0; $i<count($kategoriler_dizi); $i++)
        {
            $tmp[] = '<option value="'.$kategoriler_dizi[$i][0].'"';
            $tmp[] = (in_array($kategoriler_dizi[$i][0],$active)) ? ' selected="selected"':'';
            $tmp[] = '>';
            for ($j=0;$j<$kategoriler_dizi[$i][2];$j++) $tmp[] = '¦&nbsp;&nbsp;&nbsp;&nbsp;';
            $tmp[] = $kategoriler_dizi[$i][1];
            $tmp[] = '</option>';
        }
        unset($kategoriler_dizi);
        return implode('',$tmp);
    }

    private function categoriesList($parent,$level,$self)
    {
        $a = array();
        $this->db->from('categories')->select('id,name')->where('parent_id',$parent,'=');
        if($self) $this->db->where('parent_id',$self,'!=')->where('id',$self,'!=');
        $catList = $this->db->run(PDO::FETCH_BOTH);

        foreach($catList as $cats)
        {
            $cats[2] = $level;

            $a[] = $cats;
            $b = $this->categoriesList($cats[0],$level+1,$self);
            for ($j=0; $j<count($b); $j++) $a[] = $b[$j];

        }

        return $a;
    }

    public function catIDList($id=0)
    {
        $results = $this->db->from('categories')->select("id, FIND_IN_SET(".$id.",parent_id) AS seviye")->having("seviye=1")->run();

        $kategoriid_dizi = array();

        foreach($results as $sonuc)
        {
            $kategori_id       = $sonuc["id"];
            $kategoriid_dizi[] = $kategori_id;
            $b = $this->catIDList($kategori_id);
            for ($j=0; $j<count($b); $j++)
            {
                $kategoriid_dizi[] = $b[$j];
            }
        }
        return $kategoriid_dizi;
    }

    public function parentCat($id){
        $sonuc = 0;
        $ust = $this->db->from('categories')->select('parent_id')->where('id',$id)->where('status',1)->first();
        return $ust['parent_id'];

    }

    public function parentsCat($id){
        $sonuc = array();
        $sonUst = $id;

        do {
            $sonUst = $this->parentCat($sonUst);
            if($sonUst != 0){
                array_push($sonuc, $sonUst);
            }
        } while($sonUst != 0);

        return $sonuc;
    }

    public function setMessage($text,$class='danger')
    {
        $info = Session::fetch('admin_info');
        $info[] = '<div class="alert alert-'.$class.' background-'.$class.' alert-dismissable">
        '.$text.'
        </div>';
        Session::set('admin_info',$info);
    }

    public function showMessages()
    {
        $info = Session::fetch('admin_info');
        if(is_array($info))
        {
            Session::uset('admin_info');
            return implode('',$info);
        }
    }

    public function SaveURLOtherLang($arr){
        $isrecord = $this->db->from('seo_links')->where('controller',$arr['controller'])->where('record_id',$arr['record_id'])->first();
        if(!$isrecord) {
            $langs = $this->db->from('langs')->where('status',1)->where('flag',$arr['lang'],'!=')->run();
            foreach($langs as $lang) {
                $arr['lang'] = $lang['flag'];
                $this->db->insert('seo_links')->set($arr);
            }
        }
    }
}
?>