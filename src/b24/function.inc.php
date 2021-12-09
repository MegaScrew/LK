<?php 
	require_once 'crest.php';
	define("HOST", "localhost");
	define("USER", "u1243257_is");
	define("PASS", "7N8a4H1m");
	define("DB", "u1243257_is");
/**
* SleepFloatSecs function
*/
function sleepFloatSecs(float $secs) {
    $intSecs = intval($secs);
    $microSecs = ($secs - $intSecs) * 1000000;

    if($intSecs > 0) {
      sleep($intSecs);
    }
    if($microSecs > 0) {
      usleep($microSecs);
    }
}

/**
* Random timer
* @var $min
* @var $max
* @return float var
*/
function randomFloat(int $min, int $max){
	$var = random_int($min, $max);
	return $var / 1000;
}

/**
*  Returns the date in ISO8601 format
* @var $day integer, specifies how many days to add to the current date
* @return date format ISO8601
*/
function dateISO(int $day = 0){
	if ($day == 0) {
		$date = time();
	}else{
		$date = strtotime('+'.$day.' day');
	}
	return date(DateTime::ISO8601, $date);
}

/**
* Returns the name of the month in Russian*
*/
function nextMonth(){
	$arr = [
  		'Январь',
  		'Февраль',
  		'Март',
  		'Апрель',
  		'Май',
  		'Июнь',
  		'Июль',
  		'Август',
  		'Сентябрь',
  		'Октябрь',
  		'Ноябрь',
  		'Декабрь'
	];
	$next_month = date("m")+1 > 12 ? 1 : date("m")+1;
	$post_next_month = $next_month+1 > 12 ? 1 : $next_month+1;
	return $arr[$next_month-1];
}

/**
 * Get user info
 * @param $login
 * @param $password
 * @return user[], manager[], company[]
 **/
function login(string $login = '', string $password = '') {
	$contactId = substr($login, 3);
	$arData = [
		'find_contact' => [
			'method' => 'crm.contact.get',
			'params' => [ 'ID' => $contactId ]
		],
		'get_manager' => [
			'method' => 'user.get',
			'params' => [ 'ID' => '$result[find_contact][ASSIGNED_BY_ID]' ]
		],
		'get_company' => [
			'method' => 'crm.contact.company.items.get',
			'params' => [ 'ID' => '$result[find_contact][ID]']
		]
	];

	$result = CRest::callBatch($arData);
	while($result['error']=="QUERY_LIMIT_EXCEEDED"){
		sleepFloatSecs(randomFloat(800, 3000));
		$result = CRest::callBatch($arData);
		if ($result['error']<>"QUERY_LIMIT_EXCEEDED"){break;}
	}

	if ($result['error_description'] <> 'ID is not defined or invalid.') {
		$user = [];
		$manager = [];
		$company = [];
		if (trim($result['result']['result']['find_contact']['UF_CRM_1611743337']) == trim($password)) {
			$user['ID'] = $result['result']['result']['find_contact']['ID'];
			$user['NAME'] = $result['result']['result']['find_contact']['NAME'];
			$user['SECOND_NAME'] = $result['result']['result']['find_contact']['SECOND_NAME'];
			$user['LAST_NAME'] = $result['result']['result']['find_contact']['LAST_NAME'];
			$user['SERIYA'] = $result['result']['result']['find_contact']['UF_CRM_1579553095988'];
			$user['NOMER'] = $result['result']['result']['find_contact']['UF_CRM_1579553128782'];
			$user['KEMKOGDA'] = $result['result']['result']['find_contact']['UF_CRM_1579553168703'];
			$user['ID_MANAGER'] = $result['result']['result']['find_contact']['ASSIGNED_BY_ID'];
			$user['ISACTIVE'] = 1;

			$manager['ID'] = $result['result']['result']['get_manager']['0']['ID'];
			$manager['NAME'] = $result['result']['result']['get_manager']['0']['NAME'];
			$manager['SECOND_NAME'] = $result['result']['result']['get_manager']['0']['SECOND_NAME'];
			$manager['LAST_NAME'] = $result['result']['result']['get_manager']['0']['LAST_NAME'];

			$temp = $result['result']['result']['get_company'];
			foreach ($temp as $value) {
				array_push($company, $value);
			}
		}else{
			$user['ISACTIVE'] = 0;
		}
		
	}else{
		$user['ISACTIVE'] = 0;
	}
	return ['user' => $user, 'manager' => $manager, 'company' => $company];
}

/**
 * Get company info
 * @param $arrId[]
 * @return company[]
 **/
