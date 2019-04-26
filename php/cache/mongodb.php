<?php
//mongoDB 3.4 	mongodb.dll/1.5.0
/*
    MongoDB\Driver — MongoDB 驱动类
        MongoDB\Driver\Manager — The MongoDB\Driver\Manager class
        MongoDB\Driver\Command — The MongoDB\Driver\Command class
        MongoDB\Driver\Query — The MongoDB\Driver\Query class
        MongoDB\Driver\BulkWrite — The MongoDB\Driver\BulkWrite class
        MongoDB\Driver\WriteConcern — The MongoDB\Driver\WriteConcern class
        MongoDB\Driver\ReadPreference — The MongoDB\Driver\ReadPreference class
        MongoDB\Driver\ReadConcern — The MongoDB\Driver\ReadConcern class
        MongoDB\Driver\Cursor — The MongoDB\Driver\Cursor class
        MongoDB\Driver\CursorId — The MongoDB\Driver\CursorId class
        MongoDB\Driver\Server — The MongoDB\Driver\Server class
        MongoDB\Driver\WriteConcernError — The MongoDB\Driver\WriteConcernError class
        MongoDB\Driver\WriteError — The MongoDB\Driver\WriteError class
        MongoDB\Driver\WriteResult — The MongoDB\Driver\WriteResult class  

 */

use MongoDB\Driver\Manager;
use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Command;
use MongoDB\Driver\Cursor;
use MongoDB\Driver\Query;

//检查一个扩展是否已经加载。大小写不敏感。
if (!extension_loaded('mongodb')) {
    echo '不支持 mongodb';
    return ;
}


//mMongoManagerExecuteBulkWrite();
//mMongoManagerExecuteCommand();
//mMongoManagerExecuteQuery();
mMongo();

