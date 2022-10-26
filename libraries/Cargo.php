<?php

class Cargo {

    public static function calculate($data , $DB) {

        if($data['shipping']) {
            $city = $DB->from('citys c')->join('users_address ua','ua.city_id = c.id','LEFT')->where('ua.id',$data['shipping'])->select('c.zone_id')->first();
        }
        if($data['city_id']) {
            $city = $DB->from('citys')->where('id',$data['city_id'])->select('zone_id')->first();
        }
        $DB->from('cargo_companys')->where('status',1);
        if($data['cargo_id']) $DB->where('id',$data['cargo_id']);
        $cargoCompany = $DB->orderby('name','ASC')->run();
        foreach($cargoCompany as $key => $cc) {
            if($data['free_cargo']) $cc['price'] = 0;

            if(intval($cc['free_cargo_price']) && $data['genel_total']>=$cc['free_cargo_price']) {
                $cc['price'] = 0;
            }

            if($cc['price']) {
                if($city['zone_id']) {
                    $cc['zone_id'] = $city['zone_id'];
                    $ZoneDesi = $DB->from('geo_zone_desi')
                    ->where('zone_id',$city['zone_id'])
                    ->where('cargo_id',$cc['id'])
                    ->where('s_desi',$data['total_desi'],'<=')
                    ->where('e_desi',$data['total_desi'],'>=')
                    ->first();
                    if($ZoneDesi) {
                        $cc['price'] = $ZoneDesi['price'];
                    }

                }
            }

            $cargoCompany[$key] = $cc;
        }

        return $cargoCompany;
    }
}