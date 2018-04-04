<?php

namespace app\wealth\controller;

use think\Controller;
use think\Db;
use think\Request;
use app\wealth\model\Content as ContentModel;
use app\wealth\controller\Base;
use think\Session;

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
        $id=Db::table('content')->where('q_id',$qid)->value('c_id');
        $model=ContentModel::get($id);
        return $this->fetch('index',['list'=>$model]);
    }

    /**
     * 显示资源列表
     *$id:修改条件
     * @return mixed
     */
    public function save($id=null){//$id : 修改的数据
        $request=request();
        if(is_null($id)){
            $model=new ContentModel();//实例化-添加数据
        }else{
            $model=ContentModel::get($id);//实例化-修改数据
        }

        if($request->isGet()){
            $data=Session::has('data')?Session::get('data'):$model->getData();
            //获取修改数据
            return $this->fetch('save',[
                'message'=>Session::get('message'),
                'data'=>$data //表单中读取要修改的数据
            ]);//得到报错信息的值到模板中
        }elseif ($request->isPost()){
            $data=$request->post();//收集表单数据
            $validate=validate('Admin');
            $ch=$validate->batch()->check($data);
            if(!$ch){
                $this->redirect('save',[],302,[
                    'message'=>$validate->getError(),//得到报错信息
                    'data'=>$data
                ]);
            }
            $model->data($data);//收集表单数据
            $model->save();//保存数据
            $this->redirect('index');//跳转页面
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
