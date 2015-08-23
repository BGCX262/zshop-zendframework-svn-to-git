<?php
class GoodsPropertyModel extends BaseModel{
    protected $_name;
    
    public function init(){
        $this -> _name = TABLEPRE . 'goods_property';
    }
    
    /**
     * 批量插入商品属性 
     * @param int $goodsId 商品ID
     * @param array $goodsProperty 商品属性数组
     * @return boolean
     * @todo $db -> query()插入成功与否的判断
     */
    public function batchInsertProperty($goodsId, $goodsProperty){
        
        $db = $this -> getDefaultAdapter();
        
        //传入属性非数组
        if(!is_array($goodsProperty) || count($goodsProperty) == 0){
            return false;
        }
        
        //构造批量插入sql
        $goodsId = intval($goodsId);
        foreach($goodsProperty as $propertyId => $propertyValue){
            $properyId = intval($propertyId);
            $propertyValue = iconv_substr($propertyValue, 0, 500, 'utf-8');
            $propertyValue = $db -> quote($propertyValue);
            $propertyList[] = '(null, ' . $goodsId . ', ' . $properyId . ', ' . $propertyValue . ')';  
        }
        $propertySql = implode(', ', $propertyList);
        $insertSql = 'insert into `' . $this -> _name . '` values ' . $propertySql;
        
        //批量插入商品属性
        $result = $db -> query($insertSql);
        return true;
    }
}