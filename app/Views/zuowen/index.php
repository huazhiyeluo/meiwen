<?= $this->include('common/header-css') ?>
<?= $this->include('common/header') ?>
<div class="container min-height">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?=site_url()?>">首页</a></li>
        <?php if ($breadcrumb): ?>
        <?php foreach ($breadcrumb as $k => $v): ?>
        <li class="breadcrumb-item active" aria-current="page"><a href="<?=$v['url']?>"><?=$v['title']?></a></li>
        <?php endforeach ?>
        <?php endif ?> 
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
              <ul class="page-article-list">
                <?php foreach ($list as $k => $v): ?>
                <?php if($v['cover']) { ?>
                <li class="row">
                    <div class="col-md-4">
                        <a href="<?=my_site_url($v['route_info']['route_name'], $v['article_id'])?>"><img class="ts-mw-100" src="<?=$v['cover']?>" alt="<?=$v['title']?>" title="<?=$v['title']?>"></a>
                    </div>
                    <div class="col-md-8">
                        <h2 class="h5">
                            <a class="title" href="<?=my_site_url($v['route_info']['route_name'], $v['article_id'])?>"><?=$v['title']?></a>
                        </h2>
                        <div class="fs12 text-black-50">
                            <a class="text-black-50 username" href="<?=my_site_url('user/' . $v['uid'])?>" alt="<?=$v['username']?>" title="<?=$v['username']?>"><?=$v['username']?></a> 发表于
                            <?=time_tran($v['addtime'])?>
                        </div>
                        <div class="text-black-50 gaiyao mt-2">
                        <?=$v['desc']?><a class="show-all" href="<?=my_site_url($v['route_info']['route_name'], $v['article_id'])?>" alt="<?=$v['title']?>" title="<?=$v['title']?>">[查看全文]</a>
                        </div>
                        <div class="text-muted mt-2 fs12">
                            <div class="float-start">分类：
                                <a class="cate-color" href="<?=my_site_url('nav',$v['route_info']['cid'])?>" alt="<?=$v['route_info']['title']?>" title="<?=$v['route_info']['title']?>"><?=$v['route_info']['title']?></a>
                            </div>
                            <div class="float-end">
                                <i class="fa fa-eye"></i><?=$v['count_view']?><i class="fa fa-comment ms-2"></i><?=$v['count_comment']?>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </li>
                <?php }else {?>
                <li>
                    <h2 class="h5">
                        <a class="title" href="<?=my_site_url($v['route_info']['route_name'], $v['article_id'])?>"><?=$v['title']?></a>
                    </h2>
                    <div class="fs12 text-black-50">
                        <a class="text-black-50 username" href="<?=my_site_url('user/' . $v['uid'])?>" alt="<?=$v['username']?>" title="<?=$v['username']?>"><?=$v['username']?></a> 发表于
                        <?=time_tran($v['addtime'])?>
                    </div>
                    <div class="text-black-50 gaiyao mt-2">
                        <?=$v['desc']?><a class="show-all" href="<?=my_site_url($v['route_info']['route_name'], $v['article_id'])?>" alt="<?=$v['title']?>" title="<?=$v['title']?>">[查看全文]</a>
                    </div>
                    <div class="text-muted mt-2">
                        <div class="float-start">分类：
                            <a class="cate" href="<?=my_site_url('nav',$v['route_info']['cid'])?>" alt="<?=$v['route_info']['title']?>" title="<?=$v['route_info']['title']?>"><?=$v['route_info']['title']?></a>
                        </div>
                        <div class="float-end">
                            <i class="fa fa-eye"></i><?=$v['count_view']?><i class="fa fa-comment ms-2"></i><?=$v['count_comment']?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </li>
                <?php }?>   
                <?php endforeach?>
              </ul>
              <?=$page?>
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
          <div class="card-header  pic-color pic-color-hot">
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
          <div class="card-header pic-color pic-color-new">
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
