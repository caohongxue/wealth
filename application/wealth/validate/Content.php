<?php
/**
 * Created by PhpStorm.
 * User: MACHENIKE
 * Date: 2018/4/4
 * Time: 17:03
 */
namespace app\wealth\validate;
use think\Validate;

class Content extends Validate{
    //验证规则
    protected $rule= [
        'title'=>'require',
        't_id'=>'require',
        'content'=>'require'
    ];
}