<?php namespace App\Models\Spider;

use CodeIgniter\Model;

class SpiderModel extends Model {
    protected $table      = 'spiderurl_';
    protected $primaryKey = 'id';

    protected $allowedFields = [];
    protected $beforeInsert  = ['beforeInsert'];
    protected $beforeUpdate  = ['beforeUpdate'];

    protected $db_spider;

    public function __construct() {
        $this->db_spider = db_connect('spider');
    }

    protected function beforeInsert(array $data) {
        return $data;
    }

    protected function beforeUpdate(array $data) {
        return $data;
    }

    /**
     * [getSpiderList description]
     * @param  [type]  $t_id     [description]
     * @param  array   $where    [description]
     * @param  array   $order    [description]
     * @param  string  $field    [description]
     * @param  integer $pageSize [description]
     * @param  integer $page  [description]
     * @return [type]            [description]
     */
    public function getSpiderList($t_id, $where = [], $order = [], $field = "*", $page = 1, $pageSize = 15) {
        $pageSize  = $pageSize;
        $startSize = ($page - 1) * $pageSize;

        $builder = $this->db_spider->table('spiderurl_' . $t_id);
        $builder->select($field);

        if ($where) {
            foreach ($where as $k => $v) {
                $builder->where($k, $v);
            }
        }
        if ($order) {
            foreach ($order as $k => $v) {
                $builder->orderBy($k, $v);
            }
        }

        $builder->limit($pageSize, $startSize);

        $query = $builder->get();

        $data = $query->getResultArray();
        return $data;
    }

    /**
     * [getSpiderOne description]
     * @param  [type] $t_id  [description]
     * @param  array  $where [description]
     * @param  array  $order [description]
     * @param  string $field [description]
     * @return [type]        [description]
     */
    public function getSpiderOne($t_id, $where = [], $order = [], $field = "*") {
        $builder = $this->db_spider->table('spiderurl_' . $t_id);
        $builder->select($field);

        if ($where) {
            foreach ($where as $k => $v) {
                $builder->where($k, $v);
            }
        }
        if ($order) {
            foreach ($order as $k => $v) {
                $builder->orderBy($k, $v);
            }
        }

        $query = $builder->get();

        $data = $query->getRowArray();
        return $data;
    }

    public function addSpider($t_id, $data) {
        $builder = $this->db_spider->table('spiderurl_' . $t_id);
        $builder->insert($data);
        return $this->db_spider->insertID();
    }

    public function editSpider($t_id, $data, $where = []) {
        $builder = $this->db_spider->table('spiderurl_' . $t_id);
        $builder->where($where);
        $builder->update($data);
        return $this->db_spider->affectedRows();
    }

    public function editInSpider($t_id, $data, $ids = []) {
        $builder = $this->db_spider->table('spiderurl_' . $t_id);
        $builder->whereIn('id' , $ids);
        $builder->update($data);
        return $this->db_spider->affectedRows();
    }

    /**
     * [delSpider description]
     * @param  [type] $t_id  [description]
     * @param  array  $where [description]
     * @return [type]        [description]
     */
    public function delSpider($t_id, $where = []) {
        $builder = $this->db_spider->table('spiderurl_' . $t_id);
        $builder->delete($where);
        return $this->db_spider->affectedRows();
    }

    /**
     * 获取待处理的数据，处理完后
     * @param  [type] $t_id [description]
     * @return [type]       [description]
     */
    public function getUrls($t_id) {
        $urls = $this->getSpiderList($t_id, ['is_delete' => 0, 'is_lock' => 0, 'type' => 0], ['id' => 'DESC'], '*', 1, 15);
        if (!$urls) {
            $urls = $this->getSpiderList($t_id, ['is_delete' => 0, 'is_lock' => 0, 'type' => 1], ['id' => 'DESC'], '*', 1, 15);
        }
        if ($urls) {
            $this->editInSpider($t_id, ['is_lock' => 1], array_column($urls, 'id'));
        }
        return $urls;
    }

    public function getHtmlContent($url) {
        $client   = \Config\Services::curlrequest();
        $html = '';
        try {
        $response = $client->request('get', $url, [
                'headers' => ['User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36'],
                'timeout' => 20,
            ]);
            $html = $response->getBody();         
        } catch (\Exception $e) {
            
        }
        return $html;
    }

    public function operateUrl($url, $config) {
        $t_id       = $config['t_id'];
        $dep        = $config['dep'];
        $domainUrl  = $config['domainUrl'];
        $listUrl    = $config['listUrl'];
        $contentUrl = $config['contentUrl'];

        $dataUrl = $this->getSpiderOne($t_id, ['url' => $url]);
        if ($dataUrl) {
            $dataDep = $dataUrl['dep'];
        } else {
            $dataDep = 0;
        }

        $html = $this->getHtmlContent($url);

        preg_match_all("/<a.*href=[\"']{1}(.*)[\"']{0,1}[> \r\n\t]{1,}/isU", $html, $matchs);
        $links = $matchs[1];

        foreach ($links as $k => $v) {
            $curUrl = $this->fillUrl($v, $url, $domainUrl);

            if (preg_match($listUrl, $curUrl)) {
                if (!$this->getSpiderOne($t_id, ['url' => $curUrl, 'type' => 1])) {
                    if ($url != $curUrl) {
                        $this->addSpider($t_id, ['url' => $curUrl, 'type' => 1, 'dep' => $dataDep + 1]);
                    }
                }
            } elseif (preg_match($contentUrl, $curUrl)) {
                if (!$this->getSpiderOne($t_id, ['url' => $curUrl, 'type' => 0])) {
                    $this->addSpider($t_id, ['url' => $curUrl, 'type' => 0, 'dep' => $dataDep + 1]);
                }
            }
        }
    }

