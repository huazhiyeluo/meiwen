<?php

namespace App\Controllers;

use App\Models\BookModel;
use App\Models\ChapterModel;
use App\Models\UserModel;

class Book extends BaseController
{
    //入口文件
    public function index($page)
    {
        $type = 1;
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
        return view('book/index', $this->data);
    }

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

        $pageSize = 26;
        $page = empty($page) ? 1 : $page;

        $bookModel = new BookModel();

        $data = [];
        $where = [];
        if ($cid2) {
            $where['cid2'] = $cid2;
        } else if ($cid1) {
            $where['cid1'] = $cid1;
        }

        $books = $bookModel->getBooksList(['where' => $where, 'order' => ['book_id' => 'DESC']], 'book_id,cid1,cid2,title,cover,author,addtime,desc', $page, $pageSize);

        $books['list'] = getInitList($books['list'], $allCategorys, $type);

        $mypaper = new \App\ThirdParty\MyPaper($page, $pageSize, $books['total']);

        $data['page'] = $mypaper->createLinks($route_name, '/list_');

        $data['list'] = $books['list'];

        //4、最新文章
        $newArticles = $bookModel->getBooks(['where' => $where, 'order' => ['book_id' => 'DESC']], 'book_id,cid1,cid2,title,cover', 1, 10);
        $data['newArticles'] = getInitList($newArticles, $allCategorys, $type);

        //5、热门文章
        $hotArticles = $bookModel->getBooks(['where' => $where, 'order' => ['count_view' => 'DESC']], 'book_id,cid1,cid2,title,cover', 1, 10);
        $data['hotArticles'] = getInitList($hotArticles, $allCategorys, $type);

        //6、热门导航
        $tempCategorys = getRelationCategorys($allCategorys, 2, $cid1);
        $hotCategorys = array_slice($tempCategorys, 0, 6);
        $data['hotCategorys'] = $hotCategorys;

        $cache->save($cacheKey, $data, 24 * 3600);

