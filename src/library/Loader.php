<?php
/**
 * Created by PhpStorm.
 * User: gjianbo
 * Date: 2018/8/10
 * Time: 13:35
 */

namespace yqn\tplus;

class Loader
{
    /**
     * @var array 实例数组
     */
    protected static $instance = [];

    // 当前库的全局命名空间
    protected static $namespace = 'yqn\\tplus';

    // 基础认证类
    protected static $baseauth = null;

    // 接口配置的信息
    public static $config = null;


    /**
     * 加载基础认证的类
     * @param null $config
     */
    public static function baseAuth($config)
    {
        return IBaseAuth::getInstance($config);

        /*if ($config !== null) {
            return  IBaseAuth::getInstance($config);
        }else{
            return self::$baseauth = IBaseAuth::getInstance(self::$config);
        }*/

    }

    /**
     * @param string $name
     * @param null $baseauth
     * @return mixed
     * @throws \Exception
     */
    public static function model($name = '', $baseauth)
    {
        $uid = $name;
        if (isset(self::$instance[$uid])) {
            return self::$instance[$uid];
        }

        //判断是否指定认证基类

        /*if(empty($baseauth)){
            //self::$baseauth=$baseauth;
        }*/
        /* if (empty($baseauth)) {
             if (empty(self::$config)) {
                 throw new \Exception('Please first call baseAuth function ');
             }
             $baseauth = IBaseAuth::getInstance(self::$config);
         }*/
        //解析类名
        $class = self::parseClass($name);

        if (class_exists($class)) {
            $model = new $class($baseauth);
        } else {
            throw new \Exception('class not exists' . $class);
        }

        return self::$instance[$uid] = $model;
    }

    /**
     * 按类名解析成真实的类名
     * @param $name   string    要解析的类名
     * @return string           解析后的类名
     */
    public static function parseClass($name)
    {
        return '\\' . self::$namespace . '\\I' . ucfirst($name);
    }
}