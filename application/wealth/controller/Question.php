<?php

namespace app\wealth\controller;

use think\Controller;
use think\Db;
use think\Request;
use app\wealth\controller\Base;
use app\wealth\model\Question as QuestionModel;
use think\Session;
use app\wealth\model\User as UserModel;
class Question extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //所有主题展示
        $model=new QuestionModel();
        $list=$model->data($model->select())->toArray();
//        dump($list);exit;
        $lists=[];
        foreach ($list as $value){
            $value['uname']=Db::table('user')->where('id',$value['u_id'])->value('username');
            $value['tags']=Db::table('tag')->where('id',$value['t_id'])->value('tagname');
            $value['photo']=Db::table('user')->where('id',$value['u_id'])->value('photo');
            $lists[]=$value;
        }
        $tagnames=Db::table('tag')->select();
        $count=$model->count();
        return $this->fetch('index',['list'=>$lists,
            'userInfo'=>Session::get('userInfo'),
            'tagnames'=>$tagnames,
            'count'=>$count
        ]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function user($uid=null)
    {
        //个人用户展示
        if(empty($uid)){
            $uid=Session::get('userInfo')['id'];
        }
        $model=new QuestionModel();
        $list=$model->data($model->where('u_id',$uid)->select())->toArray();
        $lists=[];
        foreach ($list as $value){
            $value['uname']=Db::table('user')->where('id',$value['u_id'])->value('username');
            $value['tags']=Db::table('tag')->where('id',$value['t_id'])->value('tagname');
            $value['photo']=Db::table('user')->where('id',$value['u_id'])->value('photo');
            $lists[]=$value;
        }
        $tagnames=Db::table('tag')->select();
        $count=$model->count();
        return $this->fetch('index',['list'=>$lists,
            'userInfo'=>Session::get('userInfo'),
            'tagnames'=>$tagnames,
             'count'=>$count
        ]);
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function tag($tid)
    {
        //根据标签查看问题
        $model=new QuestionModel();
        $list=$model->data($model->where('t_id',$tid)->select())->toArray();
        $lists=[];
        foreach ($list as $value){
            $value['uname']=Db::table('user')->where('id',$value['u_id'])->value('username');
            $value['tags']=Db::table('tag')->where('id',$value['t_id'])->value('tagname');
            $value['photo']=Db::table('user')->where('id',$value['u_id'])->value('photo');
            $lists[]=$value;
        }
        $count=$model->count();
        $tagnames=Db::table('tag')->select();
        return $this->fetch('index', ['list'=>$lists,
            'userInfo'=>Session::get('userInfo'),
            'tagnames'=>$tagnames,
            'count'=>$count
        ]);
    }
    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $re=QuestionModel::get($id)->delete();
        if($re){
            $cid=Db::table('question_content')->where('q_id',$id)->value('c_id');
            Db::table('question_content')->where('q_id',$id)->delete();
            $rs=Db::table('content')->where('id',$cid)->delete();
            if($rs){
                $this->redirect('index');
            }
        }
    }

    public function search(){
        $username=['like','%'.input("message").'%'];
        $model=new UserModel();
        $u_id=$model->where(['username'=>$username])->select();
        $u_ids=[];
        foreach ($u_id as $value){
            $u_ids[]=$value['id'];
        }
        $qmodel=new QuestionModel();
        $list=$qmodel->where(['u_id'=>['in',$u_ids]])->select();
        $lists=[];
        foreach ($list as $value){
            $value['uname']=Db::table('user')->where('id',$value['u_id'])->value('username');
            $value['tags']=Db::table('tag')->where('id',$value['t_id'])->value('tagname');
            $value['photo']=Db::table('user')->where('id',$value['u_id'])->value('photo');
            $lists[]=$value;
        }
        $count=$model->count();
        $tagnames=Db::table('tag')->select();
        return $this->fetch('index',['list'=>$lists,
            'userInfo'=>Session::get('userInfo'),
            'tagnames'=>$tagnames,
             'count'=>$count
        ]);
    }
}
