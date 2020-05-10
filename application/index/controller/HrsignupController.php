<?php
namespace app\index\controller;
use app\common\model\Company;
use think\Request;
use app\common\model\Hr;
use think\Controller;

class HrsignupController extends Controller
{
    public function index(){
        //获取所有公司信息
        $companys = Company::all();
        $this->assign('companys',$companys);
        return $this->fetch();
    }

    public function save(){
        //接收传入数据
        $postData = Request::instance()->post();

        //实例化Student空对象
        $Hr = new Hr();

        //为对象赋值
        $Hr->name =$postData['name'];
        $Hr->username =$postData['username'];
        $Hr->sex =$postData['sex'];
        $Hr->email =$postData['email'];
        $Hr->company_id =$postData['company_id'];
        //$Request->post('company_id/d'); 直接获取可以/d
        //而post()先获取一个postData返回的是数据 []内只能company_id 不能/d 因为数组索引

        //密码长度验证
        if (!Hr::checkPasswordLength($postData['password'])){
            $this->error('密码长度不能小于3',url('index'));
        }

        //注意这里要对密码加密
        $Hr->password =Hr::encriptPassword($postData['password']) ;


        //新增对象至数据表
        if (false === $Hr->validate(true)->save()){
            $this->error('注册失败:'.$Hr->getError());
        }
        return $this->success('恭喜注册成功!',url('Hrlogin/index'));
        //注册成功后可直接免登陆进入管理界面
        //我这里注册后进入还是登陆页面
    }

}