<?php

namespace App\Controllers;

use App\Models\ArticleModel;
use App\Models\BookModel;
use App\Models\CategoryModel;
use App\Models\ChapterModel;

class Sitemap extends BaseController
{

    //---------------------------------------查询-----------------------------------------

    //入口文件
    public function index()
    {
        $newCategorys = [];
        foreach ([2, 3, 4, 1] as $type) {
            $tempAllCategorys = $this->getCategorysByType($type);
            foreach ($tempAllCategorys['pCategorys'] as $val) {
                $newCategorys[$type][$val['cid']] = $val;
                foreach ($tempAllCategorys['cCategorys'] as $cinfo) {
                    if ($cinfo['pcid'] == $val['cid']) {
                        $newCategorys[$type][$val['cid']]['child'][] = $cinfo;
                    }
                }
            }
        }

        $data['newCategorys'] = $newCategorys;
        $data['controller'] = 'index';

        $this->data['seo'] = shareSeo('站点地图');

        $this->data = array_merge($data, $this->data);
        return view('sitemap/index', $this->data);
    }

    //sitemap创建
    public function createSiteMap()
    {
        $this->createSiteMapNav();
        $this->createSiteMapArticle();
        $this->createSiteMapBook();
        $this->createSiteMapChapter();
    }

    private function createSiteMapNav()
    {
        $html = '';
        $configProject = config('Project');
        $baseConfig = $configProject->baseConfig;
        $weburl = $baseConfig['weburl'];
        foreach ([2, 3, 4] as $type) {
            $tempAllCategorys = $this->getCategorysByType($type);
            $allCategorys = getRelationCategorys($tempAllCategorys);
            $date = date("Y-m-d");

            $html .= '<?xml version="1.0" encoding="utf-8"?>';
            $html .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:mobile="http://www.baidu.com/schemas/sitemap-mobile/1/">';
            foreach ($allCategorys as $v) {
                $url = sprintf("%s/%s.html", $weburl, $v['route_name']);
                $html .= '<url>';
                $html .= '<loc>' . $url . '</loc>';
                $html .= '<mobile:mobile type="pc,mobile"/>';
                $html .= '<lastmod>' . $date . '</lastmod>';
                $html .= '<changefreq>daily</changefreq>';
                $html .= '<priority>0.8</priority>';
                $html .= '</url>';
            }
            $html .= '</urlset>';
        }

        file_put_contents(FCPATH . 'sitemap/nav.xml', $html);
    }

    private function createSiteMapArticle()
    {
        $configProject = config('Project');
        $baseConfig = $configProject->baseConfig;
        $weburl = $baseConfig['weburl'];
        $articleModel = new ArticleModel();
        foreach ([2, 3, 4] as $type) {
            $tempAllCategorys = $this->getCategorysByType($type);
            for ($i = 1; $i <= 35; $i++) {
                $pageSize = 5000;
                $page = $i;
                $html = '';
                $html .= '<?xml version="1.0" encoding="utf-8"?>';
                $html .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:mobile="http://www.baidu.com/schemas/sitemap-mobile/1/">';

                $articles = $articleModel->getArticles($type, ['order' => ['article_id' => 'asc']], 'article_id,cid1,cid2,addtime', $page, $pageSize);
                $articles = getInitList($articles, $tempAllCategorys, $type);

                foreach ($articles as $v) {
                    $url = sprintf("%s/%s/%d.html", $weburl, $v['route_info']['route_name'], $v['article_id']);
                    $html .= '<url>';
                    $html .= '<loc>' . $url . '</loc>';
                    $html .= '<mobile:mobile type="pc,mobile"/>';
                    $html .= '<lastmod>' . date("Y-m-d", $v['addtime']) . '</lastmod>';
                    $html .= '<changefreq>daily</changefreq>';
                    $html .= '<priority>0.8</priority>';
                    $html .= '</url>';
                }
                $html .= '</urlset>';

                file_put_contents(FCPATH . "sitemap/" . getFirstRoutename($type) . $i . '.xml', $html);
            }
        }
    }

