<?php
/**
 * view类
 * @author guomumin <aaron8573@gmail.com>
 *
 */
namespace Aaron\Core;

use Aaron\Application;
class View
{
    public $templateName = null; 
    public $viewPath = null;
    public $data = array(); 
    public $outPut = null; 
    
    private function init($templateName,$data = []) { 
        $this->templateName = $templateName; 
        $this->data = $data; 
        $this->fetch(); 
    }
    
    /** 
     * 加载模板文件 
     * @access      public 
     * @param       string  $file 
     */ 
    private function fetch() { 
        $path = APP_PATH. Application::$module;
        $view_file = $path . '/View/' . $this->templateName . '.php';
        if (file_exists($view_file)) {
            extract($this->data);
            ob_start(); 
            include $view_file; 
            $content = ob_get_contents(); 
            ob_end_clean(); 
            $this->outPut =  $content;
        } else { 
            trigger_error('加载 ' . $view_file . ' 模板不存在'); 
        } 
    } 
    
	/** 
     * 加载模板文件 
     * @access      final   protect 
     * @param       string  $path   模板路径 
     * @return      string  模板字符串 
     */
    final public function view($path,$data = array()){
        $this->init($path,$data);
        $this->outPut();
    }
    
    /** 
     * 输出模板 
     * @access      public  
     * @return      string 
     */ 
    public function outPut(){ 
        echo $this->outPut; 
    }
}
