<?php
//mongoDB 3.4 	mongo.dll/1.6.16
/*
  

    SQL查询语句	                                                      Mongo查询语句
    CREATE TABLE USERS (a Number, b Number)	                          隐式的创建，或 MongoDB::createCollection().
    INSERT INTO USERS VALUES(1,1)	                                  $db->users->insert(array("a" => 1, "b" => 1));
    SELECT a,b FROM users	                                          $db->users->find(array(), array("a" => 1, "b" => 1));
    SELECT * FROM users WHERE age=33	                                  $db->users->find(array("age" => 33));
    SELECT a,b FROM users WHERE age=33	                                  $db->users->find(array("age" => 33), array("a" => 1, "b" => 1));
    SELECT a,b FROM users WHERE age=33 ORDER BY name	                  $db->users->find(array("age" => 33), array("a" => 1, "b" => 1))->sort(array("name" => 1));
    SELECT * FROM users WHERE age>33	                                  $db->users->find(array("age" => array('$gt' => 33)));
    SELECT * FROM users WHERE age<33	                                  $db->users->find(array("age" => array('$lt' => 33)));
    SELECT * FROM users WHERE name LIKE "%Joe%"	                                  $db->users->find(array("name" => new MongoRegex("/Joe/")));
    SELECT * FROM users WHERE name LIKE "Joe%"	                                  $db->users->find(array("name" => new MongoRegex("/^Joe/")));
    SELECT * FROM users WHERE age>33 AND age<=40	                              $db->users->find(array("age" => array('$gt' => 33, '$lte' => 40)));
    SELECT * FROM users ORDER BY name DESC	                                  $db->users->find()->sort(array("name" => -1));
    CREATE INDEX myindexname ON users(name)	                                  $db->users->ensureIndex(array("name" => 1));
    CREATE INDEX myindexname ON users(name,ts DESC)	                          $db->users->ensureIndex(array("name" => 1, "ts" => -1));
    SELECT * FROM users WHERE a=1 and b='q'	                                  $db->users->find(array("a" => 1, "b" => "q"));
    SELECT * FROM users LIMIT 20, 10	                                      $db->users->find()->limit(10)->skip(20);
    SELECT * FROM users WHERE a=1 or b=2	                                  $db->users->find(array('$or' => array(array("a" => 1), array("b" => 2))));
    SELECT * FROM users LIMIT 1	                                              $db->users->find()->limit(1);
    EXPLAIN SELECT * FROM users WHERE z=3	                                  $db->users->find(array("z" => 3))->explain()
    SELECT DISTINCT last_name FROM users	                                  $db->command(array("distinct" => "users", "key" => "last_name"));
    SELECT COUNT(*y) FROM users	                                              $db->users->count();
    SELECT COUNT(*y) FROM users where AGE > 30	                              $db->users->find(array("age" => array('$gt' => 30)))->count();
    SELECT COUNT(AGE) from users	                                          $db->users->find(array("age" => array('$exists' => true)))->count();
    UPDATE users SET a=1 WHERE b='q'	                                      $db->users->update(array("b" => "q"), array('$set' => array("a" => 1)));
    UPDATE users SET a=a+2 WHERE b='q'	                                      $db->users->update(array("b" => "q"), array('$inc' => array("a" => 2)));
    DELETE FROM users WHERE z="abc"	                                          $db->users->remove(array("z" => "abc"));

    核心类
    MongoClient — MongoClient 类
    MongoDB — MongoDB 类
    MongoCollection — The MongoCollection class
    MongoCursor — The MongoCursor class
    MongoCursorInterface — The MongoCursorInterface interface
    MongoCommandCursor — The MongoCommandCursor class 
 */


//检查一个扩展是否已经加载。大小写不敏感。
if (!extension_loaded('mongo')) {
    echo '不支持 mongo';
    return ;
}

//test();
mMongoClient();

//定义这个类的扩展被弃用。相反，应该使用MongoDB扩展。
//替代方案包括：MongoDB\Driver\Manager
function mMongoClient(){
    //mongodb://[username:password@]host1[:port1][,host2[:port2:],...]/db
    //MongoClient::__construct ([ string $server = "mongodb://localhost:27017" [, array $options = array("connect" => TRUE) ]] )
    $m = new MongoClient(); // 连接 localhost:27017
    
}








function test(){
    // 链接服务器 连接数据库
    $m = new MongoClient();

    // 选择一个数据库 这个数据库不需要提前建好，当你使用它的时候，就会自动建立。
    $db = $m->comedy;

    // 获取集合 选择一个集合  一个集合相当于一张表
    $collection = $db->cartoons;
    
        
    //建立索引 指定字段名和排序方向： 升序（1）或降序（-1）
    $collection->ensureIndex( array( "XKCD" => 1 ) );

    // 插入一个文档（译注：“文档”相当于关系型数据库的“行”）
    //注意：你可以嵌套数组、对象。
    //驱动会把关联数组保存为 js对象，
    //从0开始的连续数字下标数组保存为 js数组，
    //不从0开始或有间断的（如0,1,4,5）数组保存为 js对象。 
    //译注：即：只有从0开始的连续数字下标数组保存为数组，其他复杂类型均为对象。
    $document = array( "title" => "Calvin and Hobbes", "author" => "Bill Watterson" );
    $collection->insert($document);
    
    //要查看我们上一步插入到数据库的文档，可以简单的使用 MongoCollection::findOne() 方法从即合理获得一个简单的文档。 这个方法在只想查询一个结果的时候很有用。
    $documents = $collection->findOne();
    var_dump($documents);
    
    //计算文档数量
    var_dump($collection->count());

    // 添加另一个文档，它的结构与之前的不同
    $document = array( "title" => "XKCD", "online" => true );
    $collection->insert($document);
    
    //查询集合中的所有文档
    //使用游标获取所有文档
    //要获得集合中的所有文档，我们需要 MongoCollection::find() 方法。 
    //find() 方法返回一个 MongoCursor 对象，允许我们遍历整个结果集合来读取文档。要查询所有的文档并显示它们，
    $cursor = $collection->find();
    foreach ( $cursor as $id => $value )
    {
        echo "$id: ";
        var_dump( $value );
        //5b37523f517962b817000032: array(3) {["_id"]=>object(MongoId)#10 (1) {["$id"]=>string(24) "5b37523f517962b817000032"}["title"]=>string(4) "XKCD"["online"]=> bool(true)}
    }
    
    
    //设置查询条件
    $query = array( 'title' => 'XKCD' );
    $cursor = $collection->find( $query );
    while ( $cursor->hasNext() )
    {
        var_dump( $cursor->getNext() );
    }
    
    
}