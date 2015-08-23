<?php
class Admin_GoodsBrandController extends Zend_Controller_Action{
    protected $_name;
    protected $goodsBrand;
    
    public function init(){
        $this -> _name = TABLEPRE . 'goods_brand';
        $this -> goodsBrand = new GoodsBrandModel();
    }

    //品牌列表
    public function indexAction(){ 
        //搜索
        $keyword = $this -> _getParam('brandname');
        empty($keyword) || $search['brand_name'] = $keyword;
        $this -> goodsBrand -> like =$search;
        
        //总行数
        $totalRows = $this -> goodsBrand -> getTotalNums();
        //每页多少行
        $rowsPerPage = 2;
        //总页数
        $totalPage = ceil($totalRows/$rowsPerPage);
        
        //页码
        $page = intval($this -> _getParam('page'));
        $page = $page<1 ? 1 : ($page > $totalPage ? $totalPage : $page);
        
        $brandList = $this -> goodsBrand -> getList(null, $page, $rowsPerPage, 'id');
        $this -> view -> totalPage = $totalPage;
        $this -> view -> page = $page;
        $this -> view -> brandList = $brandList;
        $this -> view -> url = $this -> _helper ->url('index','goodsbrand','admin', array('page' => $page)); //??
        $this -> getRequest()->getActionName(); //??
    }
    
    public function addAction(){
        
    }
    
    //品牌添加处理
    public function insertAction(){
        //获得传入参数
        $name = $this -> _getParam('name');
        $description = $this -> _getParam('description');
        $orders = intval($this -> _getParam('orders'));
        
        //检测输入后插入数据库
        if(empty($name) || empty($description)){
            Zshop_Message::show($this, '带*号内容必须输入', 'back',2);
        }elseif($this -> goodsBrand -> checkDuplicat('brand_name', $name)){
            Zshop_Message::show($this, '品牌名称已存在', 'back',2);
        }else{
            $brand = array(
                    'brand_name'  => iconv_substr($name,0,100,'utf-8'),
                    'description' => iconv_substr(nl2br($description),0, 500 ,'utf-8'),
                    'orders'      => intval($orders)
            );
            $id = $this -> goodsBrand -> insert($brand);
            if($id){
                $message = '添加成功';
                $url = 'admin/goodsbrand/add';
            }else{
                $message = '添加失败';
                $url = 'back';
            }
            Zshop_Message::show($this, $message, $url,2);
        }
    }
    
    //删除品牌
    public function deleteAction(){
        $id = $this -> getRequest() -> get('id');
        if(empty($id)){
            $id = $this -> getRequest() -> getPost('check');
        }
        $isDeleted = $this -> goodsBrand -> delete($id);
        $message = $isDeleted ? '删除成功' : '删除失败';
        zshop_message::show($this, $message, 'admin/goodsbrand/index',2);
    }
    
    //品牌编辑页
    public function editAction(){
        $id = $this -> _getParam('id');
        is_numeric($id) && $brand = $this -> goodsBrand -> getOne($id);
        $this -> view -> brand = $brand;
        
    }
    
    //品牌编辑处理
    public function updateAction(){
        //获取传入参数
        $id = $this -> _getParam('id');
        $name = $this -> _getParam('name');
        $description = $this -> _getParam('description');
        $orders = $this -> _getParam('orders');
        
        //判断传入参数，并提交
        if(empty($name) || empty($description)){
            Zshop_Message::show($this, '带*号内容必须输入', 'back',2);
        }else{
            $brand = array(
                    'brand_name'  => iconv_substr($name,0,100,'utf-8'),
                    'description' => iconv_substr(nl2br($description),0, 500 ,'utf-8'),
                    'orders'      => intval($orders)
            );
            $isUpdated = $this -> goodsBrand -> update($brand, $id);
            if($isUpdated){
                $message = '修改成功';
                $url = 'admin/goodsbrand/index';
            }else{
                $message = '修改失败';
                $url = 'back';
            }
            Zshop_Message::show($this, $message, $url, 2);
        }
    }
}
?>
