<?php 
class Zshop_MyPlugin extends Zend_Controller_Plugin_Abstract
{   
    //后台登录权限检查 
	public function routeShutdown(Zend_Controller_Request_Abstract $request)
	{
		if($request -> getModuleName() == 'admin'){
		 	$session = new Zend_Session_Namespace('admin');
		 	header("Cache-control:private");
		 	if(empty($session -> adminName) && $request -> getControllerName() !== 'login'){
                $request ->setControllerName('login');
		 	    $request ->setActionName('index');
		 	}
	    }
    }

		
}
?>