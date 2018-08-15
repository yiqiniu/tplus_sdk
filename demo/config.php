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
    'cache'=>[
        //缓存类型,只支持file 或redis  redis的话,请配置redis 连接信息
        'type'=>'file',
        'redis'=>[
            'host' => '127.0.0.1', // redis主机
            'port' => 6379, // redis端口
            'password' => '', // 密码
            'persistent' => true, // 是否长连接
            'session_name' => 'yqn_', // sessionkey前缀
        ],
    ]
];