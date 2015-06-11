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

	    public function fetchGroupByParam ($sql, $params) {
	    	$r = $this->handler()->query($sql, \PDO::FETCH_ASSOC);
	    	$result = array ();
	    	foreach ($r as $row) {
	    		$value = isset($params['value']) ? $row[$params['value']]: $row;
	    		if (isset($params['type'])&&$params['type']=='array') {
					$result[$row[$params['index']]][] = $value;
	    		} else {
	    			$result[$row[$params['index']]] = $value;
	    		}
		    }
	        return $result;
	    }

	    public function getDataGroupByRID ($sql, $params) {
	    	$result = array ('dynamicFields'=>array(	
				'addField' => array(),
				'selectionInheritable' => array (),
				'selectionRelated' => array (),
				'users' => array (),
			),
			'staticFields'=>array());
	    	$r = $this->handler()->query($sql, \PDO::FETCH_ASSOC);

	    	// отсчет нужен с 0 для js
	    	$dynamicFieldsId = array();
	    	$sch = 0;
	    	foreach ($r as $row) {
	    		$nameOfField = array('id'=>$row['tfr_id'],'title'=> $row['tfr_title']);
	    		$selectTypeOfField = array('id'=>$row['tfr_id'],'key'=>$row['type_fr_key'],'title'=> $row['type_fr_title']);
	    		$unitsOfField = array ('tfru_title'=>$row['tfr_title'], 'u_id'=>$row['u_id'], 'u_title'=>$row['u_title']);
	    		if ($row['fr_id']) {
	    			if (array_key_exists($row['fr_id'],$dynamicFieldsId)==false) {
	    				$dynamicFieldsId[$row['fr_id']]=$sch++;
	    			}
	    			$result['dynamicFields']['addField'][$dynamicFieldsId[$row['fr_id']]] = array(
	    										'id' => $row['fr_id'],
	    										'nameOfField'=>$nameOfField, 
	    										'security'=> $row['fr_idACL'], 
	    										'selectTypeOfField'=>$selectTypeOfField,
	    										'unitsOfField'=>$unitsOfField,
	    										'value'=>$row['value_fr_value'],
	    										'viewOfField'=>$row['tvfr_id']);		    
	    		}
	    	}
	    	$result['staticFields'] = array ('r_id'=>$row['r_id'],'title'=>$row['r_title'], 'short_descr'=>$row['r_short_descr'],'selectCommonSecurity'=>$row['r_idACL']);
	        return $result;	
	    }
	}
?>