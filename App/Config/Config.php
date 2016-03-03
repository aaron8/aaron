<?php
$config = array();

$config['route'] = array();

//模块状态，true为开启，false为关闭
//$config['route']['module_status'] = true;

//模块
$config['route']['module'] = array('home', 'admin');

//默认模块
$config['route']['default_module'] = 'home';

//默认控制器
$config['route']['default_controller'] = 'index';

//默认方法
$config['route']['default_function'] = 'index';

//路由类型1为默认，2为path格式
$config['route']['type'] = 2;

$config['debug'] = true;

$config['cache'] = [];

//缓存类型，默认file，memcached，redis
$config['cache']['type'] = 'file';

$config['cache']['status'] = true;

return $config;