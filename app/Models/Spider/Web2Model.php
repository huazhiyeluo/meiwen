<?php namespace App\Models\Spider;

use CodeIgniter\Model;

class Web2Model extends Model {
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

    public function setContent($t_id, $type, $url, $entryUrl) {
        echo $url;
        $spiderModel             = new SpiderModel();
        $categoryModel           = new \App\Models\CategoryModel();
        $html                    = $spiderModel->getHtmlContent($url);
        $dom                     = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->validateOnParse    = false;
        $dom->formatOutput       = false;
        $encode                  = mb_detect_encoding($html, array("ASCII", 'UTF-8', "GB2312", "GBK", 'BIG5'));
        $html                    = mb_convert_encoding($html, "UTF-8", $encode);
        $html                    = str_replace("gb2312", "UTF-8", $html);

        @$dom->loadHTML('<?xml version="1.0" encoding="UTF-8">' . $html);
        $xPath = new \DOMXPath($dom);

        $title     = $this->getTitle($spiderModel, $dom, $xPath);
        $categorys = $this->getCategory($spiderModel, $dom, $xPath);
        $contents  = $this->getContentAndCover($spiderModel, $dom, $xPath, $entryUrl);

        if ($title && $contents['content'] && $categorys['cat1']) {

            $insertData          = [];
            $insertData['title'] = $title;
            $insertData['cover'] = $contents['cover'];

            $links = $this->getPage($spiderModel, $dom, $xPath, $url);
            if ($links) {

                $contentArr    = $this->getPageContent($links, $entryUrl);
                $contentArr[1] = $contents['content'];
                ksort($contentArr);
                $insertData['content'] = $contentArr;
            } else {
                $insertData['content'] = $contents['content'];
            }

            $cids               = $categoryModel->loadCategory($categorys['cat1'], $categorys['cat2'], $type);
            $insertData['cid1'] = $cids['cid1'];
            $insertData['cid2'] = $cids['cid2'];

            $this->saveContent($type, $insertData);

            $spiderModel->editSpider($t_id, ['is_delete' => 1], ['url' => $url]);

            log_message('info', '---success---' . $url);
        } else {
            echo "---failure---";
            log_message('info', '---failure---' . $url);
        }
        echo "\n";
    }

    private function getCategory($spiderModel, $dom, $xPath) {
        $cat1 = $spiderModel->findNodeVal($dom, $xPath, "//div[contains(@class,'sitemap')]/a[2]");
        $cat2 = $spiderModel->findNodeVal($dom, $xPath, "//div[contains(@class,'sitemap')]/a[3]");
        return ['cat1' => $cat1, 'cat2' => trim($cat2)];
    }

    private function getTitle($spiderModel, $dom, $xPath) {
        $title = strip_tags($spiderModel->findNodeVal($dom, $xPath, "//div[contains(@class,'newsview')]/h1"));
        return trim($title);
    }

    private function getContentAndCover($spiderModel, $dom, $xPath, $entryUrl, $get_cover = 1) {
        $content = $spiderModel->findNodeVal($dom, $xPath, "//div[@id='artcont']/div[contains(@class,'news_con')]|//div[contains(@class,'news_con')]");
        $content = preg_replace("/<(\/?script.*?)>/si", "", $content);
        $content = preg_replace("/<a[^>]*>(.*?)<\/a>/is", "$1", $content);
        $content = preg_replace("/<p>[\s|\&nbsp\;| |\xc2\xa0|\t|\r|\n|\s(?=\s)]+/", "<p>", $content);
        $content = preg_replace("/<div>[\s|\&nbsp\;|　|\t|\r|\n|\s(?=\s)]+/", "<div>", $content);
        $content = str_replace(array('　', '#13;', '&', '<p></p>', '<div></div>', '<![CDATA[s("nr_c1");]]>'), array(' ', '', '', '', '', ''), $content);
        $content = str_replace('<br/><br/>', '<br/>', $content);
        $content = preg_replace('/style=".*?"/i', '', $content);
        $content = str_replace(array('<p><![CDATA[s("nr_c1");]]></p>', '<div align="center"/>'), array(' ', ''), $content);
        $content = preg_replace("/<div align=\"center\">(.*?)<\/div>/is", "", $content);
        $content = preg_replace('/src=\"\/(.*)\"/U', "src=\"$entryUrl$1\"", $content);

        preg_match_all('/<img.*src=\"(.*)\"/U', $content, $matches);

        $cover = '';
        if (isset($matches[1])) {
            $imageModel = new ImageModel();
            $imgpath    = $imageModel->getImage($matches[1], $get_cover);

            if ($imgpath['desImages']) {
                $content = str_replace($matches[1], $imgpath['desImages'], $content);
                $content = preg_replace("/<img src=\"\"([\s\S]*)\/>/U", "", $content);
                $content = str_replace(array('　', '#13;', '&', '<p></p>', '<div></div>'), array(' ', '', '', '', '', ''), $content);
            }
            $cover = $imgpath['cover'];
        }

        return ['cover' => $cover, 'content' => trim($content)];
    }

