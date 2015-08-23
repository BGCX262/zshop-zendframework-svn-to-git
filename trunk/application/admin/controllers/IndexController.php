<?php
class admin_IndexController extends Zend_Controller_Action
{
	public function init()
    {	
   
    }

    public function indexAction()
    {
    	$session = new Zend_Session_Namespace('admin');
		$this->view->adminName = $session->adminName;
    }
	


}

