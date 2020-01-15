<?php
/**
 * Created by PhpStorm.
 * User: gjianbo
 * Date: 2018/8/1
 * Time: 10:06
 */

namespace yqn\tplus;

use yqn\helper\Debug;
use yqn\helper\Tools;

abstract class IBaseSdk
{


    protected $_oauth = null;

    //日志文件名前缀
    protected $logfile = '';
    //使用post 进行数据操作, baseAuth::httpPost的别名函数
    protected $httpPost = null;
    //使用post 进行数据操作, baseAuth::httpGet的别名函数
    protected $httpGet = null;
    // 操作名
    protected $opName = '';
    //子类的类名 若符合规则,可自动生成操作名
    protected $clsName = '';

    //日志保存的方式,最后一条或内容追加,true 为追加  false最后一次
    protected $log_append = true;
    //操作菜单
    protected $_opAction = [
        // 查询
        'query' => 'Query',
        // 创建
        'create' => 'Create',
        // 修改
        'update' => 'Update',
        // 删除
        'delete' => 'Delete'
    ];
    //查询时返回的字段
    protected $selectfield = '';
    /**
     * 参数查询前缀
     * @var array
     */
    protected $param_prefix = [
        'query' => 'param',
        'create' => 'dto',
        'update' => 'dto',
        'delete' => 'dto',
    ];

    // 请求头部
    protected $header = [];

    /**
     * ChanjetSdk constructor.
     * @param array  配置参数
     */
    public function __construct(IBaseAuth $auth)
    {
        $this->_oauth = $auth;
        //获取当前的类型
        $name = get_class($this);
        $this->clsName = substr(strrchr($name, "\\"), 1);
        //根据类名 生成 opName
        //规则: 类名的第一位,必须是大写'I'开头,操作名=除第1位外首字母小写的其他字符
        if (substr($this->clsName, 0, 1) == 'I') {
            $this->opName = lcfirst(substr($this->clsName, 1));
        }

        //生成日志的文件名
        $this->logfile = Tools::$log_path . DIRECTORY_SEPARATOR . date('Ym') . DIRECTORY_SEPARATOR . date('d') . '_' . $this->clsName . '_log.txt';
        //判断日否日否可以追加
        $this->log_append = isset($auth->_config['log']['append']) ? $auth->_config['log']['append'] : true;
        //调用其他初始化操作
        $this->initiaize();
    }


    /**
     * 调用其他的初始化构造函数
     */
    protected function initiaize()
    {
    }

    /**
     * 写日志
     * @param $str string/array  日志内容
     */
    public function writelog($str)
    {
        Tools::writeLogger($this->logfile, $str, $this->log_append);
    }

    /**
     * 记录访问日志
     * @param $url string       访问的url
     * @param string $method 访问的方式 get  post
     * @param string $data 访问的数据
     * @param bool $retdata 返回的结果
     * @param string $time 耗时
     */
    protected function accesslog($url, $method = 'post', $data = '', $retdata = false, $time = '')
    {
        if ($this->_oauth->debug) {
            $data = [
                'type' => 'accesslog',
                'classname' => $this->clsName,
                'url' => $url,
                'method' => $method,
                'uses_time' => $time,
                'querydata' => $data,
                'return' => $retdata
            ];
            //访问日志每次清空
            Tools::writeLogger($this->logfile, $data, $this->log_append);
        }

    }

    /**
     * 记录错误的日志
     * @param $name     string     访问的操作类型和名称
     * @param string $arguments 访问的数据
     */
    protected function errorlog($name, $arguments = '', $errinfo = [])
    {
        $data = [
            'type' => 'errorlog',
            'classname' => $this->clsName,
            'name' => $name,
            'arguments' => $arguments,
            'error' => $errinfo
        ];
        Tools::writeLogger($this->logfile, $data, $this->log_append);
    }

    /**
     * 魔术方式 用于处理统一的发送和和处理
     * @param $name
     * @param $arguments
     * @return bool|string
     */
    public function __call($name, $arguments)
    {
        list($type, $name) = explode('_', $name);
        if ($this->opName == '') {
            $this->opName = lcfirst($this->clsName);
        }
        if (!in_array($type, array('post', 'get')) || !isset($this->_opAction[$name]) || $name == '' || $this->opName == '') {
            $this->errorlog($name, $arguments, ['msg' => '访问时验证失败',
                '$this->opName' => $this->opName,
                'name' => $name,
                'type' => $type,
                'opAction' => $this->_opAction,
            ]);
            return false;
        }
        Debug::remark('begin');
        $url = $this->opName . '/' . $this->_opAction[$name];
        $retdata = false;
        switch ($type) {
            case 'post':
                $retdata = $this->_oauth->httpPost($url, $arguments[0],
                    isset($arguments[1]) ? $arguments[1] : false,
                    $this->header);
                break;
            case 'get':
                $retdata = $this->_oauth->httpGet($url, $arguments[0],
                    isset($arguments[1]) ? $arguments[1] : false,
                    $this->header
                );
                break;
        }
        Debug::remark('end');

        $this->accesslog($url, $type, $arguments[0], $retdata, Debug::getRangeTime('begin', 'end', 6) . 's');

        return $retdata;
    }

    /**
     * 查询接口,外部可进行操作
     * @param array $where 要查询的条件
     * @param string $perfix 操作的前缀如: param ,dto
     * @return mixed
     */

    public function query($where = [], $perfix = '')
    {
        if (is_string($where)) {
            $perfix = $where;
            $where = [];
        }
        if (empty($perfix) && isset($this->param_prefix['query'])) {
            $perfix = $this->param_prefix['query'];
        }
        //如果指定了返回字段,查询是按返回的字段为主
        if (!isset($where['SelectFields']) && !empty($this->selectfield)) {
            $where['SelectFields'] = $this->selectfield;
        }
        //生成要发送的数据
        $senddata = ($perfix . '=') . (empty($where) ? '{}' : json_encode($where));
        return $this->post_query($senddata);
    }

    /**
     * 添加数据接口,以完成默认操作,必须在子类调用,外部不能直接访问
     * @param array $data 要添加的数据
     * @param string $perfix 操作的前缀如: param ,dto
     * @return mixed
     */
    public function create($data = [], $perfix = '')
    {
        if (empty($data)) {
            return false;
        }
        if (empty($perfix) && isset($this->param_prefix['create'])) {
            $perfix = $this->param_prefix['create'];
        }
        $senddata = ($perfix . '=') . json_encode($data);
        return $this->post_create($senddata);
    }

    /**
     * 修改接口,以完成默认操作,必须在子类调用,外部不能直接访问
     * @param array $data 要修改的数据
     * @param string $perfix 操作的前缀如: param ,dto
     * @return mixed
     */
    protected function update($data = [], $perfix = '')
    {
        if (empty($data)) {
            return false;
        }
        if (empty($perfix) && isset($this->param_prefix['update'])) {
            $perfix = $this->param_prefix['update'];
        }
        $senddata = ($perfix . '=') . json_encode($data);
        return $this->post_update($senddata);
    }

    /**
     *
     * 删除接口
     * @param array $where 要删除数据
     * @param string $perfix 操作的前缀如: param ,dto
     * @return mixed
     */
    protected function delete($where = [], $perfix = '')
    {
        if (empty($where)) {
            return false;
        }
        if (empty($perfix) && isset($this->param_prefix['delete'])) {
            $perfix = $this->param_prefix['delete'];
        }
        $senddata = $perfix . '=' . json_encode($where);
        return $this->post_delete($senddata);
    }


}