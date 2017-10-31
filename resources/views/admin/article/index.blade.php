@extends('admin.layout.index')
@section('title', '首页')
@section('link')

@endsection
@section('page')
    <div class="wrapper article">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="pull-right" style="width: 80%;padding-top: 5px">
                            <form action="/admin/article" method="get">
                                {{ csrf_field() }}
                                <span>
                                    搜索：<input type="text" placeholder="标题" name="title" value="{{ $params['title'] or '' }}">
                                    置顶：
                                    <select class="" name="sticky" style="height: 24px;">
                                        <option value="">- 全部 -</option>
                                        <option value="1">-已置顶-</option>
                                        <option value="0">-未置顶-</option>
                                    </select>
                                    发布：
                                    <select class="" name="state" style="height: 24px;">
                                        <option value="">- 全部 -</option>
                                        <option value="1">-已发布-</option>
                                        <option value="0">-未发布-</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-xs">
                                        搜索
                                    </button>
                                </span>
                                <a href="/admin/article/create" style="float: right;padding-top: 5px;">
                                    <button class="btn btn-white btn-xs" type="button">添 加</button>
                                </a>
                            </form>
                        </div>
                        <div>
                            <h2>文章管理</h2>
                        </div>
                        <div class="clients-list">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="full-height-scroll">
<table class="table table-hover table-bordered  table-striped">
    <thead>
    <tr>
        <th>ID</th>
        <th>封面</th>
        <th>标题</th>
        <th>作者</th>
        <th>分类</th>
        <th>置顶</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>更新时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    @foreach($articles as $article)
        <tr>
            <td>{{ $article->id }}</td>
            <td style="padding:0"><img src="{{ $article->cover }}" style="width: 65px; height: 40px;"></td>
            <td>{{ $article->title }}</td>
            <td>{{ $article->author }}</td>
            <td>{{ $article->category }}</td>
            <td><a class="article-sticky" href="/admin/article/{{ $article->id }}" article-type="sticky">{{ $article->sticky }}</a></td>
            <td><a class="article-state" href="/admin/article/{{ $article->id }}" article-type="state">{{ $article->state }}</a></td>
            <td>{{ $article->created_at }}</td>
            <td>{{ $article->updated_at }}</td>
            <td>
                <button type="button" class="btn btn-primary btn-xs article-data" data-toggle="modal"  tags ="{{ $article->tags }}"
                        count ="{{ $article->count }}" comments ="{{ $article->comments_count }}" points ="{{ $article->points_count }}">统计</button>
                <a href="/admin/article/{{ $article->id }}/edit">
                    <button type="button" class="btn btn-primary btn-xs role-edit"
                            data-toggle="modal">修改
                    </button>
                </a>
                <a class="ajax-delete" href="/admin/article/{{ $article->id }}" method="delete" confirm="确定删除该文章吗？">
                    <button type="button" class="btn btn-primary btn-xs role-delete">
                        删除
                    </button>
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<div style="text-align:center;">{{ $articles->links() }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            $('body').on('mouseover', '.article-data', function () {
                var count = $(this).attr('count');
                var comments = $(this).attr('comments');
                var points = $(this).attr('points');
                var tags = $(this).attr('tags');
                var title = "浏览总数：" + count + "</br>评论总数：" + comments + "</br>点赞总数：" + points;
                if (tags) {
                    title += "</br>标签：" + tags;
                }
                layer.tips(title, this, {tips: [1, '#3595CC'], time: 0});
            });
            $('body').on('mouseout', '.article-data', function () {
                $('.layui-layer-tips').remove();
            });
            $('body').on('click', '.article-sticky,.article-state', function(){
                var that = this;
                var type = $(that).attr('article-type');
                var href = $(that).attr('href');
                $.ajax({
                    url: href,
                    type: 'PUT',
                    data: {'type':type},
                    dataType: "json",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function(event, result){
                        if (event.state == 'success') {
                            swal({
                                title: event.message,
                                text: "",
                                timer: 1000,
                                showConfirmButton: false,
                                type: "success"
                            });
                            $(that).html(event.word);
                        } else {
                            toastr.options.timeOut = 5000;
                            toastr.error(event.message);
                        }
                    },
                    error: function(){
                        toastr.options.timeOut = 5000;
                        toastr.error('ajax请求失败！');
                    }
                });
                return false;
            });
            @if(isset($params['sticky']))
                $('select[name = sticky]').find("option[value = {{ $params['sticky'] }}]").attr("selected", "selected");
            @endif
            @if(isset($params['state']))
                $('select[name = state]').find("option[value = {{ $params['state'] }}]").attr("selected", "selected");
            @endif
        });
    </script>
@endsection
