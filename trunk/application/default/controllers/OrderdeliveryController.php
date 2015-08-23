<?php

class OrderdeliveryController extends Zend_Controller_Action
{

    public function init(){
		$this->orderdy=new OrderdeliveryModel();
    }
	/*********¶©µ¥ÅäËÍÁÐ±í*************/
    public function indexAction(){
		$this->orderdy->searchOrderdelivery();
    }

	public function addAction(){
		$data = $this->_request->getPost();
		//$data = $this->_request->getParam('dq');
		print_r($data);exit;
    }


}

