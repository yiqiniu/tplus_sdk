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
    protected $_opAction = [
        // 查询
        'query' => 'Query',
        // 创建
        'create' => 'Create',
        // 修改
        'update' => 'Update',
        // 删除
        'delete' => 'Delete',
        //批量创建
        'createBatch' => 'CreateBatch'
    ];

    /**
     * 添加数据接口,以完成默认操作,必须在子类调用,外部不能直接访问
     * @param array $data 要添加的数据
     * @return mixed
     */
    public function createBatch($data = [])
    {
        if (empty($data)) {
            return false;
        }

        $senddata = '_args=' . json_encode($data);

        return $this->post_create($senddata);
    }



}