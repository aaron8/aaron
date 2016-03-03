<?php
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
     * 加载控制器
     * @param string 类名/方法名 $path
     * @param string 
     */
    /*
    protected function load($path, $params=''){
        if (!$path) trigger_error('加载'.$path.'控制器不存在');
        
        $path_arr = explode('/', $path);
        $paramStr = '';
        
        if ($params != ''){
            if (stripos($params, '/') !== FALSE){
                $paramStr = str_replace('/', ',', $params);
            } 
            $paramStr = $params;
        }
        
        $urlArray = array(
            'controller'=> $path_arr[0],
            'action'    => $path_arr[1],
            'params'	=> $paramStr
        );
        Application::routeToCm($urlArray);
        
    }
    */
    /** 
     * 输出模板 
     * @access      public  
     * @return      string 
     */ 
    public function outPut(){ 
        echo $this->outPut; 
    }
}
