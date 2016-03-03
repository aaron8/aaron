<?php
namespace Aaron\Library;

/**
 * mysql 数据库类
 * @author guomumin <aaron8573@gmail.com>
 *
 */
class Mysql
{
    
    private $_link_id = NULL;
    
    public function __construct($config = [])
    {
        $this->connect($config);
    }
    
    private function connect($config)
    {
        $host = $config['host'].':'.$config['port'];
        
        if ($config['pconnect'])
        {
            $this->_link_id = @mysql_connect($host, $config['user'], $config['password'], $config['pconnect']);
        }else{
            $this->_link_id = @mysql_connect($host, $config['user'], $config['password']);
        }
        
        if (!$this->_link_id) trigger_error('数据库连接失败:['. mysql_errno() . '] ' . mysql_error(), E_USER_ERROR);
        
        if (!@mysql_select_db($config['database'], $this->_link_id))
        {
            trigger_error('选择数据库错误:['. mysql_errno() . '] ' . mysql_error(), E_USER_ERROR);
        }
        
        @mysql_query("set names '". $config['charset'] ."'");
    }
    
    public function select_db($database)
    {
        return mysql_select_db($database, $this->_link_id);
    }
    
    public function query($sql)
    {
        $query = mysql_query($sql, $this->_link_id);
        if (!$query) trigger_error('sql运行错误:['. mysql_errno() . '] ' . mysql_error(), E_USER_ERROR);
        
        return $query;
    }
    
    public function insert($table, $data)
    {
        if (!is_array($data) || count($data) < 1) return false;
        
        $fileds = $values = '';
        $i = 0;
        while (list($k, $v) = each($data))
        {
            if ($i>0)
            {
                $fileds .= ',';
                $values .= ',';
            }
            $fileds .= '`'. $k .'`';
            $values .= '"'. $v .'"';
            $i++;
        }
        
        $sql = 'INSERT INTO '. $table .' ('. $fileds .') VALUES ('. $values .')';
        return $this->query($sql);
    }
    
    public function insertId()
    {
        return mysql_insert_id();
    }
    
    public function update($table, $data, $where = NULL, $operation = [])
    {
        if (!is_array($data) || count($data) < 1) return false;
        $set = '';
        $i = 0;
        foreach ($data as $k=>$v)
        {
            if ($i > 0) $set .= ',';
            
            $set .= '`'.$k.'`="'.$v.'"';
            $i++;
        }
        
        $param = '';
        if(!is_null($where))
        {
            $param = ' WHERE ';
            if (is_array($where))
            {
                $i = 0;
                foreach ($where as $k=>$v)
                {
                    $mark = '=';
                    if ($i > 0) $param .= ' AND ';
                    
                    if (array_key_exists($k, $operation)) $mark = $operation[$k];
                    
                    $v = (gettype($v) == 'string') ? ('"'. $v .'"') : $v;
                    $param .= '`'. $k .'`'. $mark . $v;
                }
                
            }else{
                $param .= $where;
            }
        }
        
        $sql = 'UPDATE '. $table .' SET '. $set . $param;
        return $this->query($sql);
    }
    
    public function delete($table, $where = NULL, $operation = [])
    {
        $param = '';
        if(!is_null($where))
        {
            $param = ' WHERE ';
            if (is_array($where))
            {
                $i = 0;
                foreach ($where as $k=>$v)
                {
                    $mark = '=';
                    if ($i > 0) $param .= ' AND '; 
                    
                    if (array_key_exists($k, $operation)) $mark = $operation[$k];
                    
                    $v = (gettype($v) == 'string') ? ('"'. $v .'"') : $v;
                    $param .= '`'. $k .'`'. $mark . $v;
                }
            }else{
                $param .= $where;
            }
        }
        
        $sql = 'DELETE FROM '. $table .$param;
        return $this->query($sql);
    }
    
    public function resultListArray($sql, $result_type = MYSQL_ASSOC)
    {
        $query = $this->query($sql);
        $rs = [];
        while ($row = mysql_fetch_array($query, $result_type))
        {
            $rs[] = $row;
        }
        return $rs;
    }
    
    public function resultRowArray($sql, $result_type = MYSQL_ASSOC)
    {
        $query = $this->query($sql);
        $rs = mysql_fetch_row($query, $result_type);
        return $rs;
    }
    
    public function __destruct()
    {
        mysql_close($this->_link_id);
    }
    
}