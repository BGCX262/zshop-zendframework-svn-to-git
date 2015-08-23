<?php
class Admin_GoodsTypePropertyController extends Zend_Controller_Action{
    protected $goodsPropertyModel;
    public function init(){
        $this -> goodsPropertyModel = new GoodsTypePropertyModel();
    }
    
    //商品类型属性列表
    public function indexAction(){
        $typeId = intval($this -> _getParam('typeId'));
        //设置总行数条件
        $this -> goodsPropertyModel -> where =array('type_id' => $typeId);
        //获取总行数
        $totalRows = $this -> goodsPropertyModel -> getTotalNums();
        //每页多少行
        $rowsPerPage = 10;
        //总页数
        $totalPage = ceil($totalRows/$rowsPerPage);
        //页码
        $page = intval($this -> _getParam('page'));
        $page = $page<1 ? 1 : ($page > $totalPage ? $totalPage : $page);
        
        //取得属性列表
        $propertyList = $this -> goodsPropertyModel -> getPropertyList($typeId, $page, $rowsPerPage);
        $this -> view -> totalPage  = $totalPage;
        $this -> view -> page  = $page;
        $this -> view -> propertyList = $propertyList;

    }
 
    //编辑商品类型属性
    public function editAction(){
        $propertyId = intval($this -> _getParam('propertyId'));
        $propertyName = $this -> goodsPropertyModel -> getOne($propertyId)-> property_name;
        $this -> view -> propertyName = $propertyName;
        
    }
    
    //编辑商品类型属性处理
    public function updateAction(){
        //获取商品属性ID、属性名称
        $propertyId = intval($this -> _getParam('propertyId'));
        $propertyName = iconv_substr($this -> _getParam('propertyName'), 0, 100, 'utf-8');
        if(empty($propertyName)){
            Zshop_Message::show($this, '输入不能为空', 'back', 2);
        }else{
            //更改商品属性名称
            $set = array('property_name' => $propertyName);
            $isUpdated = $this -> goodsPropertyModel -> update($set, $propertyId, 'property_id');
            if($isUpdated){
                $message = '更新成功';
                $typeId = $this -> goodsPropertyModel -> getOne($propertyId) -> type_id;
                $backUrl = 'admin/goodstypeproperty/index/typeId/' . $typeId;
            }else{
                $message = '更新失败';
                $backUrl = 'back';
            }
            Zshop_Message::show($this, $message, $backUrl, 2);
        }
       
    }
    
    //删除商品类型属性
    public function deleteAction(){
        $propertyId = intval($this -> _getParam('propertyId'));
        if(empty($propertyId)){
            $propertyId = $this -> getRequest() -> getPost('check');
        }
        $typeId = intval($this -> _getParam('typeId'));
        //删除属性
        $isDeleted = $this -> goodsPropertyModel -> delete($propertyId, 'property_id');
        //提示信息
        $backUrl = 'admin/goodstypeproperty/index/typeId/' . $typeId;
        $message = $isDeleted ? '删除成功' : '删除失败';
        Zshop_Message::show($this, $message, $backUrl, 2);
    }

    //添加商品类型属性
    public function addAction(){
        
    }
    
    //添加商品类型属性处理
    public function insertAction(){
        $typeId = intval($this -> _getParam('typeId'));
        $propertyName = iconv_substr($this -> _getParam('propertyName'), 0, 100, 'utf-8');
        $nameExists = $this -> goodsPropertyModel -> checkDuplicat('property_name', $propertyName); 
        if ($nameExists) {
            Zshop_Message::show($this, $propertyName.'已经存在', 'back', 2);
        }elseif(empty($typeId) || empty($propertyName)){
            Zshop_Message::show($this, '输入不能为空', 'back', 2);
        }else{
            //增加商品类型属性
            $set = array(
                    'type_id'   => $typeId, 
                    'property_name' => $propertyName
                    );
            $isInserted = $this -> goodsPropertyModel -> insert($set);
            if($isInserted){
                $message = '添加成功';
                $backUrl = 'admin/goodstypeproperty/index/typeId/' . $typeId;
            }else{
                $message = '添加失败';
                $backUrl = 'back';
            }
            Zshop_Message::show($this, $message, $backUrl, 2);
        }
    }
    
    //处理商品添加页商品类型属性ajax请求
    public function goodspropertyAction(){
        $typeId = intval($this -> _getParam('typeId'));
        $goodsProperty = new GoodsTypePropertyModel();
        $goodsProperty -> where = array('type_id' => $typeId);
        $propertyList = $goodsProperty -> getList(array('property_id', 'property_name')) -> toarray();
        echo json_encode($propertyList);
        $this->_helper->viewRenderer->setNoRender(); 
    } 
}

?>