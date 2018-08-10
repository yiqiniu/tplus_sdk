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
        "appKey" => '',
        "appSecret" => '',
        // 私钥路径
        'appPrivateKey' => '',
        // 企业编号
        'orgid' => '',

    ],
    // 调试状态,自动记录日志
    'api_debug'=>true,
    //默认缓存和日志保存的位置
    'runtime'=>dirname(__DIR__) . DIRECTORY_SEPARATOR . 'runtime' . DIRECTORY_SEPARATOR,
];