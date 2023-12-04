<div class="col-md-8">
	<div class="card">
		<div class="card-body">
            <ul class="ts-face-list">
                <?php foreach ($follows as $k => $v) {?>
                <li>
                    <a href="<?=my_site_url('user/'.$v['uid']) ?>">
                        <img class="rounded-circle" alt="<?=$v['username']?>" title="<?=$v['username']?>" src="<?=$v['photo']?>" width="48" height="48">
                    </a>
                    <div class="title-cut text-black-50 fs12 mt-2"><?=$v['username']?></div>
                </li>
                <?php }?>
            </ul>
		</div>
	</div>
</div>