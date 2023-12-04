<div class="col-md-8">
    <div class="card">
		<div class="card-body">
			<?php if (isset($userInfo['uid']) && $tuserInfo['uid'] != $userInfo['uid'] || !$userInfo) {?>
			<div class="ts-guest">
				<img class="rounded-circle" src="<?=site_url()?>static/image/user_large.png">
				<form method="POST" id="form_guestbook" enctype="multipart/form-data" novalidate="novalidate">
					<textarea class="form-control" name="content"></textarea>
					<input type="hidden" name="touid" value="<?=$tuserInfo['uid']?>">
					<div class="pd100">
						<button class="btn btn-sm btn-info ts-guestbook mt-2 mb-2" type="button">
							添加留言
						</button>
					</div>
				</form>
			</div>
			<div class="clear">
			</div>
			<?php }?>
			<!--回复-->
			<div id="reguest" style="display:none;">
				<form method="POST" id="form_guestbook_reply" enctype="multipart/form-data" novalidate="novalidate">
					<textarea class="form-control" name="content">
					</textarea>
					<input id="touserid" type="hidden" name="touid" value="0">
					<input id="reid" type="hidden" name="reid" value="0">
					<div class="pd100">
						<button class="btn btn-sm btn-info mt-2 mb-2 ts-guestbook-reply" type="button">回复</button>
						<a class="btn btn-sm btn-outline-secondary mt-2 mb-2" href="javascript:void('0')" onclick="reply()">取消</a>
					</div>
				</form>
			</div>
			<ul class="ts-glist">
				<?php foreach ($usergbs as $k => $v) {?>
				<li>
					<a href="<?=my_site_url('user/' . $v['uid'])?>">
						<img src="<?=$v['photo']?>"
						class="rounded-circle" width="36" height="36">
					</a>
					<div class="content">
						<p class="c9 fs12">
							<a href="<?=my_site_url('user/' . $v['uid'])?>"><?=$v['username']?></a> <?=$v['addtime']?>
						</p>
						<div>
							<?=$v['content']?>
						</div>
						<?php foreach ($v['child'] as $key => $val) {?>
						<div class="bg-light p-2 text-secondary rounded">
							回复：<?=$val['content']?>
						</div>
						<?php }?>
						<?php if (isset($userInfo['uid']) && $tuserInfo['uid'] != $userInfo['uid']) {?>
						<p class="text-right fs12">
							<a href="#reguest" onclick="reply('<?=$v['uid']?>','<?=$v['id']?>','<?=$v['username']?>')"><i class="fa fa-reply"></i> 回复</a>
                            <a class="ms-1" onclick="delReply('<?=$v['id']?>')"><i class="far fa-trash-alt"></i> 删除</a>
                        </p>
                        <?php }?>
						<!--回复留言-->
					</div>
				</li>
				<?php }?>
			</ul>
		</div>
    </div>
</div>