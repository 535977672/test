<?php
//redis

//检查一个扩展是否已经加载。大小写不敏感。
if (!function_exists('redis')) {
    echo '不支持 redis';
    return ;
}

redis();

function redis(){
    //Redis支持数据的持久化，可以将内存中的数据保存在磁盘中，重启的时候可以再次加载进行使用。
    //Redis不仅仅支持简单的key-value类型的数据，同时还提供list，set，zset，hash等数据结构的存储。
    //Redis支持数据的备份，即master-slave模式的数据备份。
    //phpinfo();
    
    
    $handle = new RediesTest();
    //$handle->redisKey();
    //$handle->redisString();
    //$handle->redisHash();
    //$handle->redisList();
    //$handle->redisSet();
    //$handle->redisSortedSet();
    
    //$handle->redisPub();
    
    //事务
    $handle->redisTransaction();
    
    //$handle->redisConnection();

    //$handle->redisServer();
    
    //管道
    //$handle->redisPipeline();
}
    
    
class RediesTest{
    
    //键
    function redisKey(){
        //cmd D:\xampp\redis\redis-server.exe redis.conf  
        //redis-cli.exe -h 127.0.0.1 -p 6379
        $redis = new Redis();
        //host: string，服务地址 port: int,端口号 timeout: float,链接时长 (可选, 默认为 0 ，不限链接时间) 注: 在redis.conf中也有时间，默认为300
        $connect = $redis->connect('127.0.0.1',6379, 300);
        if ( !$connect ){
            return false;
        }
        $redis->auth('1234567890');
        
        $redis->flushall();//清空整个 Redis 服务器的数据(删除所有数据库的所有 key)。bool(true)  此命令从不失败。 
        //$redis->flushDB(); //flushdb 清空当前数据库中的所有 key 。 
        //var_dump($redis->info());//array(42) {["redis_version"]=>string(5) "2.2.5"...

        //1.set  del
        $redis->set('k1','1');
        $redis->del('k1');

        //2.mset  mget
        $redis->mset(array('k2'=>2,'k3'=>3,'k4'=>4,'k5'=>5,'k6'=>6,'k7'=>7));  
        $redis->del(array('k2','k3'));
        $mget = $redis->mget(array('k2','k3','k4','k5'));//array(4) { [0]=> bool(false) [1]=> bool(false) [2]=> string(1) "4" [3]=> string(1) "5" }


        //3.keys  randomkey
        //警告 :KEYS的速度非常快，但在一个大的数据库中使用它仍然可能造成性能问题，如果你需要从一个数据集中查找特定的key，你最好还是用集合(Set)。
        $redis->keys('k*');// array(4) { [0]=> string(2) "k4" [1]=> string(2) "k5" [2]=> string(2) "k6" [3]=> string(2) "k7" }
        //从当前数据库中随机返回(不删除)一个key。
        $redis->randomkey();//string(2) "k4"

        //4.ttl  -1 | >0 返回给定key的剩余生存时间(time to live)(以秒为单位)。
        //pttl 以毫秒为单位返回 key 的剩余的过期时间。 当key不存在或没有设置生存时间时，返回-1 。
        //expire //为给定key设置生存时间。当key过期时，它会被自动删除。在Redis中，带有生存时间的key被称作“易失的”(volatile)。
        //$redis->expireAt('k4',time()+100);//为key设置生存时间 时间参数是UNIX时间戳(unix timestamp)
        //$redis->pexpire();$redis->pexpireAt(); //过期时间 单位毫秒
        //$redis->pttl();//过期时间 单位毫秒
        $redis->expire('k4',100);
        $redis->ttl('k4');//100
        $redis->ttl('k1'); // -1

        //5.exists 检查给定key是否存在。
        $redis->exists('k1');//bool(false)

        //6. move 将当前数据库(默认为0)的key移动到给定的数据库db当中。
        // 如果当前数据库(源数据库)和给定数据库(目标数据库)有相同名字的给定key，或者key不存在于当前数据库，那么MOVE没有任何效果。
        $redis->flushdb();
        $redis->select(0);  //切换到指定的数据库，数据库索引号用数字值指定，以 0 作为起始索引值。 redis默认使用数据库0，为了清晰起见，这里再显式指定一次。//OK 
        $redis->mset(array('k1'=>1,'k2'=>2,'k3'=>3,'k4'=>4,'k5'=>5,'k6'=>6,'k7'=>7));  
        $redis->move('k1',1);
        $redis->mget(array('k1','k2','k3'));//false , 2, 3
        $redis->select(1);
        $redis->set('k5', 55);
        $redis->mget(array('k1','k5','k3'));//1, 55 , false
        //$redis->flushdb();
        $redis->select(0);

        //7. rename 当key和newkey相同或者key不存在时，返回一个错误。当newkey已经存在时，RENAME命令将覆盖旧值。
        //   renamenx 当且仅当newkey不存在时，将key改为newkey。如果newkey已经存在，返回0。
        $redis->rename('k2','k22');
        $redis->renamenx('k3','k4');
        $redis->mget(array('k2', 'k22','k3','k4'));//false , 2, 3,  4

        //8.type 返回key所储存的值的类型。 none(key不存在) int(0)  string(字符串) int(1)  list(列表) int(3)  set(集合) int(2)  zset(有序集) int(4)  hash(哈希表) int(5)
        $redis->type('k4');//int(1)

        //9. expire 为给定key设置生存时间。当key过期时，它会被自动删除。在Redis中，带有生存时间的key被称作“易失的”(volatile)。
        //   expireat 不同在于EXPIREAT命令接受的时间参数是UNIX时间戳(unix timestamp)。
        //   $redis->EXPIREAT('cache','1355292000');

        //10. persist 移除给定key的生存时间。
        $redis->expire('k4',100);
        $redis->persist('k4');
        //$redis->ttl('k4');//-1

        //11. dump 如果 key 不存在，那么返回 nil 。 否则，返回序列化之后的值。
        //序列化给定 key ，并返回被序列化的值，使用 RESTORE 命令可以将这个值反序列化为 Redis 键。
        //$redis->dump('k4');//false 版本低了>= 2.6.0
        //restore(''newkey', ttl, '被序列化的值')//>= 2.6.0 反序列化给定的序列化值，并将它和给定的 key 关联。 参数 ttl 以毫秒为单位为 key 设置生存时间；如果 ttl 为 0 ，那么不设置生存时间。

        //12. object
        //OBJECT subcommand [arguments [arguments]]
        //OBJECT命令允许从内部察看给定key的Redis对象。
        //
        //它通常用在除错(debugging)或者了解为了节省空间而对key使用特殊编码的情况。
        //当将Redis用作缓存程序时，你也可以通过OBJECT命令中的信息，决定key的驱逐策略(eviction policies)。
        //OBJECT命令有多个子命令：
        //
        //OBJECT REFCOUNT <key>返回给定key引用所储存的值的次数。此命令主要用于除错。
        //OBJECT ENCODING <key>返回给定key锁储存的值所使用的内部表示(representation)。
        //OBJECT IDLETIME <key>返回给定key自储存以来的空转时间(idle， 没有被读取也没有被写入)，以秒为单位。
        //对象可以以多种方式编码：
        //字符串可以被编码为raw(一般字符串)或int(用字符串表示64位数字是为了节约空间)。
        //列表可以被编码为ziplist或linkedlist。ziplist是为节约大小较小的列表空间而作的特殊表示。
        //集合可以被编码为intset或者hashtable。intset是只储存数字的小集合的特殊表示。
        //哈希表可以编码为zipmap或者hashtable。zipmap是小哈希表的特殊表示。
        //有序集合可以被编码为ziplist或者skiplist格式。ziplist用于表示小的有序集合，而skiplist则用于表示任何大小的有序集合。
        //假如你做了什么让Redis没办法再使用节省空间的编码时(比如将一个只有1个元素的集合扩展为一个有100万个元素的集合)，特殊编码类型(specially encoded types)会自动转换成通用类型(general type)。
        //时间复杂度：
        //O(1)
        //返回值：
        //REFCOUNT和IDLETIME返回数字。
        //ENCODING返回相应的编码类型。 
        $redis->select(2);
        $redis->set('k4', '34334');
        $redis->object('REFCOUNT','k4');  //1 # 只有一个引用
        sleep(5);
        $redis->OBJECT('IDLETIME','k4'); //O  # 等待一阵。。。然后查看空转时间
        $redis->GET('k4'); // # 提取k4， 让它处于活跃(active)状态  //34334
        $redis->OBJECT('IDLETIME','k4');  # 不再处于空转 //O
        $redis->OBJECT('ENCODING','k4');  # 字符串的编码方式 //string(3) "int"
        $redis->SET('phone',15820123123);  # 大的数字也被编码为字符串
        $redis->OBJECT('ENCODING','phone'); //string(3) "raw"
        $redis->SET('age',20);  # 短数字被编码为int
        $redis->OBJECT('ENCODING','age'); //string(3) "int"
        $redis->select(0);
        
        
        //13. sort 排序，分页等 
        //参数array(‘by’ => ‘some_pattern_*’,‘limit’ => array(0, 1),get’ => ‘some_other_pattern_*’ or an array of patterns,sort’ => ‘asc’ or ‘desc’,‘alpha’ => TRUE,‘store’ => ‘external-key’)
        //$redis->sort('key', array());
        //var_dump(   $redis->flushdb()    );  // 删除当前数据库所有key 此命令从不失败。总是返回 OK 。bool(true)
        
        //14. sacn() >= 2.8.0 SCAN 命令及其相关的 SSCAN 命令、 HSCAN 命令和 ZSCAN 命令都用于增量地迭代（incrementally iterate）一集元素（a collection of elements）

    }  
    
