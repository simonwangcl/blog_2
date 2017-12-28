@extends('admin.layout.index')
@section('title', '小买卖')
@section('link')
    <link href="{{ URL::asset('/css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
    <style>
        .modal-body{
            height:450px;
        }
        .info{
            margin: 10px;
        }
        .red{
            color: #ff0000;
        }
    </style>
@endsection

@section('page')
    <div class="wrapper article">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="pull-right">
                            <span class="info">今天：<span class="red">{{ $today }}</span> 单</span>
                            <span class="info">7天：<span class="red">{{ $seven }}</span> 单</span>
                            <span class="info">30天：<span class="red">{{ $thirty }}</span> 单</span>
                            <span class="info">总订单：<span class="red">{{ $all_num }}</span> 单</span>
                            <span class="info">总金额：<span class="red">{{ $all_price }}</span> 元</span>
                            <button data-toggle="modal" href="#modal-form" class="btn btn-white btn-xs" type="button" id="small_add">添 加</button>
                        </div>
                        <div>
                            <h2>小买卖</h2>
                        </div>
                        <div class="clients-list">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="full-height-scroll">
<table class="table table-hover table-bordered  table-striped">
    <thead>
    <tr>
        <th>ID</th>
        <th>金额</th>
        <th>日期</th>
        <th>创建时间</th>
        <th>修改时间</th>
        <th style="width: 135px;">操作</th>
    </tr>
    </thead>
    <tbody>
    @foreach($smalls as $small)
        <tr>
            <td>{{ $small->id }}</td>
            <td>{{ $small->price }}</td>
            <td>{{ $small->date }}</td>
            <td>{{ $small->created_at }}</td>
            <td>{{ $small->updated_at }}</td>
            <td>
                <button type="button" class="btn btn-primary btn-xs small-edit" data-toggle="modal" href="#modal-form">修改</button>
                <a class="ajax-delete" href="/admin/tools/small/{{ $small->id }}" method="delete" confirm="确定删除该订单吗？">
                    <button type="button" class="btn btn-primary btn-xs">删除</button>
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<div style="text-align:center;">{{ $smalls->links() }}</div>
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
                            <h3 class="m-t-none m-b">添加垫付订单</h3>
                            <form class="form-horizontal m-t ajax-form" action="/admin/tools/small" method="post">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">金额：</label>
                                    <div class="col-sm-8">
                                        <input id="price" name="price" class="form-control" type="text" required maxlength="5" autofocus="true">
                                        <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 9个字节之内</span>
                                    </div>
                                </div>
                                <div class="form-group" id="data">
                                    <label class="col-sm-3 control-label">日期：</label>
                                    <div class="col-sm-8 input-group date">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input type="text" class="form-control" name="date" value="" data-date="{{ date('Y-m-d') }}">
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
    <script src="{{ URL::asset('/js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ URL::asset('/js/plugins/cropper/cropper.min.js') }}"></script>
    {{--<script src="{{ URL::asset('/js/demo/form-advanced-demo.min.js') }}"></script>--}}
    <script>
        $(document).ready(function () {
            $('body').on('click', '#small_add', function () {
                $('.m-t-none').html('添加垫付订单');
                $('.ajax-form').attr('method', 'post');
                $('.ajax-form').attr('action', '/admin/tools/small');
                $('#price').val('');
                $('input[name=date]').val($('input[name=date]').attr('data-date'));
                setTimeout("$('#price').focus()", 300);
            });
            $('body').on('click', '.small-edit', function () {
                $('.m-t-none').html('修改垫付订单');
                $('.ajax-form').attr('method', 'put');
                $('.ajax-form').attr('action', '/admin/tools/small/' + $(this).parents('tr').children('td').eq(0).html());
                $('#price').val($(this).parents('tr').children('td').eq(1).html());
                $('input[name=date]').val($(this).parents('tr').children('td').eq(2).html());
                setTimeout("$('#price').focus()", 300);
            });
            $(".ajax-form").on("success", function (event, result) {
                if (result.state == 'success') {
                    swal(result.message, "", "success")
                    setTimeout("window.location.reload()", 1000);
                } else {
                    toastr.options.timeOut = 5000;
                    toastr.error(result.message);
                }
            });

            $('body').on('click', '#data', function () {
                $('.datepicker').css('z-index',3000);
            });

            $("#data .input-group.date").datepicker({
                todayBtn: "linked",
                keyboardNavigation: !1,
                forceParse: !1,
                calendarWeeks: !0,
                autoclose: !0
            });
        });
    </script>
@endsection
