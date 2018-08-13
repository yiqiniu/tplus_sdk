<?php
/**
 * Created by PhpStorm.
 * User: gjianbo
 * Date: 2018/8/10
 * Time: 11:09
 */

use yqn\tplus\Loader  as tplusLoader;

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
function tplus_baseAuth($config=null){
    return tplusLoader::baseAuth($config);
}

/**
 * 获取对应类
 * @param $name         string  要获取的类名
 * @param $baseauth     object  基础认证的类
 * @return object       返回要获取的类
 */
function tplus_load($name,$baseauth=null){
    try{
        return tplusLoader::model($name,$baseauth);
    }
    catch (\Exception $e){
        var_dump($e);
        return null;
    }
}

