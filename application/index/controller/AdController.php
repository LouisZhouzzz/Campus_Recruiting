<?php
namespace app\index\controller;
use app\common\model\Ad;
use app\common\model\Hr;
use think\Session;
use think\Request;
use think\Controller;
class AdController extends HrindexController
{
    public function index()
    {
//        只是管理对应的招聘广告
        $Ad = new Ad;
        $Hr = new Hr;
        $hrId = Session::get('hrId');
        $hr = Hr::get($hrId);

//        对数据表类操作 好于直接对数据库操作   用where操作好于直接用sql语句操作
        //获取查询信息
        $jobName = Request::instance()->get('jobName');

        //定制查询信息   一部分里面再挑一部分多多个where就好
         if (!empty($jobName)){
            $Ad ->where('hr_id','eq',$hrId)
                ->where('jobName','like','%'.$jobName.'%');
             $ads= $Ad->select();
         }else{
                    $ads = $hr->ads;
//             通过一对多拿到hr对应的招聘广告   或者  再用上面id匹配
         }

      // $pageSize = 5; // 每页显示5条数据
    //这是tp5的 bug, 分页时第二页带不上参数,然而用了附带参数的方法,查询时又查不了
     /*   $ads = $Ad->paginate($pageSize,false,[
            'query'=>[
                'jobName'=>$jobName,
            ]
        ]);*/

        $this->assign('ads',$ads);
        //使用默认的分页条数就行
        return $this->fetch();
    }

    //add 等等增删改查
   public function add()
   {
       /*$hrId = Session::get('hrId');
       $this->assign('hrId',$hrId); 没必要把他放到V层{$hrId}又传回来
       直接在C层给不就得了*/
       return $this->fetch();
   }
    public function save(){

        //接收传入数据
        $postData = Request::instance()->post();
        //实例化Ad空对象
        $Ad = new Ad();

        $hrId = Session::get('hrId');

        //为对象赋值
        $Ad->hr_id =$hrId;
        $Ad->jobName =$postData['jobName'];
        $Ad->hireNumber =$postData['hireNumber'];
        $Ad->profession =$postData['profession'];
        $Ad->city = $postData['city'];
        $Ad->salary =$postData['salary'];
        $Ad->jobInfo =$postData['jobInfo'];
        $Ad->contactInfo =$postData['contactInfo'];

        //新增对象至数据表
        if (false === $Ad->validate(true)->save()){
            $this->error('新增失败:'.$Ad->getError());
        }
        return $this->success('新增成功,新增ID为'.$Ad->id,url('index'));
    }

    public function delete()
    {
        //获取传入的ID值
        $id = Request::instance()->param('id/d');

        if (is_null($id)||0===$id){
            return $this->error('未获取到ID信息');
        }

        //获取要删除的对象
        $Ad = Ad::get($id);

        //要删除的对象不存在
        if (is_null($Ad)){
            return $this->error('不存在id为' . $id . '的招聘广告，删除失败');
        }

        //删除对象
        if (!$Ad->delete()){
            return $this->error('删除失败'.$Ad->getError());
        }

        //进行跳转
        return $this->success('删除成功',url('index'));
    }

    public function edit(){
        //获取传入的ID
        $id = Request::instance()->param('id/d');

        //获取当前对象
        if(is_null($Ad = Ad::get($id))){
            return $this->error('系统未找到ID为' . $id . '的记录');
        }

        $this->assign('Ad',$Ad);
        return $this->fetch();
    }
    public function update()
    {
        //获取要更新的记录的ID
        $id = Request::instance()->post('id/d');

        //获取当前对象
        $Ad = Ad::get($id);

        //判断当前对象是否为空,再更新数据
        if (!is_null($Ad)){
            //把表单中填的数据写入当前对象
            $Ad->jobName = Request::instance()->post('jobName');
            $Ad->hireNumber = Request::instance()->post('hireNumber');
            $Ad->jobInfo = Request::instance()->post('jobInfo');
            $Ad->contactInfo = Request::instance()->post('contactInfo');
            $Ad->profession = Request::instance()->post('profession');
            $Ad->city = Request::instance()->post('city');
            $Ad->salary = Request::instance()->post('salary');


            //更新
            if (false=== $Ad->validate(true)->save()){
                return  $this->error('更新失败'.$Ad->getError());
            }
//            用了return就直接程序结束了 不用return就没结束要else
            return $this->success('更新成功',url('index'));
        }
    }
    public function test(){
        $ad = Ad::get(1);
        return var_dump($ad->Resumes);
    }
}