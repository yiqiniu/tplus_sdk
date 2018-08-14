<?php
/**
 * Created by PhpStorm.
 * User: gjianbo
 * Date: 2018/8/14
 * Time: 17:14
 */

namespace yqn\tplus;


/**
 * 销售订单
 * Class ISaleOrder
 * @package yqn\tplus
 */
class ISaleOrder extends IBaseSdk
{
    protected $param_prefix=[
        'query'=>'dto',
        'create'=>'dto',
        'update'=>'dto',
        'delete'=>'dto',
    ];

}