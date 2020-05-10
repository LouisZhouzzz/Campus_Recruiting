<?php
namespace app\common\validate;

use think\Validate;

class Hr extends Validate
{
    protected $rule = [
        'username' =>'require|unique:hr|length:2,25',
        'name'=>'require|length:2,25',
        'sex'=>'in:0,1',
        'email' =>'email',
        'password'=>'require|length:3,40',
        'company_id'=>'require',
    ];
}