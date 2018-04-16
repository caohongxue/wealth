<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/4/3
 * Time: 16:32
 */
namespace app\admin\Controller;

use think\Controller;
use think\Db;

class Ques extends Controller {

    public function index(){
        $model=new \app\admin\Model\Ques();
        $list=$model->data($model->select())->toArray();
        $lists=[];
        foreach ($list as $value)
        {
            $value['t_id']= Db::table('tag')->find($value['t_id'])['tagname'];
            $value['u_id']=Db::table('user')->find($value['u_id'])['username'];
            $lists[]=$value;
        }

        return $this->fetch('index',['result'=>$lists]);
    }
    public function esc(){
        $model=new \app\admin\Model\Ques();
        $list=$model->order('id','esc')->select();
        return $this->fetch('index',['result'=>$list]);
    }
    public function desc(){
        $model=new \app\admin\Model\Ques();
        $list=$model->order('id','desc')->select();
        return $this->fetch('index',['result'=>$list]);
    }
    function sel(){
        if (empty($_POST['title'])) {
            $model=new \app\admin\model\Ques();
            $list = $model->select();
        } else {
            //(2)根据名字模糊查询,(也可以传入多个条件)
            $model=new \app\admin\model\Ques();
            $list = $model->where(['title'=>['like','%'.$_POST['title'].'%']])->select();
        }
        return $this->fetch('index',['result'=>$list]);
    }
    public function delete()
    {
        \app\admin\model\Ques::destroy(input('id/a',[]));//删除多条数据
        $this->redirect('index');
    }
}