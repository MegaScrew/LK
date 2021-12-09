<?php
require_once 'function.inc.php';
$formData = json_decode($_GET['formData'], true);

if (isset($_POST['Steps'])) {
	switch ($_POST['Steps']) {
		case '7':
			$formData['Steps'] = '7';
			break;
		case '9':
			$formData['Steps'] = '9';
			break;
		case '11':
			$formData['Steps'] = '11';
			break;
		case '13':
			$formData['Steps'] = '13';
			break;
		case '14':
			$formData['Steps'] = '14';
			break;	
		default:
			// code...
			break;
	}
}

switch ($formData['Steps']) {
	case 'login':
		$user = login($formData['login'], $formData['password']);
		echo(json_encode($user, JSON_UNESCAPED_UNICODE));
		break;
	case '2':
		$company = get_company($formData['data']);
		echo(json_encode($company, JSON_UNESCAPED_UNICODE));
		break;
	case '3':
		$company = get_company_user($formData['data']);
		echo(json_encode($company, JSON_UNESCAPED_UNICODE));
		break;
	case '4':
		$attorney = get_attorney($formData['data']);
		echo(json_encode($attorney, JSON_UNESCAPED_UNICODE));
		break;
	case '5':
		$shipments = get_shipments($formData['data']);
		echo(json_encode($shipments, JSON_UNESCAPED_UNICODE));
		break;
	case '6':
		$certificate = get_certificate($formData['data']);
		echo(json_encode($certificate, JSON_UNESCAPED_UNICODE));
		break;
	case '7':
		$result = set_certificate($_POST['dealId'], $_POST['fileDate'], $_FILES);
		echo(json_encode($result, JSON_UNESCAPED_UNICODE));
		break;
	case '8':
		$contract = get_contract($formData['data']);
		echo(json_encode($contract, JSON_UNESCAPED_UNICODE));
		break;
	case '9':
		$result = set_contract($_POST['dealId'], $_POST['fileDate'], $_FILES);
		echo(json_encode($result, JSON_UNESCAPED_UNICODE));
		break;
	case '10':
		$getCalEvents = get_CalEvents($formData['shopId'], $formData['event_date']);
		echo(json_encode($getCalEvents, JSON_UNESCAPED_UNICODE));
		break;
	case '11':
		$result = set_CalEvents($_POST['shopId'], $_POST['InnerNumber'], $_POST['Type'], $_POST['fileDate'], $_FILES);
		echo(json_encode($result, JSON_UNESCAPED_UNICODE));
		break;
	case '12':
		$getInvoices = get_Invoices($formData['shopId'], $formData['event_date']);
		echo(json_encode($getInvoices, JSON_UNESCAPED_UNICODE));
		break;
	case '13':
		$result = set_Invoices($_POST['shopId'], $_POST['InnerNumber'], $_POST['Type'], $_POST['fileDate'], $_FILES);
		echo(json_encode($result, JSON_UNESCAPED_UNICODE));
		break;
	case '14':
		$result = up_Invoices($_POST['shopId'], $_POST['InnerNumber'], $_POST['Type'], $_POST['fileDate'], $_FILES, $_POST['elementId']);
		echo(json_encode($result, JSON_UNESCAPED_UNICODE));
		break;
	default:
		// code...
		break;
}


// echo '<pre>';
// 	print_r($company);
// echo '</pre>';

?>