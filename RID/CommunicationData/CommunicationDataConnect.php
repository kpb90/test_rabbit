<?php
	namespace RID\CommunicationData;
	use PhpAmqpLib\Connection\AMQPConnection;
	use PhpAmqpLib\Message\AMQPMessage;

	abstract class CommunicationDataConnect
	{
		protected $host;
        protected $port;
        protected $login;
        protected $pswd;
		protected $connection;
        protected $channel;

        public function __construct($paramConnection = array())
        {
            $paramConnection = !$paramConnection ?  array('host'=>'192.168.10.102', 'port'=> 5672, 'login' => 'pk', 'pswd' => '123') : $paramConnection;
            //$paramConnection = !$paramConnection ?  array('host'=>'192.168.0.4', 'port'=> 5672, 'login' => 'kpb', 'pswd' => '123') : $paramConnection;
            $this->host = $paramConnection['host'];
            $this->port = $paramConnection['port'];
            $this->login = $paramConnection['login'];
            $this->pswd = $paramConnection['pswd'];
        }

        public function __destruct() {
            if ($this->channel) {
                $this->channel->close();
            }

            if ($this->connection) {
                $this->connection->close();
            }

            //echo "close";
        }

	}
?>