function get_company(array $arrId = []) {
	$total = count($arrId);          // Всего записей в выборке
    $calls = $total;                  // Сколько запросов надо сделать
    $current_call = 0;                // Номер текущего запроса
    $call_count = 0;                  // Счетчик вызовов для соблюдения условия не больше 2-х запросов в секунду

    sleepFloatSecs(randomFloat(500, 2000));     // Делаем паузу перед основной работай  

    $arData = array();                // Массив для вызова callBatch
    $result = array();                // Массив для результатов вызова callBatch
    $totalResultCompany = array();    // Массив всех выбранных магазинов

    /***********Цыкл формирования пакета запросов и выполнение их *********/
    do {
        $current_call++;

        $temp = [                                   // Собираем запрос
            'method' => 'crm.company.list',
            'params' => [
                'filter' => [
                	'ID' => $arrId[$current_call-1]        // ID сделки
                ],
                'select' => [
	            	'ID',
	            	'TITLE',
	            	'REVENUE',
	            	'UF_CRM_1613731949',
	            	'UF_CRM_1619173084',
	            	'UF_CRM_1579359748326',
	            	'UF_CRM_1594794891',
	            	'UF_CRM_1580400783014'
            	],
            	'start' => -1
            ]
        ];

        array_push($arData, $temp);                 // Сохраняем собранный запрос в массив параметров arData для передачи его в callBatch

        if ((count($arData) == 50) || ($current_call == $calls)) {  // Если в массиве параметров arData 50 запросов или это последний запрос
            
            $call_count++;                                      // При каждом вызове увеличиваем счетчик
            if ($call_count == 3) {                             // Проверяем счетчик вызовов call_count
                sleepFloatSecs(randomFloat(500, 2000));                                       // Если да то делаем паузу 1 сек
                $call_count = 0;                                // Сбрасываем счетчик
            }

            $result = CRest::callBatch($arData);                // Вызываем callBatch
            
            while($result['error']=="QUERY_LIMIT_EXCEEDED"){
                sleepFloatSecs(randomFloat(500, 2000));
                $result = CRest::callBatch($arData);
                if ($result['error']<>"QUERY_LIMIT_EXCEEDED"){break;}
            }     
            
            $temp = $result['result']['result'];   // Убираем лишнее вложение в массиве
			foreach ($temp as $value) {
				array_push($totalResultCompany, $value['0']);
			}

            $arData = [];                                       // Очишаем массив параметров arData для callBatch
        }
    } while ($current_call < $calls);                           // Проверяем условие что текущих вызовов меньще чем надо сделать всего

    $temp = get_deal_info($totalResultCompany);
	    // echo '<pre>';
	    // 	print_r($temp);
	    // echo '</pre>';

	    foreach ($temp as $value) {
			foreach ($totalResultCompany as &$company) {
				if ($value['COMPANY_ID'] == $company['ID']) {
					$company['DEAL_ID'] = $value['ID'];
					$company['Balance'] = $value['UF_CRM_1628167713'];
            		if ($company['UF_CRM_1580400783014']=='3490'){$company['Type']='Верный';}
            		if ($company['UF_CRM_1580400783014']=='54'){$company['Type']='Пятёрочка';}
            		if ($company['UF_CRM_1580400783014']=='68'){$company['Type']='Перекресток';}
            		if ($company['UF_CRM_1580400783014']=='846'){$company['Type']='Перекресток-СКЛАД';}
            		if ($company['UF_CRM_1580400783014']=='844'){$company['Type']='Монетка';}
            		if ($company['UF_CRM_1580400783014']=='848'){$company['Type']='РЦ';}
            		if ($company['UF_CRM_1580400783014']=='52'){$company['Type']='Магнит';}
            		if ($company['UF_CRM_1580400783014']=='3656'){$company['Type']='Перекрёсток-Фабрика';}
            		if ($company['UF_CRM_1580400783014']=='1004'){$company['Type']='Экономный';}
				}
			}
		}

    return $totalResultCompany;
}

/**
 * Get company user info
 * @param $contactId
 * @return company[], company_detail[]
 **/
