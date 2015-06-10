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

        public function __construct($paramConnection)
        {
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

            echo "close";
        }

	}
?>