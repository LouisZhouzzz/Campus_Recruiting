<?php
namespace app\index\controller;
use app\common\model\Student;
use think\Request;
class StudentController extends AdminindexController
{
    //显示和编辑没有用密码  添加用了密码
    public function index()
    {
        //获取查询信息
        $name = Request::instance()->get('name');

        $pageSize = 5; // 每页显示5条数据

        $Student = new Student;
        //定制查询信息
        if (!empty($name)){
            $Student->where('name','like','%'.$name.'%');
        }

        $students = $Student->paginate($pageSize,false,[
            'query'=>[
                'name'=>$name,
            ]
        ]);
        $this->assign('students',$students);
        return  $this->fetch();
    }
    public function add(){
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
        $Student->password =Student::encriptPassword($postData['password']);
        $Student->school =$postData['school'];
        $Student->profession =$postData['profession'];

        //新增对象至数据表
        if (false === $Student->validate(true)->save()){
            $this->error('新增失败:'.$Student->getError());
        }
        return $this->success('新增成功,新增ID为'.$Student->id,url('index'));
    }
    public function delete()
    {
        //获取传入的ID值
        $id = Request::instance()->param('id/d');

        if (is_null($id)||0===$id){
            return $this->error('未获取到ID信息');
        }

        //获取要删除的对象
        $Student = Student::get($id);

        //要删除的对象不存在
        if (is_null($Student)){
            return $this->error('不存在id为' . $id . '的学生，删除失败');
        }

        //删除对象
        if (!$Student->delete()){
            return $this->error('删除失败'.$Student->getError());
        }

        //进行跳转
        return $this->success('删除成功',url('index'));
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
    public function update()
    {
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