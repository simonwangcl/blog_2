@extends('admin.layout.index')
@section('title', '个人资料')
@section('link')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('/css/plugins/cropper/cropper.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('/css/plugins/cropper/avatar.css') }}">
@endsection

@section('page')
    <div class="wrapper article">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <div>
                            <h2>个人资料</h2>
                        </div>
                        <div class="clients-list">
                            <div class="tab-content">
                                <div class="tab-pane active">

                                    <div class="full-height-scroll">
                                        <div class="container" id="crop-avatar">
                                            <div class="tabs-container col-sm-8">
                                                <ul class="nav nav-tabs">
                                                    @if($type == 'avatar')
                                                        <li class="active"><a data-toggle="tab" href="#tab-1"
                                                                              aria-expanded="true"> 头像修改 </a></li>
                                                    @else
                                                        <li><a data-toggle="tab" href="#tab-1" aria-expanded="false">
                                                                头像修改 </a></li>
                                                    @endif
                                                    @if($type == 'info')
                                                        <li class="active"><a data-toggle="tab" href="#tab-2"
                                                                              aria-expanded="true"> 资料修改 </a></li>
                                                    @else
                                                        <li><a data-toggle="tab" href="#tab-2" aria-expanded="false">
                                                                资料修改 </a></li>
                                                    @endif
                                                    @if($type == 'password')
                                                        <li class="active"><a data-toggle="tab" href="#tab-3"
                                                                              aria-expanded="true"> 密码修改 </a></li>
                                                    @else
                                                        <li><a data-toggle="tab" href="#tab-3" aria-expanded="false">
                                                                密码修改 </a></li>
                                                    @endif
                                                    @if($type == 'bind')
                                                        <li class="active"><a data-toggle="tab" href="#tab-4"
                                                                              aria-expanded="true"> QQ绑定 </a></li>
                                                    @else
                                                        <li><a data-toggle="tab" href="#tab-4" aria-expanded="false">
                                                                QQ绑定 </a></li>
                                                    @endif
                                                </ul>
                                                <div class="tab-content">
                                                    <div id="tab-1"
                                                         class="tab-pane @if($type == 'avatar') active @endif">
                                                        <div class="modal fade" id="avatar-modal" aria-hidden="true"
                                                             aria-labelledby="avatar-modal-label" role="dialog"
                                                             tabindex="-1">
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content">
                                                                    <form class="avatar-form"
                                                                          action="/admin/upload?type=avatar"
                                                                          enctype="multipart/form-data" method="post">
                                                                        {{ csrf_field() }}
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close"
                                                                                    data-dismiss="modal">&times;
                                                                            </button>
                                                                            <h4 class="modal-title"
                                                                                id="avatar-modal-label">更换头像</h4>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="avatar-body">
                                                                                <!-- Upload image and data -->
                                                                                <div class="avatar-upload">
                                                                                    <input type="hidden"
                                                                                           class="avatar-src"
                                                                                           name="avatar_src"
                                                                                           value="{{ $user->image }}">
                                                                                    <input type="hidden"
                                                                                           class="avatar-data"
                                                                                           name="image_data">
                                                                                    <label for="avatarInput">本地上传</label>
                                                                                    <input type="file"
                                                                                           class="avatar-input"
                                                                                           id="avatarInput"
                                                                                           name="image">
                                                                                </div>

                                                                                <!-- Crop and preview -->
                                                                                <div class="row">
                                                                                    <div class="col-md-9">
                                                                                        <div class="avatar-wrapper"></div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="avatar-preview preview-lg"></div>
                                                                                        <div class="avatar-preview preview-md"></div>
                                                                                        <div class="avatar-preview preview-sm"></div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="row avatar-btns">
                                                                                    <div class="col-md-9">
                                                                                        <div class="col-md-4"></div>
                                                                                        <div class="btn-group">
                                                                                            <button type="button"
                                                                                                    class="btn btn-primary"
                                                                                                    data-method="rotate"
                                                                                                    data-option="-90"
                                                                                                    title="Rotate -90 degrees">
                                                                                                左转90°
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="btn-group">
                                                                                            <button type="button"
                                                                                                    class="btn btn-primary"
                                                                                                    data-method="rotate"
                                                                                                    data-option="90"
                                                                                                    title="Rotate 90 degrees">
                                                                                                右转90°
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <button type="submit"
                                                                                                class="btn btn-primary btn-block avatar-save">
                                                                                            上传
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="panel-body">
                                                            <form class="form-horizontal m-t ajax-form"
                                                                  action="/admin/info/{{ $user->id }}" method="put">
                                                                <input type="hidden" name="type" value="avatar">
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label"
                                                                           style="margin-right: 15px">头像：</label>
                                                                    <div class="avatar-view"
                                                                         title="点击上传头像，只能上传JPG,JPEG,GIF,PNG格式的图片！">
                                                                        <img src="{{ $user->image }}" alt="头像">
                                                                        <input type="hidden" name="avatar"
                                                                               value="{{ $user->image }}">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col-sm-8 col-sm-offset-3">
                                                                        <button class="btn btn-primary" type="submit">提
                                                                            交
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div id="tab-2" class="tab-pane @if($type == 'info') active @endif">
                                                        <div class="panel-body">
                                                            <form class="form-horizontal m-t ajax-form"
                                                                  action="/admin/info/{{ $user->id }}" method="put">
                                                                <input type="hidden" name="type" value="info">
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label">用户名：</label>
                                                                    <div class="col-sm-5">
                                                                        <input id="username" name="username"
                                                                               class="form-control" type="text"
                                                                               required="required" aria-invalid="true"
                                                                               minlength="2" maxlength="30"
                                                                               value="{{ $user['name'] }}">
                                                                        <span class="help-block m-b-none"><i
                                                                                    class="fa fa-info-circle"></i> 用户名 2 - 30 字节之间(一个中文3个字节)</span>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label">邮箱：</label>
                                                                    <div class="col-sm-5">
                                                                        <input id="email" name="email"
                                                                               class="form-control" type="text"
                                                                               required="required" aria-invalid="true"
                                                                               minlength="5" maxlength="30"
                                                                               value="{{ $user['email'] }}">
                                                                        <span class="help-block m-b-none"><i
                                                                                    class="fa fa-info-circle"></i> 邮箱于 5 - 30 个字节之间</span>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label">手机号码：</label>
                                                                    <div class="col-sm-5">
                                                                        <input id="phone" name="phone"
                                                                               class="form-control" type="text"
                                                                               required="required" aria-invalid="true"
                                                                               number minlength="11" maxlength="11"
                                                                               value="{{ $user['phone'] }}">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col-sm-8 col-sm-offset-3">
                                                                        <button class="btn btn-primary" type="submit">提
                                                                            交
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div id="tab-3"
                                                         class="tab-pane @if($type == 'password') active @endif">
                                                        <div class="panel-body">
                                                            <form class="form-horizontal m-t ajax-form"
                                                                  action="/admin/info/{{ $user->id }}" method="put">
                                                                <input type="hidden" name="type" value="password">
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label">原密码：</label>
                                                                    <div class="col-sm-5">
                                                                        <input name="old" class="form-control"
                                                                               type="text" required="required">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label">新密码：</label>
                                                                    <div class="col-sm-5">
                                                                        <input name="new" class="form-control"
                                                                               type="password" minlength="6"
                                                                               maxlength="15" required="required">
                                                                        <span class="help-block m-b-none"><i
                                                                                    class="fa fa-info-circle"></i> 密码于 6 - 15 个字节之间</span>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-sm-3 control-label">确认密码：</label>
                                                                    <div class="col-sm-5">
                                                                        <input name="confirm" class="form-control"
                                                                               type="password" minlength="6"
                                                                               maxlength="15" required="required">
                                                                        <span class="help-block m-b-none"><i
                                                                                    class="fa fa-info-circle"></i> 请再次输入您的密码</span>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col-sm-8 col-sm-offset-3">
                                                                        <button class="btn btn-primary" type="submit">提
                                                                            交
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div id="tab-4" class="tab-pane @if($type == 'bind') active @endif">
                                                        <div class="panel-body">
                                                            <form class="form-horizontal m-t ajax-form"
                                                                  action="/admin/info/{{ $user->id }}" method="put">
                                                                <input type="hidden" name="type" value="bind">
                                                                @if( $user->qqModel )
                                                                    <div class="form-group">
                                                                        <label class="col-sm-3 control-label">已绑定帐号：</label>
                                                                        <div class="col-sm-5">
                                                                            头像：&nbsp;&nbsp;&nbsp;&nbsp;<img
                                                                                    src="{{ $user->qqModel->image }}"
                                                                                    style="width: 50px"></br></br>
                                                                            昵称：&nbsp;&nbsp;&nbsp;&nbsp;{{ $user->qqModel->name }}</br></br>
                                                                            性别：&nbsp;&nbsp;&nbsp;&nbsp;{{ $user->qqModel->gender }}</br></br>
                                                                            城市：&nbsp;&nbsp;&nbsp;&nbsp;{{ $user->qqModel->province }}
                                                                            - {{ $user->qqModel->city }}
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden"
                                                                           value="{{ $user->qqModel->id }}" id="qq_id">
                                                                    <div class="form-group">
                                                                        <div class="col-sm-8 col-sm-offset-3">
                                                                            <button class="btn btn-primary qq-unbound"
                                                                                    type="button">解除绑定
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div class="form-group">
                                                                        <label class="col-sm-3 control-label">QQ昵称：</label>
                                                                        <div class="col-sm-5 input-group">
                                                                            <input class="form-control" type="text"
                                                                                   id="qq-search" data-id="">
                                                                            <div class="input-group-btn">
                                                                                <button type="button"
                                                                                        class="btn btn-white dropdown-toggle"
                                                                                        data-toggle="dropdown">
                                                                                    <span class="caret"></span>
                                                                                </button>
                                                                                <ul class="dropdown-menu dropdown-menu-right"
                                                                                    role="menu"></ul>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" name="qq_id" id="qq_id">
                                                                    <div class="form-group">
                                                                        <div class="col-sm-8 col-sm-offset-3">
                                                                            <button class="btn btn-primary"
                                                                                    type="submit">提 交
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </form>
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
                </div>
            </div>
        </div>
    </div>
@endsection
{{--@section('beforeScript')--}}
{{--<script>--}}
{{----}}
{{--</script>--}}
{{--@endsection--}}
@section('script')
    <script src="{{ URL::asset('/js/plugins/cropper/cropper.min.js') }}"></script>
    <script src="{{ URL::asset('/js/plugins/cropper/avatar.js') }}"></script>
    <script src="{{ URL::asset('/js/plugins/suggest/bootstrap-suggest.min.js') }}"></script>
    <script>
        $(".ajax-form").on("success", function (event, result) {
            if (result.state == 'success') {
                if (result.type) {
                    swal(result.message, "", "success");
                    setTimeout("window.location.href='/admin/info?type=" + result.type + "'", 1000);
                } else {//修改密码后，提示重新登录
                    swal({
                            title: '',
                            text: result.message,
                            type: "warning",
                            showCancelButton: false,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "确定!",
                            closeOnConfirm: false,
                            closeOnCancel: false
                        },
                        function (isConfirm) {
                            if (isConfirm) {
                                destroy();
                            }
                        });
                }
            } else {
                toastr.options.timeOut = 5000;
                toastr.error(result.message);
            }
        });

        $('body').on('click', '.qq-unbound', function () {
            var id = $('#qq_id').val();
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
                    url: '/admin/qquser/' + id,
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    success: function (data) {
                        if (data.state == 'success') {
                            swal(data.message, "", "success")
                            setTimeout("window.location.href='/admin/info?type=bind'", 1000);
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

        $("#qq-search").bsSuggest({
            idField: 'Id', //data.value 的第几个数据，作为input输入框data-id的内容
            keyField: 'Name', //data.value 的第几个数据，作为input输入框的内容
            allowNoKeyword: false, //是否允许无关键字时请求数据
            multiWord: true, //以分隔符号分割的多关键字支持
            separator: ",", //多关键字支持时的分隔符，默认为空格
            getDataMethod: "url", //获取数据的方式，总是从 URL 获取
            showBtn: false,//是否显示下拉按钮
            effectiveFieldsAlias: {
                Id: "ID",
                Image: "头像",
                Name: "昵称",
                Gender: "性别",
                City: "城市"
            },
            showHeader: true,
//            url: 'http://suggest.taobao.com/sug?code=utf-8&extras=1&q=',
            url: '/admin/info/1?keyword=',
            /*优先从url ajax 请求 json 帮助数据，注意最后一个参数为关键字请求参数*/
//            jsonp: 'callback',
            /*如果从 url 获取数据，并且需要跨域，则该参数必须设置*/
            processData: function (json) { // url 获取数据时，对数据的处理，作为 getData 的回调函数
                var i, len, data = {
                    value: []
                };
                if (!json || !json.result || json.result.length == 0) {
                    return false;
                }
                len = json.result.length;
                for (i = 0; i < len; i++) {
                    var img = "<img src='" + json.result[i]['image'] + "' style='width:50px;'>";
                    data.value.push({
                        "Id": json.result[i]['id'],
                        "Image": img,
                        "Name": json.result[i]['name'],
                        "Gender": json.result[i]['gender'],
                        "City": json.result[i]['province'] + '-' + json.result[i]['city']
                    });
                }
                return data;
            }
        });

        window.beforeAjax = function () {
            $('#qq_id').val($('#qq-search').attr('data-id'));
        }
    </script>
@endsection
