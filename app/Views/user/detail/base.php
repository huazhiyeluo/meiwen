<div class="col-md-8">
    <div class="card">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-2">UID：</dt><dd class="col-sm-9 text-black-50"><?=$tuserInfo['uid']?></dd>
                <dt class="col-sm-2">性别：</dt>
                <dd class="col-sm-9 text-black-50"><?=$tuserInfo['gender'] == 1 ? '男' :($tuserInfo['gender'] ==2 ?  '女' :'未知') ?></dd>
                <dt class="col-sm-2">自我介绍：</dt>
                <dd class="col-sm-9 text-black-50"><?=$tuserInfo['about']?></dd>
                <dt class="col-sm-2">关注：</dt>
                <dd class="col-sm-9 text-black-50"><?=$tuserInfo['count_follow']?> 人</dd>
                <dt class="col-sm-2">粉丝：</dt>
                <dd class="col-sm-9 text-black-50"><?=$tuserInfo['count_followed']?> 人</dd>
            </dl>
        </div>
    </div>
</div>