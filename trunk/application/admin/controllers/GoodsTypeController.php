<?php
class Admin_GoodsTypeController extends Zend_Controller_Action{
    protected $goodsTypeModel;
    
    public function init(){
        $this -> goodsTypeModel = new GoodsTypeModel(); 
    }
    
    //类型列表
    public function indexAction(){
        //总行数
        $totalRows = $this -> goodsTypeModel -> getTotalNums();
        //每页多少行
        $rowsPerPage = 10;
        //总页数
        $totalPage = ceil($totalRows/$rowsPerPage);
        
        //页码
        $page = intval($this -> _getParam('page'));
        $page = $page<1 ? 1 : ($page > $totalPage ? $totalPage : $page);
        
        $this -> view -> goodsType = $this -> goodsTypeModel -> getList(null, $page, $rowsPerPage,'id');
        $this -> view -> page = $page;
        $this -> view -> totalPage = $totalPage;
    }
    
    //商品类型添加
    public function addAction(){
        
    }
    
    //商品类型添加处理
    public function insertAction(){
        //获取POST值
        $typeName = iconv_substr($this -> _getParam('typename'), 0, 100, 'utf-8');
        $propertyList = trim($this -> _getParam('propertylist'));
        
        if(empty($typeName) || empty($propertyList)){
            Zshop_Message::show($this, '请输入完整信息', 'back', 2);
        }else{
            //商品类型名称
            $type = array('type_name' => $typeName); 
            //插入商品类型表
            $typeId = $this -> goodsTypeModel -> insert($type); 
            
            //构造商品类型属性数组
            $property['type_id'] = $typeId;
            
            $goodsTypeProperty = new GoodsTypePropertyModel();
            //商品类型属性名称分割
            $propertyList = array_unique(explode("\r\n", $propertyList));
            //构造商品类型属性数组
            foreach($propertyList as $propertyName){
                if(!empty($propertyName)){
                    $property['property_name'] = iconv_substr($propertyName, 0, 100, 'utf-8');
                    $propertyID[] = $goodsTypeProperty -> insert($property); //插入商品类型属性表
                }
            }
            
            //检查插入是否失败
            $error = false;
            foreach($propertyID as $value){
                if(empty($value)){
                    $error = true;
                    break;
                }
            }

            if((!$error) && $typeId){
                $message = '添加成功';
                $backUrl = 'admin/goodstype/index';
            }else{
                $message = '添加失败';
                $backUrl = 'back';
            }
            Zshop_Message::show($this, $message, $backUrl, 2);
        }
    }
    
    //商品类型修改
    public function editAction(){
        $typeId = intval($this -> _getParam('id'));
        //类型名称
        $typeName = $this -> goodsTypeModel -> find($typeId) -> current() -> type_name;
        $this -> view -> typeName = $typeName;
    }
    
    // 商品类型修改处理
    public function updateAction(){
        $typeId = intval($this -> _getParam('id'));
        $typeName = $this -> _getParam('typename');
        
        //检查输入
        if(empty($typeId) || empty($typeName)){
            Zshop_Message::show($this, '类型名称不能为空', 'back', 2);
        }else{
            //更新
            $set = array('type_name' => $typeName);
            $isUpdated = $this -> goodsTypeModel -> update($set, $typeId);
            //提示
            if($isUpdated){
                $message = '修改成功';
                $backUrl = 'admin/goodstype/index';
            }else{
                $message = '修改失败';
                $backUrl = 'back';
            }
            Zshop_Message::show($this, $message, $backUrl, 2);
        }
    }

    public function deleteAction(){
        $typeId = intval($this -> _getParam('id'));
        if(empty($typeId)){
            Zshop_Message::show($this, '类型ID为空', 'back', 2);
        }else{
            //删除商品类型
            $typeDeleted = $this -> goodsTypeModel -> delete($typeId);
            //删除商品类型属性
            $goodsTypeProperty = new GoodsTypePropertyModel();
            $propertyDeleted = $goodsTypeProperty -> delete($typeId, 'type_id');
            //跳转提示
            $message = ($typeDeleted && $propertyDeleted) ? '删除成功' : '删除失败';
            Zshop_Message::show($this, $message, 'admin/goodstype/index', 2);
        }
    }
}

?>