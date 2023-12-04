<?php

namespace App\Controllers;
use App\Models\ArticleModel;
use App\Models\UserModel;

class User extends BaseController {






    //---------------------------------------用户个人信息-----------------------------------------

    //用户个人信息
    public function detail($uid, $tag = 'base', $page = 1) {
        $articleModel = new ArticleModel();
        $userModel    = new UserModel();

        

        $data['controller'] = 'article';

        $data['tag'] = $tag;

        $tuserInfo = $userModel->getUserInfo($uid);

        $userInfo  = $this->data['userInfo'];

        $data['tuserInfo'] = $tuserInfo;

        if ($this->data['userInfo']) {
            $follow         = $userModel->getUserFollow(['uid' => $userInfo['uid'], 'uid_follow' => $tuserInfo['uid']]);
            $data['follow'] = $follow;
        }
        switch ($tag) {
        case 'meiwen':
            $allCategorys = $this->getCategorysByType(2);
            $pageSize = 10;
            $page     = empty($page) ? 1 : $page;
            $articles = $articleModel->getArticlesList(2, ['where' => ['uid' => $uid], 'order' => ['article_id' => 'desc']], 'article_id,uid,cid1,cid2,title,cover,addtime,is_mul_page,count_comment,count_view', $page, $pageSize);

            $articles['list'] = $articleModel->getContentAll(2, $articles['list']);
            $articles['list'] = getInitList($articles['list'], $allCategorys, 2);

            $mypaper = new \App\ThirdParty\MyPaper($page, $pageSize, $articles['total']);

            $data['list'] = $articles['list'];
            $data['page'] = $mypaper->createLinks('user/' . $uid . '/meiwen', '/list_');
            break;
        case 'gushi':
            $allCategorys = $this->getCategorysByType(3);
            $pageSize = 10;
            $page     = empty($page) ? 1 : $page;
            $articles = $articleModel->getArticlesList(3, ['where' => ['uid' => $uid], 'order' => ['article_id' => 'desc']], 'article_id,uid,cid1,cid2,title,cover,addtime,is_mul_page,count_comment,count_view', $page, $pageSize);

            $articles['list'] = $articleModel->getContentAll(3, $articles['list']);
            $articles['list'] = getInitList($articles['list'], $allCategorys, 3);

            $mypaper = new \App\ThirdParty\MyPaper($page, $pageSize, $articles['total']);

            $data['list'] = $articles['list'];
            $data['page'] = $mypaper->createLinks('user/' . $uid . '/gushi', '/list_');
            break;
        case 'zuowen':
            $allCategorys = $this->getCategorysByType(4);
            $pageSize = 10;
            $page     = empty($page) ? 1 : $page;
            $articles = $articleModel->getArticlesList(4, ['where' => ['uid' => $uid], 'order' => ['article_id' => 'desc']], 'article_id,uid,cid1,cid2,title,cover,addtime,is_mul_page,count_comment,count_view', $page, $pageSize);

            $articles['list'] = $articleModel->getContentAll(4, $articles['list']);
            $articles['list'] = getInitList($articles['list'], $allCategorys, 4);

            $mypaper = new \App\ThirdParty\MyPaper($page, $pageSize, $articles['total']);

            $data['list'] = $articles['list'];
            $data['page'] = $mypaper->createLinks('user/' . $uid . '/zuowen', '/list_');
            break;
        case 'guestbook':
            $usergbs = $userModel->getUserGbs(['where' => ['reid' => 0, 'touid' => $uid], 'order' => ['addtime' => 'desc']], '*', 1, 50);
            foreach ($usergbs as $k => &$v) {
                $userInfoTemp  = $userModel->getUserInfo($v['uid']);
                $v['username'] = $userInfoTemp['username'];
                $v['photo']    = $userInfoTemp['photo'];
                $v['addtime']  = time_tran($v['addtime']);

                $v['child'] = $userModel->getUserGbs(['where' => ['reid' => $v['id']]]);
            }
            $this->data['usergbs'] = $usergbs;
            break;
        case 'followed':
            $followeds = $userModel->getUserFollows(['where' => ['uid_follow' => $uid], 'order' => ['addtime' => 'desc']], '*', 1, 50);
            foreach ($followeds as $k => &$v) {
                $userInfoTemp  = $userModel->getUserInfo($v['uid']);
                $v['username'] = $userInfoTemp['username'];
                $v['photo']    = $userInfoTemp['photo'];
                $v['addtime']  = time_tran($v['addtime']);
            }
            $this->data['followeds'] = $followeds;
            break;
        case 'follow':
            $follows = $userModel->getUserFollows(['where' => ['uid' => $uid], 'order' => ['addtime' => 'desc']], '*', 1, 50);
            foreach ($follows as $k => &$v) {
                $userInfoTemp  = $userModel->getUserInfo($v['uid_follow']);
                $v['username'] = $userInfoTemp['username'];
                $v['photo']    = $userInfoTemp['photo'];
                $v['addtime']  = time_tran($v['addtime']);
            }
            $this->data['follows'] = $follows;
            break;
        }

        $this->data['seo'] = shareSeo('关于' . $tuserInfo['username'] . '空间', $tuserInfo['signed'], $tuserInfo['about'], $tuserInfo['photo']);

        $this->data = array_merge($data, $this->data);
        return view('user/detail', $this->data);
    }

