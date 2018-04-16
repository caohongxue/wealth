<?php

namespace app\wealth\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Session;
use app\wealth\model\User as UserModel;
class User extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function login(){
        if(request()->isGet()){
            return $this->fetch('login',[
                'message'=>Session::get('message')
            ]);
        }
        elseif(request()->isPost()){
            $username=input('username');
            $admin=UserModel::get(['username'=>$username]);
            if($admin){
                if($admin->passwd == md5(md5(input('password')).$admin->salt)){
                    $data=$admin->getData();
                    $data['last_time']=time();
                    UserModel::get(['username'=>$username])->save($data);
                    Session::set('userInfo',$data);
                    $this->redirect('Question/index');
                }
                $this->redirect('login',[],302,[
                    'message'=>'密码错误'
                ]);
            }
            $this->redirect('login',[],302,[
                'message'=>'用户不存在'
            ]);
        }
    }
    public function index(){
        $this->error('找管理员去');
    }
    public function loginOut(){
        Session::delete('userInfo');
        $this->redirect('login');
    }
    public function save(){
        $model=UserModel::get(Session::get('userInfo')['id']);

        if(\request()->isPost()){
            $data=[
                'username'=>input('username'),
            ];
            $re=Db::table('user')->where($data)->find();
            if($re){
                $this->error('用户名已存在');
            }
            $salt=rand(0,9).rand(0,9).rand(0,9).rand(0,9);
            if($model['passwd']!=md5(md5(input('passwd')).$model['salt'])){
                $data['passwd']=md5(md5(input('passwd')).$salt);
                $data['salt']=$salt;
            }
            $re=$model->save($data);
            if($re){
                Session::delete('userInfo');
                $this->success('修改用户信成功','login');
            }else{
                $this->redirect('save');
            }
        }
        $count=\app\wealth\model\Question::count();
        $tagnames=Db::table('tag')->select();
        return $this->fetch('save',[
            'data'=>$model,
            'tagnames'=>$tagnames,
            'userInfo'=>Session::get('user'),
            'count'=>$count
            ]);
    }
    public function upload(){
         // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('photo');
        if($file){
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                $photo= $info->getSaveName();
            }else{
                $this->error($file->getError());
            }
            $data['photo']=$photo;
            $model=UserModel::get(Session::get('userInfo')['id']);
            $re=$model->save($data);
            if($re){
                $this->success('修改头像成功','save');
            }else{
                $this->error('修改头像失败','save');
            }
        }else{
            $this->error('头像未上传','save');
        }
    }

}
