<?php
class GoodsTypeModel extends BaseModel{
    
    protected $_name;  //表名
    
    public function init(){
        $this -> _name = TABLEPRE . 'goods_type';
    }
}