function get_company_user(string $contactId = '0') {
	$arData = [
		'get_company' => [
			'method' => 'crm.contact.company.items.get',
			'params' => [ 'ID' => $contactId]
		]
	];

	$result = CRest::callBatch($arData);
	while($result['error']=="QUERY_LIMIT_EXCEEDED"){
		sleepFloatSecs(randomFloat(800, 3000));
		$result = CRest::callBatch($arData);
		if ($result['error']<>"QUERY_LIMIT_EXCEEDED"){break;}
	}

	if ($result['error_description'] <> 'ID is not defined or invalid.') {
		$company = [];
		$arrId = [];
		$temp = $result['result']['result']['get_company'];
		foreach ($temp as $value) {
			array_push($company, $value);
			array_push($arrId, $value['COMPANY_ID']);
		}
	
		$total = count($arrId);          // Всего записей в выборке
	    $calls = $total;                  // Сколько запросов надо сделать
	    $current_call = 0;                // Номер текущего запроса
	    $call_count = 0;                  // Счетчик вызовов для соблюдения условия не больше 2-х запросов в секунду

	    sleepFloatSecs(randomFloat(500, 2000));     // Делаем паузу перед основной работай  

	    $arData = array();                // Массив для вызова callBatch
	    $result = array();                // Массив для результатов вызова callBatch
	    $totalResultCompany = array();    // Массив всех выбранных магазинов

	    /***********Цыкл формирования пакета запросов и выполнение их *********/
	    do {
	        $current_call++;

	        $temp = [                                   // Собираем запрос
	            'method' => 'crm.company.list',
	            'params' => [
	                'filter' => [
	                	'ID' => $arrId[$current_call-1]        // ID сделки
	                ],
	                'select' => [
		            	'ID',
		            	'TITLE',
		            	'REVENUE',
		            	'UF_CRM_1613731949',
		            	'UF_CRM_1619173084',
		            	'UF_CRM_1579359748326',
		            	'UF_CRM_1594794891',
		            	'UF_CRM_1580400783014'
	            	],
	            	'start' => -1
	            ]
	        ];

	        array_push($arData, $temp);                 // Сохраняем собранный запрос в массив параметров arData для передачи его в callBatch

	        if ((count($arData) == 50) || ($current_call == $calls)) {  // Если в массиве параметров arData 50 запросов или это последний запрос
	            
	            $call_count++;                                      // При каждом вызове увеличиваем счетчик
	            if ($call_count == 3) {                             // Проверяем счетчик вызовов call_count
	                sleepFloatSecs(randomFloat(500, 2000));                                       // Если да то делаем паузу 1 сек
	                $call_count = 0;                                // Сбрасываем счетчик
	            }

	            $result = CRest::callBatch($arData);                // Вызываем callBatch
	            
	            while($result['error']=="QUERY_LIMIT_EXCEEDED"){
	                sleepFloatSecs(randomFloat(500, 2000));
	                $result = CRest::callBatch($arData);
	                if ($result['error']<>"QUERY_LIMIT_EXCEEDED"){break;}
	            }
	            
	            $temp = $result['result']['result']; // Убираем лишнее вложение в массиве
				foreach ($temp as $value) {
					array_push($totalResultCompany, $value['0']);
				}

	            $arData = [];                                       // Очишаем массив параметров arData для callBatch
	        }
	    } while ($current_call < $calls);                         // Проверяем условие что текущих вызовов меньще чем надо сделать всего

	    $temp = get_deal_info($totalResultCompany);
	    // echo '<pre>';
	    // 	print_r($temp);
	    // echo '</pre>';

	    foreach ($temp as $value) {
			foreach ($totalResultCompany as &$comp) {
				if ($value['COMPANY_ID'] == $comp['ID']) {
					$comp['DEAL_ID'] = $value['ID'];
					$comp['Balance'] = $value['UF_CRM_1628167713'];
					if ($comp['UF_CRM_1580400783014']=='3490'){$comp['Type']='Верный';}
            		if ($comp['UF_CRM_1580400783014']=='54'){$comp['Type']='Пятёрочка';}
            		if ($comp['UF_CRM_1580400783014']=='68'){$comp['Type']='Перекресток';}
            		if ($comp['UF_CRM_1580400783014']=='846'){$comp['Type']='Перекресток-СКЛАД';}
            		if ($comp['UF_CRM_1580400783014']=='844'){$comp['Type']='Монетка';}
            		if ($comp['UF_CRM_1580400783014']=='848'){$comp['Type']='РЦ';}
            		if ($comp['UF_CRM_1580400783014']=='52'){$comp['Type']='Магнит';}
            		if ($comp['UF_CRM_1580400783014']=='3656'){$comp['Type']='Перекрёсток-Фабрика';}
            		if ($comp['UF_CRM_1580400783014']=='1004'){$comp['Type']='Экономный';}
				}
			}
		}

	    return [ 'company' => $company, 'company_detail' => $totalResultCompany ];
	}
    return 0;
}

/**
 * Get deals info
 * @param $companyId
 * @return company_detail[]
 **/
function get_deal_info(array $companyId = []) {
		$total = count($companyId);          // Всего записей в выборке
	    $calls = $total;                  // Сколько запросов надо сделать
	    $current_call = 0;                // Номер текущего запроса
	    $call_count = 0;                  // Счетчик вызовов для соблюдения условия не больше 2-х запросов в секунду

	    sleepFloatSecs(randomFloat(500, 2000));     // Делаем паузу перед основной работай  

	    $arData = array();                // Массив для вызова callBatch
	    $result = array();                // Массив для результатов вызова callBatch
	    $totalResultDeals = array();    // Массив всех выбранных магазинов

	    /***********Цыкл формирования пакета запросов и выполнение их *********/
	    do {
	        $current_call++;

	        $temp = [                                   // Собираем запрос
	            'method' => 'crm.deal.list',
	            'params' => [
	                'filter' => [
	                	'COMPANY_ID' => $companyId[$current_call-1]['ID'],       // ID компании
	                	'STAGE_SEMANTIC_ID' => 'P'
	                ],
	                'select' => [
		            	'ID',
		            	'COMPANY_ID',
		            	'UF_CRM_1628167713'
	            	],
	            	'start' => -1
	            ]
	        ];

	        array_push($arData, $temp);                 // Сохраняем собранный запрос в массив параметров arData для передачи его в callBatch

	        if ((count($arData) == 50) || ($current_call == $calls)) {  // Если в массиве параметров arData 50 запросов или это последний запрос
	            
	            $call_count++;                                      // При каждом вызове увеличиваем счетчик
	            if ($call_count == 3) {                             // Проверяем счетчик вызовов call_count
	                sleepFloatSecs(randomFloat(500, 2000));                                       // Если да то делаем паузу 1 сек
	                $call_count = 0;                                // Сбрасываем счетчик
	            }

	            $result = CRest::callBatch($arData);                // Вызываем callBatch
	            
	            while($result['error']=="QUERY_LIMIT_EXCEEDED"){
	                sleepFloatSecs(randomFloat(500, 2000));
	                $result = CRest::callBatch($arData);
	                if ($result['error']<>"QUERY_LIMIT_EXCEEDED"){break;}
	            }
	            
	            $temp = $result['result']['result']; // Убираем лишнее вложение в массиве
				foreach ($temp as $value) {
					array_push($totalResultDeals, $value['0']);
				}

	            $arData = [];                                       // Очишаем массив параметров arData для callBatch
	        }
	    } while ($current_call < $calls);                         // Проверяем условие что текущих вызовов меньще чем надо сделать всего

	    return $totalResultDeals;
}


