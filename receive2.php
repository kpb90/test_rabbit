<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;

$GLOBALS['DB_CONNECTION'] = dbconnect_new ();
	
$connection = new AMQPConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();


$channel->queue_declare('hello1', false, true, false, false);

echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

$GLOBALS['argv'] = $argv;
$callback = function($msg) {
  echo " [x] Received ", $msg->body, "\n";
    $date = date("Y-m-d H:i:s");
    $consumer = $GLOBALS['argv'][1] ? $GLOBALS['argv'][1] : 0;
    $data = unserialize(base64_decode($msg->body));
    $publisher = $data['publisher'];
    $message = $data['m'];
    $query = "INSERT INTO `level_2`(`data`, `date`,`publisher`,`consumer`) VALUES ('{$message}','$date','$publisher','$consumer')";
    $GLOBALS['DB_CONNECTION']->query($query);
    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
};

$channel->basic_consume('hello1', '', false, false, false, false, $callback);

while(count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();

function dbconnect_new ()
	{
		$mysql_var = new mysqli("localhost", "root", "root", "rabbitmq_test");
		$mysql_var->set_charset("utf8");
		return $mysql_var;
	}
?>