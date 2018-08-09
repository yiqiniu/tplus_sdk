<?php
/**
 * Created by PhpStorm.
 * User: gjianbo
 * Date: 2018/8/1
 * Time: 10:06
 */

use yqn\chanjet\IBaseAuth;
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
    //warehouse($ibaseauth);
    person($ibaseauth);
}


// 员工表
function person($ibaseauth){
    $Person = new IPerson($ibaseauth);
    $data =$Person->query('dto');
    var_dump($data);
}

//$test = $chanjet->login();
//var_dump($test);
/*$demo = $chanjet->demo();
var_dump($demo);*/

//仓库操作
function warehouse($ibaseauth){
    // 库存表

    $warehouse = new  IWarehouse($ibaseauth);

    $data = $warehouse->query(['Code'=>'01']);

    var_dump($data);
}