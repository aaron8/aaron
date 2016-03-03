<?php
namespace Aaron\Core;

/**
 * url解析类
 * @author Administrator
 * 参数暂时未进行过滤
 */
class Route
{
    /**
     * 访问的模块
     * @var unknown
     */
    public $module;
    
    /**
     * 访问的控制器
     * @var unknown
     */
    public $controller;
    
    /**
     * 访问的方法
     * @var unknown
     */
    public $function;
    
    /**
     * 访问的参数
     * @var unknown
     */
    public $params = array();
    
    /**
     * route配置
     * @var unknown
     */
    public $config;
    
    public function __construct($config = array())
    {
        $this->config = $config;
    }
    
    /**
     * 解析路由
     */
    public function parseUrl()
    {
        switch ($this->config['type'])
        {
            case 1:
                $this->parseUrlDefault();
                break;
        
            case 2:
                $this->parseUrlRestful();
                break;
        
            default:
                $this->parseUrlDefault();
                break;
        }
        
    }
    
    /**
     * 默认url解析地址 
     * /?m=home&c=index&a=index
     */
    public function parseUrlDefault()
    {
        
        $request_uri = parse_url($_SERVER['REQUEST_URI']);
        
        $parse_uri = array();
        
        if (isset($request_uri['query']))
        {
            parse_str(trim(strip_tags($request_uri['query'])), $parse_uri);
        }
        
        $this->module       = isset($parse_uri['m']) ? $parse_uri['m'] : $this->config['default_module'];
        
        $this->controller   = isset($parse_uri['c']) ? $parse_uri['c'] : $this->config['default_controller'];
        
        $this->function     = isset($parse_uri['a']) ? $parse_uri['a'] : $this->config['default_function'];
        
    }
    
    /**
     * restfull 格式
     * 
     */
    public function parseUrlRestful()
    {
        $request_uri = trim(strip_tags($_SERVER['REQUEST_URI']));
        
        $parser_uri = array();
        
        if (!empty($request_uri) && stripos($request_uri, '?')!==false)
        {
            $request_uri = explode('?', $request_uri)[0];
        }
        
        if (!empty($request_uri) && stripos($request_uri, '/')!==false)
        {
            $parser_uri = explode('/', $request_uri);
            array_splice($parser_uri, 0, 1);
        }
        
        $this->module       = (isset($parser_uri[0])&& !empty($parser_uri[0])) ? $parser_uri[0] : $this->config['default_module'];
        
        $this->controller   = (isset($parser_uri[1])&& !empty($parser_uri[1])) ? $parser_uri[1] : $this->config['default_controller'];
        
        $this->function     = (isset($parser_uri[2])&& !empty($parser_uri[2])) ? $parser_uri[2] : $this->config['default_function'];
        
    }
    
}
