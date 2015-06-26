<?php
	error_reporting(E_ALL);
	ini_set('display_errors','off');
require_once __DIR__ . '/vendor/autoload.php';

use RID as RID;
use RID\Db;
use RID\Router;
use RID\Logger\Logger;

Logger::$PATH = 'logs/receiver/';
$receiveData = new RID\CommunicationData\ReceiveDataRabbit();
$dbRID = new Db\DbRID;
$dbRID->setConnect(array(
	        "type" => "mysql",
	        "host" => "localhost",
	        "name" => "rabbitmq_test",
	        "user" => "root",
	        "pass" => "root",
	        "options" => array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
	    ));
$receiveData->connect ();
$router = new Router\Router($dbRID, $receiveData);
$receiveData->receive (array ('routingKey'=>'addRID', 'callback' => array($router, 'listen_request')));

?>