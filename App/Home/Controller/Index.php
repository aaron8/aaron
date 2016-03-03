<?php
namespace Aaron;
use Aaron\Core\Controller;

class Index extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function Index()
    {
        $str = 'this is index controller! ';
        
        $test_model = loadModel('test');
        $to = $test_model->getList();
        
        
        
        $data = [];
        $data['str'] = $str;
        $data['to'] = $to;
        $this->view('index', $data);
    }
    
    public function Test()
    {
        
        echo 'index/test';
        
    }
    
    public function red()
    {
        redirect('http://www.itam.cn');
    }
    
    public function Json()
    {
        $js = ['a'=>'b'];
        
        ajaxResponse(true, '', $js);
    }
}