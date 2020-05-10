<?php
namespace app\index\controller;

use app\common\model\Hr;
use think\Controller;
use think\Request;

class HrloginController extends Controller
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

        if (Hr::login($postData['username'],$postData['password'])){
            //用户名密码正确,将adminId存session,并跳转至管理员个人信息界面
            return $this->success('login success',url('Hrmyself/index'));
        }else{
            //用户名不存在,跳转至登录界面
            return $this->error('username or password incorrect',url('Hrlogin/index'));
        }
    }
    public function logOut(){
        //注销要放到V层调用上去
        if (Hr::logOut()){
            return $this->success('log Out success',url('index'));
        } else{
            return $this->error('log Out error',url('index'));
        }
    }

}