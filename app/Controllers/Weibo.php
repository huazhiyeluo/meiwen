<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\WeiboModel;

class Weibo extends BaseController {

    //文章入口
    public function index($page = 1) {
        $pageSize = 15;
        $page     = empty($page) ? 1 : $page;

        $weiboModel = new WeiboModel();
        $userModel  = new UserModel();

        $weibos = $weiboModel->getWeibosList(['where' => ['is_audit' => 1], 'order' => ['weibo_id' => 'DESC']], '*', $page, $pageSize);
        foreach ($weibos['list'] as $k => &$v) {
            $userInfo      = $userModel->getUserInfo($v['uid']);
            $v['addtime']  = time_tran($v['addtime']);
            $v['content']  = str_replace("\n", "<br />", $v['content']);
            $v['username'] = $userInfo['username'];
            $v['photo']    = $userInfo['photo'];
        }

        $mypaper      = new \App\ThirdParty\MyPaper($page, $pageSize, $weibos['total']);
        $data['page'] = $mypaper->createLinks('weibo', '/list_');
        $data['list'] = $weibos['list'];

        $hotweibos = $weiboModel->getWeibos(['where' => ['is_audit' => 1], 'order' => ['count_comment' => 'DESC']], '*', 1, 30);
        foreach ($hotweibos as $k => &$v) {
            $userInfo      = $userModel->getUserInfo($v['uid']);
            $v['addtime']  = time_tran($v['addtime']);
            $v['content']  = str_replace("\n", "<br />", $v['content']);
            $v['username'] = $userInfo['username'];
            $v['photo']    = $userInfo['photo'];
        }

        $data['hotweibos'] = $hotweibos;

        $this->data['controller'] = 'weibo';

        $this->data['seo'] = shareSeo('树洞_树洞_秘密,在这里看八卦闲聊、分享秘密', '树洞,树洞网,秘密,爱嗨网', '树洞是一个基于情感倾诉、烦恼咨询的多应用匿名社区网站,在这里，我们为您寄存秘密、心事。拨动你的心弦，倾听来自心海的回音！');

        $this->data = array_merge($data, $this->data);

        return view('weibo/index', $this->data);
    }

    //文章入口
    public function detail($weibo_id, $page = 1) {
        $pageSize = 15;
        $page     = empty($page) ? 1 : $page;

        $weiboModel = new WeiboModel();
        $userModel  = new UserModel();

        $weibo            = $weiboModel->getWeibo(['where' => ['weibo_id' => $weibo_id]]);
        $weibo['info']    = getInfo($weibo['content'], 50);
        $weibo['content'] = str_replace("\n", "<br />", $weibo['content']);
        $data['weibo']    = $weibo;

        $tuserInfo          = $userModel->getUserInfo($weibo['uid']);
        $data['tuserInfo'] = $tuserInfo;

        $weiboComments = $weiboModel->getWeiboCommentsList(['where' => ['weibo_id' => $weibo_id], 'order' => ['weibo_id' => 'DESC']], '*', $page, $pageSize);
        foreach ($weiboComments['list'] as $k => &$v) {
            $userInfo      = $userModel->getUserInfo($v['uid']);
            $v['addtime']  = time_tran($v['addtime']);
            $v['content']  = str_replace("\n", "<br />", $v['content']);
            $v['username'] = $userInfo['username'];
            $v['photo']    = $userInfo['photo'];
        }

        $mypaper               = new \App\ThirdParty\MyPaper($page, $pageSize, $weiboComments['total']);
        $data['page']          = $mypaper->createLinks('weibo/' . $weibo_id, '/_');
        $data['weiboComments'] = $weiboComments['list'];

        $otherweibos = $weiboModel->getWeibos(['where' => ['is_audit' => 1, 'uid' => $weibo['uid'], 'weibo_id <>' => $weibo_id], 'order' => ['weibo_id' => 'desc']], '*', 1, 20);
        foreach ($otherweibos as $k => &$v) {
            $v['content'] = getInfo($v['content'], 50);
        }
        $data['otherweibos'] = $otherweibos;


        if (!empty($this->data['userInfo'])){
            $this->data['follow'] = $userModel->getUserFollow(['uid'=>$this->data['userInfo']['uid'],'uid_follow'=>$weibo['uid']]);
        }else{
            $this->data['follow'] = [];
        }
        

        $this->data['controller'] = 'weibo';

        $this->data['seo'] = shareSeo(getInfo($weibo['content'], 25), '树洞,树洞网,秘密,爱嗨网', getInfo($weibo['content']), $tuserInfo['photo']);


        $this->data = array_merge($data, $this->data);

        return view('weibo/detail', $this->data);
    }

    //发树洞
    public function createdo() {
        $weiboModel = new WeiboModel();

        $params   = $this->request->getPost();
        $userInfo = $this->data['userInfo'];
        if (!$userInfo) {
            die(nologin());
        }
        $uid = $userInfo['uid'];

        $data            = [];
        $data['uid']     = (int) $uid;
        $data['content'] = $params['content'];
        $data['addtime'] = time();
        $data['uptime']  = time();

        $weiboid = $weiboModel->addWeibo($data);
        if (!$weiboid) {
            die(json_encode(array('code' => 5, 'msg' => '发表树洞失败')));
        }

        die(json_encode(array('code' => 0, 'msg' => '发表树洞成功')));
    }

    //发树洞评论
    public function replydo() {
        $weiboModel = new WeiboModel();

        $params   = $this->request->getPost();
        $userInfo = $this->data['userInfo'];
        if (!$userInfo) {
            die(nologin());
        }
        $uid = $userInfo['uid'];

        $weibo_id = $params['weibo_id'];
        $content  = $params['content'];

        $weiboInfo = $weiboModel->getWeibo(['where' => ['weibo_id' => $weibo_id]]);
        $weiboModel->editWeibo(['count_comment' => $weiboInfo['count_comment'] + 1, 'uptime' => time()], ['weibo_id' => $weibo_id]);

        $data             = [];
        $data['uid']      = (int) $uid;
        $data['weibo_id'] = $weibo_id;
        $data['touid']    = $weiboInfo['uid'];
        $data['content']  = $content;
        $data['addtime']  = time();

        $commentid = $weiboModel->addWeiboComment($data);
        if (!$commentid) {
            die(json_encode(array('code' => 5, 'msg' => '评论树洞失败')));
        }

        die(json_encode(array('code' => 0, 'msg' => '评论树洞成功')));
    }

    //删除树洞
    public function del() {
        $weiboModel = new WeiboModel();

        $params   = $this->request->getPost();
        $userInfo = $this->data['userInfo'];
        if (!$userInfo) {
            die(nologin());
        }
        $uid = $userInfo['uid'];

        $weibo_id = $params['weibo_id'];

        $rs = $weiboModel->delWeibo(['weibo_id' => $weibo_id]);
        if (!$rs) {
            die(json_encode(array('code' => 1, 'msg' => '树洞删除失败')));
        }
        $weiboModel->delWeiboComment(['weibo_id' => $weibo_id]);
        die(json_encode(array('code' => 0, 'msg' => '树洞删除成功')));
    }

}
