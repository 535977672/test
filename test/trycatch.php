<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$obj = new TryCatch();
$obj->index(10);


class TryCatch
{
   
    public function index($n=0)
    {  
		$var1 = 1;
		try{
		
			if($n>0){
				$var2 = 2;
				$n--;
				throw new Exception('E');
			}
		}catch (Exception $e){
			echo $var1. '<br />';
			echo $var2. '<br />';
			echo $n. '<br />';
			$this->index($n);
		}
    }
}
