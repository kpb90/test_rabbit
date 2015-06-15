<?php
	namespace RID\CommunicationData;
	use PhpAmqpLib\Connection\AMQPConnection;
	use PhpAmqpLib\Message\AMQPMessage;

	class SenderDataRabbit extends CommunicationDataConnect implements SenderDataInterface 
	{
		public function __construct($paramConnection=array())
        {
            parent ::__construct($paramConnection);
        }

        public function __destruct() {
            parent ::__destruct();
        }

 		public function connect() {
			$this->connection = new AMQPConnection($this->host, $this->port, $this->login, $this->pswd);
			$this->channel = $this->connection->channel();
			//echo "connect";
 		}

		 public function send ($param) {
		 	$this->channel->queue_declare($param['routingKey'], false, true, false, false);
			$msg = new AMQPMessage($param['msgBody'], array('delivery_mode' => 2) );
			$this->channel->basic_publish($msg, '', $param['routingKey']);
			//echo "send";
		 }
	}
?>
