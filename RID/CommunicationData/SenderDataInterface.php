<?php
	namespace RID\CommunicationData;

	interface SenderDataInterface 
	{
		 public function connect();
		 public function send($param);
	}
?>