    //字符串
    //eg. 常规计数：微博数，粉丝数等。
    function redisString(){
        $redis = new Redis();
        $connect = $redis->connect('127.0.0.1',6379);
        if ( !$connect ){
            return false;
        }
        $redis->auth('1234567890');
        $redis->flushall();
        
        
        //1. set setnx setex psetex(毫秒) mset msetnx get mget getset 
        //setnx 若给定的key已经存在，则SETNX不做任何动作。SETNX是”SET if Not eXists”(如果不存在，则SET)的简写。设计模式(Design pattern): 将SETNX用于加锁(locking)
        //$redis->setex('cache_user_id', 60,10086);//60s有效期  ==》$redis->SET('key', 'value');$redis->EXPIRE('key','seconds');
        //msetnx 即使只有一个key已存在，MSETNX也会拒绝所有传入key的设置操作
        //$redis->getset('k1','1');//将给定key的值设为value，并返回key的旧值。当key存在但不是字符串类型时，返回一个错误。

        //2. setrange  getrange(substr)
        //# 情况1：对非空字符串进行SETRANGE
        $redis->SET('greeting', "hello world hello");
        $redis->SETRANGE('greeting', 6, "Redis");//"hello Redis hello"
        
        //# 情况2：对空字符串/不存在的key进行SETRANGE
        $redis->EXISTS('empty_string');//bool(false)
        $redis->SETRANGE('empty_string', 5 ,"Redis!");  # 对不存在的key使用SETRANGE //int(11)
        $redis->GET('empty_string');  # 空白处被"\x00"填充  #"\x00\x00\x00\x00\x00Redis!"   //return string(11) "Redis!"  
        $redis->GETRANGE('empty_string', 0, 6);//string(7) "Re"
        
        //3. append 如果key已经存在并且是一个字符串，APPEND命令将value追加到key原来的值之后。
        //如果key不存在，APPEND就简单地将给定key设为value，就像执行SET key value一样。
        $redis->APPEND('myphone',"nokia");  # 对不存在的key进行APPEND，等同于SET myphone "nokia" //int(5) # 字符长度
        $redis->APPEND('myphone', " - 1110");//"nokia - 1110"
        
        //4. strlen 字符串值的长度。 当 key不存在时，返回0。
        $redis->set('k1', "1234");//数字值在Redis中以字符串的形式保存
        $strlen = $redis->strlen('k1');//4
        
        //5. incr incrby incrbyfloat decr decrby
        // incr 返回值：执行INCR命令之后key的值。
        //将key中储存的数字值增一。如果key不存在，以0为key的初始值，然后执行INCR操作。如果key不存在，以0为key的初始值，然后执行INCR操作。
        // incrby 将key所储存的值加上增量increment。
        $k1 = $redis->incr('k1');//1235
        $k1 = $redis->incrBy('k1',21);//1256
        
        //6. setbit getbit 
        //setbit   对key所储存的字符串值，设置或清除指定偏移量上的位(bit)。
        //getbit   对key所储存的字符串值，获取指定偏移量上的位(bit)。
        //返回值：指定偏移量原来储存的位（"0"或"1"）.
        echo  $redis->setbit('k11',1,1);//0
        echo  $redis->setbit('k11',2,0);//0
        echo  $redis->setbit('k11',3,1);//0
        echo  $redis->setbit('k11',3,0);//1
        echo  $redis->getbit('k11',2);//0
        echo  $redis->get('k11');//@ //100
        
      
    }
    
