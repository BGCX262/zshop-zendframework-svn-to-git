<?php
	class Admin_PaymentController extends Zend_Controller_Action{
	
		public $email;
	
		function init(){
			$this->payment = new PaymentModel();
		}

		function indexAction(){
			$this->view->datalist = $this->payment->searchPayment();
		}
		 
	
	}

?>