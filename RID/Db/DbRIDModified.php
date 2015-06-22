<?php
    namespace RID\Db;
    use RID\Logger\Logger;
    use RID\FileUpload\FileUpload;
    class DbRIDModified extends DbRIDAction implements DbRIDModifiedInterface
    {
        protected $communicator;
        public function __construct ($db, $communicator = array()) {
            $this->communicator = $communicator;
            parent ::__construct($db);
        }

        protected function saveTemplateRID ($params) {
            
        }

        public function saveRID ($params) {
            $params = unserialize($params);
            $form = json_decode($params['form'], true);
            $modifiedForm = json_decode($params['modifiedForm'], true);
            if (!$form['staticFields']['r_id']) {
                $queryRID = "INSERT INTO `RID`(`title`, `short_descr`, `idACL`) VALUES (:title,:short_descr,:idACL)";
                $paramsRID = array (':title' => $form['staticFields']['title'], ':short_descr' =>  $form['staticFields']['short_descr'], ':idACL'=>$form['staticFields']['selectCommonSecurity']);
                $typesRID = array (':title' => \PDO::PARAM_STR, ':short_descr' => \PDO::PARAM_STR, ':idACL' => \PDO::PARAM_INT);
                $this->db->query ($queryRID, $paramsRID, $typesRID);
                $idRID = $form['staticFields']['r_id'] = $this->db->handler()->lastInsertId();
                $modifiedForm = $this->applyChangeToDynamicField($idRID, $modifiedForm);
            } else {
                $idRID = $form['staticFields']['r_id'];
                $paramsRID = array (':title' => $form['staticFields']['title'], ':short_descr' =>  $form['staticFields']['short_descr'], ':idACL'=>$form['staticFields']['selectCommonSecurity'],':id'=>$idRID);
                $typesRID = array (':title' => \PDO::PARAM_STR, ':short_descr' => \PDO::PARAM_STR, ':idACL' => \PDO::PARAM_INT, ':id' => \PDO::PARAM_INT);
                $queryRID = "UPDATE `RID` SET `title`=:title,`short_descr`=:short_descr,`idACL`=:idACL WHERE id=:id";
                $this->db->query ($queryRID, $paramsRID, $typesRID);
                $modifiedForm = $this->applyChangeToDynamicField($idRID, $modifiedForm);
            }
            return array ('module' => $params['module'], 'task'=> $params['task'], 'form'=>$form, 'modifiedForm' => $modifiedForm);
        }

        protected function applyChangeToDynamicFieldSelectBranchUpdate ($idRID, $data) {
            $query = "UPDATE `Branch_RID` SET `idRID`=:idRID,`idBranch`=:idBranch WHERE  `id`=:id";
            $types = array (':id' => \PDO::PARAM_INT, ':idRID' => \PDO::PARAM_INT, ':idBranch' => \PDO::PARAM_INT);
            foreach ($data as &$item)  {
                if (!$item) {
                    continue;
                }
                $params = array (':id' => $item['id'], ':idRID' => $idRID, ':idBranch' => $item['value'][0]['value']);
                $this->db->query ($query, $params , $types);
            } 
            return $data;
        }

        protected function applyChangeToDynamicFieldSelectBranchAdd ($idRID, $data) {
            $query = "INSERT INTO `Branch_RID`(`idRID`, `idBranch`) VALUES (:idRID,:idBranch)";
            $types = array (':idRID' => \PDO::PARAM_INT, ':idBranch' => \PDO::PARAM_INT);
            foreach ($data as &$item)  {
                if (!$item) {
                    continue;
                }
                 if (array_key_exists('value', $item)==true) {
                    $sch = 0;
                    foreach ($item['value'] as &$v) {
                        if (!$v['value']) {
                           $v['value'] = null;
                        }
                        $params = array (':idRID' => $idRID, ':idBranch' => $v['value']);
                        $this->db->query ($query, $params , $types);
                        $item['id'] = $this->db->handler()->lastInsertId();
                    }

                }
            } 
            return $data;
        }

        protected function applyChangeToDynamicFieldSelectionInheritableRemove ($idRID, $data) {
            $query = "DELETE FROM `inheritableRID` WHERE id = :id";
            $types = array (':id' => \PDO::PARAM_INT);
            foreach ($data as $item)  {
                if (!$item) {
                    continue;
                }
                $params = array (':id' => $item['id']);
                $this->db->query ($query, $params , $types);
            } 
            return $data;
        }

        protected function applyChangeToDynamicFieldSelectionInheritableAdd ($idRID, $data) {
            $query = "INSERT INTO `inheritableRID`(`idRID`, `idInheritableRID`) VALUES (:idRID,:idInheritableRID)";
            $types = array (':idRID' => \PDO::PARAM_INT, ':idInheritableRID' => \PDO::PARAM_INT);
            foreach ($data as &$item)  {
                if (!$item) {
                    continue;
                }
                $params = array (':idRID' => $idRID, ':idInheritableRID' => $item['idLinkRid']);
                $this->db->query ($query, $params , $types);
                $item['id'] = $this->db->handler()->lastInsertId();
            } 
            return $data;
        }

        protected function applyChangeToDynamicFieldSelectionRelatedRemove ($idRID, $data) {
            $query = "DELETE FROM `RelativeRID` WHERE id = :id";
            $types = array (':id' => \PDO::PARAM_INT);
            foreach ($data as $item)  {
                if (!$item) {
                    continue;
                }
                $params = array (':id' => $item['id']);
                $this->db->query ($query, $params , $types);
            } 
            return $data;
        }

        protected function applyChangeToDynamicFieldSelectionRelatedAdd ($idRID, $data) {
            $query = "INSERT INTO `RelativeRID`(`idRID`, `idRelativeRID`) VALUES (:idRID,:idRelativeRID)";
            $types = array (':idRID' => \PDO::PARAM_INT, ':idRelativeRID' => \PDO::PARAM_INT);
            foreach ($data as &$item)  {
                if (!$item) {
                    continue;
                }
                $params = array (':idRID' => $idRID, ':idRelativeRID' => $item['idLinkRid']);
                $this->db->query ($query, $params , $types);
                $item['id'] = $this->db->handler()->lastInsertId();
            } 
            return $data;
        }

        protected function applyChangeToDynamicFieldAddFieldRemove ($idRID, $data) {
            $queryFieldRIDDelete = "DELETE FROM `FieldRID` WHERE id = :id";
            $typesFieldRIDDelete = array (':id' => \PDO::PARAM_INT);
            foreach ($data as $item)  {
                if (!$item) {
                    continue;
                }
                $paramsFieldRIDDelete = array (':id' => $item['id']);
                $this->db->query ($queryFieldRIDDelete, $paramsFieldRIDDelete , $typesFieldRIDDelete);
            } 
            return $data;
        }

        protected function applyChangeToDynamicFieldAddFieldAdd ($idRID, $data) {
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
            foreach ($data as &$item)  {
                if (!$item) {
                    continue;
                }
                $linkTitleFieldRIDUnitsField = false;
                // тип поля: строка, файл, текст
                $idTypeFieldRID = is_array($item['selectTypeOfField'])===true ? $idTypeFieldRID = $item['selectTypeOfField']['id'] : 0;
                
                // единицы измерения:см, кг
                $idUnits = null;
                if (is_array($item['unitsOfField'])===true&&$item['unitsOfField']['u_title']) {
                    if (array_key_exists ('new_record', $item['unitsOfField'])&&$item['unitsOfField']['new_record']) {
                        $paramsUnits = array (':title' => $item['unitsOfField']['u_title']);
                        $this->db->query ($queryUnits, $paramsUnits, $typesUnits);
                        $linkTitleFieldRIDUnitsField = true;
 						$item['unitsOfField']['u_id'] = $idUnits = $this->db->handler()->lastInsertId();
                    } else {
                        $idUnits = $item['unitsOfField']['u_id'];
                    }
                }

                $idTypeValueFieldRID = $item['viewOfField'] ? $item['viewOfField'] : null;

                // Название поля: вязкость, вес
                $idTitleFieldRID = null;
                if (is_array($item['nameOfField'])===true) {
                     if (array_key_exists ('new_record', $item['nameOfField'])&&$item['nameOfField']['new_record']) {
                        $paramsTitleFieldRID = array (':title' => $item['nameOfField']['title']);
                        $this->db->query ($queryTitleFieldRID, $paramsTitleFieldRID, $typesTitleFieldRID);
						$item['nameOfField']['id'] = $idTitleFieldRID = $this->db->handler()->lastInsertId();
                     } else {
                        $idTitleFieldRID =  $item['nameOfField']['id'];
                     }
                }

                // создаем новую связь название поля -> единицы измерения
                if ($linkTitleFieldRIDUnitsField===true) {
                    $paramsTitleFieldRID_Units = array (':idTitleFieldRID' => $idTitleFieldRID,':idUnits'=>$idUnits);
                    $this->db->query ($queryTitleFieldRID_Units, $paramsTitleFieldRID_Units, $typesTitleFieldRID_Units);
                }

                $idACL = $item['idACL'];
                $paramsFieldRID = array (':idTypeFieldRID' => $idTypeFieldRID, ':idUnits' => $idUnits, ':idTypeValueFieldRID' => $idTypeValueFieldRID, ':idTitleFieldRID' => $idTitleFieldRID, ':idACL' => $idACL, ':idRID' => $idRID);
                $this->db->query ($queryFieldRID, $paramsFieldRID, $typesFieldRID);
                $item['id'] = $idFieldRID = $this->db->handler()->lastInsertId();
                if (array_key_exists('value', $item)==true) {
                    $sch = 0;
                    foreach ($item['value'] as &$v) {
                        $paramsValueFieldRID = array (':idFieldRID' => $idFieldRID, ':value' => $v['value'], ':ord' => ++$sch);
                        $this->db->query ($queryValueFieldRID, $paramsValueFieldRID , $typesValueFieldRID);
                        $v['valueId'] = $this->db->handler()->lastInsertId();
                    }

                }
            }

            return $data;
        }

        protected function applyChangeToDynamicFieldAddFieldUpdate ($idRID, $data) {
            $queryValueFieldRIDUpdate = "UPDATE `ValueFieldRID` SET `value`=:value WHERE id = :id"; 
            $typesValueFieldRIDUpdate = array (':value' => \PDO::PARAM_STR, ':id' => \PDO::PARAM_INT);
            foreach ($data as $item)  {
                if (!$item) {
                    continue;
                }
                foreach ($item['value'] as $key => $value) {
                    $paramsValueFieldRIDUpdate = array (':value' => $value['value'], ':id' => $value['valueId']);
                    $this->db->query ($queryValueFieldRIDUpdate, $paramsValueFieldRIDUpdate , $typesValueFieldRIDUpdate);
                }
            }  
            return $data; 
        }

        protected function applyChangeToDynamicFieldUsersRemove ($idRID, $data) {
            $queryUser_RIDRemove = "DELETE FROM `User_RID` WHERE id=:id"; 
            $typesUser_RIDRemove = array (':id' => \PDO::PARAM_INT);
            foreach ($data as $item)  {
                if (!$item) {
                    continue;
                }
                $paramsUser_RIDRemove = array (':id' => $item['id']);
                $this->db->query ($queryUser_RIDRemove, $paramsUser_RIDRemove, $typesUser_RIDRemove);
            }   

            return $data;
        }

        protected function applyChangeToDynamicFieldUsersAdd ($idRID, $data) {
            $queryUser_RIDAdd = "INSERT INTO `User_RID`(`emailUser`, `idRID`, `idACL`) VALUES (:emailUser,:idRID,:idACL)"; 
            $typesUser_RIDAdd = array (':emailUser' => \PDO::PARAM_STR, ':idRID' => \PDO::PARAM_INT, ':idACL' => \PDO::PARAM_INT);
            foreach ($data as &$item)  {
                if (!$item) {
                    continue;
                }
                $paramsUser_RIDAdd = array (':emailUser' => $item['email'], ':idRID' => $idRID, ':idACL' => $item['idACL']);
                $this->db->query ($queryUser_RIDAdd, $paramsUser_RIDAdd , $typesUser_RIDAdd);
                $item['id'] = $this->db->handler()->lastInsertId();
            } 
            return $data;  
        }

        protected function fltrs_secret_params ($params) {
            $prevSelectCommonSecurity = $params['form']['staticFields']['prevSelectCommonSecurity'];
            $selectCommonSecurity = $params['form']['staticFields']['selectCommonSecurity']; 
            $modifiedForm = array ();
            if ($selectCommonSecurity == 5 && $selectCommonSecurity != $prevSelectCommonSecurity) {
                return array ('module'=> 'addRID', 'task' => 'removeRID', 'idRID' => $params['form']['staticFields']['r_id']);
            } else if ($prevSelectCommonSecurity == 5 && $selectCommonSecurity != $prevSelectCommonSecurity){
                //$params['modifiedForm'] = array ();
                foreach ($params['form']['dynamicFields'] as $dynamicFieldsK => $dynamicFieldsV) {
                    if (is_array($params['modifiedForm'][$dynamicFieldsK]['add'])&&count($params['modifiedForm'][$dynamicFieldsK]['add'])) {
                        foreach ($params['modifiedForm'][$dynamicFieldsK]['add'] as $k => $v) {
                            $dynamicFieldsV[$k]['id'] = $v['id'];
                        }
                    }
                    foreach ($dynamicFieldsV as $dynamicFieldsV_K => $dynamicFieldsV_V) {
                        if (array_key_exists('idACL', $dynamicFieldsV_V)&&$dynamicFieldsV_V['idACL']==5) {
                            unset ($dynamicFieldsV[$dynamicFieldsV_K]);
                        }
                    }
                    $modifiedForm[$dynamicFieldsK] = array ('add' => $dynamicFieldsV);
                }
                $params['modifiedForm'] = $modifiedForm;
                $params['form']['staticFields']['new_record'] = true;
                //return array ('module' => $params['module'], 'task'=> $params['task'], 'form'=>$form, 'modifiedForm' => $modifiedForm);
            } else {
                foreach ($params['modifiedForm'] as $titleTypeOfConcreteField=>&$concreteTypeOfField ) {
                    foreach ($concreteTypeOfField as $operation => &$concreteTypeOfFieldData) {
                        if ($operation == 'add' || $operation == 'update') {
                            foreach ($concreteTypeOfFieldData as $key => &$item) {
                                if (!$item) {
                                    continue;
                                }
                                if (array_key_exists('idACL', $item)&&$item['idACL']==5) {
                                    $concreteTypeOfField['remove'][] = array ('id'=>$item['id']);
                                    unset ($concreteTypeOfFieldData[$key]);
                                }
                            }
                        }
                    }
                }
            }
            return $params;
        }
    }
?>