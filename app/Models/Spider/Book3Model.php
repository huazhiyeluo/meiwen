<?php namespace App\Models\Spider;

use CodeIgniter\Model;

class Book3Model extends Model {
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
        $cover     = $this->getCover($spiderModel, $dom, $xPath, $entryUrl);
        $desc      = $this->getDesc($spiderModel, $dom, $xPath);

        // $links = $this->getPage($spiderModel, $dom, $xPath, $entryUrl);
        // if ($links) {
        //     $this->getPageChapterContent(85, $links, $entryUrl);
        // }
        // exit;

        if ($title && $desc && $categorys['cat1']) {
            $insertData          = [];
            $insertData['title'] = $title;
            $insertData['cover'] = $cover;
            $insertData['desc']  = $desc;
            $cids                = $categoryModel->loadCategory($categorys['cat1'], $categorys['cat2'], $type);
            $insertData['cid1']  = $cids['cid1'];
            $insertData['cid2']  = $cids['cid2'];

            $book_id = $this->saveContent($insertData);

            $links = $this->getPage($spiderModel, $dom, $xPath, $entryUrl);
            if ($links) {
                $this->getPageChapterContent($book_id, $links, $entryUrl);
            }

            $spiderModel->editSpider($t_id, ['is_delete' => 1], ['url' => $url]);

            log_message('info', '---success---' . $url);
        } else {
            echo "---failure---";
            log_message('info', '---failure---' . $url);
        }
        echo "\n";
    }

    //-----------------------------------------book----------------------------------------

    private function getCategory($spiderModel, $dom, $xPath) {
        $cat1 = $spiderModel->findNodeVal($dom, $xPath, "//div[contains(@class,'place')]/span/a[2]");
        $cat2 = $spiderModel->findNodeVal($dom, $xPath, "//div[contains(@class,'place')]/span/a[3]");
        return ['cat1' => $cat1, 'cat2' => trim($cat2)];
    }

    private function getTitle($spiderModel, $dom, $xPath) {
        $title = strip_tags($spiderModel->findNodeVal($dom, $xPath, "//h2[contains(@class,'articleH22')]"));
        return trim($title);
    }

    private function getCover($spiderModel, $dom, $xPath, $entryUrl) {
        $cover = $spiderModel->findNodeVal($dom, $xPath, "//div[contains(@class,'bookDes')]/div[contains(@class,'ablum')]");
        $cover = preg_replace('/src=\"\/(.*)\"/U', "src=\"$entryUrl$1\"", $cover);
        preg_match_all('/<img.*src=\"(.*)\"/U', $cover, $matches);
        if (isset($matches[1])) {
            $imageModel = new ImageModel();
            $cover      = $imageModel->getImageCover($matches[1][0]);
        }
        return trim($cover);
    }

    private function getDesc($spiderModel, $dom, $xPath) {
        $desc = strip_tags($spiderModel->findNodeVal($dom, $xPath, "//div[contains(@class,'bookDes')]/div[contains(@class,'text')]"));
        $desc = str_replace(array('　', '#13;', '&', '<p></p>', '<div></div>', '<![CDATA[s("nr_c1");]]>'), array(' ', '', '', '', '', ''), $desc);
        return trim($desc);
    }

    private function saveContent($data) {
        $bookModel = new \App\Models\BookModel();

        $bookData = [];

        $bookData['uid']     = rand(10001, 10100);
        $bookData['cid1']    = $data['cid1'];
        $bookData['cid2']    = $data['cid2'];
        $bookData['title']   = $data['title'];
        $bookData['cover']   = $data['cover'];
        $bookData['desc']    = $data['desc'];
        $bookData['addtime'] = time();

        $book_id = $bookModel->addBook($bookData);
        return $book_id;
    }

    //-----------------------------------------book chapter----------------------------------------

    private function getChapterTitle($spiderModel, $dom, $xPath) {
        $title = strip_tags($spiderModel->findNodeVal($dom, $xPath, "//div[contains(@class,'articleCont')]/h1"));
        return trim($title);
    }

    private function getChapterContent($spiderModel, $dom, $xPath, $entryUrl, $get_cover = 1) {
        $content = $spiderModel->findNodeVal($dom, $xPath, "//div[contains(@class,'articleContent')]");
        $content = preg_replace("/<(\/?script.*?)>/si", "", $content);
        $content = preg_replace("/<a[^>]*>(.*?)<\/a>/is", "$1", $content);
        $content = preg_replace("/<p>[\s|\&nbsp\;| |\xc2\xa0|\t|\r|\n|\s(?=\s)]+/", "<p>", $content);
        $content = preg_replace("/<div>[\s|\&nbsp\;|　|\t|\r|\n|\s(?=\s)]+/", "<div>", $content);
        $content = preg_replace("/<b class=\"maintext\">(.*?)<\/b>/is", "", $content);
        $content = str_replace(array('　', '#13;', '&', '<p></p>', '<div></div>', '<![CDATA[s("nr_c1");]]>'), array(' ', '', '', '', '', ''), $content);
        $content = str_replace('<br/><br/>', '<br/>', $content);
        $content = preg_replace('/style=".*?"/i', '', $content);
        $content = str_replace(array('<p><![CDATA[s("nr_c1");]]></p>', '<div align="center"/>'), array(' ', ''), $content);
        $content = preg_replace('/src=\"\/(.*)\"/U', "src=\"$entryUrl$1\"", $content);

        preg_match_all('/<img.*src=\"(.*)\"/U', $content, $matches);
        if (isset($matches[1])) {
            $imageModel = new ImageModel();
            $imgpath    = $imageModel->getImage($matches[1], $get_cover);

            if ($imgpath['desImages']) {
                $content = str_replace($matches[1], $imgpath['desImages'], $content);
                $content = preg_replace("/<img src=\"\"([\s\S]*)\/>/U", "", $content);
                $content = str_replace(array('　', '#13;', '&', '<p></p>', '<div></div>'), array(' ', '', '', '', '', ''), $content);
            }
        }
        return trim($content);
    }

    private function getPageChapterContent($book_id, $links, $entryUrl) {
        foreach ($links as $k => $url) {
            $spiderModel             = new SpiderModel();
            $categoryModel           = new \App\Models\CategoryModel();
            $html                    = $spiderModel->getHtmlContent($url);
            $dom                     = new \DOMDocument();
            $dom->preserveWhiteSpace = false;
            $dom->validateOnParse    = false;
            $dom->formatOutput       = false;

            $encode = mb_detect_encoding($html, array("ASCII", 'UTF-8', "GB2312", "GBK", 'BIG5'));
            $html   = mb_convert_encoding($html, "UTF-8", $encode);
            $html   = str_replace("gb2312", "UTF-8", $html);

            @$dom->loadHTML('<?xml version="1.0" encoding="UTF-8">' . $html);
            $xPath   = new \DOMXPath($dom);
            $title   = $this->getChapterTitle($spiderModel, $dom, $xPath);
            $content = $this->getChapterContent($spiderModel, $dom, $xPath, $entryUrl, 0);

            $data               = [];
            $data['chapter_id'] = $k;
            $data['book_id']    = $book_id;
            $data['title']      = $title;
            $data['content']    = $content;
            $this->saveChapterContent($data);
        }
    }

    private function saveChapterContent($data) {
        $chapterModel = new \App\Models\ChapterModel();

        $chapterData               = [];
        $chapterData['chapter_id'] = $data['chapter_id'];
        $chapterData['book_id']    = $data['book_id'];
        $chapterData['title']      = $data['title'];
        $chapterData['addtime']    = time();

        $link_id = $chapterModel->addChapter($chapterData);

        $contentData            = [];
        $contentData['link_id'] = $link_id;
        $contentData['content'] = $data['content'];
        $chapterModel->addChapterContent($link_id, $contentData);

    }

    //-----------------------------------------common----------------------------------------

    private function getPage($spiderModel, $dom, $xPath, $url) {
        $pageHtml = $spiderModel->findNodeVal($dom, $xPath, "//div[contains(@class,'listcss')]/ul");
        preg_match_all("/<a.*href=[\"']{1}(.*)[\"']{0,1}[> \r\n\t]{1,}/isU", $pageHtml, $matchs);
        $links = $matchs[1];
        if ($links) {
            return $this->getPageFull($url, $links);
        }
        return [];
    }

    private function getPageFull($baseurl, $links) {
        $temp = [];
        foreach ($links as $k => $v) {
            $temp[$k + 1] = $baseurl . $v;
        }
        return $temp;
    }

}
