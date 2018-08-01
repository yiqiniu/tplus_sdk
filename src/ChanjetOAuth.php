<?php
/**
 * Created by PhpStorm.
 * User: gjianbo
 * Date: 2018/8/1
 * Time: 10:39
 */

namespace yqn\chanjet;


use Firebase\JWT\JWT;
use InvalidArgumentException;


class ChanjetOAuth
{

    //创建静态私有的变量保存该类对象
    static private  $instance=null;
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

    private $_token_timeout=3600*5;

    private $_sign = '';

    //配置文件
    private $_config = [
        'serverUrl' => '',
        "appKey" => '',
        "appSecret" => '',
        'privceKey' => '',
        'orgid' => '',
        'cachePath'=>''
    ];

    /**
     * ChanjetOAuth constructor.
     * @param $_config
     */
    public function __construct($config)
    {
        $this->_config = array_merge($this->_config, $config);
        if (empty($this->_config['serverUrl'])) {
            throw new InvalidArgumentException("Missing Config -- [serverUrl]");
        }

        if (empty($this->_config['appKey'])) {
            throw new InvalidArgumentException("Missing Config -- [appKey]");
        }
        if (empty($this->_config['appSecret'])) {
            throw new InvalidArgumentException("Missing Config -- [appSecret]");
        }
        if (empty($this->_config['appPrivateKey'])) {
            throw new InvalidArgumentException("Missing Config -- [appPrivateKey]");
        }
        if (!file_exists($this->_config['appPrivateKey'])) {
            throw new InvalidArgumentException("privceKey File not Exist");
        }
        $this->_privekey = file_get_contents($this->_config['appPrivateKey']);

        //设置缓存的路径
        if(empty($this->_config['cachePath'])){
            Tools::$cache_path=$this->_config['cachePath'];
        }
        //判断是否登录过
        if($token=Tools::getCache('access_token')){
            $this->_access_token = $token;
        }
    }


    /**
     *
     * @param $config
     * @return null|ChanjetOAuth
     */
    static public function  getInstance($config){
        if (!self::$instance instanceof self) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }
    /**
     * 生成签名的字符串
     * @return string   获取签名的内容
     */
    public function getSign()
    {
        $appdata = [
            'appkey' => $this->_config['appKey'],
            'orgid' => $this->_config['orgid'],
            'appsecret' => $this->_config['appSecret']
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

        $auth = ['appKey' => $this->_config['appKey'], 'authInfo' => $sign, 'orgId' => $this->_config['orgid']];
        $this->_sign = base64_encode(json_encode($auth));

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
    public function httpPost($url, $data,$fullurl=false)
    {
        if(!$fullurl){
            $url=$this->geturl($url);
        }
        $this->getSign();
        return $this->httpDone(Tools::post($url, $data, ['headers' => $this->_header]));
    }

    /**
     * 发送get请求
     * @param $url  string          请求的url地址
     * @param $data array/string    请求的数据
     * @return bool|string
     */
    public function httpGet($url, $data)
    {
        $this->getSign();
        return $this->httpDone(Tools::get($url, $data, ['headers' => $this->_header]));
    }


    /**
     * 检测返回结果
     * @param $data string   要解析的数据
     * @return bool|array    成功解决设置了true,失败返回false
     */
    protected function httpDone($data)
    {
        if ($data != false){
            return json_decode($data,true);
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
    public function login($username='',$passwd='',$accNum=''){
        if (empty($username) && empty($this->_config['orgid'])){
            throw  new \Exception("no specified Username Or orgid");
        }
        $postdata=[];
        if (!empty($username)){
            $postdata = ['userName' => $username, 'password' => $passwd, 'accNum' => $accNum];
            $url= $this->_config['serverUrl'].self::USERNAME_URL;
        }else{
            $url= $this->_config['serverUrl'].self::ORGID_URL;
        }

        //进行登录操作
        $jsondata =  $this->httpPost($url,["_args" => json_encode($postdata)]);
        //检查是否登录成功
        if($jsondata!==false){
            Tools::setCache('access_token',$jsondata['access_token'],$this->_token_timeout);
            $this->_access_token = $jsondata['access_token'];
        }
        return $jsondata;
    }

    /**
     * 判断用户是否登录
     * @return bool  true 已登录  false 未登录
     */
    public function checkLogin(){
        return empty($this->_access_token)?false:true;
    }

    /**
     * 生成完整的url地址
     * @param string $url
     * @return bool|string
     */
    public function geturl($url=''){
        if(stripos($url,$this->_config['serverUrl'])!==false){
            return $url;
        }
        if(!empty($url)){
           $f=substr($url,0,1);
           if($f=='/' || $f='\\'){
               $url=substr($url,1);
           }
        }
        return $this->_config['serverUrl'].$url;
    }



 }