    private function createSiteMapBook()
    {
        $configProject = config('Project');
        $baseConfig = $configProject->baseConfig;
        $weburl = $baseConfig['weburl'];
        $tempAllCategorys = $this->getCategorysByType(1);
        $bookModel = new BookModel();
        for ($i = 1; $i <= 10; $i++) {

            

            $pageSize = 5000;
            $page = $i;

            $html = '';
            $html .= '<?xml version="1.0" encoding="utf-8"?>';
            $html .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:mobile="http://www.baidu.com/schemas/sitemap-mobile/1/">';

            $articles = $bookModel->getBooks(['order' => ['book_id' => 'asc']], 'book_id,cid1,cid2,addtime', $page, $pageSize);
            $articles = getInitList($articles, $tempAllCategorys, 1);

            foreach ($articles as $v) {
                $url = sprintf("%s/%s/%d.html", $weburl, $v['route_info']['route_name'], $v['book_id']);
                $html .= '<url>';
                $html .= '<loc>' . $url . '</loc>';
                $html .= '<mobile:mobile type="pc,mobile"/>';
                $html .= '<lastmod>' . date("Y-m-d", $v['addtime']) . '</lastmod>';
                $html .= '<changefreq>daily</changefreq>';
                $html .= '<priority>0.8</priority>';
                $html .= '</url>';
            }
            $html .= '</urlset>';

            file_put_contents(FCPATH . "sitemap/" . getFirstRoutename(1) . $i . '.xml', $html);
        }
    }

    private function createSiteMapChapter()
    {
        $configProject = config('Project');
        $baseConfig = $configProject->baseConfig;
        $weburl = $baseConfig['weburl'];
        $tempAllCategorys = $this->getCategorysByType(1);
        $chapterModel = new ChapterModel();
        $bookModel = new BookModel();
        for ($i = 1; $i <= 10; $i++) {
            $pageSize = 5000;
            $page = $i;

            $html = '';
            $html .= '<?xml version="1.0" encoding="utf-8"?>';
            $html .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:mobile="http://www.baidu.com/schemas/sitemap-mobile/1/">';

            $articles = $chapterModel->getChapters(['order' => ['book_id' => 'asc']], 'book_id,chapter_id,addtime', $page, $pageSize);
            $book_ids = array_unique(array_column($articles, 'book_id'));
            if ($book_ids) {
                $newChaptersBooks = $bookModel->getBooks(['whereIn' => ['book_id' => $book_ids], 'order' => ['book_id' => 'DESC']], 'book_id,title,cid1,cid2,author', 1, count($book_ids));
                $articles = getInitChapter($articles, $newChaptersBooks, $tempAllCategorys);
                foreach ($articles as $v) {
                    $url = sprintf("%s/%s/%d_%d.html", $weburl, $v['route_info']['route_name'], $v['book_id'],$v['chapter_id']);
                    $html .= '<url>';
                    $html .= '<loc>' . $url . '</loc>';
                    $html .= '<mobile:mobile type="pc,mobile"/>';
                    $html .= '<lastmod>' . date("Y-m-d", $v['addtime']) . '</lastmod>';
                    $html .= '<changefreq>daily</changefreq>';
                    $html .= '<priority>0.8</priority>';
                    $html .= '</url>';
                }
            }
            $html .= '</urlset>';

            file_put_contents(FCPATH . "sitemap/" . getFirstRoutename(1) . '_chapter' . $i . '.xml', $html);
        }
    }


    //提交百度主动推送
    public function toBaiduSiteDay()
    {
        $this->toBaiduSiteDayArticle();
    }

    /***************************************************************提交百度主动推送****************************************************/
    private function toBaiduSiteDayArticle()
    {
        $articleModel = new ArticleModel();
        $configProject = config('Project');
        $weburl = $configProject->weburl;
        $articleModel = new ArticleModel();
        foreach ([2, 3, 4] as $type) {
            $tempAllCategorys = $this->getCategorysByType($type);
            for ($i = 3; $i <= 15; $i++) {
                $pageSize = 1000;
                $page = $i;

                $articles = $articleModel->getArticlesList($type, ['order' => ['article_id' => 'asc']], 'article_id,cid1,cid2', $page, $pageSize);
                $articles['list'] = getInitList($articles['list'], $tempAllCategorys, $type);

                $urls = [];
                foreach ($articles['list'] as $k => $v) {
                    $urls[] = sprintf("%s/%s/%d.html", $weburl, $v['route_info']['route_name'], $v['article_id']);;
                }
                if ($urls) {
                    $api = 'http://data.zz.baidu.com/urls?site=https://www.guiaihai.com&token=BhIpDsRH9UiJ7AaP';
                    $ch = curl_init();
                    $options = array(
                        CURLOPT_URL => $api,
                        CURLOPT_POST => true,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_POSTFIELDS => implode("\n", $urls),
                        CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
                    );
                    curl_setopt_array($ch, $options);
                    $result = curl_exec($ch);
                    echo $result;
                }
                exit;
            }
        }
    }
}
