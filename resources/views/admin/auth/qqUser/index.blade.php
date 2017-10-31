@extends('admin.layout.index')
@section('title', '用户')
@section('link')

@endsection

@section('page')
    <div class="wrapper article">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="pull-right" style="width: 80%;padding-top: 5px">
                            <form action="/admin/user" method="get">
                                {{ csrf_field() }}
                                <span>
                                    搜索：<input type="text" placeholder="用户名/邮箱/手机" name="keyword" value="{{ $params['keyword'] or '' }}">
                                    角色：
                                    <select class="" name="role" style="height: 24px;">
                                        <option value="0">-全部-</option>

                                    </select>
                                    <button type="submit" class="btn btn-primary btn-xs">
                                        搜索
                                    </button>
                                </span>
                            </form>
                        </div>
                        <div>
                            <h2>用户管理</h2>
                        </div>
                        <div class="clients-list">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="full-height-scroll">
<table class="table table-hover table-bordered  table-striped">
    <thead>
    <tr>
        <th>ID</th>
        <th>绑定用户</th>
        <th>头像</th>
        <th>QQ昵称</th>
        <th>性别</th>
        <th>城市</th>
        <th>年份</th>
        <th>注册时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>@if($user->user)<a href="/admin/user/{{ $user->user->id }}/edit">{{ ucwords($user->user->name) }}</a>@endif</td>
            <td style="padding:0"><img src="{{ $user->image or '/img/default/avatar45.jpg'}}" style="width: 45px;"></td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->gender }}</td>
            <td>{{ $user->province }} - {{ $user->city }}</td>
            <td>{{ $user->year }}</td>
            <td>{{ $user->created_at }}</td>
            <td><button type="button" class="btn btn-primary btn-xs qq-unbound" data-toggle="modal">解除绑定</button></td>
        </tr>
    @endforeach
    </tbody>
</table>
<div style="text-align:center;">{{ $users->links() }}</div>
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
        $('body').on('click', '.qq-unbound', function(){
            var id = $(this).parents('tr').children().eq(0).html();
            swal({
                title: '是否解除绑定？',
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确定",
                cancelButtonText: "取消",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }, function () {
                $.ajax({
                    type: "put",
                    dataType: "JSON",
                    url: '/admin/qquser/'+id,
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    success: function (data) {
                        if (data.state == 'success') {
                            swal(data.message, "", "success")
                            setTimeout("window.location.reload()", 1000);
                        } else {
                            toastr.options.timeOut = 5000;
                            toastr.error(data.message);
                        }
                    },
                    error: function () {
                        toastr.error('ajax请求失败！');
                    }
                });
            });
        });
    </script>
@endsection
