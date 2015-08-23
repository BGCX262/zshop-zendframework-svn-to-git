<?php
class Zshop_Common
{

	////////////无极分类函数，深度
	public static function tree($data,$pid=0,$depth=0,&$tree=array()){
		   foreach($data as $key=>$val){
				if($pid == $val['pid']){
					$val['depth'] = $depth;
					$tree[] = $val;
					tree($data,$val['id'],$depth+1,$tree);
				}
		   }
		   return $tree;
	 }
}