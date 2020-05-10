<?php
namespace app\index\controller;
use app\common\model\Resume;
use think\Request;
use app\common\model\Student;
use think\Controller;

class SignupController extends Controller
{
    public function index(){
        return $this->fetch();
    }
    public function save(){
        //接收传入数据
        $postData = Request::instance()->post();

        //实例化Student空对象
        $Student = new Student();

        //为对象赋值
        $Student->name =$postData['name'];
        $Student->username =$postData['username'];
        $Student->sex =$postData['sex'];
        $Student->email =$postData['email'];

        //密码长度验证
        if (!Student::checkPasswordLength($postData['password'])){
            $this->error('密码长度不能小于3',url('index'));
        }

        //注意这里要对密码加密
        $Student->password =Student::encriptPassword($postData['password']) ;
        $Student->school =$postData['school'];
        $Student->profession =$postData['profession'];


        //新增对象至数据表
        if (false === $Student->validate(true)->save()){
            $this->error('注册失败:'.$Student->getError());
        }

        //通过以上检验学生就注册成功了,这里学生注册的同时,帮学生创建一份1.1简历,填上初始信息
        //实例化Resume空对象
        $Resume = new Resume();

        //为对象赋值
        $Resume->name =$Student->name;
        $Resume->sex =$Student->sex;
        $Resume->email =$Student->email;
        $Resume->school =$Student->school;
        $Resume->profession =$Student->profession;
        $Resume->student_id =$Student->id; //注意要把学生id给简历造成1.1关联
        //新增对象至数据表
        if (false === $Resume->validate(true)->save()){
            $this->error('注册失败:'.$Resume->getError());
        }


        return $this->success('恭喜注册成功!',url('Login/index'));
        //注册成功后可直接免登陆进入管理界面
        //我这里注册后进入还是登陆页面
    }
}