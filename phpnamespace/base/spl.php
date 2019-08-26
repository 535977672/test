<?php
/**
 * @param string $class 完全标准的类名。
 * @return void
 */
function autoloadClass($class) {

    // 具体项目的命名空间前缀
    $prefix = 'foo\\';

    // 命名空间前缀对应的基础目录
    $base_dir = __DIR__ . '/../foo/';

    // 该类使用了此命名空间前缀？
    $len = strlen($prefix);
    //strncmp — 二进制安全比较字符串开头的若干个字符 >0 <0 =0
    if (strncmp($prefix, $class, $len) !== 0) {
        // 否，交给下一个已注册的自动加载函数
        return;
    }

    // 获取相对类名
    $relative_class = substr($class, $len);

    // 命名空间前缀替换为基础目录，
    // 将相对类名中命名空间分隔符替换为目录分隔符，
    // 附加 .php
    // test\test\est -> test/test/Test.php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // 如果文件存在，加载它
    if (file_exists($file)) {
        require_once($file);
        return true;
    }
    return false;
}

spl_autoload_register('autoloadClass');//注册到SPL __autoload函数队列尾
