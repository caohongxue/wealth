<?php

namespace app\wealth\validate;
use think\Validate;

class User extends Validate{
    protected $rule = [
        'username'=>'require',
        'passwd'=>'require',
    ];
    protected $field = [
        'username'=>'用户名',
        'passwd'=>'密码',
    ];
}