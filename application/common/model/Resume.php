<?php
namespace app\common\model;
use think\Model;

class Resume extends Model
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

    /**
     * 输出性别的属性
     * @return string 0男，1女
     */
    public function getSexAttr($value)
    {
        $status = array('0'=>'男','1'=>'女');
        $sex = $status[$value];
        if (isset($sex))
        {
            return $sex;
        } else {
            return $status[0];
        }
    }


    //定义同名方法一对一关联
    public function Student()
    {
        return $this->belongsTo('Student','id');
    }
//    定义简历与招聘广告多对多关联
    public function Ads()
    {
        return $this->belongsToMany('Ad',  config('database.prefix') . 'resume_ad');
    }
/*有了这个多对多关联的Ads()，在进行查找操作时，它会自动的对ad表进行操作；
在进行数据插入、更新操作时，它又会自动对中间表resume_ad进行操作*/
}