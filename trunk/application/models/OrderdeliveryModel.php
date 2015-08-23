<?php
	class OrderdeliveryModel extends Zend_Db_Table{
		protected $_name;
		protected $_primary="id";
	
		function init(){
			$this->_name = TABLEPRE.'order_delivery';
		}
		/***********添加订单配送***************/		
		function insertOrderdelivery($data){
			return $this->insert($data);
		}
		
			
	/***********配送列表***************/		
		function searchOrderdelivery(){
			$select = $this->select();
			return $this->fetchAll($select)->toArray();
		}
		
	/***********获取一条记录***************/
		function getOne($id){
			$select=$this->select();
			$select->where("id = ?",$id);
			return $this->fetchRow($select)->toArray();
		}
		
	/***********删除配送方式***************/			
		function deleteOrderdelivery($rid){
			$db= $this->getAdapter();
			$sql = $db->quoteInto(" id in (?)",$rid);
			return $this->delete($sql);
		}
	/***********修改配送资料***************/			
		function updateOrderdelivery($id,$data){
			$db = $this -> getAdapter();
            $where = $db -> quoteInto('id = ?',intval($id));
                
            return $this -> update($data,$where);
			
		}
	}

?>
