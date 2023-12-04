<?php namespace Config;

use CodeIgniter\Config\BaseConfig;

class Project extends BaseConfig
{

    public array $baseConfig = [
        'sitename' => '爱嗨',
        'contact' => '微信：huazhiyeluo',
        'weburl' => 'https://www.guiaihai.com',
        'beian' => '赣ICP备15003370号-8',
        'version' => 202003312313,
        'theme' => 'default',
    ];

    public array $showType = [
        ['type' => 1, 'flag' => 'book', 'title' => '图书', 'is_show'=>1],
        ['type' => 2, 'flag' => 'meiwen', 'title' => '美文', 'is_show'=>1],
        ['type' => 3, 'flag' => 'gushi', 'title' => '故事', 'is_show'=>1],
        ['type' => 4, 'flag' => 'zuowen', 'title' => '作文', 'is_show'=>1],
    ]; //显示项目 1、图示 2、美文 3、故事 4、作文

    public $seo = [
        'title' => '爱嗨网_美文_故事_作文_图书_树洞',
        'keywords' => '爱嗨网,美文,故事,作文,图书,树洞',
        'description' => '爱嗨网(www.guiaihai.com)为您提供了美文、故事、作文、图书、树洞等各类精美文章,欢迎阅读浏览分享！|在线作文投稿！',
        'photo' => 'https://www.guiaihai.com/logo.png',
    ];

    public array $fileConfig = [
        'text' => '爱嗨',
        'fontpath' => FCPATH . 'static/fonts/simhei.ttf',
        'filepath' =>  ENVIRONMENT == 'production' ? '/data/wwwroot/img.simeiwen.com/gah/' : '/data/mywww/img.simeiwen.com/gah/',
        'fileurl' => 'http://img.simeiwen.com/gah/',
    ];

    public array $thirdLoginConfig = [
        'qq' => [
            'appId' => 102010518,
            'appKey' => 'kNp33oKbhZDdpnZ7',
            'returnUrl' => 'https://www.guiaihai.com/loginreturn/qq.html',
        ],
        'baidu' => [
            'appId'     => '7kKiWYLb9hSAj5aX5IojlGDi',
            'appKey'    => '36apBl5hhzsHGPMmGFBLemGCixpEcR7G',
            'returnUrl' => 'https://www.guiaihai.com/loginreturn/baidu.html',
        ],
        'weibo' => [
            'appId'     => 4257280620,
            'appKey'    => '87f04da3a4e3fa265b2a7b2a7093767a',
            'returnUrl' => 'https://www.guiaihai.com/loginreturn/weibo.html',
        ],
    ];

    public string $tongji = <<<HTML
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?e783fa799b57c6244e2c503a977e3abd";
  var s = document.getElementsByTagName("script")[0];
  s.parentNode.insertBefore(hm, s);
})();
</script>
HTML;

}
