<?php
	namespace RID\Db;
    use RID\Logger\Logger;
    use RID\FileUpload\FileUpload;
	class DbRIDModified extends DbRIDAction
	{
        private $communicator;
		public function __construct ($db, $communicator = array()) {
            $this->communicator = $communicator;
			parent ::__construct($db);
		}

        private function saveTemplateRID ($params) {
            
        }

		public function saveRID ($params) {
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

        private function applyChangeToDynamicFieldSelectBranchUpdate ($idRID, $data) {
            $query = "UPDATE `Branch_RID` SET `idRID`=:idRID,`idBranch`=:idBranch WHERE  `id`=:id";
            $types = array (':id' => \PDO::PARAM_STR, ':idRID' => \PDO::PARAM_STR, ':idBranch' => \PDO::PARAM_STR);
            foreach ($data as $item)  {
                if (!$item) {
                    continue;
                }
                $params = array (':id' => $item->id, ':idRID' => $idRID, ':idBranch' => $item->value[0]->value);
                $this->db->query ($query, $params , $types);
            } 
        }

        private function applyChangeToDynamicFieldSelectBranchAdd ($idRID, $data) {
            $query = "INSERT INTO `Branch_RID`(`id`, `idRID`, `idBranch`) VALUES (:id,:idRID,:idBranch)";
            $types = array (':id' => \PDO::PARAM_STR, ':idRID' => \PDO::PARAM_STR, ':idBranch' => \PDO::PARAM_STR);
            foreach ($data as $item)  {
                if (!$item) {
                    continue;
                }
                Logger::getLogger('BranchAdd','345.txt')->log(print_r($item, true));
                 if (property_exists($item, 'value')==true) {
                    $sch = 0;
                    foreach ($item->value as $v) {
                        if (!$v->value) {
                           $v->value = null;
                        }
                        $params = array (':id' => $item->id, ':idRID' => $idRID, ':idBranch' => $v->value);
                        $this->db->query ($query, $params , $types);
                    }

                }
            } 
        }

        private function applyChangeToDynamicFieldSelectionInheritableRemove ($idRID, $data) {
            $query = "DELETE FROM `inheritableRID` WHERE id = :id";
            $types = array (':id' => \PDO::PARAM_STR);
            foreach ($data as $item)  {
                if (!$item) {
                    continue;
                }
                $params = array (':id' => $item->id);
                $this->db->query ($query, $params , $types);
            } 
        }

        private function applyChangeToDynamicFieldSelectionInheritableAdd ($idRID, $data) {
           $query = "INSERT INTO `inheritableRID`(`id`, `idRID`, `idInheritableRID`) VALUES (:id,:idRID,:idInheritableRID)";
            $types = array (':id' => \PDO::PARAM_STR, ':idRID' => \PDO::PARAM_STR, ':idInheritableRID' => \PDO::PARAM_STR);
            foreach ($data as $item)  {
                if (!$item) {
                    continue;
                }
                $params = array (':id' => $item->id, ':idRID' => $idRID, ':idInheritableRID' => $item->idLinkRid);
                $this->db->query ($query, $params , $types);
            } 
        }

        private function applyChangeToDynamicFieldSelectionRelatedRemove ($idRID, $data) {
            $query = "DELETE FROM `RelativeRID` WHERE id = :id";
            $types = array (':id' => \PDO::PARAM_STR);
            foreach ($data as $item)  {
                if (!$item) {
                    continue;
                }
                $params = array (':id' => $item->id);
                $this->db->query ($query, $params , $types);
            } 
        }

        private function applyChangeToDynamicFieldSelectionRelatedAdd ($idRID, $data) {
            $query = "INSERT INTO `RelativeRID`(`id`, `idRID`, `idRelativeRID`) VALUES (:id,:idRID,:idRelativeRID)";
            $types = array (':id' => \PDO::PARAM_STR, ':idRID' => \PDO::PARAM_STR, ':idRelativeRID' => \PDO::PARAM_STR);
            foreach ($data as $item)  {
                if (!$item) {
                    continue;
                }
                $params = array (':id' => $item->id, ':idRID' => $idRID, ':idRelativeRID' => $item->idLinkRid);
                $this->db->query ($query, $params , $types);
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
            $queryFieldRID = "INSERT INTO `FieldRID` (`id`,`idTypeFieldRID`, `idUnits`, `idTypeValueFieldRID`, `idTitleFieldRID`, `idACL`, `idRID`) 
                               VALUES 
                              (:id, :idTypeFieldRID, :idUnits, :idTypeValueFieldRID, :idTitleFieldRID, :idACL, :idRID)";
            $typesFieldRID = array (':id'=> \PDO::PARAM_STR,':idTypeFieldRID' => \PDO::PARAM_INT, ':idUnits' => \PDO::PARAM_INT, ':idTypeValueFieldRID' => \PDO::PARAM_INT, ':idTitleFieldRID' => \PDO::PARAM_INT, ':idACL' => \PDO::PARAM_INT, ':idRID' => \PDO::PARAM_INT);

            $queryValueFieldRID = "INSERT INTO `ValueFieldRID` (`id`,`idFieldRID`, `value`, `ord`) 
                                    VALUES 
                                    (:id, :idFieldRID, :value, :ord)";
            $typesValueFieldRID = array (':id'=> \PDO::PARAM_STR, ':idFieldRID' => \PDO::PARAM_STR, ':value' => \PDO::PARAM_STR, ':ord' => \PDO::PARAM_INT);

            $queryUnits = "INSERT INTO `Units`(`id`,`title`) VALUES (:id, :title)";
            $typesUnits = array (':id' => \PDO::PARAM_STR,':title' => \PDO::PARAM_STR);
            
            $queryTitleFieldRID = "INSERT INTO `TitleFieldRID`(`id`,`title`) VALUES (:id,:title)"; 
            $typesTitleFieldRID = array (':id'=>\PDO::PARAM_STR,':title' => \PDO::PARAM_STR);

            $queryTitleFieldRID_Units = "INSERT INTO `TitleFieldRID_Units`(`id`,`idTitleFieldRID`, `idUnits`) VALUES (:id,:idTitleFieldRID,:idUnits)"; 
            $typesTitleFieldRID_Units = array (':id'=> \PDO::PARAM_STR, ':idTitleFieldRID' => \PDO::PARAM_STR, ':idUnits' => \PDO::PARAM_STR);

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
                    if (property_exists ($item->unitsOfField,'new_record')&&$item->unitsOfField->new_record) {
                        $paramsUnits = array (':id'=> $idUnits,':title' => $item->unitsOfField->u_title);
                        $this->db->query ($queryUnits, $paramsUnits, $typesUnits);
                        $linkTitleFieldRIDUnitsField = true;
                    }
                } else {
                    $idUnits = null;
                }

                $idTypeValueFieldRID = $item->viewOfField ? $item->viewOfField : null;

                // Название поля: вязкость, вес
                if (is_object($item->nameOfField)===true) {
                    $idTitleFieldRID = $item->nameOfField->id;
                     if (property_exists ($item->nameOfField,'new_record')&&$item->nameOfField->new_record) {
                        $paramsTitleFieldRID = array (':id'=>$idTitleFieldRID,':title' => $item->nameOfField->title);
                        $this->db->query ($queryTitleFieldRID, $paramsTitleFieldRID, $typesTitleFieldRID);
                     }
                } else {
                    $idTitleFieldRID = null;
                }

                // создаем новую связь название поля -> единицы измерения
                if ($linkTitleFieldRIDUnitsField===true) {
                    $paramsTitleFieldRID_Units = array (':id'=> $this->guid(), ':idTitleFieldRID' => $idTitleFieldRID,':idUnits'=>$idUnits);
                    $this->db->query ($queryTitleFieldRID_Units, $paramsTitleFieldRID_Units, $typesTitleFieldRID_Units);
                }

                $idACL = $item->idACL;
                $idFieldRID = $item->id;
                $paramsFieldRID = array ('id'=> $idFieldRID, ':idTypeFieldRID' => $idTypeFieldRID, ':idUnits' => $idUnits, ':idTypeValueFieldRID' => $idTypeValueFieldRID, ':idTitleFieldRID' => $idTitleFieldRID, ':idACL' => $idACL, ':idRID' => $idRID);
                $this->db->query ($queryFieldRID, $paramsFieldRID, $typesFieldRID);
                
                if (property_exists($item, 'value')==true) {
                    $sch = 0;
                    foreach ($item->value as $v) {
                        $paramsValueFieldRID = array (':id'=> $v->valueId, ':idFieldRID' => $idFieldRID, ':value' => $v->value, ':ord' => ++$sch);
                        $this->db->query ($queryValueFieldRID, $paramsValueFieldRID , $typesValueFieldRID);
                    }

                }
            }
        }

        private function guid()
        {
            if (function_exists('com_create_guid') === true)
            {
                return trim(com_create_guid(), '{}');
            }

            return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
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
            $typesUser_RIDRemove = array (':id' => \PDO::PARAM_STR);
            foreach ($data as $item)  {
                if (!$item) {
                    continue;
                }
                $paramsUser_RIDRemove = array (':id' => $item->id);
                $this->db->query ($queryUser_RIDRemove, $paramsUser_RIDRemove, $typesUser_RIDRemove);
            }   
        }

        private function applyChangeToDynamicFieldUsersAdd ($idRID, $data) {
            $queryUser_RIDAdd = "INSERT INTO `User_RID`(`id`,`emailUser`, `idRID`, `idACL`) VALUES (:id, :emailUser,:idRID,:idACL)"; 
            $typesUser_RIDAdd = array (':id'=>  \PDO::PARAM_STR, ':emailUser' => \PDO::PARAM_STR, ':idRID' => \PDO::PARAM_INT, ':idACL' => \PDO::PARAM_INT);
            foreach ($data as $item)  {
                if (!$item) {
                    continue;
                }
                $paramsUser_RIDAdd = array (':id'=>$item->id,':emailUser' => $item->email, ':idRID' => $idRID, ':idACL' => $item->idACL);
                $this->db->query ($queryUser_RIDAdd, $paramsUser_RIDAdd , $typesUser_RIDAdd);
            }   
        }

		public function operation ($operation, $params) {
				switch ($operation) {
					case 'saveRID':
						  $this->db->transaction(array($this, 'saveRID'),$params);
					break;
                    case 'saveTemplateRID':
                        $this->saveTemplateRID($params);
                    break;
                    
					default:
					break;
				}
                
                if (method_exists($this->communicator, 'send')===true) {
                     $this->communicator->connect();
                     $params = $this->fltrs_secret_params($params);
                     Logger::getLogger('DbRIDModified','queues.txt')->log('Отправка сообщения в очередь: '.print_r($params, true));
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
              foreach ($modifiedForm as $titleTypeOfConcreteField=>&$concreteTypeOfField ) {
                foreach ($concreteTypeOfField as &$concreteTypeOfFieldData) {
                    foreach ($concreteTypeOfFieldData as $key => &$item) {
                        if (!$item) {
                            continue;
                        }
  
                        if (property_exists ($item,'idACL')&&$item->idACL==5) {
                            if (property_exists($item, 'value')==true) {
                                foreach ($item->value as $v) {
                                     $v->value = "#secret#";
                                }

                            }
                        }
                    }
                }
            }
            $modifiedForm = json_encode((object)$modifiedForm);
            $params['modifiedForm'] = $modifiedForm;
            return $params;
        }
	}
?>