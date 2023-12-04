<?php

namespace App\Controllers;

use App\Models\ArticleModel;
use App\Models\CategoryModel;

class Repair extends BaseController
{

    //---------------------------------------------------分类处理---------------------------------------------------
    public function cate()
    {
        $categoryModel = new CategoryModel();
        $articleModel = new ArticleModel();
        $sql = "select * from (select type,route_name,count(*) as num from ts_category where pcid > 0 group by type,route_name) as a where num > 1 order by num desc;";
        $data = $categoryModel->getCategorysBySql($sql);

        foreach ($data as $v) {
            $type = $v['type'];
            $route_name = $v['route_name'];
            $sql = "select cid,pcid,type from ts_category where type = $type and route_name = '{$route_name}' order by pcid asc;";
            $temp = $categoryModel->getCategorysBySql($sql);

            $ucid = $temp[0]['cid'];
            $upcid = $temp[0]['pcid'];

            foreach ($temp as $tk => $tv) {
                if ($tk > 0) {
                    echo json_encode($temp[0]) . '<-' . json_encode($tv) . "\n";
                    $categoryModel->editCategory(['is_delete' => 1], ['cid' => $tv['cid'], 'pcid' => $tv['pcid']]);
                    $articleModel->editArticle($tv['type'], ['cid2' => $ucid, 'cid1' => $upcid], ['cid2' => $tv['cid'], 'cid1' => $tv['pcid']]);
                }
            }
        }
    }

    public function move()
    {
        $categoryModel = new CategoryModel();
        $articleModel = new ArticleModel();

        // $groups = [472, 474];

        // $srcType  = 3;
        // $moveType = 2;

        $groups = [134, 135, 136, 137, 726, 725, 707, 575, 611, 756, 765, 767];

        $srcType = 2;
        $moveType = 3;

        foreach ($groups as $k => $cid2) {
            $categoryModel->editCategory(['is_delete' => 1], ['cid' => $cid2]);

            $ccate = $categoryModel->getCategory(['where' => ['cid' => $cid2]]);
            $pcate = $categoryModel->getCategory(['where' => ['cid' => $ccate['pcid']]]);

            $newCates = $categoryModel->loadCategory($pcate['spider_title'], $ccate['spider_title'], $moveType);

            $article_ids = $articleModel->getArticles($srcType, ['where' => ['cid2' => $cid2]], 'article_id', 1, 10000);

            foreach ($article_ids as $v) {
                $article_id = $v['article_id'];
                $article = $articleModel->getArticle($srcType, ['where' => ['article_id' => $article_id]]);

                echo "{$article['article_id']}-{$article['title']}\n";
                unset($article['article_id']);
                $article['cid1'] = $newCates['cid1'];
                $article['cid2'] = $newCates['cid2'];
                $new_article_id = $articleModel->addArticle($moveType, $article);
                $articleModel->delArticle($srcType, ['article_id' => $article_id]);

                if ($article['is_mul_page'] == 1) {
                    $contents = $articleModel->getArticleContentMuls($srcType, $article_id);
                    foreach ($contents as $k => $v) {
                        $contentData = [];
                        $contentData['article_id'] = $new_article_id;
                        $contentData['page_num'] = $v['page_num'];
                        $contentData['content'] = $v['content'];
                        $articleModel->addArticleMulContent($moveType, $new_article_id, $contentData);
                        $articleModel->delArticleMulContent($srcType, $article_id);
                    }

                } else {
                    $content = $articleModel->getArticleContent($srcType, $article_id);
                    $contentData = [];
                    $contentData['article_id'] = $new_article_id;
                    $contentData['content'] = $content['content'];
                    $articleModel->addArticleContent($moveType, $new_article_id, $contentData);
                    $articleModel->delArticleContent($srcType, $article_id);
                }
            }

        }
    }

    public function repairArticle()
    {
        echo "start repair" . "\n";
        $articleModel = new ArticleModel();
        foreach ([2, 3, 4] as $type) {
            echo "start repair" . $type . "\n";
            $allCategorys = $this->getCategorysByType($type);
            for ($i = 1; $i <= 35; $i++) {
                $pageSize = 5000;
                $page = $i;
                $articles = $articleModel->getArticles($type, ['order' => ['article_id' => 'asc']], 'article_id,cid1,cid2', $page, $pageSize);
                foreach ($articles as $v) {
                    if ($v['cid2'] > 0 && $v['cid1'] == 0) {

                        if (isset($allCategorys['cCategorys'][$v['cid2']])) {
                            echo $v['article_id'] . "\n";
                            $category = $allCategorys['cCategorys'][$v['cid2']];
                            if ($category['pcid'] != 0) {
                                $articleModel->editArticle($type, ['cid1' => $category['pcid'], 'cid2' => $category['cid']], ['article_id' => $v['article_id']]);
                            } else {
                                $articleModel->editArticle($type, ['cid1' => $category['cid'], 'cid2' => 0], ['article_id' => $v['article_id']]);
                            }
                        }
                        if (isset($allCategorys['pCategorys'][$v['cid2']])) {
                            echo $v['article_id'] . "\n";
                            $category = $allCategorys['pCategorys'][$v['cid2']];
                            $articleModel->editArticle($type, ['cid1' => $category['cid'], 'cid2' => 0], ['article_id' => $v['article_id']]);
                        }
                    }
                }
            }
        }
    }

