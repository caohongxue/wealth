<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;
use app\admin\model\Tag as TagModel;
use app\admin\controller\Database as DatabaseController;

class Tag extends DatabaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $list=TagModel::all();
        return $this->fetch('index',['list'=>$list]);
    }

    public function show()
    {
        $list=TagModel::all();
        return $this->fetch('index',['list'=>$list]);
    }
    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function thin()
    {
        $sql='select * from `tag`,`question` where tag.id=question.t_id';
        $list=\db('question')->query($sql);
//        var_dump($list);die;
        return $this->fetch('thin',['list'=>$list]);
    }

    /**
     * 删除资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function delete($id)
    {
        if(isset($id)){
            TagModel::destroy($id);
            $this->success('删除成功',url('index'));
        }else{
            $this->error('您要删除的信息不存在');
        }
    }

    /**
     * 根据id排序
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function esc()
    {
        $model=new TagModel();
        $list=$model->order('id','esc')->select();
        return $this->fetch('index',['list'=>$list]);
    }

    /**
     * 根据id排序
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function desc()
    {
        $model=new TagModel();
        $list=$model->order('id','desc')->select();
        return $this->fetch('index',['list'=>$list]);
    }

    /**
     * 搜索
     *
     * @return \think\Response
     */
    function sel(){
        if (empty($_POST['g_name'])) {
            $model=new TagModel();
            $list = $model->select();
        } else {
            //(2)根据名字模糊查询,(也可以传入多个条件)
            $model=new TagModel();
            $list = $model->where(['tagname'=>['like','%'.$_POST['g_name'].'%']])->select();
        }

        return $this->fetch('index',['list'=>$list,'g_name'=> $_POST['g_name']]);
    }

    public function add($id=null){
        $request=request();
        if(is_null($id)){
            $model=new TagModel();
        }else{
            $model=TagModel::get($id);
        }

        if($request->isGet()){
            $data=$model->getData();
            return $this->fetch('add',[
                'data'=>$data
            ]);
        }elseif ($request->isPost()){
            $data=$request->post();
            if(isset($data['id'])){
                $data['update_time']=time();
            }else{
                $data['create_time']=time();
            }
            $model->data($data);
            $model->save();
            $this->success('保存标记成功',url('Tag/index'));
        }
    }
}
