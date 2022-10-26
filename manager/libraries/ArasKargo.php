<?php
ini_set("soap.wsdl_cache_enabled", 0);
ini_set("soap.wsdl_cache", 0);
ini_set('soap.wsdl_cache_ttl', '0');

class ArasKargo
{
    const GonderiServisUrlTest = 'http://customerservicestest.araskargo.com.tr/arascargoservice/arascargoservice.asmx?WSDL';
    const GonderiServisUrl = 'http://customerws.araskargo.com.tr/arascargoservice.asmx?wsdl';
    const KargoTakipUrl = 'http://customerservices.araskargo.com.tr/ArasCargoCustomerIntegrationService/ArasCargoIntegrationService.svc?wsdl';

    public $DEBUG = false;

    public $username, $password, $CustomerCode;

    public function __set($key,$value)
    {
        $this->$key = $value;
    }

    public function KargoGonder($Order)
    {
        $vars = array(
            'UserName' => $this->username,
            'Password' => $this->password,
            'tradingWaybillNumber' => $Order['barcode'],
            'InvoiceNumber' => $Order['barcode'],
            'IntegrationCode' => $Order['barcode'],
            'ReceiverName' => $Order['shipping']['name'],
            'ReceiverAddress' => $Order['shipping']['address'],
            'ReceiverPhone1' => preg_replace('#[^0-9]+#','',$Order['shipping']['phone']),
            'ReceiverCityName' => $Order['shipping']['city_name'],
            'ReceiverTownName' => $Order['shipping']['state'],
            'PayorTypeCode' => 1,
            'IsWorldWide' => '0',
        );

        $vars['IsCod']  = '';
        $vars['CodAmount'] = '';
        $vars['CodCollectionType'] = '';
        $vars['CodBillingType'] = '';

        if(in_array($Order['payment_type'],[3,4]))
        {
            $vars['IsCod']  = 1;
            $vars['CodAmount'] = $Order['price'];
            $vars['CodCollectionType'] = (($Order['payment_type'] == 3) ? 0 : 1);
            $vars['CodBillingType'] = 0;
        }
        $vars['ReceiverPhone2'] = '';
        $vars['ReceiverPhone3'] = '';
        $vars['VolumetricWeight'] = '';
        $vars['Weight'] = '';
        $vars['PieceCount'] = '';
        $vars['SpecialField1'] = '';
        $vars['SpecialField2'] = '';
        $vars['SpecialField3'] = '';
        $vars['SpecialField3'] = '';
        $vars['Description'] = '';
        $vars['TaxNumber'] = '';
        $vars['TtDocumentId'] = '';
        $vars['TaxOffice'] = '';
        $vars['PrivilegeOrder'] = '';
        $vars['Country'] = '';
        $vars['CountryCode'] = '';
        $vars['CityCode'] = '';
        $vars['TownCode'] = '';
        $vars['ReceiverDistrictName'] = '';
        $vars['ReceiverQuarterName'] = '';
        $vars['ReceiverAvenueName'] = '';
        $vars['ReceiverStreetName'] = '';
        $vars['UnitID'] = '';
        $vars['SenderAccountAddressId'] = '';
        $vars['PieceCount'] = '1';
        $vars['PieceDetails'][] = array(
            "VolumetricWeight" => '',
            "Weight" => '',
            "BarcodeNumber" => substr($Order['barcode'],0,16),
            "ProductNumber" => '',
            "Description" => '',
        );

        if($debug) return $vars;

        try {

            $soap = new SoapClient(self::GonderiServisUrl,array('trace'=>1));
            $params = [
                'orderInfo'=> [
                    'Order'=> $vars,
                ],
                'userName' => $this->username,
                'password' => $this->password,
            ];
            if(!$this->DEBUG) {
                $result = $soap->SetOrder($params);
               // file_put_contents('aras.txt',print_r($result,1));
                $resultCode = $result->SetOrderResult->OrderResultInfo->ResultCode;
                $resultMessage = $result->SetOrderResult->OrderResultInfo->ResultMessage;
            } else {
                $result = new stdClass();
                $resultCode = '0';
                $resultMessage = 'Adres bilgisi hatalıdır !';
            }
            return ($resultCode == 0)  ? ['status' => 'success'] : ['status' => 'failed', 'reason' => trim($resultMessage)];
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
    }

    public function KargoTakip($Code = null)
    {

        $soap = new SoapClient(self::KargoTakipUrl,array('trace'=>1));
        try {

            $LoginInfo =  '<LoginInfo><UserName>'.$this->apikey.'</UserName><Password>'.$this->apipass.'</Password><CustomerCode>'.$this->CustomerCode.'</CustomerCode></LoginInfo>';
            $QueryInfo =  '<QueryInfo><QueryType>1</QueryType><IntegrationCode>'.$Code.'</IntegrationCode></QueryInfo>';

            $result = json_decode($soap->GetQueryJSON(['loginInfo'=> $LoginInfo,'queryInfo'=>$QueryInfo])->GetQueryJSONResult,true);
            //file_put_contents('takip.txt',print_r($result,1));
            if($result['QueryResult']['Cargo']) {
                return [
                    'TakipNo2' => $result['QueryResult']['Cargo']['KARGO_KODU'],
                    'TakipNo' => $result['QueryResult']['Cargo']['KARGO_TAKIP_NO'],
                    'TakipUrl' => 'https://www.araskargo.com.tr/tr/cargo_tracking_detail.aspx?kargo_takip_no='.$result['QueryResult']['Cargo']['KARGO_TAKIP_NO'],
                    'Seri' => substr($result['QueryResult']['Cargo']['IRSALIYE_NUMARA'],0,2),
                    'SiraNo' => substr($result['QueryResult']['Cargo']['IRSALIYE_NUMARA'],2),
                    'AliciSube' => $result['QueryResult']['Cargo']['VARIS_SUBE'],
                    'Adet' => $result['QueryResult']['Cargo']['ADET'],
                    'Desi' => $result['QueryResult']['Cargo']['DESI'],
                    'Kg' => $result['QueryResult']['Cargo']['DESI'],
                    'FaturaTarihi' => date("d.m.Y",strtotime($result['QueryResult']['Cargo']['CIKIS_TARIH'])),
                ];
            }
            return false;
        } catch(Exception $e)
        {

            echo '<pre>';
            print_r($e);
        }

    }

    public function TakipNo($Code = null)
    {
        $soap = new SoapClient(self::KargoTakipUrl,array('trace'=>1));
        try {

            $LoginInfo =  '<LoginInfo><UserName>'.$this->username.'</UserName><Password>'.$this->password.'</Password><CustomerCode>'.$this->CustomerCode.'</CustomerCode></LoginInfo>';
            $QueryInfo =  '<QueryInfo><QueryType>1</QueryType><IntegrationCode>'.$Code.'</IntegrationCode></QueryInfo>';

            $result = json_decode($soap->GetQueryJSON(['loginInfo'=> $LoginInfo,'queryInfo'=>$QueryInfo])->GetQueryJSONResult,true);
            $result = ($result['QueryResult']['Cargo']['ALICI']) ? ($result['QueryResult']['Cargo']) :$result['QueryResult']['Cargo'][0];
            if($result['DURUM_KODU'] == 6) return $result;
            return false;
        } catch(Exception $e)
        {

            echo '<pre>';
            print_r($e);
        }
    }

    public function IadeKargolar()
    {
        $soap = new SoapClient(self::KargoTakipUrl,array('trace'=>1));
        try {

            $LoginInfo =  '<LoginInfo><UserName>'.$this->username.'</UserName><Password>'.$this->password.'</Password><CustomerCode>'.$this->CustomerCode.'</CustomerCode></LoginInfo>';
            $QueryInfo =  '<QueryInfo><QueryType>16</QueryType><StartDate>'.date("d.m.Y",strtotime("-7 days")).'</StartDate><EndDate>'.date("d.m.Y",strtotime("tomorrow")).'</EndDate></QueryInfo>';

            $result = json_decode($soap->GetQueryJSON(['loginInfo'=> $LoginInfo,'queryInfo'=>$QueryInfo])->GetQueryJSONResult,true);
            return ($result['QueryResult']['WaybillNotDelivered']);
        } catch(Exception $e)
        {

            echo '<pre>';
            print_r($e);
        }
    }

    public function KargoDetay($Code = null)
    {
        $soap = new SoapClient(self::KargoTakipUrl,array('trace'=>1));
        try {

            $LoginInfo =  '<LoginInfo><UserName>'.$this->username.'</UserName><Password>'.$this->password.'</Password><CustomerCode>'.$this->CustomerCode.'</CustomerCode></LoginInfo>';
            $QueryInfo =  '<QueryInfo><QueryType>1</QueryType><IntegrationCode>'.$Code.'</IntegrationCode></QueryInfo>';

            return json_decode($soap->GetQueryJSON(['loginInfo'=> $LoginInfo,'queryInfo'=>$QueryInfo])->GetQueryJSONResult,true);
        } catch(Exception $e)
        {

            echo '<pre>';
            print_r($e);
        }
    }
}

$Aras = new ArasKargo();
$Aras->username = $data['username'];
$Aras->password = $data['password'];
$Aras->apikey = $data['api_key'];
$Aras->apipass = $data['api_password'];
$Aras->CustomerCode = $data['customer_code'];
return $Aras;