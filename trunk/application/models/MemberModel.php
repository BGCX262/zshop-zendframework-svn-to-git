<?php
	class MemberModel extends Zend_Db_Table{
		protected $_name;
		protected $_primary="id";
		public $grade;
	
		function init(){
			$this->_name = TABLEPRE.'member';
		}
	
	/***********��ӻ�Ա***************/
		
		function insertMember($data){
			return $this->insert($data);

		}
			
	/***********��ѯ��Ա �б� ��ҳ***************/		
		function searchMember($data=null,$page=null,$numrows=10){
			$db = $this->getAdapter();
			$select = $this->select()->setIntegrityCheck(false);
			$this->grade = $db->query("select id,grade_name from s_member_grade")->fetchAll();
			
			!empty($data['user']) && $select->where('user_name like ?','%' . $data['user'] . '%');
			!empty($data['address']) && $select->where('address like ?','%' . $data['address'] . '%');
			!empty($data['grade']) && $select->where('grade like ?','%' . $data['grade'] . '%');
			$page!=null && $select->limitPage($page,$numrows);
			
			$joinTable = TABLEPRE . 'member_grade';
			$joinWhere = TABLEPRE . "member_grade.id=$this->_name.grade"; 
			$field = TABLEPRE . 'member_grade.grade_name';
			$select->from('s_member','*')
			       ->joinleft($joinTable, $joinWhere, $field);
			return $db->fetchAll($select->__toString());
		}
		
	/***********��ȡһ����¼***************/
		function getOne($id){
			$select=$this->select();
			$select->where("id = ?",$id);
			return $this->fetchRow($select);
		}
		
	/***********ɾ����Ա***************/			
		function deleteMember($rid){
			$db= $this->getAdapter();
			$sql = $db->quoteInto(" id in (?)",$rid);
			return $this->delete($sql);
		}
	/***********�޸Ļ�Ա����***************/			
		function updateMember($id,$data){
			$db = $this -> getAdapter();
            $where = $db -> quoteInto('id = ?',intval($id));
                
            return $this -> update($data,$where);
			
		}
		
	/***********��ԱΨһ��***************/			
		function checkMember($user){
			$select=$this->select();
			$select->from($this->_name,'count(*)')
			       ->where("user_name = ?",$user);
			$db =  $this->getAdapter();
			$numrows = $db -> fetchOne($select->__toString());
			return $numrows == 0?true:false;	
		}
	/***********��ȡ����***************/			
		function countMember($data=null){
			$select=$this->select();
			$select->from($this->_name,"count(*) as nums");
			!empty($data['user']) && $select->where('user_name like ?','%' . $data['user'] . '%');
			!empty($data['address']) && $select->where('address like ?','%' . $data['address'] . '%');
			!empty($data['grade']) && $select->where('grade like ?','%' . $data['grade'] . '%');
			
			$rs = $this->fetchAll($select)->toArray();
			return $rs['0']['nums'];
		}

		////�ʼ�����//////
		function sendMember($key){
			$select = $this->select();
			!empty($key) && $select->where('user_name like ?','%' . $key . '%');
			return $this->fetchAll($select)->toArray();
		}

		//���ݻ�Ա��ID��ѯ��Ա

		function groupMember($in){
			$select=$this->select();
			$select->where("grade in(?)",$in);
			return $this->fetchAll($select)->toArray();
		}
	}

?>
