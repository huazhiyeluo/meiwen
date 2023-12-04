<?= $this->include('common/header-css') ?>
<?= $this->include('common/header') ?>
<div class="container min-height">
    <nav aria-label="breadcrumb" class="position-relative">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?=site_url() ?>">首页</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$tuserInfo['username']?></li>
        </ol>
    </nav>
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
            <div class="media">
                <img class="mr-3 rounded-circle" src="<?=$tuserInfo['photo']?>" alt="<?=$tuserInfo['username']?>" title="<?=$tuserInfo['username']?>" width="56" height="56">
                <div class="media-body ms-3">
                    <h1 class="h5 fw400"><?=$tuserInfo['username']?>
                    </h1>
                    <div class="text-black-50">
                        <?=$tuserInfo['signed'] ? $tuserInfo['signed'] : '懒的都不写签名 '?>
                    </div>
                </div>
            </div>
            <?php if(isset($userInfo['uid']) && $tuserInfo['uid'] != $userInfo['uid'] || !$userInfo){?>
            <div class="mt-2 text-end ts-follow">
                <?php if(isset($follow) && $follow){?>
                <a class="btn btn-info btn-sm" href="javascript:void('0')" onclick="unfollow(<?=$tuserInfo['uid']?>);">取消关注</a>
                <?php }else{?>
                <a class="btn btn-info btn-sm" href="javascript:void('0')" onclick="follow(<?=$tuserInfo['uid']?>);">关注</a>
                <?php }?>
            </div>
            <?php }?>
            <div class="border-top pt-3 mt-3">
                <a class="btn btn-sm <?=$tag == 'base' ? 'btn-outline-secondary' : 'text-secondary';?>" href="<?=my_site_url('user/' . $tuserInfo['uid'] . '/base')?>">关于</a>
                <a class="btn btn-sm <?=$tag == 'meiwen' ? 'btn-outline-secondary' : 'text-secondary';?>" href="<?=my_site_url('user/' . $tuserInfo['uid'] . '/meiwen')?>">美文</a>
                <a class="btn btn-sm <?=$tag == 'gushi' ? 'btn-outline-secondary' : 'text-secondary';?>" href="<?=my_site_url('user/' . $tuserInfo['uid'] . '/gushi')?>">故事</a>
                <a class="btn btn-sm <?=$tag == 'zuowen' ? 'btn-outline-secondary' : 'text-secondary';?>" href="<?=my_site_url('user/' . $tuserInfo['uid'] . '/zuowen')?>">作文</a>
                <a class="btn btn-sm <?=$tag == 'guestbook' ? 'btn-outline-secondary' : 'text-secondary';?>" href="<?=my_site_url('user/' . $tuserInfo['uid'] . '/guestbook')?>">留言</a>
                <a class="btn btn-sm <?=$tag == 'followed' ? 'btn-outline-secondary' : 'text-secondary';?>" href="<?=my_site_url('user/' . $tuserInfo['uid'] . '/followed')?>">粉丝</a>
                <a class="btn btn-sm <?=$tag == 'follow' ? 'btn-outline-secondary' : 'text-secondary';?>" href="<?=my_site_url('user/' . $tuserInfo['uid'] . '/follow')?>">关注</a>
            </div>
        </div>
    </div>
    <div class="row">
        <?=view("user/detail/" . $tag) ?>
        <div class="col-md-4">

        </div>
    </div>
</div>

<?=$this->include('common/footer')?>
<?=$this->include('common/footer-js')?>

<script type="text/javascript">
  $(".ts-guestbook").click(function (){
    var content = $("#form_guestbook textarea[name='content']").val();
    if(!content)
    {
        $(".toast-body").removeClass("text-success").addClass("text-danger").html('内容不允许为空');
        new bootstrap.Toast(document.querySelector('.toast')).show();
        return false;
    }
    var formData = new FormData($('#form_guestbook')[0]);
    $.ajax({
        url: '<?=my_site_url('user/detailsdo/guestbook/add');?>', 
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
                  location.href = '<?=my_site_url('user/'.$tuserInfo['uid'].'/guestbook')?>'
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
  function reply(uid ,id , username)
  {
    $("#reguest").toggle();
    $("#touserid").val(uid)
    $("#reid").val(id)
    $("#reguest textarea[name='content']").val("@"+username+'#')
  }
  function delReply(id){
    $.post("<?=my_site_url('user/detailsdo/guestbook/del');?>", { "id": id},
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
                  location.href = '<?=my_site_url('user/'.$tuserInfo['uid'].'/guestbook')?>'
                })
            }else{
                $(".toast-body").removeClass("text-success").addClass("text-danger").html(rs.msg);
                new bootstrap.Toast(document.querySelector('.toast')).show();
            }
    }, "json");
  }
  $(".ts-guestbook-reply").click(function (){
    var content = $("#form_guestbook_reply textarea[name='content']").val();
    if(!content)
    {
        layer.msg('内容不允许为空', {icon: 5,time :2000});
        return false;
    }
    var formData = new FormData($('#form_guestbook_reply')[0]);
    $.ajax({
        url: '<?=my_site_url('user/detailsdo/guestbook/reply');?>', 
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
                  location.href = '<?=my_site_url('user/'.$tuserInfo['uid'].'/guestbook')?>'
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
