<?php
/**
 *
 * @author guomumin <aaron8573@gmail.com>
 *
 */
namespace Aaron;
/**
 * 主要公共方法在这里
 */

/**
 * 首字母绝对大写
 * @param string $str
 * @return string
 */
function ucfirstStr($str = null)
{
    if (is_null($str)) return $str;
    return ucfirst(strtolower($str));
}

/**
 * ajax请求返回结果
 * @param string $rs
 * @param string $message
 * @param string $data
 */
function ajaxResponse($rs = true, $message = null, $data = null)
{
    $rs_respose = ['result' => '', 'message' => '', 'data' => []];
    if ($rs)
    {
        $rs_respose['result'] = 'success';
        $rs_respose['message'] = '操作成功!';
    }else{
        $rs_respose['result'] = 'error';
        $rs_respose['message'] = '操作失败!';
    }
    
    if (!empty($message))
    {
        $rs_respose['message'] = $message;
    }
    
    if (!empty($data))
    {
        $rs_respose['data'] = $data;
    }
    
    header('Content-type: application/json');
    exit(json_encode($rs_respose, JSON_UNESCAPED_UNICODE));
}

/**
 * 发送curl
 * urt8编码写死的
 * @param 发送url $url
 * @param 发送数据 array() $data
 * @return unknown
 */
function curlSend($url, $data){
    $headers = array(
            "POST 1 HTTP/1.0",
            "Content-type: application/json;charset=UTF-8",
            "Accept: application/json",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: \"run\"",
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $return = curl_exec($ch);
    curl_close($ch);
    return $return;
}

/**
 * 获取随机字符串
 * @param $length   长度
 * @param $type     类型 1为纯数字，2为纯字母，3为数字字母结合，其他为大小写混合
 * @return string
 */
function randNum($length = 6, $type = 1)
{
    switch ($type)
    {
        case 1:
            $str = '1234567890';
            break;
            
        case 2:
            $str = 'abcdefghijklmnopqrstuvwxyz';
            break;
            
        case 3:
            $str = '1234567890abcdefghijklmnopqrstuvwxyz';
            break;
            
        default:
            $str = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            break;
    }
    
    $max = strlen($str) - 1;
    
    mt_srand((double) microtime() * 1000000);
    
    $result = '';
    for ($i = 0; $i < $length; $i ++) {
        $result .= $str[mt_rand(0, $max)];
    }
    
    return $result;
}

/**
 * 是否ajax
 * @return boolean
 */
function isAjax() {
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
        return TRUE;
    }else{
        return FALSE;
    }
}

/**
 * 是否post
 * @return boolean
 */
function isPost() {
    if($_SERVER['REQUEST_METHOD']=='POST'){
        return TRUE;
    }else {
        return FALSE;
    }
}

/**
 * 加载模型
 * @param unknown $model
 * @return unknown
 */
function loadModel($model)
{
    $model_name = ucfirst($model). '_Model';
    
    $model_path = APP_PATH. 'Model/'. $model_name .'.php';
    
    if (!is_file($model_path)) trigger_error('文件不存在:'.$model_path, E_USER_ERROR);
    
    require_once $model_path;
    
    $class_name = '\\'. Application::$namespace .'\\'.$model_name;
    return new $class_name();
}

/**
 * 加载library库类
 * @param unknown $library
 * @return unknown
 */
function loadLibrary($library, $options = [])
{
    $lib_name = ucfirst($library);
    
    $lib_path = APP_PATH . 'Library/'. $lib_name .'.php';
    
    if(!is_file($lib_path))
    {
        $lib_path = SYSTEM_PATH . 'Library/'. $lib_name .'.php';
    }
    
    if (!is_file($lib_path)) trigger_error('文件不存在:'.$lib_path, E_USER_ERROR);
    
    require_once $lib_path;
    
    $class_name = '\\'. Application::$namespace .'\\'.$lib_name;
    
    if (empty($options)) return new $class_name();
    
    return new $class_name($options);
}

/**
 * 加载service
 * @param unknown $service
 * @return unknown
 */
function loadService($service, $options = [])
{
    $service_name = ucfirst($service);
    
    $service_path = APP_PATH. 'Service/'. $service_name .'.php';
    
    if (!is_file($service_path)) trigger_error('文件不存在:'.$service_path, E_USER_ERROR);
    
    require_once $service_path;
    
    $class_name = '\\'. Application::$namespace .'\\'.$service_name;
    
    if (empty($options)) return new $class_name();
    
    return new $class_name($options);
}

/**
 * url跳转
 * @param string $uri
 */
function redirect($uri = '')
{
    if (empty($uri))
    {
        $uri = '/';
    }elseif (stripos($uri, 'http://') !== FALSE) {
        
    }elseif (!stripos($uri, Application::$module)) {
        $uri = '/'. Application::$module. '/'. $uri;
    }else{
        $uri = '/'. $uri;
    }
    
    header("Location:$uri");
    exit();
}

function is_login($key = NULL, $module = '')
{
    
}