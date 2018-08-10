<?php
/**
 * Created by PhpStorm.
 * User: gjianbo
 * Date: 2018/8/1
 * Time: 10:06
 */

use yqn\chanjet\IDepartment;
use yqn\chanjet\IPartner;
use yqn\chanjet\IPerson;
use yqn\chanjet\IWarehouse;

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
 * @throws Exception
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