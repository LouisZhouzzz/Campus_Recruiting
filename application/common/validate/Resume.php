<?php
namespace app\common\validate;

use think\Validate;

class Resume extends Validate
{
    protected $rule = [
        'name'=>'length:2,25',
        'sex'=>'in:0,1',
        'school'=>'length:3,25',
        'profession'=>'length:2,25',
        'phoneNumber' =>'length:1,20',
        'email' =>'email',
        'birthday' =>'length:1,20',
        'city' =>'length:1,20',
        'cardNumber' =>'length:1,20',
        'education' =>'length:1,1000',
        'jobWish' =>'length:1,1000',
        'project' =>'length:1,1000',
        'specialty' =>'length:1,1000',
        'certificate' =>'length:1,1000',
        'extraInfo' =>'length:1,1000',
    ];
}