<?php
namespace app\index\controller;

use app\common\model\Student;
use think\Controller;
use think\Request;

class LoginController extends Controller
//登录控制器不能继承验证类indexController 因为
//验证控制器检测没有登录 如果继承了的话 登录控制器也没登录会一直跳到这个页面
{
    public function test()
    {
        $hello = ['hello'];
        echo  Student::encriptPassword($hello);
    }
    public function index()
    {
       return $this->fetch();
    }
    public function login()
    {
        //接收post信息
        $postData = Request::instance()->post();

        //学生登录跳到个人中心页面,       暂时可以简历管理页
        if (Student::login($postData['username'],$postData['password'])){
            //用户名密码正确,将studentId存session,并跳转至学生管理界面
            return $this->success('login success',url('Studentmyself/index'));
        }else{
            //用户名不存在,跳转至登录界面
            return $this->error('username or password incorrect',url('index'));
        }

    }
    public function logOut()
    {
        //注销还没放到学生个人信息页面的V层调用上去
        if (Student::logOut()){
            return $this->success('log Out success',url('index'));
        } else{
            return $this->error('log Out error',url('index'));
        }
    }
}