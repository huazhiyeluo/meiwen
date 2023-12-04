<?php

namespace App\Controllers;

class Login extends BaseController
{

    //---------------------------------------登录-----------------------------------------

    //入口文件
    public function login()
    {
        $userInfo = $this->data['userInfo'];
        if ($userInfo) {
            return redirect()->to(site_url());
        }

        $data['controller'] = 'index';

        $this->data['seo'] = shareSeo('登录');
        $this->data = array_merge($data, $this->data);

        nologinset();

        return view('user/login', $this->data);
    }

    public function loginDoWeb()
    {
        $params = $this->request->getPost();

        $account = isset($params['account']) ? $params['account'] : '';
        $password = isset($params['password']) ? $params['password'] : '';
        $email = '';
        $phone = '';

        $pemail = "/^\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14}$/";
        $pphone = "/^0?(13|14|15|17|18|19)[0-9]{9}$/";
        if (preg_match($pemail, $account)) {
            $email = $account;
        }

        if (preg_match($pphone, $account)) {
            $phone = $account;
        }

        if (!$email && !$phone) {
            die(json_encode(array('code' => 1, 'msg' => '账号格式输入错误!')));
        }

        $ppassword = "/^[A-Za-z0-9_]{6,12}$/";
        if (!preg_match($ppassword, $password)) {
            die(json_encode(array('code' => 2, 'msg' => '密码只能包含A-Za-z0-9_,且6至12位长度!')));
        }

        $className = "\App\Models\Login\LoginWebModel";
        $obj = new $className;

        $params = [];
        $params['email'] = $email;
        $params['phone'] = $phone;
        $params['password'] = $password;
        $params['username'] = '';
        $params['photo'] = '';
        $params['ip'] = $this->request->getIPAddress();

        $ret = $obj->loginInit($params);
        if ($ret['code']) {
            die(json_encode($ret));
        }
        $prev_url = tologinurl();
        die(json_encode(['code' => 0, 'msg' => '登录成功', 'prev_url' => $prev_url]));
    }

    public function loginReturn($platform)
    {
        $params = $this->request->getGet();
        $platform = ucfirst($platform);
        $className = "\App\Models\Login\Login{$platform}Model";
        $obj = new $className;

        $params['ip'] = $this->request->getIPAddress();

        $prev_url = session()->get('prev_url');

        $ret = $obj->loginInit($params);
        if ($ret['code']) {
            $data['controller'] = 'article';
            $data['button'] = '返回上一页';
            $data['msg'] = $ret['msg'];
            $data['url'] = $prev_url;
            $this->data = array_merge($data, $this->data);
            return view('tip/index', $this->data);
        }
        return redirect()->to($prev_url);
    }

    public function loginDo($platform)
    {
        $platform = ucfirst($platform);
        $className = "\App\Models\Login\Login{$platform}Model";
        $obj = new $className;

        if ($this->data['edittype'] == 'mobile') {
            $display = 'mobile';
        } else {
            $display = 'default';
        }

        $obj->login($display);

    }

    //---------------------------------------退出登录-----------------------------------------

    public function logout() {
        session()->remove('userInfo');
        return redirect()->to(site_url());
    }


}