    //哈希表hash
    //eg. 保存文章内容 存储对象。 存储部分变更的数据，如用户信息等。
    function redisHash(){
        $redis = new Redis();
        $connect = $redis->connect('127.0.0.1',6379);
        if ( !$connect ){
            return false;
        }
        $redis->auth('1234567890');
        
        $redis->flushall();//清空整个 Redis 服务器的数据(删除所有数据库的所有 key)。bool(true)  
        //Redis hash 是一个string类型的field和value的映射表，hash特别适合用于存储对象。
        //Redis 中每个 hash 可以存储 2^32 - 1 键值对（40多亿）。
        
        
        //1. hset  HSET KEY_NAME FIELD VALUE  >= 2.0.0 如果哈希表不存在，一个新的哈希表被创建并进行 HSET,操作返回 1 。如果字段已经存在于哈希表中，旧值将被覆盖,返回 0 。
        //2. hsetnx 如果哈希表不存在，一个新的哈希表被创建并进行 HSET 操作。 如果字段已经存在于哈希表中，操作无效。 如果 key 不存在，一个新哈希表被创建并执行 HSETNX 命令。
        //3. hmset 命令用于同时将多个 field-value (字段-值)对设置到哈希表中。 此命令会覆盖哈希表中已存在的字段。 如果哈希表不存在，会创建一个空哈希表，并执行 HMSET 操作。
        //4. hget hmget hgetall(以列表形式返回哈希表的字段及字段值。 若 key 不存在，返回空列表)
        $redis->hSet('k1', 'y1', '1');//1
        $redis->hSet('k1', 'y1', '2');//0
        
        $redis->hGet('k1','y1');//2
        
        $redis->hSetNx('k2', 'y1', '1');//true
        $redis->hSetNx('k2', 'y1', '2');//false
        
        $redis->hset('k3', 'y1', '1');//1
        $redis->hMSet('k3', array('y1'=>2,'y2'=>3));//true
        $redis->hMget('k3', array('y1','y2'));//array("y1"=>2,"y2"=>3)
        
        $redis->hGetAll('k3');//array("y1"=>2,"y2"=>3)
        $redis->hGetAll('k4');//array()
        
        //5. hvals 命令返回哈希表所有字段的值。键名重置
        //6. hkeys 命令用于获取哈希表中的所有字段名。
        //7. hlen 用于获取哈希表中字段的数量。
        $redis->hVals('k3');//array(2,3)
        $redis->hVals('k1');//array(2)
        
        $redis->hKeys('k3');//array('y1','y2')
        $redis->hKeys('k2');//array('y1')
        
        $redis->hLen('k1');//1
        $redis->hLen('k3');//2
        
        //8. hexists 哈希表的指定字段是否存在
        //9. hdel 命令用于删除哈希表 key 中的一个或多个指定字段，不存在的字段将被忽略。 被成功删除字段的数量，不包括被忽略的字段。
        $redis->hExists('k2','y1');//true
        $redis->hDel('k2','y1');//1
        $redis->hExists('k2','y1');//false
       
        //10. hincrby 命令用于为哈希表中的字段值加上指定增量值。
        //增量也可以为负数，相当于对指定字段进行减法操作。 如果哈希表的 key 不存在，一个新的哈希表被创建并执行 HINCRBY 命令。
        //如果指定的字段不存在，那么在执行命令前，字段的值被初始化为 0 。
        //对一个储存字符串值的字段执行 HINCRBY 命令将造成一个错误。本操作的值被限制在 64 位(bit)有符号数字表示之内。
        //11. hincrbyfloat >= 2.6.0
        $redis->hGet('k1','y1');//2
        $redis->hIncrBy('k1','y1', 3);//5
        //$redis->hIncrByFloat('k1','y1', 6.09);//false
        
        //12. public function hscan ($str_key, &$i_iterator, $str_pattern, $i_count) {} 迭代哈希表中的键值对。
        //http://doc.redisfans.com/key/scan.html
    }
    
