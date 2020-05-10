<?php
namespace app\index\controller;
use think\Request;
use app\common\model\Admin;
use think\Controller;

class AdminsignupController extends Controller
{
    public function index(){
    return $this->fetch();
    }

    public function save(){
        //接收传入数据
        $postData = Request::instance()->post();

        //实例化Student空对象
        $Admin = new Admin();

        //为对象赋值
        $Admin->name =$postData['name'];
        $Admin->username =$postData['username'];
        $Admin->sex =$postData['sex'];
        $Admin->email =$postData['email'];

        //密码长度验证
        if (!Admin::checkPasswordLength($postData['password'])){
            $this->error('密码长度不能小于3',url('index'));
        }

        //注意这里要对密码加密
        $Admin->password =Admin::encriptPassword($postData['password']) ;


        //新增对象至数据表
        if (false === $Admin->validate(true)->save()){
            $this->error('注册失败:'.$Admin->getError());
        }
        return $this->success('恭喜注册成功!',url('Adminlogin/index'));
        //注册成功后可直接免登陆进入管理界面
        //我这里注册后进入还是登陆页面
    }

}