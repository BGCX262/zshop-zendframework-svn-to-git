<?php
class BaseModel extends Zend_Db_Table{
    protected $_name;
    public $where; //精确查询条件数组，键为字段名，值为字段值
    public $like; // 模糊查询条件数组，键为字段名，值为查询关键词
    
    public function init(){
        $this -> where = null;
        $this -> like = null;
    }
    
    /**
     * 
     * @param string $field 检查重复的字段名
     * @param string $str 检查值
     * @return boolean 重复返回true，不重复flase
     */
    public function checkDuplicat($field, $str){
        $select = $this -> select() -> from($this -> _name, 'count(*) as nums')
                                    -> where($field . '=?', $str);
        $rowsNum = $this -> fetchAll($select) -> current() -> nums;
        return $rowsNum? true : false;
    }
    
    /**
    * 根据id获取一条记录
    * @param int $id
    * return zend_db_table_row对象
    */
    public function getOne($id){
        return $this -> find(intval($id)) -> current();
    }
    
    /**
     * 获得符合条件的记录总数
     * 返回记录总数，无记录返回0
     */
    public function getTotalNums(){
        $rowSet = $this -> getList('count(*) as total');
        return $rowSet -> current() -> total;
    }
    
    /**
     * 获得列表
     * @param string or array $field       字段名
     * @param int             $page        页码
     * @param int             $numsPerPage 每页条数
     * @param string          $order       排序方式
     * @return object zend_db_table_rowset 列表对象 
     */
    public function getList($field=null, $page=null, $numsPerPage=null, $order=null){
        $select = $this -> select();
        
        //选择字段
        isset($field) && $select -> from($this->_name, $field);
        //精确查找
        if(is_array($this -> where)){
            foreach($this -> where as $fieldName => $value){
                $select -> where('`' . $fieldName . '`=?', $value);
            }
        }
        //模糊查找
        if(is_array($this -> like)){
            foreach($this -> like as $fieldName => $keyword){
                $select -> where('`' . $fieldName . '` like ?', '%'.$keyword.'%');
            }
        }
        //分页
        if(isset($page) && isset($numsPerPage)){
            $select -> limitPage($page, $numsPerPage);
        }
        //排序
        isset($order) && $select -> order($order);
        
        return $this -> fetchAll($select);
    }
    
    /**
     * 删除ID所在行
     * @param int or array $id
     * @param string $column 字段名
     * @see Zend_Db_Table_Abstract::delete()
     * @return boolean 更新成功返回true,否则false
     */
    public function delete($id, $column='id'){
        $db = $this -> getAdapter();
        $wherePre = $column . " in(?)"; 
        $where = $db -> quoteInto($wherePre, $id);
        $deletedRows = parent::delete($where);
        return $deletedRows ? true : false;
    }
    
    /**
     * 根据数组，更新id所在行数据
     * param array $set 更新数组，下标为字段名，值为字段值
     * param int or array $id 条件值
     * param string $whereField 条件字段名
     * @see Zend_Db_Table_Abstract::update()
     * @return boolean 更新成功返回true,否则false
     */
    public function update(array $set, $id, $whereField = 'id'){
        if(is_array($id)){
            //过滤id值
            $db = $this -> getDefaultAdapter();
            $quoteId = $db -> quoteInto(' in (?)', $id);
            //构造where条件
            $where = $whereField . $quoteId;
            //$where = $whereField . ' in (' . implode(',', $id) . ')'; //用db引用之前写法
        }else{
            $where = $whereField . ' = ' . intval($id);
        }
        $affectedRows = parent::update($set, $where);
        return $affectedRows ? true : false;
    }
}