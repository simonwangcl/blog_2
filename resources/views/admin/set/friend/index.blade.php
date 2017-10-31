@extends('admin.layout.index')
@section('title', '友链')
@section('link')

@endsection

@section('page')
    <div class="wrapper article">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="pull-right">
                            <button data-toggle="modal" href="#modal-form" class="btn btn-white btn-xs" type="button"
                                    id="friend_add">添 加
                            </button>
                        </div>
                        <div>
                            <h2>友链管理</h2>
                        </div>
                        <div class="clients-list">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="full-height-scroll">
<table class="table table-hover table-bordered  table-striped">
    <thead>
    <tr>
        <th>ID</th>
        <th>展示名称</th>
        <th>跳转地址</th>
        <th>排序</th>
        <th>创建时间</th>
        <th>修改时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    @foreach($friends as $friend)
        <tr>
            <td>{{ $friend->id }}</td>
            <td>{{ $friend->name }}</td>
            <td>{{ $friend->href }}</td>
            <td>{{ $friend->rank }}</td>
            <td>{{ $friend->created_at }}</td>
            <td>{{ $friend->updated_at }}</td>
            <td>
                <button type="button" class="btn btn-primary btn-xs friend-edit" data-toggle="modal" href="#modal-form">修改</button>
                <a class="ajax-delete" href="/admin/friend/{{ $friend->id }}" method="delete" confirm="确定删除该友链吗？">
                    <button type="button" class="btn btn-primary btn-xs">删除
                    </button>
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<div style="text-align:center;">{{ $friends->links() }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-form" class="modal fade" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-10">
                            <h3 class="m-t-none m-b">添加友链</h3>
<form class="form-horizontal m-t ajax-form" action="/admin/role" method="post">
    <div class="form-group">
        <label class="col-sm-3 control-label">展示名称：</label>
        <div class="col-sm-8">
            <input id="name" name="name" class="form-control" type="text" required maxlength="30">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">链接地址：</label>
        <div class="col-sm-8">
            <input id="href" name="href" class="form-control" type="text">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">排序：</label>
        <div class="col-sm-8">
            <input id="rank" name="rank" class="form-control" type="text" maxlength="2">
            <span class="help-block m-b-none"><i class="fa fa-info-circle"></i>默认排序为 0 </span>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-8 col-sm-offset-3">
            <button class="btn btn-block btn-primary" type="submit">提 交</button>
        </div>
    </div>
</form>
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
            $('body').on('click', '#friend_add', function () {
                $('.m-t-none').html('添加友链');
                $('.ajax-form').attr('method', 'post');
                $('.ajax-form').attr('action', '/admin/friend');
                $('#name').val('');
                $('#href').val('');
                $('#rank').val('');
            })
            $('body').on('click', '.friend-edit', function () {
                $('.m-t-none').html('修改友链');
                $('.ajax-form').attr('method', 'put');
                $('.ajax-form').attr('action', '/admin/friend/' + $(this).parents('tr').children('td').eq(0).html());
                $('#name').val($(this).parents('tr').children('td').eq(1).html());
                $('#href').val($(this).parents('tr').children('td').eq(2).html());
                $('#rank').val($(this).parents('tr').children('td').eq(3).html());
            })
            $(".ajax-form").on("success", function (event, result) {
                if (result.state == 'success') {
                    swal(result.message, "", "success")
                    setTimeout("window.location.reload()", 1000);
                } else {
                    toastr.options.timeOut = 5000;
                    toastr.error(result.message);
                }
            })
        });
    </script>
@endsection