    //表list 头元素指的是列表左端/前端第一个元素，尾元素指的是列表右端/后端第一个元素。
    //eg. 消息队列 秒杀 订单系统-发货系统分离
    //在Redis中我们的最新微博ID使用了常驻缓存，这是一直更新的。但是做了限制不能超过5000个ID，因此获取ID的函数会一直询问Redis。
    //只有在start/count参数超出了这个范围的时候，才需要去访问数据库。
    function redisList(){
        $redis = new Redis();
        $connect = $redis->connect('127.0.0.1',6379);
        if ( !$connect ){
            return false;
        }
        $redis->auth('1234567890');
        $redis->flushall();
        
        //1. lpush 返回值：列表的长度。
        //lpush 将一个或多个值value插入到列表key的表头。 返回执行LPUSH命令后，列表的长度。注解:在Redis 2.4版本以前的LPUSH命令，都只接受单个value值。
        //如果key不存在，一个空列表会被创建并执行LPUSH操作。当key存在但不是列表类型时，返回一个错误。
        //如果有多个value值，那么各个value值按从左到右的顺序依次插入到表头：比如对一个空列表(mylist)执行LPUSH mylist a b c，则结果列表为c b a，等同于执行执行命令LPUSH mylist a、LPUSH mylist b、LPUSH mylist c。
        $redis->lPush('k1', array('1', '2', '3'));//1 >2.4.0
        $redis->lPush('k1', 1);//2
        $redis->lPush('k1', 2);//3
        //lrange 返回列表中指定区间内的元素，区间以偏移量 START 和 END 指定。
        //以-1表示列表的最后一个元素，-2表示列表的倒数第二个元素
        $redis->lrange('k1',0, -1);//array(2,1,'array')
        
        //2. lpushx 将值value插入到列表key的表头，当且仅当key存在并且是一个列表。
        //3. rpush 将一个或多个值value插入到列表key的表尾。如果key不存在，一个空列表会被创建并执行RPUSH操作。当key存在但不是列表类型时，返回一个错误。
        //4. rpushx
        $redis->lPushx('k1', 3);//4
        $redis->rPush('k1', 4);//5
        $redis->rPushx('k2', 5);//0        
        
        //5. LPOP(移除并返回列表key的头元素) RPOP
        //BLPOP BRPOP  列表的阻塞式(blocking)弹出原语。
        //它是LPOP命令的阻塞版本，当给定列表内没有任何元素可供弹出的时候，连接将被BLPOP命令阻塞，直到等待超时或发现可弹出元素为止。
        //当给定多个key参数时，按参数key的先后顺序依次检查各个列表，弹出第一个非空列表的头元素。
        //
        //非阻塞行为
        //当BLPOP被调用时，如果给定key内至少有一个非空列表，那么弹出遇到的第一个非空列表的头元素，并和被弹出元素所属的列表的名字一起，组成结果返回给调用者。
        //当存在多个给定key时，BLPOP按给定key参数排列的先后顺序，依次检查各个列表。
        //假设现在有job、 command和request三个列表，其中job不存在，command和request都持有非空列表。考虑以下命令：
        //BLPOP job command request 0 //超时参数设为0表示阻塞时间可以无限期延长(block indefinitely)
        //BLPOP保证返回的元素来自command，因为它是按”查找job -> 查找command -> 查找request“这样的顺序，第一个找到的非空列表。
        //
        //阻塞行为
        //如果所有给定key都不存在或包含空列表，那么BLPOP命令将阻塞连接，直到等待超时，或有另一个客户端对给定key的任意一个执行LPUSH或RPUSH命令为止。
        //超时参数timeout接受一个以秒为单位的数字作为值。超时参数设为0表示阻塞时间可以无限期延长(block indefinitely) 。
        //
        //相同的key被多个客户端同时阻塞
        //相同的key可以被多个客户端同时阻塞。
        //不同的客户端被放进一个队列中，按”先阻塞先服务”(first-BLPOP，first-served)的顺序为key执行BLPOP命令。
        //
        //在MULTI/EXEC事务中的BLPOP
        //BLPOP可以用于流水线(pipline,批量地发送多个命令并读入多个回复)，但把它用在MULTI/EXEC块当中没有意义。因为这要求整个服务器被阻塞以保证块执行时的原子性，该行为阻止了其他客户端执行LPUSH或RPUSH命令。
        //因此，一个被包裹在MULTI/EXEC块内的BLPOP命令，行为表现得就像LPOP一样，对空列表返回nil，对非空列表弹出列表元素，不进行任何阻塞操作。
        //
        //如果列表为空，返回一个nil。
        //反之，返回一个含有两个元素的列表，第一个元素是被弹出元素所属的key，第二个元素是被弹出元素的值。
        $redis->lrange('k1',0, -1);//array(3,2,1,'array',4)
        $redis->lPop('k1');//3
        $redis->rPop('k1');//4
        $redis->blPop('k1',1, 300);//array('k1', 2)//超时参数timeout接受一个以秒为单位的数字作为值。超时参数设为0表示阻塞时间可以无限期延长(block indefinitely) 。
        $redis->brPop('k1',1);//array('k1', 'array')
        $redis->lrange('k1',0, -1);//array(1)
        
        $redis->brPop('k2',2);//array()
        $redis->brPop(array('k2', 'k1'), 1);//array('k1', 1)
        $redis->lrange('k1',0, -1);//array()
        
        //6. llen 长度  如果key不是列表类型，返回一个错误。
        $redis->lPush('k1', 1);
        $redis->lLen('k1');//1
        
        //7. lrem  lrem($key,$value,$count) 
        //根据参数count的值，移除列表中与参数value相等的元素。
        //count的值可以是以下几种：
        //count > 0: 从表头开始向表尾搜索，移除与value相等的元素，数量为count。
        //count < 0: 从表尾开始向表头搜索，移除与value相等的元素，数量为count的绝对值。
        //count = 0: 移除表中所有与value相等的值。
        //返回值 被移除元素的数量。 因为不存在的key被视作空表(empty list)，所以当key不存在时，LREM命令总是返回0。
        $redis->flushdb();
        $redis->lPush('k1', 1); $redis->lPush('k1', 2); $redis->lPush('k1', 1); $redis->lPush('k1', 1); $redis->lPush('k1', 1);
        $redis->lrange('k1',0, -1);//array(1,1,1,2,1)
        $redis->lrem('k1',1,1);//1 //array(1,1,2,1)
        $redis->lrem('k1',1,-1);//1 //array(1,1,2)
        $redis->lrem('k1',1,0);//2 //array(2)
        
        //8. lindex 返回列表key中，下标为index的元素。以-1表示列表的最后一个元素，-2表示列表的倒数第二个元素
        //9. lset 将列表key下标为index的元素的值甚至为value。
        //10. ltrim 对一个列表进行修剪(trim)，就是说，让列表只保留指定区间内的元素，不在指定区间之内的元素都将被删除。
        //11. linsert LINSERT key BEFORE|AFTER pivot value 将值value插入到列表key当中，位于  第一个 值pivot之前或之后。返回值:列表的长度
        $redis->lPush('k2', 1);
        $redis->lPush('k2', 2);
        $redis->lindex('k2',1);//1
        $redis->lSet('k2',1,6);//true array(2,6)
        $redis->ltrim('k2',0,-2);//true  array(2)
        
        $redis->lInsert('k2', Redis::AFTER, 2, 21);//2
        $redis->lInsert('k2', Redis::AFTER, 2, 21);//3
        $redis->lInsert('k2', Redis::BEFORE, 2, 2);//4
        $redis->lInsert('k2', Redis::AFTER, 2, 21) ;//5
        $redis->lInsert('k2', Redis::BEFORE, 2, 22);//6
        
        //12. rpoplpush brpoplpush阻塞
        //RPOPLPUSH source destination  BRPOPLPUSH source destination timeout
        //命令RPOPLPUSH在一个原子时间内，执行以下两个动作：
        //将列表source中的最后一个元素(尾元素)弹出，并返回给客户端。
        //将source弹出的元素插入到列表destination，作为destination列表的的头元素。
        //举个例子，你有两个列表source和destination，source列表有元素a, b, c，destination列表有元素x, y, z，执行RPOPLPUSH source destination之后，source列表包含元素a, b，destination列表包含元素c, x, y, z ，并且元素c被返回。
        //如果source不存在，值nil被返回，并且不执行其他动作。
        //
        //如果source和destination相同，则列表中的表尾元素被移动到表头，并返回该元素，可以把这种特殊情况视作列表的旋转(rotation)操作。        
        $redis->lrange('k1',0, -1);//array(2)
        $redis->lrange('k2',0, -1);//array(22,2,21,2,21,21)
        
        $redis->brpoplpush('k2', 'k1', 1);//21
        
        $redis->lrange('k1',0, -1);//array(21,2)
        $redis->lrange('k2',0, -1);//array(22,2,21,2,21)
        
        //设计模式： 一个安全的队列
        //Redis的列表经常被用作队列(queue)，用于在不同程序之间有序地交换消息(message)。一个程序(称之为生产者，producer)通过LPUSH命令将消息放入队列中，而另一个程序(称之为消费者，consumer)通过RPOP命令取出队列中等待时间最长的消息。
        //不幸的是，在这个过程中，一个消费者可能在获得一个消息之后崩溃，而未执行完成的消息也因此丢失。
        //使用RPOPLPUSH命令可以解决这个问题，因为它在返回一个消息之余，还将该消息添加到另一个列表当中，另外的这个列表可以用作消息的备份表：假如一切正常，当消费者完成该消息的处理之后，可以用LREM命令将该消息从备份表删除。
        //最后，还可以添加一个客户端专门用于监视备份表，它自动地将超过一定处理时限的消息重新放入队列中去(负责处理该消息的客户端可能已经崩溃)，这样就不会丢失任何消息了。

        //循环列表
        //通过使用相同的 key 作为 RPOPLPUSH 命令的两个参数，客户端可以用一个接一个地获取列表元素的方式，取得列表的所有元素，而不必像 LRANGE 命令那样一下子将所有列表元素都从服务器传送到客户端中(两种方式的总复杂度都是 O(N))。
        //以上的模式甚至在以下的两个情况下也能正常工作：
        //有多个客户端同时对同一个列表进行旋转(rotating)，它们获取不同的元素，直到所有元素都被读取完，之后又从头开始。
        //有客户端在向列表尾部(右边)添加新元素
        //这个模式使得我们可以很容易实现这样一类系统：有 N 个客户端，需要连续不断地对一些元素进行处理，而且处理的过程必须尽可能地快。一个典型的例子就是服务器的监控程序：它们需要在尽可能短的时间内，并行地检查一组网站，确保它们的可访问性。
        //注意，使用这个模式的客户端是易于扩展(scala)且安全(reliable)的，因为就算接收到元素的客户端失败，元素还是保存在列表里面，不会丢失，等到下个迭代来临的时候，别的客户端又可以继续处理这些元素了。
        
    }    

