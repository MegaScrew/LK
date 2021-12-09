<?php
ob_start();
require_once('tcpdf_include.php');
require_once('bitrix24.php');
require_once('regions_constants.php');


$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

$pdf->AddPage();
$pdf->Image('./images/bull.png',25,8,60,'','jpeg');
$pdf->SetTextColor(0,0,0);


global $idRetailer;
//$info = new Bitrix24('87292');
$info = new Bitrix24($_REQUEST['id']);

$company_info = $info->get_company_info_by_deal_id();
$contact_info = $info->get_contact_info_by_deal_id();

$main_client = $contact_info['NAME'].' '.$contact_info['SECOND_NAME'].' '.
    $contact_info['LAST_NAME'].', +7(996)590-99-40,'.'документ : '.
    $contact_info['UF_CRM_1579553095988'].$contact_info['UF_CRM_1579553128782'].
    $contact_info['UF_CRM_1579553168703'].$contact_info['UF_CRM_1579359356307']
    .'номер а/м '.$contact_info['UF_CRM_1579359371606'];

$second_client = $company_info['UF_CRM_1605534590'].'документ : '.$company_info['UF_CRM_1580747316022'];

$third_client = $company_info['UF_CRM_1605534652'].'документ : '.$company_info['UF_CRM_1580747328323'];

$company_name = $idRetailer[$company_info['UF_CRM_1580400783014']];

$company_title = $company_info['TITLE'];

$attorney =  explode('T',$contact_info['UF_CRM_1580401296502'])[0];
$attorney = new DateTime("$attorney");
$attorney = date_format($attorney, 'd.m.Y');

$date = date('d.m.Y');


$html = <<<EOD
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        .body{
            font-family: times_new_roman;
            text-align: center;
            font-size: 10px;
        }
        .top_header{
            font-family: Arial;
            text-align: center;
            font-size: 20px;
            text-shadow: 1px 1px 1px grey;
        }
    </style>
    <title>Document</title>
</head>
<body class="body">
<span class="top_header">ИП ГКФХ Пономарев И.А.</span><br><br>
Свидетельство о гос. регистрации: 11№002133560 от 15.04.2011<br>
ОГРН 311110110500020, ИНН 110503829315<br>
167031, Республика Коми, г.Сыктывкар, ул.Карла Маркса 172-95<br>
Тел./факс 8904-103-37-43, e-mail: akoroteev7@gmail.com<br>
</div>
<br><br><br>
</body>
</html>
EOD;

$pdf->writeHTMLCell(0, 0, 90, 25, $html, 0, 1, 0, true, '', true);

$html = <<<EOD
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        .body{
            font-family: times_new_roman;
        }
        .rahal_header{
            font-family: times_b_I;
            font-size: 20px;
        }
        .contact {
            text-align: right;
        }

        .date {
            text-align: left;
        }

        .header {
            text-align: center;
            font-family: times_b;
        }

        .bold {
            font-family: times_b;
        }

        .bold_underline {
            font-family: times_b;
            text-decoration: underline;
        }

        .pound {
            display: flex;
            justify-content: space-between;
        }

        .print {
            position: absolute;
            width: 156px;
            left: 300px;
        }

    </style>
    <title>Document</title>
</head>
<body class="body">
<div class="date">$date</div>
<div class="header">Доверенность</div>
<div class="standart">
&nbsp; &nbsp; &nbsp; &nbsp;Глава Крестьянского Фермерского хозяйства Пономарев Игорь Александрович, (ОГРН 311110110500020)   настоящей доверенностью уполномочивает:<br><br>
$main_client<br>
$second_client<br>
$third_client<br><br>
&nbsp; &nbsp; &nbsp; &nbsp;Адреса магазинов: <br><br>
<span class="bold">$company_name в $company_title</span><br><br>
&nbsp; &nbsp; &nbsp; &nbsp;Доверенность выдана сроком до: <span class="bold_underline">$attorney</span><br><br><br>
&nbsp; &nbsp; &nbsp; &nbsp;Подпись получившего доверенность удостоверяем<br><br><br><br><br><br><br><br>
Глава КФХ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
   &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Пономарев И.А.
</div>

</body>
</html>
EOD;

$pdf->writeHTML($html, true, false, true, false, '');

ob_end_clean();
$pdf->Output('my_exapmle.pdf', 'I');


// ставим литеру D и оставляем ее так чтобы скачать файл