    private function getPage($spiderModel, $dom, $xPath, $url) {
        $pageHtml = $spiderModel->findNodeVal($dom, $xPath, "//div[@id='artcont']/div[contains(@class,'news_con')]/div[contains(@align,'center')]");
        preg_match_all("/<a.*href=[\"']{1}(.*)[\"']{0,1}[> \r\n\t]{1,}/isU", $pageHtml, $matchs);
        $links = $matchs[1];
        if ($links) {
            return $this->getPageFull($url, $links[count($links) - 1]);
        }
        return [];
    }

    private function getPageFull($baseurl, $url) {
        $urls  = explode('_', $url);
        $urls2 = explode('.', $urls[1]);
        $max   = $urls2[0];

        $temp    = [];
        $baseurl = rtrim($baseurl, '.html');
        foreach (range(2, $max) as $k => $v) {
            $temp[$v] = $baseurl . '_' . $v . '.html';
        }
        return $temp;
    }

    private function getPageContent($links, $entryUrl) {
        $contentArr = [];
        foreach ($links as $k => $url) {
            $spiderModel             = new SpiderModel();
            $categoryModel           = new \App\Models\CategoryModel();
            $html                    = $spiderModel->getHtmlContent($url);
            $dom                     = new \DOMDocument();
            $dom->preserveWhiteSpace = false;
            $dom->validateOnParse    = false;
            $dom->formatOutput       = false;
            $encode                  = mb_detect_encoding($html, array("ASCII", 'UTF-8', "GB2312", "GBK", 'BIG5'));
            $html                    = mb_convert_encoding($html, "UTF-8", $encode);
            $html                    = str_replace("gb2312", "UTF-8", $html);

            @$dom->loadHTML('<?xml version="1.0" encoding="UTF-8">' . $html);
            $xPath          = new \DOMXPath($dom);
            $contents       = $this->getContentAndCover($spiderModel, $dom, $xPath, $entryUrl, 0);
            $contentArr[$k] = $contents['content'];
        }
        return $contentArr;
    }

    private function saveContent($type, $data) {
        $articleModel = new \App\Models\ArticleModel();

        $articleData = [];

        $articleData['uid']      = rand(10001, 10100);
        $articleData['cid1']     = $data['cid1'];
        $articleData['cid2']     = $data['cid2'];
        $articleData['title']    = $data['title'];
        $articleData['cover']    = $data['cover'];
        $articleData['is_audit'] = 1;
        $articleData['addtime']  = time();

        if (is_array($data['content'])) {
            $articleData['is_mul_page'] = 1;
            $article_id                 = $articleModel->addArticle($type, $articleData);

            foreach ($data['content'] as $k => $v) {
                $contentData               = [];
                $contentData['article_id'] = $article_id;
                $contentData['page_num']   = $k;
                $contentData['content']    = $v;
                $articleModel->addArticleMulContent($type, $article_id, $contentData);
            }
        } else {

            $articleData['is_mul_page'] = 0;
            $article_id                 = $articleModel->addArticle($type, $articleData);

            $contentData               = [];
            $contentData['article_id'] = $article_id;
            $contentData['content']    = $data['content'];
            $articleModel->addArticleContent($type, $article_id, $contentData);
        }
    }

}