    // 集合 set
    /*
        Redis的Set是string类型的无序集合。集合成员是唯一的，这就意味着集合中不能出现重复的数据。
        Redis 中 集合是通过哈希表实现的，所以添加，删除，查找的复杂度都是O(1)。
        集合中最大的成员数为 2^32 - 1 (4294967295, 每个集合可存储40多亿个成员)。        
        A = {'a', 'b', 'c'}
        B = {'a', 'e', 'i', 'o', 'u'}

        inter(x, y): 交集，在集合x和集合y中都存在的元素。
        inter(A, B) = {'a'}

        union(x, y): 并集，在集合x中或集合y中的元素，如果一个元素在x和y中都出现，那只记录一次即可。
        union(A,B) = {'a', 'b', 'c', 'e', 'i', 'o', 'u'}

        diff(x, y): 差集，在集合x中而不在集合y中的元素。
        diff(A,B) = {'b', 'c'}

        card(x): 基数，一个集合中元素的数量。
        card(A) = 3

        空集： 基数为0的集合。
      
       在微博应用中，可以将一个用户所有的关注人存在一个集合中，将其所有粉丝存在一个集合。
       Redis还为集合提供了求交集、并集、差集等操作，可以非常方便的实现如共同关注、共同喜好、二度好友等功能，
       对上面的所有集合操作，你还可以使用不同的命令选择将结果返回给客户端还是存集到一个新的集合中。
     */ 
    function redisSet(){
        $redis = new Redis();
        $connect = $redis->connect('127.0.0.1',6379);
        if ( !$connect ){
            return false;
        }
        $redis->auth('1234567890');
        $redis->flushall();
        
        //1. sadd 返回值:添加数量 在Redis2.4版本以前，SADD只接受单个member值。
        //2. smembers     members 成员
        //3. srem     移除 返回：被成功移除的元素的数量
        //4. sismember 判断member元素是否是集合key的成员。
        $redis->sAdd('k1',1);//1
        $redis->sAdd('k1',2);//1
        $redis->sAdd('k1',2);//0 //集合中不能出现重复的数据
        $redis->srem('k1',2);//1
        $redis->sMembers('k1');//array(1)
        $redis->sismember('k1',1);//true
        
        //5. scard 返回集合key的基数(集合中元素的数量)。
        //6. smove 将member元素从source集合移动到destination集合。
        //如果source集合不存在或不包含指定的member元素，则SMOVE命令不执行任何操作，仅返回0。否则，member元素从source集合中被移除，并添加到destination集合中去。
        //当destination集合已经包含member元素时，SMOVE命令只是简单地将source集合中的member元素删除。
        //当source或destination不是集合类型时，返回一个错误。
        $redis->sMove('k1', 'k2', 1);//true
        $redis->scard('k2') ;//1
        
        //7. spop 移除并返回集合中的一个随机元素。
        //8. srandmember 返回集合中的一个随机元素。
        $redis->sAdd('k2',4);//1
        $redis->sRandMember('k2');//1 //返回
        $redis->sPop('k2');//1 //移除并返回
        
        //9. sinter  SINTER key [key ...]返回一个集合的全部成员，该集合是所有给定集合的交集。不存在的key被视为空集。
        //10. sinterstore  SINTERSTORE destination key [key ...]此命令等同于SINTER，但它将结果保存到destination集合，而不是简单地返回结果集。如果destination集合已经存在，则将其覆盖。如果destination集合已经存在，则将其覆盖。
        //11. sunion  12. sunionstore 并集  
        //13. sdiff 14. sdiffstore 差集
        $redis->sAdd('k3',1);
        $redis->sAdd('k3',2);
        $redis->sAdd('k3',3);
        $redis->sAdd('k3',4);
        $redis->sAdd('k3',5);
        
        $redis->sAdd('k4',5);
        $redis->sAdd('k4',6);
        $redis->sAdd('k4',7);
        $redis->sAdd('k4',8);
        $redis->sAdd('k4',9);
        
        $redis->sInter('k3','k4');//array(5)
        $redis->sInterStore('kdesinter','k3','k4');//1
        
        $redis->sUnion('k3','k4');//array(1,2,3,4,5,6,7,8,9)
        $redis->sUnionStore('kdesunion','k3','k4');//9
        
        $redis->sDiff('k3','k4');//array(1,2,3,4)
        $redis->sDiffStore('kdesdiff','k3','k4');//4
    }    
    
