@extends('admin.layout.index')
@section('title', '个人简介')
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
                                    id="resume_add">添 加
                            </button>
                        </div>
                        <div>
                            <h2>个人简介</h2>
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
                                                <th>详细内容</th>
                                                <th>排序</th>
                                                <th>创建时间</th>
                                                <th>修改时间</th>
                                                <th>操作</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($resumes as $resume)
                                                <tr>
                                                    <td>{{ $resume->id }}</td>
                                                    <td>{{ $resume->name }}</td>
                                                    <td>{{ $resume->content }}</td>
                                                    <td>{{ $resume->rank }}</td>
                                                    <td>{{ $resume->created_at }}</td>
                                                    <td>{{ $resume->updated_at }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary btn-xs resume-edit" data-toggle="modal" href="#modal-form">修改</button>
                                                        <a class="ajax-delete" href="/admin/resume/{{ $resume->id }}" method="delete" confirm="确定删除该内容吗？">
                                                            <button type="button" class="btn btn-primary btn-xs">删除
                                                            </button>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        <div style="text-align:center;">{{ $resumes->links() }}</div>
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
                            <h3 class="m-t-none m-b">添加个人信息</h3>
                            <form class="form-horizontal m-t ajax-form" action="/admin/resume" method="post">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">展示名称：</label>
                                    <div class="col-sm-8">
                                        <input id="name" name="name" class="form-control" type="text" required maxlength="30">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">展示内容：</label>
                                    <div class="col-sm-8">
                                        <input id="content" name="content" class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">排序：</label>
                                    <div class="col-sm-8">
                                        <input id="rank" name="rank" class="form-control" type="text" maxlength="2">
                                        <span class="help-block m-b-none"><i class="fa fa-info-circle"></i>默认排序为 99 </span>
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
            $('body').on('click', '#resume_add', function () {
                $('.m-t-none').html('添加信息');
                $('.ajax-form').attr('method', 'post');
                $('.ajax-form').attr('action', '/admin/resume');
                $('#name').val('');
                $('#content').val('');
                $('#rank').val('');
            })
            $('body').on('click', '.resume-edit', function () {
                $('.m-t-none').html('修改信息');
                $('.ajax-form').attr('method', 'put');
                $('.ajax-form').attr('action', '/admin/resume/' + $(this).parents('tr').children('td').eq(0).html());
                $('#name').val($(this).parents('tr').children('td').eq(1).html());
                $('#content').val($(this).parents('tr').children('td').eq(2).html());
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
