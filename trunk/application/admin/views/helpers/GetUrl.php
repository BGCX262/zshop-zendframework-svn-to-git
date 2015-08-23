<?php
class Zend_View_Helper_GetUrl {
    /**
     * @return 获得当前视图的链接，不包括传递的参数
     */
    public function GetUrl() {
        $front = Zend_Controller_Front::getInstance();
        $url = $front -> getbaseurl() . '/' . 
               $front -> getRequest() -> getModuleName() . '/' .
               $front -> getRequest() -> getControllerName() . '/' .
               $front -> getRequest() -> getActionName();
        
        $params = $front -> getRequest() -> getParams();
        foreach($params as $key => $value){
            if(!in_array(strtolower($key),array('module', 'controller', 'action', 'page'))){
                $url .= '/' . $key . '/' .$value;
            }
        }
        return $url;
    }
}