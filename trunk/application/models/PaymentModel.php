<?php
	class PaymentModel extends Zend_Db_Table{
		protected $_name;
		protected $_primary="id";
	
		function init(){
			$this->_name = TABLEPRE.'payment_mode';
		}
			
	/***********�����б�***************/		
		function searchPayment(){
			$select = $this->select();
			return $this->fetchAll($select)->toArray();
		}
		
	/***********��ȡһ����¼***************/
		function getOne($id){
			$select=$this->select();
			$select->where("id = ?",$id);
			return $this->fetchRow($select)->toArray();
		}
		
	/***********ɾ��֧����ʽ***************/			
		function deletePayment($rid){
			$db= $this->getAdapter();
			$sql = $db->quoteInto(" id in (?)",$rid);
			return $this->delete($sql);
		}
	/***********�޸�֧����Ϣ***************/			
		function updatePayment($id,$data){
			$db = $this -> getAdapter();
            $where = $db -> quoteInto('id = ?',intval($id));
                
            return $this -> update($data,$where);
			
		}
	}

?>
