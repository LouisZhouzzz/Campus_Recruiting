<?php
namespace app\index\controller;
use app\common\model\Admin;
use app\common\model\Company;
use think\Request;
use think\Controller;
use think\Session;
class AdminController extends AdminindexController
{
    public function index(){
        $Admin = new Admin;

      /*//需要获取登录传入的用户名,通过username找到这条记录然后显示
        //接收post信息
        $postData = Request::instance()->post();
        return $postData['username'];这需要表单传到两个触发器*/

        //或者利用存的sessionId查找显示
        $adminId = Session::get('adminId');

//        $admins = $Admin->select($adminId);
//        $admins是一个对象数组 一个元素
//        $admin=$admins[0];
//        不需要以上这么做 直接get就行

        $admin = Admin::get($adminId);

        $this->assign('admin',$admin);
        return  $this->fetch();
    }

    public function edit(){
        //获取传入的ID
        $id = Request::instance()->param('id/d');

        //获取当前对象
        if(is_null($Admin = Admin::get($id))){
            return $this->error('系统未找到ID为' . $id . '的记录');
        }

        $this->assign('Admin',$Admin);
        return $this->fetch();
    }

    public function update(){
        //获取要更新的记录的ID
        $id = Request::instance()->post('id/d');

        //获取当前对象
        $Admin = Admin::get($id);

        //判断当前对象是否为空,再更新数据
        if (!is_null($Admin)){
            //把表单中填的数据写入当前对象
            $Admin->name = Request::instance()->post('name');
            $Admin->username = Request::instance()->post('username');
            $Admin->sex = Request::instance()->post('sex');
            $Admin->email = Request::instance()->post('email');

            //更新
            if (false=== $Admin->validate(true)->save()){
                return  $this->error('更新失败'.$Admin->getError());
            }
//            用了return就直接程序结束了 不用return就没结束要else
            return $this->success('更新成功',url('index'));
        }
    }

    public function password()
    {
        return $this->fetch();
    }
    public function updatePassword(){

        $oldPassword= Request::instance()->post('oldPassword');
        $password =   Request::instance()->post('password');
        //不填是空字符串不是null is_null不行,要用empty()
        if(0!==strlen($oldPassword)){
            $adminId= Session::get('adminId');
            $admin = Admin::get($adminId);

            //检测原始密码长度
            if (!Admin::checkPasswordLength($oldPassword) ){
                return $this->error('原始密码长度不小于3!'.$admin->getError());
            }

            if (Admin::encriptPassword($oldPassword) === $admin->password){
                //检测新密码长度
                if (!Admin::checkPasswordLength($password) ){
                    return $this->error('新密码长度不小于3!'.$admin->getError());
                }

                //新密码不能与旧密码相同
                if ($password === $oldPassword){
                    return $this->error('新密码不能与旧密码相同!'.$admin->getError());
                }

                $admin->password = Admin::encriptPassword($password);
                //修改密码至数据表

                //测试显示不论保存相同还是不同都save()结果都是1
                //这里save不能判断保存于原来同还是不同了 不能判断密码与原密码是否同
                if (0 === $admin->save()){
                    return $this->error('密码修改失败!'.$admin->getError());
                }
                return $this->success('密码修改成功!',url('index'));
            }else{
                return $this->error('password incorrect',url('password'));
            }
        }else{
            return $this->error('原始密码不能为空',url('password'));
        }

    }
    public function addCompany(){
        return $this->fetch();
    }
    public function saveCompany(){
        //接收传入数据
        $postData = Request::instance()->post();

        //实例化Student空对象
        $Company = new Company();

        //为对象赋值
        $Company->companyName =$postData['companyName'];
        $Company->employeeNumber =$postData['employeeNumber'];
        $Company->profession =$postData['profession'];
        $Company->city =$postData['city'];
        $Company->address =$postData['address'];
        $Company->introduction =$postData['introduction'];

        //新增对象至数据表
        if (false === $Company->validate(true)->save()){
            $this->error('新增失败:'.$Company->getError());
        }
        return $this->success('新增成功,新增ID为'.$Company->id,url('index'));
    }
}