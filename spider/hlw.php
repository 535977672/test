<?php
require  __DIR__ . '/vendor/autoload.php';
use phpspider\core\phpspider;
use phpspider\core\requests;
use phpspider\core\db;

/* Do NOT delete this comment */
/* 不要删除这段注释 */

//华龙网新闻
//http://www.cqnews.net
$configs = array(
    'name' => 'hlw',//定义当前爬虫名称
    //'tasknum' => 1,
    'log_show' => false,//是否显示日志 为true时显示调试信息 为false时显示爬取面板
    'log_file' => __DIR__ .'/log/log.log', //日志文件路径
    //'log_type' => 'info',
    'input_encoding' => 'UTF-8',//明确指定输入的页面编码格式(UTF-8,GB2312,…..)，防止出现乱码,如果设置null则自动识别
    'output_encoding' => 'UTF-8',//明确指定输出的编码格式(UTF-8,GB2312,…..)，防止出现乱码,如果设置null则为utf-8
    
    //'tasknum' => 1,//同时工作的爬虫任务数 需要配合redis保存采集任务数据，供进程间共享使用
    //'multiserver' => false, //多服务器处理 需要配合redis来保存采集任务数据，供多服务器共享数据使用
    //'serverid' => 1,//服务器ID

    //'save_running_state' => false, //保存爬虫运行状态 需要配合redis来保存采集任务数据，供程序下次执行使用
    //'queue_config' => (
    //    'host'      => '127.0.0.1',
    //    'port'      => 6379,
    //    'pass'      => '',
    //    'db'        => 5,
    //    'prefix'    => 'phpspider',
    //    'timeout'   => 30,
    //),//redis配置
    
    //'proxy' =>['http://host:port', 'http://user:pass@host:port'], //代理服务器 如果爬取的网站根据IP做了反爬虫, 可以设置此项
    //'max_try'=> 0, //爬虫爬取每个网页失败后尝试次数
    //'max_depth'=> 1, //爬虫爬取网页深度，超过深度的页面不再采集
    //'max_fields'=> 1, //爬虫爬取内容网页最大条数 抓取到一定的字段后退出
    
    //'interval'=> 100, //爬虫爬取每个网页的时间间隔 毫秒
    //'timeout'=> 100, //爬虫爬取每个网页的超时时间 秒
    
    //'user_agent'=> phpspider::AGENT_PC,  //爬虫爬取网页所使用的浏览器类型
    //'user_agent'=>"Mozilla/5.0",
    //'user_agent'=> [], //随机浏览器类型，用于破解防采集
    
    //'client_ip' => '127.0.0.1',//爬虫爬取网页所使用的伪IP，用于破解防采集
    //'client_ip' => [],//随机伪造IP，用于破解防采集
    
    
    //export 爬虫爬取数据导出
    //type：导出类型 csv、sql、db
    //file：导出 csv、sql 文件地址
    //table：导出db、sql数据表名
    'export' => array(
        'type' => 'db',
        'table' => 'spider_hlw',  // 如果数据表没有数据新增请检查表结构和字段名是否匹配
    ),
    //数据库配置
    'db_config'=>[
        'host'  => '127.0.0.1',
        'port'  => 3306,
        'user'  => 'root',
        'pass'  => 'root',
        'name'  => 'test'
    ],
    
    //定义爬虫爬取哪些域名下的网页, 非域名下的url会被忽略以提高爬取速度
    'domains' => array(
        'www.cqnews.net',
        'cq.cqnews.net',
        'news.cqnews.net',
        'house.cqnews.net',
        'life.cqnews.net'
    ),
    
    //入口页 
    //列表页
    //内容页
    
    //scan_urls 定义爬虫的入口链接, 爬虫从这些链接开始爬取,同时这些链接也是监控爬虫所要监控的链接
    'scan_urls' => array(
        "http://www.cqnews.net",
    ),
    //定义列表页url的规则 对于有列表页的网站, 使用此配置可以大幅提高爬虫的爬取速率
    //'list_url_regexes' => array(
    //    "http://www.baidu.com/index_\d+.html",
    //),
    //content_url_regexes 定义内容页url的规则
    'content_url_regexes' => array(
        'http://\S+.cqnews.net/html/\d{4}-\d{2}/\d{2}/content_\d+.html'
    ),
    
    //fields 定义内容页的抽取规则 规则由一个个field组成, 一个field代表一个数据抽取项
    //name 数据变量名
    //selector 定义抽取规则, 默认使用xpath 如果使用其他类型的, 需要指定selector_type
    //selector_type 抽取规则的类型 目前可用xpath, jsonpath, regex
    //required 定义该field的值是否必须, 默认false 赋值为true的话, 如果该field没有抽取到内容, 该field对应的整条数据都将被丢弃
    //repeated 定义该field抽取到的内容是否是有多项, 默认false 赋值为true的话, 无论该field是否真的是有多项, 抽取到的结果都是数组结构
    //children 为此field定义子项 树形结构
    //
    //source_type 该field的数据源, 默认从当前的网页中抽取数据
    //选择attached_url可以发起一个新的请求, 然后从请求返回的数据中抽取
    //选择url_context可以从当前网页的url附加数据中抽取
    //attached_url 当source_type设置为attached_url时, 定义新请求的url
    'fields' => array(
        // 标题
        array(
            'name' => "name",
            'selector' => "//div[@class='left_news']//h1",
            'required' => false,
        ),
        // 内容
        array(
            'name' => "content",
            'selector' => "//div[@class='wenziduanluo')]//div[@id='main_text']",
            'required' => false,
        )
    ),
);

