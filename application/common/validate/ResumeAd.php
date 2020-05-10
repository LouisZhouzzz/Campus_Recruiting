<?php
namespace app\common\validate;
use think\Validate;

class ResumeAd extends Validate
{
    protected $rule = [
        'resume_id'  => 'require',
        'ad_id' => 'require'
    ];
}