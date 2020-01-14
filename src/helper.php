<?php
/**
 * Created by PhpStorm.
 * User: gjianbo
 * Date: 2018/8/10
 * Time: 11:09
 */

use yqn\helper\Debug as tplusDebug;
use yqn\tplus\Loader  as tplusLoader;

define('TPLUS_START_TIME', microtime(true));
define('TPLUS_START_MEM', memory_get_usage());


/**
 *  设置接口要使用的信息,参考实例说明
 * @param $config
 */
function tplus_config($config){
    tplusLoader::$config=$config;
}
/**
 * 获取基础认证的类
 * @param $config array  配置参数
 * @return object        返回基础认证的对象
 */
function tplus_baseAuth($config)
{
    return tplusLoader::baseAuth($config);
}

/**
 * 获取对应类
 * @param $name         string  要获取的类名
 * @param $baseauth     object  基础认证的类
 * @return object       返回要获取的类
 */
function tplus_load($name, $baseauth)
{
    try{
        return tplusLoader::model($name,$baseauth);
    }
    catch (\Exception $e){
        var_dump($e);
        return null;
    }
}

/**
 * 记录时间（微秒）和内存使用情况
 * @param string $start 开始标签
 * @param string $end 结束标签
 * @param integer|string $dec 小数位 如果是m 表示统计内存占用
 * @return mixed
 */
function tplus_debug($start, $end = '', $dec = 6)
{
    if ('' == $end) {
        tplusDebug::remark($start);
    } else {
        return 'm' == $dec ? tplusDebug::getRangeMem($start, $end) : tplusDebug::getRangeTime($start, $end, $dec);
    }
}


function tplus_dump($var, $echo = true, $label = null)
{
    return tplusDebug::dump($var, $echo, $label);
}
