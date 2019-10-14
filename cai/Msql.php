<?php

class Msql
{
    public $host = '127.0.0.1';
    public $port = '3306';
    public $db = 'cai';
    public $root = 'root';
    public $pwd = '11111111';
    public $options = [
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'', 
        PDO::ATTR_PERSISTENT => true,
        ];

    public $conn = null;
    
    public function __construct(){
        $this->conn = @new PDO("mysql:host=$this->host:$this->port;dbname=$this->db", $this->root, $this->pwd, $this->options);
    }
    
    public function select($sql)
    {
        $stmt = $this->prepare($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function add($sql)
    {
        return $this->prepare($sql);
    }
    
    public function update($sql)
    {
        return $this->prepare($sql);
    }
    
    public function count($sql)
    {
        $result = $this->conn->query($sql);
        return $result->fetchColumn();
    }
    
    public function sum($sql)
    {
        $result = $this->conn->query($sql);
        return $result->fetchColumn();
    }
    
    private function prepare($sql)
    {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt;
    }
    
    public function index()
    {
        for($i = 0; $i < 1; $i++){
            $this->insertData(2000,1000);
        }
    }
    
    public function getRandStr(){
        return ++$this->start;
    }
    
    public function insertData($n = 1, $jn = 1){
        for($i = 0; $i < $n; $i++){
            $sql = 'INSERT INTO '.$this->table.' (`mail`) VALUES ';
            for($j = 0; $j < $jn; $j++){
                $sql .= "('" . 
                    $this->getRandStr(10) . "') , ";
            }
            $sql = substr($sql, 0, -3);
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            //echo $i.'-'.date('H:i:s').PHP_EOL;
        }
    }
}