function mMongoManagerExecuteBulkWrite(){
    
    //MongoDB\Driver\BulkWrite
    //可以添加一个或多个写操作
    //public __construct ([ array $options ] )
    //public int count ( void )
    //public void delete ( array|object $filter [, array $deleteOptions ] )
    //public mixed insert ( array|object $document )
    //public void update ( array|object $filter , array|object $newObj [, array $updateOptions ] )
    
    //1. MongoDB\Driver\BulkWrite::__construct ([ array $options ] )  insert update delete count
    //bypassDocumentValidation   FALSE  boolean	真 允许插入和更新操作来绕过文档级验证
    //ordered  TRUE                     boolean 有序的操作（TRUE）是串行地在MongoDB服务器上执行的，而无序的操作（FALSE）则以任意的顺序发送到服务器，并且可以并行执行。
    $bluk = new BulkWrite(array('ordered' => true));

    
    //public mixed MongoDB\Driver\BulkWrite::insert ( array|object $document )
    //添加一个插入操作
    $document1 = ['k1' => 1, 'k2' => '福利费放假放假的首付款就', 'k3' => 'wee234332xFDFS白富美'];
    $document2 = ['_id' => time(), 'k1' => 2, 'k2' => '风飞沙浪费了少年犯', 'k3' => 'fdfdf股份的看过的反馈给r3r4fsd'];
    $document3 = ['_id' => 'df2334ewfdb', 'k1' => 3, 'k2' => '过段时间股份感觉到交付给', 'k3' => '飞得高5太44'];
    $document4 = ['_id' => new MongoDB\BSON\ObjectId, 'k1' => 4, 'k2' => '过段时间股份感觉到交付给', 'k3' => '飞得高5太44'];
    
    // 1.1
    $rd1 = $bluk->insert($document1);//object(MongoDB\BSON\ObjectId)#3 (1) { ["oid"]=> string(24) "5b39abee6af0d51f28005ed6" }
    $rd2 = $bluk->insert($document2);//int(1530506222)
    $rd3 = $bluk->insert($document3);//string(11) "df2334ewfdb"
    $rd4 = $bluk->insert($document4);//object(MongoDB\BSON\ObjectId)#5 (1) { ["oid"]=> string(24) "5b39ac566af0d51f28005ed7" }
    
    //var_dump($bluk);//object(MongoDB\Driver\BulkWrite)#2 (7) { ["database"]=> NULL ["collection"]=> NULL ["ordered"]=> bool(true) ["bypassDocumentValidation"]=> NULL ["executed"]=> bool(false) ["server_id"]=> int(0) ["write_concern"]=> NULL }
    
    // 1.2 void MongoDB\Driver\BulkWrite::update ( array|object $filter , array|object $newObj [, array $updateOptions ] )
    //$newObj 一份包含更新操作符（例如$set）或替换文档（即只有字段：值表达式）的文档。
    //$updateOptions
    //    arrayFilters      array|object  一组筛选文档，用于确定在阵列字段上更新操作的哪些阵列元素
    //    collation         array|object
    //    multi  FALSE FALSE只更新第一个匹配的文档，或者所有匹配的文档都是正确的。如果newObj是替换文档，那么这个选项就不成立了
    //    upsert FALSE 如果过滤器与现有的文档不匹配，则插入单个文档。如果是替代文档（即没有更新操作符），则将从newObj创建文档;否则，newObj中的运营商将被应用到过滤器来创建新文档。
    $bluk->update(
        array('k1' => 1),
        array('$set' => array('k2' => 'k2 update')),//$set $inc
        ['multi' => false, 'upsert' => true]
    );
    
    // 1.3 int MongoDB\Driver\BulkWrite::count ( void )
    count($bluk);//4
    $bluk->count();//4
    
        
    // 1.4 void MongoDB\Driver\BulkWrite::delete ( array|object $filter [, array $deleteOptions ] )
    //$filter 过滤
    //$deleteOptions 
    //collation array|object	
    //   locale: <string>,
    //   caseLevel: <boolean>,
    //   caseFirst: <string>,
    //   strength: <int>,
    //   numericOrdering: <boolean>,
    //   alternate: <string>,
    //   maxVariable: <string>,
    //   backwards: <boolean>
    //limit         FALSE 删除所有匹配的文档（FALSE），或者只删除第一个匹配的文档（TRUE）
    $bluk->delete(['k1' => 4], ['limit' => 1]);
    
    
    //2. mongodb://[username:password@]host1[:port1][,host2[:port2],...[,hostN[:portN]]][/[database][?options]]
    //MongoDB\Driver\Manager::__construct ([ string $uri = "mongodb://127.0.0.1/ [, array $uriOptions = array() [, array $driverOptions = array() ]]] )
    //$manager = new MongoDB\Driver\Manager("mongodb://127.0.0.1:27017");//object(MongoDB\Driver\Manager)#1 (2) { ["uri"]=> string(25) "mongodb://127.0.0.1:27017" ["cluster"]=> array(0) { } }
    $manager = new Manager("mongodb://127.0.0.1:27017");// use MongoDB\Driver\Manager;
    
    //2.1 public MongoDB\Driver\WriteResult MongoDB\Driver\Manager::executeBulkWrite ( string $namespace , MongoDB\Driver\BulkWrite $bulk [, array $options = array() ] )
    //返回 MongoDB\Driver\WriteResult 对象
    //$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 100);
    $result = $manager->executeBulkWrite('db.ExecuteBulkWrite', $bluk, $writeConcern);
    
    //3. MongoDB\Driver\WriteResult
    printf("Inserted %d document(s)\n", $result->getInsertedCount());//返回插入的文档数量（不包括upserts），如果写不被承认，则返回NULL。 Inserted 4 document(s)
    printf("Matched  %d document(s)\n", $result->getMatchedCount());//如果更新操作不会对文档造成任何改变（例如，将字段的值设置为当前值），那么匹配的计数可能会大于MongoDB驱动程序WriteResult所返回的值：：getmodicount（）。返回被选中更新的文档的数量，如果没有被确认，则返回NULL。  Matched 1 document(s) 
    printf("Updated  %d document(s)\n", $result->getModifiedCount());//返回已更新的现有文档的数量，如果没有被确认，则返回NULL。  Updated 1 document(s)
    printf("Upserted %d document(s)\n", $result->getUpsertedCount());//返回upsert插入的文档数量。 Upserted 0 document(s)
    printf("Deleted  %d document(s)\n", $result->getDeletedCount());//返回被删除的文档的数量，如果没有被确认，则返回NULL。  Deleted 1 document(s)

    var_dump($result->getUpsertedIds());
    
    $result->isAcknowledged();//如果写入被确认，则返回TRUE;否则返回FALSE。
    $result->server(); //返回与此写入结果相关联的MongoDB驱动服务器。
    $WriteError = $result->getWriteError();//返回一个MongoDB驱动程序WriteError对象数组，用于在写操作期间遇到的任何写错误。如果没有发生写入错误，数组将是空的。
    //4. WriteError对象数组 $WriteError[0]->getMessage()  getInfo() getCode() getIndex();
    $wrieconcernerror = $result->getWriteConcernError();//如果在写操作过程中遇到写关注错误，则返回MongoDB驱动程序wrieconcernerror，否则将无效
    //5. wrieconcernerror对象 $wrieconcernerror->getMessage()  getInfo() getCode    
}

