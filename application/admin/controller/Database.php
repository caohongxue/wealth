<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Session;

class Database extends Controller
{
    function hasLogin(){
         return Session::has('username','think');
    }
    function database(){
        if(!$this->hasLogin()){
            $this->redirect('Login/index');
        }
    }
    protected $beforeActionList = [
        'database'
    ];
}
