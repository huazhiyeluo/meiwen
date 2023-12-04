<div class="col-md-8">
    <div class="card">
		<div class="card-body">
			<ul class="ts-topic-list mb-2">
			<?php foreach ($list as $k => $v) {?>
            <li>
                <h2 class="h5">
                    <a href="<?=my_site_url($v['route_info']['route_name'], $v['article_id'])?>"></a>
                </h2>
                <div class="text-black-50 gaiyao mt-2">
                    <?=$v['desc']?>
                    (<a href="<?=my_site_url($v['route_info']['route_name'], $v['article_id'])?>" alt="<?=$v['route_info']['title']?>" title="<?=$v['route_info']['title']?>">查看全文</a>)
                </div>
                <div class="text-muted mt-2 fs12">

                    <div class="float-left">分类：
                        <a href="<?=my_site_url($v['route_info']['route_name'])?>" alt="<?=$v['route_info']['title']?>" title="<?=$v['route_info']['title']?>"><?=$v['route_info']['title']?></a>
                    </div>
                    <div class="float-end">
                        <i class="fa fa-eye"></i><?=$v['count_view']?><i class="fa fa-comment ms-2"></i><?=$v['count_comment']?>
                    </div>
                </div>
                <div class="clearfix"></div>
            </li>
			<?php }?>
			</ul>
            <?=$page?>
		</div>
    </div>
</div>