<?php
//分词
function getScws($text)
{
    $sh = scws_open();
    scws_set_charset($sh, 'utf8');
    scws_set_ignore($sh, 1);
    scws_set_duality($sh, 1);
    scws_set_multi($sh, SCWS_MULTI_SHORT);
    scws_set_dict($sh, '/usr/local/scws/etc/dict.utf8.xdb');
    scws_set_rule($sh, '/usr/local/scws/etc/rules.utf8.ini');
    $text = strip_tags($text);
    scws_send_text($sh, $text);
    $top = scws_get_tops($sh, 6);
    $keywords = implode(',', array_column($top, 'word'));
    return $keywords;
}

//唯一值生成器
function genGUID($separate = '-', $prefix = '')
{
    $chars = md5(uniqid(mt_rand(), true));
    $aGuid[] = substr($chars, 0, 8);
    $aGuid[] = substr($chars, 8, 4);
    $aGuid[] = substr($chars, 12, 4);
    $aGuid[] = substr($chars, 16, 4);
    $aGuid[] = substr($chars, 20, 12);
    return $prefix . strtoupper(implode((string) $separate, $aGuid));
}

function getInfo($content, $length = 150)
{
    $info = strip_tags($content);
    $info = trim(str_replace(array("&#13;", " ", "&nbsp;", "　", "\r\n", "\n", "\r"), "", $info));
    $info = msubstr($info, 0, $length) . '...';
    return trim($info);
}

function mstrlen($str, $charset = "utf-8")
{
    if (function_exists("mb_strlen")) {
        $len = mb_strlen($str, $charset);
    } else if (function_exists("iconv_strlen")) {
        $len = iconv_strlen($str, $charset);
    } else {
        $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $len = count($match[0]);
    }
    return $len;
}

function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true)
{
    $len = mstrlen($str, $charset);

    if ($len <= $length) {
        $slice = $str;
    } else {
        if (function_exists("mb_substr")) {
            $slice = mb_substr($str, $start, $length, $charset);
        } else if (function_exists('iconv_substr')) {
            $slice = iconv_substr($str, $start, $length, $charset);
        } else {
            $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
            $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
            preg_match_all($re[$charset], $str, $match);
            $slice = join("", array_slice($match[0], $start, $length));
        }
        if ($suffix == true) {
            $slice = $slice . '...';
        }
    }
    return $slice;
}

function time_tran($timeInt, $format = 'Y-m-d H:i')
{
    $timeInt = (int) $timeInt;
    $d = time() - $timeInt;
    if ($d < 0) {
        return $timeInt;
    } else {
        if ($d < 60) {
            return $d . '秒前';
        } else {
            if ($d < 3600) {
                return floor($d / 60) . '分钟前';
            } else {
                if ($d < 86400) {
                    return floor($d / 3600) . '小时前';
                } else {
                    if ($d < 259200) {
                        return floor($d / 86400) . '天前';
                    } else {
                        return date($format, $timeInt);
                    }
                }
            }
        }
    }
}

//数据元素拼接成url链接
function combineURL($baseURL, $keysArr)
{
    $combined = $baseURL . "?";
    $valueArr = array();

    foreach ($keysArr as $key => $val) {
        $valueArr[] = "$key=$val";
    }

    $keyStr = implode("&", $valueArr);
    $combined .= ($keyStr);

    return $combined;
}

//链接生成器
function my_site_url($name, $id = 0, $id2 = 0, $fix = '.html', $scheme = '')
{
    $str = '';
    if ($name) {
        $str .= $name;
    }
    if ($id) {
        $str .= '/' . $id;
    }

    if ($id2) {
        $str .= '/' . $id2;
    }

    $str .= $fix;

    return site_url($str);
}

//获取路由信息
function getRouteInfo($allCategorys, $cid1 = 0, $cid2 = 0)
{
    if (!empty($cid2)) {
        $info = isset($allCategorys['cCategorys'][$cid2]) ? $allCategorys['cCategorys'][$cid2] : [];
        if (empty($info)) {
            $info = isset($allCategorys['pCategorys'][$cid2]) ? $allCategorys['pCategorys'][$cid2] : [];
        }
    } else {
        $info = isset($allCategorys['pCategorys'][$cid1]) ? $allCategorys['pCategorys'][$cid1] : [];
    }
    return $info;
}

//初始化数据
function getInitList($list, $allCategorys, $type)
{
    if ($list) {
        $userModel = new \App\Models\UserModel();
        foreach ($list as $k => $v) {
            if (isset($v['uid'])) {
                $userInfo = $userModel->getUserInfo($v['uid']);
                $list[$k]['username'] = $userInfo['username'];
            }
            $routeInfo = getRouteInfo($allCategorys, $v['cid1'], $v['cid2'], $type);
            $list[$k]['route_info'] = $routeInfo;
        }
    }
    return $list;
}

