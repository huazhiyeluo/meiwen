<?php

namespace App\Controllers;

use App\Models\Spider\SpiderModel;

class Spider extends BaseController {

    private $webList = [
        1 => [
            't_id'       => 1,
            'type'       => 2,
            'entryUrl'   => 'http://www.szwj72.cn/',
            'dep'        => 10,
            'domainUrl'  => 'szwj72.cn',
            'listUrl'    => '/szwj72.cn\/([a-z]+\/){1,2}(Index.html){0,1}(index_[\d]+.html){0,1}$/i',
            'contentUrl' => '/szwj72.cn\/([a-z]+\/){1,2}[\d]+\/[\d]+.html/i',
        ],
        2 => [
            't_id'       => 2,
            'type'       => 3,
            'entryUrl'   => 'https://www.guidaye.com/',
            'dep'        => 10,
            'domainUrl'  => 'guidaye.com',
            'listUrl'    => '/www.guidaye.com\/(dp|cp|xy|yy|jl|mj|ly|neihanguigushi|yc)\/(index_[\d]+.html){0,1}$/i',
            'contentUrl' => '/www.guidaye.com\/(dp|cp|xy|yy|jl|mj|ly|neihanguigushi|yc)\/[0-9]+.html$/i',
        ],
        3 => [
            't_id'       => 3,
            'type'       => 1,
            'entryUrl'   => 'https://www.uuzuowen.com/',
            'dep'        => 10,
            'domainUrl'  => 'uuzuowen.com',
            'listUrl'    => '/www.uuzuowen.com\/gudianbook\/shishudaquan\/$/i',
            'contentUrl' => '/www.uuzuowen.com\/gudianbook\/shishudaquan\/([a-z]+\/){1}$/i',
        ],
        4 => [
            't_id'       => 4,
            'type'       => 2,
            'entryUrl'   => 'https://www.bidushe.com/',
            'dep'        => 10,
            'domainUrl'  => 'bidushe.com',
            'listUrl'    => '/www.bidushe.com\/([a-z]+\/){1,3}(list_[\d]+.html){0,1}$/i',
            'contentUrl' => '/www.bidushe.com\/([a-z]{2,}\/){1,3}[0-9]+.html$/i',
        ],
        5 => [
            't_id'       => 5,
            'type'       => 4,
            'entryUrl'   => 'https://www.99zuowen.com/',
            'dep'        => 10,
            'domainUrl'  => '99zuowen.com',
            'listUrl'    => '/www.99zuowen.com\/([a-z]+\/){0,4}(list_[\d]+_[\d]+.html){0,1}([a-z]+.html){0,1}$/i',
            'contentUrl' => '/www.99zuowen.com\/([a-z]+\/){1,4}[0-9]+.html$/i',
        ],
        6 => [
            't_id'       => 6,
            'type'       => 3,
            'entryUrl'   => 'https://www.gushidaquan.com.cn/',
            'dep'        => 10,
            'domainUrl'  => 'gushidaquan.com.cn',
            'listUrl'    => '/www.gushidaquan.com.cn\/([a-z]+\/){1,3}([a-z]+)$/i',
            'contentUrl' => '/www.gushidaquan.com.cn\/([a-z]+\/){1,3}[0-9]+.html$/i',
        ],
    ];

    public function index($t_id = 0) {
        // if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
        //     $runFile = WRITEPATH . "run/spider{$t_id}.run";
        //     $dieFile = WRITEPATH . "run/spider{$t_id}.die";
        //     clearstatcache(); // 清除文件缓存，不然获取最后访问时间会出错
        //     //判断是否需要重启
        //     if (file_exists($runFile)) {
        //         //重启检测设为300s，当300s中未对runFile进行访问时，重启进程
        //         if (time() - fileatime($runFile) < 300) {
        //             return false;
        //         } else {
        //             $pid = file_get_contents($runFile);
        //             shell_exec("ps aux | grep '{$_SERVER['PHP_SELF']}' | grep 'Spider index {$t_id}' | grep -v 'grep' | awk '{print $2}' | grep {$pid} | xargs --no-run-if-empty kill");
        //         }
        //     }
        //     if (!file_put_contents($runFile, getmygid())) {
        //         return false;
        //     }
        // }
        $config = $this->webList[$t_id];
        $type   = $config['type'];

        $spider = new SpiderModel();
        if ($type == 1) {
            $className = "\App\Models\Spider\Book{$t_id}Model";
        } else {
            $className = "\App\Models\Spider\Web{$t_id}Model";
        }
        $web = new $className;

        while (true) {
            // if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
            //     if (file_exists($dieFile)) {
            //         unlink($runFile) && unlink($dieFile);
            //         return false;
            //     }
            //     touch($runFile);
            // }
            $this->loadIndex($t_id, $config, $spider, $web);
            sleep(1);
        }
    }

    private function loadIndex($t_id, $config, $spider, $web) {

        //$web->setContent('https://www.guidaye.com/mj/12112.html', $config['entryUrl']);
        // $web->setContent('https://www.guidaye.com/cp/2517.html', $config['entryUrl']);
        // $web->setContent('https://www.guidaye.com/xy/10479.html', $config['entryUrl']);
        // $web->setContent($t_id, $config['type'], 'http://www.szwj72.cn/Article/aqzw/201511/712.html', $config['entryUrl']);

        // exit;
        echo $t_id;
        $urls = $spider->getUrls($t_id);

        // print_r($urls);
        // exit;

        if ($urls) //存在url
        {

            foreach ($urls as $k => $v) {
                $spider->operateUrl($v['url'], $config);
                if ($v['type'] == 0) {
                    $web->setContent($t_id, $config['type'], $v['url'], $config['entryUrl']);
                }
                // sleep(1);
            }
            sleep(1);
        } else {
            //不存在走入口url
            $spider->operateUrl($config['entryUrl'], $config);
        }
    }

}