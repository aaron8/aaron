<?php
namespace Aaron\Library;
/**
 * mysqli 数据库类
 * @author guomumin <aaron8573@gmail.com>
 *
 */
class Mysqli
{
    
    private $_link_id = NULL;
    
    public function __construct($config = [])
    {
        $this->connect($config);
    }
    
    private function connect($config)
    {
        $this->_link_id = mysqli_init();
        
        if (!mysqli_options($this->_link_id, MYSQLI_INIT_COMMAND, 'SET AUTOCOMMIT = 0')) 
        {
            trigger_error('Setting MYSQLI_INIT_COMMAND failed', E_USER_ERROR);
        }
        
        if (!mysqli_options($this->_link_id, MYSQLI_OPT_CONNECT_TIMEOUT, 5)) 
        {
            trigger_error('Setting MYSQLI_OPT_CONNECT_TIMEOUT failed', E_USER_ERROR);
        }
        
        if (!mysqli_real_connect($this->_link_id, $config['host'], $config['user'], $config['password'], $config['database']))
        {
            trigger_error('数据库连接失败:['. mysqli_connect_errno() .'] '. mysqli_connect_error());
        }
        
        $this->_link_id->query("SET NAMES '". $config['charset'] ."'");
    }
    
    public function select_db($database)
    {
        return mysqli_select_db($this->_link_id, $database);
    }
    
    public function query($sql)
    {
        $result = mysqli_query($this->_link_id, $sql);
        if (!$result) trigger_error('sql运行错误:['. mysqli_connect_errno() . '] ' . mysqli_connect_error(), E_USER_ERROR);
        
        return $result;
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
        return mysqli_insert_id($this->_link_id);
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
        $result = $this->query($sql);
        $rs = [];
        while ($row = mysqli_fetch_array($result, $result_type))
        {
            $rs[] = $row;
        }
        mysqli_free_result($result);
        return $rs;
    }
    
    public function resultRowArray($sql)
    {
        $result = $this->query($sql);
        $rs = mysqli_fetch_row($result);
        mysqli_free_result($result);
        return $rs;
    }
    
    public function __destruct()
    {
        mysqli_close($this->_link_id);
    }
    
}