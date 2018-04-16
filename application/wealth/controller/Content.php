<?php

namespace app\wealth\controller;

use think\Controller;
use think\Db;
use think\Request;
use app\wealth\model\Content as ContentModel;
use app\wealth\controller\Base;
use think\Session;
use app\wealth\model\Question as QuestionModel;
class Content extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index($qid)
    {
        //查看问题详情
        $list=Db::table('question')->where('id',$qid)->find()['title'];
        $id=Db::table('question_content')->where('q_id',$qid)->value('c_id');
        $model=ContentModel::get($id);
        $tagnames=Db::table('tag')->select();
        $c=new QuestionModel();
        $count=$c->count();
        return $this->fetch('index',['list'=>$model,
            'tagnames'=>$tagnames,
             'data'=>$list,
             'count'=>$count
        ]);
    }

    /**
     * 显示资源列表
     *$id:修改条件
     * @return mixed
     */
    public function save($id=null){//$id : 修改的数据
        $request=request();
        if(is_null($id)){
            $model=new QuestionModel();//实例化-添加数据
            $cmodel=new ContentModel();
        }else{
            $model=QuestionModel::get($id);//实例化-添加数据
            $cid=Db::table('question_content')->where('q_id',$id)->value('c_id');
            $cmodel=ContentModel::get($cid);
        }
        if($request->isGet()){
            $data=$model->getData();
            if(empty($data)){
                $data['t_id']=1;
            }
            $c=new QuestionModel();
            $count=$c->count();
            //获取修改数据
            $data2=$cmodel->getData();
            $tagnames=Db::table('tag')->select();
            return $this->fetch('save',[
                'userInfo'=>Session::get('user'),
                'message'=>Session::get('message'),
                'tagnames'=>$tagnames,
                'data1'=>$data,
                'count'=>$count,
                'data2'=>$data2 //表单中读取要修改的数据
            ]);//得到报错信息的值到模板中
        }elseif ($request->isPost()){
            $validate=validate('Content');
            $ch=$validate->batch()->check(input());
            if(!$ch){
               $errors=implode('/',$validate->getError());
               $this->error($errors);
            }
            $data1=[
                'title'=>input('title'),
                'u_id'=>Session::get('user')['id'],
                't_id'=>input('t_id'),
            ];

            if(is_null($id)){
                $data1['create_time']=time();
            }else{
                $data1['update_time']=time();
            }
            $data2=[
                'content'=>input('content')
            ];
            $model->data($data1)->save();
            $cmodel->data($data2)->save();
            if(is_null($id)){
                $data3['q_id']=Db::table('question')->where($data1)->value('id');
                $data3['c_id']=Db::table('content')->where($data2)->value('id');
                Db::table('question_content')->insert($data3);
            }
            $this->redirect('Question/index');//跳转页面
        }
    }


    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
