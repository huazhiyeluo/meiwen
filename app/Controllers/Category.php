<?php

namespace App\Controllers;

class Category extends BaseController
{

    /**
     * 分类列表
     * @param  [type] $page [description]  页码
     * @return [type]       [description]
     */
    public function index(string $proute_name = '', string $croute_name = '', $page = 1)
    {
        //1、获得路由分类信息
        $route_name = '';
        if ($croute_name != "") {
            $route_name = "$proute_name/$croute_name";
        } else {
            $route_name = $proute_name;
        }
        foreach (range(1, 4) as $v) {
            $allCategorys = $this->getCategorysByType($v);
            $newCategorys = array_column(getRelationCategorys($allCategorys), null, 'route_name');
            if (isset($newCategorys[$route_name])) {
                $category = $newCategorys[$route_name];
                break;
            }
        }

        //2、路由
        if (empty($category)) {
            die("No category");
        }
        $type = $category['type'];
        if ($category['pcid'] == 0) {
            $pcid = $category['cid'];
            $cid1 = $category['cid'];
            $cid2 = 0;
        } else {
            $pcid = $category['pcid'];
            $cid1 = $category['pcid'];
            $cid2 = $category['cid'];
        }

        //4、关联菜单
        $tempCategorys = getRelationCategorys($allCategorys, 0, $pcid);
        $relationCategorys = array_slice($tempCategorys, 0, 8);

        $this->data['type'] = $type;
        $this->data['breadcrumb'] = getBreadcrumb($allCategorys, $category);
        $this->data['route_name'] = $route_name;
        $this->data['controller'] = getFirstRoutename($type);

        $this->data['seo'] = getNavTDS($this->data['seo'], $tempCategorys, $type, $category['cid']);

        $nav = getFirstNav($type);
        array_unshift($relationCategorys, $nav);
        $this->data['relationCategorys'] = $relationCategorys;

        switch ($type) {
            case 1:
                $obj = new Book();
                $data = $obj->showIndex($allCategorys, $category, $page, $cid1, $cid2);
                $this->data = array_merge($this->data, $data);
                return view('book/index', $this->data);
                break;
            case 2:
                $obj = new Meiwen();
                $data = $obj->showIndex($allCategorys, $category, $page, $cid1, $cid2);
                $this->data = array_merge($this->data, $data);
                return view('meiwen/index', $this->data);
                break;
            case 3:
                $obj = new Gushi();
                $data = $obj->showIndex($allCategorys, $category, $page, $cid1, $cid2);
                $this->data = array_merge($this->data, $data);
                return view('gushi/index', $this->data);
                break;
            case 4:
                $obj = new Zuowen();
                $data = $obj->showIndex($allCategorys, $category, $page, $cid1, $cid2);
                $this->data = array_merge($this->data, $data);
                return view('zuowen/index', $this->data);
                break;
            default:
                die("Unknown category");
        }
    }

    /**
     * [detail description]分类详情
     * @param  [type] $id   [description] 文章ID | 书籍ID
     * @param  [type] $page [description] 文章页码 | 书籍章节
     * @return [type]       [description]
     */
    public function detail(string $proute_name = '', string $croute_name = '', $id, $page)
    {
        //1、获得路由分类信息
        $route_name = '';
        if ($croute_name != "") {
            $route_name = "$proute_name/$croute_name";
        } else {
            $route_name = $proute_name;
        }
        foreach (range(1, 4) as $v) {
            $allCategorys = $this->getCategorysByType($v);
            $newCategorys = array_column(getRelationCategorys($allCategorys), null, 'route_name');
            if (isset($newCategorys[$route_name])) {
                $category = $newCategorys[$route_name];
                break;
            }
        }
        if (empty($category)) {
            die("No category");
        }
        //2、路由
        $type = $category['type'];
        if ($category['pcid'] == 0) {
            $pcid = $category['cid'];
        } else {
            $pcid = $category['pcid'];
        }

        //4、关联菜单
        $tempCategorys = getRelationCategorys($allCategorys, 0, $pcid);
        $relationCategorys = array_slice($tempCategorys, 0, 8);

        $this->data['type'] = $type;
        $this->data['breadcrumb'] = getBreadcrumb($allCategorys, $category);
        $this->data['route_name'] = $route_name;
        $this->data['controller'] = getFirstRoutename($type);

        $nav = getFirstNav($type);
        array_unshift($relationCategorys, $nav);
        $this->data['relationCategorys'] = $relationCategorys;

        switch ($type) {
            case 1:
                $obj = new Book();
                if (!empty($page)) {
                    $data = $obj->showDetail($allCategorys, $category, $id, $page);
                    $this->data = array_merge($this->data, $data);
                    return view('book/detail', $this->data);
                } else {
                    $data = $obj->showChapter($allCategorys, $category, $id);
                    $this->data = array_merge($this->data, $data);
                    return view('book/chapter', $this->data);
                }
                break;
            case 2:
                $obj = new Meiwen();
                $data = $obj->showDetail($allCategorys, $category, $id, $page);
                $this->data = array_merge($this->data, $data);
                return view('meiwen/detail', $this->data);
                break;
            case 3:
                $obj = new Gushi();
                $data = $obj->showDetail($allCategorys, $category, $id, $page);
                $this->data = array_merge($this->data, $data);
                return view('gushi/detail', $this->data);
                break;
            case 4:
                $obj = new Zuowen();
                $data = $obj->showDetail($allCategorys, $category, $id, $page);
                $this->data = array_merge($this->data, $data);
                return view('zuowen/detail', $this->data);
                break;
            default:
                die("Unknown category");
        }
    }
}
