<?php

class OrderController extends Zend_Controller_Action
{

    public function init(){
       $this->order = new OrderModel();
	   $this -> goodsCatalogModel = new GoodsCatalogModel();
    }

    public function indexAction(){
        $page = $this->_request->getParam('page');
		empty($page) && $page = 1;
		$numrows = 6;
		$this->view->datalist = $this->order->searchOrder($data=null,$page,$numrows);
		$count = $this->order->countOrder();
		$this->view->totalPage = ceil($count/$numrows);
		$this->view->page=$page;
		$this -> view -> catalog = $this -> goodsCatalogModel -> getCatTree();
    }

	function orderAction(){
	
	}
	//==========ÑéÖ¤Âë==========
	function validateAction(){
		$vpic = new Zend_Validate_();
		$vpic->show();
	}
  
    function testcheckout(){
        return 0;
    }

}

