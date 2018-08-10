<?php
/**
 * Created by PhpStorm.
 * User: gjianbo
 * Date: 2018/8/9
 * Time: 13:50
 */

namespace yqn\chanjet;


class IDepartment extends IBaseSdk
{

    protected $param_prefix=[
        'query'=>'dto',
        'create'=>'dto',
        'update'=>'dto',
        'delete'=>'dto',
    ];
    protected $selectfield='ID,Code,Name';


}