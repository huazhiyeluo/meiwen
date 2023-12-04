<?= $this->include('common/header-css') ?>
<?= $this->include('common/header') ?>
<div class="container min-height">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                </div>
                <div class="col-md-8">
                    <div class="ts-other-menu">
                        <a class="<?=$tag == '' ? 's_select' : ''?>" href="<?=my_site_url('search') ?>">全部</a>
                        <?php foreach ($showType as $k => $v): ?>
                        |<a class="<?=$tag == $v['flag'] ? 's_select' : ''?>" href="<?=my_site_url('search/'.$v['flag']) ?>" title="<?=$v['title']?>"> <?=$v['title']?></a>
                        <?php endforeach?>
                    </div>
                    <div class="clearfix">
                        <form method="POST" action="<?=$url?>">
                            <div class="input-group w-auto">
                                <input class="form-control" name="keyword" value="<?=$keyword?>"  placeholder="请输入关键字">
                                <div class="input-group-append"><button class="btn btn-outline-info" type="submit"><i class="fa fa-search"></i>搜索</button></div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-2">
                </div>
            </div>
            <div class="row">
                <div class="col-md-1">

                </div>
                <div class="col-md-10">
                    <div class="ts-other-top">获得约 <span><?=(int)$total?></span> 条结果</div>
                    <hr />
                    <?php foreach ($list as $k => $v) {?>
                    <?php if(in_array($v['oid'], [2,3,4])){?>
                    <div class="ts-other-result">
                        <div class="content">
                            <div>
                                <span class="c9">[<?=getFirstName($v['oid'])?>]</span>&nbsp;<a href="<?=my_site_url($v['route_info']['route_name'])?>"><?=$v['route_info']['title']?></a> | <a href="<?=my_site_url($v['route_info']['route_name'], $v['id'])?>"><?=$v['title']?></a>
                            </div>
                            <div class="info">发表于 <?=date("Y-m-d",$v['addtime'])?> &nbsp;<a href="<?=my_site_url($v['route_info']['route_name'], $v['id'])?>"><?=$v['num']?> 阅读</a></div>
                        </div>
                    </div>
                    <?php }?>
                    <?php if(in_array($v['oid'], [1])){?>
                    <div class="ts-other-result">
                        <div class="content">
                            <div>
                                <span class="c9">[<?=getFirstName($v['oid'])?>]</span>&nbsp;<a href="<?=my_site_url($v['route_info']['route_name'])?>"><?=$v['route_info']['title']?></a> | <a href="<?=my_site_url($v['route_info']['route_name'], $v['id'])?>"><?=$v['title']?></a>
                            </div>
                            <div class="info">发表于 <?=date("Y-m-d",$v['addtime'])?> &nbsp;<a href="<?=my_site_url($v['route_info']['route_name'], $v['id'])?>"><?=$v['num']?> 阅读</a></div>
                        </div>
                    </div>
                    <?php }?>
                    <?php if(in_array($v['oid'], [5])){?>
                    <div class="ts-other-result">
                        <div class="content">
                            <div>
                                <span class="c9">[<?=getFirstName($v['oid'])?>]</span> &nbsp;<a href="<?=my_site_url($v['route_info']['route_name'])?>"><?=$v['route_info']['title']?></a> | <a href="<?=my_site_url($v['route_info']['route_name'], $v['b_id'])?>"><?=$v['book_info']['title']?></a> | <a href="<?=my_site_url($v['route_info']['route_name'], $v['b_id'] . '_' . $v['id'])?>"><?=$v['title']?></a>
                            </div>
                            <div class="info">更新于 <?=date("Y-m-d",$v['addtime'])?> &nbsp;<a href="<?=my_site_url($v['route_info']['route_name'], $v['b_id'] . '_' . $v['id'])?>"><?=$v['num']?> 阅读</a></div>
                        </div>
                    </div>
                    <?php }?>
                    <?php }?>
                    <div class="mt-3">
                        <?=$page?>
                    </div>
                </div>
                <div class="col-md-1">

                </div>
            </div>
        </div>
    </div>
</div>
<?=$this->include('common/footer')?>
<?=$this->include('common/footer-js')?>