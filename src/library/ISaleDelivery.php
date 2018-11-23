<?php
/**
 * Created by PhpStorm.
 * User: gjianbo
 * Date: 2018/11/22
 * Time: 18:02
 */

namespace yqn\tplus;


/**
 * 销售订执行情况查询
 * Class ISaleDelivery
 * @package yqn\tplus
 */
class ISaleDelivery extends IBaseSdk
{
    protected $_opAction = [
        // 查询
        'query' => 'QueryExecuting',
        // 创建
        'create' => 'Create',
        // 修改
        'update' => 'Update',
        // 删除
        'delete' => 'Delete'
    ];

}