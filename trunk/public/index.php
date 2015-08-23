<?php
// Define path to application directory
define ( 'APPLICATION_PATH', realpath ( dirname ( __FILE__ ) . '/../application' ) );

// Define application environment
define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

//数据库表名前缀
define('TABLEPRE', 's_');

//网站根目录
define('ROOT_PATH', str_replace('\\', '/', dirname( __FILE__ ))); 

//图片附件存放路径
define ('GOODS_ATTACH_PATH', ROOT_PATH . '/attach/goodsAttach');

//商品导图存放路径
define ('GOODS_PIC_PATH', ROOT_PATH . '/attach/goodsPic');

//商品相册存放路径
define ('GOODS_ALBUM_PATH', ROOT_PATH . '/attach/goodsAlbum');

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
	realpath(APPLICATION_PATH . '/models'),
    get_include_path()
)));

//enable autoloader class
include 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance() -> setFallbackAutoloader(true);
/** Zend_Application */
//require_once 'Zend/Application.php';

// Create application, bootstrap, and run
/*
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);*/

$params = array ('host'     => '127.0.0.1', 
		         'username' => 'zshop',
		         'password' => 'zshop',
		         'dbname'   => 'zshop');
$db = Zend_Db::factory('PDO_MYSQL', $params);
$db -> query('set names utf8');
Zend_Db_Table::setDefaultAdapter($db);

$front = Zend_Controller_Front::getInstance();
//$front -> setParam("useCaseSensitiveActions",true); //支持controller,action大小写识别
$front -> addModuleDirectory('../application') -> registerPlugin(new Zshop_MyPlugin());
$front -> throwExceptions(true);
$front -> dispatch();

//$application->bootstrap()->run();