//初始化书籍数据
function getInitChapter($list, $listBook, $allCategorys)
{
    if ($list) {
        $listBook = array_column($listBook, null, 'book_id');
        foreach ($list as $k => $v) {
            $book_info = $listBook[$v['book_id']];
            $list[$k]['book_info'] = $book_info;
            $list[$k]['route_info'] = getRouteInfo($allCategorys, $book_info['cid1'], $book_info['cid2'], 1);
        }
    }
    return $list;
}

//获取一级菜单
function getRelCategorys($allCategorys, $types = [], $level = 0, $pcid = 5000)
{
    $tempCategorys = [];

    if ($level) {
        if ($level == 1) {
            $tempCategorys = $allCategorys['pCategorys'];
        }
        if ($level == 2) {
            $tempCategorys = $allCategorys['cCategorys'];
        }
    } else {
        $tempCategorys = array_reduce($allCategorys, 'array_merge', array());
    }

    $relationCategorys = [];
    if ($tempCategorys) {
        $temp = array_reduce($tempCategorys, 'array_merge', array());
        foreach ($temp as $k => $v) {
            if (empty($types)) {
                if ($pcid < 5000) {
                    if ($pcid == $v['pcid']) {
                        $relationCategorys[] = $v;
                    }
                } else {
                    $relationCategorys[] = $v;
                }

            } elseif (in_array($v['type'], $types)) {
                if ($pcid < 5000) {
                    if ($pcid == $v['pcid']) {
                        $relationCategorys[] = $v;
                    }
                } else {
                    $relationCategorys[] = $v;
                }
            }
        }
    }
    return $relationCategorys;
}

/**
 * 文章归类到不同表
 * $mark array
 * return array
 */
function getContentTableIndex($mark)
{
    $result = [];
    foreach ($mark as $article_id => $is_mul_page) {
        if ($is_mul_page == 0) {
            $index = intval($article_id / 4000);
            $result[$is_mul_page][$index][] = $article_id;
        }
        if ($is_mul_page == 1) {
            $index = intval($article_id / 50000);
            $result[$is_mul_page][$index][] = $article_id;
        }

    }
    return $result;
}

/**
 * 获取文章封面
 * @param  [type] $content [description]
 * @return [type]          [description]
 */
function getCover($content)
{
    $cover = '';
    $preg = '/<img[^>]*src=[\'"]?([^>\'"\s]*)[\'"]?[^>]*>/is';
    preg_match_all($preg, $content, $allImg);
    if (isset($allImg[1][0])) {
        $cover = $allImg[1][0];
    }
    return $cover;
}

/**
 * 设置上一个url
 * @return [type] [description]
 */
function nologinset()
{
    $prev_url = isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : site_url();
    session()->set('prev_url', $prev_url);
}

function nologin()
{
    $prev_url = isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : site_url();
    session()->set('prev_url', $prev_url);
    return json_encode(array('code' => 100, 'msg' => '请先登录！'));
}

/**
 * 获取上一个url
 * @return [type] [description]
 */
function tologinurl()
{
    $prev_url = session()->get('prev_url');
    if (!$prev_url) {
        $prev_url = site_url();
    }
    return $prev_url;
}

/**
 * 获取面包屑导航的第一个数据
 * @param  [type] $type [description]
 * @return [type]       [description]
 */
function getFirstBreadcrumb($type)
{
    $breadcrumb = [];
    switch ($type) {
        case 1:
            $breadcrumb = ['url' => my_site_url('book'), 'title' => '图书'];
            break;
        case 2:
            $breadcrumb = ['url' => my_site_url('meiwen'), 'title' => '美文'];
            break;
        case 3:
            $breadcrumb = ['url' => my_site_url('gushi'), 'title' => '故事'];
            break;
        case 4:
            $breadcrumb = ['url' => my_site_url('zuowen'), 'title' => '作文'];
            break;
    }
    return $breadcrumb;
}

/**
 * 获取全部
 * @param  [type] $type [description]
 * @return [type]       [description]
 */
function getFirstNav($type)
{
    $nav = [];
    switch ($type) {
        case 1:
            $nav = ['cid' => 0, 'route_name' => 'book', 'title' => '全部'];
            break;
        case 2:
            $nav = ['cid' => 0, 'route_name' => 'meiwen', 'title' => '全部'];
            break;
        case 3:
            $nav = ['cid' => 0, 'route_name' => 'gushi', 'title' => '全部'];
            break;
        case 4:
            $nav = ['cid' => 0, 'route_name' => 'zuowen', 'title' => '全部'];
            break;
    }
    return $nav;
}

/**
 * 路由
 * @param  [type] $type [description]
 * @return [type]       [description]
 */
