<?php
namespace app\common\validate;

use think\Validate;
class Ad extends Validate
{
    protected $rule = [
        'jobName' =>'require|length:2,30',
        'hireNumber'=>'require|length:1,11',
        'hireNumber'=>'require|length:1,11',
        'profession'=>'require|length:2,30',
        'city'=>'require|length:2,30',
        'salary'=>'require|length:1,30',
        'jobInfo'=>'require|length:2,1000',
        'contactInfo'=>'require|length:2,1000',
        'hr_id'=>'require',
    ];
}