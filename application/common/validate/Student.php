<?php
namespace app\common\validate;

use think\Validate;

class Student extends Validate
{
    protected $rule = [
        'username' =>'require|unique:student|length:4,25',
        'name'=>'require|length:2,25',
        'sex'=>'in:0,1',
        'email' =>'email',
        'password'=>'require|length:3,40',
        'school'=>'require|length:3,25',
        'profession'=>'require|length:2,25',
    ];
}