/**
 * Get attorney
 * @param $dealId
 * @return attorney[] 
 **/
function get_attorney(string $dealId = '0') {
	$arData = [
	    'attorney' => [
	        'method' => 'crm.documentgenerator.document.list',
	        'params' => [
	            'order' => [
	                'id' => 'DESC'
	            ],
	            'filter' => [
	                'entityTypeId' =>  2,
	                'entityId' => $dealId, // id сделки
	                'templateId' => 272   // id шаблона документа
	            ],
	            'select' => ['*']
	        ],
	    ]
	];
	$result = CRest::callBatch($arData);

	while($result['error']=="QUERY_LIMIT_EXCEEDED"){
	    sleepFloatSecs(randomFloat(500, 2000));
	    $result = CRest::callBatch($arData);
	    if ($result['error']<>"QUERY_LIMIT_EXCEEDED"){break;}
	}

	$temp = $result['result']['result']['attorney']['documents'];                    
    $attorney = $temp;

	return [ 'attorney' => $attorney ];
}

/**
 * Get shipments
 * @param $inner_number
 * @return shipments1[], shipments1[] 
 **/
function get_shipments(string $inner_number = '0') {
	$shipments1 = [];
	$shipments2 = [];
	try {
  		$db = new PDO('mysql:host='.HOST.';dbname='.DB.';charset=UTF8;', USER, PASS);
	} catch (PDOException $e) {
  		print "Error!: " . $e->getMessage();
  		die();
	}
	$year = "2021";//$_POST['year'];
	$month = date('m', strtotime("first day of previous month"));//$_POST['month'];
	$month2 = date('m');//$_POST['month'];
	
	$date1 = $year."-".$month.'-01';
	$date2 = $year."-".$month.'-31';

	$stmt = $db->prepare("SELECT `id`, `date`, `name_of_numenclature`, `shipped_with_adjustments` FROM `revice` WHERE `inner_number`=:inner_number AND `date` BETWEEN :date1 AND :date2 ORDER BY `date` ASC");
	$stmt->execute(['inner_number' => $inner_number, 'date1' => $date1, 'date2' => $date2]);
	$i = 0;
	while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
		$shipments1[$i]['id'] = $row->id;
		$shipments1[$i]['date'] = $row->date;
		$shipments1[$i]['name_of_numenclature'] = $row->name_of_numenclature;
		$shipments1[$i]['shipped_with_adjustments'] = $row->shipped_with_adjustments;
		$i++;
	}
	$month2 = date('m');
	$date1 = $year."-".$month2.'-01';
	$date2 = $year."-".$month2.'-31';

	$stmt = $db->prepare("SELECT `id`, `date`, `name_of_numenclature`, `shipped_with_adjustments` FROM `revice` WHERE `inner_number`=:inner_number AND `date` BETWEEN :date1 AND :date2 ORDER BY `date` ASC");
	$stmt->execute(['inner_number' => $inner_number, 'date1' => $date1, 'date2' => $date2]);
	$i = 0;
	while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
		$shipments2[$i]['id'] = $row->id;
		$shipments2[$i]['date'] = $row->date;
		$shipments2[$i]['name_of_numenclature'] = $row->name_of_numenclature;
		$shipments2[$i]['shipped_with_adjustments'] = $row->shipped_with_adjustments;
		$i++;
	}

	return [ 'shipments1' => $shipments1, 'shipments2' => $shipments2];
}

/**
 * Get certificate
 * @param $dealId
 * @return certificate[]
 **/
function get_certificate(string $dealId = '0') {
	$certificate = [];

	$paramsv = [
		"IBLOCK_TYPE_ID" => 'lists',
		"IBLOCK_ID" => 28,
		"FILTER"=> [
			"PROPERTY_220" => "D_".$dealId,
			"PROPERTY_222" =>160,//тип документа - справка
		]
	];
	
	$check_certificate = CRest::call('lists.element.get', $paramsv);
	while($check_certificate['error']=="QUERY_LIMIT_EXCEEDED"){
		sleepFloatSecs(randomFloat(500, 2000));
		$check_certificate = CRest::call('lists.element.get', $paramsv);
		if ($check_certificate['error']<>"QUERY_LIMIT_EXCEEDED"){break;}
	}

	for ($i=0;$i<$check_certificate['total'];$i++){
		$temp = array_values($check_certificate['result'][$i]['PROPERTY_868']);
        array_push($certificate, $temp[0]);
    }                    							

	return [ 'certificate' => $certificate ];
}

