@extends('admin.layout.index')
@section('title', '分类')
@section('link')
    <link href="{{ URL::asset('/css/plugins/iCheck/custom.css') }}" rel="stylesheet">
@endsection

@section('page')
    <div class="wrapper article">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="pull-right">
                            <button data-toggle="modal" href="#modal-form" class="btn btn-white btn-xs" type="button"
                                    id="category_add">添 加
                            </button>
                        </div>
                        <div>
                            <h2>分类管理</h2>
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
                            <h3 class="m-t-none m-b">添加分类</h3>
                            <form class="form-horizontal m-t ajax-form" action="/admin/category" method="post">
                                <div class="form-group" id='menu-select'>
                                    <label class="col-sm-3 control-label">父级分类：</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="pid" autocomplete="off">
                                            <option value="0">-请选择-</option>
                                            @foreach($names as $id => $name)
                                                <option value="{{$id}}">{{$name}}</option>
                                            @endforeach
                                        </select>
                                        <span class="help-block m-b-none">
                                        <i class="fa fa-info-circle"></i> 不选则为父级分类
                                    </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">分类名称：</label>
                                    <div class="col-sm-8">
                                        <input id="name" name="name" class="form-control" type="text" required
                                               maxlength="9">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">跳转链接：</label>
                                    <div class="col-sm-8">
                                        <input id="href" name="href" class="form-control" type="text" Nmaxlength="50">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">新页面打开：</label>
                                    <div class="col-sm-8">
                                    <span class="radio i-checks" style="display: inline;">
                                        <label><input type="radio" checked="checked" value="0" name="target"> <i></i> 否 </label>
                                    </span>
                                        <span class="radio i-checks" style="display: inline;">
                                        <label><input type="radio" value="1" name="target"> <i></i> 是 </label>
                                    </span>
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
    <script src="{{ URL::asset('js/plugins/iCheck/icheck.min.js') }}"></script>
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
                        url: '/admin/category/1',
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

//这玩意是不是个刷新按钮= =
            $('body').on('click', '#category_add', function () {
                $('#menu-select').show();
                $('.m-t-none').html('添加分类');
                $('.ajax-form').attr('method', 'post');
                $('#name').val('');
                $('#href').val('');
                $('input[type="radio"][value="0"]').iCheck('check');
                $('select[name="pid"]').children('option').each(function () {
                    $(this).prop('selected', false);
                });
                $('.ajax-form').attr('action', '/admin/category');
            })
            $('body').on('click', '.category_edit', function () {
                $('#menu-select').hide();
                $('.m-t-none').html('修改分类');
                $('.ajax-form').attr('method', 'put');
                $('#name').val($(this).attr('data-name'));
                $('#href').val($(this).attr('data-href'));
                $('input[type="radio"]').eq($(this).attr('data-tar')).iCheck('check');
                $('.ajax-form').attr('action', '/admin/category/' + $(this).attr('data-id'));
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

            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });
    </script>
@endsection
