<?php
class Parasut {
    protected $appId = 'bc446dc6e57e94b8e97c051f023d165b473c52c0ea163df9333154f6426e2cba';
    protected $appSecret = '493397eb6ded8d789f4ef1210505037378a08df834fd215e2d054fa95ebf77e0';
    protected $CompanyId = '396453';
    private $Username = 'info@phebusmuzayede.com';
    private $Password = '868790';
    public $access_token = '';
    public $refresh_token = '';
    public $file = 'parasut.ini';
    public $errors;

    const APIURL = 'https://api.parasut.com';
    const ENDPOINT = 'https://api.parasut.com/v4/';
    const AUTHURL = 'https://api.parasut.com/oauth/authorize';
    const TOKENURL = 'https://api.parasut.com/oauth/token';

    public function __construct() {

        $this->file = BASE . 'libraries/parasut.ini';
        $this->checkTokens();
    }

    public function checkTokens()
    {
        try {
            $tokens = parse_ini_file($this->file);
        } catch (\Exception $e) {
            @unlink($this->file);
        }

        if (! isset($tokens['access_token']) || ! isset($tokens['created_at'])) {
            return $this->authorize();
        }
        if (time() - (int) $tokens['created_at'] > 7200) {
            return $this->authorize();
        }
        $this->access_token = $tokens['access_token'];

        return $tokens;
    }

    public function authorize()
    {
        $resp = $this->GetAccessToken();

        if (isset($resp['access_token'])) {
            $token = '';
            foreach ($resp as $key => $value) {
                $token .= $key.'='.$value."\n";
            }
            file_put_contents($this->file, $token);

            $this->access_token = $resp['access_token'];
        }

        return false;
    }

    public function GetAccessToken() {
        $params = [
            'grant_type' =>'password',
            'client_id' => $this->appId,
            'client_secret' => $this->appSecret,
            'username'=> $this->Username,
            'password'=>$this->Password,
            'redirect_uri'=>'urn:ietf:wg:oauth:2.0:oob',
        ];

        $data = self::Request($params, self::TOKENURL, 'POST');
        return $data;
    }

    public function RefreshToken() {
        if($this->refresh_token) {
            $params = [
                'grant_type' =>'refresh_token',
                'client_id' => $this->appId,
                'client_secret' => $this->appSecret,
                'refresh_token'=> $this->refresh_token,
            ];

            $data = self::Request($params, self::TOKENURL, 'POST');
            return $data;
        }
    }

    public function contacts() {
        $params = [
            'page' => [
                'number' => 1,
                'size' => 25,
            ]
        ];

        $url = self::ENDPOINT . $this->CompanyId . '/contacts';
        $header[] = 'Authorization: Bearer '.$this->access_token;
        $result = self::Request($params, $url , 'GET', $header);
        dump($result);
    }

    public function createCustomer($data) {

        $params = [
            'data' => [
                'id' => '',
                'type' => 'contacts',
                'attributes' => [
                    'email' => $data['username'],
                    'name' => $data['name'],
                    'tax_office' => $data['tax_name'],
                    'tax_number' => $data['tax_no'],
                    'district' => $data['state'],
                    'city' => $data['city_name'],
                    'address' => $data['address'],
                    'phone' => $data['phone'],
                    'contact_type' => ($data['tax_name']) ? 'company' : 'person',
                    'account_type' => 'customer',
                ]
            ]
        ];

        $url = self::ENDPOINT . $this->CompanyId . '/contacts';
        $header[] = 'Authorization: Bearer '.$this->access_token;
        $result = self::Request($params, $url , 'POST', $header);

        return ($result['data']['id']);
    }

    public function createProduct($data) {

        $params = [
            'data' => [
                'id' => '',
                'type' => 'products',
                'attributes' => [
                    'code' => $data['stocksku'],
                    'name' => $data['name'].' '.$data['model'],
                    'vat_rate' => $data['kdv'],
                    'unit' => 'Adet',
                    'list_price' => $data['price'],
                    'inventory_tracking' => false,
                ]
            ]
        ];

        $url = self::ENDPOINT . $this->CompanyId . '/products';
        $header[] = 'Authorization: Bearer '.$this->access_token;
        $result = self::Request($params, $url , 'POST', $header);
        return $result['data']['id'];

    }

    public function createInvoce($data) {

        $params = [
            'data' => [
                'id' => '',
                'type' => 'sales_invoices',
                'attributes' => [
                    'item_type' => 'invoice',
                    'issue_date' => date("Y-m-d",$data['invoice_time']),
                    'due_date' => date("Y-m-d",$data['invoice_time']),
                    'order_date' => date("Y-m-d",$data['create_time']),
                    'currency' => 'TRL',
                    'order_no' => $data['code'],
                    'billing_address' => $data['bill']['address'],
                    'billing_phone' => $data['bill']['phone'],
                    'tax_office' => $data['bill']['tax_name'],
                    'tax_number' => (($data['bill']['tax_no']) ? $data['bill']['tax_no'] : '11111111111'),
                    'city' => $data['bill']['city_name'],
                    'district' => $data['bill']['state'],
                ],
                'relationships' => [
                    'contact' => [
                        'data' => [
                            'id' => $data['user']['parasut_id'],
                            'type' => 'contacts',
                        ]
                    ]
                ]
            ]
        ];


        $params['data']['relationships']['details']['data'][] = [
            'id' => '',
            'type' => 'sales_invoice_details',
            'attributes' => [
                'quantity' => 1,
                'unit_price' => $data['product']['price'],
                'vat_rate' => 18,
            ],
            'relationships' => [
                'product' => [
                    'data' => [
                        'id' => '40116545',
                        'type' => 'products',
                    ]
                ]
            ]
        ];


        $url = self::ENDPOINT . $this->CompanyId . '/sales_invoices';
        $header[] = 'Authorization: Bearer '.$this->access_token;
        $result = self::Request($params, $url , 'POST', $header);
        return $result['data']['id'];
    }

