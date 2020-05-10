<?php
namespace app\index\controller;//命名空间,也说明了文件所在的文件夹
use think\Controller;
use app\common\model\Student;
//Index既是类名,也是文件名,说明这个文件的名字为Index.php
//而且也叫控制器controller
class IndexController extends Controller
{
    public function __construct()
    {
        //调用父类构造函数(必须)
        parent::__construct();

        //验证用户是否登录
        if (!Student::isLogin()){
            return $this->error('plz login first',url('Login/index'));
        }
    }
    public function index()
    {
        //这个类里面的还是有验证 才能进,本系统所有index开头的控制器作为验证类
    }
}
