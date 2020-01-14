<?php


namespace yqn\tplus;


/**
 * 通用报表接口
 * Class ReportQuery
 * @package app\tplus\lib
 */
class IReportQuery extends IBaseSdk
{

    protected $opName = 'reportQuery';
    //操作菜单
    protected $_opAction = [
        // 查询
        'query' => 'GetReportData',
    ];
    protected $param_prefix = [
        'query' => 'request',
    ];
}