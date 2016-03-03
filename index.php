<?php
namespace Aaron;
$begin_time = microtime(true);
//站点根目录
define('WEB_ROOT', dirname(__FILE__) .'/');

//系统目录
define('SYSTEM_PATH', WEB_ROOT .'Aaron/');

//站点文件目录
define('APP_PATH', WEB_ROOT .'App/');

//环境变量
$service_environment = isset($_SERVER['AR_EVN']) ? $_SERVER['AR_EVN'] : ''; 

define('ENVIRONMENT', $service_environment);

require_once SYSTEM_PATH.'Aaron.php';

Application::run();

/**
 * debug详细内容
 */
if (Application::$config['debug'])
{
    echo '<table align="center" style="font-size:12px;" cellspacing="0" cellpadding="5" border="1" bordercolor="#2be5e5">';
    
    echo '<tr><td>';
    echo '用时: ' . sprintf('%.9f', microtime(true) - $begin_time) .' 秒';
    echo '</td></tr>';
    
    echo '<tr><td>加载文件:<br>';
    
    $files = get_included_files();
    foreach ($files as $k=>$v)
    {
        echo $v,'<br>';
    }
    echo '</td></tr>';
    
    echo '<tr><td>';
    echo '请求地址：'.$_SERVER['REQUEST_URI'];
    echo '</td></tr>';
    
    echo '<tr><td>';
    echo '请求类型：'.$_SERVER['REQUEST_METHOD'];
    echo '</td></tr>';
    
    echo '<tr><td>';
    echo '请求参数：';
    echo print_r($_REQUEST, true);
    echo '</td></tr>';
    echo '<table>';
}

