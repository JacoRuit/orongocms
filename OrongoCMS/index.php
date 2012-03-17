<?php
/**
 * @author Jaco Ruit
 */

require 'startOrongo.php';
startOrongo();


class Test{
    private $array;
    public $a;
    
    public function __construct(){
        $this->array = array();
        $a =  debug_backtrace();
        var_dump($a[1]['class']);
        var_dump($a);
    }
    
    public function __invoke(){
        foreach($this->array as $func){ $func(); }
    }
    
    public function add($method){
        var_dump($method instanceof Closure);
        $this->array[count($this->array)] = $method;
        var_dump(func_get_args($method));
    }
    
}



setCurrentPage('index');
$index = new IndexFrontend();
$index->main(array('time' => time()));
$index->render();

?>
