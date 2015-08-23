<?php
	class OrderdeliveryModel extends Zend_Db_Table{
		protected $_name;
		protected $_primary="id";
	
		function init(){
			$this->_name = TABLEPRE.'order_delivery';
		}
		/***********��Ӷ�������***************/		
		function insertOrderdelivery($data){
			return $this->insert($data);
		}
		
			
	/***********�����б�***************/		
		function searchOrderdelivery(){
			$select = $this->select();
			return $this->fetchAll($select)->toArray();
		}
		
	/***********��ȡһ����¼***************/
		function getOne($id){
			$select=$this->select();
			$select->where("id = ?",$id);
			return $this->fetchRow($select)->toArray();
		}
		
	/***********ɾ�����ͷ�ʽ***************/			
		function deleteOrderdelivery($rid){
			$db= $this->getAdapter();
			$sql = $db->quoteInto(" id in (?)",$rid);
			return $this->delete($sql);
		}
	/***********�޸���������***************/			
		function updateOrderdelivery($id,$data){
			$db = $this -> getAdapter();
            $where = $db -> quoteInto('id = ?',intval($id));
                
            return $this -> update($data,$where);
			
		}
	}

?>
