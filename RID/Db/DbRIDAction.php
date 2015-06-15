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
	}
?>