<?php
ob_start();
require_once('tcpdf_include.php');
require_once('bitrix24.php');
require_once('regions_constants.php');


$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(5, 5, 5);
$pdf->AddPage();
$pdf->SetTextColor(0,0,0);
global $idRetailer;
$info = new Bitrix24($_REQUEST['id']);

$company_info = $info->get_company_info_by_deal_id();
$contact_info = $info->get_contact_info_by_deal_id();
$deal_info = $info->get_deal_info();
//print_r($deal_info);
$opportunity = $deal_info['OPPORTUNITY'];
$qr_url = 'https://rahalcrm.bitrix24.ru'.($deal_info['UF_CRM_1593292405']['downloadUrl']);


$main_client = $contact_info['NAME'].' '.$contact_info['SECOND_NAME'].' '.
    $contact_info['LAST_NAME'].', +7(996)590-99-40,'.'документ : '.
    $contact_info['UF_CRM_1579553095988'].$contact_info['UF_CRM_1579553128782'].
    $contact_info['UF_CRM_1579553168703'].$contact_info['UF_CRM_1579359356307']
    .'номер а/м '.$contact_info['UF_CRM_1579359371606'];

$second_client = $company_info['UF_CRM_1605534590'].'документ : '.$company_info['UF_CRM_1580747316022'];

$third_client = $company_info['UF_CRM_1605534652'].'документ : '.$company_info['UF_CRM_1580747328323'];

$inner_number = $company_info['UF_CRM_1594794891'];

$company_name = $idRetailer[$company_info['UF_CRM_1580400783014']];

$company_title = $company_info['TITLE'];

$deal_stage = $deal_info['STAGE_ID'];
if ($deal_stage == "C12:PREPAYMENT_INVOICE"){$interval = '(доплата за '.$company_info['UF_CRM_1614603075'].'кг в период '.$company_info['UF_CRM_1619766058'].')';
											 
}

$attorney =  explode('T',$contact_info['UF_CRM_1580401296502'])[0];
$attorney = new DateTime("$attorney");
$attorney = date_format($attorney, 'd-m-Y');

$date = date('d-m-Y');



$html = <<<EOD
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        .body{
            font-family: times_new_roman;
            text-align: center;
        }
        .first_line{
            font-family: times_new_roman;
            font-size: 7px;
        }
        .second_line{
            font-family: times_new_roman;
            font-size: 9px;
        }
        .third_line{
            font-family: times_new_roman;
            text-align: left;
            font-size: 7px;
        }
        .forth_line{
            font-family: times_new_roman;
            font-size: 10px;
        }
        .fifth_line{
            font-family: times_new_roman;
            font-size: 7px;
        }

    </style>
    <title>Document</title>
</head>
<body class="body">
<table  width="790"  border="0.5">
    <tr height="10">
        <td rowspan="2" width="160">Квитанция</td>
        <td><span class="first_line">ПАО СБЕРБАНК &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
   &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Форма №ПД-4</span></td></tr>
    <tr>
        <td>Индивидуальный предприниматель Сидоров Максим Александрович</td>
    </tr>
    <tr>
        <td rowspan="15">
            <span>&nbsp; &nbsp; &nbsp;</span>
            <img src="$qr_url" width="140" height="140" alt="alt" class="qr">
        </td>
        <td><span class="second_line">(наименование получателя платежа)</span></td>
    </tr>
    <tr>
        <td><span class="forth_line">ИНН 110901212063 КПП 000000000 &nbsp; &nbsp; &nbsp; 40802810528000011871</span></td>
    </tr>
    <tr>
        <td><span class="second_line">(инн получателя платежа)&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
  &nbsp; &nbsp; &nbsp;(номер счёта получателя платежа)</span></td>
    </tr>
    <tr>
        <td><span>БИК 048702640 (КОМИ ОТДЕЛЕНИЕ N8617 ПАО СБЕРБАНК)</span></td>
    </tr>
    <tr>
        <td><span class="second_line">(наименование банка получателя платежа)</span></td>
    </tr>
    <tr>
        <td>Назначение: $inner_number $company_title $interval</td>
    </tr>
    <tr>
        <td><span class="second_line">(назначение платежа)</span></td>
    </tr>
    <tr>
        <td>Сумма: $opportunity 00 коп.</td>
    </tr>
    <tr>
        <td>
            <span class="second_line">(сумма платежа)</span><br>
            <span class="fifth_line">С условиями приёма указанной в платёжном документе суммы, в т.ч. с суммой взимаемой платы за услуги</span>
        </td>
    </tr>
    <tr>
        <td><span class="fifth_line">банка, ознакомлен и согласен.
         &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Подпись плательщика &nbsp; &nbsp; &nbsp; &nbsp; \</span></td>
    </tr>
    <tr>
        <td rowspan="5">****************** ВНИМАНИЕ *******************<br>
При оплате через терминал или приложение банка в <br>
1) Лицевой счет / Договор укажите $inner_number <br>
2) ФИО плательщика укажите Фамилию Имя Отчество человека который оплачивает квитанцию <br>
3) Адрес укажите город в котором находится магазина и его адрес. <br>
4) $qr_url

</td>
    </tr>
    <tr>
    </tr>
    <tr>
    </tr>
    <tr>
    </tr>
    <tr>
    </tr>
</table>
</html>
EOD;

$pdf->writeHTML($html, true, false, true, false, '');

ob_end_clean();
$pdf->Output('my_exapmle.pdf', 'I');


// ставим литеру D и оставляем ее так чтобы скачать файл