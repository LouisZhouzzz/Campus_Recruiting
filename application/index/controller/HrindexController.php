<?php
namespace app\index\controller;//命名空间,也说明了文件所在的文件夹
use think\Controller;
use app\common\model\Hr;
//Index既是类名,也是文件名,说明这个文件的名字为Index.php
//而且也叫控制器controller
class HrindexController extends Controller
//管理员是否登录的验证类
{
    public function __construct()
    {
        //调用父类构造函数(必须)
        parent::__construct();

        //验证用户是否登录
        if (!Hr::isLogin()){
            return $this->error('plz login first',url('Hrlogin/index'));
        }
    }
    public function index()
    {

    }
}
