<?php
set_time_limit(0);
ini_set('memory_limit', '-1');

error_reporting(E_ALL);
ini_set('display_errors', '1');

$obj = new Ct2();
$obj->index();

class Ct2
{
    public $table = 'cp190606';

    public function index()
    {
        
        //持久连接 PDO::ATTR_PERSISTENT
        $conn = new PDO("mysql:host=119.3.161.184:3306;dbname=cp", 'root', 'Wd23@*ds3dD',array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'', PDO::ATTR_PERSISTENT => true));
        $stmt1 = $conn->prepare("select * from $this->table where num = :num limit 1");
        $stmt2 = $conn->prepare("UPDATE $this->table SET n = :nn  WHERE id = :id");
        
        $ns = [];
        $n = 20000000;
        //$n = 10000;
        $nss = $n-1;
        
        $arr2 = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16'];
        for($i = 0; $i < $n; $i++){
            $arr1 = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33'];
            $r = [];
            for($j = 0; $j < 6; $j++){
                $t = array_rand($arr1);
                $r[] =$arr1[$t];
                unset($arr1[$t]);
            }
            sort($r, SORT_NUMERIC);
            $t2 = array_rand($arr2);
            $r[] = $arr2[$t2];
            
            $num = implode('', $r);
            
            $stmt1->execute(array('num' => $num));
            $rr = $stmt1->fetchAll(PDO::FETCH_ASSOC);
            if(!empty($rr)) {
                $nn = intval($rr[0]['n']) + 1;
                
                $stmt2->execute(array('nn' => $nn, 'id' => $rr[0]['id']));
            }else{
                $kk = array_search($num, $ns);
                if($kk === false){
                    $nnn[] = ['num'=>$num, 'n' => 1];
                    $ns[] = $num;
                }else{
                    $nnn[$kk]['n'] = $nnn[$kk]['n']+1;
                }
                
                if($i%30000 == 0 || $i == $nss){
                    $sql = 'INSERT INTO '.$this->table.' (`num` , `n`) VALUES ';
                    foreach ($nnn as $v){
                        $sql .= '(\'' . $v['num'] . '\', ' .$v['n'] . ') , ';
                    }
                    $sql = substr($sql, 0, -3);
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $nnn = [];
                    $ns = [];
                    $sql = '';
                    //echo $i.'-'.date('H:i:s').PHP_EOL;
                }
            }
            $stmt = '';
            $arr1 = [];
            $r = [];
            
            if($i%10000000 == 0){
				//echo $i.'-'.date('H:i:s').PHP_EOL;
            }
        }
    }
}
