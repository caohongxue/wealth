<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/4/3
 * Time: 20:14
 */
namespace app\admin\controller;

use think\Controller;
use think\Db;
use app\admin\model\Content as ContentModel;
use app\admin\controller\Database as DatabaseController;

class Content extends DatabaseController{
    public function esc(){
        $model=new ContentModel();
        $list=$model->order('id','esc')->select();
        return $this->fetch('index',['result'=>$list]);
    }
    public function desc(){
        $model=new ContentModel();
        $list=$model->order('id','desc')->select();
        return $this->fetch('index',['result'=>$list]);
    }

    public function see($id){
        $model=new ContentModel();
        $cid=Db::table('question_content')->find($id)['c_id'];
        $content=Db::table('content')->where('id',$cid)->select();
        return $this->fetch('index',['result'=>$content]);
    }
}