        return $data;

    }

    public function showChapter($allCategorys, $category, $book_id)
    {
        $route_name = $category['route_name'];
        $type = $category['type'];

        $data = [];
        $bookModel = new BookModel();
        $chapterModel = new ChapterModel();
        $userModel = new userModel();
        $book = $bookModel->getBook(['where' => ['book_id' => $book_id]]);

        $bookModel->editBook(['count_view' => $book['count_view'] + 1], ['book_id' => $book_id]);

        $chapters = $chapterModel->getChapters(['where' => ['book_id' => $book_id], 'order' => ['chapter_id' => 'ASC']], 'chapter_id,title', 1, 1200);

        $userInfo = $userModel->getUserInfo($book['uid']);
        $book['count_view'] = $book['count_view'] + 1;
        $book['username'] = $userInfo['username'];
        $book['photo'] = $userInfo['photo'];
        $book['route_name'] = $route_name;

        $data['book'] = $book;
        $data['chapters'] = $chapters;

        $cid1 = $book['cid1'];
        $cid2 = $book['cid2'];
        $pcategory = $allCategorys['pCategorys'][$cid1];

        $where = [];
        $firstBreadcrumb = getFirstBreadcrumb($type);
        if ($cid2) {
            $breadcrumb = [$firstBreadcrumb, ['url' => my_site_url($pcategory['route_name']), 'title' => $pcategory['title']], ['url' => my_site_url($category['route_name']), 'title' => $category['title']], ['url' => my_site_url($category['route_name'], $book_id), 'title' => $book['title'], 'cl' => 'ts-hide']];
            $where['cid2'] = $cid2;
        } elseif ($cid1) {
            $breadcrumb = [$firstBreadcrumb, ['url' => my_site_url($category['route_name']), 'title' => $category['title']], ['url' => my_site_url($pcategory['route_name'], $article_id), 'title' => $article['title'], 'cl' => 'ts-hide']];
            $where['cid1'] = $cid1;
        }

        //4、最新文章
        $newArticles = $bookModel->getBooks(['where' => $where, 'order' => ['book_id' => 'DESC']], 'book_id,cid1,cid2,title,cover', 1, 8);
        $data['newArticles'] = getInitList($newArticles, $allCategorys, $type);

        //5、热门文章
        $hotArticles = $bookModel->getBooks(['where' => $where, 'order' => ['count_view' => 'DESC']], 'book_id,cid1,cid2,title,cover', 1, 8);
        $data['hotArticles'] = getInitList($hotArticles, $allCategorys, $type);

        //7、热门导航
        $tempCategorys = getRelationCategorys($allCategorys, 2, $cid1);
        $hotCategorys = array_slice($tempCategorys, 0, 6);
        $data['hotCategorys'] = $hotCategorys;

        //8、标中选中栏
        $data['breadcrumb'] = $breadcrumb;
        $keywords = implode(',', array_column($hotCategorys, 'title'));
        $data['seo'] = shareSeo($book['title'], $keywords, $book['desc']);

        return $data;
    }

    //文章详情
    public function showDetail($allCategorys, $category, $book_id, $chapter_id)
    {
        $route_name = $category['route_name'];
        $type = $category['type'];

        $data = [];
        $bookModel = new BookModel();
        $chapterModel = new ChapterModel();
        $userModel = new userModel();
        $book = $bookModel->getBook(['where' => ['book_id' => $book_id]]);
        $chapter = $chapterModel->getChapter(['where' => ['book_id' => $book_id, 'chapter_id' => $chapter_id]]);

        $chapterModel->editChapter(['count_view' => $chapter['count_view'] + 1], ['chapter_id' => $chapter_id]);

        $bookContent = $chapterModel->getChapterContent($chapter['link_id']);

        $userInfo = $userModel->getUserInfo($book['uid']);
        $chapter['count_view'] = $chapter['count_view'] + 1;
        $chapter['content'] = $bookContent['content'];
        $chapter['info'] = getInfo($chapter['content']);
        $chapter['username'] = $userInfo['username'];
        $chapter['photo'] = $userInfo['photo'];
        $chapter['route_name'] = $route_name;

        $data['chapter'] = $chapter;

        $cid1 = $book['cid1'];
        $cid2 = $book['cid2'];
        $pcategory = $allCategorys['pCategorys'][$cid1];

        $where = [];
        $firstBreadcrumb = getFirstBreadcrumb($type);
        if ($cid2) {
            $breadcrumb = [$firstBreadcrumb, ['url' => my_site_url($pcategory['route_name']), 'title' => $pcategory['title']], ['url' => my_site_url($category['route_name']), 'title' => $category['title']], ['url' => my_site_url($category['route_name'], $book_id), 'title' => $book['title'], 'cl' => 'ts-hide'], ['url' => my_site_url($pcategory['route_name'], $book_id . '_' . $chapter_id), 'title' => $chapter['title'], 'cl' => 'ts-hide']];
            $where['cid2'] = $cid2;
        } elseif ($cid1) {
            $breadcrumb = [$firstBreadcrumb, ['url' => my_site_url($category['route_name']), 'title' => $category['title']], ['url' => my_site_url($category['route_name'], $book_id), 'title' => $book['title'], 'cl' => 'ts-hide'], ['url' => my_site_url($category['route_name'], $book_id . '_' . $chapter_id), 'title' => $chapter['title'], 'cl' => 'ts-hide']];
            $where['cid1'] = $cid1;
        }

        //4、最新文章
        $newArticles = $bookModel->getBooks(['where' => $where, 'order' => ['book_id' => 'DESC']], 'book_id,cid1,cid2,title,cover', 1, 8);
        $data['newArticles'] = getInitList($newArticles, $allCategorys, $type);

        //5、热门文章
        $hotArticles = $bookModel->getBooks(['where' => $where, 'order' => ['count_view' => 'DESC']], 'book_id,cid1,cid2,title,cover', 1, 8);
        $data['hotArticles'] = getInitList($hotArticles, $allCategorys, $type);

        $data['prevChapter'] = $chapterModel->getChapter(['where' => ['book_id' => $book_id, 'chapter_id <' => $chapter_id], 'order' => ['chapter_id' => 'desc']]);
        $data['nextChapter'] = $chapterModel->getChapter(['where' => ['book_id' => $book_id, 'chapter_id >' => $chapter_id], 'order' => ['chapter_id' => 'asc']]);

        //7、热门导航
        $tempCategorys = getRelationCategorys($allCategorys, 2, $cid1);
        $hotCategorys = array_slice($tempCategorys, 0, 6);
        $data['hotCategorys'] = $hotCategorys;

        //8、标中选中栏
        $data['breadcrumb'] = $breadcrumb;
        $keywords = implode(',', array_column($hotCategorys, 'title'));
        $data['seo'] = shareSeo($chapter['title'], $keywords, $chapter['info']);

        return $data;
    }

}
