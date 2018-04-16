<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\model\User as UserModel;
use think\Session;
use think\Db;

class Login extends Controller
{
    /**
     * 显示登录页面
     *
     * @return \think\Response
     */
    public function index()
    {
        return $this->fetch('login/login');
    }

    /**
     * 后台登录
     *
     * @return \think\Response
     */
    public function login(){
        if(request()->isGet()){
            return $this->fetch('login',[
                'message'=>Session::get('message'),
            ]);
        }
        elseif (request()->isPost()){
            $username = input('username');
            $user = UserModel::get(['username'=>$username]);
            if($user){
                if($user['status']!=3){
                    $this->redirect('login',[],302,[
                        'message'=>'此用户不为超级管理员',
                    ]);
                }
                if($user->passwd == md5(md5(input('passwd')).$user->salt)){
                    $data = $user->getData();
                    Session::set('username',$data,'think');

                    $this->redirect('User/index');
                }
                $this->redirect('login',[],302,[
                    'message'=>'密码错误!',
                ]);
            }
            $this->redirect('login',[],302,[
                'message'=>'用户名错误!',
            ]);
        }
    }


    /**
     * 注销用户
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function out()
    {
        Session::delete('username','think');
        $this->success('注销成功','index');
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
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
