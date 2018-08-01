<?php
/**
 * Created by PhpStorm.
 * User: gjianbo
 * Date: 2018/8/1
 * Time: 10:06
 */

namespace yqn\chanjet;




class ChanjetSdk
{


    protected $_oauth=null;
    /**
     * ChanjetSdk constructor.
     * @param array  配置参数
     */
    public function __construct($_config)
    {
        $this->_oauth = ChanjetOAuth::getInstance($_config);
    }

    //用户登录
    public function login($user='',$pass='',$accNum=''){

        return $this->_oauth->login($user,$pass,$accNum);
    }

    //查询模拟
    public function demo(){

        if(!$this->_oauth->checkLogin()){
            if($this->_oauth->login()===false){
                echo '登录失败';
                return false;
            }
        }
        $url = '/person/Query';
        //$data='dto={}';

        $data=['Code'=>'1001','Name'=>'adsf'];
        $datastr='dto=';
        if(empty($data)){
            $datastr.='{}';
        }elseif(is_array($data)){
            $datastr.=json_encode($data);
        }else{
            $datastr.=$data;
        }

        $retdata = $this->_oauth->httpPost($url,$datastr);
        var_dump($retdata);
    }
}