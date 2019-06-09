<?php
set_time_limit(0);
ini_set('memory_limit', '-1');

error_reporting(E_ALL);
ini_set('display_errors', '1');

$obj = new Ct2();
$obj->index();



class Ct2
{
    public $host = '119.3.161.184';
    public $port = '3306';
    public $db = 'test';
    public $root = 'root';
    public $pwd = 'Wd23@*ds3dD';
    public $options = [
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'', 
        PDO::ATTR_PERSISTENT => true,
        ];

    public $table = 'tselect';

    public function index()
    {  
        //n=5 5+4+3+2+1
        for($i = 0; $i < 1; $i++){
            $pid = pcntl_fork();
            if (!$pid) {
                $this->insertData(2,3);
            }
        }
    }
    
    public function getRandStr($length = 10, $type = 1){
        if ($type == 1) {
            $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNPQEST0123456789';
        } else if ($type == 2) {
            $chars = 'abcdefghijklmnopqrstuvwxyz';
        } else if ($type == 3) {
            $chars = '0123456789';
        }
        $len = strlen($chars);
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[rand(0, $len - 1)];
        }
        return $str;
    }
    
    public function insertData($n = 1, $jn = 1){
        $conn = @new PDO("mysql:host=$this->host:$this->port;dbname=$this->db", $this->root, $this->pwd, $this->options);

        for($i = 0; $i < $n; $i++){
            $sql = 'INSERT INTO '.$this->table.' (`p1` , `p2`, `p3`, `p4`, `p5`, `p6`, `p7`, `p8`, `p9`) VALUES ';
            for($j = 0; $j < $jn; $j++){
                $sql .= "('" . 
                    $this->getRandStr(10) . "', '" . 
                    $this->getRandStr(10, 3) . "', '" .
                    $this->getRandStr(5) . "', '" .
                    $this->getRandStr(2, 3) . "', '" .
                    $this->getRandStr(2, 3) . "', '" .
                    $this->getRandStr(10) . "', '" .
                    $this->getRandStr(10) . "', '" .
                    $this->getRandStr(10) . "', '" .
                    $this->getRandStr(10) . "') , ";
            }
            $sql = substr($sql, 0, -3);
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            //echo $i.'-'.date('H:i:s').PHP_EOL;
        }
    }
	
	public function index2()
    {  
		$this->table = 'tselect2';
        for($i = 0; $i < 1; $i++){
            $pid = pcntl_fork();
            if (!$pid) {
                $this->insertData2(2,3);
            }
        }
    }
	
	public function insertData2($n = 1, $jn = 1){
		try{
		
			$conn = new PDO("mysql:host=$this->host:$this->port;dbname=$this->db", $this->root, $this->pwd, $this->options);

			for($i = 0; $i < $n; $n--){
				if(!isset($sql) || empty($sql)){
					$sql = 'INSERT INTO '.$this->table.' (`p1` , `p2`, `p3`, `p4`, `p5`, `p6`, `p7`, `p8`, `p9`, `p10`, `p11`, `p12`, `p13`, `p14`, `p15`, `p16`, `p17`, `p18`, `p19`, `p20`) VALUES ';
					for($j = 0; $j < $jn; $j++){
						$s = $this->getRandStr(10);
						$sql .= "('" . 
							$this->getRandStr(10) . "', '" . 
							$this->getRandStr(10, 3) . "', '" .
							$this->getRandStr(5) . "', '" .
							$this->getRandStr(2, 3) . "', '" .
							$this->getRandStr(2, 3) . "', '" .
							$this->getRandStr(10) . "', '" .
							$this->getRandStr(10) . "', '" .
							$this->getRandStr(10) . "', '" .
							$this->getRandStr(10) . "', '" .
							$this->getRandStr(10) . "', '" .
							$s . "', '" .
							$s . "', '" .
							$s . "', '" .
							$s . "', '" .
							$s . "', '" .
							$s . "', '" .
							$s . "', '" .
							$s . "', '" .
							$s . "', '" .
							time() . "') , ";
					}
					$sql = substr($sql, 0, -3);
				}
				$stmt = $conn->prepare($sql);
				$stmt->execute();
			}
		}catch (Exception $e){
			sleep(5);
			$this->insertData2($n,$jn);
		}
    }
}
