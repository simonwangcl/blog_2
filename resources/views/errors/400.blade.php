<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0029)http://news.ifeng.com/jhghdhd -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>没有权限</title>
    <style type="text/css">
        body {
            margin: 0px;
            padding: 0px;
            background: #fff;
        }

        .main {
            width: 998px;
            height: 525px;
            border: 1px solid #d9d9d9;
            margin: 10px auto;
            background: url({{ URL::asset('/img/bkpic.jpg') }}) no-repeat 156px 126px;
        }

        .main .mat {
            width: 552px;
            margin: 146px 0px 0px 235px;
        }

        .main .mat p {
            font: bold 16px/24px simsun;
            text-align: center;
            margin-bottom: 80px;
        }

        .main .mat p span {
            color: #ba2835;
            padding-right: 10px;
        }

        .main .mat .tit {
            font: normal 12px simsun;
            color: #515151;
            padding-left: 20px;
            height: 28px;
            background: url({{ URL::asset('/img/bklin.gif') }}) repeat-x left bottom;
        }

        .main .mat ul {
            margin: 5px 20px;
            padding: 0px;
        }

        .main .mat ul li {
            background: url({{ URL::asset('/img/picli.gif') }}) no-repeat left center;
            height: 20px;
            list-style: none;
            font: normal 14px/20px simsun;
            padding-left: 12px;
        }

        a:link {
            color: #004276;
            text-decoration: none;
        }

        a:visited {
            color: #004276;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
            color: #ba2636
        }
    </style>
</head>
<body>
<div class="main">
    <div class="mat">
        <p>抱歉，您没有权限使用该功能!<br><span id="xm">3</span>秒后返回后台首页</p>
        <ul>
            <li><a href="/admin">立即返回后台首页</a></li>
        </ul>
    </div>
</div>
<script type="text/javascript">
    var i = 3;
    var intervalid;
    var xm = document.getElementById("xm");
    intervalid = setInterval("fun()", 1000);
    function fun() {
        if (i == 0) {
            window.location.href = "/admin";
            clearInterval(intervalid);
        }
        xm.innerHTML = i;
        i--;
    }
</script>
</body>
</html>