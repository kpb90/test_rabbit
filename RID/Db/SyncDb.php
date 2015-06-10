<?php
	namespace RID\Db;
	class SyncDb
	{
		public function connect () {

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