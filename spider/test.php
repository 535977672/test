<?php
require  __DIR__ . '/vendor/autoload.php';
use phpspider\core\phpspider;
use phpspider\core\requests;
use phpspider\core\db;

/* Do NOT delete this comment */
/* 不要删除这段注释 */


$configs = array(
    'name' => 'test',//定义当前爬虫名称
    //'tasknum' => 1,
    'log_show' => false,//是否显示日志 为true时显示调试信息 为false时显示爬取面板
    'log_file' => __DIR__ .'/log/log.log', //日志文件路径
    //'log_type' => 'info',
    //'input_encoding' => '',//明确指定输入的页面编码格式(UTF-8,GB2312,…..)，防止出现乱码,如果设置null则自动识别
    //'output_encoding' => '',//明确指定输出的编码格式(UTF-8,GB2312,…..)，防止出现乱码,如果设置null则为utf-8
    
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
        'table' => '数据表',  // 如果数据表没有数据新增请检查表结构和字段名是否匹配
    ),
    //数据库配置
    'db_config'=>[
        'host'  => '127.0.0.1',
        'port'  => 3306,
        'user'  => 'root',
        'pass'  => 'root',
        'name'  => 'demo'
    ],
    
    //定义爬虫爬取哪些域名下的网页, 非域名下的url会被忽略以提高爬取速度
    'domains' => array(
        'www.baidu.com'
    ),
    //scan_urls 定义爬虫的入口链接, 爬虫从这些链接开始爬取,同时这些链接也是监控爬虫所要监控的链接
    'scan_urls' => array(
        "http://www.baidu.com/",
        "http://www.baidu.com/",
        "http://www.baidu.com/",
        "http://www.baidu.com/",
    ),
    //定义列表页url的规则 对于有列表页的网站, 使用此配置可以大幅提高爬虫的爬取速率
    'list_url_regexes' => array(
        "http://www.baidu.com/index_\d+.html",
        "http://www.baidu.com/index_\d+.html",
        "http://www.baidu.com/index_\d+.html",
    ),
    //content_url_regexes 定义内容页url的规则
    'content_url_regexes' => array(
        "http://www.baidu.com/\d+.html",
        "http://www.baidu.com/\d+.html",
        "http://www.baidu.com/\d+.html",
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
            'selector' => "//div[@id='Article']//h1",
            'required' => true,
        ),
        // 分类
        array(
            'name' => "category",
            'selector' => "//div[contains(@class,'crumbs')]//span//a",
            'required' => true,
        ),
        // 发布时间
        array(
            'name' => "addtime",
            'selector' => "//p[contains(@class,'sub-info')]//span",
            'required' => true,
        ),
        // API URL
        array(
            'name' => "url",
            'selector' => "//p[contains(@class,'sub-info')]//span",
            'required' => true,
        ),
        // 图片
        array(
            'name' => "image",
            'selector' => "//*[@id='big-pic']//a//img",
            'required' => true,
        ),
        // 内容
        array(
            'name' => "content",
            'selector' => "//div[@id='pages']//a//@href",
            'repeated' => true,
            'required' => true,
            'children' => array(
                array(
                    // 抽取出其他分页的url待用
                    'name' => 'content_page_url',
                    'selector' => "//text()"
                ),
                array(
                    // 抽取其他分页的内容
                    'name' => 'page_content',
                    // 发送 attached_url 请求获取其他的分页数据
                    // attached_url 使用了上面抓取的 content_page_url
                    'source_type' => 'attached_url',
                    'attached_url' => 'content_page_url',
                    'selector' => "//*[@id='big-pic']//a//img"
                ),
            ),
        ),
    ),
);

//$spider = new phpspider($configs);

//回调函数是在爬虫爬取并处理网页的过程中设置的一些系统钩子, 通过这些钩子可以完成一些特殊的处理逻辑.

$spider->on_start = function($phpspider) 
{
    $db_config = $phpspider->get_config("db_config");
    //print_r($db_config);
    //exit;
    // 数据库连接
    db::set_connect('default', $db_config);
    db::_init();
};

$spider->on_extract_field = function($fieldname, $data, $page) 
{
    if ($fieldname == 'url') 
    {
        $data = $page['request']['url'];
    }
    elseif ($fieldname == 'name') 
    {
        $data = trim(preg_replace("#\(.*?\)#", "", $data));
    }
    if ($fieldname == 'addtime') 
    {
        $data = strtotime(substr($data, 0, 19));
    }
    elseif ($fieldname == 'content') 
    {
        $contents = $data;
        $array = array();
        foreach ($contents as $content) 
        {
            $url = $content['page_content'];
            // md5($url) 过滤重复的URL
            $array[md5($url)] = $url;

            //// 以纳秒为单位生成随机数
            //$filename = uniqid().".jpg";
            //// 在data目录下生成图片
            //$filepath = PATH_ROOT."/images/{$filename}";
            //// 用系统自带的下载器wget下载
            //exec("wget -q {$url} -O {$filepath}");
            //$array[] = $filename;
        }
        $data = implode(",", $array);
    }
    return $data;
};

$category = array(
    '丝袜美女' => 'siwameitui',
    '唯美写真' => 'weimeixiezhen',
    '性感美女' => 'xingganmeinv',
    '明星美女' => 'mingxingmeinv',
    '清纯美女' => 'qingchunmeinv',
    '美女模特' => 'meinvmote',
);

$spider->on_extract_page = function($page, $data) use ($category)
{
    if (!isset($category[$data['category']])) 
    {
        return false;
    }
    
    $data['dir'] = $category[$data['category']];
    $data['content'] = $data['image'].','.$data['content'];
    $data['image'] = str_replace("ocnt0imhl.bkt.clouddn.com", "file.13384.com", $data['image']);
    $data['image'] = $data['image']."?imageView2/1/w/320/h/420";
    $data['content'] = str_replace("ocnt0imhl.bkt.clouddn.com", "file.13384.com", $data['content']);
    $sql = "Select Count(*) As `count` From `meinv_content` Where `name`='{$data['name']}'";
    $row = db::get_one($sql);
    if (!$row['count']) 
    {
        db::insert("meinv_content", $data);
    }
    return $data;
};

$spider->start();

