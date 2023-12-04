<?=$this->include('common/header-css')?>
<?=$this->include('common/header')?>
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
                <form method="POST" id="form_weibo" enctype="multipart/form-data" novalidate="novalidate">
                    <textarea class="form-control" rows="2" id="content" name="content"></textarea>
                    <div style="padding:5px 0;">
                        <div class="fr">
                            <button class="btn btn-sm btn-info ts-publish" type="button">树洞一下</button>
                        </div>
                    </div>
                </form>
                <ul class="ts-weibo-show">
                    <?php foreach ($list as $k => $v) {?>
                    <a class="mbtl" href="<?=my_site_url('user/' . $v['uid'])?>">
                        <img class="rounded-circle" alt="<?=$v['username']?>" title="<?=$v['username']?>" src="<?=$v['photo']?>">
                    </a>
                    <li class="mbtr">
                        <div class="author">
                            <a class="ms-2" href="<?=my_site_url('user/' . $v['uid'])?>" alt="<?=$v['username']?>" title="<?=$v['username']?>"><?=$v['username']?></a>
                            <span class="fs12 c9">
                                <?=$v['addtime']?>
                            </span>
                        </div>
                        <div class="content ms-2">
                            <a href="<?=my_site_url('weibo/' . $v['weibo_id'])?>"><?=$v['content']?></a>
                        </div>
                        <p class="text-end">
                            <a href="<?=my_site_url('weibo/' . $v['weibo_id'])?>">(<?=$v['count_comment']?>)回复</a>
                        </p>
                    </li>
                    <?php }?>
                </ul>
                <?=$page?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">热门树洞</div>
            <div class="card-body">
                <ul class="ts-weibo-list">
                    <?php foreach ($hotweibos as $k => $v) {?>
                    <li>
                        <span class="portrait">
                            <a href="<?=my_site_url('user/' . $v['uid'])?>"><img align="absmiddle" class="SmallPortrait rounded-circle" title="<?=$v['username']?>" alt="<?=$v['username']?>" src="<?=$v['photo']?>">
                            </a>
                        </span>
                        <div class="body">
                            <span class="user"><a href="<?=my_site_url('user/' . $v['uid'])?>"><?=$v['username']?></a>：</span>
                            <span class="log"><a href="<?=my_site_url('weibo/' . $v['weibo_id'])?>"><?=$v['content']?></a></span>
                            <span class="time"><?=$v['addtime']?> (<a href="<?=my_site_url('weibo/' . $v['weibo_id'])?>"><?=$v['count_comment']?> 评</a>)</span>
                        </div>
                        <div class="clearfix"></div>
                    </li>
                    <?php }?>
                </ul>
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
        layer.msg('内容不允许为空', {icon: 5,time :2000});
        return false;
    }
    var formData = new FormData($('#form_weibo')[0]);
    $.ajax({
        url: '<?=my_site_url('weibo/createdo');?>',
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
</script>
