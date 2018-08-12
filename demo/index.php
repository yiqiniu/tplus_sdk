<?php
/**
 * Created by PhpStorm.
 * User: gjianbo
 * Date: 2018/8/1
 * Time: 10:06
 */

include "autoload.php";

// 加载配置文件
$config = include 'config.demo.php';


//获取认证的信息
//$ibaseauth = IBaseAuth::getInstance($config);

$ibaseauth = tplus_baseAuth($config);

// 进行自动登录
if($ibaseauth->autologin()){
    //person($ibaseauth);
    //partner($ibaseauth);
    warehouse($ibaseauth);
    department($ibaseauth);

}

/**
 * @param $ibaseauth
 */
function department($ibaseauth){

    $department =tplus_load('department');
    //$department = new IDepartment($ibaseauth);
    $data =$department->query();
    var_dump($data);
}


//合作伙伴
function partner($ibaseauth){
    $partner =tplus_load('partner');
    //$partner = new IPartner($ibaseauth);
    $data =$partner->query(['Code'=>'010100010']);
    var_dump($data);
}
// 员工表
function person($ibaseauth){
    $Person = tplus_load('person');
    $data =$Person->query();
    var_dump($data);
}


//仓库操作
function warehouse($ibaseauth){
    // 库存表
    $warehouse = tplus_load('warehouse');
    //$data = $warehouse->query(['Code'=>'01']);
    $data = $warehouse->query();
    var_dump($data);
}

function demo(){

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