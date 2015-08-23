<?php
class admin_LoginController extends Zend_Controller_Action
{
	public $session;
	
	public function init()
	{
		$this->session = new Zend_Session_Namespace('admin');	
	}
	
	public function indexAction()
    {
    	$this->render('login');	
    }

    public function loginAction()
    {
    	//获取表单值
    	$user=$this->_getParam('user');
    	$pass=$this->_getParam('pass');
    	//实例化后台模型
    	$admin= new AdminModel();
    	//实例化session后台命名空间
    	$isValid=$admin->login($user, $pass);
    	if($isValid){
    		$this->session->adminName = $user;
    		$this->_redirect('/admin/index/index');
    	}else{
    		$this->_redirect('/admin/login/index');
    	}	
    }

	public function logoutAction()
	{
		unset($this->session->adminName);
		Zend_Session::destroy(true);
		Zshop_Message::show($this, '退出成功', 'admin/login/index', 1);
	}

}

?>
