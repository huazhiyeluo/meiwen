<?=$this->include('common/header-css')?>
<?=$this->include('common/header')?>
<div class="container min-height">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?=site_url()?>">首页</a></li>
        <?php if ($breadcrumb): ?>
        <?php foreach ($breadcrumb as $k => $v): ?>
        <li class="breadcrumb-item  <?=isset($v['cl']) ? $v['cl'] : ''?>" aria-current="page"><a href="<?=$v['url']?>"><?=$v['title']?></a></li>
        <?php endforeach?>
        <?php endif?>
      </ol>
    </nav>
    <div class="mt-2">
        <?php foreach ($relationCategorys as $k => $v) {?>
        <a class="btn btn-sm text-secondary <?=$route_name == $v['route_name'] ? 'btn-outline-secondary' : ''?>" href="<?=my_site_url($v['route_name'])?>"><?=$v['title']?></a>
        <?php }?>
    </div>
  <div class="row">
    <div class="col-md-8">
        <div class="card">
          <div class="card-body">
            <h1 class="h4 pb-2 fw400"><?=$article['title']?></h1>
            <div class="author pb-2">
                <div class="avatar"><a href="<?=my_site_url('user/' . $article['uid'])?>"><img class="rounded-circle" src="<?=$article['photo']?>" alt="<?=$article['username']?>" title="<?=$article['username']?>" /></a></div>
                <div class="content">
                  <div class="title"><a href="<?=my_site_url('user/' . $article['uid'])?>"><?=$article['username']?></a></div>
                  <div class="info">发表于 <?=time_tran($article['addtime'])?></div>
                </div>
            </div>
            <div class="article_show_content lh30 common-content">
                <?=$article['content']?>
            </div>
            <?=$page?>
          </div>
      </div>
    </div>
    <div class="col-md-4">
        <div class="card ts-nav-list">
          <div class="card-header">
            热门导航
          </div>
          <div class="card-body">
                <div class="row">
                    <?php foreach ($hotCategorys as $k => $v): ?>
                    <div class="col-md-4 col-4">
                        <a class="text-black-50" href="<?=my_site_url($v['route_name'])?>"><?=$v['title']?></a>
                    </div>
                    <?php endforeach?>
                </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            热门<?=getFirstName($type)?>
          </div>
          <div class="card-body">
                <ul class="wenzhang-list">
                    <?php foreach ($hotArticles as $v): ?>
                    <li>
                        <a href="<?=my_site_url($v['route_info']['route_name'], $v['article_id'])?>"><?=$v['title']?></a>
                    </li>
                    <?php endforeach?>
                </ul>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            最新<?=getFirstName($type)?>
          </div>
          <div class="card-body">
                <ul class="wenzhang-list">
                    <?php foreach ($newArticles as $v): ?>
                    <li>
                        <a href="<?=my_site_url($v['route_info']['route_name'], $v['article_id'])?>"><?=$v['title']?></a>
                    </li>
                    <?php endforeach?>
                </ul>
          </div>
        </div>
    </div>
  </div>
</div>
<?=$this->include('common/footer')?>
<?=$this->include('common/footer-js')?>
