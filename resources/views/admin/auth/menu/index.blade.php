@extends('admin.layout.index')
@section('title', '菜单')
@section('link')

@endsection

@section('page')
    <div class="wrapper article">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="pull-right">
                            <button data-toggle="modal" href="#modal-form" class="btn btn-white btn-xs" type="button" id="menu_add">添 加</button>
                        </div>
                        <div>
                            <h2>菜单管理</h2>
                        </div>
                        <div class="clients-list">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="full-height-scroll">
                                        <div class="dd" id="nestable3">
                                            {!! $html !!}
                                        </div>
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
                            <h3 class="m-t-none m-b">添加菜单</h3>
                            <form class="form-horizontal m-t ajax-form" action="/admin/menu" method="post">
                                <div class="form-group" id='menu-select'>
                                    <label class="col-sm-3 control-label">父级菜单：</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="pid" autocomplete="off">
                                            <option value="0">-请选择-</option>
                                            @foreach($names as $id => $name)
                                                <option value="{{$id}}">{{$name}}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block m-b-none">
                                            <i class="fa fa-info-circle"></i> 不选则为父级菜单
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">菜单名称：</label>
                                    <div class="col-sm-8">
                                        <input id="name" name="name" class="form-control" type="text" required maxlength="9">
                                        <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 9个字节之内</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">菜单链接：</label>
                                    <div class="col-sm-8">
                                        <input id="path" name="path" class="form-control" type="text" maxlength="50">
                                        <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 50个字节之内</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">菜单图标：</label>
                                    <div class="col-sm-8">
                                        <input id="icon" name="icon" class="form-control" type="text" maxlength="30">
                                        <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 30个字节之内</span>
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
    <script src="{{ URL::asset('/js/plugins/nestable/jquery.nestable.js') }}"></script>
    <script>
        $(document).ready(function () {
            var info;
            var updateOutput = function (e) {
                var list = e.length ? e : $(e.target);
                list = window.JSON.stringify(list.nestable('serialize'));
                if (!info) {
                    info = list;
                }
                //判断两个json对象是否相等，不相等时才发起请求
                if (JSON.stringify(info) !== JSON.stringify(list)) {
                    info = list;
                    $.ajax({
                        type: "put",
                        dataType: "JSON",
                        url: '/admin/menu/1',
                        data: {list: list, type: 'rank'},
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                        success: function (data) {
                            toastr.options.timeOut = 5000;
                            if (data.state == 'success') {
                                toastr.success(data.message);
                            } else {
                                toastr.error(data.message);
                            }
                        },
                        error: function () {
                            toastr.error('ajax请求失败！');
                        }
                    });
                }
            };
            $('#nestable3').nestable({
                group: 1,
                maxDepth: 2
            }).on('change', updateOutput);
            updateOutput($('#nestable3').data('output', info));


            $('body').on('click', '#menu_add', function () {
                $('#menu-select').show();
                $('.m-t-none').html('添加菜单');
                $('.ajax-form').attr('method', 'post');
                $('#name').val('');
                $('#path').val('');
                $('#icon').val('');
                $('select[name="pid"]').children('option').each(function () {
                    $(this).prop('selected', false);
                }).eq(0).prop("selected", "selected");
                $('.ajax-form').attr('action', '/admin/menu');
            })
            $('body').on('click', '.menu_edit', function () {
                $('#menu-select').hide();
                $('.m-t-none').html('修改菜单');
                $('.ajax-form').attr('method', 'put');
                $('#name').val($(this).attr('data-name'));
                $('#path').val($(this).attr('data-path'));
                $('#icon').val($(this).attr('data-icon'));
                $('.ajax-form').attr('action', '/admin/menu/' + $(this).attr('data-id'));
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
