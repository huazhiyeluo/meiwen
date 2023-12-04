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
        <a class="btn btn-sm text-secondary <?=$route_name == $v['route_name'] ? 'btn-outline-secondary' : ''?>" href="<?=my_site_url($v['route_name'])?>" title="<?=$v['title']?>"><?=$v['title']?></a>
        <?php }?>
    </div>
  <div class="row">
    <div class="col-md-8">
        <div class="card">
          <div class="card-body">
            <h1 class="h4 pb-2 fw400"><?=$chapter['title']?></h1>
            <div class="author pb-2">
                <div class="avatar"><img class="rounded-circle" src="<?=$chapter['photo']?>" alt="<?=$chapter['username']?>" title="<?=$chapter['username']?>" /></div>
                <div class="content">
                  <div class="title"><?=$chapter['username']?></div>
                  <div class="info">发表于 <?=time_tran($chapter['addtime'])?></div>
                </div>
            </div>
            <div class="article_show_content lh30 common-content">
                <?=$chapter['content']?>
            </div>
            <div class="text-center">
              <ul>
                    <li>
                      <?php if($prevChapter){?>
                      <a class="text-danger" href="<?=my_site_url($chapter['route_name'], $chapter['book_id'] . '_' . $prevChapter['chapter_id'])?>" alt="<?=$prevChapter['title']?>" title="<?=$prevChapter['title']?>">【上一页】</a>
                      <?php }else{ ?>
                      <a class="text-danger" href="###">【没有了】</a>
                      <?php } ?>
                      <a class="text-danger" href="<?=my_site_url($chapter['route_name'], $chapter['book_id'])?>">【回目录】</a>
                      <?php if($nextChapter){?>
                      <a class="text-danger" href="<?=my_site_url($chapter['route_name'], $chapter['book_id'] . '_' . $nextChapter['chapter_id'])?>" alt="<?=$nextChapter['title']?>" title="<?=$nextChapter['title']?>">【下一页】</a>
                      <?php }else{ ?>
                      <a class="text-danger" href="###">【没有了】</a>
                      <?php } ?>
                   </li>
              </ul>
            </div>
          </div>
      </div>
    </div>
    <div class="col-md-4">
        <div class="card ts-nav-list">
          <div class="card-header pic-color pic-color-rec">
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
          <div class="card-header pic-color pic-color-hot">
            热门图书
          </div>
          <div class="card-body">
                <div class="chapter-list-sidebar">
                    <?php foreach ($newArticles as $k => $v): ?>
                    <div>
                        <a class="avatar" href="<?=my_site_url($v['route_info']['route_name'], $v['book_id'])?>"><img src="<?=$v['cover']?>" alt="<?=$v['title']?>" title="<?=$v['title']?>" /></a>
                        <a class="title" href="<?=my_site_url($v['route_info']['route_name'], $v['book_id'])?>"><?=$v['title']?></a>
                    </div>
                    <?php endforeach?>
                </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header pic-color pic-color-new">
            最新图书
          </div>
          <div class="card-body">
                <div class="chapter-list-sidebar">
                    <?php foreach ($newArticles as $k => $v): ?>
                    <div>
                        <a class="avatar" href="<?=my_site_url($v['route_info']['route_name'], $v['book_id'])?>"><img src="<?=$v['cover']?>" alt="<?=$v['title']?>" title="<?=$v['title']?>" /></a>
                        <a class="title" href="<?=my_site_url($v['route_info']['route_name'], $v['book_id'])?>"><?=$v['title']?></a>
                    </div>
                    <?php endforeach?>
                </div>
          </div>
        </div>
    </div>
  </div>
</div>
<?=$this->include('common/footer')?>
<?=$this->include('common/footer-js')?>
