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
$pdf->Image('./images/print.png',90,180,50,'','png');
$pdf->SetTextColor(0,0,0);

// convert TTF font to TCPDF format and store it on the fonts folder 
//$path_to_ttf = $_SERVER["DOCUMENT_ROOT"] . '/lk/attorney_templates/times-new-roman-italic.ttf';
//$path_to_out = $_SERVER["DOCUMENT_ROOT"] . '/lk/attorney_templates/';
//$fontname = TCPDF_FONTS::addTTFfont ($path_to_ttf , 'TrueTypeUnicode', '', 96, $path_to_out);
//$pdf->SetFont($fontname, '', 14, '', false);
$pdf->SetFont('timesnewroman', '', 12, '', false);


global $idRetailer;
//$info = new Bitrix24('87292');
$info = new Bitrix24($_REQUEST['id']);

$company_info = $info->get_company_info_by_deal_id();
$contact_info = $info->get_contact_info_by_deal_id();

$main_client = $contact_info['NAME'].' '.$contact_info['SECOND_NAME'].' '.
    $contact_info['LAST_NAME'].', +7(996)590-99-40,'.' документ : '.
    $contact_info['UF_CRM_1579553095988'].$contact_info['UF_CRM_1579553128782'].
    $contact_info['UF_CRM_1579553168703'].$contact_info['UF_CRM_1579359356307']
    .'номер а/м '.$contact_info['UF_CRM_1579359371606'];

$second_client = $company_info['UF_CRM_1605534590'].' документ : '.$company_info['UF_CRM_1580747316022'];

$third_client = $company_info['UF_CRM_1605534652'].' документ : '.$company_info['UF_CRM_1580747328323'];

$company_name = $idRetailer[$company_info['UF_CRM_1580400783014']];

$company_title = $company_info['TITLE'];

$company_number = $company_info['UF_CRM_1579359748326'];

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
            font-size: 10pt;
        }
        .rahal_header{
            font-style: italic;
            font-size: 20px;
        }
        .contact {
            text-align: right;
        }

        .date {
            text-align: left;
            font-size: 10pt;
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
<div class="contact">
    <span style="font-weight: bolder; font-size: 20px; ">ООО «Рахал»</span><br>
    <br>
    <br>
    <span style="font-size: 14px; ">
    ИНН/КПП: 1101145068 /110101001 <br>
    ОГРН: 1131101007300 <br>
    ОКПО: 12885223 <br>
    <br>
    Телефон: +7-996-590-99-40 <br>
    Эл. Почта: ooorahal11@gmail.com <br>
    </span>
</div>

<div class="date">$date</div>
<div class="header"><b>Доверенность</b></div>
<br>
<div class="standart">
ООО «Рахал» (ОГРН 1131101007300) настоящей доверенностью уполномочивает:<br><br>
$main_client<br>
<br>
$second_client<br>
<br>
$third_client<br>
<br>
Доверенность на получение продуктов, потерявший потребительский вид, но сохранивших потребительские свойства, в
процессе хозяйственной
деятельность в магазинах<b>
<span class="bold">$company_name в $company_title, номер магазина $company_number</span></b><br><br>
Доверенность выдана сроком до: <span class="bold_underline"> $attorney включительно.</span><br><br><br>
Подпись получившего доверенность удостоверяем___________<br><br><br><br>
Генеральный директор ООО «Рахал» &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Зайцева Н.М.
</div>
<!-- <img src="images/print.png" alt="test alt attribute" width="150" height="160" border="0" /> -->
</body>
</html>
EOD;

$pdf->writeHTML($html, true, false, true, false, '');

ob_end_clean();
$pdf->Output('my_exapmle.pdf', 'I');


// ставим литеру D и оставляем ее так чтобы скачать файл