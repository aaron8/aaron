<?php
/**
 *
 * @author guomumin <aaron8573@gmail.com>
 *
 */
namespace Aaron\Core;

use Aaron\Application;
class Controller
{
    
    
    public function __construct()
    {
        
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