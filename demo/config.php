<?php
/**
 * Created by PhpStorm.
 * User: gjianbo
 * Date: 2018/8/1
 * Time: 10:15
 */

return [

    'serverUrl'=>'https://t.chanjet.com/tplus/api/v2/',

    "appKey" => '183573b3-8ec8-4404-90de-eb6e1ee1954e',
    "appSecret" => 'xdgzs9',
    'appPrivateKey' => dirname(__FILE__).'/cert/cjet_pri.pem',
    'orgid' => '90010503265',
    //当前目录的Cache目录下
    'cachePath'=>dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Cache' . DIRECTORY_SEPARATOR
];