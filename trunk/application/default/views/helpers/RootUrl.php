<?php
/*
 * ���ܣ���ȡ��Ŀ¼
 * ���ߣ��޲�
 * ʱ�䣺2011/8/13
 */
    class Zend_View_Helper_RootUrl {
        public function RootUrl() {
            return Zend_Controller_Front::getInstance()->getRequest()->getBaseUrl();
        }
    }
?>