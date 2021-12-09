<?php
    session_start();
    if ($_COOKIE['login'] == ""){
        if ($_SESSION['login'] == ""){
            header("Location: https://smaip.ru/lk/login.php");
        }
    }
    require 'functions.php';
    if (isset($_POST['add_certificate'])){
        $shop_id=$_POST['shop_id'];
        $deal_id=$_POST['deal_id'];
        $day_of_invoice=date("d.m.Y",strtotime($_POST['date_dogovor']));
        $razreshenniye_simvoli = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $random_number = substr(str_shuffle($razreshenniye_simvoli), 0, 15);
        $name = $random_number.'.'.pathinfo( $_FILES['pictures']['name'], PATHINFO_EXTENSION );
        $params = array(
            'id' => 1633722,//id папки test
            'data' => array(
                'NAME' => $name,
            ),
            'fileContent' => base64_encode(file_get_contents($_FILES['pictures']['tmp_name']))
        );
        $diskUpload = CRest::call('disk.folder.uploadfile', $params);
        while($diskUpload['error']=="QUERY_LIMIT_EXCEEDED"){
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
                "PROPERTY_224" =>$day_of_invoice,
                "PROPERTY_220" =>'D_'.$deal_id
            )
        );
        $result = CRest::call('lists.element.add', $params);
        while($result['error']=="QUERY_LIMIT_EXCEEDED"){
            $result = CRest::call('lists.element.add', $params);
            if ($result['error']<>"QUERY_LIMIT_EXCEEDED"){break;}
        }
        if ($result['error']==""){
            $message = '<div class="alert alert-primary">Справка о фермерском хозяйстве успешно добавлена!</div>';
        }
        elseif ($result['error']=="ERROR_ELEMENT_FIELD_VALUE"){
            $message = '<div class="alert alert-danger">Ошибка, размер файла превышает 20МБ. Попробуйте загрузить фотографию с меньшим разрешением.</div>';
        }
        else {
            $message = '<div class="alert alert-danger">Ошибка, попробуйте позже или обратитесь к менеджеру и сообщите код ошибки: '.$result['error'].'</div>';
        }
        $params = array('id' => $element_id);
        CRest::call('disk.file.delete', $params);
        $_FILES['pictures']="";
    }