    //有序集合sorted set 
    /*
        Redis 有序集合和集合一样也是string类型元素的集合,且不允许重复的成员。
        不同的是每个元素都会关联一个double类型的分数。redis正是通过分数来为集合中的成员进行从小到大的排序。
        有序集合的成员是唯一的,但分数(score)却可以重复。
        集合是通过哈希表实现的，所以添加，删除，查找的复杂度都是O(1)。 集合中最大的成员数为 2^32 - 1 (4294967295, 每个集合可存储40多亿个成员)。
     * 
     * 排行榜应用 做带权重的队列 需要精准设定过期时间的应用 
     * 
     * 
     */
    function redisSortedSet(){
        $redis = new Redis();
        $connect = $redis->connect('127.0.0.1',6379);
        if ( !$connect ){
            return false;
        }
        $redis->auth('1234567890');
        $redis->flushall();
        
        //1. zadd  在Redis2.4版本以前，ZADD每次只能添加一个元素。
        //将一个或多个member元素及其score值加入到有序集key当中。如果某个member已经是有序集的成员，那么更新这个member的score值，并通过重新插入这个member元素，来保证该member在正确的位置上。
        //返回值  被成功添加的新成员的数量，不包括那些被更新的、已经存在的成员。
        $redis->zAdd('z1', 10.2210, 'z1');
        $redis->zAdd('z1', 10.1, 'z2');
        $redis->zAdd('z1', 10.1, 'z3');
        $redis->zAdd('z1', 10, 'z4');
        $redis->zAdd('z1', 10.2, 'z5');
        
        //2. zrem 在Redis2.4版本以前，ZREM每次只能删除一个元素。
        $redis->zRem('z1', 'z5');
        
        //3. zcard 数量 有序集key的基数
        $redis->zCard('z1');//4
        
        //4. zcount 返回有序集key中，score值在min和max之间(默认包括score值等于min或max)的成员数量。
        $redis->zCount('z1', '10', '10.1');//3
        
        //5. zscore 返回有序集key中，成员member的score值。
        $redis->zScore('z1', 'z1');//10.221
        
        //6. zincrby 为有序集key的成员member的score值加上增量increment。当key不存在，或member不是key的成员时，ZINCRBY key increment member等同于ZADD key increment member。
        $redis->zIncrBy('z1', 1, 'z1');//11.221
        
        //7. zrange 返回有序集key中，指定区间内的成员。返回有序集key中，指定区间内的成员。具有相同score值的成员按字典序(lexicographical order)来排列。
        //8. zrevrange ZRANGE key start stop [WITHSCORES]其中成员的位置按score值递减(从大到小)来排列。
        $redis->zRange('z1', 0, 3);//array(z4,z2,z3,z1)
        $redis->zRevRange('z1', 0, 3);
        
        //9. zrangebyscore ZRANGEBYSCORE key min max [WITHSCORES] [LIMIT offset count]返回有序集key中，所有score值介于min和max之间(包括等于min或max)的成员。有序集成员按score值递增(从小到大)次序排列。
        //10. zrerangebyscore
        $redis->zRangeByScore('z1', 10, 10.1);////array(z4,z2,z3)
        $redis->zRevRangeByScore('z1', 10.1, 10);
        
        //11. zrank 等级，排名 返回有序集key中成员member的排名。其中有序集成员按score值递增(从小到大)顺序排列。排名以0为底，也就是说，score值最小的成员排名为0。
        //12. zrerank  ZREMRANGEBYRANK key start stop
        $redis->zRank('z1', 'z1');//3
        $redis->zRevRank('z1', 'z1');//0
        
        //13. zremrangebyrank ZREMRANGEBYRANK key start stop 移除有序集key中，指定排名(rank)区间内的所有成员。
        //14. zremrangebyscore 移除有序集key中，所有score值介于min和max之间(包括等于min或max)的成员。
        $redis->zRemRangeByRank('z1', 0, 1);//2
        $redis->zRemRangeByScore('z1', 10, 12);//2
        
        //15. ZINTERSTORE destination numkeys key [key ...] [WEIGHTS weight [weight ...]] [AGGREGATE SUM|MIN|MAX]
        //计算给定的一个或多个有序集的交集，其中给定key的数量必须以numkeys参数指定，并将该交集(结果集)储存到destination。
        //返回：保存到 destination 的结果集的基数。
        $redis->zAdd('k1', 10, 'a');
        $redis->zAdd('k1', 11, 'b');
        $redis->zAdd('k1', 15, 'c');
        $redis->zAdd('k1', 18, 'd');
        
        $redis->zAdd('k2', 10, 'a');
        $redis->zAdd('k2', 11, 'b');
        $redis->zAdd('k2', 15, 'e');
        $redis->zAdd('k2', 18, 'd');
        
        $redis->zinterstore('k3', array('k1', 'k2'), array(4,3), 'MIN');//3
        $redis->zRange('k3', 0, -1);//array(a,b,d)   array(30,33,54)
        
        $redis->zunionstore('k4', array('k1', 'k2'), array(4,3), 'MIN');//5
        $redis->zRange('k4', 0, -1);
        
        
        //16. ZUNIONSTORE destination numkeys key [key ...] [WEIGHTS weight [weight ...]] [AGGREGATE SUM|MIN|MAX]
        //计算给定的一个或多个有序集的并集，其中给定key的数量必须以numkeys参数指定，并将该并集(结果集)储存到destination。
        //WEIGHTS
        //使用WEIGHTS选项，你可以为每个给定有序集分别指定一个乘法因子(multiplication factor)，每个给定有序集的所有成员的score值在传递给聚合函数(aggregation function)之前都要先乘以该有序集的因子。
        //如果没有指定WEIGHTS选项，乘法因子默认设置为1。
        //
        //AGGREGATE
        //使用AGGREGATE选项，你可以指定并集的结果集的聚合方式。
        //默认使用的参数SUM，可以将所有集合中某个成员的score值之和作为结果集中该成员的score值；使用参数MIN，可以将所有集合中某个成员的最小score值作为结果集中该成员的score值；而参数MAX则是将所有集合中某个成员的最大score值作为结果集中该成员的score值。        
    
    }     
    
