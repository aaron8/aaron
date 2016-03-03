<?php
namespace Aaron\Core;

class Db
{
    public $db = NULL;
    
    public function __construct()
    {
        
    }
    
    public function init($config = [])
    {
        $now_config = self::parseConfig($config);
        
        self::connectDb($now_config);
        
        $db_type_name = '\\Aaron\\Library\\'.ucfirst($now_config['dbdriver']);
        
        $this->db = new $db_type_name($now_config);
        return $this->db;
    }
    
    static private function parseConfig($config = [])
    {
        $cfg = [
                'dbdriver' => $config['dbdriver'],
                'user' => $config['user'],
                'password' => $config['password'],
                'host' => $config['host'],
                'port' => $config['port'],
                'database' => $config['database'],
                'charset' => $config['charset'],
                'pconnect' => $config['pconnect']
            ];
        return $cfg;
    }
    
    static private function connectDb($config = [])
    {
        $mysql_file = SYSTEM_PATH . 'Library/Db/' .  ucfirst($config['dbdriver']) . '.php';
        
        if (!is_file($mysql_file)) trigger_error('数据库文件不存在:'.$mysql_file, E_USER_ERROR);
        
        require_once $mysql_file;
    }
    
}