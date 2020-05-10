<?php
namespace app\index\controller;
use app\common\model\Resume;
use app\common\model\Student;
use think\Request;
use think\Session;
use think\Controller;
class ResumeController extends IndexController
{
    public function index(){
        $Resume = new Resume;
        $Student = new Student;
        $studentId = Session::get('studentId');
        $student = Student::get($studentId);

       /* $resume_id = $student->getData('resume_id');
        $resume  = Resume::get($resume_id);
       这是普通n:1做法   */

//       以下是1.1做法  1.1中只需要在简历中放学生id,就能实现1.1
//  不需要在学生表里再放简历id,  否则就是两个n.1

       $resume = $student->Resume;

//       return var_dump($resume->Student); 两个可以互相获取了

        $this->assign('resume',$resume);
        return $this->fetch();
    }

   public function edit(){


       $studentId = Session::get('studentId');
       $student = Student::get($studentId);

        //获取当前简历对象
        if(is_null( $resume=$student->resume)){
            return $this->error('系统未找到该学生的简历记录');
        }

        $this->assign('resume',$resume);
        return $this->fetch();
    }
    public function update(){
       $id = Request::instance()->post('id/d');


        //获取当前对象
        $Resume = Resume::get($id);

        //判断当前对象是否为空,再更新数据
        if (!is_null($Resume)){
            //把表单中填的数据写入当前对象

            $Resume->name = Request::instance()->post('name');
            $Resume->sex = Request::instance()->post('sex');
            $Resume->birthday = Request::instance()->post('birthday');
            $Resume->profession = Request::instance()->post('profession');
            $Resume->city = Request::instance()->post('city');
            $Resume->school = Request::instance()->post('school');
            $Resume->phoneNumber = Request::instance()->post('phoneNumber');
            $Resume->email = Request::instance()->post('email');
            $Resume->cardNumber = Request::instance()->post('cardNumber');
            $Resume->jobWish = Request::instance()->post('jobWish');
            $Resume->project = Request::instance()->post('project');
            $Resume->education = Request::instance()->post('education');
            $Resume->specialty = Request::instance()->post('specialty');
            $Resume->certificate = Request::instance()->post('certificate');
            $Resume->extraInfo = Request::instance()->post('extraInfo');

            //更新
            if (false=== $Resume->validate(true)->save()){
                return  $this->error('更新失败'.$Resume->getError());
            }
//            用了return就直接程序结束了 不用return就没结束要else
            return $this->success('更新成功',url('edit'));
        }
    }

   public function test(){
        //多对多功能测试  在M层的Ads()多对多关联方法
       /*有了这个多对多关联的Ads()，在进行查找操作时，它会自动的对ad表进行操作；
在进行数据插入、更新操作时，它又会自动对中间表resume_ad进行操作*/
       $Resume= new Resume();
       $resume= Resume::get(1);
        return var_dump($resume->Ads);
        //$resume->Ads  获取没有括号   //新增$resume->Ads()->save(***)中间有括号
        //或用$resume->Ads()->select()得整个数组

       // 只更新中间表数据 写法
//       $resume = Resume::get(1);
//       $resume->Ads()->save($ad);
   }

}