<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">

    <title>@yield('title')</title>

    <meta name="keywords" content="simon,blog,php">
    <meta name="description" content="Simon's Blog">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html"/>
    <![endif]-->

    <link rel="shortcut icon" href="/favicon.ico">
    <link href="{{ URL::asset('/css/bootstrap.min.css?v=3.3.6') }}" rel="stylesheet">
    <link href="{{ URL::asset('/css/font-awesome.min.css?v=4.4.0') }}" rel="stylesheet">
    <link href="{{ URL::asset('/css/animate.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('/css/style.css?v=4.1.0') }}" rel="stylesheet">
    <link href="{{ URL::asset('/css/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
    @section('link')
    @show
</head>

<body class="fixed-sidebar full-height-layout gray-bg" style="overflow:hidden">
<div id="wrapper">
    <!--左侧导航开始-->
@include('admin.layout.menu')
<!--左侧导航结束-->
    <!--右侧部分开始-->
@include('admin.layout.page')
<!--右侧部分结束-->
    <!--右侧边栏开始-->
@include('admin.layout.sidebar')
<!--右侧边栏结束-->
</div>

<!-- 全局js -->
<script src="{{ URL::asset('/js/jquery.min.js?v=2.1.4') }}"></script>
<script src="{{ URL::asset('/js/bootstrap.min.js?v=3.3.6') }}"></script>
<script src="{{ URL::asset('/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
<script src="{{ URL::asset('/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ URL::asset('/js/plugins/layer/layer.min.js') }}"></script>

<!-- 第三方插件 -->
<script src="{{ URL::asset('/js/plugins/validate/jquery.validate.min.js') }}"></script>
<script src="{{ URL::asset('/js/plugins/validate/messages_zh.min.js') }}"></script>
<script src="{{ URL::asset('/js/demo/form-validate-demo.js') }}"></script>
<script src="{{ URL::asset('/js/plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ URL::asset('/js/plugins/sweetalert/sweetalert.min.js') }}"></script>

<!-- 自定义js -->
<script src="{{ URL::asset('/js/hplus.js?v=4.1.0') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/contabs.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/ajax.js') }}"></script>
<script>
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
    var destroy = function(){
        var url = '/admin/login/' +{{ $userModel['id'] }};
        $.ajax({
            type: 'delete',
            url: url,
            dataType: 'json',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                if (data.state == 'success') {
                    window.location.href = '/admin/login';
                } else {
                    toastr.options.timeOut = 5000;
                    toastr.error('退出登录失败！');
                }
            },
            error: function (xhr, type) {
                toastr.options.timeOut = 5000;
                toastr.error('ajax请求失败！');
            }
        });
    }
    $('body').on('click', '.login_destroy', function () {
        swal({
            title: "确定退出登录吗？",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "继续退出",
            cancelButtonText: "返回页面",
            closeOnConfirm: false
        }, function () {
            destroy();
        });

    });

    var routeName = "{{$routeName}}";
    $('#side-menu').find('li[class="menus-list"]').each(function () {
        var that = $(this);
        var master = that.children("a:first-child");
        var slaver = that.children("ul");
        if (master.attr('href') == routeName) {
            master.css('background-color', '#293846');
            master.css('color', 'white');
        }
        if (slaver.length) {
            slaver.find('a').each(function () {
                if ($(this).attr('href') == routeName) {
                    $(this).css('background-color', '#293846');
                    $(this).css('color', 'white');
                    that.addClass("active");
                }
            });
        }
    })
    $('.clients-list .tab-pane').css('height', $("body").height() - 190 + 'px');
</script>
@section('script')
@show
</body>

</html>
