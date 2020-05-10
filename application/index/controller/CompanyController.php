<?php
namespace app\index\controller;
use app\common\model\Company;
use app\common\model\Hr;
use think\Request;
use think\Session;
use think\Controller;
class CompanyController extends HrindexController
{
    public function index(){
        //        只是管理对应的公司

        $Company = new Company;
        $Hr = new Hr;
        $hrId = Session::get('hrId');
        $hr = Hr::get($hrId);
        $company_id = $hr->getData('company_id');

        $company  = Company::get($company_id);

//        $companys = $Company->select($company_id);
//        $company = $companys[0];

        //以上几行可以在model写,    hr中还可以直接利用n:1得到对应的company

        $this->assign('company',$company);
        return $this->fetch();
    }
    public function edit(){
        //获取传入的ID
        $id = Request::instance()->param('id/d');

        //获取当前对象
        if(is_null($Company = Company::get($id))){
            return $this->error('系统未找到ID为' . $id . '的记录');
        }

        $this->assign('Company',$Company);
        return $this->fetch();
    }

    public function update(){
        //获取要更新的记录的ID
        $id = Request::instance()->post('id/d');

        //获取当前对象
        $Company = Company::get($id);

        //判断当前对象是否为空,再更新数据
        if (!is_null($Company)){
            //把表单中填的数据写入当前对象
            $Company->companyName = Request::instance()->post('companyName');
            $Company->employeeNumber = Request::instance()->post('employeeNumber');
            $Company->profession = Request::instance()->post('profession');
            $Company->city = Request::instance()->post('city');
            $Company->address = Request::instance()->post('address');
            $Company->introduction = Request::instance()->post('introduction');

            //更新
            if (false=== $Company->validate(true)->save()){
                return  $this->error('更新失败'.$Company->getError());
            }
//            用了return就直接程序结束了 不用return就没结束要else
            return $this->success('更新成功',url('index'));
        }
    }
}