<?php
namespace app\common\validate;

use think\Validate;

class Admin extends Validate
{
    protected $rule = [
        'username' =>'require|unique:admin|length:4,25',
        'name'=>'require|length:2,25',
        'sex'=>'in:0,1',
        'email' =>'email',
        'password'=>'require|length:3,40',
    ];
}