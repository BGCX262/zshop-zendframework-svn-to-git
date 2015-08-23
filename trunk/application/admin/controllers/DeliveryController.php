<?php
	class Admin_DeliveryController extends Zend_Controller_Action{
	
	
		function init(){
			$this->delivery = new DeliveryModel();
		}

		function indexAction(){
			$this->view->datalist = $this->delivery->searchDelivery();
		}
		
	
	}

?>