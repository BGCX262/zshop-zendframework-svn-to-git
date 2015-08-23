<?php
class Zshop_Message
{
	/**
	 * 
	 * @param object $controller controller对象的实例
	 * @param string $message    提示消息 
	 * @param string $forwardUrl 跳转地址，如果为'back'，不刷新后退
	 * @param int    $time       等待时间,单位秒，大于等于1
	 */
    public static function show($controller, $message, $forwardUrl, $time)
    {
    	//获取根目录
    	$rootUrl = zend_controller_front::getInstance()->getBaseUrl();
    	$forwardUrl !== 'back' && $forwardUrl = $rootUrl . '/' . $forwardUrl;
    	$viewPath=APPLICATION_PATH . '/admin/views/scripts/common';	
    	$controller->view->message = $message;
    	$controller->view->forwardUrl = $forwardUrl;
    	$controller->view->time = $time;
    	$controller->view->setScriptPath($viewPath);
    	$controller->render('message',null,true);
    }	
}