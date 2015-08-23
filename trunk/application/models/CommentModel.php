<?php
	class CommentModel extends Zend_Db_Table{
		protected $_name;
		protected $_primary="id";
		public $member_name;
		public $goods_name;
	
		function init(){
			$this->_name = TABLEPRE.'goods_comment';
		}
			
	/***********查询商品评论 列表 分页***************/		
		function searchComment($keyword=null,$page=null,$numrows=10){
			$db = $this->getAdapter();
			$select = $this->select()->setIntegrityCheck(false);
			!empty($keyword) && $select->where('content like ?','%' . $keyword . '%');
			$page!=null && $select->limitPage($page,$numrows);
			$select->from($this->_name,'*')
				   ->join("s_member","s_goods_comment.user_id = s_member.id","s_member.user_name")
			       ->join("s_goods_info","s_goods_comment.goods_id = s_goods_info.id","s_goods_info.goods_name");
			return $db->fetchAll($select->__toString());
		}
		
	/***********获取一条商品评论记录***************/
		function getOne($id){
			$db = $this->getAdapter();
			$select = $this->select()->setIntegrityCheck(false);
			$select->from($this->_name,'*')
				   ->where($this->_name.".id = ?",intval($id))
				   ->join("s_member","s_goods_comment.user_id = s_member.id","*")
			       ->join("s_goods_info","s_goods_comment.goods_id = s_goods_info.id","s_goods_info.goods_name");
			return $db->fetchAll($select->__toString());
		}
		
	/***********删除商品咨询***************/			
		function deleteComment($rid){
			$db= $this->getAdapter();
			$sql = $db->quoteInto(" id in (?)",$rid);
			return $this->delete($sql);
		}
	/***********获取商品评论总数***************/			
		function countComment($keyword){
			$select=$this->select();
			$select->from($this->_name,"count(*) as nums");
			!empty($keyword) && $select->where('content like ?','%' . $keyword . '%');	
			$rs = $this->fetchAll($select)->toArray();
			return $rs['0']['nums'];
		}
	}

?>
