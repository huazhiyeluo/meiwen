<?= $this->include('common/header-css') ?>
<?= $this->include('common/header') ?>
<div class="container min-height">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?=site_url()?>">首页</a></li>
        <li class="breadcrumb-item active"><a href="<?=my_site_url('weibo')?>">树洞</a></li>
      </ol>
    </nav>

  <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                  <!-- Then put toasts within -->
                    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="2000" style="z-index: 100000;position: fixed;margin:auto;left:0;right: 0;margin-top: 2rem;">
                        <div class="toast-header">
                        <strong class="me-auto"><?=$baseConfig['sitename']?>网提示您！</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                        
                        </div>
                    </div>
                    <ul class="ts-weibo-show">
                        <li class="mbtl">
                            <a href="<?=my_site_url('user/' . $tuserInfo['uid'])?>">
                                <img class="rounded-circle" title="<?=$tuserInfo['username']?>" alt="<?=$tuserInfo['username']?>" src="<?=$tuserInfo['photo']?>" />
                            </a>
                        </li>
                        <li class="mbtr">
                            <div class="author">
                                <a class="ml-2" href="<?=my_site_url('user/' . $tuserInfo['uid'])?>" alt="<?=$tuserInfo['username']?>" title="<?=$tuserInfo['username']?>"><?=$tuserInfo['username']?></a>
                                <span class="fs12 c9"><?=date("Y-m-d H:i:s", $weibo['addtime'])?></span>
                            </div>
                            <div class="content ml-2">
                                <?=$weibo['content']?>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    回复(<?=$weibo['count_comment']?>)
                </div>
                <div class="card-body">
                    <ul class="ts-weibo-comment">
                        <?php foreach ($weiboComments as $k => $v) {?>
                        <li class="clearfix">
                            <div class="user-face">
                                <a href="<?=site_url('user/' . $v['uid'])?>">
                                    <img class="rounded-circle" title="<?=$v['username']?>" alt="<?=$v['username']?>" src="<?=$v['photo']?>"
                                    width="36" height="36">
                                </a>
                            </div>
                            <div class="reply-doc">
                                <h4><?=$v['addtime']?>  <a href="<?=my_site_url('user/' . $v['uid'])?>"><?=$v['username']?></a></h4>
                                <p><?=$v['content']?></p>
                            </div>
                        </li>
                        <?php }?>
                    </ul>
                    <?=$page?>
                    <form method="POST" id="form" enctype="multipart/form-data" novalidate="novalidate" class="mt-3">
                        <textarea class="form-control" rows="2" name="content"></textarea>
                        <div style="padding:5px 0;">
                            <input type="hidden" name="weibo_id" value="<?=$weibo['weibo_id']?>">
                            <button class="btn btn-sm btn-info ts-publish" type="button">回复</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    更多[<?=$tuserInfo['username']?>]的树洞
                </div>
                <div class="card-body">
                    <ul class="ts-weibo-list">
                        <?php foreach ($otherweibos as $k => $v) {?>
                        <li><a href="<?=my_site_url('weibo/' . $v['weibo_id'])?>"><?=$v['content']?></a></li>
                        <?php }?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="ts-facebox">
                        <div class="face">
                            <a href="<?=site_url('user/' . $tuserInfo['uid'])?>">
                                <img class="rounded-circle" title="<?=$tuserInfo['username']?>" alt="<?=$tuserInfo['username']?>" src="<?=$tuserInfo['photo']?>" width="36" height="36">
                            </a>
                        </div>
                        <div class="info">
                            <div>
                                <a href="<?=site_url('user/' . $tuserInfo['uid'])?>"><?=$tuserInfo['username']?></a>
                            </div>
                            <div>
                                <?php if(isset($userInfo['uid']) && $tuserInfo['uid'] != $userInfo['uid'] || !$userInfo){?>
                                    <?php if(isset($follow) && $follow){?>
                                    <a class="btn btn-outline-info btn-sm" href="javascript:void('0')" onclick="unfollow(<?=$tuserInfo['uid']?>);">取消关注</a>
                                    <?php }else{?>
                                    <a class="btn btn-outline-info btn-sm" href="javascript:void('0')" onclick="follow(<?=$tuserInfo['uid']?>);">关注</a>
                                    <?php }?>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
  </div>
</div>
<?=$this->include('common/footer')?>
<?=$this->include('common/footer-js')?>
<script type="text/javascript">
$(".ts-publish").click(function (){
    var content = $.trim($("textarea[name='content']").val());
    if(!content)
    {
        $(".toast-body").removeClass("text-success").addClass("text-danger").html('内容不允许为空');
        new bootstrap.Toast(document.querySelector('.toast')).show();
        return false;
    }
    var formData = new FormData($('#form')[0]);
    $.ajax({
        url: '<?=my_site_url('weibo/replydo');?>',
        type: 'POST',
        success: function (rs){
            if(rs.code == 100)
            {
                $(".toast-body").removeClass("text-success").addClass("text-danger").html(rs.msg);
                new bootstrap.Toast(document.querySelector('.toast')).show();
                document.querySelector('.toast').addEventListener('hidden.bs.toast', function () {
                  location.href = '<?=my_site_url('login')?>'
                })
            }else if(rs.code == 0)
            {
                $(".toast-body").removeClass("text-danger").addClass("text-success").html(rs.msg);
                new bootstrap.Toast(document.querySelector('.toast')).show();
                document.querySelector('.toast').addEventListener('hidden.bs.toast', function () {
                  location.reload()
                })
            }else{
                $(".toast-body").removeClass("text-success").addClass("text-danger").html(rs.msg);
                new bootstrap.Toast(document.querySelector('.toast')).show();
            }
        },
        // Form数据
        data: formData,
        cache: false,
        dataType: 'json',
        contentType: false,
        processData: false
    });
})
function follow(uid_follow){
    $.post("<?=my_site_url('user/detailsdo/follow');?>", { "uid_follow": uid_follow},
        function(rs){
            if(rs.code == 100)
            {
                $(".toast-body").removeClass("text-success").addClass("text-danger").html(rs.msg);
                new bootstrap.Toast(document.querySelector('.toast')).show();
                document.querySelector('.toast').addEventListener('hidden.bs.toast', function () {
                  location.href = '<?=my_site_url('login')?>'
                })
            }else if(rs.code == 0)
            {
                $(".toast-body").removeClass("text-danger").addClass("text-success").html(rs.msg);
                new bootstrap.Toast(document.querySelector('.toast')).show();
                document.querySelector('.toast').addEventListener('hidden.bs.toast', function () {
                  location.reload()
                })
            }else{
                $(".toast-body").removeClass("text-success").addClass("text-danger").html(rs.msg);
                new bootstrap.Toast(document.querySelector('.toast')).show();
            }
    }, "json");
}
function unfollow(uid_follow){
    $.post("<?=my_site_url('user/detailsdo/unfollow');?>", { "uid_follow": uid_follow},
        function(rs){
            if(rs.code == 100)
            {
                $(".toast-body").removeClass("text-success").addClass("text-danger").html(rs.msg);
                new bootstrap.Toast(document.querySelector('.toast')).show();
                document.querySelector('.toast').addEventListener('hidden.bs.toast', function () {
                  location.href = '<?=my_site_url('login')?>'
                })
            }else if(rs.code == 0)
            {
                $(".toast-body").removeClass("text-danger").addClass("text-success").html(rs.msg);
                new bootstrap.Toast(document.querySelector('.toast')).show();
                document.querySelector('.toast').addEventListener('hidden.bs.toast', function () {
                  location.reload()
                })
            }else{
                $(".toast-body").removeClass("text-success").addClass("text-danger").html(rs.msg);
                new bootstrap.Toast(document.querySelector('.toast')).show();
            }
    }, "json");
}
</script>
