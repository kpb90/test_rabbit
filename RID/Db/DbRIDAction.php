<?php
	namespace RID\Db;
	abstract class DbRIDAction
	{
		protected $db;
		public function __construct ($db) {
			$this->db = $db;
			$this->db->handler();
		}
	}
?>