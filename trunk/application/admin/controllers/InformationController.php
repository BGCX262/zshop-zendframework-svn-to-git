<?php
	class admin_InformationController extends Zend_Controller_Action{
		
		function init(){
			$this->sortdata=new InformationModel();
			$this->contentModel =new InformationcontentModel();
		}
		
		public function indexAction(){
							//总页数
			$totalRows=$this->contentModel->getCount();
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
					$this->view->date=$this-> contentModel ->searchMessage($data,$page,$rowsPerPage);
				
				return ;
				}
			//获取短消息表所有数据
			$this->view->date = $this->contentModel->getList1($page,$rowsPerPage);
			
			
			
			$this->view->render('index');
				
		}
		//插入分类信息
		public function insertAction(){
			//处理接收参数
			$pid = intval($this -> _getParam('pid'));
			$catName = iconv_substr($this -> _getParam('catname'), 0, 100, 'utf-8');
			$description = iconv_substr($this -> _getParam('description'), 0, 500, 'utf-8');
			$display = intval($this -> _getParam('display'));
			$order = intval($this -> _getParam('order'));
		
			//赋默认值
			$display !== 0 && $display = 1;
			
			if(empty($catName)){
				Zshop_Message::show($this, '带*号内容必须输入', 'back',2);
			}else{
				$catalog = array(
						'pid' => $pid,
						'cat_name' => $catName,
						'display' => $display,
						'description' => $description,
						'order' => $order
				);
				$id = $this -> sortdata -> insert($catalog);
				if($id){
					$message = '添加成功';
					$url = 'admin/information/index';
				}else{
					$message = '添加失败';
					$url = 'back';
				}
				Zshop_Message::show($this, $message, $url,2);
			}
			
			
		}
		
		public function addAction(){
			$data = $this->sortdata->tree();
			$this->view->all = $data;
			
		}
		
		public function adduiAction(){
			$sort1 = $this->_getParam('sort');
			$sort2 = $this->_getParam('sort2');

			if(empty($sort1)){
				$message = "请选择分类";
				Zshop_Message::show($this, $message,back,2);
				return ;
			}else{
				if(empty($sort2)){
					$sort = $sort1;					
				}else{					
					$sort = $sort2;
				}	
			}
			$title = $this->_getParam('title');
			$content = $this->_getParam('content');
			//获取登陆的管理员用户名
			$session = new Zend_Session_Namespace('admin');
			$senderName = $session -> adminName;
			
			$arr = array(
					'cat_id'  => $sort,
					'title'   => $title,
					'content' => $content,
					'author'  => $senderName,
					'addtime' => time(),
						
					);
			
			
			$id = $this -> contentModel -> insert($arr);
			if($id){
				$message = '添加成功';
				$url = 'admin/information/index';
			}else{
				$message = '添加失败';
				$url = 'back';
			}
			Zshop_Message::show($this, $message, $url,2);

		}
		
		
		public function sortAction(){
				$data = $this->sortdata->tree();
			     $this->view->all = $data;
	
		}
		
		public function sortindexAction(){
			$data = $this->sortdata->tree();
			$this->view->catalog = $data;
			
		}
		
		//文章分类编辑页
		public function editAction(){
			$id = intval($this -> _getParam('id'));
			$currentCat = $this -> sortdata -> getOne($id)->toArray();
			$this -> view -> id = $id;
			$this -> view -> cat = $currentCat;
		
			$this -> view -> catArray = $this -> sortdata -> tree();
		}
		
		//文章分类编辑更新
		public function updateAction(){
			//处理接收参数
			$id = intval($this -> _getParam('id'));
			$pid = intval($this -> _getParam('pid'));
			$catName = iconv_substr($this -> _getParam('catname'), 0, 100, 'utf-8');
			$description = iconv_substr($this -> _getParam('description'), 0, 500, 'utf-8');
			$display = intval($this -> _getParam('display'));
			$order = intval($this -> _getParam('order'));
		
			//是否显示赋值
			$display !== 0 && $display = 1;
		
			if(empty($catName)){
				Zshop_Message::show($this, '带*号内容必须输入', 'back',2);
			}else{
				$catalog = array(
						'pid' => $pid,
						'cat_name' => $catName,
						'display' => $display,
						'description' => $description,
						'order' => $order
				);
			//	print_r($id);exit;
				$isUpdated = $this -> sortdata -> update($catalog,$id);

				if($isUpdated){
					$message = '修改成功';
					$url = 'admin/information/sortindex';
				}else{
					$message = '修改失败';
					$url = 'back';
				}
				Zshop_Message::show($this, $message, $url,2);
			}
		
		}
		
		/**
		 * 文章分类删除
		 * @todo 分类下存在商品不删除
		 */
		public function deleteAction(){
			$id = intval($this -> _getParam('id'));
			$this -> sortdata -> where = array('pid' => $id);
			$childCatNum = $this -> sortdata -> getTotalNums();
			if($childCatNum > 0){
				Zshop_Message::show($this,'该分类下存在子分类，不能删除','back',2);
			}else{
				$isDeleted = $this -> sortdata -> delete($id);
				$message = $isDeleted?'删除成功':'删除失败';
				Zshop_Message::show($this, $message, 'back', 2);
			}
		}	
		
		public function ajaxAction(){
			$id = $this->_getParam('id');
			if(!empty($id)){
				$data = $this-> sortdata -> search("pid", $id);
				echo json_encode($data);
				exit;
			}
			exit;
		}
		
		//删除文章
		public function delAction(){
			$id = $this->getRequest()->getParam('id');
			if(empty($id)){
				$ids = $this -> getRequest() -> getPost('check');
					
			}else{
				$ids = $id;
			}
			$isDeleted = $this->contentModel->delete($ids);
			$message = $isDeleted ? '删除成功' : '删除失败';
			Zshop_Message::show($this,$message,"admin/information/index",3);
				
		}
		
		//文章编辑页
		
		public function editinformAction(){
			$id = $this->getRequest()->getParam('id'); 
			$this->view->data = $this-> contentModel ->find($id)->toArray();
			$data = $this->sortdata->tree();
			$this->view->all = $data;
			
		}
	}

?>