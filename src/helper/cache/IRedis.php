<?php
/**
 * Created by PhpStorm.
 * User: gjianbo
 * Date: 2017-06-16
 * Time: 10:31
 */

namespace yqn\helper\cache;


class IRedis
{

    //创建静态私有的变量保存该类对象
    static private  $instance=null;

    protected $handler = null;
    protected $options = [
        'host'       => '127.0.0.1',
        'port'       => 6379,
        'password'   => '',
        'select'     => 1,
        'timeout'    => 0,
        'expire'     => 0,
        'persistent' => false,
        'prefix'     => '',
    ];

    /**
     * Redis constructor.
     */
    public function __construct($config=null)
    {
        if (!extension_loaded('redis')) {
            throw new \BadFunctionCallException('not support: redis');
        }
        if(!empty($config)){
            $this->options=array_merge($this->options,$config);
        }
        $func          = $this->options['persistent'] ? 'pconnect' : 'connect';
        $this->handler = new \Redis;
        $this->handler->$func($this->options['host'], $this->options['port'], $this->options['timeout']);

        if ('' != $this->options['password']) {
            $this->handler->auth($this->options['password']);
        }

        if (0 != $this->options['select']) {
            $this->handler->select($this->options['select']);
        }
    }


    /**
     *获取缓存
     * @param $config
     * @return null|Redis
     */
    static public function  getInstance($config){
        if (!self::$instance instanceof self) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }
    /**

    /**
     * 设置缓存
     * @param $name string 缓存的名称
     * @param $val  string 缓存的内容
     * @param $expired int 缓存时间(0表示永久缓存)
     * @return  bool    TRUE if the command is successful.
     */
    public function setCache($name,$val,$expired=3600){
        return $this->handler->set($this->options['prefix'].$name,$val,$expired);
    }

    /**
     * 获取缓存的内容
     * @param $name string 缓存的名称
     * @return bool|string
     */
    public function getCache($name){
        return $this->handler->get($this->options['prefix'].$name);
    }

    /**
     * 删除缓存
     * @param $name string 缓存的名称
     * @return int
     */
    public function delCache($name){
        return $this->handler->del($this->options['prefix'].$name);

    }



}