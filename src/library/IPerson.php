<?php
/**
 * Created by PhpStorm.
 * User: gjianbo
 * Date: 2018/8/8
 * Time: 10:39
 */

namespace yqn\chanjet;


/**
 * 员工信息
 * Class IPerson
 * @package yqn\chanjet
 */
class IPerson extends IBaseSdk
{

    protected $param_prefix=[
        'query'=>'dto',
        'create'=>'dto',
        'update'=>'dto',
        'delete'=>'dto',
    ];
    protected $selectfield='ID,Code,Name,Shorthand,MobilePhoneNo';
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

        $this->writelog($retdata);
        var_dump($retdata);
    }


}