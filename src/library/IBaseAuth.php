<?php
/**
 * Created by PhpStorm.
 * User: gjianbo
 * Date: 2018/8/1
 * Time: 10:39
 */

namespace yqn\tplus;


use Firebase\JWT\JWT;
use InvalidArgumentException;
use yqn\helper\Http;
use yqn\helper\Tools;


class IBaseAuth
{

    //创建静态私有的变量保存该类对象
    static private $instance = [];
    //保存已生成的token
    private $_access_token = '';

    //使用使用云企业IDURL
    const ORGID_URL = 'collaborationapp/GetAnonymousTPlusToken?IsFree=1';
    //使用用户名登录的URL
    const USERNAME_URL = 'collaborationapp/GetRealNameTPlusToken?IsFree=1';
    //私key
    private $_privekey = '';
    //header
    protected $_header = null;
    //缓存有效期
    private $_token_timeout = 3600 * 12;

    //已生成有签名
    private $_sign = '';
    //runtime路径
    private $_runtime_path = '';
    //是否开始调试
    private $debug = false;


    //T+配置文件
    private $_tplusconfig = null;

    //全部的配置文件
    private $_config = [
        'api' => [
            'serverUrl' => '',
            "appKey" => '',
            "appSecret" => '',
            'privceKey' => '',
            'orgid' => '',
            'cachePath' => ''
        ],
        'api_debug' => false,
        //默认缓存和日志保存的位置
        'runtime' => '',
        'cache' => [
            'type' => 'file'
        ],
        'log' => [
            'append' => true,
        ]
    ];


    /**
     * ChanjetOAuth constructor.
     * @param $_config
     */
    private function __construct($config)
    {
        //全部配置
        $this->_config = array_merge($this->_config, $config);
        //tplus的配置
        $this->_tplusconfig = $this->_config['api'];
        if (empty($this->_tplusconfig['serverUrl'])) {
            throw new InvalidArgumentException("Missing Config -- [serverUrl]");
        }

        if (empty($this->_tplusconfig['appKey'])) {
            throw new InvalidArgumentException("Missing Config -- [appKey]");
        }
        if (empty($this->_tplusconfig['appSecret'])) {
            throw new InvalidArgumentException("Missing Config -- [appSecret]");
        }
        if (empty($this->_tplusconfig['appPrivateKey'])) {
            throw new InvalidArgumentException("Missing Config -- [appPrivateKey]");
        }
        if (!file_exists($this->_tplusconfig['appPrivateKey'])) {
            throw new InvalidArgumentException("privceKey File not Exist");
        }
        $this->_privekey = file_get_contents($this->_tplusconfig['appPrivateKey']);
        //运行保存的路径
        $this->_runtime_path = '../' . dirname(__DIR__) . DIRECTORY_SEPARATOR . 'runtime' . DIRECTORY_SEPARATOR;


        //是否自动记录日志
        if (!empty($this->_config['api_debug'])) {
            $this->debug = $this->_config['api_debug'];
        }
        //设置缓存的路径
        if (!empty($this->_config['runtime'])) {
            $this->_runtime_path = $this->_config['runtime'];
        }
        $this->initialize();

    }

    /**
     * 初始化缓存变量
     * @return mixed|null|string
     */
    private function initialize()
    {


        // 设置日志的路径
        Tools::$log_path = $this->_runtime_path . 'logs' . DIRECTORY_SEPARATOR;
        // 设置缓存的路径
        Tools::$cache_path = $this->_runtime_path . 'cache' . DIRECTORY_SEPARATOR;


        // 使用指定的缓存
        $cache_name = isset($this->_config['cache']['type']) ? $this->_config['cache']['type'] : 'file';

        $classname = '\\yqn\\helper\\cache\\I' . ucfirst($cache_name);
        if (class_exists($classname)) {
            switch ($cache_name) {
                case "file":
                    Tools::$cache = $classname::getInstance(Tools::$cache_path);
                    break;
                case 'redis':
                    Tools::$cache = $classname::getInstance(isset($this->_config['cache']['redis']) ? $this->_config['cache']['redis'] : []);
                    break;
                default:
                    Tools::$cache = $classname::getInstance($this->_runtime_path . 'cache' . DIRECTORY_SEPARATOR);
                    break;
            }
        }

        //判断是否登录过
        if ($token = Tools::getCache('access_token_' . $this->_tplusconfig['orgid'] . '_' . date('Y-m-d'))) {
            $this->_access_token = $token;
        }
    }


    /**
     *
     * @param $config
     * @return null|IBaseAuth
     */
    static public function getInstance($config)
    {
        $sign = md5(serialize($config));
        if (!isset(self::$instance[$sign])) {
            self::$instance[$sign] = new self($config);
        }
        return self::$instance[$sign];
    }

    /**
     * 生成签名的字符串
     * @return string   获取签名的内容
     */
    public function getSign()
    {
        //获取已缓存的http请求的签名
        $this->_sign = Tools::getCache('http_sign_' . $this->_tplusconfig['orgid'] . '_' . date('Y-m-d'));
        //如果token无效或签名无效 或
        if (empty($this->_sign)) {

            $appdata = [
                'appkey' => $this->_tplusconfig['appKey'],
                'orgid' => $this->_tplusconfig['orgid'],
                'appsecret' => $this->_tplusconfig['appSecret']
            ];
            $md5key = md5(json_encode($appdata));
            $signData = [];
            if (!empty($this->_access_token)) {
                $signData['access_token'] = $this->_access_token;
            }
            $signData['sub'] = 'e-commerce';
            $signData['datas'] = $md5key;
            $signData['exp'] = time() + $this->_token_timeout;
            $sign = JWT::encode($signData, $this->_privekey, 'RS256');

            $auth = ['appKey' => $this->_tplusconfig['appKey'], 'authInfo' => $sign, 'orgId' => $this->_tplusconfig['orgid']];
            $this->_sign = base64_encode(json_encode($auth));
            if (!empty($this->_access_token)) {
                Tools::setCache('http_sign_' . $this->_tplusconfig['orgid'] . '_' . date('Y-m-d'), $this->_sign, $this->_token_timeout);
            }
        }
        // 设置请求的头部信息
        $this->_header = [
            "Content-type:application/x-www-form-urlencoded;charset=utf-8",
            "Authorization:$this->_sign",
        ];
        return $this->_sign;
    }

