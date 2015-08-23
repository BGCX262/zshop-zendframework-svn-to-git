<?php
class GoodsContentModel extends BaseModel{
    protected $_name;
    
    public function init(){
        $this -> _name = TABLEPRE . 'goods_content';
    }

}