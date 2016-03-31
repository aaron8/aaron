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
        
        return trim(strip_tags($_POST[$key]));
    }
    
    protected function get($key = NULL, $filter = false)
    {
        if (is_null($key)) return $_GET;
    
        return $filter ? trim(strip_tags($_GET[$key])) : $_GET[$key];
    }
    
    protected function view($path = null, $data = [], $boole = false)
    {
        $view = new View();
        
        if ($boole) return $view->view($path, $data);
        
        $view->view($path, $data);
    }
    
    protected function service()
    {
        
    }
    
    
}

?>