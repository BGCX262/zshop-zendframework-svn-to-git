<?php
class InformationcontentModel extends BaseModel
{
	protected $_name;

	function init(){
		$this->_name = TABLEPRE . 'information_content';

	}
	public function getCount(){
		$select = $this->select();
		$select->from($this->_name,'count(*) as tol');
		return $this->fetchAll($select)->current()->tol;
	
	}
	
	public function getList1($page,$numsPerPage){
	
		$select = $this->select();
		//要关闭下面这个方法才能使用连表查询
		$select->setIntegrityCheck(false);
		$select ->from('s_information_content','*')
		->limitPage($page,$numsPerPage);
		$date = $this->fetchAll($select)->toArray();
	
	
		//获取短消息内容前十个字符
		foreach ($date as $key =>$val)
		{
			if(strlen($val['title'])>15){
				$dates = substr($val['title'],0,15)."......";
					
				$date[$key]['title'] = $dates;
			}
				
			if(strlen($val['content'])>30){
				$dates = substr($val['content'],0,60)."......";
					
				$date[$key]['content'] = $dates;
			}
				
		}
	
		return $date;
	}
	
	//列表查询
	public function searchMessage($data = array(),$page,$numsPerPage){
	
		$select = $this->select();
		$select->from($this->_name,'*');
		$data['title'] && $select ->where("title like ?","%".$data['title']."%");
		$data['keyword'] && $select ->orwhere("content like ?","%".$data['keyword']."%");
		$select -> limitPage($page, $numsPerPage);
		return   $this->fetchAll($select)->toArray();
	
	
	}
	
}
	?>