<?php
	class PaymentModel extends Zend_Db_Table{
		protected $_name;
		protected $_primary="id";
	
		function init(){
			$this->_name = TABLEPRE.'payment_mode';
		}
			
	/***********配送列表***************/		
		function searchPayment(){
			$select = $this->select();
			return $this->fetchAll($select)->toArray();
		}
		
	/***********获取一条记录***************/
		function getOne($id){
			$select=$this->select();
			$select->where("id = ?",$id);
			return $this->fetchRow($select)->toArray();
		}
		
	/***********删除支付方式***************/			
		function deletePayment($rid){
			$db= $this->getAdapter();
			$sql = $db->quoteInto(" id in (?)",$rid);
			return $this->delete($sql);
		}
	/***********修改支付信息***************/			
		function updatePayment($id,$data){
			$db = $this -> getAdapter();
            $where = $db -> quoteInto('id = ?',intval($id));
                
            return $this -> update($data,$where);
			
		}
	}

?>
