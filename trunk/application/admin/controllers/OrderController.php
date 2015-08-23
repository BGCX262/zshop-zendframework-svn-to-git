<?php
	class Admin_OrderController extends Zend_Controller_Action{
		public $member;
	
	function init(){
		$this->order = new OrderModel();
	}
	
	function indexAction(){
		$data=$this->_request->getPost();
		if(empty($data)){
			$data['order'] = $this->_request->getParam('order');
			//$data['get'] = $this->_request->getParam('get');
			$data['status'] = $this->_request->getParam('status');	
		}
		$page = $this->_request->getParam('page');
		empty($page) && $page=1;
		
		$rownums = 10;
		$this->view->datalist = $this->order->searchOrder($data,$page,$rownums);
		$count = $this->order->countOrder($data);
		$this->view->countPage = ceil($count/$rownums);
		$this->view->page=$page;			
	}
	
	function deleteAction(){
		$id= $this->_request->getParam('id');
		$this->member->deleteMember($id);
		$this->_redirect('admin/member/index');
	}
	
	function  updateAction(){
		$id= $this->_request->getParam('id');
		$this->view->detail = $this->member->getOne($id);	
	}


	function searchAction(){
	
	}

	function updateFormAction(){
		$id= $this->_request->getParam('id');
		$name = $this->_request->getParam('user_name');
		$sex = $this->_request->getParam('sex');
		$date = $this->_request->getParam('date');
		$phone = $this->_request->getParam('phone');
		$email = $this->_request->getParam('email');
		if(empty($name) || empty($sex) || empty($email)|| empty($phone)|| empty($date)){
			$this->_forward("update","member","admin",array("id"=>$id));
		}else{
			$data = array(
					'user_name' => $name,
					'email'    => $email,
					'sex'      => $sex,
					'phone'    => $phone,
					'birthday' => strtotime($date),
			);
			$this->member->updateMember($id,$data);
			$this -> _redirect('admin/member/index');							
		}   
	}
	
	function addAction(){
		$this->render('add');
	}

	function addFormAction(){
		$name = $this->_request->getParam('user_name');
		$pass = $this->_request->getParam('password');
		$sex = $this->_request->getParam('sex');
		$date = $this->_request->getParam('date');
		$phone = $this->_request->getParam('phone');
		$email = $this->_request->getParam('email');
		if(empty($name) || empty($pass) || empty($sex) || empty($email)|| empty($phone)|| empty($date)){
			Zshop_Message::show($this, '输入不能为空', 'admin/member/add',1);
		}else{
			$isValid = $this -> member -> checkMember($name);
			if($isValid){
				$data = array(
						'user_name' => $name,
						'password' => $pass,
						'email'    => $email,
						'sex'      => $sex,
						'phone'    => $phone,
						'birthday' => strtotime($date),
						'ip' => $_SERVER['REMOTE_ADDR'],
						'grade' => 1,
						'point' => 0,
						'money' => 0,
						'create_time' => time()
				);
				$id = $this -> member -> insertMember($data);
				$id? $this -> _redirect('admin/member/index') : Zshop_Message::show($this, '插入失败', 'admin/member/add',1);
			}else{
				Zshop_Message::show($this, '用户名已存在', 'admin/member/add',1);
			}
		}

	}

	function shipmentsAction(){
	
	}
	function returnedAction(){
	
	}
	function stockoutAction(){
	
	}
}

?>