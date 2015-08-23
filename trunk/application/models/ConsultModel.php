<?php
	class ConsultModel extends Zend_Db_Table{
		protected $_name;
		protected $_primary="id";
		public $grade;
	
		function init(){
			$this->_name = TABLEPRE.'goods_consult';
		}
			
	/***********查询咨询 列表 分页***************/		
		function searchConsult($page=null,$numrows=10){
			$db = $this->getAdapter();
			$select = $this->select()->setIntegrityCheck(false);
			$page!=null && $select->limitPage($page,$numrows);
			/*
			goods_comment.goods_id = goods_info.id  - >goods_name  goods_comment.user_id = member.id   ->user_name
			select s_goods_info.goods_name,s_goods_comment.*,s_member.user_name from s_goods_info,s_goods_comment,s_member where s_goods_comment.goods_id = s_goods_info.id and s_goods_comment.user_id = s_member.id and s_goods_comment.content like "%few%"
			$joinTable = TABLEPRE . 'goods_consult';
			$joinWhere = TABLEPRE . "goods_consult.id=$this->_name.grade"; 
			$field = TABLEPRE . 'member_grade.grade_name';*/
			$select->from("s_goods_consult",'*')
				   ->join("s_member","s_goods_consult.user_id = s_member.id","s_member.user_name")
			       ->join("s_goods_info","s_goods_consult.goods_id = s_goods_info.id","s_goods_info.goods_name");
			return $db->fetchAll($select->__toString());
		}
		
	/***********获取一条记录***************/
		function getOne($id){
			$select=$this->select();
			$select->where("id = ?",$id);
			return $this->fetchRow($select)->toArray();
		}
		
	/***********删除商品咨询***************/			
		function deleteConsult($rid){
			$db= $this->getAdapter();
			$sql = $db->quoteInto(" id in (?)",$rid);
			return $this->delete($sql);
		}
	/***********获取商品咨询总数***************/			
		function countConsult(){
			$select=$this->select();
			$select->from($this->_name,"count(*) as nums");
			$rs = $this->fetchAll($select)->toArray();
			return $rs['0']['nums'];
		}

		///更新数据

		function updateConsult($id,$data){
			$db = $this->getAdapter();
			$where = $db->quoteInto("id = ?",intval($id));
			return $this->update($data,$where);
		}
	}

?>
