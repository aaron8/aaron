<?php
namespace Aaron\Core;

use Aaron;
use Aaron\Application;
/**
 * 数据库模型
 * @author guomumin
 * 
 */
class Model
{
    /**
     * 
     * @var 数据库配置
     */
    public $db_config;
    
    /**
     * 
     * @var 数据库链接对象
     */
    public $db = null;
    
    /**
     * 
     * @var 当前操作表名
     */
    public $table;
    
    /**
     * 
     * @var 连贯操作字段
     */
    public $param = [
            'field' => '',
            'table' => '',
            'where' => '',
            'where_or' => '',
            'limit' => '',
            'join_on' => '',
            'order_by' => '',
            'group_by' => '',
            'having' => '',
        ];
    
    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->db_config = Application::getConfig('database');
        
        $db_obj = new Db();
        
        $this->db = $db_obj->init($this->db_config);
    }
    
    /**
     * 连贯操作 表
     * 当model中指定公共属性table时可省
     * @param string $table
     * @return \Aaron\Core\Model
     */
    public function table($table = null)
    {
        if (is_null($table)) $table = $this->table;
    
        $this->param['table'] = empty($table) ? '' : ($this->db_config['prefix'].$table);
    
        return $this;
    }
    
    /**
     * 连贯操作 where and
     * @param array|string $where
     * @param array $operation where条件中符号，默认=
     * @return \Aaron\Core\Model
     */
    public function where($where = null, $operation = [])
    {
        if (!is_null($where))
        {
            if (is_array($where))
            {
                $i = 0;
                foreach ($where as $k=>$v)
                {
                    $mark = '=';
                    if ($i > 0) $this->param['where'] .= ' AND ';
                    
                    if (array_key_exists($k, $operation)) $mark = $operation[$k];
                    
                    $v = (gettype($v) == 'string') ? ('"'. $v .'"') : $v;
                    
                    $this->param['where'] .= $k.$mark.$v;
                    $i++;
                }
                $where = $this->param['where'] ;
            }
            
            $this->param['where'] = ' AND '. $where;
            
            if (empty($this->param['where_or']))
            {
                $this->param['where'] = ' WHERE '. $where;
            }
        }
        return $this;
    }
    
    /**
     * 连贯操作 where or
     * @param string|array $where
     * @param array $operation where条件中符号，默认=
     * @return \Aaron\Core\Model
     */
    public function orWhere($where = null, $operation = [])
    {
        if (!is_null($where))
        {
            if (is_array($where))
            {
                $i = 0;
                foreach ($where as $k=>$v)
                {
                    $mark = '=';
                    if ($i > 0) $this->param['where'] .= ' OR ';
                    
                    if (array_key_exists($k, $operation)) $mark = $operation[$k];
                    
                    $v = (gettype($v) == 'string') ? ('"'. $v .'"') : $v;
                    $this->param['where_or'] .= $k.$mark.$v;
                    $i++;
                }
                $where = $this->param['where_or'];
            }
            
            if (empty($this->param['where']))
            {
                $this->param['where_or'] = ' WHERE ('. $where .')';
            }else{
                $this->param['where_or'] = ' AND ('. $where .')';
            }
        }
        return $this;
    }
    
    /**
     * 连贯操作 limit
     * 传值：0,10
     * @param string $limit
     * @return \Aaron\Core\Model
     */
    public function limit($limit = null)
    {
        if (!is_null($limit))
        {
            $this->param['limit'] = ' LIMIT '. $limit;
        }
        return $this;
    }
    
    /**
     * 连贯操作 获取数据结果列表
     * @param string $filed
     * @return array
     */
    public function lists($filed = '*')
    {
        $sql = $this->getSql($filed);
        return $this->db->resultListArray($sql);
    }
    
    /**
     * 连贯操作 获取一条数据
     * @param string $filed
     * @return array
     */
    public function row($filed = '*')
    {
        $sql = $this->getSql($filed);
        return $this->db->resultRowArray($sql);
    }
    
    /**
     * 
     * @param unknown $filed
     * @return string
     */
    private function getSql($filed)
    {
        $this->param['filed'] = $filed;
        
        if ($this->param['table'] == '')
        {
            $this->param['table'] = $this->db_config['prefix'].$this->table;
        }
        
        return 'SELECT '. $this->param['filed'] .' FROM '. $this->param['table'] . $this->param['join_on'] . $this->param['where']. $this->param['group_by'] . $this->param['having'] . $this->param['order_by'] . $this->param['limit'];
    }
    
    /**
     * 添加数据
     * @param array $data
     * @return boolean
     */
    public function insert($data = [])
    {
        if ($this->param['table'] == '')
        {
            $this->param['table'] = $this->db_config['prefix'].$this->table;
        }
        
        if ($this->db->insert($this->param['table'], $data))
        {
            return $this->db->indertId();
        }
        
        return false;
    }
    
    /**
     * 更新数据
     * @param 更新数据 array $data
     * @param 更新条件 array|string $where
     * @param where中的符号默认= array $operation
     */
    public function update($data = [], $where = [], $operation = [])
    {
        if ($this->param['table'] == '')
        {
            $this->param['table'] = $this->db_config['prefix'].$this->table;
        }
        
        return $this->db->update($this->param['table'], $data, $where, $operation);
    }
    
    /**
     * 删除数据
     * @param 删除数据的条件 array|string $where
     * @param where中的符号默认= array $operation
     */
    public function delete($where = [], $operation = [])
    {
        if ($this->param['table'] == '')
        {
            $this->param['table'] = $this->db_config['prefix'].$this->table;
        }
        
        return $this->db->delete($this->param['table'], $where, $operation);
    }
    
    /**
     * 连贯操作 关联查询
     * @param string $join_type
     * @param array $join
     * @return \Aaron\Core\Model
     */
    public function joinOn($join = [], $join_type = 'LEFT')
    {
        if (!is_null($join))
        {
            foreach ($join as $table=>$v)
            {
                $this->param['join_on'] .= $join_type.' JOIN '. $this->db_config['prefix'].$table .' ON '. $v .' ';
            }
        }
        return $this;
    }
    
    /**
     * 连贯操作 order by
     * 
     * @param string $filed
     * @return \Aaron\Core\Model
     */
    public function orderBy($filed = NULL)
    {
        if (!is_null($filed))
        {
            $this->param['order_by'] = ' ORDER BY ';
            if (is_array($filed))
            {
                $i = 0;
                foreach ($filed as $k=>$v)
                {
                    if ($i > 0) $this->param['order_by'] .= ',';
                    
                    $this->param['order_by'] .= $k.' '.$v;
                    $i++;
                }
            }else{
                $this->param['order_by'] = $filed;
            }
        }
        
        return $this;
    }
    
    /**
     * 连贯操作 group by
     * 
     * @param string $filed
     * @return \Aaron\Core\Model
     */
    public function groupBy($filed = NULL)
    {
        if (!is_null($filed))
        {
            $this->param['group_by'] = ' GROUP BY ';
            if (is_array($filed))
            {
                $i = 0;
                foreach ($filed as $k=>$v)
                {
                    if ($i > 0) $this->param['group_by'] .= ',';
                    
                    $this->param['group_by'] .= $k.' '.$v;
                    $i++;
                }
            }else{
                $this->param['group_by'] = $filed;
            }
        }
        
        return $this;
    }
    
    /**
     * 连贯操作 having
     * 用法：$having = ['id'=> '100']; $operation = ['id' => '>'];
     * @param array $having 条件
     * @param array $operation 条件方式
     * @return \Aaron\Core\Model
     */
    public function having($having = [], $operation = [])
    {
        if (!is_null($having))
        {
            $this->param['having'] = ' HAVING ';
            $i = 0;
            foreach ($having as $k=>$v)
            {
                if ($i > 0) $this->param['having'] .= ',';
                
                $operator = '=';
                if (array_key_exists($k, $operation)) $operator = $operation[$k];
                
                $v = (gettype($v) == 'string') ? ('"'. $v .'"') : $v;
                
                $this->param['having'] .= $k.$operator.$v;
                $i++;
            }
        }
        return $this;
    }
    
}