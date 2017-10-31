<header class="navbar navbar-inverse navbar-fixed-top bs-docs-nav" role="banner">
    <div class="container">
        <div class="navbar-header">
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="/" class="navbar-brand" title="Simon PHP Blog">Simon</a>
        </div>
        <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation" id="mainnav">
            <ul class="nav navbar-nav">
                <li @if(!isset($param['category'])) class="active" @endif>
                    <a href="/" title="王超立PHP博客">首页</a>
                </li>
                @foreach($categories as $category)
                    @if($category->children->toArray())
                        <li class="category-has-children @if( isset($param['cate']) && $param['cate'] == $category->id) active @endif">
                            <a href="@if($category->href) {{$category->href}} @else /?category={{ $category->id }} @endif" @if($category->target) target="_blank" @endif>{{ $category->name }}
                                <i class="fa fa-angle-down"></i></a>
                            <ul style="display: none" class="sub-menu">
                                @foreach($category->children as $children)
                                    <li><a href="@if($children->href) {{$children->href}} @else /?category={{ $children->id }} @endif" @if($children->target) target="_blank" @endif>{{ $children->name }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li @if(isset($param['cate']) && $param['cate'] == $category->id) class="active" @endif>
                            <a href="@if($category->href) {{$category->href}} @else /?category={{ $category->id }} @endif" @if($category->target) target="_blank" @endif>{{ $category->name }}</a>
                        </li>
                    @endif
                @endforeach
                <li class="divider-vertical"></li>
            </ul>
            <form class="navbar-form navbar-left" role="form" action="/" method="get">
                <div class="form-group">
                    <label class="sr-only" for="exampleInputEmail2">Search PHP</label>
                    <input type="text" class="form-control" name="keywords" placeholder="Search"
                           value="{{ $param['keywords'] or ''}}">
                </div>
                <button type="submit" class="btn btn-primary">搜索</button>
            </form>
            @if($userModel)
                <div id="welcome" class="navbar-right">
                    <img src="{{$userModel['image']}}">&nbsp;
                    <span>{{ ucwords($userModel['name']) }}</span>&nbsp;
                    <a href="javascript:void(0);" id="login-out"><i class="fa fa fa-sign-out"></i></a>
                </div>
            @else
                <div id="login-logo" class="navbar-right">
                    <span>
                        <a href="javascript:void(0);">
                            <img src="{{ URL::asset('/img/default/qq-login-logo.png') }}" id="qq-login-logo">
                        </a>
                    </span>
                </div>
            @endif
        </nav>
    </div>
</header>