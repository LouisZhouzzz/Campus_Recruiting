<?php
namespace app\common\validate;

use think\Validate;

class Company extends Validate
{
    protected $rule = [
        'companyName' =>'require|unique:company|length:1,25',
        'employeeNumber'=>'require|length:1,30',
        'profession'=>'require|length:1,30',
        'city'=>'require|length:1,30',
        'address'=>'require|length:1,50',
        'introduction'=>'require|length:2,255',
    ];
}