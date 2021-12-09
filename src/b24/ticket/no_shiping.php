<?php
ob_start();
require_once('tcpdf_include.php');
require_once('bitrix24.php');
require_once('regions_constants.php');
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->AddPage();
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('timesnewroman', '', 12, '', false);
global $idRetailer;
$info = new Bitrix24($_REQUEST['id']);
$company_info = $info->get_company_info_by_deal_id();
while($company_info['error']=="QUERY_LIMIT_EXCEEDED"){
	$company_info = $info->get_company_info_by_deal_id();
	if ($company_info['error']<>"QUERY_LIMIT_EXCEEDED"){break;}
}
$company_name = $idRetailer[$company_info['UF_CRM_1580400783014']];
$company_title = $company_info['TITLE'];
$company_number = $company_info['UF_CRM_1579359748326'];
$attorney =  explode('T',$deal_info['UF_CRM_1617255922'])[0];
$attorney = new DateTime("$attorney");
$attorney = date_format($attorney, 'd.m.Y');

$html='
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
</heat>
<body>
<table border="0">
    <tr>
        <td colspan="3" align="center" style="font-size: 14px;"><b>Лист фиксации ОТСУТСТВИЯ отгрузок</b></td>        
    </tr>
    <tr>
        <td colspan="3" align="center"></td>        
    </tr>
    <tr>
        <td colspan="3" align="left">Грузополучатель<br>Поставщик '.$company_name.' '.$company_title.', номер магазина '.$company_number.'<br>
</td>        
    </tr>
    <tr>
        <td border="1" align="center" width="25%">Дата</td>        
        <td border="1" align="center" width="53%">Отсутствие продукции для отгрузки подтверждаю (подпись ответственного лица)</td>        
        <td border="1" align="center" width="25%">Печать</td>        
    </tr>
	 <tr>
        <td border="1"></td>        
        <td border="1" align="center" height="50">Отгрузку не произвели</td>        
        <td border="1"></td>        
    </tr>
	<tr>
        <td border="1"></td>        
        <td border="1" align="center" height="50">Отгрузку не произвели</td>        
        <td border="1"></td>        
    </tr>
	<tr>
        <td border="1"></td>        
        <td border="1" align="center" height="50">Отгрузку не произвели</td>        
        <td border="1"></td>        
    </tr>
	<tr>
        <td border="1"></td>        
        <td border="1" align="center" height="50">Отгрузку не произвели</td>        
        <td border="1"></td>        
    </tr>
	<tr>
        <td border="1"></td>        
        <td border="1" align="center" height="50">Отгрузку не произвели</td>        
        <td border="1"></td>        
    </tr>
	<tr>
        <td border="1"></td>        
        <td border="1" align="center" height="50">Отгрузку не произвели</td>        
        <td border="1"></td>        
    </tr>
	<tr>
        <td border="1"></td>        
        <td border="1" align="center" height="50">Отгрузку не произвели</td>        
        <td border="1"></td>        
    </tr>
	<tr>
        <td border="1"></td>        
        <td border="1" align="center" height="50">Отгрузку не произвели</td>        
        <td border="1"></td>        
    </tr>
	<tr>
        <td border="1"></td>        
        <td border="1" align="center" height="50">Отгрузку не произвели</td>        
        <td border="1"></td>        
    </tr>
	<tr>
        <td border="1"></td>        
        <td border="1" align="center" height="50">Отгрузку не произвели</td>        
        <td border="1"></td>        
    </tr>
</table>
</body>
</html>';

$pdf->writeHTML($html, true, false, true, false, '');
ob_end_clean();
$pdf->Output('Attorney_to_'.$attorney.'.pdf', 'I');