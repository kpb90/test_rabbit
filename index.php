<?php
require_once __DIR__ . '/vendor/autoload.php';
/*

var_dump(json_decode(file_get_contents('php://input'), true));
var_dump(json_decode($_REQUEST['form'], true));
var_dump($_FILES);
*/
if ($_REQUEST) {
	switch ($_REQUEST['task']) {
		case 'saveRID':
			$form = json_decode($_REQUEST['form']);
			unlink('test_RID/'.$form->staticFields->title.'.txt');
			addToLog($_REQUEST['form'],'test_RID/'.$form->staticFields->title.'.txt');
		break;
		case 'saveTemplateRID':
			$form = json_decode($_REQUEST['form']);
			unlink('template_RID/'.$form->staticFields->title.'.txt');
			addToLog($_REQUEST['form'],'template_RID/'.rand(1,10000).'.txt');
		break;
		case 'getTemplateRID':
			$file = 'template_RID/'.urldecode($_REQUEST['id']).'.txt';
			echo file_get_contents($file);
		break;
		case 'getRID':
			if ($_REQUEST['id']=='all') {
				$files = array ();
				$files2 = array ();
				foreach (scandir('test_RID/') as $item) {
					if ($item == '.' || $item == '..') continue;
					$files[]=str_replace ('.txt','',$item);
				}
				foreach (scandir('template_RID/') as $item) {
					if ($item == '.' || $item == '..') continue;
					$files2[]=str_replace ('.txt','',$item);
				}
				echo json_encode(array('allRID'=>$files, 'allTemplateRID'=>$files2));
			} else {
				$file = 'test_RID/'.urldecode($_REQUEST['id']).'.txt';
				echo file_get_contents($file);
			}
		break;

		default:
			# code...
		break;
	}
	exit;
}

function addToLog($message,$filename = "log.txt") {
		$handle = fopen($filename, "a+");
		fwrite($handle, $message . PHP_EOL);
		fclose($handle);			
	}
echo  '<html>
		<head>
			 <meta charset="utf-8">
			 <meta http-equiv="Cache-Control" content="no-cache">
			 <style>
			 	.glyphicon-remove {
			 		cursor:pointer;
			 	}

			 	.glyphicon-remove:hover {
			 		color:red;
			 	}
			 </style>
		</head>
		<body>
			<script src="/angular/js/angular/angular.min.js"></script>
	        <script src="/angular/js/angular-route/angular-route.js"></script>
	        <script src="/angular/js/angular-resource/angular-resource.js"></script>
        	<script src="/angular/js/jquery/jquery.min.js"></script>
			<link rel="stylesheet" type="text/css" href="/angular/js/bootstrap-3.2.0-dist/css/bootstrap.min.css" />
	        <link rel="stylesheet" type="text/css" href="/angular/js/bootstrap-3.2.0-dist/css/bootstrap-theme.min.css" />
	        <script src="/angular/js/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
	        <script src="/angular/js/ui-bootstrap-tpls-0.11.0.js"></script>


	        <script src="/angular/app/app.js?ver=5"></script>
	        <script src="/angular/app/controllers.js?ver=6"></script>
	        <script src="/angular/app/directives.js?ver=6"></script>
	        <script src="/angular/app/services.js?ver=6"></script>
	        <script src="/angular/app/filters.js?ver=6"></script>
	        

			<script type="text/javascript" src="//cdn.jsdelivr.net/jquery.maskedinput/1.3.1/jquery.maskedinput.min.js"></script>
			<script src="/angular/app/angular-jquery-maskedinput.js"></script>
			<div ng-app="myApp" ng-controller="AppCtrl">
				<ng-include  src="\'/RID_form.html\'"> </ng-include>	
			</div>
		</body>
		</html>
		';


?>
