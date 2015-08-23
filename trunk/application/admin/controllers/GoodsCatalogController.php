<?php
class Admin_GoodsCatalogController extends Zend_Controller_Action{
    public function init(){
        $this -> goodsCatalogModel = new GoodsCatalogModel();
    }

    //商品分类列表
    public function indexAction(){
        $this -> view -> catalog = $this -> goodsCatalogModel -> getCatTree();
    }
    
    //添加分类显示页
    public function addAction(){
        $this -> view -> catArray = $this -> goodsCatalogModel -> getCatTree();
    }
    
    //分类添加处理
    public function insertAction(){
        
        //处理接收参数
        $pid = intval($this -> _getParam('pid'));
        $catName = iconv_substr($this -> _getParam('catname'), 0, 100, 'utf-8');
        $description = iconv_substr($this -> _getParam('description'), 0, 500, 'utf-8');
        $display = intval($this -> _getParam('display'));
        $order = intval($this -> _getParam('order'));
        
        //赋默认值
        $display !== 0 && $display = 1;
        
        if(empty($catName)){
            Zshop_Message::show($this, '带*号内容必须输入', 'back',2);
        }else{
            $catalog = array(
                        'pid' => $pid, 
                        'catname' => $catName, 
                        'display' => $display, 
                        'description' => $description, 
                        'order' => $order
                       );
            $id = $this -> goodsCatalogModel -> insert($catalog);
            if($id){
                $message = '添加成功';
                $url = 'admin/goodscatalog/index';
            }else{
                $message = '添加失败';
                $url = 'back';
            }
            Zshop_Message::show($this, $message, $url,2);
        }
    }
    
    //商品分类编辑页
    public function editAction(){
        $id = intval($this -> _getParam('id'));
        $currentCat = $this -> goodsCatalogModel -> getone($id);
        $this -> view -> id = $id;
        $this -> view -> cat = $currentCat;
        $this -> view -> catArray = $this -> goodsCatalogModel -> getCatTree();
    }
    
    //商品分类编辑更新
    public function updateAction(){
        //处理接收参数
        $id = intval($this -> _getParam('id'));
        $pid = intval($this -> _getParam('pid'));
        $catName = iconv_substr($this -> _getParam('catname'), 0, 100, 'utf-8');
        $description = iconv_substr($this -> _getParam('description'), 0, 500, 'utf-8');
        $display = intval($this -> _getParam('display'));
        $order = intval($this -> _getParam('order'));
        
        //是否显示赋值
        $display !== 0 && $display = 1;
        
        if(empty($catName)){
            Zshop_Message::show($this, '带*号内容必须输入', 'back',2);
        }else{
            $catalog = array(
                    'pid' => $pid,
                    'catname' => $catName,
                    'display' => $display,
                    'description' => $description,
                    'order' => $order
            );
            $isUpdated = $this -> goodsCatalogModel -> update($catalog, $id);
            if($isUpdated){
                $message = '修改成功';
                $url = 'admin/goodscatalog/index';
            }else{
                $message = '修改失败';
                $url = 'back';
            }
            Zshop_Message::show($this, $message, $url,2);
        }
        
    }
     
    /**
     * 商品分类删除
     * @todo 分类下存在商品不删除
     */
    public function deleteAction(){
        $id = intval($this -> _getParam('id'));
        $this -> goodsCatalogModel -> where = array('pid' => $id);
        $childCatNum = $this -> goodsCatalogModel -> getTotalNums();
        if($childCatNum > 0){
            Zshop_Message::show($this,'该分类下存在子分类，不能删除','back',2);
        }else{
            $isDeleted = $this -> goodsCatalogModel -> delete($id);
            $message = $isDeleted?'删除成功':'删除失败';
            Zshop_Message::show($this, $message, 'admin/goodscatalog/index', 2);
        }
    }
}

?>