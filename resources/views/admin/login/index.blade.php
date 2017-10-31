<!DOCTYPE html>
<html lang="cn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <title>登录</title>
    <meta name="keywords" content="simon,blog,php">
    <meta name="description" content="Simon' Blog">
    <link href="{{ URL::asset('/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('/css/font-awesome.css?v=4.4.0') }}" rel="stylesheet">
    <link href="{{ URL::asset('/css/plugins/iCheck/custom.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('/css/animate.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('/css/style.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('/css/login.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('/css/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('/css/captcha.css') }}" rel="stylesheet">
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html"/>
    <![endif]-->
    <script>
        if (window.top !== window.self) {
            window.top.location = window.location;
        }
    </script>
</head>

<body class="signin">
<div class="signinpanel">
    <div class="row">
        <div class="col-lg-offset-3 col-sm-6">
            <form method="post" action="/admin/login" id="ajax-form" onsubmit="return ajax()">
                {{ csrf_field() }}
                <h4 class="no-margins" style="color: #000000">登录：</h4>
                <p class="m-t-md text-center" style="color: #000000">Simon's Blog</p>
                <input type="text" class="form-control uname" placeholder="用户名/邮件/手机号" name="name" required="true"
                       minlength="2" maxlength="30"/>
                <input type="password" class="form-control pword m-b" placeholder="密码" name="password" required="true"
                       minlength="6" maxlength="15"/>
                <div class="form-group text-left">
                    <div class="checkbox i-checks">
                        <label class="no-padding">
                            <input type="checkbox" name="remember" value="1"><i></i>
                            <span style="color: #000000">记住我</span>
                        </label>
                    </div>
                </div>
                {{--<div class="text-center"><a href="">忘记密码了？</a> | <a href="/admin/register">立即注册</a></div>--}}
                <button class="btn btn-success btn-block" style="display: none" id="loginIn">登录</button>
                <div class="form-inline-input">
                    <div class="code-box" id="code-box">
                        <input type="text" name="code" class="code-input" />
                        <p></p>
                        <span>>>></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="signup-footer">
        <div class="pull-left">
            &copy; 2017 All Rights Reserved.
        </div>
    </div>
</div>
<!-- 全局js -->
<script src="{{ URL::asset('/js/jquery.min.js?v=2.1.4') }}"></script>
<script src="{{ URL::asset('/js/bootstrap.min.js?v=3.3.6') }}"></script>

<!-- jQuery Validation plugin javascript-->
<script src="{{ URL::asset('/js/plugins/validate/jquery.validate.min.js') }}"></script>
<script src="{{ URL::asset('/js/plugins/validate/messages_zh.min.js') }}"></script>
<script src="{{ URL::asset('/js/captcha.js') }}"></script>

<!-- iCheck -->
<script src="{{ URL::asset('/js/plugins/iCheck/icheck.min.js') }}"></script>
<script src="{{ URL::asset('/js/plugins/toastr/toastr.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });

        window.addEventListener('load',function(){

            //code是后台传入的验证字符串
            var codeFn = new moveCode({{ $code }});

            //获取当前的code值
            //console.log(codeFn.getCode());

            //改变code值
            //code = '46asd546as5';
            //codeFn.setCode(code);

            //重置为初始状态
            //codeFn.resetCode();
        });
    });
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "progressBar": true,
        "positionClass": "toast-top-center",
        "onclick": null,
        "showDuration": "400",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    function ajax() {
        var name = $('input[name="name"]').val();
        var password = $('input[name="password"]').val();
        var result = 1;

        if (name.length < 2 || name.length > 30) {
            toastr.error('帐号长度在 2-30 个字节之间！');
            result = 0;
        }
        if (password.length < 6 || password.length > 15) {
            toastr.error('密码长度在 6-15 个字节之间！');
            result = 0;
        }
        if (result) {
            var url = $('#ajax-form').attr('action');
            var method = $('#ajax-form').attr('method');
            var remember = $("input[type='checkbox']").is(':checked');
            $.ajax({
                type: method,
                url: url,
                data: {name: name, password: password, remember: remember},
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                success: function (data) {
                    if (data.state == 'success') {
                        window.location.href = '/admin';
                    } else {
                        toastr.options.timeOut = 5000;
                        toastr.error(data.message);
                        $('input[name="password"]').val('');
                    }
                },
                error: function (xhr, type) {
                    toastr.options.timeOut = 5000;
                    toastr.error('ajax请求失败！');
                }
            });
        }
        return false;
    }
</script>
</body>
</html>
