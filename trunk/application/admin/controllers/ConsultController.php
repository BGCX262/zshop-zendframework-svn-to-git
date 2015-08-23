<?php
	class Admin_ConsultController extends Zend_Controller_Action{

		public $consult;

		public function init(){
			$this->consult = new ConsultModel();
		}
		
		public function indexAction(){
			$page = $this->_request->getParam('page');
			empty($page) && $page = 1;
			$numrows = 6;
			$this->view->dataList = $this->consult->searchConsult($page,$numrows);
			$count = $this->consult->countConsult();
			$this->view->totalPage = ceil($count/$numrows);
			$this->view->page=$page;
		}
		public function showAction(){	
			$id = $this->_request->getParam('id');
			$this->view->data = $this->consult->getOne($id);
		}

		public function deleteAction(){
			$id = $this->_request->getParam('id');
			$this->consult->deleteConsult($id);
		
		}
		public function replyAction(){
			$id = $this->_request->getParam('id');
			$reply = $this->_request->getParam('reply');
			$data = array('reply'=>$reply,'reply_status'=>'1');
			$ok = $this->consult->updateConsult($id,$data);
			!empty($ok)  && Zshop_Message::show($this, '回复成功！','admin/consult/index',2);
			
			

		}
	
	}

?>