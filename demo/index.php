<?php
/**
 * Created by PhpStorm.
 * User: gjianbo
 * Date: 2018/8/1
 * Time: 10:06
 */

use yqn\chanjet\IBaseAuth;
use yqn\chanjet\IDepartment;
use yqn\chanjet\IPartner;
use yqn\chanjet\IPerson;
use yqn\chanjet\IWarehouse;

include "autoload.php";

// 加载配置文件
$config = include 'config.php';


//获取认证的信息
$ibaseauth = IBaseAuth::getInstance($config);

// 进行自动登录
if($ibaseauth->autologin()){
    //person($ibaseauth);
    partner($ibaseauth);
    //warehouse($ibaseauth);
    //department($ibaseauth);

}

/**
 * @param $ibaseauth
 */
function department($ibaseauth){
    $department = new IDepartment($ibaseauth);
    $data =$department->query();
    var_dump($data);
}


//合作伙伴
function partner($ibaseauth){
    $partner = new IPartner($ibaseauth);
    $data =$partner->query(['Code'=>'010100010']);
    var_dump($data);
}
// 员工表
function person($ibaseauth){
    $Person = new IPerson($ibaseauth);
    $data =$Person->query();
    var_dump($data);
}


//仓库操作
function warehouse($ibaseauth){
    // 库存表

    $warehouse = new  IWarehouse($ibaseauth);
    //$data = $warehouse->query(['Code'=>'01']);
    $data = $warehouse->query();
    var_dump($data);
}