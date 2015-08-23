<?php
	class OrderModel extends Zend_Db_Table{
		protected $_name;
		protected $_primary="id";
	
		function init(){
			$this->_name = TABLEPRE.'order';
		}

		/***********添加订单***************/
		
		function insertOrder($data){
			return $this->insert($data);
		}
			
	/***********后台查询订单 列表 分页***************/		
		function searchOrder($data=null,$page=null,$numrows=10){
			$db = $this->getAdapter();
			$select = $this->select()->setIntegrityCheck(false);
			!empty($data['order']) && $select->where('order_id = ?',$data['order']);
			!empty($data['status']) && $select->where('order_status = ?',$data['status']);
			$page!=null && $select->limitPage($page,$numrows);
			/*
				$select->from("s_goods_consult",'*')
				   ->join("s_member","s_goods_consult.user_id = s_member.id","s_member.user_name")
			       ->join("s_goods_info","s_goods_consult.goods_id = s_goods_info.id","s_goods_info.goods_name");
			*/
			$joinTable = TABLEPRE . 'member';
			$joinWhere = TABLEPRE . "member.id=$this->_name.user_id"; 
			$field = TABLEPRE . 'member.user_name';
			$select->from('s_order','*')
			       ->joinleft($joinTable, $joinWhere, $field);
			return $db->fetchAll($select->__toString());
		}
		
	/***********获取一个订单***************/
		function getOne($id){
			$select=$this->select();
			$select->where("id = ?",$id);
			return $this->fetchRow($select)->toArray();
		}
		
	/***********隐藏订单***************/			
		function deleteOrder($id){
			return $this->delete("id =".intval($id));
			
		}
	/***********修改订单信息***************/			
		function updateOrder($id,$data){
			$db = $this -> getAdapter();
            $where = $db -> quoteInto('id = ?',intval($id));
                
            return $this -> update($data,$where);
			
		}
	/***********获取订单总数***************/			
		function countOrder($data=null){
			$select=$this->select();
			$select->from($this->_name,"count(*) as nums");
			//!empty($data['user']) && $select->where('user_name like ?','%' . $data['user'] . '%');
			//!empty($data['address']) && $select->where('address like ?','%' . $data['address'] . '%');
			//!empty($data['grade']) && $select->where('grade like ?','%' . $data['grade'] . '%');
			
			$rs = $this->fetchAll($select)->toArray();
			return $rs['nums'];
		}
 
}

?>