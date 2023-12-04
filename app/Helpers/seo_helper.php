<?php



function getSeo($defaultSeo, $navs, $type, $cid)
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
        $sitename = $configProject->sitename;
        $weburl = $configProject->weburl;

        $navs = array_column($navs, 'title');

        if (!$title) {
            $title = $catename . '_' . $sitename . '网';
        }
        if (!$keywords) {
            $keywords = implode(',', $navs);
        }
        if (!$description) {
            $description = $sitename . '网(' . $weburl . ')中' . $catename . '栏目为您提供了' . implode('、', $navs) . '等各类精美文章,欢迎阅读浏览分享！';
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


function shareSeo($title = '', $keywords = '', $description = '', $photo = '')
{
    $configProject = config('Project');
    $seo = $configProject->seo;
    $baseConfig = $configProject->baseConfig;
    $shareInfo = ['title' => $seo['title'], 'keywords' => $seo['keywords'], 'description' => $seo['description'], 'photo' => $seo['photo']];
    if ($title) {
        $shareInfo['title'] = $title . '_' . $baseConfig['sitename'] . '网';
    }
    if ($keywords) {
        $shareInfo['keywords'] = $keywords;
    }
    if ($description) {
        $shareInfo['description'] = $description;
    }
    if ($photo) {
        $shareInfo['photo'] = $photo;
    }
    return $shareInfo;
}