/**
 * Set certificate
 * @param $dealId
 * @param $fileDate
 * @param $file
 * @return $dealId, $fileDate, $message
 **/
function set_certificate(string $dealId = '0', string $fileDate, $file) {
    $day_of_certificate=date("d.m.Y",strtotime($fileDate));
    $razreshenniye_simvoli = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $random_number = substr(str_shuffle($razreshenniye_simvoli), 0, 15);
    $name = $random_number.'.'.pathinfo( $file['file']['name'], PATHINFO_EXTENSION );
        $params = array(
            'id' => 1633722,//id папки test
            'data' => array(
                'NAME' => $name,
            ),
            'fileContent' => [$name, base64_encode(file_get_contents($file['file']['tmp_name']))]
        );
        $diskUpload = CRest::call('disk.folder.uploadfile', $params);
        while($diskUpload['error']=="QUERY_LIMIT_EXCEEDED"){
        	sleepFloatSecs(randomFloat(500, 2000));
            $diskUpload = CRest::call('disk.folder.uploadfile', $params);
            if ($diskUpload['error']<>"QUERY_LIMIT_EXCEEDED"){break;}
        }
        $file_id = $diskUpload['result']['FILE_ID'];
        $element_id = $diskUpload['result']['ID'];
        $random_number = substr(str_shuffle($razreshenniye_simvoli), 0, 15);
        $params = array(
            "IBLOCK_TYPE_ID" => 'lists',
            "IBLOCK_ID" => 28,
            "ELEMENT_CODE" => $name,
            "FIELDS"=> array(
                "NAME" => "test",
                "PROPERTY_218" =>$file_id,
                "PROPERTY_222" =>160,//тип документа - справка
                "PROPERTY_228" =>162,//наличие в бумажном виде - нет
                "PROPERTY_224" =>$day_of_certificate,
                "PROPERTY_220" =>'D_'.$dealId
            )
        );
        $result = CRest::call('lists.element.add', $params);
        while($result['error']=="QUERY_LIMIT_EXCEEDED"){
        	sleepFloatSecs(randomFloat(500, 2000));
            $result = CRest::call('lists.element.add', $params);
            if ($result['error']<>"QUERY_LIMIT_EXCEEDED"){break;}
        }
        if ($result['error']==""){
            $message = 0;
        }
        elseif ($result['error']=="ERROR_ELEMENT_FIELD_VALUE"){
            $message = 1;
        }
        else {
            $message = 2;
        }
        $params = array('id' => $element_id);
        $result = CRest::call('disk.file.delete', $params);
        while($result['error']=="QUERY_LIMIT_EXCEEDED"){
        	sleepFloatSecs(randomFloat(300, 900));
            $result = CRest::call('disk.file.delete', $params);
            if ($result['error']<>"QUERY_LIMIT_EXCEEDED"){break;}
        }
        $file['file']="";


	return [ 'dealId' => $dealId,  'fileDate' => $fileDate, 'message' => $message ];
}

/**
 * Get contract
 * @param $dealId
 * @return contract[]
 **/
function get_contract(string $dealId = '0') {
	$contract = [];

	$paramsv = [
		"IBLOCK_TYPE_ID" => 'lists',
		"IBLOCK_ID" => 28,
		"FILTER"=> [
			"PROPERTY_220" => "D_".$dealId,
			"PROPERTY_222" =>158,//тип документа - договор
		]
	];
	
	$check_contract = CRest::call('lists.element.get', $paramsv);
	while($check_contract['error']=="QUERY_LIMIT_EXCEEDED"){
		sleepFloatSecs(randomFloat(500, 2000));
		$check_contract = CRest::call('lists.element.get', $paramsv);
		if ($check_contract['error']<>"QUERY_LIMIT_EXCEEDED"){break;}
	}

	for ($i=0;$i<$check_contract['total'];$i++){
		$temp = array_values($check_contract['result'][$i]['PROPERTY_868']);
        array_push($contract, $temp[0]);
    }                    							

	return [ 'contract' => $contract ];
}

/**
 * Set certificate
 * @param $dealId
 * @param $fileDate
 * @param $file
 * @return $dealId, $fileDate, $message
 **/
