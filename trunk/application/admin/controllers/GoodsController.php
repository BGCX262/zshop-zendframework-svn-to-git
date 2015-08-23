<?php
class Admin_GoodsController extends Zend_Controller_Action
{
    public function indexAction(){
        $goods = new GoodsModel();
        //搜索判断
        $Search = $this -> _getParam('search');
        if($Search){
        	//列表搜索条件
        	$catId = $this -> _getParam('catid');
        	$brandId = $this -> _getParam('goods_brand');
        	$recommend = $this -> _getParam('recommend');
        	$onsale = $this -> _getParam('onsale');
        	$goodsName = $this -> _getParam('goods_name');
        	
        	//搜索条件显示
        	$this -> view -> catId = $catId;
        	$this -> view -> brandId = $brandId;
        	$this -> view -> recommend = $recommend;
        	$this -> view -> onsale = $onsale;
        	$this -> view -> goodsName = $goodsName;
        	
        	//商品分类条件
        	$catId == 'all' || $filter['cat_id'] = intval($catId); 
        	//商品品牌
        	$brandId == 'all' || $filter['brand_id'] = intval($brandId);
        	//是否推荐商品
        	$recommend == 'all' || $filter['recommend'] = intval($recommend);
        	//是否上架商品
        	$onsale == 'all' || $filter['is_on_sale'] = intval($onsale);
        	//商品模糊搜索
            empty($goodsName) || $goods -> like = array('goods_name' => $goodsName);
        }else{
            
        }
        //商品未删除
        $filter['id_del'] = 0;
        //过滤条件
        $goods -> where = $filter;
        //总行数
        $totalRows = $goods -> getTotalNums();
        //每页多少行
        $rowsPerPage = 10;
        //总页数
        $totalPage = ceil($totalRows/$rowsPerPage);
        //页码
        $page = intval($this -> _getParam('page'));
        $page = $page<1 ? 1 : ($page > $totalPage ? $totalPage : $page);
        
        $goodsList = $goods -> getList(null, $page, $rowsPerPage, 'id desc');
        $this -> view -> totalPage = $totalPage;
        $this -> view -> page = $page;
        $this -> view -> goodsList = $goodsList;
        
        //分类搜索列表显示
        $goodsCatalogModel = new GoodsCatalogModel();
        $this -> view -> catArray = $goodsCatalogModel -> getCatTree();
        //品牌搜索列表显示
        $goodsBrand = new GoodsBrandModel();
        $this -> view -> goodsBrand =  $goodsBrand -> getList();
    	
    }
    
    //商品添加表单页
    public function addAction()
    {
        $goodsCatalogModel = new GoodsCatalogModel();
        $goodsBrand = new GoodsBrandModel();
        $goodsType = new GoodsTypeModel();
        $this -> view -> goodsBrand =  $goodsBrand -> getList();
        $this -> view -> catArray = $goodsCatalogModel -> getCatTree();
        $this -> view -> goodsType = $goodsType -> getList();
    }
    
