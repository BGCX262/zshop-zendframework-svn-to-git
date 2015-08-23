<?php 
	class EmailModel extends Zend_Db_Table{
		protected $_name;
		protected $_primary="id";
		public $_send;
	
		public function init(){
			$this->_name = TABLEPRE.'mail';
			$this->email = new Zend_Mail();
			
		}

		//��������
		public function insertEmail($data){
			return $this->insert($data);
		}

		//�ʼ���ѯ
		public function searchEmail($page=null,$numrows=6){
			$select = $this->select();
			$page!=null && $select->limitPage($page,$numrows);
			return $this->fetchAll($select)->toArray();
		}

		//�ʼ�����
		public function sendEmail($to,$title,$content){
			$config= array('auth'=>'login','username' => 'Ying_2_su','password' => 'aizou786921');
			$host   = new Zend_Mail_Transport_Smtp('smtp.126.com',$config);  
			$this->email->setBodyHtml($content);
			$this->email->setFrom("Ying_2_su@126.com",'Some Sender');
			$this->_send = "Ying_2_su@126.com";
			if(preg_match("/@/",$to)){
				$arr = explode(';',$to);
				for($i=0;$i<count($arr);$i++){
					$this->email->addTo($arr[$i],'Some Recipient');
				}
			}else{
				$arr = explode(';',$to);
				for($i=0;$i<count($arr);$i++){
					$group[] = intval($arr[$i]);	
				}
				$this->grade = new MemberModel();
				$member = $this->grade->groupMember($group);
				foreach($member as $val){
					$for_grade = $val['email'];
					$this->email->addTo($for_grade,'Some Recipient');
				}	
			}
			$this->email->setSubject($title);
			if($this->email->send($host)){
				return true;
			}else{
				return false;
			}
		}

		//��������
		function updateField($id,$data){
			$db = $this -> getAdapter();
            $where = $db -> quoteInto('id = ?',intval($id));
                
            return $this -> update($data,$where);
			
		}

		/***********��ȡ���ʼ�����***************/			
		function countEmail(){
			$select=$this->select();
			$select->from($this->_name,"count(*) as nums");
			$rs = $this->fetchAll($select)->toArray();
			return $rs['0']['nums'];
		}

		/***********��ȡһ����¼***************/
		function getOne($id){
			$select=$this->select();
			$select->where("id = ?",$id);
			return $this->fetchRow($select)->toArray();
		}

	}



?>