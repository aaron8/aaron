<?php
namespace Aaron;

use Aaron\Core\Model;
class Test_Model extends Model
{
    public $table = 'activity_reward';
    
    public function __construct()
    {
        parent::__construct();
        $this->db->select_db('ums');
    }
    
    public function to()
    {
        return "test model";
    }
    
    public function getList()
    {
        $_where = ['related_member_uuid'=>'20C91CB4C7F5E6FCE055000000000001'];
        $_operation = ['related_member_uuid' => '!='];
        return $this->where($_where, $_operation)->limit(10)->lists();
        
    }
    
}