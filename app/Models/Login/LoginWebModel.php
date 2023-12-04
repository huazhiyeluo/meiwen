<?php namespace App\Models\Login;

class LoginWebModel extends LoginModel {

    //获取Access Token
    public $access_token;
    //openid
    public $openid;

    //登录状态
    public $loginState = 'LoginwebState';

    public $sid = 0;

    public $email;

    public $phone;

    public $password;

    public function __construct() {
        parent::__construct();
    }

    public function checkBack($params) {
        $this->email    = $params['email'];
        $this->phone    = $params['phone'];
        $this->password = $params['password'];
        $this->username = $params['username'];
        $this->photo    = $params['photo'];
    }

    //获取用户信息
    public function getUserInfo() {
        $where = [];
        if ($this->email) {
            $where['email'] = $this->email;
        }
        if ($this->photo) {
            $where['photo'] = $this->photo;
        }
        $user = $this->userModel->getUserOne($where);
        if (!$user) {
            $this->errRet = ['code' => 3, 'msg' => '用户不存在，请返回重试'];
            return false;
        }
        if (md5($user['salt'] . $this->password) != $user['password']) {
            $this->errRet = ['code' => 4, 'msg' => '密码错误，请返回重试'];
            return false;
        }
        $this->openid       = $user['openid'];
        $this->access_token = '';
    }

}
