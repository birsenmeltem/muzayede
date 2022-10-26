<?php
class Index_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function banners()
    {
        return $this->db->from('banners')->where('status',1)->orderby('rows','ASC')->run();
    }

    public function mainpage_auctions()
    {
        $time = time();
        $values = $this->db->from('auctions')->in('status',[1,2])->where('start_time',$time,'<=')->orderby('end_time','ASC')->run();
        foreach($values as $key => $val) {
            if(date("d-m-Y") == date("d-m-Y",$val['start_time'])) $values[$key]['today'] = true;
            $values[$key]['url'] = parent::get_seo_link('auctions',$val['id']);
            $values[$key]['live_url'] = parent::get_seo_link('auctions',$val['id'],'live');
            if($val['status'] == 2) $values[$key]['url'] = $values[$key]['live_url'];
            $values[$key]['end_date'] = strftime("%d %B, %H:%M",$val['end_time']);
            $values[$key]['start_live'] = strftime("%d %B, %H:%M",strtotime("+2 minutes",$val['end_time']));
            $values[$key]['date'] = date("Y/m/d H:i:s",strtotime("+2 minutes",$val['end_time']));
        }

        return $values;
    }

    public function muzayede_arsivi() {

        $url = BASEURL.'muzayede-arsivi?page=[page]';

        $perpage = $this->settings['show_product_page'];
        $activepage = max(1,intval($this->input->get('page')));

        $this->db->from('auctions')->where('status',3);

        $this->db->select('COUNT(id) total');
        $total = $this->db->total();

        $this->db->orderby('start_time','DESC');

        $limit = $this->db->pagination($total, $perpage, $activepage );
        $this->db->select('*');
        $this->db->limit($limit['start'],$limit['limit']);

        $values = $this->db->run();

        foreach($values as $key => $val) {
            $values[$key]['url'] = parent::get_seo_link('auctions',$val['id']);
            $values[$key]['end_date'] = strftime("%d %B, %H:%M",$val['end_time']);
            $values[$key]['start_live'] = strftime("%d %B, %H:%M",strtotime("+2 minutes",$val['end_time']));
            $values[$key]['date'] = date("Y/m/d H:i:s",$val['end_time']);
        }

        return  [
            'total' => $total,
            'records' => $values,
            'pagination' => $this->db->showPagination($url),
        ];

    }

    public function brands()
    {
        return $this->db->from('brands')->where('status',1)->orderby('rows','ASC')->run();
    }

    public function mainpage_products($cat=null)
    {
        $values = parent::mainpage_products($cat);

        return $values;
    }

    public function search($q){

        $qq = (stripos($q,'"')!== FALSE) ? trim($q,'"') : str_replace(' ','%',$q);

        $data['name'] = sprintf($this->vars['searchhead'],trim($q,'"'));
        $data['url'] =  BASEURL .'search?q='.$q;

        if(strlen($q)>= 3) {


            $arg = [
                'orderby' => [
                    'id','DESC'
                ],
                'where' => [
                    ['(p.name',addslashes($qq),'LIKE','&&'],
                    ['p.shortdetail',addslashes($qq),'LIKE','||'],
                    ['p.detail',addslashes($qq),'LIKE','||'],
                ]
            ];

            if($min = $this->input->get('min')) {
                $arg['where'][] = ['p.price',$min,'>=','&&'];
            }
            $auid[] = 0;

            $aucts = $this->db->from('auctions')->where('start_time',time(),'<=')->where('status',1)->run();
            foreach($aucts as $au) $auid[] = $au['id'];

            $arg['where'][] = ['p.status',1,'=',') &&'];
            $arg['where'][] = ['p.auction_id',$auid,'IN','&&'];

            /*
            if($this->input->get('active')) {


            } else {
                $aucts = $this->db->from('auctions')->where('start_time',time(),'<=')->in('status',[1,2,3])->run();
                foreach($aucts as $au) $auid[] = $au['id'];
                $arg['where'][] = ['p.status',0,'!=',') &&'];
                $arg['where'][] = ['p.auction_id',$auid,'IN','&&'];
            }
            */

            if($max = $this->input->get('max')) {
                $arg['where'][] = ['p.price',$max,'<=','&&'];
            }

            switch($this->input->get('order'))
            {
                case 'price-DESC':
                    $arg['orderby'] = ['price','DESC'];
                    break;
                case 'price-ASC':
                    $arg['orderby'] = ['price','ASC'];
                    break;
                case 'rate':
                    $arg['orderby'] = ['rate','DESC'];
                    break;
                case 'rows':
                    $arg['orderby'] = ['rows','ASC'];
                    break;
                case 'id':
                    $arg['orderby'] = ['id','DESC'];
                    break;
            }

            $arg['url'] = $data['url'].'&order='.implode('-',$arg['orderby']).'&page=[page]';
            $data += parent::GetProducts($arg);

            $data['orderby'] = implode('-',$arg['orderby']);
        } else {
            $this->setMessage($this->vars['min3karakter'],'danger');

        }
        return $data;

    }

    public function search_archive($q){

        $qq = (stripos($q,'"')!== FALSE) ? trim($q,'"') : str_replace(' ','%',$q);

        $data['name'] = sprintf($this->vars['searchhead'],trim($q,'"'));
        $data['url'] =  BASEURL .'search?q='.$q;

        if(strlen($q)>= 3) {


            $arg = [
                'orderby' => [
                    'id','DESC'
                ],
                'where' => [
                    ['(p.name',addslashes($qq),'LIKE','&&'],
                    ['p.shortdetail',addslashes($qq),'LIKE','||'],
                    ['p.detail',addslashes($qq),'LIKE','||'],
                ]
            ];

            if($min = $this->input->get('min')) {
                $arg['where'][] = ['p.price',$min,'>=','&&'];
            }
            $auid[] = 0;

            $aucts = $this->db->from('auctions')->where('start_time',time(),'<=')->where('status',3)->run();
            foreach($aucts as $au) $auid[] = $au['id'];

            $arg['where'][] = ['p.status',[2,3],'IN',') &&'];
            $arg['where'][] = ['p.auction_id',$auid,'IN','&&'];

            /*
            if($this->input->get('active')) {


            } else {
                $aucts = $this->db->from('auctions')->where('start_time',time(),'<=')->in('status',[1,2,3])->run();
                foreach($aucts as $au) $auid[] = $au['id'];
                $arg['where'][] = ['p.status',0,'!=',') &&'];
                $arg['where'][] = ['p.auction_id',$auid,'IN','&&'];
            }
            */

            if($max = $this->input->get('max')) {
                $arg['where'][] = ['p.price',$max,'<=','&&'];
            }

            switch($this->input->get('order'))
            {
                case 'price-DESC':
                    $arg['orderby'] = ['price','DESC'];
                    break;
                case 'price-ASC':
                    $arg['orderby'] = ['price','ASC'];
                    break;
                case 'rate':
                    $arg['orderby'] = ['rate','DESC'];
                    break;
                case 'rows':
                    $arg['orderby'] = ['rows','ASC'];
                    break;
                case 'id':
                    $arg['orderby'] = ['id','DESC'];
                    break;
            }

            $arg['url'] = $data['url'].'&order='.implode('-',$arg['orderby']).'&page=[page]';
            $data += parent::GetProducts($arg);

            $data['orderby'] = implode('-',$arg['orderby']);
        } else {
            $this->setMessage($this->vars['min3karakter'],'danger');

        }
        return $data;

    }

    public function alarm($q){

        $data['name'] = sprintf($this->vars['alarmhead'],$q);
        $data['q'] = $q;
        $data['url'] =  BASEURL .'alarm?q='.$q;

        if(strlen($q)>= 3) {

            $arg = [
                'orderby' => [
                    'rows','ASC'
                ],
                'where' => [
                    ['name',str_replace(' ','%',$q),'LIKE','&&'],
                    ['sku',str_replace(' ','%',$q),'LIKE','||'],
                ]
            ];

            switch($this->input->get('order'))
            {
                case 'price-DESC':
                    $arg['orderby'] = ['price','DESC'];
                    break;
                case 'price-ASC':
                    $arg['orderby'] = ['price','ASC'];
                    break;
                case 'rate':
                    $arg['orderby'] = ['rate','DESC'];
                    break;
                case 'rows':
                    $arg['orderby'] = ['rows','ASC'];
                    break;
                case 'id':
                    $arg['orderby'] = ['id','DESC'];
                    break;
            }

            $arg['url'] = $data['url'].'&order='.implode('-',$arg['orderby']).'&page=[page]';
            $data += parent::GetProducts($arg);

            $data['orderby'] = implode('-',$arg['orderby']);
        } else {
            $this->setMessage($this->vars['min3karakter'],'danger');

        }
        return $data;

    }

    public function iletisim()
    {

        $time = time();

        $sess = Session::fetch('iletisim_');

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

                Session::set('iletisim_',$time + 120);
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

    public function ekspertiz_formu() {
        $i = $this->input->post('i',true);
        if($i) {
            if($i['name'] && $i['email'] && $i['message'])
            {
                $html[] = '<table border="1" width="800">';
                $html[] = '<tr>';
                $html[] = '<th>Adı Soyadı</th><td>'.$i['name'].'</td></tr>';
                $html[] = '<tr><th>Email</th><td>'.$i['email'].'</td></tr>';
                $html[] = '<tr><th>Telefon</th><td>'.$i['phone'].'</td></tr>';
                $html[] = '<tr><th>Mesajı</th><td>'.$i['message'].'</td></tr>';
                $html[] = '<tr><th>IP</th><td>'.USER_IP.'</td>';
                $html[] = '</tr>';
                $html[] = '</table>';

                if($_FILES['files']) {
                    foreach($_FILES['files']['name'] as $key => $name) {
                        $ext = pathinfo($_FILES['files']['name'][$key],PATHINFO_EXTENSION);
                        if(!in_array(strtolower($ext),['jpeg','jpg'])) continue;
                        $filename = md5(microtime(false)).'.'.$ext;
                        move_uploaded_file($_FILES['files']['tmp_name'][$key],'data/temp/'.$filename);
                        $files[] = 'data/temp/'.$filename;
                    }

                }

                if($files) {
                    sendMail($this->settings['company_email'],'Bizimle Satın Formu',implode('',$html),'./',$files);
                    //sendMail('mesut@organizma.web.tr','Bizimle Satın Formu',implode('',$html),'./',$files);
                    $this->setMessage($this->vars['iformgonderildi'],'success');
                } else {
                    $this->setMessage($this->vars['eksikbilgi'],'danger');
                }

                return true;
            } else {
                $this->setMessage($this->vars['eksikbilgi'],'danger');
            }
        }
    }

    public function auc_sitemap(){
        @header("Content-type: text/xml");

        $tmp[] = '<?xml version="1.0" encoding="UTF-8"?>
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $tmp[] = '<url>
        <loc>'.BASEURL.'</loc>
        <lastmod>'.date('c').'</lastmod>
        <changefreq>daily</changefreq>
        <priority>1</priority>
        </url>';

        $values = $this->db->from('auctions')->in('status',[1,2,3])->where('start_time',time(),'<=')->orderby('start_time','DESC')->run();

        foreach($values as $val) {
            $url =  parent::get_seo_link('auctions',$val['id']);
            $tmp[] = '<url>
            <loc>'.BASEURL.$url.'</loc>
            <lastmod>'.date('c',$val['start_time']).'</lastmod>
            <changefreq>daily</changefreq>
            <priority>0.9</priority>
            </url>';
        }

        $tmp[] = '</urlset>';
        echo implode("\r\n",$tmp);
        exit;
    }

    public function pro_sitemap(){
        @header("Content-type: text/xml");

        $tmp[] = '<?xml version="1.0" encoding="UTF-8"?>
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $tmp[] = '<url>
        <loc>'.BASEURL.'</loc>
        <lastmod>'.date('c').'</lastmod>
        <changefreq>daily</changefreq>
        <priority>1</priority>
        </url>';

        $values = $this->db->from('products')->in('status',[1,2,3])->orderby('id','DESC')->limit(0,5000)->select('id,name')->run();

        foreach($values as $val) {
            $url =  parent::get_seo_link('products',$val['id']);
            $tmp[] = '<url>
            <loc>'.BASEURL.$url.'</loc>
            <lastmod>'.date('c').'</lastmod>
            <changefreq>daily</changefreq>
            <priority>0.9</priority>
            </url>';
        }

        $tmp[] = '</urlset>';
        echo implode("\r\n",$tmp);
        exit;
    }

    public function cat_sitemap(){
        @header("Content-type: text/xml");

        $tmp[] = '<?xml version="1.0" encoding="UTF-8"?>
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $tmp[] = '<url>
        <loc>'.BASEURL.'</loc>
        <lastmod>'.date('c').'</lastmod>
        <changefreq>daily</changefreq>
        <priority>1</priority>
        </url>';

        $values = $this->db->from('categories')->in('status',[1])->orderby('name','ASC')->run();

        foreach($values as $val) {
            $url =  parent::get_seo_link('categories',$val['id']);
            $tmp[] = '<url>
            <loc>'.BASEURL.$url.'</loc>
            <lastmod>'.date('c').'</lastmod>
            <changefreq>daily</changefreq>
            <priority>0.9</priority>
            </url>';
        }

        $tmp[] = '</urlset>';
        echo implode("\r\n",$tmp);
        exit;
    }

    public function page_sitemap(){
        @header("Content-type: text/xml");

        $tmp[] = '<?xml version="1.0" encoding="UTF-8"?>
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $tmp[] = '<url>
        <loc>'.BASEURL.'</loc>
        <lastmod>'.date('c').'</lastmod>
        <changefreq>daily</changefreq>
        <priority>1</priority>
        </url>';

        $values = $this->db->from('pages')->in('status',[1])
            ->where('yer',2)
            ->orderby('name','ASC')->run();

        foreach($values as $val) {
            $url =  parent::get_seo_link('pages',$val['id']);
            $tmp[] = '<url>
            <loc>'.BASEURL.$url.'</loc>
            <lastmod>'.date('c',$val['create_time']).'</lastmod>
            <changefreq>daily</changefreq>
            <priority>0.9</priority>
            </url>';
        }

        $tmp[] = '</urlset>';
        echo implode("\r\n",$tmp);
        exit;
    }

    public function fakeProduct() {

        foreach(range(4,50) as $val) {
            $pro = $this->db->from('products')->where('id',1)->first();
            $img = $this->db->from('products_images')->where('product_id',1)->first();
            unset($pro['id'],$img['id']);
            $pro['name'] = $val.' - Lorem Ipsum is simply dummy text of the printing';
            $pro['old_price'] = $pro['old_price'] + $val;
            $pro['sale'] = 0;
            $pro['sku'] = $val;
            $this->db->insert('products')->set($pro);
            $img['product_id'] = $this->db->lastId();
            $this->db->insert('products_images')->set($img);
            mkdir('data/products/'.$img['product_id']);
            copy('data/products/1/'.$img['picture'],'data/products/'.$img['product_id'].'/'.$img['picture']);
            copy('data/products/1/'.$img['big_picture'],'data/products/'.$img['product_id'].'/'.$img['big_picture']);
        }
    }

    public function mesut() {

        $users = $this->db->from('users')->select('id')->run();

        foreach($users as $user) {
            $ua = $this->db->from('users_address')->where('shipping',1)->where('user_id',$user['id'])->orderby('main','DESC')->first();
            if($ua) {
                dump($ua);

                $user_update = [
                    'phone' => $ua['phone'],
                    'address' => $ua['address'],
                    'state' => $ua['state'],
                    'city_id' => $ua['city_id'],
                    'country_id' => $ua['country_id'],
                ];
                $this->db->update('users')->where('id',$user['id'])->set($user_update);

            }
        }
    }

}
