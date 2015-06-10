<?php
	namespace RID\CommunicationData;
	
	interface ReceiveDataInterface {
		 public function connect();
		 public function receive($param);
	}

?>