    //Redis的Pub/Sub系统可以构建实时的消息系统 
    function redisPub(){
        set_time_limit(0);
        $redis = new Redis();
        $connect = $redis->connect('127.0.0.1',6379);
        if ( !$connect ){
            return false;
        }
        $redis->auth('1234567890');
        //$redis->flushall();
         
        //1.publish channel message 将信息 message 发送到指定的频道 channel 。
        //返回: 接收到信息 message 的订阅者数量。
        echo $redis->publish('subscribe1', '苹果');
         
        //2.PUBSUB <subcommand> [argument [argument ...]] >= 2.8.0
        //查看订阅与发布系统状态的内省命令， 它由数个不同格式的子命令组成， 以下将分别对这些子命令进行介绍。
        
        //3.PUNSUBSCRIBE [pattern [pattern ...]]
        //指示客户端退订所有给定模式。
        
        //4.SUBSCRIBE channel [channel ...] 订阅给定的一个或多个频道的信息。
        //返回: 接收到的信息
        //$redis->subscribe(array('subscribe1', 'subscribe2'), function($re, $re2, $re3){
        //    var_dump($re);//object(Redis)#2 (1) { ["socket"]=> resource(3) of type (Redis Socket Buffer) }
        //    var_dump($re2);//string(10) "subscribe1"
        //    var_dump($re3);//string(6) "苹果"
        //    return false;
        //});
       
    }   
    
    //Transaction 事务
    function redisTransaction(){
        $redis = new Redis();
        $connect = $redis->connect('127.0.0.1',6379);
        if ( !$connect ){
            return false;
        }
        $redis->auth('1234567890');
        //$redis->flushall();
         
        //1. watch 监视一个(或多个) key ，如果在事务执行之前这个(或这些) key 被其他命令所改动，那么事务将被打断。总是返回 OK 。
        //2. unwatch 取消 WATCH 命令对所有 key 的监视。如果在执行 WATCH 命令之后， EXEC 命令或 DISCARD 命令先被执行了的话，那么就不需要再执行 UNWATCH 了。总是返回 OK 。
        //因为 EXEC 命令会执行事务，因此 WATCH 命令的效果已经产生了；而 DISCARD 命令在取消事务的同时也会取消所有对 key 的监视，因此这两个命令执行之后，就没有必要执行 UNWATCH 了。
        
        //3. multi 标记一个事务块的开始。总是返回 OK 。
        //4. discard 取消事务，放弃执行事务块内的所有命令。总是返回 OK 。
        //5. exec 执行所有事务块内的命令。
        //如某个(或某些) key 正处于 WATCH 命令的监视之下，且事务块中有和这个(或这些) key 相关的命令，那么 EXEC 命令只在这个(或这些) key 没有被其他命令所改动的情况下执行并生效，否则该事务被打断(abort)。
        
        if(empty($_GET['sb'])){
            $redis->watch('k1');//监视 k1
            //$redis->set('k1',13); //k1保存，事务被打断
            //$redis->unwatch(); //取消监视
            $redis->multi();//开启事务
            var_dump($redis->set('k1',13));
            $redis->set('k1',1);
            $redis->set('k2',2);
            sleep(1);
            //$redis->discard();//取消事务 取消监视
            $redis->exec();//提交事务 取消监视
        }else{
            $redis->set('k1',11);
        }
        
    }     

    //Connection 连接
    function redisConnection(){
        $redis = new Redis();
        $connect = $redis->connect('127.0.0.1',6379);
        //$connect = $redis->pconnect('127.0.0.1',6379);
        if ( !$connect ){
            return false;
        }
        $redis->auth('1234567890');
        $redis->flushall();
         
        //1. Select 命令用于切换到指定的数据库，数据库索引号 index 用数字值指定，以 0 作为起始索引值。
        //2. Auth 命令用于检测给定的密码和配置文件中的密码是否相符。
        //通过设置配置文件中 requirepass 项的值(使用命令 CONFIG SET requirepass password )，可以使用密码来保护 Redis 服务器。
        //如果开启了密码保护的话，在每次连接 Redis 服务器之后，就要使用 AUTH 命令解锁，解锁之后才能使用其他 Redis 命令。
        //如果 AUTH 命令给定的密码 password 和配置文件中的密码相符的话，服务器会返回 OK 并开始接受命令输入。
        //反之，如果密码不匹配的话，服务器将返回一个错误，并要求客户端需重新输入密码。
        //警告:因为 Redis 高性能的特点，在很短时间内尝试猜测非常多个密码是有可能的，因此请确保使用的密码足够复杂和足够长，以免遭受密码猜测攻击。
        
        //3. Quit 命令用于关闭与当前客户端与redis服务的连接。
        //4. Ping 命令使用客户端向 Redis 服务器发送一个 PING ，如果服务器运作正常的话，会返回一个 PONG 。
        //5. Echo 命令用于打印给定的字符串。测试用
        
        $requirepass = $redis->config('get','requirepass');
        if($requirepass['requirepass'] == false){
            $redis->config('set','requirepass', '1234567890');
        }
        $redis->set('k1', 1);
        $redis->select(15);//0~15
        $redis->set('k1', 1);
        
        $redis->ping();//"+PONG"
        $redis->sendEcho('111');//"111"
        
        //var_dump(   $redis->quit()    );//undefined method Redis::quit()
    }    
    
