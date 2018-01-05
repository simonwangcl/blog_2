<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="{{ URL::asset('/css/bootstrap.min.css?v=3.3.6') }}" rel="stylesheet">
    <link href="{{ URL::asset('/css/font-awesome.min.css?v=4.4.0') }}" rel="stylesheet">
    <link href="{{ URL::asset('/css/animate.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('/css/style.css?v=4.1.0') }}" rel="stylesheet">

</head>

<body class="gray-bg">
<div class="wrapper wrapper-content  animated fadeInRight article">
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">
            <div class="ibox">
                <div class="ibox-content">
<div class="pull-right">
    <form action="/book" method="get">
        <span style="color: #ff0000;">（本地下载练手用的，网速有限，请尽量使用网盘下载，谢谢！）&nbsp;&nbsp;</span>类型：
        <select name="type" style="margin: 0;padding: 0;height: 26px">
            <option value="">-- 全部 --</option>
            @foreach($types as $type)
                @if($type->pid == 0)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endif
            @endforeach
        </select>
        书名：<input type="text" name="name" value="{{ $params['name'] or '' }}" style="margin: 0;padding: 0;height: 26px">
        <button class="btn btn-white btn-xs" type="submit">搜 索</button>
    </form>
</div>
<div class="text-center article-title">
    @foreach($books as $book)
        @if($book->children->toArray())
            <p><h2>{{ $book->name }}</h2></p>
            @foreach($book->children as $child)
                <p style="margin-left: 30px">
                    {{ $child->name }}.{{pathinfo($child->path, PATHINFO_EXTENSION) }}
                    @if($child->size)
                        &nbsp;&nbsp;&nbsp;&nbsp;大小：{{ $child->size }}
                    @endif
                    @if($child->path)
                        &nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" class="download" data-id="{{ $child->id }}">本地下载</a>
                    @endif
                    @if($child->cloud)
                        &nbsp;&nbsp;&nbsp;&nbsp;<a href="{{ $child->cloud }}" target="_blank">网盘下载</a>
                        （{{ $child->password }}@if($child->video)，内含视频@endif）
                    @endif
                </p>
            @endforeach
        @endif
    @endforeach
</div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- 全局js -->
<script src="{{ URL::asset('/js/jquery.min.js?v=2.1.4') }}"></script>
<script src="{{ URL::asset('/js/bootstrap.min.js?v=3.3.6') }}"></script>


<!-- 自定义js -->
<script type="text/javascript" src="{{ URL::asset('/js/contabs.js') }}"></script>
<script>
    $(document).ready(function () {
//        下载
        $('body').on('click', '.download', function () {
            var id = $(this).attr('data-id');
            window.location.href = '/book/' + id;
        });
//        设置select
        $("select[name='type']").find("option[value='" + {{ $params['type'] or 0}} +"']").attr("selected", true);
    });
</script>
</body>

</html>