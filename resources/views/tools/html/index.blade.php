<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>王超立 | Simon's Blog</title>
    <meta name="keywords" content="simon,blog,php,Simon's Blog,simon's blog,simon's,博客，王超立">
    <meta name="description" content="Simon's Blog">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <link rel="shortcut icon" href="/favicon.ico">
</head>

<body>
    <div style="height: 80%;width: 100%">
        <form method="post" action="/tools/html/new">
            {{ csrf_field() }}
            <textarea rows="30" style="width: 100%" name="html" placeholder="在这里复制需要新页面打开的内容！！"></textarea>
            <div style="text-align: center"><button type="submit">新页面打开</button></div>
        </form>
    </div>
</body>
<!-- 引入JQuery文件 -->
<script src="{{ URL::asset('/js/jquery.min.js') }}"></script>
</html>
