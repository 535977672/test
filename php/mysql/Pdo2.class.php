<?php
class Pdo2{
    private $_servername = "127.0.0.1";
    private $_username = "root";
    private $_password = "11111111";
    private $_dbname = 'test';
    private $_conn = '';
    private $_transaction = array();
    
    function __construct() {
        
        $this->_conn = new PDO("mysql:host=$this->_servername;dbname=$this->_dbname", $this->_username, $this->_password,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
      
    }
    
    function transactionTest2($rand, $isbt = false) {
        $this->_transaction[$rand] = $isbt;
        try {
            $this->beginTransaction($rand);
            
            //使用问号（?）参数来准备SQL语句
            $stmt = $this->_conn->prepare("INSERT INTO user (id, name, password, k1) VALUES (?, ?, ?, ?)");
            $stmt->execute(array(null, 'ts3123', '23', 33));
            $lastInsertId = $this->_conn->lastInsertId();
            $stmt = $this->_conn->prepare("select * from user where id = $lastInsertId");
            $stmt->execute();
            $re = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //$this->rollback($rand);
            $this->commit($rand);
            return $re;
        }catch(PDOException $e){
            $this->rollback($rand);
            echo $e->getMessage();
            return false;
        }
    }
    
    private function beginTransaction($rand){
        if($this->_transaction[$rand] === false) return false;
        $this->_conn->beginTransaction();
        return true;
    }
    
    private function commit($rand){
        if($this->_transaction[$rand] === false) return false;
        $this->_conn->commit();
        return true;
    }
    
    private function rollback($rand){
        if($this->_transaction[$rand] === false) return false;
        $this->_conn->rollback();
        return true;
    }

}