?>
<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1.0, user-scalable=0">
        <title>ЛК | Справка о фермерском хозяйстве</title>
        <link rel="stylesheet" href="assets/materialdesignicons.min.css">
        <link rel="stylesheet" href="assets/jquery.fancybox.css">
        <link rel="stylesheet" href="assets/style2.css">
        <link rel="shortcut icon" href="favicon.ico">
        <style>
            .sidebar .nav .nav-item.active > .nav-link i {
                color: #b66dff !important;
            }
            .shepherd-button {
                background: -webkit-gradient(linear, left top, right top, from(#da8cff), to(#9a55ff)) !important;
                background: linear-gradient(to right, #da8cff, #9a55ff) !important;
                border: 0 !important;
                -webkit-transition: opacity 0.3s ease !important;
                transition: opacity 0.3s ease !important;
                color: white !important;
                text-transform: capitalize !important;
                font-size: 0.875rem !important;
                line-height: 1 !important;
                font-family: "ubuntu-bold", sans-serif !important;
            }
            .rad {
                border-radius:0.32rem;
            }
            .b24-widget-button-position-bottom-right {
                right:3vh !important;
                bottom:3vh !important;
            }
            .page-title-icon {
                flex-shrink: 0;
            }
            .page-header {
                justify-content: flex-start !important;
            }
            .page-body-wrapper {
                height: 100vh;
            }
            .main-panel {
                min-height:100% !important;
                overflow-y: auto;
                overflow-x: hidden;
            }
            .content-wrapper {
                margin-top: 70px;
                margin-left: 260px;
                width: calc(100% - 260px);
                transition: width .25s ease, margin-left .25s ease;
            }
            .sidebar-icon-only .content-wrapper{
                width: calc(100% - 70px);
                margin-left: 70px;
                transition: width .25s ease, margin-left .25s ease;
            }
            .sidebar {
                min-height:calc(100vh - 70px);
                position: fixed;
                margin-top: 70px;
                background:#fff;
                font-family:ubuntu-regular,sans-serif;
                padding:0;
                width:260px;
                z-index:11;
                transition:width .25s ease,background .25s ease;
                -webkit-transition:width .25s ease,background .25s ease;
                -moz-transition:width .25s ease,background .25s ease;
                -ms-transition:width .25s ease,background .25s ease;
            }
            .sidebar .nav {
                overflow:hidden;
                -ms-flex-wrap:nowrap;
                flex-wrap:nowrap;
                -webkit-box-orient:vertical;
                -webkit-box-direction:normal;
                -ms-flex-direction:column;
                flex-direction:column;
                margin-bottom:60px;
            }
            .sidebar .nav .nav-item {
                padding:0 2.25rem;
                -webkit-transition-duration:.25s;
                transition-duration:.25s;
                transition-property:background;
                -webkit-transition-property:background;
            }
            .sidebar .nav .nav-item .nav-link {
                display:-webkit-box;
                display:-ms-flexbox;
                display:flex;
                -webkit-box-align:center;
                -ms-flex-align:center;
                align-items:center;
                white-space:nowrap;
                padding:1.125rem 0 1.125rem 0;
                color:#3e4b5b;
                -webkit-transition-duration:.45s;
                transition-duration:.45s;
                transition-property:color;
                -webkit-transition-property:color;
            }
            .sidebar .nav .nav-item .nav-link i {
                color:inherit
            }
            .sidebar .nav .nav-item .nav-link i.menu-icon {
                font-size:1.125rem;
                line-height:1;
                margin-left:auto;
                margin-right:0;
                color:#bba8bff5;
            }
            .sidebar .nav .nav-item .nav-link i.menu-icon:before {
                vertical-align:middle;
            }
            .sidebar .nav .nav-item .nav-link .menu-title {
                color:inherit;
                display:inline-block;
                font-size:.875rem;
                line-height:1;
                vertical-align:middle;
            }
            .sidebar .nav .nav-item .nav-link .badge {
                margin-right:auto;
                margin-left:1rem;
            }
            .sidebar .nav .nav-item:hover {
                background:#fcfcfc;
            }
            .sidebar .nav .nav-item.nav-profile .nav-link {
                height:auto;
                line-height:1;
                border-top:0;
                padding:1.25rem 0;
            }
            .sidebar .nav .nav-item.nav-profile .nav-link .nav-profile-image {
                width:44px;
                height:44px;
            }
            .sidebar .nav .nav-item.nav-profile .nav-link .nav-profile-image img {
                width:44px;
                height:44px;
                border-radius:100%;
            }
            .sidebar .nav .nav-item.nav-profile .nav-link .nav-profile-text {
                margin-left:1rem;
            }
            .sidebar .nav .nav-item.nav-profile .nav-link .nav-profile-badge {
                font-size:1.125rem;
                margin-left:auto;
            }
            .sidebar .nav .nav-item.sidebar-actions {
                margin-top:1rem;
            }
            .sidebar .nav .nav-item.sidebar-actions .nav-link {
                border-top:0;
                display:block;
                height:auto;
            }
            .sidebar .nav .nav-item.sidebar-actions:hover {
                background:initial;
            }
            .sidebar .nav .nav-item.sidebar-actions:hover .nav-link {
                color:initial;
            }
            .sidebar .nav:not(.sub-menu)>.nav-item:hover:not(.nav-category):not(.nav-profile)>.nav-link {
                color:#29323d;
            }
            @media screen and (max-width:991px){
                .sidebar-offcanvas {
                    position:fixed;
                    max-height:calc(100vh - 70px);
                    top:70px;
                    bottom:0;
                    overflow:auto;
                    right:-260px;
                    -webkit-transition:all .25s ease-out;
                    transition:all .25s ease-out;
                    margin-top: 0;
                }
                .content-wrapper {
                    margin-top: 70px;
                    margin-left: 0;
                    width: 100%;
                }
            }
        </style>
    </head>
	<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
	<script src="https://cdn.jsdelivr.net/jquery.bootstrapvalidator/0.5.3/js/bootstrapValidator.min.js"></script>
	<body>
		<div class="container-scroller">
			<?php
			require 'nav2.php';
			require 'sidebar.php';?>
			<div class="main-panel">
				<?php echo '
				<div class="content-wrapper">
					<div class="page-header">
						<span class="page-title-icon bg-gradient-primary text-white mr-2">
							<i class="mdi mdi-file-document-box"></i>
						</span>
						<h3 class="page-title">Справка о фермерском хозяйстве по магазину '.$_POST['shop_name'].'</h3>
					</div>';
					Manager();
					echo $message;
					echo '<div class="row">';
					if (isset($_POST['shop_id'])){
						echo '<div class="col-12">
								<div class="card">
									<div class="card-body">';
						if ($_POST['deal_id']<>""){
							$deal_id=$_POST['deal_id'];
							$paramsv = array(
								"IBLOCK_TYPE_ID" => 'lists',
								"IBLOCK_ID" => 28,
								"FILTER"=> array(
									"PROPERTY_220" => "D_".$deal_id,
									"PROPERTY_222" =>160//3310
								)
							);
							$check_certificate = CRest::call('lists.element.get', $paramsv);
							while($check_certificate['error']=="QUERY_LIMIT_EXCEEDED"){
								$check_certificate = CRest::call('lists.element.get', $paramsv);
								if ($check_certificate['error']<>"QUERY_LIMIT_EXCEEDED"){break;}
							}
							echo '<div class="col-12 mt-3">
							<button class="btn btn-gradient-primary rad" type="button" data-toggle="modal" data-target="#certificateModal"><i class="mdi mdi-folder"></i> Загрузить справку о ФХ</button>
                            							<div class="modal fade" id="certificateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            							  <div class="modal-dialog" role="document">
                            								<div class="modal-content">
                            								<form method="post" action="#" enctype="multipart/form-data"  id="form_f">
                            								  <div class="modal-header">
                            									<h5 class="modal-title" id="exampleModalLabel">Справка о наличии фермерского хозяйства - '.$_POST['shop_name'].'</h5>
                            									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            									  <span aria-hidden="true">&times;</span>
                            									</button>
                            								  </div>
                            								  <div class="modal-body" style="background:#d2cbd4">
                            										<input hidden name="shop_id" value="'.$_POST['shop_id'].'">
                            										<input hidden name="deal_id" value="'.$_POST['deal_id'].'">
                            										<input hidden name="shop_name" value="'.$_POST['shop_name'].'">
                            										<div class="form-group">
                            											<label for="date_dogovor">Дата документа</label>
                            											<input required type="date" name="date_dogovor" id="date_dogovor" class="form-control mt-1">
                            										</div>
                            										<input type="hidden" name="MAX_FILE_SIZE" value="20971520" />
                            										<input type="hidden" name="id" value="0">

                            										<div class="form-group">
                            										  <label for="pictures" class="form-label">Документ</label>
                            										  <input required name="pictures" class="form-control mt-1" type="file" id="pictures">
                            										</div>
                            									</div>
                            								  <div class="modal-footer">
                            									<button type="submit" class="btn btn-primary" name="add_certificate" id="pc2" disabled >Добавить</button>
                            								  </div>
                            								  </form>
                            								  <div class="d-flex justify-content-center"><div class="spinner-border" role="status" id="loader2"></div></div>
                            					  <script>
                            					  	$(function() {$("#loader2").hide();});
                            						$( "#pc2" ).click(function() {$("#loader2").fadeIn();$("#form_f").fadeOut();});

                            						$("#form_f").bootstrapValidator({fields: {pictures: {validators: {notEmpty: {}}},date_dogovor: {validators: {notEmpty: {}}}}});


                                $("#form_f").on("status.field.bv", function(e, data) {
                                    formIsValid = true;
                                    $(".form-group",$(this)).each( function() {
                                        formIsValid = formIsValid && $(this).hasClass("has-success");
                                    });

                                    if(formIsValid) {
                                        $("#pc2", $(this)).attr("disabled", false);
                                    } else {
                                        $("#pc2", $(this)).attr("disabled", true);
                                    }
                                });
                            					  </script>


                            								</div>
                            							  </div>
                            							</div>

							<h4>Прикреплённые Справки о ФХ</h4><div class="card"><div class="card-body">';
                            							for ($i=0;$i<$check_certificate['total'];$i++){
                            								$new_list=array_values($check_certificate['result'][$i]['PROPERTY_868']);
                            								echo '<img class="w-50" src="'.$new_list[0].'">';
                            							}
                            							echo '</div></div></div>';

						}
						else {echo 'Нет активных сделок';}
							}
					else {
						echo '<div class="col-12">
								<div class="card">
									<div class="card-body">Магазин не выбран - <a href="https://smaip.ru/lk/p.php?p=main">На главную</a></div>
								</div>
							</div>';
					}
				echo '</div></div>';
				?>
				<footer class="footer">
					<div class="container-fluid clearfix">
						<span class="d-block text-center text-sm-left d-sm-inline-block"><a href="p.php?p=main">На главную</a></span>
						<span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"> ©2021 | <a href="https://smaip.ru" target="_blank">ИП Сидоров М.А.</a></span>
					</div>
				</footer>
			</div>
		</div>
		<script src="assets/vendor.bundle.base.js"></script>
		<script src="assets/main.js"></script>
		<script src="assets/jquery.fancybox.js"></script>
	</body>
</html>