<?php
namespace app\index\controller;
use app\common\model\Student;
use think\Request;
use think\Session;
class StudentmyselfController extends IndexController
{
    public function index(){
        //学生个人中心页面  显示个人信息和功能
        $Student = new Student;


        //或者利用存的sessionId查找显示
        $studentId= Session::get('studentId');
        $student = Student::get($studentId);

        $this->assign('student',$student);
        return $this->fetch();
    }
    public function password()
    {
        return $this->fetch();
    }
    public function updatePassword(){

        $oldPassword= Request::instance()->post('oldPassword');
        $password =   Request::instance()->post('password');
        //不填是空字符串不是null is_null不行,要用empty() empty太过了
        /*任何一个未初始化的变量、值为 0 或 false 或 空字符串”” 或 null的变量、
        空数组、没有任何属性的对象， empty(变量) == true。*/
        //可以封装一个修改密码方法到M层 传入新老密码两个参数就行 不同情况返回不同数代表状态
        //然后C层根据状态码去得结果
        if(0!==strlen($oldPassword)){

           $studentId= Session::get('studentId');
           $student = Student::get($studentId);
            //检测原始密码长度
            if (!Student::checkPasswordLength($oldPassword) ){
                return $this->error('原始密码长度不小于3!'.$student->getError());
            }

           if (Student::encriptPassword($oldPassword) === $student->password){
               //检测新密码长度
               if (!Student::checkPasswordLength($password) ){
                   return $this->error('新密码长度不小于3!'.$student->getError());
               }

               //新密码不能与旧密码相同
               if ($password === $oldPassword){
                   return $this->error('新密码不能与旧密码相同!'.$student->getError());
               }

               $student->password = Student::encriptPassword($password);
               //修改密码至数据表

               //测试显示不论保存相同还是不同都save()结果都是1
               //这里save不能判断保存于原来同还是不同了 不能判断密码与原密码是否同
               if (0 === $student->save()){
                   return $this->error('密码修改失败!'.$student->getError());
               }
               return $this->success('密码修改成功!',url('index'));
           }else{
               return $this->error('password incorrect',url('password'));
           }
       }else{
            return $this->error('原始密码不能为空',url('password'));
        }

    }
    public function myApply(){
        $studentId= Session::get('studentId');
        $student = Student::get($studentId);
        $ads = $student->Resume->Ads;
        $this->assign('ads',$ads);
        return $this->fetch();
    }
    public function edit(){
        //获取传入的ID
        $id = Request::instance()->param('id/d');

        //获取当前对象
        if(is_null($Student = Student::get($id))){
            return $this->error('系统未找到ID为' . $id . '的记录');
        }

        $this->assign('Student',$Student);
        return $this->fetch();
    }
    public function update(){
        //获取要更新的记录的ID
        $id = Request::instance()->post('id/d');

        //获取当前对象
        $Student = Student::get($id);

        //判断当前对象是否为空,再更新数据
        if (!is_null($Student)){
            //把表单中填的数据写入当前对象
            $Student->name = Request::instance()->post('name');
            $Student->username = Request::instance()->post('username');
            $Student->sex = Request::instance()->post('sex');
            $Student->email = Request::instance()->post('email');
            $Student->school = Request::instance()->post('school');
            $Student->profession = Request::instance()->post('profession');

            //更新
            if (false=== $Student->validate(true)->save()){
                return  $this->error('更新失败'.$Student->getError());
            }
//            用了return就直接程序结束了 不用return就没结束要else
            return $this->success('更新成功',url('index'));
        }
    }
}