    //Connection 连接
    function redisServer(){
        $redis = new Redis();
        $connect = $redis->connect('127.0.0.1',6379);
        if ( !$connect ){
            return false;
        }
        $redis->auth('1234567890');
        
        //1.flushall 清空整个 Redis 服务器的数据(删除所有数据库的所有 key )。
        //2.flushdb 清空当前数据库中的所有 key。
        //$redis->flushall();
        
        //3.CONFIG GET parameter
        $redis->config('get','requirepass');
        
        //4.BGSAVE 命令执行之后立即返回 OK ，然后 Redis fork 出一个新子进程，原来的 Redis 进程(父进程)继续处理客户端请求，而子进程则负责将数据保存到磁盘，然后退出。
        //客户端可以通过 LASTSAVE 命令查看相关信息，判断 BGSAVE 命令是否执行成功。请移步 持久化文档 查看更多相关细节。
        
        //6.LASTSAVE 返回最近一次 Redis 成功将数据保存到磁盘上的时间，以 UNIX 时间戳格式表示。
        
        //5.info
        $r = $redis->info();
        ksort($r);
        //print_r(   $r  );
        
        /*
            Array
            (
                [allocation_stats] => 1=1,4=259,57=2......
                [aof_enabled] => 0
                [arch_bits] => 32
                [bgrewriteaof_in_progress] => 0
                [bgsave_in_progress] => 0
                [blocked_clients] => 0
                [changes_since_last_save] => 2
                [client_biggest_input_buf] => 0
                [client_longest_output_list] => 0
                [connected_clients] => 1
                [connected_slaves] => 0
                [db0] => keys=1,expires=0
                [evicted_keys] => 0
                [expired_keys] => 0
                [hash_max_zipmap_entries] => 512
                [hash_max_zipmap_value] => 64
                [keyspace_hits] => 32
                [keyspace_misses] => 0
                [last_save_time] => 1489460011
                [loading] => 0
                [lru_clock] => 48274
                [mem_fragmentation_ratio] => 1.00
                [multiplexing_api] => winsock2
                [process_id] => 444
                [pubsub_channels] => 0
                [pubsub_patterns] => 0
                [redis_git_dirty] => 0
                [redis_git_sha1] => 0
                [redis_version] => 2.2.5
                [role] => master
                [total_commands_processed] => 695
                [total_connections_received] => 106
                [uptime_in_days] => 0
                [uptime_in_seconds] => 4236
                [use_tcmalloc] => 0
                [used_cpu_sys] => 0.09
                [used_cpu_sys_childrens] => 0.00
                [used_cpu_user] => 0.78
                [used_cpu_user_childrens] => 0.00
                [used_memory] => 425652
                [used_memory_human] => 415.68K
                [used_memory_rss] => 425652
                [vm_enabled] => 0
            )
         */
    }   
    
    /**
     * 管道
     */
    function redisPipeline(){
        $redis = new Redis();
        $connect = $redis->connect('127.0.0.1',6379);
        if ( !$connect ){
            return false;
        }
        $redis->auth('1234567890');
        
        $redis->select(10);
        $redis->flushDB();
        
        set_time_limit(0);
        
        $pipe = $redis->multi(Redis::PIPELINE); //1.3680789470673
        //$pipe = $redis->multi(); //29.154567956924
        $t1 = microtime(true);
        for ($i = 0; $i <  100000; $i++) {   
            $pipe->set("key::$i", str_pad($i, 4, '0', 0));   
            $pipe->get("key::$i");   
        }   
      
        $pipe->exec(); 
 
        echo microtime(true)-$t1 . PHP_EOL;
        
        $redis->select(0);
    }
}

/*

Key（键）
DEL
DUMP
EXISTS
EXPIRE
EXPIREAT
KEYS
MIGRATE
MOVE
OBJECT
PERSIST
PEXPIRE
PEXPIREAT
PTTL
RANDOMKEY
RENAME
RENAMENX
RESTORE
SORT
TTL
TYPE
SCAN


String（字符串）
APPEND
BITCOUNT
BITOP
DECR
DECRBY
GET
GETBIT
GETRANGE
GETSET
INCR
INCRBY
INCRBYFLOAT
MGET
MSET
MSETNX
PSETEX
SET
SETBIT
SETEX
SETNX
SETRANGE
STRLEN


Hash（哈希表）
HDEL
HEXISTS
HGET
HGETALL
HINCRBY
HINCRBYFLOAT
HKEYS
HLEN
HMGET
HMSET
HSET
HSETNX
HVALS
HSCAN


List（列表）
BLPOP
BRPOP
BRPOPLPUSH
LINDEX
LINSERT
LLEN
LPOP
LPUSH
LPUSHX
LRANGE
LREM
LSET
LTRIM
RPOP
RPOPLPUSH
RPUSH
RPUSHX


Set（集合）
SADD
SCARD
SDIFF
SDIFFSTORE
SINTER
SINTERSTORE
SISMEMBER
SMEMBERS
SMOVE
SPOP
SRANDMEMBER
SREM
SUNION
SUNIONSTORE
SSCAN


SortedSet（有序集合）
ZADD
ZCARD
ZCOUNT
ZINCRBY
ZRANGE
ZRANGEBYSCORE
ZRANK
ZREM
ZREMRANGEBYRANK
ZREMRANGEBYSCORE
ZREVRANGE
ZREVRANGEBYSCORE
ZREVRANK
ZSCORE
ZUNIONSTORE
ZINTERSTORE
ZSCAN


Pub/Sub（发布/订阅）
PSUBSCRIBE
PUBLISH
PUBSUB
PUNSUBSCRIBE
SUBSCRIBE
UNSUBSCRIBE



Transaction（事务）
DISCARD
EXEC
MULTI
UNWATCH
WATCH
Script（脚本）
EVAL
EVALSHA
SCRIPT EXISTS
SCRIPT FLUSH
SCRIPT KILL
SCRIPT LOAD


Connection（连接）
AUTH
ECHO
PING
QUIT
SELECT


Server（服务器）
BGREWRITEAOF
BGSAVE
CLIENT GETNAME
CLIENT KILL
CLIENT LIST
CLIENT SETNAME
CONFIG GET
CONFIG RESETSTAT
CONFIG REWRITE
CONFIG SET
DBSIZE
DEBUG OBJECT
DEBUG SEGFAULT
FLUSHALL
FLUSHDB
INFO
LASTSAVE
MONITOR
PSYNC
SAVE
SHUTDOWN
SLAVEOF
SLOWLOG
SYNC
TIME


*/









