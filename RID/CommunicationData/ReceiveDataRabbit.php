<?php
    namespace RID\CommunicationData;

    use PhpAmqpLib\Connection\AMQPConnection;
    use PhpAmqpLib\Message\AMQPMessage;
    use RID\Logger\Logger;

    class ReceiveDataRabbit extends CommunicationDataConnect implements ReceiveDataInterface
    {
        private $callback;
        public $numConsumer;

        public function __construct($paramConnection=array())
        {
            parent ::__construct($paramConnection);
        }

        public function __destruct() {
            parent ::__destruct();
        }

        public function processMessage(AMQPMessage $msg)
        {
            $unserialize_msg_body = unserialize(base64_decode($msg->body));
            Logger::getLogger('ReceiveDataRabbit','queues.txt')->log('Получение сообщения из очереди '.print_r($unserialize_msg_body, true));
            $processFlag = call_user_func($this->callback, $unserialize_msg_body, $this->numConsumer);
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        }

        public function connect() {
            $this->connection = new AMQPConnection($this->host, $this->port, $this->login, $this->pswd);
            $this->channel = $this->connection->channel();
           // echo "connect";
        }

        public function receive ($param) {
           $this->callback = $param['callback'];
           $this->channel->queue_declare($param['routingKey'], false, true, false, false);
           echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";
           $this->channel->basic_consume($param['routingKey'], '', false, false, false, false, array($this, 'processMessage'));
           while(count($this->channel->callbacks)) {
                $this->channel->wait();
            }
        }
    }
?>