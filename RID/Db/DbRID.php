<?php
	namespace RID\Db;
	class DbRID extends Db
	{
		public function fetchGroupKeyVal($sql, $params)
	    {
	    	$r = $this->handler()->query($sql);
	    	$result = array ();
	    	foreach ($r as $row) {
		        $result[$row[$params['index']]] = array ($row[$params['key']]=>$row[$params['value']]);
		    }
	        return $result;
	    }
	}
?>