<?php
	namespace RID\Db;
    use RID\Logger\Logger;
	class DbRIDModified extends DbRIDAction
	{
        private $communicator;
		public function __construct ($db, $communicator = array()) {
            $this->communicator = $communicator;
			parent ::__construct($db);
		}

		private function saveRID ($params) {
			$form = json_decode($params['form'], true);
			$staticData = $form['staticFields'];
			$modifiedForm = json_decode($params['modifiedForm']);
            $idRID = $staticData['r_id'];
			if (isset ($staticData['new_record'])&&$staticData['new_record']) {
                $query = "INSERT INTO `RID`(`id`,`title`, `short_descr`, `idACL`) VALUES (:id, :title,:short_descr,:idACL)";
                $params = array (':id'=>$idRID,':title' => $staticData['title'], ':short_descr' =>  $staticData['short_descr'], ':idACL'=>$staticData['selectCommonSecurity']);
                $types = array (':id' => \PDO::PARAM_STR,':title' => \PDO::PARAM_STR, ':short_descr' => \PDO::PARAM_STR, ':idACL' => \PDO::PARAM_INT);
                $this->db->query ($query, $params, $types);
                $this->applyChangeToDynamicField($idRID, $modifiedForm);
			} else {
                $params = array (':title' => $staticData['title'], ':short_descr' =>  $staticData['short_descr'], ':idACL'=>$staticData['selectCommonSecurity'],':id'=>$idRID);
                $types = array (':title' => \PDO::PARAM_STR, ':short_descr' => \PDO::PARAM_STR, ':idACL' => \PDO::PARAM_INT, ':id' => \PDO::PARAM_INT);
                $query = "UPDATE `RID` SET `title`=:title,`short_descr`=:short_descr,`idACL`=:idACL WHERE id=:id";
                $this->db->query ($query, $params, $types);
                $this->applyChangeToDynamicField($idRID, $modifiedForm);
			}
		}

		private function applyChangeToDynamicField ($idRID, $modifiedForm) {
            $modifiedForm = (array) $modifiedForm;
            //print_r($modifiedForm);
            //exit;
              foreach ($modifiedForm as $titleTypeOfConcreteField=>$concreteTypeOfField ) {
                foreach ($concreteTypeOfField as $operation => $concreteTypeOfFieldData) {
                    $method = 'applyChangeToDynamicField'.ucfirst($titleTypeOfConcreteField).ucfirst($operation);
                    if (method_exists($this, $method)!==false) {
                        $this->$method($idRID, $concreteTypeOfFieldData);
                    }
                }
            }
		}

        private function applyChangeToDynamicFieldAddFieldRemove ($idRID, $data) {
            $queryFieldRIDDelete = "DELETE FROM `FieldRID` WHERE id = :id";
            $typesFieldRIDDelete = array (':id' => \PDO::PARAM_STR);
            foreach ($data as $item)  {
                if (!$item) {
                    continue;
                }
                $paramsFieldRIDDelete = array (':id' => $item->id);
                $this->db->query ($queryFieldRIDDelete, $paramsFieldRIDDelete , $typesFieldRIDDelete);
            } 
        }

        private function applyChangeToDynamicFieldAddFieldAdd ($idRID, $data) {
            $queryFieldRID = "INSERT INTO `FieldRID` (`idTypeFieldRID`, `idUnits`, `idTypeValueFieldRID`, `idTitleFieldRID`, `idACL`, `idRID`) 
                               VALUES 
                              (:idTypeFieldRID, :idUnits, :idTypeValueFieldRID, :idTitleFieldRID, :idACL, :idRID)";
            $typesFieldRID = array (':idTypeFieldRID' => \PDO::PARAM_INT, ':idUnits' => \PDO::PARAM_INT, ':idTypeValueFieldRID' => \PDO::PARAM_INT, ':idTitleFieldRID' => \PDO::PARAM_INT, ':idACL' => \PDO::PARAM_INT, ':idRID' => \PDO::PARAM_INT);

            $queryValueFieldRID = "INSERT INTO `ValueFieldRID` (`idFieldRID`, `value`, `ord`) 
                                    VALUES 
                                    (:idFieldRID, :value, :ord)";
            $typesValueFieldRID = array (':idFieldRID' => \PDO::PARAM_INT, ':value' => \PDO::PARAM_STR, ':ord' => \PDO::PARAM_INT);

            $queryUnits = "INSERT INTO `Units`(`title`) VALUES (:title)";
            $typesUnits = array (':title' => \PDO::PARAM_STR);
            
            $queryTitleFieldRID = "INSERT INTO `TitleFieldRID`(`title`) VALUES (:title)"; 
            $typesTitleFieldRID = array (':title' => \PDO::PARAM_STR);

            $queryTitleFieldRID_Units = "INSERT INTO `TitleFieldRID_Units`(`idTitleFieldRID`, `idUnits`) VALUES (:idTitleFieldRID,:idUnits)"; 
            $typesTitleFieldRID_Units = array (':idTitleFieldRID' => \PDO::PARAM_INT, ':idUnits' => \PDO::PARAM_INT);

            // для типа файл или текст: idTypeValueFieldRID = null (значение, диапазон значений), idUnits = null
            foreach ($data as $item)  {
                if (!$item) {
                    continue;
                }
                $linkTitleFieldRIDUnitsField = false;
                // тип поля: строка, файл, текст
                $idTypeFieldRID = is_object($item->selectTypeOfField)===true ? $idTypeFieldRID = $item->selectTypeOfField->id : 0;
                // единицы измерения:см, кг
                if (is_object($item->unitsOfField)===true) {
                    $idUnits = $item->unitsOfField->u_id;
                } else {
                    if ($item->unitsOfField) {
                       $paramsUnits = array (':title' => $item->unitsOfField);
                       $this->db->query ($queryUnits, $paramsUnits, $typesUnits);
                       $idUnits =$this->db->handler()->lastInsertId();
                       $linkTitleFieldRIDUnitsField = true;
                    } else {
                        $idUnits = null;
                    }
                }
                
                // Название поля: вязкость, вес
                $idTypeValueFieldRID = $item->viewOfField ? $item->viewOfField : null;
                 if (is_object($item->nameOfField)===true) {
                    $idTitleFieldRID = $item->nameOfField->id;
                } else {
                    $paramsTitleFieldRID = array (':title' => $item->nameOfField);
                    $this->db->query ($queryTitleFieldRID, $paramsTitleFieldRID, $typesTitleFieldRID);
                    $idTitleFieldRID =$this->db->handler()->lastInsertId();
                }
                // создаем новую связь название поля -> единицы измерения
                if ($linkTitleFieldRIDUnitsField===true) {
                    $paramsTitleFieldRID_Units = array (':idTitleFieldRID' => $idTitleFieldRID,':idUnits'=>$idUnits);
                    $this->db->query ($queryTitleFieldRID_Units, $paramsTitleFieldRID_Units, $typesTitleFieldRID_Units);
                }

                $idACL = $item->security;
                $paramsFieldRID = array (':idTypeFieldRID' => $idTypeFieldRID, ':idUnits' => $idUnits, ':idTypeValueFieldRID' => $idTypeValueFieldRID, ':idTitleFieldRID' => $idTitleFieldRID, ':idACL' => $idACL, ':idRID' => $idRID);
                $this->db->query ($queryFieldRID, $paramsFieldRID, $typesFieldRID);
                $idFieldRID = $this->db->handler()->lastInsertId();
                if (property_exists($item, 'value')==true) {
                    if (is_object($item->value)===false) {
                        $paramsValueFieldRID = array (':idFieldRID' => $idFieldRID, ':value' => $item->value, ':ord' => 1);
                        $this->db->query ($queryValueFieldRID, $paramsValueFieldRID , $typesValueFieldRID);
                    } else {
                        $sch = 0;
                        foreach ($item->value as $v) {
                            $paramsValueFieldRID = array (':idFieldRID' => $idFieldRID, ':value' => $v->value, ':ord' => ++$sch);
                            $this->db->query ($queryValueFieldRID, $paramsValueFieldRID , $typesValueFieldRID);
                        }
                    }
                }
            }
        }

        private function applyChangeToDynamicFieldAddFieldUpdate ($idRID, $data) {
            $queryValueFieldRIDUpdate = "UPDATE `ValueFieldRID` SET `value`=:value WHERE id = :id"; 
            $typesValueFieldRIDUpdate = array (':value' => \PDO::PARAM_STR, ':id' => \PDO::PARAM_INT);
            foreach ($data as $item)  {
                if (!$item) {
                    continue;
                }
                foreach ($item->value as $key => $value) {
                    $paramsValueFieldRIDUpdate = array (':value' => $value->value, ':id' => $value->valueId);
                    $this->db->query ($queryValueFieldRIDUpdate, $paramsValueFieldRIDUpdate , $typesValueFieldRIDUpdate);
                }
            }   
        }

        private function applyChangeToDynamicFieldUsersRemove ($idRID, $data) {
            $queryUser_RIDRemove = "DELETE FROM `User_RID` WHERE id=:id"; 
            $typesUser_RIDRemove = array (':id' => \PDO::PARAM_INT);
            foreach ($data as $item)  {
                if (!$item) {
                    continue;
                }
                $paramsUser_RIDRemove = array (':id' => $item->user_rid_id);
                $this->db->query ($queryUser_RIDRemove, $paramsUser_RIDRemove, $typesUser_RIDRemove);
            }   
        }

        private function applyChangeToDynamicFieldUsersAdd ($idRID, $data) {
            $queryUser_RIDAdd = "INSERT INTO `User_RID`(`emailUser`, `idRID`, `idACL`) VALUES (:emailUser,:idRID,:idACL)"; 
            $typesUser_RIDAdd = array (':emailUser' => \PDO::PARAM_STR, ':idRID' => \PDO::PARAM_INT, ':idACL' => \PDO::PARAM_INT);
            foreach ($data as $item)  {
                if (!$item) {
                    continue;
                }
                $paramsUser_RIDAdd = array (':emailUser' => $item->email, ':idRID' => $idRID, ':idACL' => $item->idACL);
                $this->db->query ($queryUser_RIDAdd, $paramsUser_RIDAdd , $typesUser_RIDAdd);
            }   
        }

		public function operation ($operation, $params) {
				switch ($operation) {
					case 'saveRID':
						$this->saveRID($params);
					break;
					default:
					break;
				}
                
                if (method_exists($this->communicator, 'send')===true) {
                     $this->communicator->connect();
                     $params = $this->fltrs_secret_params($params);
                     Logger::getLogger('DbRIDModified','DbRIDModified.txt')->log('Отправка сообщения в очередь: '.print_r($params, true));
                     $this->communicator->send(array('msgBody' => base64_encode(serialize($params)), 'routingKey' => 'addRID'));  
                } 
        }

        private function fltrs_secret_params ($params) {
            $form = json_decode($params['form'], true);
            $modifiedForm = json_decode($params['modifiedForm']);

            if ($form['staticFields']['selectCommonSecurity']==5) {
                $form['staticFields']['title'] = "#secret#";
                $form['staticFields']['short_descr'] = "#secret#";
                $params['form'] = json_encode($form);
            }

             $modifiedForm = (array) $modifiedForm;
           // print_r($modifiedForm);
           // exit;
              foreach ($modifiedForm as $titleTypeOfConcreteField=>$concreteTypeOfField ) {
                foreach ($concreteTypeOfField as $concreteTypeOfFieldData) {
                    foreach ($concreteTypeOfFieldData as $key => $value) {
                        # code...
                    }
                }
            }
            $modifiedForm = json_encode((object)$modifiedForm);
            $params['modifiedForm'] = $modifiedForm;
            return $params;
        }
	}
?>