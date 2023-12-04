<?php

namespace App\Controllers;
use App\Models\BookModel;
use App\Models\SearchModel;

class Search extends BaseController {

    //---------------------------------------查询-----------------------------------------

    //入口文件
    public function index($tag = '', $page = 1) {
        $pageSize = 15;

        $data['tag']        = $tag;
        $data['controller'] = 'index';

        $params = $this->request->getPost();
        if (!$params) {
            $params = $this->request->getGet();
        }
        $keyword = isset($params['keyword']) ? $params['keyword'] : '';

        $data['keyword'] = $keyword;
        $subfix          = '';
        if ($keyword) {
            $subfix = '?keyword=' . $keyword;
        }

        if ($tag == '') {
            $url = my_site_url('search') . $subfix;
        }
        if ($tag == 'meiwen') {
            $url = my_site_url('search/meiwen') . $subfix;
        }
        if ($tag == 'gushi') {
            $url = my_site_url('search/gushi') . $subfix;
        }
        if ($tag == 'zuowen') {
            $url = my_site_url('search/zuowen') . $subfix;
        }
        if ($tag == 'book') {
            $url = my_site_url('search/book') . $subfix;
        }

        $allCategorys = $this->getCategorysByType();

        $data['url'] = $url;

        $searchModel = new SearchModel();
        $bookModel   = new BookModel();

        if ($keyword) {
            switch ($tag) {
            case '':
                $searchs = $searchModel->getSearchAll($keyword, $page, $pageSize);
                break;
            case 'meiwen':
                $searchs = $searchModel->getSearchMeiwen($keyword, $page, $pageSize);
                break;
            case 'gushi':
                $searchs = $searchModel->getSearchGushi($keyword, $page, $pageSize);
                break;
            case 'zuowen':
                $searchs = $searchModel->getSearchZuowen($keyword, $page, $pageSize);
                break;
            case 'book':
                $searchs = $searchModel->getSearchBook($keyword, $page, $pageSize);
                break;
            }
        } else {
            $searchs = ['list' => [], 'total' => 0];
        }

        $book_ids = array_unique(array_filter(array_column($searchs['list'], 'b_id')));
        if($book_ids)
        {
            $listBook = $bookModel->getBooks(['whereIn' => ['book_id' => $book_ids], 'order' => ['book_id' => 'DESC']], 'book_id,title,cid1,cid2,author', 1, 8);
            $listBook = array_column($listBook, null, 'book_id');
        }

        foreach ($searchs['list'] as $k => $v) {
            if ($v['oid'] != 5) {
                $searchs['list'][$k]['route_info'] = getRouteInfo($allCategorys, $v['cid1'], $v['cid2'], $v['oid']);
            } else {
                $book_info                         = $listBook[$v['id']];
                $searchs['list'][$k]['book_info']  = $book_info;
                $searchs['list'][$k]['route_info'] = getRouteInfo($allCategorys, $book_info['cid1'], $book_info['cid2'], 1);
            }
        }

        $mypaper       = new \App\ThirdParty\MyPaper($page, $pageSize, $searchs['total']);
        $data['page']  = $mypaper->createLinks('search', '/list_', '?keyword=' . $keyword);
        $data['list']  = $searchs['list'];
        $data['total'] = $searchs['total'];

        $this->data['seo'] = shareSeo('搜索_'.$keyword);

        $this->data = array_merge($data, $this->data);
        return view('search/index', $this->data);
    }
}
