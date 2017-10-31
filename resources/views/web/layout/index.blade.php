<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>王超立 | Simon's Blog</title>
    <meta name="keywords" content="simon,blog,php,Simon's Blog,simon's blog,simon's,博客，王超立">
    <meta name="description" content="Simon's Blog">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <!-- 引入BootStrap的CSS文件 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ URL::asset('/css/bootstrap.min.css?v=3.3.6') }}">
    <link href="{{ URL::asset('/css/font-awesome.min.css?v=4.4.0') }}" rel="stylesheet">

    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Tangerine&amp;v1"/>
    <link rel="stylesheet" href="{{ URL::asset('/css/web.css') }}">
    @section('link')
    @show
</head>

<body>
@include('web.layout.header')

@include('web.layout.logo')
<div class="container">
    <div class="row">
        @section('content')
        @show
        @include('web.layout.menu')
    </div>
</div>
@include('web.layout.footer')
</body>
<!-- 引入JQuery文件 -->
<script src="{{ URL::asset('/js/jquery.min.js') }}"></script>
<!-- 引入BootStrap的JS文件 -->
<script src="{{ URL::asset('/js/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('/js/plugins/layer/layer.min.js') }}"></script>
<!-- 引入自定义的JS文件 -->
<script src="{{ URL::asset('/js/web.js') }}"></script>
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="{{ URL::asset('/js/html5shiv.min.js') }}"></script>
<script src="{{ URL::asset('/js/respond.min.js') }}"></script>
<![endif]-->
@section('script')
@show
</html>
