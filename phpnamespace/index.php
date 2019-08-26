<?php

//PSR4自动加载命名空间规范

// [ 应用入口文件 ]
namespace foo;

use base\Psr4AutoloaderClass;
use foo\Example1;
use foo\example\Example1 as E2;

require __DIR__ . '/base/Psr4AutoloaderClass.php';

$loader = new Psr4AutoloaderClass;
$loader->register();
//$loader->addNamespace('base', __DIR__ . '/base');
$loader->addNamespace('foo', __DIR__ . '/core');

$e = new Example1();
var_dump($e->getName());

$e = new E2('code');
var_dump($e->getName());
