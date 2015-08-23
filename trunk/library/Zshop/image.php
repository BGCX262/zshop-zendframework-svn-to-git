<?php
class Zshop_image{
    //图片操作类
   
    //按路径建立文件夹
    public function createPath($path){
        //将正斜线替换为反斜线,兼容win
        $path = str_replace('\\', '/', $path);
        $dirname=explode('/', $path);
        //删除数组中的空值,如路径传入为/cms的情况
        $dirname=array_filter($dirname);
        //循环创建文件夹
        foreach($dirname as $val){
            $dir.=$val . '/';
            if(!is_dir($dir)){
                if(!mkdir($dir)){
                    return false;
                }
            }
        }
        return true;
    }
    
    //上传图片文件
    public function uploadFile($file,$filepath){
        if(empty($file['tmp_name']) || file_exists($filepath) || $file['error']>0){
            return false;
        }
        if(!($file['type']=='image/gif' || $file['type']=='image/jpeg' || $file['type']=='image/pjpeg' || $file['type']=='image/png')){
            return false;
        }
        //创建目录
        $dir=dirname($filepath);
        if(!is_dir($dir)){
            $this -> createPath($dir);
        }
        return move_uploaded_file($file['tmp_name'],$filepath) ? true : false;
        
    }
    
    
}