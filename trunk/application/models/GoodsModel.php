<?php
class GoodsModel extends BaseModel{
    protected $_name;
    
    public function init(){
        $this -> _name = TABLEPRE . 'goods_info';
    }
}