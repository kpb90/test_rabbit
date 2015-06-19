<?php
    namespace RID\Db;
    use RID\Logger\Logger;
    use RID\FileUpload\FileUpload;
    class DbRIDDaemonModified extends DbRIDAction implements DbRIDModifiedInterface
    {
        protected $communicator;
        protected $idPublisher;
        public function __construct ($db, $communicator = array()) {
            $this->communicator = $communicator;
            $this->idPublisher = "4230923";
            parent ::__construct($db);
        }

        protected function getIdsPrivate ($data) {
            $idsPrivate = array ();
            foreach ($data as $item)  {
                if (!$item) {
                    continue;
                }
                $idsPrivate[] = $item->id;
            }    
            return  $idsPrivate;     
        }

        protected function applyChangeToDynamicFieldRemove ($idRID, $data, $table) {
            $idsPrivate = $this->getIdsPrivate ($data);
            $ids = array ();
            if (count($idsPrivate)) {
                $ids = $this->db->fetchAllInArray ("SELECT id FROM  `{$table}`", 'idPrivate',  $idsPrivate, ' and idPublisher = ?', $this->idPublisher);
                if (count($ids)) {
                    $queryFieldRIDDelete = "DELETE FROM `{$table}` WHERE id = :id";
                    $typesFieldRIDDelete = array (':id' => \PDO::PARAM_INT);
                    foreach ($ids as $id) {
                        $paramsFieldRIDDelete = array (':id' => $id);
                        $this->db->query ($queryFieldRIDDelete, $paramsFieldRIDDelete,  $typesFieldRIDDelete);
                    }
                }
            }
            Logger::getLogger('DbRIDDaemon','remove.txt')->log("{$table} ids:".print_r($ids, true));
        }

        public function saveRID ($params) {
            $params = unserialize($params);
            //Logger::getLogger('tra','tra.txt')->log(print_r($params, true));
            $form = $params['form'];
            $modifiedForm = $params['modifiedForm'];
            $idRidPrivate = $form['staticFields']['r_id'];
            if (isset ($form['staticFields']['new_record'])&&$form['staticFields']['new_record']) {
                $query = "INSERT INTO `RID`(`idPrivate`, `idPublisher`, `title`, `short_descr`, `idACL`) VALUES (:idPrivate, :idPublisher, :title, :short_descr, :idACL)";
                $params = array (':idPrivate' => $idRidPrivate, ':idPublisher' => $this->idPublisher, ':title' => $form['staticFields']['title'], ':short_descr' =>  $form['staticFields']['short_descr'], ':idACL'=>$form['staticFields']['selectCommonSecurity']);
                $types = array (':idPrivate' => \PDO::PARAM_INT, ':idPublisher' => \PDO::PARAM_INT, ':title' => \PDO::PARAM_STR, ':short_descr' => \PDO::PARAM_STR, ':idACL' => \PDO::PARAM_INT);
                $this->db->query ($query, $params, $types);
                $idRID = $form['staticFields']['r_id'] = $this->db->handler()->lastInsertId();
                $modifiedForm = $this->applyChangeToDynamicField($idRID, $modifiedForm);
            } else {
                $params = array (':title' => $form['staticFields']['title'], ':short_descr' =>  $form['staticFields']['short_descr'], ':idACL'=>$form['staticFields']['selectCommonSecurity'],':idPrivate' => $idRidPrivate, ':idPublisher' => $this->idPublisher);
                $types = array (':title' => \PDO::PARAM_STR, ':short_descr' => \PDO::PARAM_STR, ':idACL' => \PDO::PARAM_INT, ':idPrivate' => \PDO::PARAM_INT, ':idPublisher' => \PDO::PARAM_INT);
                $query = "UPDATE `RID` SET `title`=:title,`short_descr`=:short_descr,`idACL`=:idACL WHERE idPrivate=:idPrivate and idPublisher = :idPublisher";
                $this->db->query ($query, $params, $types);

                $idRID = $this->db->fetchOne ("SELECT id FROM RID WHERE idPrivate=:idPrivate and idPublisher = :idPublisher", array (':idPrivate'=>$idRidPrivate,':idPublisher'=>$this->idPublisher));
                $modifiedForm = $this->applyChangeToDynamicField($idRID, $modifiedForm);
            }
            return array ('form'=>$form, 'modifiedForm' => $modifiedForm);
        }

        protected function applyChangeToDynamicFieldSelectBranchUpdate ($idRID, $data) {
            $query = "UPDATE `Branch_RID` SET `idRID`=:idRID,`idBranch`=:idBranch WHERE idPrivate=:idPrivate and idPublisher = :idPublisher";
            $types = array (':idPrivate' => \PDO::PARAM_INT, ':idPublisher' => \PDO::PARAM_INT, ':idRID' => \PDO::PARAM_INT, ':idBranch' => \PDO::PARAM_INT);
            foreach ($data as &$item)  {
                if (!$item) {
                    continue;
                }
                $params = array (':idPrivate' => $item->id, ':idPublisher' => $this->idPublisher, ':idRID' => $idRID, ':idBranch' => $item->value[0]->value);
                $this->db->query ($query, $params , $types);
            } 
            return $data;
        }

        protected function applyChangeToDynamicFieldSelectBranchAdd ($idRID, $data) {
            $query = "INSERT INTO `Branch_RID`(`idRID`, `idBranch`,`idPrivate`, `idPublisher`) VALUES (:idRID,:idBranch, :idPrivate, :idPublisher)";
            $types = array (':idRID' => \PDO::PARAM_INT, ':idBranch' => \PDO::PARAM_INT, ':idPrivate' => \PDO::PARAM_INT, ':idPublisher' => \PDO::PARAM_INT);
            foreach ($data as &$item)  {
                if (!$item) {
                    continue;
                }
                 if (property_exists($item, 'value')==true) {
                    $sch = 0;
                    foreach ($item->value as &$v) {
                        if (!$v->value) {
                           $v->value = null;
                        }
                        $params = array (':idRID' => $idRID, ':idBranch' => $v->value, ':idPrivate' => $v->id, ':idPublisher' => $this->idPublisher);
                        $this->db->query ($query, $params , $types);
                        $v->id = $this->db->handler()->lastInsertId();
                    }

                }
            } 
            return $data;
        }
       
        protected function applyChangeToDynamicFieldSelectionInheritableRemove ($idRID, $data) {
           $this->applyChangeToDynamicFieldRemove($idRID, $data, 'inheritableRID');
        }

        protected function applyChangeToDynamicFieldSelectionInheritableAdd ($idRID, $data) {
            $query = "INSERT INTO `inheritableRID`(`idRID`, `idInheritableRID`,`idPrivate`, `idPublisher`) VALUES (:idRID, :idInheritableRID, :idPrivate, :idPublisher)";
            $types = array (':idRID' => \PDO::PARAM_INT, ':idInheritableRID' => \PDO::PARAM_INT, ':idPrivate' => \PDO::PARAM_INT, ':idPublisher' => \PDO::PARAM_INT);
            foreach ($data as &$item)  {
                if (!$item) {
                    continue;
                }
                $idLinkRid = $this->db->fetchOne ("SELECT id FROM RID WHERE idPrivate=:idPrivate and idPublisher = :idPublisher", array (':idPrivate'=>$item->idLinkRid,':idPublisher'=>$this->idPublisher));
                $params = array (':idRID' => $idRID, ':idInheritableRID' => $idLinkRid, ':idPrivate' => $item->id, ':idPublisher' => $this->idPublisher);
                $this->db->query ($query, $params , $types);
            } 
            return $data;
        }

        protected function applyChangeToDynamicFieldSelectionRelatedRemove ($idRID, $data) {
            $this->applyChangeToDynamicFieldRemove($idRID, $data, 'RelativeRID');
        }

        protected function applyChangeToDynamicFieldSelectionRelatedAdd ($idRID, $data) {
            $query = "INSERT INTO `RelativeRID`(`idRID`, `idRelativeRID`,`idPrivate`, `idPublisher`) VALUES (:idRID, :idRelativeRID, :idPrivate, :idPublisher)";
            $types = array (':idRID' => \PDO::PARAM_INT, ':idRelativeRID' => \PDO::PARAM_INT, ':idPrivate' => \PDO::PARAM_INT, ':idPublisher' => \PDO::PARAM_INT);
            foreach ($data as &$item)  {
                if (!$item) {
                    continue;
                }
                $idLinkRid = $this->db->fetchOne ("SELECT id FROM RID WHERE idPrivate=:idPrivate and idPublisher = :idPublisher", array (':idPrivate'=>$item->idLinkRid,':idPublisher'=>$this->idPublisher));
                $params = array (':idRID' => $idRID, ':idRelativeRID' =>$idLinkRid, ':idPrivate' => $item->id, ':idPublisher' => $this->idPublisher);
                $this->db->query ($query, $params , $types);
            } 
            return $data;
        }

        protected function applyChangeToDynamicFieldAddFieldRemove ($idRID, $data) {
            $this->applyChangeToDynamicFieldRemove($idRID, $data, 'FieldRID');
        }

        protected function applyChangeToDynamicFieldAddFieldAdd ($idRID, $data) {

            $queryFieldRID = "INSERT INTO `FieldRID` (`idPrivate`, `idPublisher`, `idTypeFieldRID`, `idUnits`, `idTypeValueFieldRID`, `idTitleFieldRID`, `idACL`, `idRID`) 
                               VALUES 
                              (:idPrivate, :idPublisher, :idTypeFieldRID, :idUnits, :idTypeValueFieldRID, :idTitleFieldRID, :idACL, :idRID)";
            $typesFieldRID = array (':idPrivate' => \PDO::PARAM_INT, ':idPublisher' => \PDO::PARAM_INT, ':idTypeFieldRID' => \PDO::PARAM_INT, ':idUnits' => \PDO::PARAM_INT, ':idTypeValueFieldRID' => \PDO::PARAM_INT, ':idTitleFieldRID' => \PDO::PARAM_INT, ':idACL' => \PDO::PARAM_INT, ':idRID' => \PDO::PARAM_INT);

            $queryValueFieldRID = "INSERT INTO `ValueFieldRID` (`idPrivate`, `idPublisher`, `idFieldRID`, `value`, `ord`) 
                                    VALUES 
                                    (:idPrivate, :idPublisher, :idFieldRID, :value, :ord)";
            $typesValueFieldRID = array (':idPrivate' => \PDO::PARAM_INT, ':idPublisher' => \PDO::PARAM_INT, ':idFieldRID' => \PDO::PARAM_INT, ':value' => \PDO::PARAM_STR, ':ord' => \PDO::PARAM_INT);

            $queryUnits = "INSERT INTO `Units`(`idPrivate`, `idPublisher`, `title`) VALUES (:idPrivate, :idPublisher, :title)";
            $typesUnits = array (':idPrivate' => \PDO::PARAM_INT, ':idPublisher' => \PDO::PARAM_INT, ':title' => \PDO::PARAM_STR);
            
            $queryTitleFieldRID = "INSERT INTO `TitleFieldRID`(`idPrivate`, `idPublisher`, `title`) VALUES (:idPrivate, :idPublisher, :title)"; 
            $typesTitleFieldRID = array (':idPrivate' => \PDO::PARAM_INT, ':idPublisher' => \PDO::PARAM_INT, ':title' => \PDO::PARAM_STR);

            $queryTitleFieldRID_Units = "INSERT INTO `TitleFieldRID_Units`(`idPublisher`, `idTitleFieldRID`, `idUnits`) VALUES (:idPublisher, :idTitleFieldRID,:idUnits)"; 
            $typesTitleFieldRID_Units = array (':idPublisher' => \PDO::PARAM_INT, ':idTitleFieldRID' => \PDO::PARAM_INT, ':idUnits' => \PDO::PARAM_INT);

            // для типа файл или текст: idTypeValueFieldRID = null (значение, диапазон значений), idUnits = null
            foreach ($data as &$item)  {
                if (!$item) {
                    continue;
                }
                $linkTitleFieldRIDUnitsField = false;
                // тип поля: строка, файл, текст
                $idTypeFieldRID = is_object($item->selectTypeOfField)===true ? $idTypeFieldRID = $item->selectTypeOfField->id : 0;
                
                // единицы измерения:см, кг
                $idUnitsPrivate = $item->unitsOfField->u_id;
                if (is_object($item->unitsOfField)===true) {
                    if (property_exists ($item->unitsOfField,'new_record')&&$item->unitsOfField->new_record) {
                        $paramsUnits = array (':idPrivate' => $idUnitsPrivate, ':idPublisher' => $this->idPublisher, ':title' => $item->unitsOfField->u_title);
                        $this->db->query ($queryUnits, $paramsUnits, $typesUnits);
                        $linkTitleFieldRIDUnitsField = true;
 						$idUnits = $this->db->handler()->lastInsertId();
                    } else {
                        $idUnits = $this->db->fetchOne ("SELECT id FROM Units WHERE idPrivate=:idPrivate and idPublisher = :idPublisher", array (':idPrivate'=>$idUnitsPrivate,':idPublisher'=>$this->idPublisher));
                    }
                } else {
                    $idUnits = null;
                }

                $idTypeValueFieldRID = $item->viewOfField ? $item->viewOfField : null;

                // Название поля: вязкость, вес
                $idTitleFieldRIDPrivate = $item->nameOfField->id;
                if (is_object($item->nameOfField)===true) {
                     if (property_exists ($item->nameOfField,'new_record')&&$item->nameOfField->new_record) {
                        $paramsTitleFieldRID = array (':idPrivate' => $idTitleFieldRIDPrivate, ':idPublisher' => $this->idPublisher,':title' => $item->nameOfField->title);
                        $this->db->query ($queryTitleFieldRID, $paramsTitleFieldRID, $typesTitleFieldRID);
						$idTitleFieldRID = $this->db->handler()->lastInsertId();
                     } else {
                        $idTitleFieldRID = $this->db->fetchOne ("SELECT id FROM TitleFieldRID WHERE idPrivate=:idPrivate and idPublisher = :idPublisher", array (':idPrivate'=>$idTitleFieldRIDPrivate,':idPublisher'=>$this->idPublisher));
                     }
                } else {
                    $idTitleFieldRID = null;
                }

                // создаем новую связь название поля -> единицы измерения
                if ($linkTitleFieldRIDUnitsField===true) {
                    $paramsTitleFieldRID_Units = array (':idPublisher' => $this->idPublisher,':idTitleFieldRID' => $idTitleFieldRID,':idUnits'=>$idUnits);
                    $this->db->query ($queryTitleFieldRID_Units, $paramsTitleFieldRID_Units, $typesTitleFieldRID_Units);
                }

                $idACL = $item->idACL;
                $idFieldRIDPrivate = $item->id;
                $paramsFieldRID = array (':idPrivate' => $idFieldRIDPrivate, ':idPublisher' => $this->idPublisher,':idTypeFieldRID' => $idTypeFieldRID, ':idUnits' => $idUnits, ':idTypeValueFieldRID' => $idTypeValueFieldRID, ':idTitleFieldRID' => $idTitleFieldRID, ':idACL' => $idACL, ':idRID' => $idRID);
                $this->db->query ($queryFieldRID, $paramsFieldRID, $typesFieldRID);
                $idFieldRID = $this->db->handler()->lastInsertId();
                if (property_exists($item, 'value')==true) {
                    $sch = 0;
                    foreach ($item->value as &$v) {
                        $idValuePrivate = $v->valueId;
                        $paramsValueFieldRID = array (':idPrivate' => $idValuePrivate, ':idPublisher' => $this->idPublisher, ':idFieldRID' => $idFieldRID, ':value' => $v->value, ':ord' => ++$sch);
                        $this->db->query ($queryValueFieldRID, $paramsValueFieldRID , $typesValueFieldRID);
                    }
                }
            }
            return $data;
        }

        protected function applyChangeToDynamicFieldAddFieldUpdate ($idRID, $data) {
            $queryValueFieldRIDUpdate = "UPDATE `ValueFieldRID` SET `value`=:value WHERE idPrivate = :idPrivate and idPublisher = :idPublisher"; 
            $typesValueFieldRIDUpdate = array (':value' => \PDO::PARAM_STR, ':idPrivate' => \PDO::PARAM_INT, ':idPublisher' => \PDO::PARAM_INT);
            foreach ($data as $item)  {
                if (!$item) {
                    continue;
                }
                foreach ($item->value as $key => $value) {
                    $paramsValueFieldRIDUpdate = array (':value' => $value->value, ':idPrivate' => $value->valueId, ':idPublisher' => $this->idPublisher);
                    $this->db->query ($queryValueFieldRIDUpdate, $paramsValueFieldRIDUpdate , $typesValueFieldRIDUpdate);
                }
            }   
            return $data;
        }

        protected function applyChangeToDynamicFieldUsersRemove ($idRID, $data) {
            $this->applyChangeToDynamicFieldRemove($idRID, $data, 'User_RID');
        }

        protected function applyChangeToDynamicFieldUsersAdd ($idRID, $data) {
            $queryUser_RIDAdd = "INSERT INTO `User_RID`(`idPrivate`, `idPublisher`,`emailUser`, `idRID`, `idACL`) VALUES (:idPrivate, :idPublisher, :emailUser,:idRID,:idACL)"; 
            $typesUser_RIDAdd = array (':idPrivate' => \PDO::PARAM_INT, ':idPublisher' => \PDO::PARAM_INT,':emailUser' => \PDO::PARAM_STR, ':idRID' => \PDO::PARAM_INT, ':idACL' => \PDO::PARAM_INT);
            foreach ($data as &$item)  {
                if (!$item) {
                    continue;
                }
                $paramsUser_RIDAdd = array (':idPrivate' => $item->id, ':idPublisher' => $this->idPublisher, ':emailUser' => $item->email, ':idRID' => $idRID, ':idACL' => $item->idACL);
                $this->db->query ($queryUser_RIDAdd, $paramsUser_RIDAdd , $typesUser_RIDAdd);
            } 
            return $data;  
        }
    }
?>