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

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                      <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#phone" type="button" role="tab" aria-controls="phone" aria-selected="true"><h6>手机号注册</h6></button>
                      </li>
                      <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#email" type="button" role="tab" aria-controls="email" aria-selected="false"><h6>邮箱注册</h6></button>
                      </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="phone">
                            <form class="needs-validation" novalidate id="form_phone">
                                <div class="mb-3 mt-3">
                                    <label class="form-label" for="phoneEmail">手机号码</label>
                                    <input class="form-control" type="phone" id="phonePhone" name="phone" aria-describedby="phoneHelp" placeholder="请输入手机号码">
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="phonePassword">密码</label>
                                    <input class="form-control" type="password" id="phonePassword" name="password" placeholder="请输入密码">
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="phoneRepassword">确认密码</label>
                                    <input class="form-control" type="password" id="phoneRepassword" name="repassword" placeholder="请输入确认密码">
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="phoneUsername">用户名</label>
                                    <input class="form-control" type="phone" id="phoneUsername" name="username" placeholder="请输入用户名">
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <button type="button" class="btn btn-primary btn-sm btn-block phone-register">注册</button> <a class="ms-2" href="<?=my_site_url('login')?>">已有账号，去登录？</a>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="email">
                            <form class="needs-validation" novalidate id="form_email">
                                <div class="mb-3  mt-3">
                                    <label class="form-label" for="emailEmail">邮箱</label>
                                    <input class="form-control" type="email" id="emailEmail" name="email" aria-describedby="emailHelp" placeholder="请输入邮箱">
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="emailPassword">密码</label>
                                    <input class="form-control" type="password" id="emailPassword" name="password" placeholder="请输入密码">
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="emailRepassword">确认密码</label>
                                    <input class="form-control" type="password" id="emailRepassword" name="repassword" placeholder="请输入确认密码">
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="emailUsername">用户名</label>
                                    <input class="form-control" type="email" id="emailUsername" name="username"  placeholder="请输入用户名">
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <button type="button" class="btn btn-primary btn-sm btn-block email-register">注册</button> <a class="ms-2" href="<?=my_site_url('login')?>">已有账号，去登录？</a>
                            </form>
                        </div>
                    </div>
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

    $("#emailEmail").bind("input propertychange", function () {
        var pemail = /^\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14}$/g;
        if (!pemail.test($(this).val())) {
            $(this).parent().find("div.invalid-feedback").html("邮箱输入错误!");
            $(this).addClass("is-invalid");
            $(this).removeClass("is-valid");
        } else {
            $(this).parent().find("div.valid-feedback").html("邮箱输入正确!");
            $(this).addClass("is-valid");
            $(this).removeClass("is-invalid");
        }
    })
    $("#emailPassword").bind("input propertychange", function () {
        var ppassword = /^[A-Za-z0-9_]{6,12}$/g;
        if (!ppassword.test($(this).val())) {
            $(this).parent().find("div.invalid-feedback").html("密码不合法，密码只能包含A-Za-z0-9_且必须大于6位！");
            $(this).addClass("is-invalid");
            $(this).removeClass("is-valid");
        } else {
            $(this).parent().find("div.valid-feedback").html("密码格式输入正确！");
            $(this).addClass("is-valid");
            $(this).removeClass("is-invalid");
        }
    })
    $("#emailRepassword").bind("input propertychange", function () {
        if ($(this).val() !== $("#emailPassword").val()) {
            $(this).parent().find("div.invalid-feedback").html("确认密码必须和密码一致");
            $(this).addClass("is-invalid");
            $(this).removeClass("is-valid");
        } else {
            $(this).parent().find("div.valid-feedback").html("确认密码输入正确！");
            $(this).addClass("is-valid");
            $(this).removeClass("is-invalid");
        }
    })
    $("#emailUsername").bind("input propertychange", function () {
        var pusername = /^[A-Za-z0-9_\-\u4e00-\u9fa5]{2,12}$/g;
        if (!pusername.test($(this).val())) {
            $(this).parent().find("div.invalid-feedback").html("用户名不合法，用户名只能包含A-Za-z0-9_及汉字！");
            $(this).addClass("is-invalid");
            $(this).removeClass("is-valid");
        } else {
            $(this).parent().find("div.valid-feedback").html("用户名输入正确!");
            $(this).addClass("is-valid");
            $(this).removeClass("is-invalid");
        }
    })

    $(".email-register").click(function () {
        var flag = false;
        var pemail = /^\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14}$/g;
        if (!pemail.test($("#emailEmail").val())) {
            $("#emailEmail").parent().find("div.invalid-feedback").html("邮箱格式错误!");
            $("#emailEmail").addClass("is-invalid");
            $("#emailEmail").removeClass("is-valid");
            flag = true;
        } else {
            $("#emailEmail").parent().find("div.valid-feedback").html("邮箱输入正确!");
            $("#emailEmail").addClass("is-valid");
            $("#emailEmail").removeClass("is-invalid");
        }

        var ppassword = /^[A-Za-z0-9_]{6,12}$/g;
        if (!ppassword.test($("#emailPassword").val())) {
            $("#emailPassword").parent().find("div.invalid-feedback").html("密码只能包含A-Za-z0-9_,且6至12位长度！");
            $("#emailPassword").addClass("is-invalid");
            $("#emailPassword").removeClass("is-valid");
            flag = true;
        } else {
            $("#emailPassword").parent().find("div.valid-feedback").html("密码格式输入正确！");
            $("#emailPassword").addClass("is-valid");
            $("#emailPassword").removeClass("is-invalid");
        }

        if (!$("#emailRepassword").val() || $("#emailPassword").val() !== $("#emailRepassword").val()) {
            $("#emailRepassword").parent().find("div.invalid-feedback").html("确认密码必须和密码一致");
            $("#emailRepassword").addClass("is-invalid");
            $("#emailRepassword").addClass("is-invalid");
            $("#emailRepassword").removeClass("is-valid");
            flag = true;
        } else {
            $("#emailRepassword").parent().find("div.valid-feedback").html("确认密码输入正确！");
            $("#emailRepassword").addClass("is-valid");
            $("#emailRepassword").removeClass("is-invalid");
        }
        var pusername = /^[A-Za-z0-9_\-\u4e00-\u9fa5]{2,12}$/g;
        if (!pusername.test($("#emailUsername").val())) {
            $("#emailUsername").parent().find("div.invalid-feedback").html("用户名只能包含A-Za-z0-9_及汉字,且2至12位长度！");
            $("#emailUsername").addClass("is-invalid");
            $("#emailUsername").addClass("is-invalid");
            $("#emailUsername").removeClass("is-valid");
            flag = true;
        } else {
            $("#emailUsername").parent().find("div.valid-feedback").html("用户名输入正确!");
            $("#emailUsername").addClass("is-valid");
            $("#emailUsername").removeClass("is-invalid");
        }

        if (flag) {
            return false;
        }
        var jsonData = $('#form_email').serializeArray();

        $.post("<?=my_site_url('registerdo/1');?>", jsonData,
            function (rs)
            {
                if(rs.code == 0)
                {
                    $(".toast-body").removeClass("text-danger").addClass("text-success").html(rs.msg);
                    new bootstrap.Toast(document.querySelector('.toast')).show();
                    document.querySelector('.toast').addEventListener('hidden.bs.toast', function () {
                      location.href = rs.prev_url
                    })
                }else{
                    $(".toast-body").removeClass("text-success").addClass("text-danger").html(rs.msg);
                    new bootstrap.Toast(document.querySelector('.toast')).show();
                }
            }
            , 'json');
    });


    //----------------------------------------------------------------------------

    $("#phonePhone").bind("input propertychange", function () {
        var pphone = /^0?(13|14|15|17|18|19)[0-9]{9}$/g;
        if (!pphone.test($(this).val())) {
            $(this).parent().find("div.invalid-feedback").html("手机号码输入错误!");
            $(this).addClass("is-invalid");
            $(this).removeClass("is-valid");
        } else {
            $(this).parent().find("div.valid-feedback").html("手机号码输入正确!");
            $(this).addClass("is-valid");
            $(this).removeClass("is-invalid");
        }
    })
    $("#phonePassword").bind("input propertychange", function () {
        var ppassword = /^[A-Za-z0-9_]{6,12}$/g;
        if (!ppassword.test($(this).val())) {
            $(this).parent().find("div.invalid-feedback").html("密码不合法，密码只能包含A-Za-z0-9_且必须大于6位！");
            $(this).addClass("is-invalid");
            $(this).removeClass("is-valid");
        } else {
            $(this).parent().find("div.valid-feedback").html("密码格式输入正确！");
            $(this).addClass("is-valid");
            $(this).removeClass("is-invalid");
        }
    })
    $("#phoneRepassword").bind("input propertychange", function () {
        if ($(this).val() !== $("#phonePassword").val()) {
            $(this).parent().find("div.invalid-feedback").html("确认密码必须和密码一致");
            $(this).addClass("is-invalid");
            $(this).removeClass("is-valid");
        } else {
            $(this).parent().find("div.valid-feedback").html("确认密码输入正确！");
            $(this).addClass("is-valid");
            $(this).removeClass("is-invalid");
        }
    })
    $("#phoneUsername").bind("input propertychange", function () {
        var pusername = /^[A-Za-z0-9_\-\u4e00-\u9fa5]{2,12}$/g;
        if (!pusername.test($(this).val())) {
            $(this).parent().find("div.invalid-feedback").html("用户名不合法，用户名只能包含A-Za-z0-9_及汉字！");
            $(this).addClass("is-invalid");
            $(this).removeClass("is-valid");
        } else {
            $(this).parent().find("div.valid-feedback").html("用户名输入正确!");
            $(this).addClass("is-valid");
            $(this).removeClass("is-invalid");
        }
    })

    $(".phone-register").click(function () {
        var flag = false;
        var pphone = /^0?(13|14|15|17|18|19)[0-9]{9}$/g;
        if (!pphone.test($("#phonePhone").val())) {
            $("#phonePhone").parent().find("div.invalid-feedback").html("手机号码格式错误!");
            $("#phonePhone").addClass("is-invalid");
            $("#phonePhone").removeClass("is-valid");
            flag = true;
        } else {
            $("#phonePhone").parent().find("div.valid-feedback").html("手机号码输入正确!");
            $("#phonePhone").addClass("is-valid");
            $("#phonePhone").removeClass("is-invalid");
        }

        var ppassword = /^[A-Za-z0-9_]{6,12}$/g;
        if (!ppassword.test($("#phonePassword").val())) {
            $("#phonePassword").parent().find("div.invalid-feedback").html("密码只能包含A-Za-z0-9_,且6至12位长度！");
            $("#phonePassword").addClass("is-invalid");
            $("#phonePassword").removeClass("is-valid");
            flag = true;
        } else {
            $("#phonePassword").parent().find("div.valid-feedback").html("密码格式输入正确！");
            $("#phonePassword").addClass("is-valid");
            $("#phonePassword").removeClass("is-invalid");
        }

        if (!$("#phoneRepassword").val() || $("#phonePassword").val() !== $("#phoneRepassword").val()) {
            $("#phoneRepassword").parent().find("div.invalid-feedback").html("确认密码必须和密码一致");
            $("#phoneRepassword").addClass("is-invalid");
            $("#phoneRepassword").addClass("is-invalid");
            $("#phoneRepassword").removeClass("is-valid");
            flag = true;
        } else {
            $("#phoneRepassword").parent().find("div.valid-feedback").html("确认密码输入正确！");
            $("#phoneRepassword").addClass("is-valid");
            $("#phoneRepassword").removeClass("is-invalid");
        }
        var pusername = /^[A-Za-z0-9_\-\u4e00-\u9fa5]{2,12}$/g;
        if (!pusername.test($("#phoneUsername").val())) {
            $("#phoneUsername").parent().find("div.invalid-feedback").html("用户名只能包含A-Za-z0-9_及汉字,且2至12位长度！");
            $("#phoneUsername").addClass("is-invalid");
            $("#phoneUsername").addClass("is-invalid");
            $("#phoneUsername").removeClass("is-valid");
            flag = true;
        } else {
            $("#phoneUsername").parent().find("div.valid-feedback").html("用户名输入正确!");
            $("#phoneUsername").addClass("is-valid");
            $("#phoneUsername").removeClass("is-invalid");
        }

        if (flag) {
            return false;
        }
        var jsonData = $('#form_phone').serializeArray();

        $.post("<?=my_site_url('registerdo/2');?>", jsonData,
            function (rs)
            {
                if(rs.code == 0)
                {
                    $(".toast-body").removeClass("text-danger").addClass("text-success").html(rs.msg);
                    new bootstrap.Toast(document.querySelector('.toast')).show();
                    document.querySelector('.toast').addEventListener('hidden.bs.toast', function () {
                      location.href = rs.prev_url
                    })
                }else{
                    $(".toast-body").removeClass("text-success").addClass("text-danger").html(rs.msg);
                    new bootstrap.Toast(document.querySelector('.toast')).show();
                }
            }
            , 'json');
    });

</script>