<?php

	include_once "carlsberg_function.php";

	if (!$_SESSION['auto_user']&&strpos($_SERVER['REQUEST_URI'],'/carlsberg/')!==false) {
		header('Location:/carlsberg/');
	}
	$GLOBALS['POST'] = json_decode(file_get_contents('php://input'), true);
	if($GLOBALS['POST']['basket'] && count($GLOBALS['POST']['basket']) > 0) {
		compile_and_send_letter_carlsberg (STATE_FIRST_SEND_LETTER_TO_USER_AND_MANAGER);	
		empty_cart();
		echo '1';
		exit;
	}	
if($_SESSION['auto_user']['manager']) {
	$GLOBALS['temp_ang_script'] = '
        <script src="/angular/js/angular/angular.min.js"></script>
        <script src="/angular/js/angular-route/angular-route.js"></script>
        <script src="/angular/js/angular-resource/angular-resource.js"></script>
		<script src="/js/jquery.maskedinput.js"></script>
        <script src="/angular/app/app.js?ver=5"></script>
        <script src="/angular/app/controllers.js?ver=6"></script>
        <script src="/angular/app/directives.js?ver=6"></script>
        <script src="/angular/app/services.js?ver=6"></script>
		<script type="text/javascript" src="//cdn.jsdelivr.net/jquery.maskedinput/1.3.1/jquery.maskedinput.min.js"></script>
		<script src="/angular/app/angular-jquery-maskedinput.js"></script>';
	//ng-controller="AppCtrl"
	$GLOBALS['ZAKAZ_PAGE'][ITEMS] = $GLOBALS['temp_ang_script'].
	'<div ng-app="myApp" ng-controller="AppCtrl">
		<ng-include  src="\'/basket.html\'"> </ng-include>	
	</div>
	';
} else {
	$GLOBALS['ZAKAZ_PAGE'][ITEMS] = "<div>В данный момент вы не можете делать заявку, так как для вас еще не назначен менеджер</div>";
}
	function pscript_execute() 
	{

		if($GLOBALS['ZAKAZ_PAGE']['sec_extra'])

			$GLOBALS['ZAKAZ_PAGE']['sec_extra'] = "<font style='color: red; background-color: yellow; line-height: 30px; padding: 5px;'><b>{$GLOBALS['ZAKAZ_PAGE']['sec_extra']}</b></font>";



		$ret_str = '
	<div>' . $GLOBALS['ZAKAZ_PAGE']['page_db_content'] . '</div>' . 
	$GLOBALS['ZAKAZ_PAGE'][ITEMS] . $GLOBALS['ZAKAZ_PAGE']['sec_extra'] . '
		';



		return	'
		<div  id="component_wrap" class="page_item">

				<div>

					<div id="component" class="clear">

						<div id="WWMainPage">

							<div class="category_description">


							</div>

							<div class="category-view">

								<div class="row">

									'. $ret_str .'

								</div>

							</div>

						</div>

					</div>

				</div>

			 </div>

		';


	}



?>