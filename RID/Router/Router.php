<?php
	namespace RID\Router;
	use RID\Db;
	use RID\Logger\Logger;
 
	class Router
	{
		private $dbRID;
		private $communicator;

		public function __construct ($dbRID, $communicator = array()) {
			$this->dbRID = $dbRID;
            $this->communicator = $communicator;
		}

		public function listen_request ($request) {
			$dbRIDSelect = new Db\DbRIDSelect($this->dbRID);
			 if (method_exists($this->communicator, 'send')===true) {
 				$dbRIDModified = new Db\DbRIDModified($this->dbRID, $this->communicator);
 			} else {
 				$dbRIDModified = new Db\DbRIDDaemonModified($this->dbRID, $this->communicator);
 			}

			if ($request) {
				switch ($request['module']) {
					case 'addRID':
						switch ($request['task']) {
							case 'getInitDataForRID':
								$initDataForRid = $dbRIDSelect->getInitDataForRID();		
								if ($initDataForRid) {
									return json_encode($initDataForRid);
								}
							break;
							case 'saveRID':
			 					$response =  $dbRIDModified->operation ('saveRID', $request);
			 					return $response['response'] ? $response['response']['form']['staticFields']['r_id'] : $response['response'];
							break;
							
							case 'saveTemplateRID':
								return $dbRIDModified->operation ('saveTemplateRID', $request);
							break;
							case 'getTemplateRID':
							break;
							case 'getRID':
								$RID = $dbRIDSelect->getRID();
								return json_encode($RID);
							break;

							default:
								# code...
							break;
						}
					break;
					default:
					break;
				}
			}
		}
	}
?>