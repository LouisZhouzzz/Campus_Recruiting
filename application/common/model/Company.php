<?php
namespace app\common\model;
use think\Model;

class Company extends Model
{
    /**
     * 获取要显示的更新时间
     * @param  int $value 时间戳
     * @return string  转换后的字符串
     * @author panjie <panjie@yunzhiclub.com>
     */
    public function getUpdateTimeAttr($value)
    {
        return date('Y-m-d', $value);
    }
//    以上功能也可以用日期类型转换,只是优先级没有获取器高,获取器更灵活

//1.n可以从公司拿到多个hr
    public function hrs()
    {
        return $this->hasMany('Hr','company_id');
    }
}