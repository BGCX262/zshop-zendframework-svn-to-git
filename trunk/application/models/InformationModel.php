<?php
	class InformationModel extends BaseModel
	{
		protected $_name;
	
		function init(){
			$this->_name = TABLEPRE . 'information_catalog';
	
		}
		
		
		//无级分类
		public function tree(){
	
			$select = $this->select();
			$select->setIntegrityCheck(false);
			$select ->from('s_information_catalog','*');
			$data = $this->fetchAll($select)->toArray();
			return $this->sort($data);
		}
		
		public function sort($data,$depth=0,$pid=0,&$tree=array()){
			foreach ($data as $val){
				if($val['pid'] == $pid){
					$val['depth'] = $depth;
					$tree[] = $val;
					$this->sort($data,$depth+1,$val['id'],$tree);
					
				}
						
			}
		
			return $tree;
		}
		//根据一个条件查询数据库
		public function search($field,$value){
			$select = $this->select();
			$select->where($field ."=?",$value);
			return $this->fetchAll($select)->toArray();
		}
		
	}


?>