    //---------------------------------------------------文章修复---------------------------------------------------

    public function repairContent()
    {
        $articleModel = new ArticleModel();
        foreach (range(0, 149) as $v) {
            $sql = "update ts_guiaihai_zuowen.ts_content_$v set content = replace(content, 'ilovehai','simeiwen')";
            $articleModel->getBySql($sql);
        }

        foreach (range(0, 9) as $v) {
            $sql = "update ts_guiaihai_zuowen.ts_content_mul_$v set content = replace(content, 'ilovehai','simeiwen')";
            $articleModel->getBySql($sql);
        }
    }

    public function updateContent()
    {
        echo "start update" . "\n";
        $myImage = new \App\ThirdParty\MyImage();

        $configProject = config('Project');
        $fileConfig = $configProject->fileConfig;

        $filepath = $fileConfig['filepath'];
        $fileurl = $fileConfig['fileurl'];

        $pics = $this->getPics($filepath);

        $today = date("Ymd");
        $pattern = '/<img[^>]+>/'; // 匹配 <img> 标签及其属性

        $picIndex = 0;

        $articleModel = new ArticleModel();
        foreach ([2] as $type) {
            echo "start update" . $type . "\n";
            for ($i = 1; $i <= 30; $i++) {
                $pageSize = 5000;
                $page = $i;
                $articles = $articleModel->getArticles($type, ['where' => ['article_id >' => 0],'order' => ['article_id' => 'desc']], 'article_id,title', $page, $pageSize);
                foreach ($articles as $v) {
                    $temp = $articleModel->getArticleContent($type, $v['article_id']);

                    
                    $temp['content'] = preg_replace($pattern, "", $temp['content']);

                    if (strpos($temp['content'], '<img') !== false) {
                        continue;
                    }

                    if (!isset($pics[$picIndex])){
                        echo "no image " . $type . "\n";
                        break;
                    }

                    echo $v['article_id']."\n";

                    $srcpic = $pics[$picIndex];
                    $fix = pathinfo($srcpic, PATHINFO_EXTENSION);
                    $path = $filepath . $today . '/';
                    if (!file_exists($path) || !is_dir($path)) {
                        mkdir($path, 0777, true);
                    }
                    $writeFile = md5(time() . $srcpic) . '.' . $fix;
                    $despic = $path . $writeFile;
                    $newimage = $fileurl . $today . '/' . $writeFile;

                    $imageTag = '<img src="' . $newimage . '" alt="' . $v['title'] . '" title="' . $v['title'] . '">';

                    $firstPend = strpos($temp['content'], '</p>');
                    $firstDend = strpos($temp['content'], '</div>');

                    if ($firstPend !== false) {
                        $content = substr_replace($temp['content'], $imageTag, $firstPend + 4, 0);
                    } elseif ($firstDend !== false) {
                        $content = substr_replace($temp['content'], $imageTag, $firstPend + 4, 0);
                    } else {
                        $content = $temp['content'] . $imageTag;
                    }

                    $rand = rand(1,10);
                    if ($v['article_id'] % $rand == 0) {
                        $articleModel->editArticleContent($type, $v['article_id'], ['content' => $content], ['article_id' => $v['article_id']]);
                        if ($picIndex % 2 == 0) {
                            $articleModel->editArticle($type, ['cover' => $newimage], ['article_id' => $v['article_id']]);
                        }
                        rename($srcpic, $despic);
                        $picIndex++;
                    }
                }
            }
        }
    }

    public function getPics($filepath)
    {
        $src = dirname($filepath) . '/ilh/20200322';
        $files = glob($src . '/*');
        $pics = [];
        foreach ($files as $file) {
            if (is_file($file)) {
                list($width, $height, $type, $attr) = @getimagesize($file);

                if ($width >= 500 && $height >= 400) {
                    $pics[] = $file;
                }
            }
        }
        return $pics;
    }
}
