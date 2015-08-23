<?php
/*
 * 功能：获取根目录
 * 作者：罗灿
 * 时间：2011/8/13
 */
    class Zend_View_Helper_RootUrl {
        public function RootUrl() {
            return Zend_Controller_Front::getInstance()->getRequest()->getBaseUrl();
        }
    }
?>