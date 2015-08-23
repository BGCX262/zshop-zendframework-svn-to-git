<?php
	class Admin_GradeController extends Zend_Controller_Action{

		public $grade;
	
		function init(){
			$this->grade = new GradeModel();
		}

		function indexAction(){
			$this->view->dataList=$this->grade->searchGrade();
		}
		
		function addAction(){
			
		}

		function addFormAction(){
			$name = $this->_request->getParam('name');
			$min  = $this->_request->getParam('min');
			$max  = $this->_request->getParam('max');
			$discount  = $this->_request->getParam('discount');
			if(empty($name) || empty($min) || empty($max)){
				Zshop_Message::show($this, '不能为空', 'admin/grade/add',1);
			}else{
				$data = array(
						'grade_name' => $name,
						'min_point'  => intval($min),
						'max_point'  => intval($max),
						'discount'   => $discount
					);
				$id = $this->grade->insertGrade($data);
				if($id){
					$this -> _redirect('admin/grade/index');
				}else{
					Zshop_Message::show($this, '插入失败', 'admin/grade/add',1);
				}
			}
		
		}

		function deleteAction(){
			$id = $this->_request->getParam('id');
			$this->grade->deleteGrade($id);
			$this -> _redirect('admin/grade/index');
		}

		function updateAction(){
			$id = $this->_request->getParam('id');
			$this->view->data = $this->grade->getOne($id);
		
		}

		function updateFormAction(){
			$id = $this->_request->getParam('id');
			$name = $this->_request->getParam('name');
			$min  = $this->_request->getParam('min');
			$max  = $this->_request->getParam('max');
			$discount  = $this->_request->getParam('discount');
			if(empty($name) || empty($min) || empty($max)){
				Zshop_Message::show($this, '不能为空', 'admin/grade/add',1);
			}else{
				$data = array(
						'grade_name' => $name,
						'min_point'  => intval($min),
						'max_point'  => intval($max),
						'discount'   => $discount
					);
				$this->grade->updateGrade($id,$data);
				$this -> _redirect('admin/grade/index');
			}
		
		}
	
	
	}

?>