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
 			$dbRIDModified = new Db\DbRIDModified($this->dbRID, $this->communicator);

			if ($request) {
				switch ($request['module']) {
					case 'addRID':
						switch ($request['task']) {
							case 'getInitDataForRID':
								$initDataForRid = $dbRIDSelect->getInitDataForRID();		
								if ($initDataForRid) {
									echo json_encode($initDataForRid);
								}
							break;
							case 'saveRID':
			 					$dbRIDModified->operation ('saveRID', $request);
								$form = json_decode($request['form']);
								//unlink('test_RID/'.$form->staticFields->title.'.txt');
								//addToLog($request['form'],'test_RID/'.$form->staticFields->title.'.txt');
							break;
							
							case 'saveTemplateRID':
								$form = json_decode($request['form']);
								//unlink('template_RID/'.$form->staticFields->title.'.txt');
								//addToLog($request['form'],'template_RID/'.rand(1,10000).'.txt');
							break;
							case 'getTemplateRID':
								$file = 'template_RID/'.urldecode($request['id']).'.txt';
								echo file_get_contents($file);
							break;
							case 'getRID':
									$RID = $dbRIDSelect->getRID();
									echo json_encode($RID);
									//$file = 'test_RID/ляляля.txt';
									//echo file_get_contents($file);
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