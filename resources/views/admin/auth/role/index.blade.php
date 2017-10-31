@extends('admin.layout.index')
@section('title', '角色')
@section('link')

@endsection

@section('page')
    <div class="wrapper article">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="pull-right">
                            <button data-toggle="modal" href="#modal-form" class="btn btn-white btn-xs" type="button" id="role_add">添 加</button>
                        </div>
                        <div>
                            <h2>角色管理</h2>
                        </div>
                        <div class="clients-list">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="full-height-scroll">
                                        <table class="table table-hover table-bordered  table-striped">
                                            <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th style="width: 70px;">角色名称</th>
                                                <th>权限</th>
                                                <th>修改时间</th>
                                                <th style="width: 135px;">操作</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($roles as $role)
                                                <tr>
                                                    <td>{{ $role->id }}</td>
                                                    <td>{{ $role->name }}</td>
                                                    <td>
                                                        @foreach($role->menus as $menu)
                                                            <button type="button" class="btn btn-white btn-xs">{{$menu->name}}</button>
                                                        @endforeach
                                                    </td>
                                                    <td>{{ $role->updated_at }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary btn-xs role-edit" data-toggle="modal" href="#modal-form">修改
                                                        </button>
                                                        <a href="/admin/role/{{ $role->id }}/edit">
                                                            <button type="button" class="btn btn-primary btn-xs role-edit">
                                                                权限
                                                            </button>
                                                        </a>
                                                        <a class="ajax-delete" href="/admin/role/{{ $role->id }}"
                                                           method="delete" confirm="确定删除该角色吗？">
                                                            <button type="button" class="btn btn-primary btn-xs">删除</button>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        <div style="text-align:center;">{{ $roles->links() }}</div>
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
                            <h3 class="m-t-none m-b">添加角色</h3>
                            <form class="form-horizontal m-t ajax-form" action="/admin/role" method="post">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">角色名称：</label>
                                    <div class="col-sm-8">
                                        <input id="name" name="name" class="form-control" type="text" required maxlength="9" autofocus="true">
                                        <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 9个字节之内</span>
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
            $('body').on('click', '#role_add', function () {
                $('.m-t-none').html('添加角色');
                $('.ajax-form').attr('method', 'post');
                $('.ajax-form').attr('action', '/admin/role');
                $('#name').val('');
                setTimeout("$('#name').focus()", 300);
            })
            $('body').on('click', '.role-edit', function () {
                $('.m-t-none').html('修改角色');
                $('.ajax-form').attr('method', 'put');
                $('.ajax-form').attr('action', '/admin/role/' + $(this).parents('tr').children('td').eq(0).html());
                $('#name').val($(this).parents('tr').children('td').eq(1).html());
                setTimeout("$('#name').focus()", 300);
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
