<?php


namespace yqn\tplus;


use yqn\helper\Tools;

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

    /**
     * 调用其他的初始化构造函数
     */
    protected function initiaize()
    {

        $this->header['cookie_file'] = Tools::$log_path . 'tplus_cookie';
    }
}