function mMongoManagerExecuteCommand(){
    
    $bluk = new BulkWrite();
    $bluk->insert(['k1' => 1, 'k2' => 'k2', 'k3' => 'k3']);
    $bluk->insert(['k1' => 1, 'k2' => '2', 'k3' => '3']);
    $bluk->insert(['k1' => 1, 'k2' => 2, 'k3' => 3]);

    $manager = new Manager("mongodb://127.0.0.1:27017");
    $manager->executeBulkWrite('db.ExecuteCommand', $bluk);
    
    $command = new Command([
        'aggregate' => 'ExecuteCommand',
        'pipeline' => [
            ['$group' => ['_id' => '$_id', 'sum' => ['$sum' => '$k2']]],
        ],
        'cursor' => new stdClass,
    ]);
    

    try {
        //public MongoDB\Driver\Cursor MongoDB\Driver\Manager::executeCommand ( string $db , MongoDB\Driver\Command $command [, array $options = array() ] )
        //选择一台服务器，并在该服务器上执行该命令。
        //如果可能的话，鼓励用户使用特定的读/或写命令方法。
        //返回 MongoDB\Driver\Cursor 游标对象
        $cursor = $manager->executeCommand('db', $command);
        
        //executeReadWriteCommand
        //执行读取和写入的数据库命令
        //executeWriteCommand
        //执行写操作的数据库命令
        //executeReadCommand
        //执行读取的数据库命令
        
    } catch(MongoDB\Driver\Exception $e) {
        echo $e->getMessage(), "\n";
        exit;
    }
    $cursor->isDead();//0
    //object(stdClass)#6 (2) { ["_id"]=> object(MongoDB\BSON\ObjectId)#5 (1) { ["oid"]=> string(24) "5b39f2a76af0d5103c00654d" } ["sum"]=> int(2) }
    $response = $cursor->toArray()[0];
    //public MongoDB\Driver\CursorId MongoDB\Driver\Cursor::getId ( void ) Returns the ID for this cursor
    $cursor->getId();
    $cursor->isDead();//1
    
    
}

