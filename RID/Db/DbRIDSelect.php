<?php
	namespace RID\Db;
	class DbRIDSelect extends DbRIDAction
	{
		public function __construct ($db) {
			parent ::__construct($db);
		}

		public function getInitDataForRID () {
			$data = array ('initDataForDynamicFields'=>array(),'initDataForStaticFields'=>array(),'allRID'=>array());
			$data['initDataForStaticFields'] = array ('branches'=>array(), 'idACL'=>array(), 'typeOfField' => array ());
			$data['initDataForStaticFields']['branches'] = $this->db->fetchPairs("SELECT id, title FROM `Branch`");
			$data['initDataForStaticFields']['idACL'] = $this->db->fetchPairs("SELECT id, title FROM  `ACL` ");
			$data['initDataForDynamicFields']['typeOfField'] = $this->db->fetchGroupByParam("SELECT `id`, `key`, `title` FROM  `TypeFieldRID` ", array('index'=>'id'));
			$data['initDataForDynamicFields']['nameOfField'] = $this->db->fetchGroupByParam("SELECT id, title, own FROM `TitleFieldRID`", array('index'=>'id'));
			$data['initDataForDynamicFields']['viewOfField'] = $this->db->fetchGroupByParam("SELECT `id`,`key`, `value` FROM  `TypeValueFieldRID`", array('index'=>'id'));
			$data['initDataForDynamicFields']['unitsOfField'] = $this->db->fetchGroupByParam("SELECT tfr.title as tfru_title, u.title as u_title, u.id as u_id, u.own FROM `TitleFieldRID_Units` as tfru inner join `TitleFieldRID` as tfr on tfr.id = tfru.idTitleFieldRID inner join Units as u on u.id = tfru.idUnits", array('index'=>'tfru_title','type'=>'array'));
			$data['allRID'] = $this->db->fetchGroupByParam("SELECT id, title FROM `RID`", array('index'=>'id'));
			return $data;
		}
//'email'=> $row['userEmail'], 'idACL' => $row['userIdACL']
		public function getRID () {
			$query = "SELECT r.id AS r_id, r.title AS r_title, r.short_descr AS r_short_descr, r.idACL as r_idACL,
							 tfr.id AS tfr_id, tfr.title AS tfr_title, tfr.own as tfr_own,
							 fr.idACL AS fr_idACL, fr.id as fr_id,
							 tvfr.id AS tvfr_id, tvfr.`key` AS tvfr_key, tvfr.value AS tvfr_value, 
							 type_fr.id AS type_fr_id, type_fr.`key` AS type_fr_key, type_fr.title AS type_fr_title, 
							 u.id as u_id, u.title as u_title, u.own as u_own,
							 value_fr.id as value_fr_id, value_fr.value as value_fr_value,
							 user_rid.emailUser as user_rid_emailUser, user_rid.idACL as user_rid_idACL, user_rid.id as user_rid_id,
							 inheritable_rid.id as inheritable_rid_id, inheritable_rid.idRID as inheritable_rid_idRID, inheritable_rid.idInheritableRID,
							 relative_rid.id as relative_rid_id, relative_rid.idRID as relative_rid_idRID, relative_rid.idRelativeRID,
							 branch_rid.id as branch_rid_id, branch_rid.idBranch as branch_rid_idBranch, branch.title as branch_title
						FROM  `RID` AS r
							LEFT JOIN  `FieldRID` AS fr ON r.id = fr.idRID
							LEFT JOIN  `TitleFieldRID` AS tfr ON fr.idTitleFieldRID = tfr.id
							LEFT JOIN  `TypeValueFieldRID` AS tvfr ON tvfr.id = fr.idTypeValueFieldRID
							LEFT JOIN  `TypeFieldRID` AS type_fr ON fr.idTypeFieldRID = type_fr.id
							LEFT JOIN  `Units` as u on u.id = fr.idUnits
							LEFT JOIN  `ValueFieldRID` as value_fr on fr.id = value_fr.idFieldRID
							LEFT JOIN  `User_RID` as user_rid on user_rid.idRID = r.id
							LEFT JOIN  `inheritableRID` as inheritable_rid on inheritable_rid.idRID = r.id
							LEFT JOIN  `RelativeRID` as relative_rid on relative_rid.idRID = r.id
							LEFT JOIN  `Branch_RID` as branch_rid on branch_rid.idRID = r.id
							LEFT JOIN  `Branch` as branch on branch.id = branch_rid.idBranch
						WHERE r.id =:id";
			
			$data = $this->db->getDataGroupByRID ($query, array());
			return $data;
/*

*/

		}
	}
?>