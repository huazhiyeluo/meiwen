<?php

namespace App\Controllers;

use App\Models\ArticleModel;
use App\Models\BookModel;
use App\Models\LinkModel;

class Index extends BaseController
{

    //入口文件
    public function index()
    {
        $cacheKey = 'cache_index';
        $cache = \Config\Services::cache();
        if (ENVIRONMENT == 'production') {
            $data = $cache->get($cacheKey);
            if (!empty($data)) {
                $data['userInfo'] = $this->data['userInfo'];
                $data['weixin'] = $this->data['weixin'];
                return view('index/index', $data);
            }
        }
        $articleModel = new ArticleModel();
        $bookModel = new BookModel();
        $linkModel = new LinkModel();

        $allCategorys = ['cCategorys' => [], 'pCategorys' => []];

        $showType = array_column($this->data['showType'], 'type');

        //2、推荐书籍
        if (in_array(1, $showType)) {
            $allCategorys = $this->getCategorysByType(1);
            $recBooks = $bookModel->getBooks(['where' => [], 'order' => ['book_id' => 'DESC']], 'book_id,title,cid1,cid2,author,cover,desc', 1, 4);
            $this->data['recBooks'] = getInitList($recBooks, $allCategorys, 1);
        }

        //3、分类文章
        $this->data['list'] = [];
        foreach ([2, 3, 4] as $type) {
            if (!in_array($type, $showType)) {
                continue;
            }

            $tempAllCategorys = $this->getCategorysByType($type);
            $allCategorys['cCategorys'] = array_merge($allCategorys['cCategorys'], $tempAllCategorys['cCategorys']);
            $allCategorys['pCategorys'] = array_merge($allCategorys['pCategorys'], $tempAllCategorys['pCategorys']);
            $tempCategorys = getRelationCategorys($tempAllCategorys, 1, 0);
            $categorys = array_slice($tempCategorys, 0, 4);
            if ($categorys) {
                foreach ($categorys as $v) {
                    $articles = $articleModel->getArticles($type, ['where' => ['is_audit' => 1, 'cid1' => $v['cid']], 'order' => ['article_id' => 'DESC']], 'article_id,cid1,cid2,title', 1, 18);
                    $this->data['list'][$v['cid']]['articles'] = getInitList($articles, $tempAllCategorys, $type);
                    $this->data['list'][$v['cid']]['category'] = $v;
                }
            }
            if ($type == 2) {
                $articles = $articleModel->getArticles($type, ['where' => ['cover <>' => ''], 'order' => ['article_id' => 'DESC']], 'article_id,cover,cid1,cid2,title', 1, 5);
                $this->data['coverArticles'] = getInitList($articles, $tempAllCategorys, $type);
            }

            //4、最新文章
            $new = $articleModel->getArticles($type, ['where' => ['is_audit' => 1], 'order' => ['article_id' => 'DESC']], 'article_id,cid1,cid2,title', 1, 22);
            $this->data['newArticles'][$type] = getInitList($new, $tempAllCategorys, $type);

            //5、热门文章
            $hot = $articleModel->getArticles($type, ['where' => ['is_audit' => 1], 'order' => ['count_view' => 'DESC']], 'article_id,cid1,cid2,title', 1, 22);
            $this->data['hotArticles'][$type] = getInitList($hot, $tempAllCategorys, $type);
        }

        $tempCategorys = getRelationCategorys($allCategorys, 0, 0);
        //6、推荐导航
        $recCategorys = array_slice($tempCategorys, 6, 42);
        $this->data['recCategorys'] = $recCategorys;

        //7、热门导航
        $hotCategorys = array_slice($tempCategorys, 0, 6);
        $this->data['hotCategorys'] = $hotCategorys;

        //8、友情链接
        $time = time();
        $linkWhere = [];
        $linkWhere['start_time < '] = $time;
        $linkWhere['end_time > '] = $time;
        $friendchain = $linkModel->getLinks(['where' => $linkWhere, 'order' => ['sort' => 'ASC']], 'sitename,url,keywords', 1, 100);
        $this->data['friendchain'] = $friendchain;
        $this->data['controller'] = 'index';

        $cache->save($cacheKey, $this->data, 24 * 3600);

        return view('index/index', $this->data);
    }

    //info
    public function info($tag)
    {
        $this->data['controller'] = 'index';
        $this->data['tag'] = $tag;

        if ($tag == 'about') {
            $kwc = '关于我们';
        }

        if ($tag == 'contact') {
            $kwc = '联系我们';
        }

        if ($tag == 'terms') {
            $kwc = '用户条款';
        }

        if ($tag == 'publish') {
            $kwc = '投稿指南';
        }

        if ($tag == 'disclaimer') {
            $kwc = '免责申明';
        }
        $this->data['seo'] = shareSeo($kwc);

        return view('index/info', $this->data);
    }
}