//执行数据库查询
function mMongoManagerExecuteQuery(){
    
    $manager = new Manager("mongodb://127.0.0.1:27017");
    
//    $bluk = new BulkWrite();
//    $bluk->insert(['_id' => 1, 'k1' => 1, 'k2' => 34543, 'k3' => 545435]);
//    $bluk->insert(['_id' => 2, 'k1' => 3, 'k2' => 4543534, 'k3' => '3']);
//    $bluk->insert(['_id' => 3, 'k1' => 2, 'k2' => 25, 'k3' => 3]);
//    $bluk->insert(['_id' => 4, 'k1' => 43, 'k2' => 4524, 'k3' => 543534]);
//    $bluk->insert(['_id' => 5, 'k1' => 545, 'k2' => 6767657, 'k3' => 453]);
//    $bluk->insert(['_id' => 6, 'k1' => 334, 'k2' => 4534, 'k3' => 43534]);
//    $bluk->insert(['_id' => 7, 'k1' => 324, 'k2' => 543534, 'k3' => 5345]);
//    $bluk->insert(['_id' => 8, 'k1' => 542545, 'k2' => 43543, 'k3' => 54345]);
//    $bluk->insert(['k1' => 324, 'k2' => 543534, 'k3' => 5345]);
//    $bluk->insert(['k1' => 324, 'k2' => 543534, 'k3' => 5345]);
//    $bluk->insert(['k1' => 324, 'k2' => 543534, 'k3' => 5345]);
//    $bluk->insert(['k1' => 324, 'k2' => 543534, 'k3' => 5345]);
//    $bluk->insert(['k1' => 324, 'k2' => 543534, 'k3' => 5345]);
//    $bluk->insert(['k1' => 324, 'k2' => 543534, 'k3' => 5345]);
//    $bluk->insert(['k1' => 324, 'k2' => 543534, 'k3' => 5345]);
//    $bluk->insert(['k1' => 324, 'k2' => 543534, 'k3' => 5345]);
//    $bluk->insert(['k1' => 324, 'k2' => 543534, 'k3' => 5345]);
//    $bluk->insert(['k1' => 324, 'k2' => 543534, 'k3' => 5345]);
//    $bluk->insert(['k1' => 324, 'k2' => 543534, 'k3' => 5345]);
//    $bluk->insert(['k1' => 324, 'k2' => 543534, 'k3' => 5345]);
//    $bluk->insert(['k1' => 324, 'k2' => 543534, 'k3' => 5345]);
//    $bluk->insert(['k1' => 324, 'k2' => 543534, 'k3' => 5345]);

//    $manager->executeBulkWrite('db.ExecuteQuery', $bluk);
    
    //1.条件语句查询  $lt $lte(<=) $gt $gte $ne $regex $in $type
    //  array('keyN' => 'value')
    //  array('keyN'=>array('$lt' => 'value'))
    //  ['$in' => ['A', 'D']
    //2. and or  SELECT * FROM db.table WHERE key1 = 1 AND ( key2 > 1 OR key3 LIKE "p%")
    //    array(
    //        'key1' => 1,
    //        '$or' => array(
    //            array('key2' => array('$gt' => 1)),
    //            array('key3' => array('$regex' => '^p'))//['key3' => new \MongoDB\BSON\Regex('^p')]
    //        )
    //    );
    
    //$options
    //projection(全1/0) limit skip sort(-1 desc 1 asc) 
    $filter = [
            '$or' => [
                    [
                        'k1' => ['$gt' => 100, '$lt' => 1000],
                        'k2' => ['$gt' => 5000],
                    ],
                    ['_id' => 1]
                ],
            'k3' => ['$in' => [5345, 545435]]
        ];
    $options = [
        'projection' => ['_id' => 1, 'k1'=> 1, 'k2' => 2],//默认要返回_id字段
        'sort' => ['k1' => -1],//k1字段排序
        'limit' => 3,
        'skip' => 3
    ];

    //final public __construct ( array|object $filter [, array $queryOptions ] )
    //使用查询操作符指定查询条件
    //可选，使用投影操作符指定返回的键。查询时返回文档中所有键值， 只需省略该参数即可（默认省略）。
    $query = new Query($filter, $options);
    

    //public MongoDB\Driver\Cursor MongoDB\Driver\Manager::executeQuery ( string $namespace , MongoDB\Driver\Query $query [, array $options = array() ] )
    //执行数据库查询
    $cursor = $manager->executeQuery('db.ExecuteQuery', $query);
    
    $response = $cursor->toArray();
    var_dump($response);
}

function mMongo(){
    
    $manager = new Manager("mongodb://127.0.0.1:27017");
    
    //getReadConcern  Return the ReadConcern for the Manager
    var_dump($manager->getReadConcern());//object(MongoDB\Driver\ReadConcern)#2 (0) {}
    
    //$manager = new Manager("mongodb://localhost:27017/?readConcernLevel=local&readPreference=secondaryPreferred&readPreferenceTags=dc:ny,rack:1&readPreferenceTags=dc:ny&readPreferenceTags=");
    $manager = new Manager("mongodb://localhost:27017/", array(
        'readConcernLevel' => 'local',
        'readPreference' => 'secondaryPreferred',
        'readPreferenceTags' => array(array('dc' => 'ny', 'rack' => '1'),array('dc' => 'ny'),array()),
        
        'w' => 'majority',
        'wtimeoutMS' => 2000
        ));
    var_dump($manager->getReadConcern());//object(MongoDB\Driver\ReadConcern)#1 (1) { ["level"]=> string(5) "local" }
    var_dump($manager->getReadPreference());
    var_dump($manager->getWriteConcern());//object(MongoDB\Driver\WriteConcern)#1 (2) { ["w"]=> string(8) "majority" ["wtimeout"]=> int(2000) }
    
}
