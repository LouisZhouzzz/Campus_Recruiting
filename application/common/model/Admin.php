<?php
namespace app\common\model;
use think\Model;
class Admin extends Model
{
    /**
     * 用户登录
     * @param $username 用户名
     * @param $passwoed 密码
     * @return bool 成功返回true,失败返回false
     * @throws \think\Exception\DbException
     */
    static public function login($username,$passwoed)
    {
        $map = array('username' => $username);
        $Admin = self::get($map);

        //验证用户是否存在
        if (!is_null($Admin) ){
            //验证密码是否正确
            if ($Admin->checkPassword($passwoed)){
                //登录
                session('adminId',$Admin->getData('id'));
                return true;
            }
        }
        return false;
    }

    /**
     * 验证密码是否正确
     * @param string $password 密码
     * @return bool
     */
    public function checkPassword($password)
    {
        if ($this->getData('password') === self::encriptPassword($password)){
            //这里用$this测试也对
            return true;
        }else{
            return false;
        }
    }

    /**
     * 后端验证加密前的密码长度  ,因为加密后长度总是40 满足验证器
     * @param $password
     * @return bool
     */
    static public function checkPasswordLength($password)
    {
        if(strlen($password)>=3 && strlen($password)<=40){
            return true;
        }else {
            return false;
        }
    }

    /**
     * 密码加密算法
     * @param string $password 加密前密码 md5函数只能接收字符串类型
     * @return string 加密后密码
     */
    static public function encriptPassword($password)
    {
        //定制抛出异常信息
        if (!is_string($password)){
            throw  new  \RuntimeException("传入变量类型非字符串，错误码2", 2);
        }
        //实际过程中,我们还可以借助其他字符串算法,来实现不同的加密
        return sha1(md5($password).'15zwzhou');
    }
    static public function logOut()
    {
        //销毁session中的数据
        session('adminId',null);
        return true;
    }
    static public function isLogin()
    {
        $adminId = session('adminId');
        //这个session里面变量不为空就代表登录了

        //isset()与is_null()是一堆反义词
        if ($adminId){
            return true;
        }else{
            return false;
        }
    }
}