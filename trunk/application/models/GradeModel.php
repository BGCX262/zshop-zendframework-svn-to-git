<?php
	class GradeModel extends Zend_Db_Table{
		protected $_name;
		protected $_primary="id";
	
		function init(){
			$this->_name = TABLEPRE.'member_grade';
		}
	
	/***********添加会员组***************/
		
		function insertGrade($data){
			return $this->insert($data);


		}
			
	/***********会员组列表***************/		
		function searchGrade($data=null){
			$select= $this->select();
			return $this->fetchAll($select)->toArray();
		}
		
	/***********获取一条记录***************/
		function getOne($id){
			$select=$this->select();
			$select->where("id = ?",$id);
			return $this->fetchRow($select)->toArray();
		}
		
	/***********删除会员组***************/			
		function deleteGrade($id){
			return $this->delete("id =".intval($id));
			
		}
	/***********修改会员组信息***************/			
		function updateGrade($id,$data){
			$db = $this -> getAdapter();
            $where = $db -> quoteInto('id = ?',intval($id));
                
            return $this -> update($data,$where);
			
		}
		
	}

?>
