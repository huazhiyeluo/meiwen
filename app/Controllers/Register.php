<?php

namespace App\Controllers;

use App\Models\UserModel;

class Register extends BaseController
{
    //---------------------------------------注册-----------------------------------------
    //入口文件
    public function register()
    {
        $userInfo = $this->data['userInfo'];
        if ($userInfo) {
            return redirect()->to(site_url());
        }

        $data['controller'] = 'article';
        $this->data['seo'] = shareSeo('注册');
        $this->data = array_merge($data, $this->data);

        nologinset();

        return view('user/register', $this->data);
    }

    public function registerDo($type)
    {
        $userModel = new UserModel();

        $params = $this->request->getPost();

        $email = isset($params['email']) ? $params['email'] : '';
        $phone = isset($params['phone']) ? $params['phone'] : '';
        $password = $params['password'];
        $repassword = $params['repassword'];
        $username = $params['username'];

        if ($type == 1) {
            $pemail = "/^\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14}$/";
            if (!preg_match($pemail, $email)) {
                die(json_encode(array('code' => 1, 'msg' => '邮箱格式错误!')));
            }
        } else {
            $pphone = "/^0?(13|14|15|17|18|19)[0-9]{9}$/";
            if (!preg_match($pphone, $phone)) {
                die(json_encode(array('code' => 1, 'msg' => '手机号码输入错误!')));
            }
        }

        $ppassword = "/^[A-Za-z0-9_]{6,12}$/";
        if (!preg_match($ppassword, $password)) {
            die(json_encode(array('code' => 2, 'msg' => '密码只能包含A-Za-z0-9_,且6至12位长度!')));
        }
        if ($password !== $repassword) {
            die(json_encode(array('code' => 3, 'msg' => '重复密码必须和密码一致!')));
        }
        $pusername = "/^[A-Za-z0-9_\x{4e00}-\x{9fa5}]{2,12}$/u";
        if (!preg_match($pusername, $username)) {
            die(json_encode(array('code' => 4, 'msg' => '用户名只能包含A-Za-z0-9_及汉字,且2至12位长度！')));
        }

        if ($type == 1) {
            $res = $userModel->getUserOne(['email' => $email]);
            if ($res) {
                die(json_encode(array('code' => 5, 'msg' => '邮箱已经被注册')));
            }
        } else {
            $res = $userModel->getUserOne(['phone' => $phone]);
            if ($res) {
                die(json_encode(array('code' => 5, 'msg' => '手机号已经被注册')));
            }
        }

        //ts_user
        $salt = md5(rand());
        $data = [];
        $data['email'] = $email ? $email : '';
        $data['phone'] = $phone ? $phone : '';
        $data['password'] = md5($salt . $password);
        $data['salt'] = $salt;
        $data['openid'] = genGUID('');
        $userModel->addUser($data);

        $className = "\App\Models\Login\LoginWebModel";
        $obj = new $className;

        $params = [];
        $params['email'] = $data['email'];
        $params['phone'] = $data['phone'];
        $params['password'] = $password;
        $params['username'] = $username;
        $params['photo'] = '';
        $params['ip'] = $this->request->getIPAddress();

        $ret = $obj->loginInit($params);
        if ($ret['code']) {
            $userModel->delUser(['openid' => $data['openid']]);
            die(json_encode($ret));
        }
        $prev_url = tologinurl();
        die(json_encode(['code' => 0, 'msg' => '注册成功', 'prev_url' => $prev_url]));
    }

}
