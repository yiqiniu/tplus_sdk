<?php
/**
 * Created by PhpStorm.
 * User: gjianbo
 * Date: 2018/8/10
 * Time: 13:35
 */

namespace yqn\chanjet;


use yqn\chanjet\exception\ClassNotFoundException;

class Loader
{
    /**
     * @var array 实例数组
     */
    protected static $instance = [];

    /**
     * @var array 类名映射
     */
    protected static $map = [];

    protected static $namespace = 'yqn\\chanjet';

    protected static $baseauth=null;
    public static $config=null;


    /**
     * 加载基础认证的类
     * @param null $config
     */
    public static function baseAuth($config=null){
        if($config!==null){
            self::$config=$config;
        }
        self::$baseauth=IBaseAuth::getInstance(self::$config);
        return self::$baseauth;
    }

    /**
     * @param string $name
     * @param null $baseauth
     * @return mixed
     * @throws \Exception
     */
    public static function model($name = '',$baseauth=null)
    {
        $uid = $name ;
        if (isset(self::$instance[$uid])) {
            return self::$instance[$uid];
        }

        //
        if(!empty($baseauth)){
            self::$baseauth=$baseauth;
        }
        if (empty(self::$baseauth)){
            if(empty(self::$config)) {
                throw new \Exception('Please first call baseAuth:');
            }
            self::$baseauth=IBaseAuth::getInstance(self::$config);
        }


        $class = self::parseClass($name);

        if (class_exists($class)) {
            $model = new $class(self::$baseauth);
        } else {
            throw new \Exception('class not exists:' . $class);
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
         return '\\'.self::$namespace . '\\I' .ucfirst($name);
     }
}