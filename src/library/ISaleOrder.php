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
        'createBatch' => 'dto'
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
     * @param string $perfix 操作的前缀如: param ,dto
     * @param bool $batch 批量添加 true 批量  false 单条
     * @return mixed
     */
    public function createBatch($data = [], $perfix = '', $batch = false)
    {
        if (empty($data)) {
            return false;
        }
        if (empty($perfix) && isset($this->param_prefix['createBatch'])) {
            $perfix = $this->param_prefix['createBatch'];
        }
        $senddata = ($perfix . '=') . json_encode($data);
        return $this->post_create($senddata);
    }



}