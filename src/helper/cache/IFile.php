<?php
/**
 * Created by PhpStorm.
 * User: gjianbo
 * Date: 2018/8/15
 * Time: 9:36
 */

namespace yqn\helper\cache;


class IFile
{
    /**
     * 缓存路径
     * @var null
     */
    protected $cache_path = null;

    //创建静态私有的变量保存该类对象
    static private  $instance=null;


    /**
     * Redis constructor.
     */
    private function __construct($path=null)
    {
        if(!empty($path)){
            $this->cache_path=$path;
        }
    }


    /**
     *获取缓存
     * @param $config
     * @return null|File
     */
    static public function  getInstance($path){
        if (!self::$instance instanceof self) {
            self::$instance = new self($path);
        }
        return self::$instance;
    }

    /**
     * 缓存配置与存储
     * @param string $name 缓存名称
     * @param string $value 缓存内容
     * @param int $expired 缓存时间(0表示永久缓存)
     * @throws LocalCacheException
     */
    public function setCache($name, $value = '', $expired = 3600)
    {
        $cache_file = $this->getCacheName($name);
        $content = serialize(['name' => $name, 'value' => $value, 'expired' => time() + intval($expired)]);
        if (!file_put_contents($cache_file, $content)) {
            throw new LocalCacheException('local cache error.', '0');
        }
    }

    /**
     * 获取缓存内容
     * @param string $name 缓存名称
     * @return null|mixed
     */
    public function getCache($name)
    {
        $cache_file = $this->getCacheName($name);
        if (file_exists($cache_file) && ($content = file_get_contents($cache_file))) {
            $data = unserialize($content);
            if (isset($data['expired']) && (intval($data['expired']) === 0 || intval($data['expired']) >= time())) {
                return $data['value'];
            }
            $this->delCache($name);
        }
        return null;
    }

    /**
     * 移除缓存文件
     * @param string $name 缓存名称
     * @return bool
     */
    public function delCache($name)
    {
        $cache_file = $this->getCacheName($name);
        return file_exists($cache_file) ? unlink($cache_file) : true;
    }

    /**
     * 应用缓存目录
     * @param string $name
     * @return string
     */
    private function getCacheName($name)
    {
        if (empty($this->cache_path)) {
            $this->cache_path = dirname(__DIR__) . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR;
        }
        $this->cache_path = rtrim($this->cache_path, '/\\') . DIRECTORY_SEPARATOR;
        file_exists($this->cache_path) || mkdir($this->cache_path, 0755, true);
        return $this->cache_path . $name;
    }



}