function set_contract(string $dealId = '0', string $fileDate, $file) {
    $day_of_contract=date("d.m.Y",strtotime($fileDate));
    $razreshenniye_simvoli = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $random_number = substr(str_shuffle($razreshenniye_simvoli), 0, 15);
    $name = $random_number.'.'.pathinfo( $file['file']['name'], PATHINFO_EXTENSION );
        $params = array(
            'id' => 1633722,//id папки test
            'data' => array(
                'NAME' => $name,
            ),
            'fileContent' => [$name ,base64_encode(file_get_contents($file['file']['tmp_name']))]
        );
        $diskUpload = CRest::call('disk.folder.uploadfile', $params);
        while($diskUpload['error']=="QUERY_LIMIT_EXCEEDED"){
        	sleepFloatSecs(randomFloat(500, 2000));
            $diskUpload = CRest::call('disk.folder.uploadfile', $params);
            if ($diskUpload['error']<>"QUERY_LIMIT_EXCEEDED"){break;}
        }
        $file_id = $diskUpload['result']['FILE_ID'];
        $element_id = $diskUpload['result']['ID'];
        $random_number = substr(str_shuffle($razreshenniye_simvoli), 0, 15);
        $params = array(
            "IBLOCK_TYPE_ID" => 'lists',
            "IBLOCK_ID" => 28,
            "ELEMENT_CODE" => $name,
            "FIELDS"=> array(
                "NAME" => "test",
                "PROPERTY_218" =>$file_id,
                "PROPERTY_222" =>158,//тип документа - договор
                "PROPERTY_228" =>162,//наличие в бумажном виде - нет
                "PROPERTY_224" =>$day_of_contract,
                "PROPERTY_220" =>'D_'.$dealId
            )
        );
        $result = CRest::call('lists.element.add', $params);
        while($result['error']=="QUERY_LIMIT_EXCEEDED"){
        	sleepFloatSecs(randomFloat(500, 2000));
            $result = CRest::call('lists.element.add', $params);
            if ($result['error']<>"QUERY_LIMIT_EXCEEDED"){break;}
        }
        if ($result['error']==""){
            $message = 0;
        }
        elseif ($result['error']=="ERROR_ELEMENT_FIELD_VALUE"){
            $message = 1;
        }
        else {
            $message = 2;
        }
        $params = array('id' => $element_id);
        $result = CRest::call('disk.file.delete', $params);
        while($result['error']=="QUERY_LIMIT_EXCEEDED"){
        	sleepFloatSecs(randomFloat(300, 900));
            $result = CRest::call('disk.file.delete', $params);
            if ($result['error']<>"QUERY_LIMIT_EXCEEDED"){break;}
        }
        $file['file']="";


	return [ 'dealId' => $dealId,  'fileDate' => $fileDate, 'message' => $message ];
}

/**
 * Get foto
 * @param $shopId
 * @return foto[]
 **/
function get_CalEvents(string $shopId = '0', string $event_month) {
	$getCalEvents = [];

	$paramsv = [
		"IBLOCK_TYPE_ID" => 'lists',
		"IBLOCK_ID" => 92,
		"FILTER"=> [
			"PROPERTY_638" => $shopId,
			">=PROPERTY_620" => '01.'.date("m.Y", strtotime($event_month)),//дата добавления
            "<=PROPERTY_620" => '31.'.date("m.Y", strtotime($event_month))//дата добавления
		]
	];
	$result = CRest::call('lists.element.get', $paramsv);
	while($result['error']=="QUERY_LIMIT_EXCEEDED"){
		sleepFloatSecs(randomFloat(500, 2000));
		$result = CRest::call('lists.element.get', $paramsv);
		if ($result['error']<>"QUERY_LIMIT_EXCEEDED"){break;}
	}
	for ($i=0;$i<$result['total'];$i++){
		$temp[0] = array_values($result['result'][$i]['PROPERTY_620']);
		if ($result['result'][$i]['PROPERTY_800'] != "") {
			$temp[1] = array_values($result['result'][$i]['PROPERTY_800']);
		} else { $temp[1] = ['https://smaip.ru/favicon.ico'];}
        
        $temp[0][0] = date("Y.m.d", strtotime($temp[0][0]));
        array_push($getCalEvents, $temp);
    }                    							

	return [ 'getCalEvents' => $getCalEvents ];
}

/**
 * Set foto
 * @param $shopId
 * @param $fileDate
 * @param $file
 * @return $shopId, $fileDate, $message
 **/
