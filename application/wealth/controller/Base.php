<?php
/**
 * Created by PhpStorm.
 * User: MACHENIKE
 * Date: 2018/4/3
 * Time: 18:53
 */
namespace app\wealth\controller;

use think\Controller;
use think\Session;

class Base extends Controller{
    protected $beforeActionList = [
        'first'
   ];
    public function first(){
        $userInfo=Session::get('userInfo');
        if(!$userInfo){
            $this->redirect('User/login');
        }
    }

}