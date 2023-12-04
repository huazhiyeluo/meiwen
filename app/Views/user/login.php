<?=$this->include('common/header-css')?>
<?=$this->include('common/header')?>
<div class="container min-height">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
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
                    <div class="fs24">用户登录</div>
                    <hr>
                    <form novalidate id="form_login">
                        <div class="mb-3">
                            <label class="form-label" for="inputAccount">账号</label>
                            <input class="form-control" type="text" id="inputAccount" name="account"  aria-describedby="emailHelp" placeholder="请输入邮箱/手机号">
                            <div class="valid-feedback"></div>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="inputPassword">密码</label>
                            <input class="form-control" type="password" id="inputPassword" name="password" placeholder="请输入密码">
                            <div class="valid-feedback"></div>
                            <div class="invalid-feedback"></div>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm btn-block account-login">登录</button>  <a class="ms-2" href="<?=my_site_url('register')?>">还没有注册？</a> |
                        <a href="<?=my_site_url('forgotpasswd')?>">忘记密码？</a>
                    </form>
                    <div class="lh30 mt-3">
                        <a href="<?=my_site_url('logindo/qq')?>"><img src="<?=site_url()?>static/image/login_qq.png"></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>
<?=$this->include('common/footer')?>
<?=$this->include('common/footer-js')?>
<script type="text/javascript">
    $(".account-login").click(function () {
        var flag = false;
        var pemail = /^\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14}$/g;
        var pphone = /^0?(13|14|15|17|18|19)[0-9]{9}$/g;
        if (!pemail.test($("#inputAccount").val()) && !pphone.test($("#inputAccount").val())) {
            $("#inputAccount").parent().find("div.invalid-feedback").html("邮箱或手机号码格式错误!");
            $("#inputAccount").addClass("is-invalid");
            $("#inputAccount").removeClass("is-valid");
            flag = true;
        } else {
            $("#inputAccount").removeClass("is-invalid");
        }

        var ppassword = /^[A-Za-z0-9_]{6,12}$/g;
        if (!ppassword.test($("#inputPassword").val())) {
            $("#inputPassword").parent().find("div.invalid-feedback").html("密码只能包含A-Za-z0-9_,且6至12位长度！");
            $("#inputPassword").addClass("is-invalid");
            $("#inputPassword").removeClass("is-valid");
            flag = true;
        } else {
            $("#inputPassword").removeClass("is-invalid");
        }

        if (flag) {
            return false;
        }
        var jsonData = $('#form_login').serializeArray();

        $.post("<?=my_site_url('logindoweb');?>", jsonData,
            function (rs) {
                if (rs.code == 0) {
                    $(".toast-body").removeClass("text-danger").addClass("text-success").html(rs.msg);
                    new bootstrap.Toast(document.querySelector('.toast')).show();
                    document.querySelector('.toast').addEventListener('hidden.bs.toast', function () {
                      location.href = rs.prev_url
                    })
                } else if (rs.code == 1 || rs.code == 3) {
                    $("#inputAccount").parent().find("div.invalid-feedback").html(rs.msg);
                    $("#inputAccount").addClass("is-invalid");
                    $("#inputAccount").removeClass("is-valid");

                } else if (rs.code == 2 || rs.code == 4) {
                    $("#inputPassword").parent().find("div.invalid-feedback").html(rs.msg);
                    $("#inputPassword").addClass("is-invalid");
                    $("#inputPassword").removeClass("is-valid");
                }
            }, 'json');
    });

</script>