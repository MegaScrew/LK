<?php

/**
 * Class Bitrix24
 *
 * This class is used to find info about company and contact with deal id
 *
 * @property $id integer
 * @property $id_company integer
 * @property $id_contact integer
 * @property $info array
 * @version 1.5
 *
 */

class Bitrix24
{
    public int $id;
    public array $info = array();
    public string $id_company;
    public string $id_contact;


    public function __construct($id) {
        $this->id = $id;
        $this->info = $this->get_deal_info();
        $this->id_company = $this->info['COMPANY_ID'];
        $this->id_contact = $this->info['CONTACT_ID'];
    }


    private function executeHook($params, $url=false) {
        if ($url == false) {
            $url = 'https://rahalcrm.bitrix24.ru/rest/1644/dw3z156rm6c1t1nw/batch.json';
        }

        $queryUrl = $url;
        $queryData = http_build_query($params);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $queryUrl,
            CURLOPT_POSTFIELDS => $queryData,
        ));

        $result = curl_exec($curl);
        curl_close($curl);

        return json_decode($result, true);
    }

    public function get_deal_info(){
        $batch = array(
            "get_deal_info" => 'crm.deal.get?'
                .http_build_query(array(
                    'ID' => $this->id
                )));
        $queryBitrix = $this->executeHook(array('cmd' => $batch));

        return $queryBitrix['result']['result']['get_deal_info'];
    }

    public function get_company_info_by_deal_id(){
        $batch = array(
            "get_company_info" => 'crm.company.get?'
                .http_build_query(array(
                    'ID' => $this->id_company
                )));
        $queryBitrix = $this->executeHook(array('cmd' => $batch));
        return $queryBitrix['result']['result']['get_company_info'];
    }

    public function get_contact_info_by_deal_id(){
        $batch = array(
            "get_contact_info" => 'crm.contact.get?'
                .http_build_query(array(
                    'ID' => $this->id_contact
                )));
        $queryBitrix = $this->executeHook(array('cmd' => $batch));
        return $queryBitrix['result']['result']['get_contact_info'];
    }

    public function get_file_by_file_id($file_id){
        $batch = array(
            "get_file_info" => 'disk.file.get?'
                .http_build_query(array(
                    'id' => $file_id
                )));
        $queryBitrix = $this->executeHook(array('cmd' => $batch));
        return $queryBitrix['result'];
    }
}
//
//$example = new Bitrix24('87292');
//
//$opportunety = $example -> get_deal_info();
//
//echo '<pre>';
//print_r($opportunety);
//echo '</pre>';