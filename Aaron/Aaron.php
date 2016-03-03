<?php
namespace Aaron;

use Aaron\Core\Route;
final class Application
{
    
    static public $config = array();
    
    static public $namespace = __NAMESPACE__;
    
    static public $module = null;
    
    static public function run()
    {
        //加载配置文件
        self::$config = self::getConfig();
        
        //加载核心文件
        self::coreLoad();
        
        //路由解析
        self::routeParse();
        
    }
    
    /**
     * 获取配置文件内容
     * @param unknown $config_name
     */
    static public function getConfig($config_name = 'Config')
    {
        $config_name = ucfirst($config_name);
        if (ENVIRONMENT != '') $config_name = ENVIRONMENT.'/'.$config_name;
        
        $config_path = APP_PATH. 'Config/'. $config_name .'.php';
        
        if (!is_file($config_path)) trigger_error('文件不存在:'. $config_path, E_USER_ERROR);
        
        return require_once $config_path;
    }
    
    /**
     * 自动加载核心文件
     * @throws \Exception
     */
    static private function coreLoad()
    {
        $load_array = array(
                            'common' => array('function'),
                            'core' => array('controller', 'model', 'service', 'view', 'route', 'db'),
                            'library' => array(),
                        );
        
        foreach ($load_array as $key => $vals)
        {
            foreach ($vals as $item)
            {
                $file = SYSTEM_PATH . ucfirst($key) .'/'. ucfirst($item).'.php';
                
                if (!is_file($file)) exit(trigger_error('文件不存在:'. $file, E_USER_ERROR));
                
                require_once $file;
            }
        }
    }
    
    /**
     * url解析
     */
    static private function routeParse()
    {
        $route = new Route(self::$config['route']);
        
        $route->parseUrl();
        
        $controller = ucfirstStr($route->controller);
        
        $function = ucfirstStr($route->function);
        
        self::$module = ucfirstStr($route->module);
        
        $file = APP_PATH . self::$module .'/Controller/'. $controller .'.php';
        
        if (!is_file($file)) exit(trigger_error('文件不存在:'. $file, E_USER_ERROR));
        
        require_once $file;
        
        $class_name = '\\'. self::$namespace .'\\'.$controller;
        
        $class = new $class_name();
        
        return $class->$function();
    }
    
}

?>