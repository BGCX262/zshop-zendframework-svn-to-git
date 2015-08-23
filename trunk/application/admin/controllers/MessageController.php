<?php
	class Admin_MessageController extends Zend_Controller_Action{
		
		
	
		
		function init()
		{
			$this->alldate = new MessageModel();
			$this->member = new MemberModel();
			
		}
		//短消息列表
		public function indexAction()
		{			

			//总页数
			$totalRows=$this->alldate->getCount();
			//每页多少行
			$rowsPerPage = 3;
			$totalPage = ceil($totalRows/$rowsPerPage);
			
			//页码
			$page = intval($this -> _getParam('page'));
			$page = $page<1 ? 1 : ($page > $totalPage ? $totalPage : $page);
			
			
			$this -> view -> totalPage = $totalPage;
			$this -> view -> page = $page;
		
			//列表查询
				$data = array();
				$data['title'] = $this->_request->getParam('title');
				$data['keyword'] = $this->_request->getParam('keyword');
				
				if(!empty($data['title']) or !empty($data['keyword'])){
					$this->view->date=$this->alldate->searchMessage($data,$page,$rowsPerPage);
					$this->render('index');
				return ;
				}
			//获取短消息表所有数据
			$this->view->date = $this->alldate->getList($page,$rowsPerPage);
			
		}
		
	
		//短消息查询
		
		public function searchAction()
		{
			
			$data = array();
			$data['title'] = $this->_request->getParam('title');	
			$data['keyword'] = $this->_request->getParam('keyword');
			
			$this->view->date=$this->alldate->searchMessage($data);
			
			$this->render('index');
	
		}
	
		//发送短消息表单
		public function sendAction()
		{	
			$data=$this->_request->getPost();
			if(empty($data)){
				$data['user'] = $this->_request->getParam('user');
				$data['address'] = $this->_request->getParam('address');
				$data['grade'] = $this->_request->getParam('grade');
			}
			$this->view->dataList = $this->member->searchMember($data);
			$this->view->grade=$this->member->grade;
			
			$this->render('send');
			
		}
		//发送短消息处理
		public function senduiAction()
		{
			//获取参数
			$title = $this->getRequest()->getParam('title');
			$content = $this->getRequest()->getParam('content');
			//获取登陆的管理员用户名
			$session = new Zend_Session_Namespace('admin');
			$senderName = $session -> adminName;
			//根据用户名获取管理员ID
			$adminId = new AdminModel();
			
			$Id = $adminId->search($senderName);
			
			$ids = $this->getRequest()->getParam('check');
		
			
			if(!empty($ids) && count($ids) > 1){
				$userid = implode(',', $ids);				
			}elseif(count($ids) == 1){
				$userid = $ids;	
			}else{
				
				$userid = '0';
			}

			if(empty($title) || empty($content)) {
				Zshop_Message::show($this,"发送信息不完整","admin/message/send", 3);
				return ;
			}
	
			//插入数据
			$data = array(
			        'user_id'   => $userid,
					'sender_id' => $Id,
					'title'     => $title,
					'content'   => $content,
					'readed_id'=>0,
					'deleted_id'=>0,
			        'send_time' => time()			
					);
			$number = $this->alldate->insert($data);
			if($number>0){
				$message = "发送成功";
				
			}else{
				$message = "操作出错，请从新提交!";
			}
			Zshop_Message::show($this,$message,"admin/message/send", 3);	
			
		}

	//	编辑短消息
		public function editAction(){
			
			$id = $this->getRequest()->getParam('id');
			$val = $this->alldate->getOne($id);
			$this->view->val = $val;
	
		}
		//删除短消息记录
		public function delAction(){
			$id = $this->getRequest()->getParam('id');
			if(empty($id)){
				$ids = $this -> getRequest() -> getPost('check');
			
			}else{
           		 $ids = $id;
        }
			$isDeleted = $this->alldate->delete($ids);
			$message = $isDeleted ? '删除成功' : '删除失败';
			Zshop_Message::show($this,$message,"admin/message/index",3);
			
		}
			
		
	}

?>