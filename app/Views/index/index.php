<?=$this->include('common/header-css')?>
<?=$this->include('common/header')?>
<div class="container min-height">
  <div class="row">
    <div class="col-md-8">
        <?php if (isset($coverArticles) && count($coverArticles) > 0){ ?>
        <!-- 轮播 -->
        <div id="demo" class="carousel slide card" data-bs-ride="carousel">
          <!-- 指示符 -->
          <div class="carousel-indicators">
            <?php foreach ($coverArticles as $k => $v): ?>
            <button type="button" data-bs-target="#demo" data-bs-slide-to="<?=$k?>" <?php echo $k == 0 ? 'class="active"' : '' ?>></button>
            <?php endforeach?>
          </div>
          <!-- 轮播图片 -->
          <div class="carousel-inner">
            <?php foreach ($coverArticles as $k => $v): ?>
              <div class="carousel-item <?php echo $k == 0 ? 'active' : '' ?>">
                <a href="<?=my_site_url($v['route_info']['route_name'], $v['article_id'])?>"><img decoding="async" src="<?=$v['cover']?>"  alt="<?=$v['title']?>" title="<?=$v['title']?>" class="d-block" style="width:100%;max-height:360px;" />
                <div class="carousel-caption">
                  <h3><?=$v['title']?></h3>
                </div>
                </a>
              </div>
            <?php endforeach?>
          </div>
          <!-- 左右切换按钮 -->
          <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
          </button>
        </div>
        <?php } ?>

        <?php if(isset($recBooks)){ ?>
        <div class="card">
          <div class="card-header pic-color pic-color-new">
            推荐图书
            <small class="float-end"><a class="text-black-50" href="<?=my_site_url('book')?>">更多</a></small>
          </div>
          <div class="card-body">
                <div class="row">
                    <?php foreach ($recBooks as $k => $v): ?>
                    <div class="col-md-6">
                        <div class="rec-book-list">
                            <div class="avatar"><a href="<?=my_site_url($v['route_info']['route_name'], $v['book_id'])?>"><img class="rounded" src="<?=$v['cover']?>" alt="<?=$v['title']?>" title="<?=$v['title']?>"></a></div>
                            <div class="content">
                              <h3 class="title"><a href="<?=my_site_url($v['route_info']['route_name'], $v['book_id'])?>"><?=$v['title']?></a></h3>
                              <div class="info"><a href="<?=my_site_url($v['route_info']['route_name'], $v['book_id'])?>"><?=$v['desc']?></a></div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach?>
                </div>
          </div>
        </div>
        <?php } ?>

        <?php 
        $i = 0;
        foreach ($list as $cid => $v){ 
        ?>
        <div class="card">
          <div class="card-header pic-color pic-color-<?=$i%2==0 ? 'hot':'new'?>">
            <?=$v['category']['title']?>
            <small class="float-end"><a class="text-black-50" href="<?=my_site_url($v['category']['route_name'])?>">更多</a></small>
          </div>
          <div class="card-body">
                <ul class="row wenzhang-list">
                    <?php foreach ($v['articles'] as $key => $val): ?>
                    <li class="col-md-6">
                        <a href="<?=my_site_url($val['route_info']['route_name'], $val['article_id'])?>"><?=$val['title']?></a>
                    </li>
                    <?php endforeach?>
                </ul>
            </ul>
          </div>
        </div>
        <?php 
        $i++;
        }
        ?>

        <div class="card ts-nav-list">
          <div class="card-header pic-color pic-color-rec">
            推荐栏目
            <small class="float-end"><a class="text-black-50" href="<?=my_site_url('sitemap')?>">更多</a></small>
          </div>
          <div class="card-body">
                <div class="row">
                    <?php foreach ($recCategorys as $k => $v): ?>
                    <div class="col-sm-2 col-md-2 col-4">
                    <a href="<?=my_site_url($v['route_name'])?>"><?=$v['title']?></a>
                    </div>
                    <?php endforeach?>
                </div>
          </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
          <div class="card-header pic-color pic-color-rec">
            热门导航
          </div>
          <div class="card-body ts-nav-list">
                <div class="row">
                    <?php foreach ($hotCategorys as $k => $v): ?>
                    <div class="col-md-4 col-4">
                      <a class="text-black-50" href="<?=my_site_url($v['route_name'])?>"><?=$v['title']?></a>
                    </div>
                    <?php endforeach?>
                </div>
          </div>
        </div>
        <?php foreach ($hotArticles as $type => $val): ?>
        <?php if (count($val) > 0){ ?>
        <div class="card">
          <div class="card-header pic-color pic-color-hot">
            热门<?=getFirstName($type)?>
          </div>
          <div class="card-body">
                <ul class="wenzhang-list">
                    <?php foreach ($val as $v): ?>
                    <li>
                    <a href="<?=my_site_url($v['route_info']['route_name'], $v['article_id'])?>"><?=$v['title']?></a>
                    </li>
                    <?php endforeach?>
                </ul>
          </div>
        </div>
        <?php } ?>
        <?php endforeach?>
        <?php foreach ($newArticles as $type => $val): ?>
        <?php if (count($val) > 0){ ?>
        <div class="card">
          <div class="card-header pic-color pic-color-new">
            最新<?=getFirstName($type)?>
          </div>
          <div class="card-body">
                <ul class="wenzhang-list">
                    <?php foreach ($val as $v): ?>
                    <li>
                        <a href="<?=my_site_url($v['route_info']['route_name'], $v['article_id'])?>"><?=$v['title']?></a>
                    </li>
                    <?php endforeach?>
                </ul>
          </div>
        </div>
        <?php } ?>
        <?php endforeach?>
    </div>
  </div>
  <div class="card">
      <div class="card-header pic-color pic-color-new">友情链接<small class="float-end">广告|友链|泛目录|网站|爬虫 <a target="_blank" rel="nofollow" href="http://wpa.qq.com/msgrd?v=3&uin=370838500&site=qq&menu=yes"><img border="0" src="<?=site_url()?>static/image/qq.png" alt="<?=$baseConfig['sitename']?>网" title="<?=$baseConfig['sitename']?>网"/></a></small></div>
      <div class="card-body">
          <a class="fs13 mr-3" target="_blank" href="<?=my_site_url('sitemap')?>">站点地图</a>
          <?php if ($friendchain): ?>
          <?php foreach ($friendchain as $k => $v): ?>
            <a class="fs13 mr-3" target="_blank" href="<?=$v['url']?>" alt="<?=$v['keywords']?>"><?=$v['sitename']?></a>
          <?php endforeach?>
          <?php endif?>
      </div>
  </div>
</div>

<?=$this->include('common/footer')?>
<?=$this->include('common/footer-js')?>
