<?php

namespace App\Controllers;

use App\Models\ArticleModel;
use App\Models\UserModel;

class Zuowen extends BaseController
{
    //文章入口
    public function index($page)
    {
        $type = 4;
        $allCategorys = $this->getCategorysByType($type);

        //取得父导航、子导航的并集
        $tempCategorys = getRelationCategorys($allCategorys, 0);
        $relationCategorys = array_slice($tempCategorys, 0, 8);

        $firstBreadcrumb = getFirstBreadcrumb($type);
        $route_name = getFirstRoutename($type);

        $this->data['type'] = $type;
        $this->data['breadcrumb'] = [$firstBreadcrumb];
        $this->data['route_name'] = $route_name;
        $this->data['controller'] = $route_name;

        $this->data['seo'] = getNavTDS($this->data['seo'], $tempCategorys, $type, 0);

        $nav = getFirstNav($type);
        array_unshift($relationCategorys, $nav);
        $this->data['relationCategorys'] = $relationCategorys;

        $data = $this->showIndex($allCategorys, ['route_name' => $route_name, 'type' => $type], $page);
        $this->data = array_merge($data, $this->data);

        return view('zuowen/index', $this->data);
    }

    //文章列表页
    public function showIndex($allCategorys, $category, $page = 1, $cid1 = 0, $cid2 = 0)
    {
        $route_name = $category['route_name'];
        $type = $category['type'];

        $cacheKey = sprintf('cache_list_%d_%d_%d_%d',$type,$page, $cid1, $cid2);
        $cache = \Config\Services::cache();
        if (ENVIRONMENT == 'production' && $page <= 3) {
            $data = $cache->get($cacheKey);
            if (!empty($data)) {
                return $data;
            }
        }

        $pageSize = 15;
        $page = empty($page) ? 1 : $page;

        $articleModel = new ArticleModel();

        $data = [];
        $where = ['is_audit' => 1];
        if ($cid2) {
            $where['cid2'] = $cid2;
        } elseif ($cid1) {
            $where['cid1'] = $cid1;
        }
        $articles = $articleModel->getArticlesList($type, ['where' => $where, 'order' => ['article_id' => 'DESC']], 'article_id,uid,cid1,cid2,title,cover,addtime,is_mul_page,count_comment,count_view', $page, $pageSize);

        $articles['list'] = $articleModel->getContentAll($type, $articles['list']);

        $articles['list'] = getInitList($articles['list'], $allCategorys, $type);

        $mypaper = new \App\ThirdParty\MyPaper($page, $pageSize, $articles['total']);

        $data['page'] = $mypaper->createLinks($route_name, '/list_');

        $data['list'] = $articles['list'];

        //4、最新文章
        $newMeiwens = $articleModel->getArticles($type, ['where' => $where, 'order' => ['article_id' => 'DESC']], 'article_id,cid1,cid2,title', 1, 18);
        $data['newArticles'] = getInitList($newMeiwens, $allCategorys, $type);

        //5、热门文章
        $hotMeiwens = $articleModel->getArticles($type, ['where' => $where, 'order' => ['count_view' => 'DESC']], 'article_id,cid1,cid2,title', 1, 18);
        $data['hotArticles'] = getInitList($hotMeiwens, $allCategorys, $type);

        //6、热门导航
        $tempCategorys = getRelationCategorys($allCategorys, 2, $cid1);
        $hotCategorys = array_slice($tempCategorys, 0, 6);
        $data['hotCategorys'] = $hotCategorys;

        $cache->save($cacheKey, $data, 24 * 3600);

        return $data;
    }

    //文章详情
    public function showDetail($allCategorys, $category, $article_id, $page = 1)
    {
        if (empty($page)) {
            $page = 1;
        }
        $route_name = $category['route_name'];
        $type = $category['type'];

        $data = [];
        $articleModel = new ArticleModel();
        $userModel = new userModel();
        $article = $articleModel->getArticle($type, ['where' => ['article_id' => $article_id]]);

        $articleModel->editArticle($type, ['count_view' => $article['count_view'] + 1], ['article_id' => $article_id]);

        $data['page'] = '';
        if ($article['is_mul_page'] == 0) {
            $articleContent = $articleModel->getArticleContent($type, $article_id);
        } else {
            $total = $articleModel->getArticleContentMulCount($type, $article_id);
            $articleContent = $articleModel->getArticleContentMul($type, $article_id, $page);

            $mypaper = new \App\ThirdParty\MyPaper($page, 1, $total);
            $route_name = $route_name . '/' . $article_id;
            $data['page'] = $mypaper->createLinks($route_name, '_');
        }
        $userInfo = $userModel->getUserInfo($article['uid']);
        $article['count_view'] = $article['count_view'] + 1;
        $article['content'] = $articleContent['content'];
        $article['info'] = getInfo($article['content']);
        $article['username'] = $userInfo['username'];
        $article['photo'] = $userInfo['photo'];

        $data['article'] = $article;

        $cid1 = $article['cid1'];
        $cid2 = $article['cid2'];
        $pcategory = $allCategorys['pCategorys'][$cid1];

        $where = ['is_audit' => 1];
        $firstBreadcrumb = getFirstBreadcrumb($type);
        if ($cid2) {
            $breadcrumb = [$firstBreadcrumb, ['url' => my_site_url($pcategory['route_name']), 'title' => $pcategory['title']], ['url' => my_site_url($category['route_name']), 'title' => $category['title']], ['url' => my_site_url($category['route_name'], $article_id), 'title' => $article['title'], 'cl' => 'ts-hide']];
            $where['cid2'] = $cid2;
        } elseif ($cid1) {
            $breadcrumb = [$firstBreadcrumb, ['url' => my_site_url($category['route_name']), 'title' => $category['title']], ['url' => my_site_url($pcategory['route_name'], $article_id), 'title' => $article['title'], 'cl' => 'ts-hide']];
            $where['cid1'] = $cid1;
        }

        //4、最新文章
        $newMeiwens = $articleModel->getArticles($type, ['where' => $where, 'order' => ['article_id' => 'DESC']], 'article_id,cid1,cid2,title', 1, 18);
        $data['newArticles'] = getInitList($newMeiwens, $allCategorys, $type);

        //5、热门文章
        $hotMeiwens = $articleModel->getArticles($type, ['where' => $where, 'order' => ['count_view' => 'DESC']], 'article_id,cid1,cid2,title', 1, 18);
        $data['hotArticles'] = getInitList($hotMeiwens, $allCategorys, $type);

        //7、热门导航
        $tempCategorys = getRelationCategorys($allCategorys, 2, $cid1);
        $hotCategorys = array_slice($tempCategorys, 0, 6);
        $data['hotCategorys'] = $hotCategorys;

        //8、标中选中栏
        $data['breadcrumb'] = $breadcrumb;
        $keywords = implode(',', array_column($hotCategorys, 'title'));
        $data['seo'] = shareSeo($article['title'], $keywords, $article['info']);

        return $data;
    }
}
