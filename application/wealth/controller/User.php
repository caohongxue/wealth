<?php

namespace app\wealth\controller;

use think\Controller;
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
        dump(rand(1,30));
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

}
