<?php
namespace app\index\controller;//命名空间,也说明了文件所在的文件夹
use app\common\model\Ad;
use app\common\model\Company;
use app\common\model\Hr;
use think\Controller;
use think\Session;
use app\common\model\Student;
use think\Request;
class HomeController extends Controller
//游客能看,不需要继承验证类
{
    public function index()
    {
        //判断学生登录状态,把状态assign到前端 然后前端根据这个状态个人中心的控制显示和隐藏
        $studentId= Session::get('studentId');
        $student = Student::get($studentId);
//        还是要不管怎么情况都assign进前端,  登录才assign会出现这个变量未定义错误
        $this->assign('student',$student);
        if (is_null($studentId)){
            $isLogin = 0;

        }else{
            $isLogin = 1;
        }
        $this->assign('isLogin',$isLogin);

        return $this->fetch();
    }
    public function adResult(){
        //判断学生登录状态,把状态assign到前端 然后前端根据这个状态个人中心的控制显示和隐藏
        $studentId= Session::get('studentId');
        $student = Student::get($studentId);
//        还是要不管怎么情况都assign进前端,  登录才assign会出现这个变量未定义错误
        $this->assign('student',$student);
        if (is_null($studentId)){
            $isLogin = 0;

        }else{
            $isLogin = 1;
        }
        $this->assign('isLogin',$isLogin);

        //获取查询信息
        $jobName = Request::instance()->get('jobName');
        $city = Request::instance()->get('city');
        $profession = Request::instance()->get('profession');

        $pageSize = 5; // 每页显示5条数据

        $Ad = new Ad;
        //定制查询信息
        if (!empty($jobName)||!empty($city)||!empty($profession)){
            $Ad->where('jobName','like','%'.$jobName.'%')
                ->where('city','like','%'.$city.'%')
                ->where('profession','like','%'.$profession.'%');
        }else{
            return $this->error('职位名称,城市,行业输入不能全为空',url('index'));
        }

        $ads =$Ad->select();

//        $ads =$Ad->paginate($pageSize);
        //!empty() php5.5才能用来判断数组 这里用空数组长度判断
         if(0!==count($ads)){
            /*$ads = $Ad->paginate($pageSize,false,[
                'query'=>[
                    'jobName'=>$jobName,
                    'city'=>$city,
                    'profession'=>$profession,
                ]
            ]);*/
/*这里带url参数是为了分页点到第二页后 模板页面还能value="{:input('get.name')}"获取
 但是 $Ad->select()可以  ->paginate()也行  但是点第二页就不显示了 因为上方()不可用
导致直接分页 无法跳转第二页因为第二页检测输入为空, 带参数跳转又不支持  只能用select
现在发现select查询自动带参数*/

            $this->assign('ads',$ads);
        }else{
            return $this->error('抱歉,没有找到符合您要求的职位信息',url('index'));
        }

         return $this->fetch();
    }
    public function exactAd(){
        //判断学生登录状态,把状态assign到前端 然后前端根据这个状态个人中心的控制显示和隐藏
        $studentId= Session::get('studentId');
        $student = Student::get($studentId);
//        还是要不管怎么情况都assign进前端,  登录才assign会出现这个变量未定义错误
        $this->assign('student',$student);
        if (is_null($studentId)){
            $isLogin = 0;

        }else{
            $isLogin = 1;
        }
        $this->assign('isLogin',$isLogin);

        //获取传入的ID
        $id = Request::instance()->param('id/d');

        //获取当前对象
        if(is_null($ad = Ad::get($id))){
            return $this->error('系统未找到ID为' . $id . '的记录');
        }

        $this->assign('ad',$ad);
        return $this->fetch();
    }
    public function apply(){
        $studentId= Session::get('studentId');

        //home控制器游客可进 没有设置验证器   但投简历时候由于必须学生登录才行,先检测
        if (!is_null($studentId)){
            $student = Student::get($studentId);
            $resume =$student->Resume; //->Resume()返还关联对象  ->Resume返回简历对象


            //获取要更新的记录的ID
            $id = Request::instance()->post('id/d');
            //获取当前对象
            $ad = Ad::get($id);

            //先获取学生简历原本对应的广告的id,然后组成数组,判断现在的广告id是否在
            //这个数组里,在的话,说明已投递过了,返回已投递,不要重复投递,否则可以正常投递
             $Ads=$resume->Ads;
            $datas = array();
            foreach ($Ads as $i){
                array_push($datas, $i->id);
            }
            //如果当前广告不在数组里,即表示这个广告之前没投过,可正常投,否则提示不能重复投递
            if(!in_array($id,$datas)){
                //添加简历和招聘广告关系到中间表
                if(!$resume->Ads()->save($ad)){
                    return $this->error('投递简历失败'.$resume->getError(),url('exactAd'));
                }

                return $this->success('恭喜投递简历成功',url('Studentmyself/myApply'));
            }else{
                return $this->error('您已投递过该职位,请勿重复投递','/thinkphp5/public/index.php/index/Home/exactAd/id/'.$id);
                //这里url()助手函数总是无法正确识别 带/id/数字  的url  只能用绝对地址
            }

        }else{
            $this->error('请先登录再投递简历',url('Login/index'));
        }
    }
    public function companyAd(){
        //判断学生登录状态,把状态assign到前端 然后前端根据这个状态个人中心的控制显示和隐藏
        $studentId= Session::get('studentId');
        $student = Student::get($studentId);
//        还是要不管怎么情况都assign进前端,  登录才assign会出现这个变量未定义错误
        $this->assign('student',$student);
        if (is_null($studentId)){
            $isLogin = 0;

        }else{
            $isLogin = 1;
        }
        $this->assign('isLogin',$isLogin);

        //获取传入的ID
        $id = Request::instance()->param('id/d');

        //获取当前对象
        if(is_null($company = Company::get($id))){
            return $this->error('系统未找到ID为' . $id . '的记录');
        }
        //把公司填进去到公司的职位页面去
        $this->assign('company',$company);

        $hrs = $company->hrs; //hrs()返回的是关联对象  hrs返回的才是hr
        $this->assign('hrs',$hrs);

        return $this->fetch();
    }
}