    /**
     * 获取页面所有链接地址
     * @param  [type] $url       [description]
     * @param  [type] $lastUrl   [description]
     * @param  [type] $domainUrl [description]
     * @return [type]            [description]
     */
    private function fillUrl($url, $lastUrl, $domainUrl) {
        $url     = trim($url);
        $lastUrl = trim($lastUrl);
        if (preg_match("@^(javascript:|#|'|\")@i", $url) || $url == '') {
            return false;
        }
        if (substr($url, 0, 3) == '<%=') {
            return false;
        }
        $parse_url = @parse_url($lastUrl);
        if (empty($parse_url['scheme']) || empty($parse_url['host'])) {
            return false;
        }
        if (!in_array($parse_url['scheme'], array("http", "https"))) {
            return false;
        }
        $scheme        = $parse_url['scheme'];
        $domain        = $parse_url['host'];
        $path          = empty($parse_url['path']) ? '' : $parse_url['path'];
        $base_url_path = $domain . $path;
        $base_url_path = preg_replace("/\/([^\/]*)\.(.*)$/", "/", $base_url_path);
        $base_url_path = preg_replace("/\/$/", '', $base_url_path);

        $i    = $path_step    = 0;
        $dstr = $pstr = '';
        $pos  = strpos($url, '#');
        if ($pos > 0) {
            // 去掉#和后面的字符串
            $url = substr($url, 0, $pos);
        }

        // 京东变态的都是 //www.jd.com/111.html
        if (substr($url, 0, 2) == '//') {
            $url = str_replace("//", "", $url);
        }
        // /1234.html
        elseif ($url[0] == '/') {
            $url = $domain . $url;
        }
        // ./1234.html、../1234.html 这种类型的
        elseif ($url[0] == '.') {
            if (!isset($url[2])) {
                return false;
            } else {
                $urls = explode('/', $url);
                foreach ($urls as $u) {
                    if ($u == '..') {
                        $path_step++;
                    }
                    // 遇到 ., 不知道为什么不直接写$u == '.', 貌似一样的
                    else if ($i < count($urls) - 1) {
                        $dstr .= $urls[$i] . '/';
                    } else {
                        $dstr .= $urls[$i];
                    }
                    $i++;
                }
                $urls = explode('/', $base_url_path);
                if (count($urls) <= $path_step) {
                    return false;
                } else {
                    $pstr = '';
                    for ($i = 0; $i < count($urls) - $path_step; $i++) {$pstr .= $urls[$i] . '/';}
                    $url = $pstr . $dstr;
                }
            }
        } else {
            if (strtolower(substr($url, 0, 7)) == 'http://') {
                $url    = preg_replace('#^http://#i', '', $url);
                $scheme = "http";
            } else if (strtolower(substr($url, 0, 8)) == 'https://') {
                $url    = preg_replace('#^https://#i', '', $url);
                $scheme = "https";
            } else {
                $arr = explode("/", $base_url_path);
                array_pop($arr);
                $base_url_path = implode("/", $arr);
                $url           = $base_url_path . '/' . $url;
            }
        }
        // 两个 / 或以上的替换成一个 /
        $url = preg_replace('@/{1,}@i', '/', $url);
        $url = $scheme . '://' . $url;

        $parse_url = @parse_url($url);
        $domain    = empty($parse_url['host']) ? $domain : $parse_url['host'];
        // 如果host不为空, 判断是不是要爬取的域名
        if (!empty($parse_url['host'])) {
            //排除非域名下的url以提高爬取速度
            if (strpos($url, $domainUrl) === false) {
                return false;
            }
        }

        return $url;
    }

    public function findNodeVal($dom, $xPath, $query) {
        $content = '';
        $node    = $xPath->query($query);
        if ($node->length > 0) {
            $element = $node[0];

            $nodeName = $element->nodeName;
            $nodeType = $element->nodeType; // 1.Element 2.Attribute 3.Text
            // 如果是img标签，直接取src值
            if ($nodeType == 1 && in_array($nodeName, array('img'))) {
                $content = $element->getAttribute('src');
            }
            // 如果是标签属性，直接取节点值
            elseif ($nodeType == 2 || $nodeType == 3 || $nodeType == 4) {
                $content = $element->nodeValue;
            } else {
                // 保留nodeValue里的html符号，给children二次提取
                $content = $dom->saveXml($element);
                //$content = trim(self::$dom->saveHtml($element));
                $content = preg_replace(array("#^<{$nodeName}.*>#isU", "#</{$nodeName}>$#isU"), array('', ''), $content);
            }
        }
        return $content;
    }
}