    //商品添加
    public function insertAction(){
        //商品添加提示信息
        $notice = '';
        
        //商品导图
        if(!$_FILES['goods_pic']['error']){
            //导图存在
            $goodsPic = $_FILES['goods_pic'];
            $uploadTime = time();
            $datePath = date ("Y/m/d", $uploadTime);
            $fileName = date ("His", $uploadTime) . str_pad ( rand ( 0, 1000 ), 4, '0', STR_PAD_LEFT ) . $goodsPic ['name'];
            $path = GOODS_PIC_PATH . '/' . $datePath . '/' . $fileName;
            //导图上传
            $image = new Zshop_image();
            $up = $image -> uploadFile ($goodsPic, $path);
            if($up){
                $goodsPicSrc = $datePath . '/' . $fileName;
            }else{
                $goodsPicSrc = '';
                $notice .= '<br />商品导图上传出错';
            }
        }else{
            //导图不存在
            $goodsPicSrc = '';
            $notice = '<br />商品导图未上传';
        }
        
        //商品基本页入库
        $goodsName = iconv_substr($this -> _getParam('goods_name'), 0, 100, 'utf-8');
        $catId = intval($this -> _getParam('cat_id'));
        $goodsBrand = intval($this -> _getParam('goods_brand'));
        $shopPrice = sprintf('%.2f', $this -> _getParam('shop_price'));
        $marketPrice = sprintf('%.2f', $this -> _getParam('market_price'));
        $recommend = intval($this -> _getParam('recommend'));
        $sale = intval($this -> _getParam('sale'));
        $stock = intval($this -> _getParam('stock'));
        $typeId = intval($this -> _getParam('goods_type'));
        
        //商品录入信息检查
        if(empty($goodsName) || empty($catId)){
            Zshop_Message::show($this, '商品名称或目录没有输入', 'back', 2);
        }elseif($shopPrice<=0 || $marketPrice<=0 || $shopPrice>9999999 || $marketPrice>99999999){
            Zshop_Message::show($this, '价格输入错误', 'back', 2);
        }else{
            $goodsInfo = array(
                    'goods_name'   => $goodsName,
                    'market_price' => $marketPrice,
                    'shop_price'   => $shopPrice,
                    'cat_id'       => $catId ,
                    'brand_id'     => $goodsBrand, 
                    'type_id'      => $typeId,
                    'goods_pic'    => $goodsPicSrc, 
                    'addtime'      => time(),
                    'recommend'    => $recommend,
                    'is_on_sale'   => $sale, 
                    'id_del'       => 0 ,
                    'stock'        => $stock
                );
            $goods = new GoodsModel();
            $goodsId = $goods -> insert($goodsInfo);
          
            if($goodsId){
                //商品详细描述
                $goodsDescription = $this -> _getParam('description');
                if(!empty($goodsDescription)){
                    $goodsAttach = $this -> _getParam('attach');
                    if(!empty($goodsAttach)){
                        
                        //描述附件图片地址替换
                        $rootUrl = Zend_Controller_Front::getInstance() -> getBaseUrl();
                        $attachDir = $rootUrl . str_replace(ROOT_PATH, '', GOODS_ATTACH_PATH) . '/';
                        foreach($goodsAttach as $attachId => $attachSrc){
                            $goodsDescription = str_replace($attachDir . $attachSrc, '[img]' . $attachSrc . '[/img]', $goodsDescription);
                        }
                        
                        //描述附件商品ID替换
                        foreach($goodsAttach as $id => $src){
                            $attachIdArr[] = $id;
                        }
                        $set = array('goods_id' => $goodsId);
                        $goodsAttach = new GoodsAttachModel();
                        $goodsAttach -> update($set, $attachIdArr);
                    }
                    
                    //描述添加
                    $description = array(
                            'goods_id'    => $goodsId,
                            'description' => $goodsDescription
                          );
                    $goodsContent = new GoodsContentModel();
                    $goodsContent -> insert($description);
                }else{
                    $notice .= '<br />商品描述未填写';
                }
                
                //商品类型属性
                $goodsPropertyList = $this->_getParam('plist');
                //类型属性不空则添加
                if(!empty($goodsPropertyList) && is_array($goodsPropertyList)){
                    $goodsProperty = new GoodsPropertyModel();
                    $goodsProperty -> batchInsertProperty($goodsId, $goodsPropertyList);
                }
                
                //商品相册
                $albumPicList = $_FILES['goods_img'];
                if(!empty($albumPicList) && is_array($albumPicList)){
                    
                    $picUploadError = false; //图片上传错误标识
                    $picInsertError = false; //图片插入数据库错误标识
                    
                    //遍历相册文件
                    foreach($albumPicList['error'] as $picIndex => $error){
                        if($error == 0){
                            //构造单个相册图片
                            $albumPic['name'] = $albumPicList['name'][$picIndex];
                            $albumPic['tmp_name'] = $albumPicList['tmp_name'][$picIndex];
                            $albumPic['type'] = $albumPicList['type'][$picIndex];
                            $albumPic['size'] = $albumPicList['size'][$picIndex];
                            $albumPic['error'] = 0;
                            
                            //生成相册图片路径
                            $picUploadTime = time();
                            $picDatePath = date ("Y/m/d", $picUploadTime);
                            $picFileName = date ("His", $picUploadTime) . str_pad ( rand ( 0, 1000 ), 4, '0', STR_PAD_LEFT ) . $albumPic['name'];
                            $picPath = GOODS_ALBUM_PATH . '/' . $picDatePath . '/' . $picFileName;
                            
                            //上传相册图片
                            isset($image) || $image = new Zshop_image();
                            $picUploaded = $image -> uploadFile ($albumPic, $picPath);
                            
                            //相册图片信息入库
                            if($picUploaded == true){
                                $picInfo = array(
                                        'goods_id'    => $goodsId,
                                        'imgsrc'      => $picDatePath . '/' . $picFileName,
                                        'imgsize'     => round($albumPic['size']/1024),
                                        'oldname'     => $albumPic['name'],
                                        'upload_time' => $picUploadTime
                                );
                                $goodsPhoto = new GoodsPhotoModel();
                                $goodsPhoto -> insert($picInfo) || $picInsertError = true;
                                
                            }else{
                                $error != 4 && $picUploadError = true;  //未选择图片不计错误
                            }
                            unset($albumPic);
                        }
                    }
                    
                    //相册错误提示
                    $picUploadError && $notice .= '<br />商品相册上传出错';
                    $picInsertError && $notice .= '<br />商品相册插入数据库出错';
                }
            }
            
            if($goodsId){
                $alertTime = empty($notice) ? 2 : 5; //如有提示，跳转页面显示5秒
                Zshop_Message::show($this, '添加商品成功' . $notice, 'admin/goods/add', $alertTime);
            }else{
                Zshop_Message::show($this, '添加商品失败', 'back', 2);
            }
        }
    }
    
