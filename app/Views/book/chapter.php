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
              <h2><?=$book['title']?></h2>
              <div><?=$book['desc']?></div>
              <ul class="row chapter-list">
                  <?php foreach ($chapters as $k => $v): ?>
                  <li class="col-md-6">
                      <a href="<?=my_site_url($book['route_name'] . '/' . $book['book_id'] . '_' . $v['chapter_id'])?>"><?=$v['title']?></a>
                  </li>
                  <?php endforeach?>
              </ul>
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
