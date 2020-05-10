<?php
namespace app\index\controller;

use app\common\model\Admin;
use think\Controller;
use think\Request;

class AdminloginController extends Controller
//登录控制器不用继承验证类
//不能用驼峰命名法 否则提示不存在
{
    public function index()
    {
        return $this->fetch();
    }
    public function login(){
        //接收post信息
        $postData = Request::instance()->post();

        if (Admin::login($postData['username'],$postData['password'])){
            //用户名密码正确,将adminId存session,并跳转至管理员个人信息界面
            return $this->success('login success',url('Admin/index'));
        }else{
            //用户名不存在,跳转至登录界面
            return $this->error('username or password incorrect',url('Adminlogin/index'));
        }
    }
    public function logOut(){
        //注销要放到V层调用上去
        if (Admin::logOut()){
            return $this->success('log Out success',url('index'));
        } else{
            return $this->error('log Out error',url('index'));
        }
    }

}