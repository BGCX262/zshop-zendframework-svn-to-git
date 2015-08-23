<?php
class GoodsTypePropertyModel extends BaseModel{
    protected $_name;
    protected $_primary;
    
    public function init(){
        $this -> _name = TABLEPRE . 'goods_type_property';
        $this -> _primary = 'property_id';
    }
    
    //商品类型属性列表
    public function getPropertyList($typeid, $page, $numsPerPage){
        //联表goods_type查询 
        $select = $this->select()->setIntegrityCheck(false);
        $select -> from($this -> _name . ' as p', array('property_id as id', 'property_name as name'));
        $select -> joinLeft(TABLEPRE . 'goods_type as t', 'p.type_id = t.id', array('type_name'));
        
        //条件
        $select -> where('p.type_id=?',$typeid);
        //分页
        $select -> limitPage($page, $numsPerPage);
        return $this -> fetchAll($select);
    }
    
  
}
?>