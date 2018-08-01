<?php
/**
 * Created by PhpStorm.
 * User: gjianbo
 * Date: 2018/8/1
 * Time: 10:06
 */

use yqn\chanjet\ChanjetSdk;

include "autoload.php";

// 加载配置文件
$config = include 'config.php';



$chanjet = new ChanjetSdk($config);


//$test = $chanjet->login();
//var_dump($test);
$demo = $chanjet->demo();
var_dump($demo);

