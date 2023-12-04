<nav class="navbar navbar-expand-lg navbar-dark bg-dark header">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php foreach ($topNavCategorys as $k => $v): ?>
                <li class="nav-item"><a class="nav-link" href="<?=my_site_url($v['route_name'])?>"><?=$v['title']?></a></li>
                <?php endforeach?>
                <li class="nav-item"><a class="nav-link" href="<?=my_site_url('sitemap')?>">站点地图</a></li>
            </ul>
            <form class="d-flex" role="search">
                <input class="form-control me-2 btn-sm" type="search" placeholder="搜索：<?=implode('|',array_column($showType,'title'))?>" id="searchContent">
                <button class="btn btn-outline-secondary btn-sm mr-2" type="button" onclick="search()"><i class="fa fa-search fa-fw"></i></button>
            </form>
        </div>
        <?php if(!$userInfo){ ?>
        <div class="float-end ms-2">
            <a href="<?=my_site_url('login')?>" class="btn btn-info btn-sm">登录</a>
            <a href="<?=my_site_url('register')?>" class="btn btn-success btn-sm ml-5">注册</a>
        </div>
        <?php }else{ ?>
        <div class="float-end ms-2">
            <a href="<?=my_site_url('user/' . $userInfo['uid'])?>" alt="<?=$userInfo['username']?>" title="<?=$userInfo['username']?>"><img class="header-photo" src="<?=$userInfo['photo']?>" alt="<?=$userInfo['username']?>" title="<?=$userInfo['username']?>" /><span class="header-username"><?=$userInfo['username']?></span></a>
            <a href="<?=my_site_url('logout')?>" class="btn btn-light btn-sm">退出</a>
        </div>
        <?php } ?>            
    </div>
</nav>
<div class="container-fluid ts-nav">
    <div class="container">
        <a <?=$controller == 'index' ? 'class="on"' : ''?>  href="<?=site_url()?>" title="<?=$baseConfig['sitename']?>">首页</a>
        <?php foreach ($showType as $k => $v): ?>
        <a <?=$controller == $v['flag'] ? 'class="on"' : ''?> href="<?=my_site_url($v['flag'])?>" title="<?=$v['title']?>"><?=$v['title']?></a>
        <?php endforeach?>
        <a <?=$controller == 'weibo' ? 'class="on"' : ''?> href="<?=my_site_url('weibo')?>" title="树洞">树洞</a>
    </div>
</div>