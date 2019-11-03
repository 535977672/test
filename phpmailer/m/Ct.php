<?php

class Ct
{
    public $host = '127.0.0.1';
    public $port = '3306';
    public $db = 'test';
    public $root = 'root';
    public $pwd = '11111111';
    public $options = [
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'', 
        PDO::ATTR_PERSISTENT => true,
        ];

    public $table = 'mail_send';
    public $win;
    public $conn = null;
    
    public static $mail = [];
    
    public function __construct(){
        $this->win = strtoupper(substr(PHP_OS,0,3))==='WIN';
        $this->conn = @new PDO("mysql:host=$this->host:$this->port;dbname=$this->db", $this->root, $this->pwd, $this->options);
    }
    
    public function insertData($data){
        if(is_string($data)){
            $data = explode(' ', $data);
        }
        $sql = 'INSERT INTO '.$this->table.' (`mail`) VALUES ';
        foreach ($data as $v){
            if($v) $sql .= "('" . $v.'@qq.com' . "') , ";
        }
        $sql = substr($sql, 0, -3);
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        echo date('H:i:s').PHP_EOL;
    }
    
    public function selectData($limit){
        $start = intval(file_get_contents('./m/pos.ini'));
        $stmt = $this->conn->prepare("select * from $this->table where id > $start order by id asc limit 0,$limit");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if(!self::$mail){
            $stmt = $this->conn->prepare("select id,mail from mail order by id asc");
            $stmt->execute();
            $tpm = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($tpm as $v){
                self::$mail[] = $v['mail'];
            }
        }
        $re = [];
        $len = count($data);
        foreach($data as $k=>$v){
            if(!in_array($v['mail'], self::$mail)){
                $re[] = $v['mail'];
                if($k == $len-1){
                    file_put_contents('./m/pos_bak.ini', $start);
                    file_put_contents('./m/pos.ini', $v['id']);
                }
            }
        }
        return $re;
    }
    
    public function delData($data){
        $data = implode("','", $data);
        $stmt = $this->conn->prepare("delete from $this->table where `mail` in ('$data')");
        $stmt->execute();
    }
    
    public function selectSmtp(){
        $stmt = $this->conn->prepare("select * from mail_smtp");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
}