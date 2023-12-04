<?php namespace App\Models\Login;

class LoginWeiboModel extends LoginModel {

    //appId
    private $appId = '';
    //appKey
    private $appKey = '';
    //code 认证后返回的安全码
    private $code;
    //返回url
    private $redirectUri = '';
    //授权列表 多个使用逗号隔开
    private $scope = 'email';
    //获取Access Token
    public $access_token;
    //openid
    public $openid;

    //登录状态
    public $loginState = 'LoginweiboState';

    public $sid = 2;

    public function __construct() {
        parent::__construct();
        $configProject     = config('Project');
        $config            = $configProject->thirdLoginConfig['weibo'];
        $this->appId       = $config['appId'];
        $this->appKey      = $config['appKey'];
        $this->redirectUri = $config['returnUrl'];
    }

    public function login($display) {

        //-------生成唯一随机串防CSRF攻击
        $loginState = md5(uniqid());

        session()->set($this->loginState, $loginState);

        $url                  = 'https://open.weibo.cn/oauth2/authorize';
        $data                 = array();
        $data['client_id']    = $this->appId;
        $data['redirect_uri'] = $this->redirectUri;
        $data['scope']        = $this->scope;
        $data['state']        = $loginState;
        $data['display']      = $display;
        $url                  = combineURL($url, $data);
        header('location:' . $url);
        exit();
    }

    protected function checkBack($params) {
        if (empty($params['state'])) {
            $this->errRet = ['code' => 1, 'msg' => '登录信息错误，请返回重试！'];
        }
        if (empty($params['code'])) {
            $this->errRet = ['code' => 2, 'msg' => '登录信息错误，请返回重试！'];
        }

        $loginState = session()->get($this->loginState);
        if ($loginState != $params['state']) {
            $this->errRet = ['code' => 3, 'msg' => '用户信息获取失败，请返回重试！'];
        }
        session()->remove($this->loginState);
        $this->code = $params['code'];
    }

    //通过Authorization Code获取Access Token
    public function getAccessToken() {

        $url                   = 'https://api.weibo.com/oauth2/access_token';
        $data                  = array();
        $data['grant_type']    = 'authorization_code';
        $data['client_id']     = $this->appId;
        $data['client_secret'] = $this->appKey;
        $data['code']          = $this->code;
        $data['redirect_uri']  = $this->redirectUri;
        $url                   = combineURL($url, $data);
        $res                   = $this->client->request('post', $url, $data);
        $res                   = $res->getBody();
        if (is_integer(strpos($res, 'access_token'))) {
            $ret                = json_decode($res, true);
            $this->access_token = $ret['access_token'];
            $this->openid       = $ret['uid'];
        } else {
            $this->errRet = ['code' => 4, 'msg' => '获取 AccessToken 失败，请返回重试'];
        }
    }

    //通过Authorization Code获取getOpenId
    public function getOpenId() {
        //获取token
        $this->getAccessToken();
    }

    //获取用户信息
    public function getUserInfo() {
        $this->getOpenId();
        $url                  = 'https://api.weibo.com/2/users/show.json';
        $data                 = array();
        $data['uid']          = $this->openid;
        $data['access_token'] = $this->access_token;
        $url                  = combineURL($url, $data);
        $res                  = $this->client->request('get', $url);
        $res                  = $res->getBody();
        if (is_integer(strpos($res, 'name'))) {
            $user = json_decode($res, true);
        } else {
            $this->errRet = ['code' => 5, 'msg' => '获取openid 失败，请返回重试'];
        }
        $this->username = $user['name'];
        $this->photo    = $user['profile_image_url'];
    }
}
