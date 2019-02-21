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

$orgids = [
    ['orgid' => '90011661615', 'auth' => null],
    ['orgid' => '90012581281', 'auth' => null],
];
//'orgid' => '90011661615',
//'orgid' => '90012581281',


foreach ($orgids as $k => $v) {
    $config['api']['orgid'] = $v['orgid'];
    $ibaseauth = tplus_baseAuth($config);
    if ($ibaseauth->autologin()) {
        $orgids[$k]['auth'] = $ibaseauth;
    }

    tplus_dump($ibaseauth);
    $sale = tplus_load('saleDelivery', $ibaseauth);
    $query = [
        [
            'WhereName' => 'SaleDelivery.ExternalVoucherCode',
            'BeginValue' => 'zy_20181121084959405473600',
        ]
    ];
    $data = $sale->query($query, 'queryParam');
    tplus_dump($data);
}
//var_dump($orgids);

exit;


// 进行自动登录
if ($ibaseauth->autologin()) {

    /* $brand = tplus_load('brand');
     $data = $brand->query();
     var_dump($data);*/
    //person($ibaseauth);
    //partner($ibaseauth);
    // warehouse($ibaseauth);
    //  department();
    //inventoryClass();

    //   inventory();

    //查询销货单
    $sale = tplus_load('saleDelivery');
    $query = [
        [
            'WhereName' => 'SaleDelivery.ExternalVoucherCode',
            'BeginValue' => 'zy_20181121084959405473600',
        ]
    ];
    /**
     * 'Rows' =>
     * array (size=1)
     * 0 =>
     * array (size=51)
     * 'voucherdate' => string '/Date(1542729600000)/' (length=21)
     * 'saleDeliveryCode' => string 'SA-2018-11-060304-0000558' (length=25)
     * 'externalCode' => null
     * 'busiTypeName' => string '普通销售' (length=12)
     * 'isSaleOut' => string '已出库' (length=9)
     * 'isCancel' => string '已结清' (length=9)
     * 'saleInvoiceNo' => string '' (length=0)
     * 'CurrencyName' => string '人民币' (length=9)
     * 'partnerCode' => string '1030003' (length=7)
     * 'partnerName' => string '百世快递' (length=12)
     * 'SettleCustomerCode' => string '1030003' (length=7)
     * 'SettleCustomer' => string '百世快递' (length=12)
     * 'deliveryDate' => null
     * 'ReciveTypeName' => string '其它' (length=6)
     * 'recivingMaturity' => null
     * 'address' => string '山东省 济宁市 嘉祥县 仲山镇狼山' (length=45)
     * 'linkMan' => string '司莉莉' (length=9)
     * 'Memo' => string '0' (length=1)
     * 'priuserdefnvc1' => string '' (length=0)
     * 'priuserdefnvc2' => string '2018-11-21 09:06:23' (length=19)
     * 'priuserdefnvc3' => string '司莉莉  电话：18605375071 线上支付260' (length=47)
     * 'pubuserdefnvc1' => string '百世汇通' (length=12)               //
     * 'pubuserdefnvc2' => string '司莉莉51324738200179' (length=23)
     * 'pubuserdefnvc3' => string '线上' (length=6)
     * 'warehouseCode' => string '01' (length=2)
     * 'warehouseName' => string '一仓' (length=6)
     * 'InventoryBarCode' => null
     * 'inventoryCode' => string 'p0000002485' (length=11)
     * 'inventoryName' => string '贝因美红爱加800克听装1段' (length=34)
     * 'specification' => string '箱' (length=3)
     * 'BrandName' => string '贝因美' (length=9)
     * 'freeItem0' => null
     * 'freeItem1' => null
     * 'freeItem2' => null
     * 'freeItem3' => null
     * 'freeItem4' => null
     * 'freeItem5' => null
     * 'freeItem6' => null
     * 'freeItem7' => null
     * 'freeItem8' => null
     * 'freeItem9' => null
     * 'unitName' => string '箱' (length=3)
     * 'Batch' => string '20170501' (length=8)
     * 'ProductionDate' => string '/Date(1493568000000)/' (length=21)
     * 'ExpiryDate' => string '/Date(1556553600000)/' (length=21)
     * 'quantity' => float 1
     * 'origTaxPrice' => float 260
     * 'origTaxAmount' => float 260
     * 'detailOrigSettleAmount' => float 260
     * 'saleInvoiceQuantity' => null
     * 'saleInvoiceOrigAmount' => null
     */
    $data = $sale->query($query, 'queryParam');
    tplus_dump($data);

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