    //用户个人信息Do
    public function detailsdo($tag = 'base', $opt = '') {
        $userModel = new UserModel();
        $params    = $this->request->getPost();
        $userInfo  = $this->data['userInfo'];
        if (!$userInfo) {
            die(nologin());
        }
        $uid = $userInfo['uid'];

        switch ($tag) {
        case 'guestbook':
            switch ($opt) {
            case 'add':
                $data            = [];
                $data['touid']   = $params['touid'];
                $data['content'] = $params['content'];
                $data['uid']     = $uid;
                $data['addtime'] = time();
                $res             = $userModel->addUserGb($data);
                if (!$res) {
                    die(json_encode(array('code' => 2, 'msg' => '留言失败')));
                }
                die(json_encode(array('code' => 0, 'msg' => '留言成功')));
                break;
            case 'reply':
                $data            = [];
                $data['reid']    = $params['reid'];
                $data['touid']   = $params['touid'];
                $data['content'] = $params['content'];
                $data['uid']     = $uid;
                $data['addtime'] = time();
                $res             = $userModel->addUserGb($data);
                if (!$res) {
                    die(json_encode(array('code' => 2, 'msg' => '回复失败')));
                }
                die(json_encode(array('code' => 0, 'msg' => '回复成功')));
                break;
            case 'del':
                $id  = $params['id'];
                $res = $userModel->delUserGb(['id' => $id]);
                if (!$res) {
                    die(json_encode(array('code' => 2, 'msg' => '删除失败')));
                }
                die(json_encode(array('code' => 0, 'msg' => '删除成功')));
                break;
            }
            break;
        case 'follow':
            $uid_follow         = $params['uid_follow'];
            $data               = [];
            $data['uid_follow'] = $uid_follow;
            $data['uid']        = $uid;
            $data['addtime']    = time();
            $res                = $userModel->addUserFollow($data);
            if (!$res) {
                die(json_encode(array('code' => 2, 'msg' => '关注失败')));
            }

            $userModel->editUserInfo(['count_follow' => $userInfo['count_follow'] + 1], ['uid' => $uid]);
            $tuserInfo = $userModel->getUserInfo($uid_follow);
            $userModel->editUserInfo(['count_followed' => $tuserInfo['count_followed'] + 1], ['uid' => $uid_follow]);

            die(json_encode(array('code' => 0, 'msg' => '关注成功')));
            break;
        case 'unfollow':
            $uid_follow = $params['uid_follow'];
            $res        = $userModel->delUserFollow(['uid' => $uid, 'uid_follow' => $uid_follow]);
            if (!$res) {
                die(json_encode(array('code' => 2, 'msg' => '取消关注失败')));
            }

            $userModel->editUserInfo(['count_follow' => $userInfo['count_follow'] - 1], ['uid' => $uid]);
            $tuserInfo = $userModel->getUserInfo($uid_follow);
            $userModel->editUserInfo(['count_followed' => $tuserInfo['count_followed'] - 1], ['uid' => $uid_follow]);

            die(json_encode(array('code' => 0, 'msg' => '取消关注成功')));
            break;
        }
    }
}
