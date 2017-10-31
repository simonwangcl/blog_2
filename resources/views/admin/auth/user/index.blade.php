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
                                        @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-xs">
                                        搜索
                                    </button>
                                </span>
                                <a href="/admin/user/create" style="float: right;padding-top: 5px;">
                                    <button class="btn btn-white btn-xs" type="button">添 加</button>
                                </a>
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
        <th>头像</th>
        <th>用户名</th>
        <th>邮箱</th>
        <th>手机</th>
        <th>角色</th>
        <th>修改时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td style="padding:0"><img src="{{ $user->image or '/img/default/avatar45.jpg'}}" style="width: 45px;"></td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->phone }}</td>
            <td>{{ $user->role['name'] }}</td>
            <td>{{ $user->updated_at }}</td>
            <td>
                <a href="/admin/user/{{ $user->id }}/edit">
                    <button type="button" class="btn btn-primary btn-xs role-edit"
                            data-toggle="modal">修改
                    </button>
                </a>
                <a class="ajax-delete" href="/admin/user/{{ $user->id }}"
                   method="delete" confirm="确定删除该用户吗？">
                    <button type="button" class="btn btn-primary btn-xs role-delete">
                        删除
                    </button>
                </a>
            </td>
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
        @if(isset($params['role']))
        $('select[name = role]').find("option[value = {{ $params['role'] }}]").attr("selected", "selected");
        @endif
    </script>
@endsection