function set_CalEvents(string $shopId = '0', string $InnerNumber = '0', string $Type = '0', string $fileDate, $file) {
	$razreshenniye_simvoli = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $random_number = substr(str_shuffle($razreshenniye_simvoli), 0, 15);
    $name = $random_number.'.'.pathinfo( $file['files']['name'][0], PATHINFO_EXTENSION );
	$count_pic = count($file['files']['name']);
	$params = [
		'id' => 1633722, // id папки
		'data' => ['NAME' => $name],
		'fileContent' => [$name ,base64_encode(file_get_contents($file['files']['tmp_name'][0]))]
	];
	
	$result = CRest::call('disk.folder.uploadfile', $params);
	while($result['error']=="QUERY_LIMIT_EXCEEDED"){
		sleepFloatSecs(randomFloat(500, 2000));
		$result = CRest::call('disk.folder.uploadfile', $params);
		if ($result['error']<>"QUERY_LIMIT_EXCEEDED"){break;}
	}
	$file_id = $result['result']['FILE_ID'];
	$element_id = $result['result']['ID'];
	$random_number = substr(str_shuffle($razreshenniye_simvoli), 0, 15);
	$params = [
		"IBLOCK_TYPE_ID" => 'lists',
		"IBLOCK_ID" => 92,
		"ELEMENT_CODE" => $name,
		"FIELDS"=> array(
			"NAME" => $Type,// название магазина списком
			"PROPERTY_638" =>$shopId,
			"PROPERTY_616" =>$file_id,
			"PROPERTY_620" =>$fileDate,
			"PROPERTY_640" =>$InnerNumber// внутренний номер магазина
		)
	];
	$result = CRest::call('lists.element.add', $params);
	while($result['error']=="QUERY_LIMIT_EXCEEDED"){
		sleepFloatSecs(randomFloat(500, 2000));
		$result = CRest::call('lists.element.add', $params);
		if ($result['error']<>"QUERY_LIMIT_EXCEEDED"){break;}
	}
	$ELEMENT_id = $result['result'];
	$params = ['id' => $element_id];
    CRest::call('disk.file.delete', $params);
	if($count_pic > 1 & $count_pic < 11){
		for($i =1; $i < $count_pic; $i++){
            $random_number = substr(str_shuffle($razreshenniye_simvoli), 0, 15);
    		$name = $random_number.'.'.pathinfo( $file['files']['name'][$i], PATHINFO_EXTENSION );
    		$params = [
				'id' => 1633722, // id папки
				'data' => array('NAME' => $name),
				'fileContent' => [$name, base64_encode(file_get_contents($file['files']['tmp_name'][$i]))]
			];

			$result = CRest::call('disk.folder.uploadfile', $params);
			while($result['error']=="QUERY_LIMIT_EXCEEDED"){
				sleepFloatSecs(randomFloat(500, 2000));
				$result = CRest::call('disk.folder.uploadfile', $params);
				if ($result['error']<>"QUERY_LIMIT_EXCEEDED"){break;}
			}
			$file_id = $result['result']['FILE_ID'];
			$element_id = $result['result']['ID'];
			$random_number = substr(str_shuffle($razreshenniye_simvoli), 0, 15);
			$params = [
				"IBLOCK_TYPE_ID" => 'lists',
				"IBLOCK_ID" => 92,
				"ELEMENT_ID" => $ELEMENT_id,
				"FIELDS"=> array(
					"NAME" => $Type,// название магазина списком
					"PROPERTY_638" =>$shopId,
					"PROPERTY_616" =>$file_id,
					"PROPERTY_620" =>$fileDate,
					"PROPERTY_640" =>$InnerNumber// внутренний номер магазина
				)
			];
			$result = CRest::call('lists.element.update', $params);
            while($result['error']=="QUERY_LIMIT_EXCEEDED"){
                sleepFloatSecs(randomFloat(500, 2000));
                $result = CRest::call('lists.element.update', $params);
                if ($result['error']<>"QUERY_LIMIT_EXCEEDED"){break;}
            }
            if ($result['error']==""){
            	$message = 0;
        	} elseif ($result['error']=="ERROR_ELEMENT_FIELD_VALUE"){
            	$message = 1;
        	} else {
            	$message = 2;
        	}
			//echo '<pre>';print_r($result);echo '</pre>';
			$params = array('id' => $element_id);
    		CRest::call('disk.file.delete', $params);
        }
    }
	// echo "<pre>";
	// echo $shopId.'<br>';
	// echo $fileDate.'<br>';
	// print_r($file);
	// echo "</pre>";
	return [ 'shopId' => $shopId,  'fileDate' => $fileDate, 'message' => $message ];
}

/**
 * Get invoices
 * @param $shopId
 * @return getInvoices[]
 **/
function get_Invoices(string $shopId = '0', string $event_month) {
	$getInvoices = [];

	$paramsv = [
		"IBLOCK_TYPE_ID" => 'lists',
		"IBLOCK_ID" => 46,
		"FILTER"=> [
			"PROPERTY_138" => $shopId,
			">=PROPERTY_140" => '01.'.date("m.Y", strtotime($event_month)),//дата добавления
            "<=PROPERTY_140" => '31.'.date("m.Y", strtotime($event_month))//дата добавления
		]
	];
	$result = CRest::call('lists.element.get', $paramsv);
	while($result['error']=="QUERY_LIMIT_EXCEEDED"){
		sleepFloatSecs(randomFloat(500, 2000));
		$result = CRest::call('lists.element.get', $paramsv);
		if ($result['error']<>"QUERY_LIMIT_EXCEEDED"){break;}
	}
	// echo '<pre>';
	// 	print_r($result);
	// echo '</pre>';
	for ($i=0;$i<$result['total'];$i++){
		$temp[0] = array_values($result['result'][$i]['PROPERTY_140']);
		if ($result['result'][$i]['PROPERTY_802'] != "") {
			$temp[1] = array_values($result['result'][$i]['PROPERTY_802']);
		} else { $temp[1] = ['https://smaip.ru/favicon.ico'];}
        $temp[2] = $result['result'][$i]['ID'];
        $temp[0][0] = date("Y.m.d", strtotime($temp[0][0]));
        array_push($getInvoices, $temp);
    }                    							

	return [ 'getInvoices' => $getInvoices ];
}

