<?php
	namespace RID\Db;
	class DbRIDSelect
	{
		private $db;
		public function __construct ($db) {
			$this->db = $db;
			$this->db->handler();
		}

		public function getInitDataForRID () {
			$data = array ('initDataForDynamicFields'=>array(),'initDataForStaticFields'=>array());
			$data['initDataForStaticFields'] = array ('branches'=>array(), 'security'=>array(), 'typeOfField' => array ());
			$data['initDataForStaticFields']['branches'] = $this->db->fetchPairs("SELECT id, title FROM `Branch`");
			$data['initDataForStaticFields']['security'] = $this->db->fetchPairs("SELECT id, title FROM  `ACL` ");
			$data['initDataForStaticFields']['typeOfField'] = $this->db->fetchGroupKeyVal("SELECT `id`, `key`, `title` FROM  `TypeFieldRID` ", array('index'=>'id','key'=>'key','value'=>'title'));
			$data['initDataForDynamicFields']['nameOfField'] = $this->db->fetchPairs("SELECT id, title FROM `TitleFieldRID`");
			$data['initDataForDynamicFields']['viewOfField'] = $this->db->fetchPairs("SELECT `key`, `value` FROM  `TypeValueFieldRID` ");
			$data['initDataForDynamicFields']['unitsOfField'] = $this->db->fetchColumnGroup("SELECT tfr.title, u.title FROM `TitleFieldRID_Units` as tfru inner join `TitleFieldRID` as tfr on tfr.id = tfru.idTitleFieldRID inner join Units as u on u.id = tfru.idUnits");
			return $data;
		}

		public function InsertToDB ($message, $consumer) {
		        $date = date("Y-m-d H:i:s");
			    $data = unserialize(base64_decode($message));
			    $publisher = $data['publisher'];
			    $message = $data['m'];
			    $query = "INSERT INTO `level_2`(`data`, `date`,`publisher`,`consumer`) VALUES ('{$message}','$date','$publisher','$consumer')";
			   // $GLOBALS['DB_CONNECTION']->query($query);
			    var_dump($query);
		}
	}
?>