    //商品删除
    public function deleteAction(){
        $goodsId = intval($this -> _getParam('id'));
        $goodsIdArr = $this -> _getParam('check');
        $goods = new GoodsModel();
        $set = array('id_del' => 1);
        //判断提交来路
        if($goodsId){
            $isUpdated = $goods -> update($set, $goodsId);
            $message = $isUpdated ? '删除成功' : '删除失败';
        }elseif(is_array($goodsIdArr)){
            $goodsIdArr = array_filter($goodsIdArr);
            if(count($goodsIdArr) > 0){
                $isUpdated = $goods -> update($set, $goodsIdArr);
                $message = $isUpdated ? '删除成功' : '删除失败';
            }else{
                $message = '非法提交';
            }
        }else{
            $message = '未选择商品';
        }
        Zshop_Message::show($this, $message, 'admin/goods/index', 2); 
    }
    
    //批量推荐商品
    public function recommendAction(){
        $goodsId = $this -> _getParam('check');
        $goodsId = array_filter($goodsId); //过滤空值
        if(is_array($goodsId) && count($goodsId)>0){
            $goods = new GoodsModel();
            $isUpdated = $goods -> update(array('recommend' => 1), $goodsId);
            $message = $isUpdated ? '设置推荐成功' : '设置推荐失败';
            Zshop_Message::show($this, $message, 'admin/goods/index', 2);
        }else{
            Zshop_Message::show($this, '未选择商品', 'admin/goods/index', 2);
        }
    }
    
    //批量取消商品推荐
    public function unrecommendAction(){
        $goodsId = $this -> _getParam('check');
        $goodsId = array_filter($goodsId); //过滤空值
        if(is_array($goodsId) && count($goodsId)>0){
            $goods = new GoodsModel();
            $isUpdated = $goods -> update(array('recommend' => 0), $goodsId);
            $message = $isUpdated ? '取消推荐成功' : '取消推荐失败';
            Zshop_Message::show($this, $message, 'admin/goods/index', 2);
        }else{
            Zshop_Message::show($this, '未选择商品', 'admin/goods/index', 2);
        }
    }
    
    //批量上架商品
    public function saleAction(){
        $goodsId = $this -> _getParam('check');
        $goodsId = array_filter($goodsId); //过滤空值
        if(is_array($goodsId) && count($goodsId)>0){
            $goods = new GoodsModel();
            $isUpdated = $goods -> update(array('is_on_sale' => 1), $goodsId);
            $message = $isUpdated ? '上架成功' : '上架失败';
            Zshop_Message::show($this, $message, 'admin/goods/index', 2);
        }else{
            Zshop_Message::show($this, '未选择商品', 'admin/goods/index', 2);
        }
    }
    
    //批量下架商品
    public function outsaleAction(){
        $goodsId = $this -> _getParam('check');
        $goodsId = array_filter($goodsId); //过滤空值
        if(is_array($goodsId) && count($goodsId)>0){
            $goods = new GoodsModel();
            $isUpdated = $goods -> update(array('is_on_sale' => 0), $goodsId);
            $message = $isUpdated ? '下架成功' : '下架失败';
            Zshop_Message::show($this, $message, 'admin/goods/index', 2);
        }else{
            Zshop_Message::show($this, '未选择商品', 'admin/goods/index', 2);
        }
    }

    //商品详细描述图片附件上传
	public function upattachAction(){
	    //图片处理类
	    $image = new Zshop_image();
	    //上传图片处理
	    $file = $_FILES['attach'];
	    $uploadTime = time();
        $datePath = date ("Y/m/d", $uploadTime);
        $fileName = date ("His", $uploadTime) . str_pad ( rand ( 0, 1000 ), 4, '0', STR_PAD_LEFT ) . $file ['name'];
        $path = GOODS_ATTACH_PATH . '/' . $datePath . '/' . $fileName;
        $up = $image -> uploadFile ($file, $path);
        if ($up) {
            $fileInfo = array(
                        'goods_id'    => 0,
                        'imgsrc'      => $datePath . '/' . $fileName,
                        'imgsize'     => round($file ['size']/1024),
                        'oldname'     => $file ['name'],
                        'upload_time' => $uploadTime
                    );
            $goodsAttach = new GoodsAttachModel();
            $attachId = $goodsAttach -> insert( $fileInfo );
            $this -> view -> id = $attachId;
            $this -> view -> path = $datePath . '/' . $fileName;
            $this -> view -> src = str_replace(ROOT_PATH, '', GOODS_ATTACH_PATH) . '/' . $datePath. '/' . $fileName ;
        }
	}


}

?>