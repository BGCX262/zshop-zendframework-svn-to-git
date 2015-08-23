<?php
class AdminModel extends Zend_Db_Table
{
	protected $_name='s_admin';

	function login($user,$pass)
	{
		$select = $this->select();
		$sql=$select->from ('s_admin','count(*)')
		            ->where('admin_name=?',$user)
		            ->where('password=?',md5($pass));
		$db=$this->getAdapter();
		$fieldNum=$db->fetchOne($sql->__toString());
		if($fieldNum==1){
			return true;
		}else{
			return false;
		}
	}
	//根据条件查询字段值
	public function search($senderName){
		$select = $this->select();
		$sql=$select->from ('s_admin','id')
					->where("admin_name = '$senderName'");
		$db=$this->getAdapter();
		
		$adminId=$db->fetchOne($sql->__toString());
		
		return $adminId;
	}
	
}