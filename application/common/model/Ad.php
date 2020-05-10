<?php
namespace app\common\model;
use think\Model;
class Ad extends Model
{
    /**
     * 获取要显示的更新时间
     * @param  int $value 时间戳
     * @return string  转换后的字符串
     */
    public function getUpdateTimeAttr($value)
    {
        return date('Y-m-d', $value);
    }

    /**
     * 获取要显示的创建时间
     * @param  int $value 时间戳
     * @return string  转换后的字符串
     */
    public function getCreateTimeAttr($value)
    {
        return date('Y-m-d', $value);
    }
//    以上功能也可以用日期类型转换,只是优先级没有获取器高,获取器更灵活


//    增加一个同名函数,可以通过n:1关系返回1这个对象
    public function Hr(){
        return $this->belongsTo('Hr');
    }

    //    定义招聘广告与简历多对多关联
    public function Resumes()
    {
        return $this->belongsToMany('Resume',  config('database.prefix') . 'resume_ad');
    }
    /*有了这个多对多关联的Resumes()，在进行查找操作时，它会自动的对resume表进行操作；
    在进行数据插入、更新操作时，它又会自动对中间表resume_ad进行操作*/
}