<?php
/**
 *
 * @author guomumin <aaron8573@gmail.com>
 *
 */
namespace Aaron\Core;

use Aaron;
class Controller
{
    
    public function __construct(){}
    
    protected function post($key = NULL, $filter = false)
    {
        if (is_null($key)) return $_POST;
        
        if (!isset($_POST[$key])) return '';
        
        return ($filter) ? trim(strip_tags($_POST[$key])) : $_POST[$key];
    }
    
    protected function get($key = NULL, $filter = false)
    {
        if (is_null($key)) return $_GET;
        
        if (!isset($_GET[$key])) return '';
        
        return $filter ? trim(strip_tags($_GET[$key])) : $_GET[$key];
    }
    
    protected function request($key = NULL, $filter = false)
    {
        if (is_null($key)) return $_REQUEST;
        
        if (!isset($_REQUEST[$key])) return '';
        
        return $filter ? trim(strip_tags($_REQUEST[$key])) : $_REQUEST[$key];
    }
    
    protected function view($path = null, $data = [], $boole = false)
    {
        if (is_null($path)) return ;
        
        $view = new View();
        
        if ($boole) return $view->view($path, $data);
        
        $view->view($path, $data);
    }
    
    protected function service()
    {
        
    }
    
    
}

?>