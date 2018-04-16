<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\model\User as UserModel;
use think\Db;
use app\admin\controller\Database as DatabaseController;

class User extends DatabaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $limit = 5;
        $model = new UserModel();
        $list = $model->paginate($limit);//分页
        $lists=[];
        foreach ($list as $value){
            $value['last_time']=date('Y-m-d H:i:s',$value['last_time']);
            if($value['status']==1){
                $value['status']='启用';
            }elseif ($value['status']==2){
                $value['status']='禁用';
            }elseif ($value['status']==3){
                $value['status']='超级管理员';
            }
            $lists[]=$value;
        }
        return $this->fetch('index',['list'=>$list,'lists'=>$lists]);
    }
    /**
     * 封装图片函数
     *
     * @return \think\Response
     */
    public function upload(){
            // 获取表单上传文件 例如上传了001.jpg
   $file = request()->file('photo');
   // 移动到框架应用根目录/public/uploads/ 目录下
   $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
   if($info){        // 成功上传后 获取上传信息
        return $info->getSaveName();
    }else{       // 上传失败获取错误信息
        echo $file->getError();
    }
}



    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function add()
    {
        if(request()->isGet()){
            return $this->fetch('add');
        }elseif (request()->isPost()){
            $data = input();
            $name = Db::name('user')->where(['username'=>$data['username']])->find();
            if($name){
                $this->error('该用户已存在');
                die;
            }
            $model = new UserModel();
            $photo = $this->upload();
            $yzm = rand(0,9).rand(0,9).rand(0,9).rand(0,9);
            $data['salt'] = $yzm;
            $data['photo']=$photo;
            $data['last_time']=time();
            $data['passwd']=md5(md5($data['passwd']).$yzm);
            $re = $model->save($data);
            if($re){
                $this->success('添加成功','index');
            }else{
                $this->error('添加失败');
            }
        }
    }

    public function update(Request $request, $id)
    {
        $data = Db::name('user')->find($id);
        if(request()->isPost()){
            $model = new UserModel();
            $data = input();
            if(!$_FILES['photo']['tmp_name']==''){
                $photo = $this->upload();
                $data['photo']=$photo;
            }
            $re = $model->save($data,['id'=>$id]);
            if($re){
                $this->success('修改成功','index');
            }
        }
        return $this->fetch('update',['data'=>$data]);
    }


    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function mutildel()
    {   #/a:返回的是数组
        UserModel::destroy(input('selected/a',[]));
        $this->redirect('index');//跳转到查看页面
    }
}
