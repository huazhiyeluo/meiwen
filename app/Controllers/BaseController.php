<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;
    protected $data;
    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['common', 'seo'];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();

        $configProject = config('Project');
        $this->data['baseConfig'] = $baseConfig = $configProject->baseConfig;
        $this->data['showType'] = $showType = $configProject->showType;
        $this->data['seo'] = $configProject->seo;
        $this->data['tongji'] = $configProject->tongji;

        $session = session();
        $userInfo = $session->get('userInfo');
        $this->data['userInfo'] = $userInfo;
        $mobiledetect = new \App\ThirdParty\Mobiledetect();
        if ($mobiledetect->isMobile()) {
            $this->data['edittype'] = 'mobile';
        } else {
            $this->data['edittype'] = 'pc';
        }

        $cache = \Config\Services::cache();
        $cacheKey = 'top_type';
        $randnum = $cache->get($cacheKey);
        if (empty($randnum)) {
            $types = array_column($showType,'type');
            $randomKey = array_rand($types);
            $randnum = $types[$randomKey];
            $cache->save($cacheKey, $randnum, 24 * 3600);
        }
        $gettype = in_array($randnum, [1, 3]) ? 2 : 1;
        $allCategorys = $this->getCategorysByType($randnum);
        $tempCategorys = getRelationCategorys($allCategorys, $gettype);
        $topNavCategorys = array_slice($tempCategorys, 0, 4);
        $this->data['topNavCategorys'] = $topNavCategorys;

        //4、微信分享参数
        if (ENVIRONMENT == 'production') {
            $weixin = new \App\ThirdParty\Weixin();
            $this->data['weixin'] = $weixin->getSignPackage();
        } else {
            $this->data['weixin'] = ['appId' => 'wx15fd0f9b451abfbd', 'nonceStr' => '8AaU3AljFYjXJzyl', 'timestamp' => 1573094843, 'url' => $baseConfig['weburl'], 'signature' => 'signature', 'rawString' => 'jsapi_ticket=sM4AOVdWfPE4DxkXGEs8VGs4TY5wRfQJl4zPiVUMk2N7MwFt2spo1urMqh2JlG3Ohi-8R0WA5rkQuMvzdKv4Ew&noncestr=8AaU3AljFYjXJzyl×tamp=1573094843&url=' . $baseConfig['weburl']];
        }
    }

    /**
     * [getCategorys description]
     * @return [type]        [description]
     */
    protected function getCategorysByType(int $type = 0)
    {
        $cache = \Config\Services::cache();
        $cacheKey = 'allCategorys_' . $type;
        $allCategorys = $cache->get($cacheKey);
        if (true) {
            $categoryModel = new CategoryModel();

            $where = [];
            if (!empty($type)) {
                $where['type'] = $type;
            }
            $tempCategorys = $categoryModel->getCategorys(['where' => $where, 'order' => ['sort' => 'ASC', 'cid' => 'ASC']], 'cid,pcid,type,title,route_name', 1, 5000);
            $allCategorys = ['pCategorys' => [], 'cCategorys' => []];
            if ($tempCategorys) {
                $pallCategorys = [];
                foreach ($tempCategorys as $v) {
                    if ($v['pcid'] == 0) {
                        $pallCategorys[$v['cid']] = $v;
                    }
                }
                foreach ($tempCategorys as $k => $v) {
                    $pcid = $v['pcid'];
                    $type = $v['type'];

                    if ($pcid == 0) {
                        $allCategorys['pCategorys'][$v['cid']] = $v;
                    }
                    if ($pcid > 0) {
                        $v['route_name'] = $pallCategorys[$pcid]['route_name'] . '/' . $v['route_name'];
                        $allCategorys['cCategorys'][$v['cid']] = $v;
                    }

                }
            }
            $cache->save($cacheKey, $allCategorys, 24 * 3600);
        }
        return $allCategorys;
    }

}