    /**
     * 发送post请求
     * @param $url  string 要访问的url地址
     * @param $data  array 要发送的数据
     * @param $fullurl bool true  完整的url false 整合与serverurl进行整合
     * @return bool|string
     */
    public function httpPost($url, $data, $fullurl = false, $headers = [])
    {
        if (!$fullurl) {
            $url = $this->geturl($url);
        }
        // 获取登录的签名
        $this->getSign();
        $opt_header = ['headers' => $this->_header];
        foreach ($headers as $k => $v) {
            if (!isset($opt_header[$k])) {
                $opt_header[$k] = $v;
            }
        }
        return $this->httpDone(Http::post($url, $data, $opt_header));
    }

    /**
     * 发送get请求
     * @param $url  string          请求的url地址
     * @param $data array/string    请求的数据
     * @param $fullurl bool true  完整的url false 整合与serverurl进行整合
     * @return bool|string
     */
    public function httpGet($url, $data, $fullurl = false, $headers = [])
    {
        if (!$fullurl) {
            $url = $this->geturl($url);
        }
        $this->getSign();
        $opt_header = ['headers' => $this->_header];
        foreach ($headers as $k => $v) {
            if (!isset($opt_header[$k])) {
                $opt_header[$k] = $v;
            }
        }
        return $this->httpDone(Http::get($url, $data, $opt_header));
    }


    /**
     * 检测返回结果
     * @param $data string   要解析的数据
     * @return bool|array    成功解决设置了true,失败返回false
     */
    protected function httpDone($data)
    {
        if ($data != false) {
            return json_decode($data, true);
        }
        return false;
    }


    /**
     * 利用用户名和密码进行登录
     * @param $username string  用户名
     * @param $passwd   string  密码,未加密的
     * @param $accNum   string  帐套号
     *
     * @return bool     返回登录成功后的数据
     * @throws \Exception 用户名和org同时为空时
     */
    public function login($username = '', $passwd = '', $accNum = '')
    {
        if (empty($username) && empty($this->_tplusconfig['orgid'])) {
            throw  new \Exception("no specified Username Or orgid");
        }
        if (!empty($username)) {
            $url = $this->_tplusconfig['serverUrl'] . self::USERNAME_URL;
        } else {
            $accNum = empty($accNum) ? $this->_tplusconfig['orgid'] : $accNum;
            $url = $this->_tplusconfig['serverUrl'] . self::ORGID_URL;
        }
        $postdata = ['userName' => $username, 'password' => $passwd, 'accNum' => $accNum];

        //进行登录操作
        $jsondata = $this->httpPost($url, ["_args" => json_encode($postdata)]);
        //检查是否登录成功
        if ($jsondata !== false && isset($jsondata['access_token'])) {
            Tools::setCache('access_token_' . $accNum . '_' . date('Y-m-d'), $jsondata['access_token'], $this->_token_timeout);
            $this->_access_token = $jsondata['access_token'];
            Tools::delCache('http_sign_' . $accNum . '_' . date('Y-m-d'));
        }
        return $jsondata;
    }

    /**
     * 判断用户是否登录
     * @return bool  true 已登录  false 未登录
     */
    public function checkLogin()
    {
        return empty($this->_access_token) ? false : true;
    }

    /**
     * 自动检测是否登录,未登录时进行登录
     * @param string $username 用户名
     * @param string $passwd 密码
     * @param string $accNum 帐套号
     * @param $force bool           强制重新登录,true 强制  false  不强制
     * @return bool 成功返回true,失败返回false
     */
    public function autologin($username = '', $passwd = '', $accNum = '', $force = false)
    {
        if (is_bool($username)) {
            $force = $username;
            $username = '';
        }

        if ($force) {
            $this->_access_token = '';
            Tools::delCache('access_token_' . $this->_tplusconfig['orgid'] . '_' . date('Y-m-d'));
            Tools::delCache('http_sign_' . $this->_tplusconfig['orgid'] . '_' . date('Y-m-d'));
        }
        if (empty($this->_access_token)) {
            try {

                return $this->login($username, $passwd, $accNum);
            } catch (\Exception $e) {

                print_r($e);
                return false;
            }
        }
        return true;
    }

    /**
     * 生成完整的url地址
     * @param string $url
     * @return bool|string
     */
    public function geturl($url = '')
    {
        if (stripos($url, $this->_tplusconfig['serverUrl']) !== false) {
            return $url;
        }
        if (!empty($url)) {
            $f = substr($url, 0, 1);
            if ($f == '/' || $f == '\\') {
                $url = substr($url, 1);
            }
        }
        return $this->_tplusconfig['serverUrl'] . $url;
    }

    /**
     * 获取成员变量的值
     * @param $name
     * @return bool/value  成功返回内容 失败返回false
     */
    public function __get($name)
    {
        if (isset($this->$name)) {                               //判断变量是否被声明
            return $this->$name;
        }
        return false;
    }


    /**
     * 设置成员变量的
     * @param $name   变量名称
     * @param $value   变量值
     */
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

}