<?php
/**
 * Created by PhpStorm.
 * User: gjianbo
 * Date: 2018/8/1
 * Time: 10:15
 */

return [

    // T+ 接口信息
    'api'=>[
        //服务器的地址
        'serverUrl'=>'https://t.chanjet.com/tplus/api/v2/',
        "appKey" => '183573b3-8ec8-4404-90de-eb6e1ee1954e',
        "appSecret" => 'xdgzs9',
        // 私钥路径
        'appPrivateKey' => dirname(__FILE__).'/cert/cjet_pri.pem',
        // 企业编号
        'orgid' => '90010503265',

    ],
    // 调试状态,自动记录日志
    'api_debug'=>true,
    //默认缓存和日志保存的位置
    'runtime'=>dirname(__DIR__) . DIRECTORY_SEPARATOR . 'runtime' . DIRECTORY_SEPARATOR,
];