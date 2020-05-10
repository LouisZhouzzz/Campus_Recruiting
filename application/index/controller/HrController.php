<?php
namespace app\index\controller;
use app\common\model\Hr;
use app\common\model\Company;
use think\Request;
use think\Controller;
class HrController extends AdminindexController
{
    public function index(){
        //获取查询信息
        $name = Request::instance()->get('name');

        $pageSize = 5; // 每页显示5条数据

        $Hr = new Hr;
        //定制查询信息
        if (!empty($name)){
            $Hr->where('name','like','%'.$name.'%');
        }

        $hrs = $Hr->paginate($pageSize,false,[
            'query'=>[
                'name'=>$name,
            ]
        ]);
        $this->assign('hrs',$hrs);
        return  $this->fetch();
    }

    public function add(){
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
        $Hr->password =Hr::encriptPassword($postData['password']);
        $Hr->company_id =$postData['company_id'];


        //新增对象至数据表
        if (false === $Hr->validate(true)->save()){
            $this->error('新增失败:'.$Hr->getError());
        }
        return $this->success('新增成功,新增ID为'.$Hr->id,url('index'));
    }
    public function delete()
    {
        //获取传入的ID值
        $id = Request::instance()->param('id/d');

        if (is_null($id)||0===$id){
            return $this->error('未获取到ID信息');
        }

        //获取要删除的对象
        $Hr = Hr::get($id);

        //要删除的对象不存在
        if (is_null($Hr)){
            return $this->error('不存在id为' . $id . '的学生，删除失败');
        }

        //删除对象
        if (!$Hr->delete()){
            return $this->error('删除失败'.$Hr->getError());
        }

        //进行跳转
        return $this->success('删除成功',url('index'));
    }
    public function edit(){
        //获取所有公司信息
        $companys = Company::all();
        $this->assign('companys',$companys);

        //获取传入的ID
        $id = Request::instance()->param('id/d');

        //获取当前对象
        if(is_null($Hr = Hr::get($id))){
            return $this->error('系统未找到ID为' . $id . '的记录');
        }

        $this->assign('Hr',$Hr);
        return $this->fetch();
    }
    public function update()
    {
        //获取要更新的记录的ID
        $id = Request::instance()->post('id/d');

        //获取当前对象
        $Hr = Hr::get($id);

        //判断当前对象是否为空,再更新数据
        if (!is_null($Hr)){
            //把表单中填的数据写入当前对象
            $Hr->name = Request::instance()->post('name');
            $Hr->username = Request::instance()->post('username');
            $Hr->sex = Request::instance()->post('sex');
            $Hr->email = Request::instance()->post('email');
            $Hr->company_id =Request::instance()->post('company_id/d');
            //更新

            //保存失败的时候，$result是bool false
            //未更改时$result是int(0) 区别两者
            if (false=== $Hr->validate()->save()){
                //注意这里是validate() 与 validate(true) 效果相同
                return  $this->error('更新失败'.$Hr->getError());
            }
//            用了return就直接程序结束了 不用return就没结束要else
            return $this->success('更新成功',url('index'));
        }
    }
}