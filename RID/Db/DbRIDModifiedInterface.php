<?php
	 namespace RID\Db;
	
	interface DbRIDModifiedInterface {
		 public function operation($operation, $params);
		 public function saveRID($params);
	}

?>