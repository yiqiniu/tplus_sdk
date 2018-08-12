<?php
/**
 * Created by PhpStorm.
 * User: gjianbo
 * Date: 2018/8/8
 * Time: 10:39
 */

namespace yqn\tplus;


/**
 * 员工信息
 * Class IPerson
 * @package yqn\chanjet
 */
class IPerson extends IBaseSdk
{

    protected $param_prefix=[
        'query'=>'dto',
        'create'=>'dto',
        'update'=>'dto',
        'delete'=>'dto',
    ];
    protected $selectfield='ID,Code,Name,Shorthand,MobilePhoneNo';
    //查询模拟



}