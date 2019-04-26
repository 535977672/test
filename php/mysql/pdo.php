<?php
$servername = "127.0.0.1";
$username = "root";
$password = "11111111";
$dbname = 'test';
function __autoload($classname) {
    $filename = "./". $classname .".class.php";
    include_once($filename);
}

//sql  插入优化
//1. insert into 合并  insert into编译减慢速度
//2. pdo开启事务  insert into默认会开启事务，减慢速度
//3. 主键有序插入(主键不是随机)
//4. sql语句长度有限制 max_allow_packet默认1M
//5. 事务控制大小，事务太大影响执行效率 innodb_log_buffer_size 8M 超过会把数据存磁盘，效率下降。
//6. 从一个文本文件装载一个表时，使用LOAD DATA INFILE。这通常比使用很多INSERT语句快20倍
//7. 为了对LOAD DATA INFILE和INSERT在MyISAM表得到更快的速度，通过增加key_buffer_size系统变量来扩大键高速缓冲区。

//DELETE语句的速度
//删除一个记录的时间与索引数量确切成正比。为了更快速地删除记录，可以增加键高速缓冲的大小。参见7.5.2节，“调节服务器参数”。
//如果想要删除一个表的所有行，使用TRUNCATE TABLE tbl_name 而不要用DELETE FROM tbl_name。

// UPDATE语句的速度
//更新查询的优化同SELECT查询一样，需要额外的写开销。写速度依赖于更新的数据大小和更新的索引的数量。没有更改的索引不被更新。
//使更改更快的另一个方法是推迟更改然后在一行内进行多次更新。如果锁定表，同时做多个更新比一次做一个快得多。
//请注意对使用动态记录格式的MyISAM表，更新一个较长总长的记录可能会切分记录。如果经常这样该，偶尔使用OPTIMIZE TABLE很重要。

//其他优化
//使用持久的连接数据库以避免连接开销。如果不能使用持久的连接并且你正启动许多新的与数据库的连接，可能要更改thread_cache_size变量的值。
//总是检查所有查询确实使用已经在表中创建了的索引。在MySQL中，可以用EXPLAIN命令做到。
// 尝试避免在频繁更新的表上执行复杂的SELECT查询，以避免与锁定表有关的由于读、写冲突发生的问题。
//对于没有删除的行的MyISAM表，可以在另一个查询正从表中读取的同时在末尾插入行。如果这很重要，应考虑按照避免删除行的方式使用表。另一个可能性是在删除大量行后运行OPTIMIZE TABLE。
//要修复任何ARCHIVE表可以发生的压缩问题，可以执行OPTIMIZE TABLE。
//如果你主要按expr1，expr2，...顺序检索行，使用ALTER TABLE ... ORDER BY expr1, expr2, ...。对表大量更改后使用该选项，可以获得更好的性能。
//只是因为行太大，将一张表分割为不同的表一般没有什么用处。为了访问行，最大的性能冲击是磁盘搜索以找到行的第一个字节。在找到数据后，大多数新型磁盘对大多数应用程序来说足够快，能读入整个行。确实有必要分割的唯一情形是如果它是使用动态记录格式使之变为固定的记录大小的MyISAM表(见上述)，或如果你需要很频繁地扫描表而不需要大多数列。
//如果你需要很经常地计算结果，例如基于来自很多行的信息的计数，引入一个新表并实时更新计数器可能更好一些。...



try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));

    $conn->beginTransaction();

    //使用命名（:name）参数来准备SQL语句
    $stmt = $conn->prepare("select * from user where id > :id and k1 > :k1");
    $stmt->execute(array('id' => 50, 'k1' => 10));

    //使用问号（?）参数来准备SQL语句
    //$stmt = $conn->prepare("select * from user where id > ? and k1 > ?");
    //$stmt->execute(array(50, 10));
    
    $re1 = $stmt->fetchAll(PDO::FETCH_ASSOC);//返回一个索引为结果集列名的数组
    
    $stmt = $conn->prepare("INSERT INTO `user` (`id`, `name`, `password`, `k1`) VALUES (?, ?, ?, ?)");
    $stmt->execute(array(null, 'ts2', '2343532', 242));
    
    $lastInsertId = $conn->lastInsertId();
    $stmt = $conn->prepare("update `user` set `k1` = :k1 where `user`.`id` < :lastInsertId");
    $stmt->execute(array('k1' => 542, 'lastInsertId' => $lastInsertId));

    $stmt = $conn->prepare("delete from `user` where `user`.`id` < ?");
    $stmt->execute(array(50));
    
    $t1 = new Pdo1();
    $re2 = $t1->transactionTest1(2324545135, TRUE);
    
    $conn->commit();
}catch(PDOException $e){
    echo $e->getMessage();
}