<?php
	namespace RID\Db;
	use RID\Logger\Logger;
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
			
            $r = $this->fetchAll($sql, array (':id' =>$_REQUEST['id']));

	    	// отсчет нужен с 0 для js
	    	$dynamicFieldsId = $Users = $SelectionInheritable = $SelectionRelated = array();
	    	$schDynamicFields = $schUsers = $schSelectionInheritable = $schSelectionRelated =  0;
	    	foreach ($r as $row) {
	    		$nameOfField = array('id'=>$row['tfr_id'],'title'=> $row['tfr_title']);
	    		$selectTypeOfField = array('id'=>$row['type_fr_id'],'key'=>$row['type_fr_key'],'title'=> $row['type_fr_title']);
	    		$unitsOfField = array ('tfru_title'=>$row['tfr_title'], 'u_id'=>$row['u_id'], 'u_title'=>$row['u_title']);

		 		if ($row['inheritable_rid_id']) {
			 		if (array_key_exists($row['inheritable_rid_id'], $SelectionInheritable) === false) {
						$SelectionInheritable[$row['inheritable_rid_id']]=$schSelectionInheritable++;
					} 

					if (array_key_exists($SelectionInheritable[$row['inheritable_rid_id']], $result['dynamicFields']['selectionInheritable']) === false) {
						$result['dynamicFields']['selectionInheritable'][$SelectionInheritable[$row['inheritable_rid_id']]] = array ('id'=>$row['inheritable_rid_id'], 'idLinkRid'=>$row['idInheritableRID']);
					} 
				}

				if ($row['relative_rid_id']) {
			 		if (array_key_exists($row['relative_rid_id'], $SelectionRelated) === false) {
						$SelectionRelated[$row['relative_rid_id']]=$schSelectionRelated++;
					} 

					if (array_key_exists($SelectionRelated[$row['relative_rid_id']], $result['dynamicFields']['selectionRelated']) === false) {
						$result['dynamicFields']['selectionRelated'][$SelectionRelated[$row['relative_rid_id']]] = array ('id'=>$row['relative_rid_id'], 'idLinkRid'=>$row['idRelativeRID']);
					} 
				}

		 		if ($row['user_rid_emailUser']) {
		 			if (array_key_exists($row['user_rid_emailUser'],$Users)==false) {
	    				$Users[$row['user_rid_emailUser']]=$schUsers++;
	    			}

			 		if (array_key_exists($Users[$row['user_rid_emailUser']], $result['dynamicFields']['users']) === false) {
						$result['dynamicFields']['users'][$Users[$row['user_rid_emailUser']]] = array ('email'=> $row['user_rid_emailUser'], 'idACL' => $row['user_rid_idACL'], 'id'=> $row['user_rid_id']);
					} 
		 		}

	    		if ($row['fr_id']) {
	    			if (array_key_exists($row['fr_id'],$dynamicFieldsId)==false) {
	    				$dynamicFieldsId[$row['fr_id']]=$schDynamicFields++;
	    			}

	    			if (array_key_exists($dynamicFieldsId[$row['fr_id']], $result['dynamicFields']['addField'])===false) {
	   	    			$result['dynamicFields']['addField'][$dynamicFieldsId[$row['fr_id']]] = array(
							'id' => $row['fr_id'],
							'nameOfField'=>$nameOfField, 
							'idACL'=> $row['fr_idACL'], 
							'selectTypeOfField'=>$selectTypeOfField,
							'unitsOfField'=>$unitsOfField,
							'value'=>array(array('valueId'=>$row['value_fr_id'], 'value' =>$row['value_fr_value'])),
							'viewOfField'=>$row['tvfr_id']);		    
	    			} else {
	    				$result['dynamicFields']['addField'][$dynamicFieldsId[$row['fr_id']]]['value'][] = array('valueId'=>$row['value_fr_id'], 'value' =>$row['value_fr_value']);
	    			}
	    		}
	    	} 
	    	$result['dynamicFields']['selectBranch']=array(array('id'=>$row['branch_rid_id'], 'value' => array(array ('value'=>$row['branch_rid_idBranch']))));
	    	$result['staticFields'] = array ('r_id'=>$row['r_id'],'title'=>$row['r_title'], 'short_descr'=>$row['r_short_descr'],'selectCommonSecurity'=>$row['r_idACL']);
	        return $result;	
	    }

	    protected function log($sql, $context = array())
	    {
	        $sql = str_replace('%', '%%', $sql);
	        $sql = preg_replace('/\?/', '"%s"', $sql, sizeof($context));
	        // replace mask by data
	        $sql = vsprintf($sql, $context);
	       //Logger::info("db: " . $sql);
	        Logger::getLogger('DbRid','DbRid.txt')->log($sql."\r\n".($context ? print_r($context, true) : ''));
	    }

	    public function transaction($process, $params = array())
	    {
	        try {
	            $this->handler()->beginTransaction();
	            call_user_func_array($process, $params);
	            $this->handler()->commit();
	            return true;
	        } catch (\PDOException $e) {
	            $this->handler()->rollBack();
	            return false;
	        }
	    }
	}
?>