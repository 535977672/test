<?php
//apc
/*
    apc_add — 缓存一个变量到数据存储
    apc_bin_dump — Get a binary dump of the given files and user variables
    apc_bin_dumpfile — Output a binary dump of cached files and user variables to a file
    apc_bin_load — Load a binary dump into the APC file/user cache
    apc_bin_loadfile — Load a binary dump from a file into the APC file/user cache
    apc_cache_info — Retrieves cached information from APC's data store
    apc_cas — 用新值更新旧值
    apc_clear_cache — 清除APC缓存
    apc_compile_file — Stores a file in the bytecode cache, bypassing all filters
    apc_dec — Decrease a stored number
    apc_define_constants — Defines a set of constants for retrieval and mass-definition
    apc_delete_file — Deletes files from the opcode cache
    apc_delete — 从用户缓存中删除某个变量
    apc_exists — 检查APC中是否存在某个或者某些key
    apc_fetch — 从缓存中取出存储的变量
    apc_inc — 递增一个储存的数字
    apc_load_constants — Loads a set of constants from the cache
    apc_sma_info — Retrieves APC's Shared Memory Allocation information
    apc_store — Cache a variable in the data store

    APCIterator — APCIterator 类
    APCIterator::__construct — 构造一个 APCIterator 迭代器对象
    APCIterator::current — 获取当前项
    APCIterator::getTotalCount — 获取总数
    APCIterator::getTotalHits — 获取缓存命中数
    APCIterator::getTotalSize — 获取所有缓存的尺寸大小
    APCIterator::key — 获取迭代器的键
    APCIterator::next — 移到下一项
    APCIterator::rewind — 倒退迭代器
    APCIterator::valid — 检查当前位置是否有效
 */


//检查一个扩展是否已经加载。大小写不敏感。
if (!extension_loaded('apc')) {
    echo 'no suport apc';
    echo "</br>在php5.3.* 之后的版本自带php_opcache，不再需要apc的opcode缓存功能 </br>apc的3.1.14版本在php5.5版本上有严重的内存问题，被官方废弃。最新可用的apc版本为3.1.13，仅支持php 5.3.* 。所以，如果你的php版本是5.3.*之后的版本，那意味着你不再能使用apc！</br>";
    //return ;
}

if (!extension_loaded('apcu')) {
    echo 'no suport apcu';
    echo "</br>在php5.3.* 之后的版本自带php_opcache，不再需要apc的opcode缓存功能 </br>apc的3.1.14版本在php5.5版本上有严重的内存问题，被官方废弃。最新可用的apc版本为3.1.13，仅支持php 5.3.* 。所以，如果你的php版本是5.3.*之后的版本，那意味着你不再能使用apc！</br>";
    //return ;
}

