<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Index::index'); //首页

$routes->get('about.html', 'Index::info/about');        //关于我们
$routes->get('contact.html', 'Index::info/contact');    //联系我们
$routes->get('terms.html', 'Index::info/terms');        //用户条款
$routes->get('publish.html', 'Index::info/publish');    //投稿指南
$routes->get('disclaimer.html', 'Index::info/disclaimer'); //免责申明


$routes->get('login.html', 'Login::login'); //登录页
$routes->post('logindoweb.html', 'Login::loginDoWeb'); //邮箱手机号登录
$routes->get('loginreturn/([a-z]+).html', 'Login::loginReturn/$1'); //第三方登录回调
$routes->get('logindo/([a-z]+).html', 'Login::loginDo/$1'); //第三方登录页
$routes->get('logout.html', 'Login::logout'); //退出登录

$routes->get('register.html', 'Register::register'); //注册页
$routes->post('registerdo/(:num).html', 'Register::registerDo/$1'); //注册行为

$routes->get('code.html', 'Tool::code'); //验证码行为
$routes->post('upload/([a-z]+).html', 'Tool::upload/$1'); //上传行为

$routes->match(['get', 'post'], 'search.html', 'Search::index//1'); //搜索页
$routes->match(['get', 'post'], 'search/([a-z]+).html', 'Search::index/$1/1'); //搜索页
$routes->get('search/list_(:num).html', 'Search::index//$1'); //搜索页
$routes->get('search/([a-z]+)/list_(:num).html', 'Search::index/$1/$2'); //搜索页

$routes->get('sitemap.html', 'Sitemap::index'); //站点地图

$routes->get('user/(:num).html', 'User::detail/$1'); //用户详情
$routes->get('user/(:num)/([a-z]+).html', 'User::detail/$1/$2/1'); //用户详情
$routes->get('user/(:num)/([a-z]+)/list_(:num).html', 'User::detail/$1/$2/$3'); //用户详情
$routes->post('user/detailsdo/([a-z]+).html', 'User::detailsdo/$1'); //用户详情
$routes->post('user/detailsdo/([a-z]+)/([a-z]+).html', 'User::detailsdo/$1/$2'); //用户详情

$routes->get('weibo.html', 'Weibo::index'); //树洞
$routes->get('weibo/list_(:num).html', 'Weibo::index/$1'); //树洞
$routes->get('weibo/(:num).html', 'Weibo::detail/$1/1'); //树洞详情
$routes->get('weibo/(:num)_(:num).html', 'Weibo::detail/$1/$2'); //树洞详情
$routes->post('weibo/createdo.html', 'Weibo::createdo/$1'); //树洞详情
$routes->post('weibo/replydo.html', 'Weibo::replydo/$1'); //树洞详情
$routes->post('weibo/del.html', 'Weibo::del/$1'); //树洞详情


$routes->get('book.html', 'Book::index/1');                         //书籍入口
$routes->get('book/list_(:num).html', 'Book::index/$1');            //书籍入口

$routes->get('meiwen.html', 'Meiwen::index/1');                     //美文入口
$routes->get('meiwen/list_(:num).html', 'Meiwen::index/$1');        //美文-分页入口

$routes->get('gushi.html', 'Gushi::index/1');                      //故事入口
$routes->get('gushi/list_(:num).html', 'Gushi::index/$1');         //故事-分页入口

$routes->get('zuowen.html', 'Zuowen::index/1');                     //作文入口
$routes->get('zuowen/list_(:num).html', 'Zuowen::index/$1');        //作文-分页入口


$routes->cli('sitemap/repairArticle', 'Sitemap::repairArticle');
$routes->cli('sitemap/createSiteMap', 'Sitemap::createSiteMap');
$routes->cli('repair/repairContent', 'Repair::repairContent');
$routes->cli('repair/updateContent', 'Repair::updateContent');
$routes->cli('spider/index/(:num)', 'Spider::index/$1');


$routes->get('(:alpha).html', 'Category::index/$1//1');                                     //分类页
$routes->get('(:alpha)/(:alpha).html', 'Category::index/$1/$2/1');                          //分类页
$routes->get('(:alpha)/list_(:num).html', 'Category::index/$1//$2');                        //分类页
$routes->get('(:alpha)/(:alpha)/list_(:num).html', 'Category::index/$1/$2/$3');             //分类页

$routes->get('(:alpha)/(:num).html', 'Category::detail/$1//$2/0');                            //文章详情 | 书籍详情
$routes->get('(:alpha)/(:alpha)/(:num).html', 'Category::detail/$1/$2/$3/0');                 //文章详情 | 书籍详情

$routes->get('(:alpha)/(:num)_(:num).html', 'Category::detail/$1//$2/$3/1');                    //章节详情
$routes->get('(:alpha)/(:alpha)/(:num)_(:num).html', 'Category::detail/$1/$2/$3/$4');           //章节详情
