<?php
class GoodsCatalogModel extends BaseModel{
    protected $_name;  //表名

    public function init(){
        $this -> _name = TABLEPRE . 'goods_catalog';
    }
    
    //获取商品分类目录树
    public function getCatTree(){
        $catArray=$this -> getList(null,null,null,'order desc') -> toArray();
        return $this -> sort_cat($catArray);
    }
    
    /**
     * 树状分类排序
     * @todo 位置
     * @param unknown_type $data
     * @param unknown_type $pid
     * @param unknown_type $depth
     * @param unknown_type $tree
     * @return array
     */
    public function sort_cat($data,$pid=0,$depth=0,&$tree=array()){
        foreach($data as $val){
            if($val['pid']==$pid){
                $val['depth']=$depth;
                $tree[]=$val;
                $this -> sort_cat($data,$val['id'],$depth+1,$tree);
            }
        }
        return $tree;
    }

}