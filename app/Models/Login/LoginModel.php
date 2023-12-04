<?php namespace App\Models\Login;

use CodeIgniter\Model;

class LoginModel extends Model {
    protected $table      = 'ts_article';
    protected $primaryKey = 'article_id';

    protected $allowedFields = [];
    protected $beforeInsert  = ['beforeInsert'];
    protected $beforeUpdate  = ['beforeUpdate'];

    //错误信息
    protected $errRet;

    protected $session;
    protected $client;
    protected $userModel;

    protected $uid;
    protected $ip;
    protected $username;
    protected $photo;

    public function __construct() {
        $this->client    = \Config\Services::curlrequest();
        $this->userModel = new \App\Models\UserModel();
    }

    protected function beforeInsert(array $data) {
        return $data;
    }

    protected function beforeUpdate(array $data) {
        return $data;
    }

    private function isNewUser() {
        //sid = 0 普通账号 1 QQ账号 2 树洞账号
        $userOpen = $this->userModel->getUserOpenOne(['sid' => $this->sid, 'openid' => $this->openid]);

        if (!$userOpen) {
            $this->uid = $this->userModel->addUserOpen(['sid' => $this->sid, 'openid' => $this->openid, 'access_token' => $this->access_token, 'uptime' => time()]);
            return true;
        } else {
            $this->userModel->editUserOpen(['access_token' => $this->access_token, 'uptime' => time()], ['uid' => $userOpen['uid']]);
            $this->uid = $userOpen['uid'];
        }
        return false;
    }

    public function loginInit($params) {
        $this->ip = $params['ip'];

        $this->checkBack($params);
        $this->getUserInfo();
        if (isset($this->errRet['code']) && $this->errRet['code'] != 0) {
            return $this->errRet;
        }
        $isUserUser = $this->isNewUser();

        if ($isUserUser) {
            $ret = $this->addUser();
        } else {
            $ret = $this->updateUser();
        }
        //登录成功后处理
        if ($ret['code'] == 0) {
            session()->set('userInfo', $ret['data']);
        }
        return $ret;
    }

    private function addUser() {
        //插入user表
        $uInfo               = [];
        $uInfo['uid']        = $this->uid;
        $uInfo['username']   = $this->username;
        $uInfo['photo']      = $this->photo;
        $uInfo['reg_ip']     = $this->ip;
        $uInfo['reg_time']   = time();
        $uInfo['login_ip']   = $this->ip;
        $uInfo['login_time'] = time();
        $this->userModel->addUserInfo($uInfo);

        $userInfo = $this->userModel->getUserInfo($this->uid);

        return ['code' => 0, 'data' => $userInfo , 'msg' => '注册成功'];
    }

    private function updateUser() {
        $uInfo               = [];
        $uInfo['login_ip']   = $this->ip;
        $uInfo['login_time'] = time();

        $this->userModel->editUserInfo($uInfo, ['uid' => $this->uid]);

        $userInfo = $this->userModel->getUserInfo($this->uid, 1);

        if (!$userInfo['photo']) {
            $userInfo['photo'] = site_url() . 'static/image/user_large.png';
        }
        // if ($userInfo['is_audit'] == 0) {
        //     return ['code' => 11, 'msg' => '账号未审核，请联系管理员。微信：huazhiyeluo'];
        // }

        return ['code' => 0, 'data' => $userInfo , 'msg' => '登录成功'];
    }

}