/**
 * Set invoices
 * @param $shopId
 * @param $fileDate
 * @param $file
 * @return $shopId, $fileDate, $message
 **/
function set_Invoices(string $shopId = '0', string $InnerNumber = '0', string $Type = '0', string $fileDate, $file) {
	$razreshenniye_simvoli = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $random_number = substr(str_shuffle($razreshenniye_simvoli), 0, 15);
    $name = $random_number.'.'.pathinfo( $file['file']['name'], PATHINFO_EXTENSION );
	// $count_pic = count($file['files']['name']);
	$params = [
		'id' => 1633722, // id папки
		'data' => ['NAME' => $name],
		'fileContent' => [$name, base64_encode(file_get_contents($file['file']['tmp_name']))]
	];
	
	$result = CRest::call('disk.folder.uploadfile', $params);
	while($result['error']=="QUERY_LIMIT_EXCEEDED"){
		sleepFloatSecs(randomFloat(500, 2000));
		$result = CRest::call('disk.folder.uploadfile', $params);
		if ($result['error']<>"QUERY_LIMIT_EXCEEDED"){break;}
	}
	$file_id = $result['result']['FILE_ID'];
	$element_id = $result['result']['ID'];
	$random_number = substr(str_shuffle($razreshenniye_simvoli), 0, 15);
	$params = [
		"IBLOCK_TYPE_ID" => 'lists',
		"IBLOCK_ID" => 46,
		"ELEMENT_CODE" => $name,
		"FIELDS"=> array(
			"NAME" => $Type,// название магазина списком
			"PROPERTY_138" =>$shopId,
			"PROPERTY_132" =>$file_id,
			"PROPERTY_140" =>$fileDate
		)
	];
	$result = CRest::call('lists.element.add', $params);
	while($result['error']=="QUERY_LIMIT_EXCEEDED"){
		sleepFloatSecs(randomFloat(500, 2000));
		$result = CRest::call('lists.element.add', $params);
		if ($result['error']<>"QUERY_LIMIT_EXCEEDED"){break;}
	}
	if ($result['error']==""){
       	$message = 0;
    } elseif ($result['error']=="ERROR_ELEMENT_FIELD_VALUE"){
       	$message = 1;
    } else {
       	$message = 2;
    }

	$ELEMENT_id = $result['result'];
	$params = ['id' => $element_id];
    CRest::call('disk.file.delete', $params);

	return [ 'shopId' => $shopId,  'fileDate' => $fileDate, 'message' => $message ];
}

/**
 * Update invoices
 * @param $shopId
 * @param $fileDate
 * @param $file
 * @return $shopId, $fileDate, $message
 **/
function up_Invoices(string $shopId = '0', string $InnerNumber = '0', string $Type = '0', string $fileDate, $file, string $elementId = '') {
	$razreshenniye_simvoli = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $random_number = substr(str_shuffle($razreshenniye_simvoli), 0, 15);
    $name = $random_number.'.'.pathinfo( $file['file']['name'], PATHINFO_EXTENSION );
	// $count_pic = count($file['files']['name']);
	$params = [
		'id' => 1633722, // id папки
		'data' => ['NAME' => $name],
		'fileContent' => [$name, base64_encode(file_get_contents($file['file']['tmp_name']))]
	];
	
	$result = CRest::call('disk.folder.uploadfile', $params);
	while($result['error']=="QUERY_LIMIT_EXCEEDED"){
		sleepFloatSecs(randomFloat(500, 2000));
		$result = CRest::call('disk.folder.uploadfile', $params);
		if ($result['error']<>"QUERY_LIMIT_EXCEEDED"){break;}
	}
	$file_id = $result['result']['FILE_ID'];
	$element_id = $result['result']['ID'];
	$random_number = substr(str_shuffle($razreshenniye_simvoli), 0, 15);
	$params = [
		"IBLOCK_TYPE_ID" => 'lists',
		"IBLOCK_ID" => 46,
		"ELEMENT_ID" => $elementId,
		"FIELDS"=> array(
			"NAME" => $Type,// название магазина списком
			"PROPERTY_138" =>$shopId,
			"PROPERTY_132" =>$file_id,
			"PROPERTY_140" =>$fileDate,
			"PROPERTY_214" =>$InnerNumber
		)
	];
	$result = CRest::call('lists.element.update', $params);
	while($result['error']=="QUERY_LIMIT_EXCEEDED"){
		sleepFloatSecs(randomFloat(500, 2000));
		$result = CRest::call('lists.element.add', $params);
		if ($result['error']<>"QUERY_LIMIT_EXCEEDED"){break;}
	}
	if ($result['error']==""){
       	$message = 0;
    } elseif ($result['error']=="ERROR_ELEMENT_FIELD_VALUE"){
       	$message = 1;
    } else {
       	$message = 2;
    }

	$ELEMENT_id = $result['result'];
	$params = ['id' => $element_id];
    CRest::call('disk.file.delete', $params);

	return [ 'shopId' => $shopId,  'fileDate' => $fileDate, 'message' => $message ];
}
?>