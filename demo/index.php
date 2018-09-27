<?php
/**
 * Created by PhpStorm.
 * User: gjianbo
 * Date: 2018/8/1
 * Time: 10:06
 */

use yqn\helper\Tools;

include "autoload.php";

// 加载配置文件
$config = include 'config.demo.php';


//$ret =tplus_pingyin();
//$sss= $ret->str2py("北京一起牛技术部胖子",false,' ');
//tplus_dump($sss);


//获取认证的信息
//$ibaseauth = IBaseAuth::getInstance($config);

$ibaseauth = tplus_baseAuth($config);

// 进行自动登录
if ($ibaseauth->autologin()) {

    /* $brand = tplus_load('brand');
     $data = $brand->query();
     var_dump($data);*/
    //person($ibaseauth);
    partner($ibaseauth);
    // warehouse($ibaseauth);
    //  department();
    //inventoryClass();

    //   inventory();
}

/**
 * @param $ibaseauth
 */
function department()
{

    tplus_debug('begin');
    $department = tplus_load('department');
    //$department = new IDepartment($ibaseauth);
    $data = $department->query();
    tplus_debug('end');
    echo tplus_debug('begin', 'end', 6) . 's';

    tplus_dump($data);
}


//合作伙伴
function partner($ibaseauth)
{
    $partner = tplus_load('partner');
    //$partner = new IPartner($ibaseauth);


    $retdata = Tools::getCache('partner');
    if (empty($retdata)) {
        $postdata = [
            'SelectFields' => 'ID,Code,Name,Shorthand,PartnerAbbName,PartnerAddresDTOs.Code,PartnerAddresDTOs.telephoneNo'
        ];
        $retdata = $partner->query($postdata);
        Tools::setCache('partner', $retdata);
    }
    //var_dump(sizeof($retdata));
    //var_dump($retdata);
}

// 员工表
function person($ibaseauth)
{
    $Person = tplus_load('person');
    $data = $Person->query();
    var_dump($data);
}


//仓库操作
function warehouse($ibaseauth)
{
    // 库存表
    $warehouse = tplus_load('warehouse');
    //$data = $warehouse->query(['Code'=>'01']);
    $data = $warehouse->query();
    var_dump($data);
}

// 存货分类
function inventoryClass()
{
    // 库存表
    $inventoryClass = tplus_load('inventoryClass');
    //$data = $warehouse->query(['Code'=>'01']);
    $data = $inventoryClass->query();
    var_dump($data);
}

//存货
function inventory()
{

    // 库存表
    $inventory = tplus_load('inventory');
    //$data = $warehouse->query(['Code'=>'01']);
    $data = $inventory->query(['InventoryClass' => ['Code' => '11']]);
    var_dump($data);
}

//  销售订单
function saleOrder()
{

    // 库存表
    $saleOrder = tplus_load('inventory');


}