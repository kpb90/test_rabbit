<?php
require_once __DIR__ . '/vendor/autoload.php';
use RID\Db;
use RID\Router;
use RID\Logger\Logger;

error_reporting(E_ALL);
ini_set('display_errors','On');
/*

var_dump(json_decode(file_get_contents('php://input'), true));
var_dump(json_decode($_REQUEST['form'], true));
var_dump($_FILES);
*/

 $senderData = new RID\CommunicationData\SenderDataRabbit ();
 $dbRID = new Db\DbRID;
 $router = new Router\Router($dbRID, $senderData);
 $router->listen_request ($_REQUEST);

 echo  '<html>
		<head lang="en">
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
			 <meta http-equiv="Cache-Control" content="no-cache">
			 <style>
			 	.glyphicon-remove {
			 		cursor:pointer;
			 	}

			 	.glyphicon-remove:hover {
			 		color:red;
			 	}
			 </style>
			 		<script src="js/jquery-1.9.1.min.js" type="text/javascript"></script>
			 <link rel="stylesheet" href="js/jquery.fancybox-1.3.4.css" type="text/css" media="all" />
			<script type="text/javascript" src="js/jquery.fancybox-1.3.4.pack.js"></script>
			<script type="text/javascript" src="js/underscore-min.js"></script>
		</head>
		<body>

			<script src="angular/js/angular/angular.min.js"></script>
	        <script src="angular/js/angular-route/angular-route.js"></script>
	        <script src="angular/js/angular-resource/angular-resource.js"></script>
        	<!--<script src="angular/js/jquery/jquery.min.js"></script>-->
			<link rel="stylesheet" type="text/css" href="angular/js/bootstrap-3.2.0-dist/css/bootstrap.min.css" />
	        <link rel="stylesheet" type="text/css" href="angular/js/bootstrap-3.2.0-dist/css/bootstrap-theme.min.css" />
	        <script src="angular/js/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
	        <script src="angular/js/ui-bootstrap-tpls-0.11.0.js"></script>


	        <script src="angular/app/app.js?ver=5"></script>
	        <script src="angular/app/controllers.js?ver=6"></script>
	        <script src="angular/app/directives.js?ver=6"></script>
	        <script src="angular/app/services.js?ver=6"></script>
	        <script src="angular/app/filters.js?ver=6"></script>
	        <script src="angular/app/acl.js?ver=6"></script>
	        <script src="angular/app/dynamic_fields_of_rid.js?ver=6"></script>
	        <script src="angular/app/add_link_for_rid.js?ver=6"></script>
	        

			<script type="text/javascript" src="//cdn.jsdelivr.net/jquery.maskedinput/1.3.1/jquery.maskedinput.min.js"></script>
			<script src="angular/app/angular-jquery-maskedinput.js"></script>
			<div ng-app="myApp" ng-controller="AppCtrl">
				<ng-include  src="\'RID_form.html\'"> </ng-include>
				<!--<a data-fancy href  = "/images/1.png"><img src = "/images/1.png"></a>-->
			</div>
		</body>
		</html>
		';


?>
