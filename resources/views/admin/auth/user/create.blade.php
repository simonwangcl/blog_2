@extends('admin.layout.index')
@section('title', '用户添加')
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
                        <h2>用户添加</h2>
                        <div class="clients-list">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="full-height-scroll">
    <div class="container" id="crop-avatar">
        <!-- Cropping modal -->
        <div class="modal fade" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form class="avatar-form" action="/admin/upload?type=avatar" enctype="multipart/form-data" method="post">
                        {{ csrf_field() }}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title" id="avatar-modal-label">更换头像</h4>
                        </div>
                        <div class="modal-body">
                            <div class="avatar-body">
                                <!-- Upload image and data -->
                                <div class="avatar-upload">
                                    <input type="hidden" class="avatar-src" name="avatar_src">
                                    <input type="hidden" class="avatar-data" name="image_data">
                                    <label for="avatarInput">本地上传</label>
                                    <input type="file" class="avatar-input" id="avatarInput" name="image">
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
                                            <button type="button" class="btn btn-primary" data-method="rotate" data-option="-90" title="Rotate -90 degrees">左转90°</button>
                                        </div>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary" data-method="rotate" data-option="90" title="Rotate 90 degrees">右转90°</button>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary btn-block avatar-save">上传</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div><!-- /.modal -->
        <!-- Loading state -->
        <div class="loading" aria-label="Loading" role="img" tabindex="-1"></div>
        <form class="form-horizontal m-t ajax-form" action="/admin/user" id="signupForm" method="post">
            <div class="form-group">
                <label class="col-sm-3 control-label" style="margin-right: 15px">头像：</label>
                <div class="avatar-view" title="点击上传头像，只能上传JPG,JPEG,GIF,PNG格式的图片！">
                    <img src="" alt="头像">
                    <input type="hidden" name="avatar">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">角色：</label>
                <div class="col-sm-5">
                    <select class="form-control" name="role_id" autocomplete="off">
                        <option value="0">-请选择-</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 默认角色为游客</span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">用户名：</label>
                <div class="col-sm-5">
                    <input id="username" name="username" class="form-control" type="text" aria-invalid="true" class="valid" minlength="2" maxlength="30">
                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 用户名 2 - 30 字节之间(一个中文3个字节)</span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">邮箱：</label>
                <div class="col-sm-5">
                    <input id="email" name="email" class="form-control" type="text" aria-invalid="true" class="valid" minlength="5" maxlength="30">
                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 邮箱于 5 - 30 个字节之间</span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">手机号码：</label>
                <div class="col-sm-5">
                    <input id="phone" name="phone" class="form-control" type="text" aria-invalid="true" class="error" number minlength="11" maxlength="11">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">密码：</label>
                <div class="col-sm-5">
                    <input id="password" name="password" class="form-control" type="text" minlength="6" maxlength="15">
                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 密码于 6 - 15 个字节之间</span>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-8 col-sm-offset-3">
                    <button class="btn btn-primary" type="submit">提 交</button>
                </div>
            </div>
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
@endsection

@section('script')
    <script src="{{ URL::asset('/js/plugins/cropper/cropper.min.js') }}"></script>
    <script src="{{ URL::asset('/js/plugins/cropper/avatar.js') }}"></script>
    <script>
        $(".ajax-form").on("success", function (event, result) {
            if (result.state == 'success') {
                swal(result.message, "", "success")
                setTimeout("window.location.href='/admin/user'", 1000);
            } else {
                toastr.options.timeOut = 5000;
                toastr.error(result.message);
            }
        })
    </script>
@endsection