    public function FindEfatura($number) {
        if(trim($number)) {
            $url = self::ENDPOINT . $this->CompanyId . '/e_invoice_inboxes';

            $params = [
                'filter' => [
                    'vkn' => trim($number)
                ],
                'page' => [
                    'number' => 1,
                    'size' => 25,
                ]
            ];

            $header[] = 'Authorization: Bearer '.$this->access_token;
            $result = self::Request($params, $url , 'GET', $header);
            return ($result['data'][0]['id']) ? $result['data'][0]['attributes']: 0;
        }
    }

    public function sendInvoice($data) {

        if($data['efatura']) return self::Efatura($data);
        else return self::EArsiv($data);
    }

    private function Efatura($data) {
        $params = [
            'data' => [
                'id' => '',
                'type' => 'e_invoices',
                'attributes' => [
                    'scenario' => 'basic',
                    'to' => $data['efatura'],
                ],
                'relationships' => [
                    'invoice' => [
                        'data' => [
                            'id' => $data['parasut_id'],
                            'type' => 'sales_invoices',
                        ]
                    ]
                ]
            ]
        ];

        $url = self::ENDPOINT . $this->CompanyId . '/e_invoices';
        $header[] = 'Authorization: Bearer '.$this->access_token;
        $result = self::Request($params, $url , 'POST', $header);
        file_put_contents('efatura.txt',print_r($params,1). print_r($result,1), FILE_APPEND);
        return $result['data']['id'];
    }

    private function EArsiv($data) {

        $params = [
            'data' => [
                'id' => '',
                'type' => 'e_archives',
                'attributes' => [
                    'internet_sale' => [
                        'url' => BASEURL,
                        'payment_type' => 'EFT/HAVALE',
                        'payment_platform' => 'BANKA',
                        'payment_date' => date("Y-m-d"),
                    ]
                ],
                'relationships' => [
                    'sales_invoice' => [
                        'data' => [
                            'id' => $data['parasut_id'],
                            'type' => 'sales_invoices',
                        ]
                    ]
                ]
            ]
        ];

        $url = self::ENDPOINT . $this->CompanyId . '/e_archives';
        $header[] = 'Authorization: Bearer '.$this->access_token;
        $result = self::Request($params, $url , 'POST', $header);
        //file_put_contents('earsiv.txt',print_r($params,1). print_r($result,1), FILE_APPEND);
        return $result['data']['id'];

    }

    public function createPay($data) {

        $params = [
            'data' => [
                'id' => '',
                'type' => 'payments',
                'attributes' => [
                    'account_id' => '695872',
                    'date' => date("Y-m-d"),
                    'amount' => $data['price'],
                ]
            ]
        ];

        $url = self::ENDPOINT . $this->CompanyId . '/sales_invoices/'.$data['parasut_id'].'/payments';
        $header[] = 'Authorization: Bearer '.$this->access_token;
        $result = self::Request($params, $url , 'POST', $header);
        return $result['data']['id'];
    }

    public function GetPDF($id , $type = 'e_archives') {
        $url = self::ENDPOINT . $this->CompanyId . '/'.$type.'/'.$id.'/pdf';
        $header[] = 'Authorization: Bearer '.$this->access_token;
        $result = self::Request(false, $url , 'GET', $header);
        return $result['data']['attributes']['url'];
    }

    public function GetInvoiceInfo($invoice_id) {
        $url = self::ENDPOINT . $this->CompanyId . '/sales_invoices/'.$invoice_id;
        $header[] = 'Authorization: Bearer '.$this->access_token;
        $result = self::Request(['include' => 'active_e_document'], $url , 'GET', $header);
        $PDF =  $result['data']['relationships']['active_e_document']['data'];
        if($PDF['id']) {
            return self::GetPDF($PDF['id'],$PDF['type']);
        }
        return false;
    }

    public function CheckCron($id) {
        $url = self::ENDPOINT . $this->CompanyId . '/trackable_jobs/'.$id;
        $header[] = 'Authorization: Bearer '.$this->access_token;
        $result = self::Request('', $url , 'GET', $header);
        if($result['errors'])  {
            $result['data']['attributes']['status'] = 'error';
            $result['data']['attributes']['errors'] = $result['errors'];
        }
        return ['id' => $result['data']['id'], 'status' => $result['data']['attributes']['status'], 'error' =>  json_encode($result['data']['attributes']['errors'])];
    }

    private function Request($data , $_url = self::TOKENURL ,$method = 'POST' , $header = []) {
        $ch = curl_init($_url);

        $header[] = 'Content-Type:application/json';

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);    // ssl sayfa baglantilarinda aktif edilmeli
        $results = curl_exec ($ch);
        curl_close ($ch);
        $val = json_decode($results,true);
        if($val['errors']) {
            $Log = [
                'time' => date("d.m.Y H:i"),
                'url' => $_url,
                'header' => $header,
                'post' => $data,
                'return' => $val,
            ];
            $this->errors = $val['errors'];
            file_put_contents('parasut_error.txt',print_r($Log,1),FILE_APPEND);
        }
        return $val;
    }
}