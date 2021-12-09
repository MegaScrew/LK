<?php

/**
 * Class Bitrix24
 *
 * This class is used to find info about company and contact with deal id
 *
 * @property $id string
 * @property $id_company string
 * @property $id_contact string
 * @property $info array
 * @version 1.5
 *
 */

class Bitrix24
{
    public function __construct($id) {
        require '../crest.php';

        $this->id = $id;
        $this->info = $this->get_deal_info();
        $this->id_company = $this->info['COMPANY_ID'];
        $this->id_contact = $this->info['CONTACT_ID'];
    }


    public function get_deal_info(){
        $params = array('ID' => $this->id);
        $queryBitrix = CRest::call('crm.deal.get',$params);
		while($queryBitrix['error'] == "QUERY_LIMIT_EXCEEDED") {
			sleep(1);
			$queryBitrix = CRest::call('crm.deal.get',$params);
			if ($queryBitrix['error']<>"QUERY_LIMIT_EXCEEDED"){break;}
		}
//		print_r($queryBitrix);
        return $queryBitrix['result'];
    }

    public function get_company_info_by_deal_id(){
        $params = array('ID' => $this->id_company);
        $queryBitrix = CRest::call('crm.company.get',$params);
		while($queryBitrix['error'] == "QUERY_LIMIT_EXCEEDED") {
			sleep(1);
			$queryBitrix = CRest::call('crm.deal.get',$params);
			if ($queryBitrix['error']<>"QUERY_LIMIT_EXCEEDED"){break;}
		}
        return $queryBitrix['result'];
    }

    public function get_contact_info_by_deal_id(){
        $params = array('ID' => $this->id_contact);
        $queryBitrix = CRest::call('crm.contact.get',$params);
		while($queryBitrix['error'] == "QUERY_LIMIT_EXCEEDED") {
			sleep(1);
			$queryBitrix = CRest::call('crm.deal.get',$params);
			if ($queryBitrix['error']<>"QUERY_LIMIT_EXCEEDED"){break;}
		}
        return $queryBitrix['result'];
    }

}

//$example = new Bitrix24('87292');
//
//$opportunity = $example -> get_deal_info();
//
//echo '<pre>';
//print_r($opportunity);
//echo '</pre>';