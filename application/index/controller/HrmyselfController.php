<?php
namespace app\index\controller;
use app\common\model\Hr;
use app\common\model\Ad;
use app\common\model\Resume;
use app\common\model\ResumeAd;
use think\Request;
use think\Session;
class HrmyselfController extends HrindexController
{
    public function index(){
        //学生个人中心页面  显示个人信息和功能
        $Hr = new Hr;


        //或者利用存的sessionId查找显示
        $hrId= Session::get('hrId');
        $hr = Hr::get($hrId);

        $this->assign('hr',$hr);
        return $this->fetch();
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
            $hrId= Session::get('hrId');
            $hr = Hr::get($hrId);

            //检测原始密码长度
            if (!Hr::checkPasswordLength($oldPassword) ){
                return $this->error('原始密码长度不小于3!'.$hr->getError());
            }

            if (Hr::encriptPassword($oldPassword) === $hr->password){
                //检测新密码长度
                if (!Hr::checkPasswordLength($password) ){
                    return $this->error('新密码长度不小于3!'.$hr->getError());
                }

                //新密码不能与旧密码相同
                if ($password === $oldPassword){
                    return $this->error('新密码不能与旧密码相同!'.$hr->getError());
                }

                $hr->password = Hr::encriptPassword($password);
                //修改密码至数据表

                //测试显示不论保存相同还是不同都save()结果都是1
                //这里save不能判断保存于原来同还是不同了 不能判断密码与原密码是否同
                if (0 === $hr->save()){
                    return $this->error('密码修改失败!'.$hr->getError());
                }
                return $this->success('密码修改成功!',url('index'));
            }else{
                return $this->error('password incorrect',url('password'));
            }
        }else{
            return $this->error('原始密码不能为空',url('password'));
        }

    }
    public function myAccept(){
        $hrId= Session::get('hrId');
        $hr = Hr::get($hrId);

        $Ad = new Ad;
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

        $this->assign('ads',$ads);
        return $this->fetch();
    }

    public function resume(){
        //获取传入的ID
        $id = Request::instance()->param('id/d');

        //获取当前对象
        if(is_null($Ad = Ad::get($id))){
            return $this->error('系统未找到ID为' . $id . '的记录');
        }

        //获取查询信息
        $name = Request::instance()->get('name');
        $Resume = new Resume;
//        $ResumeAd = new ResumeAd;
        //定制查询信息   一部分里面再挑一部分多多个where就好
        //这个查询跨表了 先在选出ResumeAd对应的表中找ad_id与id相同的部分 的简历广告关系
        //然后在简历表中 匹配简历名字为name的简历
        //比较复杂了
        if (!empty($name)){
//            其实直接可以先多对多拿到简历表
            $resumes1 = $Ad->Resumes;

            $datas = array();
            foreach ($resumes1 as $i){
                array_push($datas, $i->id);
            }

            $Resume->where('id','in',$datas)
                   ->where('name','like','%'.$name.'%');

            $resumes = $Resume->select();
            if(empty($resumes)){
                return $this->error('没有找到所查找的姓名的简历',
                    '/thinkphp5/public/index.php/index/hrmyself/resume/id/'.$id);
                //对于这种带/id/数字  的url   url()助手函数没法很好生成url 暂时用绝对地址实现
            }

           /* $datas = array();
            foreach ($resumes1 as $i){
                $data = array();
                if ($i->name === $name){
                    $datas[]=$i;
                }
                array_push($datas, $data);
            }
             return var_dump($datas);*/


            /*   $resumes1->where('name','like','%'.$name.'%');
               //然后是查询后的
               $resumes = $resumes1->select();  不行因为数组不能where*/


            //下面相当于把多对多重做了一遍通过招聘广告id获取简历列表 多余 不能像hr拿ad多对一简单
        //    $ResumeAd ->where('ad_id','eq',$id);
//                ->where('name','like','%'.$name.'%');
//            $ResumeAds= $ResumeAd->select();
           /* $datas = array();
            foreach ($ResumeAds as $ResumeAd){
                $data = array();
                $data[] = $ResumeAd->Resume;  还用了多对一
                array_push($datas, $data);
            }*/
            //$datas就是 与id匹配的简历对象的数组
//            根据datas foreach判断name  最后不能成功 因为tp无法,这是数组
//            没有->name 没有getData()方法  越做越复杂
//            return var_dump($resumes);

        }else{
             $resumes = $Ad->Resumes;
//             通过多对多拿到该广告对应的简历
        }

         //获取招聘广告对应的简历
         // $resumes = $Ad->Resumes;

        //没有时候返回空数组 不是null 不能is_null 要用!empty
       if(empty($resumes)){
           return $this->error('所选招聘广告没有收到任何简历',url('myAccept'));
        }


        $this->assign('resumes',$resumes);
        return $this->fetch();
    }

    public function resumeDetail(){
        //获取传入的ID
        $id = Request::instance()->param('id/d');

        //获取当前对象
        if(is_null($resume = Resume::get($id))){
            return $this->error('系统未找到ID为' . $id . '的记录');
        }

        $this->assign('resume',$resume);
        return $this->fetch();
    }

    public function edit(){
        //获取传入的ID
        $id = Request::instance()->param('id/d');

        //获取当前对象
        if(is_null($Hr = Hr::get($id))){
            return $this->error('系统未找到ID为' . $id . '的记录');
        }

        $this->assign('Hr',$Hr);
        return $this->fetch();
    }
    public function update(){
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

            //更新
            if (false=== $Hr->validate(true)->save()){
                return  $this->error('更新失败'.$Hr->getError());
            }
//            用了return就直接程序结束了 不用return就没结束要else
            return $this->success('更新成功',url('index'));
        }
    }
}