function getFirstRoutename($type)
{
    $route_name = '';
    switch ($type) {
        case 1:
            $route_name = 'book';
            break;
        case 2:
            $route_name = 'meiwen';
            break;
        case 3:
            $route_name = 'gushi';
            break;
        case 4:
            $route_name = 'zuowen';
            break;
    }
    return $route_name;
}

/**
 * 路由
 * @param  [type] $type [description]
 * @return [type]       [description]
 */
function getFirstName($type)
{
    $name = '';
    switch ($type) {
        case 1:
            $name = '图书';
            break;
        case 2:
            $name = '美文';
            break;
        case 3:
            $name = '故事';
            break;
        case 4:
            $name = '作文';
            break;
        case 5:
            $name = '章节';
            break;
    }
    return $name;
}

/**
 * [sendEmail description]发送邮件
 * @param  [type] $to      [description]
 * @param  [type] $title   [description]
 * @param  [type] $content [description]
 * @return [type]          [description]
 */
function sendEmail($to, $title, $content)
{
    $email = \Config\Services::email();

    $config['protocol'] = 'smtp';
    $config['SMTPHost'] = 'smtp.qq.com';
    $config['SMTPUser'] = '370838500@qq.com';
    $config['SMTPPass'] = 'kozexlkomusqbidg'; // mgvmqpktqdkmbgfb
    $config['SMTPCrypto'] = 'tls';
    $config['SMTPPort'] = 587;
    $config['mailType'] = 'html';
    $config['wordWrap'] = true;

    $email->initialize($config);

    $configProject = config('Project');
    $sitename = $configProject->sitename;

    $email->setFrom('370838500@qq.com', $sitename . '网');
    $email->setTo($to);

    $email->setSubject($title);
    $email->setMessage($content);

    return $email->send();
}

function getNavTDS($defaultSeo, $navs, $type, $cid)
{
    $title = '';
    $keywords = '';
    $description = '';
    $catename = getFirstName($type);

    if ($cid) {
        $categoryModel = new \App\Models\CategoryModel();
        $category = $categoryModel->getCategory(['where' => ['cid' => $cid]]);
        if ($category) {
            $title = $category['meta_title'];
            $keywords = $category['meta_keywords'];
            $description = $category['meta_description'];

            $catename = $category['title'];
        }
    }
    if ($navs) {
        $configProject = config('Project');
        $baseConfig = $configProject->baseConfig;

        $navs = array_column($navs, 'title');

        if (!$title) {
            $title = $catename . '_' . $baseConfig['sitename'] . '网';
        }
        if (!$keywords) {
            $keywords = implode(',', $navs);
        }
        if (!$description) {
            $description = $baseConfig['sitename'] . '网(' . $baseConfig['weburl'] . ')中' . $catename . '栏目为您提供了' . implode('、', $navs) . '等各类精美文章,欢迎阅读浏览分享！';
        }
    }
    if (!$title) {
        $title = $defaultSeo['title'];
    }
    if (!$keywords) {
        $keywords = $defaultSeo['keywords'];
    }
    if (!$description) {
        $description = $defaultSeo['description'];
    }
    return ['title' => $title, 'keywords' => $keywords, 'description' => $description];
}

//----------------------------------------------------------------  --------------------------------

//获取一级菜单
function getRelationCategorys(array $allCategorys, int $level = 0, int $pcid = 0)
{
    $tempCategorys = [];
    if ($level) {
        if ($level == 1) {
            $tempCategorys = $allCategorys['pCategorys'];
        }
        if ($level == 2) {
            $tempCategorys = $allCategorys['cCategorys'];
        }
    } else {
        $tempCategorys = array_merge($allCategorys['pCategorys'], $allCategorys['cCategorys']);
    }

    $relationCategorys = [];
    foreach ($tempCategorys as $v) {
        if ($pcid != 0 && $pcid == $v['pcid']) {
            $relationCategorys[] = $v;
        } else {
            $relationCategorys[] = $v;
        }
    }
    return $relationCategorys;
}

function getBreadcrumb(array $allCategorys = [], array $category = [])
{
    $breadcrumb = [];
    if (isset($category['type']) && !empty($category['type'])) {
        $type = $category['type'];
        $firstBreadcrumb = getFirstBreadcrumb($type);
        if (isset($category['pcid']) && !empty($category['pcid'])) {
            $pcategory = $allCategorys['pCategorys'][$category['pcid']];
            $breadcrumb = [$firstBreadcrumb, ['url' => my_site_url($pcategory['route_name']), 'title' => $pcategory['title']], ['url' => my_site_url($category['route_name']), 'title' => $category['title']]];
        } else {
            $breadcrumb = [$firstBreadcrumb, ['url' => my_site_url($category['route_name']), 'title' => $category['title']]];
        }
    }
    return $breadcrumb;
}
