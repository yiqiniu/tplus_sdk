<?php
/**
 * Created by PhpStorm.
 * User: gjianbo
 * Date: 2018/8/14
 * Time: 17:17
 */

namespace yqn\tplus;


/**
 * 存货
 * Class IInventory
 * @package yqn\tplus
 */
class IInventory extends IBaseSdk
{
    protected $param_prefix=[
        'query'=>'param',
        'create'=>'dto',
        'update'=>'dto',
        'delete'=>'dto',
    ];

}