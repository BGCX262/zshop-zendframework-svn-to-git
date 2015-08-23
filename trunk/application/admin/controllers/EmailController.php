<?php
	class admin_EmailController extends Zend_Controller_Action{
	
		public $email;
	
		function init(){
			$this->email = new EmailModel();
			$this->member= new MemberModel();
		}

		function indexAction(){
			$page = $this->_request->getParam('page');
			empty($page) && $page = 1;
			$numrows = 6;
			$this->view->datalist = $this->email->searchEmail($page,$numrows);
			$count = $this->email->countEmail();
			$this->view->totalPage = ceil($count/$numrows);
			$this->view->page=$page;
		}
		
		function sendAction(){
			$id=$this->_request->getParam('checked');
			if(empty($id)){
				$this->render('send');
			}else{
				$this->view->data = $this->email->getOne($id);
				$this->render('send-form');
			}
		}

		function sendFormAction(){
			$to = $this->_request->getParam('to');
			$title = $this->_request->getParam('title');
			$content = $this->_request->getParam('content');
			/*
			$data = array(
						'to' => $to,
						'title' => $title,
						'content'    => $content
				);print_r($data);exit;*/
			if(empty($to) || empty($title) || empty($content)){
				Zshop_Message::show($this, '不能为空！','admin/email/send',2);
			}else{
				$tf = $this->email->sendEmail($to,$title,$content);
				if($tf){
					$data=array(
						'address'=>$to,
						'send_name'=>$this->email->_send,
						'title'=>$title,
						'content'=>$content,
						'send_time'=>time()
					);
					$line = $this->email->insertEmail($data);
					if($line){
						Zshop_Message::show($this, '发送成功！','admin/email/index',2);
					}else{
						Zshop_Message::show($this, '发送成功 , 插入失败！','admin/email/index',2);
					}
				}else{
					Zshop_Message::show($this, '发送失败！','admin/email/index',4);
				}
			}
		} 
	
	}

?>