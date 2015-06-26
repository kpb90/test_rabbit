<?php
	namespace RID\Db;
	use RID\Logger\Logger;
	abstract class DbRIDAction
	{
		protected $db;
		public function __construct ($db) {
			$this->db = $db;
			$this->db->handler();
		}

		protected function applyChangeToDynamicField ($idRID, $modifiedForm) {
            foreach ($modifiedForm as $titleTypeOfConcreteField=>&$concreteTypeOfField ) {
                foreach ($concreteTypeOfField as $operation => &$concreteTypeOfFieldData) {
                    $method = 'applyChangeToDynamicField'.ucfirst($titleTypeOfConcreteField).ucfirst($operation);
                    if (method_exists($this, $method)!==false) {
                        if (is_array($concreteTypeOfFieldData)) {
                            $concreteTypeOfFieldData = $this->$method($idRID, $concreteTypeOfFieldData);
                        }
                    }
                }
            }
            return $modifiedForm;
        }

        public function operation ($operation, $params) {
                switch ($operation) {
                    case 'saveRID':
                         $response = $this->db->transaction(array($this, 'saveRID'),$params);
                    break;

                    case 'removeRID':
                        $response = $this->db->transaction(array($this, 'removeRID'),$params);
                     //   $this->saveTemplateRID($params);
                    break;
                    
                    default:
                    break;
                }
                
                if (method_exists($this->communicator, 'send')===true) {
                    $this->communicator->connect();
                    $public_params = $this->fltrs_secret_params($response['response']);
                    if ($public_params) {
                         Logger::getLogger('DbRIDModified','queues.txt')->log('Отправка сообщения в очередь: '.print_r($public_params, true));
                         Logger::getLogger('DbRIDModified','queues.txt')->log('Отправка сообщения в очередь: '.base64_encode(serialize($public_params)));
                         $this->communicator->send(array('msgBody' => base64_encode(serialize($public_params)), 'routingKey' => 'addRID'));  
                    }
                } 
                return $response;
        }
    }
?>