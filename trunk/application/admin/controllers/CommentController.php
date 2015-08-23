<?php
	class Admin_CommentController extends Zend_Controller_Action{
	
		public $comment;

		function init(){
			$this->comment = new CommentModel();
		}
		
		function indexAction(){
			$keyword = $this->_request->getParam('keyword');
			$page = $this->_request->getParam('page');
			empty($page) && $page = 1;
			$numrows = 6;
			$this->view->dataList = $this->comment->searchComment($keyword,$page,$numrows);
			$count = $this->comment->countComment($keyword);
			$this->view->totalPage = ceil($count/$numrows);
			$this->view->page=$page;
		}
		
		function deleteAction(){
			$aid=$this->_request->getParam('checked');
			$id= $this->_request->getParam('id');
			!empty($id) ? $rid = $id : $rid = $aid;
			$this->comment->deleteComment($rid);
			$this->_redirect('admin/comment/index');
		}
	
		function  updateAction(){
			$id= $this->_request->getParam('id');
			$this->view->data = $this->comment->getOne($id);	
			$this->render("one");
		}
	
	}

?>