$spider = new phpspider($configs);

//回调函数是在爬虫爬取并处理网页的过程中设置的一些系统钩子, 通过这些钩子可以完成一些特殊的处理逻辑.

$spider->on_start = function($phpspider) 
{
    $db_config = $phpspider->get_config("db_config");
    //print_r($db_config);
    //Array
    //(
    //    [host] => 127.0.0.1
    //    [port] => 3306
    //    [user] => root
    //    [pass] => root
    //    [name] => test
    //)
    //exit;
    // 数据库连接
    db::set_connect('default', $db_config);
    db::_init();
    
    //requests::set_header("Referer", "http://www.cqnews.net");
    //requests::set_cookie(rr,33);
    //requests::set_cookies($cookies);
};

//抽取一个url爬取
//判断当前网页是否被反爬虫了, 需要开发者实现
$spider->on_status_code = function($status_code, $url, $content, $phpspider) 
{
    //print_r($status_code);
    // 如果状态码为429，说明对方网站设置了不让同一个客户端同时请求太多次
    if ($status_code == '429') 
    {
        // 将url插入待爬的队列中,等待再次爬取
        $phpspider->add_url($url);
        // 当前页先不处理了
        return false; 
    }

    
    //print_r($content);exit;//页面以下载
    
    // 不拦截的状态码这里记得要返回，否则后面内容就都空了
    return $content;
};

$spider->is_anti_spider = function($url, $content, $phpspider) 
{
    // $content中包含"404页面不存在"字符串
    if (strpos($content, "404页面不存在") !== false) 
    {
        // 如果使用了代理IP，IP切换需要时间，这里可以添加到队列等下次换了IP再抓取
        // $phpspider->add_url($url);
        return true; // 告诉框架网页被反爬虫了，不要继续处理它
    }
    // 当前页面没有被反爬虫，可以继续处理
    return false;
};

//下载url对应的网页
//网页下载完成之后调用. 主要用来对下载的网页进行处理
$spider->on_download_page = function($page, $phpspider) 
{
    //print_r($page);exit();
    
    return $page;
};

//内容页下载
//网页下载完成之后调用. 主要用来对下载的网页进行处理
$spider->on_download_attached_page = function($content, $phpspider) 
{
    print_r($content);exit();
    return $content;
};

//在爬取到入口url的内容之后, 添加新的url到待爬队列之前调用. 
//主要用来发现新的待爬url, 并且能给新发现的url附加数据
$spider->on_scan_page = function($page, $content, $phpspider) 
{
    //$phpspider->add_url($url);
    //print_r($content);exit();
    
    preg_match_all("#http://\S+.cqnews.net/html/\d{4}-\d{2}/\d{2}/content_\d+.html#",$content,$match);
    //var_dump($match);exit();
    //array(1) {
    //  [0]=>
    //  array(102) {
    //    [0]=>
    //    string(60) "http://news.cqnews.net/html/2019-07/05/content_50549755.html"
    //    [1]=>
    //    string(60) "http://news.cqnews.net/html/2019-07/05/content_50549951.html"
    //    [2]=>
    //    string(60) "http://news.cqnews.net/html/2019-07/05/content_50549948.html"
    //    [3]=>
    
    if(!empty($match[0])){
        foreach ($match[0] as $key => $value) {
            $phpspider->add_url($value);
        }
    }
    
    //返回false 通知爬虫不再从当前网页中发现待爬url
    return false;
};

//爬取到入口url的内容之后, 添加新的url到待爬队列之前调用. 
//主要用来发现新的待爬url, 并且能给新发现的url附加数据
$spider->on_list_page = function($page, $content, $phpspider) 
{
    //print_r($page);exit();
    return false;
};

//爬取到入口url的内容之后, 添加新的url到待爬队列之前调用. 
//主要用来发现新的待爬url, 并且能给新发现的url附加数据
$spider->on_content_page = function($page, $content, $phpspider) 
{
    //print_r($content);exit;
    
//    preg_match_all('#document\.location\.href = document\.getElementById\("content"\)\.innerText;#',$content,$match);
//    if(!empty($match[0])){
//        return false;
//    }
    
    return false;
};

//在抽取到field内容之后调用,对其中包含的img标签进行回调处理
//很多网站对图片作了延迟加载, 这时候就需要在这个函数里面来处理
//$spider->on_handle_img = function($fieldname, $img) 
//{
//    return $img;
//};

//当一个field的内容被抽取到后进行的回调, 在此回调中可以对网页中抽取的内容作进一步处理
$spider->on_extract_field = function($fieldname, $data, $page) 
{
    //print_r($data);exit;
    return $data;
};



//在一个网页的所有field抽取完成之后, 可能需要对field进一步处理, 以发布到自己的网站
$spider->on_extract_page = function($page, $data)
{
    $dataes = [];
    $dataes['id'] = 0;
    $dataes['name'] = str_replace('\s+', '', str_replace('&#13;', '', $data['name']));
    $dataes['content'] = htmlentities($data['content']);
    var_dump($data);
    if(!empty($dataes['name'])){
        //db::insert("spider_hlw", $dataes);
        return $dataes;
        exit;
    }else{
        return false;
    }
};

$spider->start();

