@extends('admin.layout.index')
@section('title', '首页')
@section('link')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('/css/plugins/cropper/cropper.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('/css/plugins/cropper/cover.css') }}">
    <link href="{{ URL::asset('/css/plugins/chosen/chosen.css') }}" rel="stylesheet">
    <!-- 配置文件 -->
    <script src="{{ URL::asset('/thirdparty/ueDitor/ueditor.config.js') }}"></script>
    <!-- 编辑器源码文件 -->
    <script src="{{ URL::asset('/thirdparty/ueDitor/ueditor.all.js') }}"></script>

    <!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
    <!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
    <script type="text/javascript" charset="utf-8" src="{{ URL::asset('/thirdparty/ueDitor/lang/zh-cn/zh-cn.js') }}"></script>
@endsection
@section('page')
    <div class="wrapper article">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">
                <div class="ibox">
                    <div class="ibox-content">
                        <div>
                            <h2>修改文章</h2>
                        </div>
                        <div class="clients-list">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="full-height-scroll" id="crop-avatar">
<div class="modal fade" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="avatar-form" action="/admin/upload?type=cover" enctype="multipart/form-data" method="post">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="avatar-modal-label">更换封面</h4>
                </div>
                <div class="modal-body">
                    <div class="avatar-body">
                        <!-- Upload image and data -->
                        <div class="avatar-upload">
                            <input type="hidden" class="avatar-src" name="avatar_src" value="{{ $article->cover }}">
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
</div>
<div class="loading" aria-label="Loading" role="img" tabindex="-1"></div>
<form class="form-horizontal ajax-form" action="/admin/article/{{ $article->id }}?type=article" method="put">
    <div class="form-group">
        <label class="col-sm-2 control-label" style="margin-right: 15px">
            封面：
        </label>
        <div class="avatar-view" title="点击上传封面，只能上传JPG,JPEG,GIF,PNG格式的图片（260*160）！" style="width: 276px;height: 166px">
            <img src="{{ $article->cover }}" alt="封面">
            <input type="hidden" name="cover" value="{{ $article->cover }}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">
            标题：
        </label>
        <div class="col-sm-9">
            <input name="title" class="form-control" type="text" value="{{ $article->title }}" required="required" aria-invalid="true" minlength="5" maxlength="50">
            <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 标题于 5 - 50 个字之间</span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">
            简述：
        </label>
        <div class="col-sm-9">
            <input name="sketch" class="form-control" type="text" value="{{ $article->sketch }}" required="required" aria-invalid="true" minlength="10" maxlength="300">
            <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 简述于 10 - 300 个字之间</span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">
            分类：
        </label>
        <div class="col-sm-9">
            <select class="form-control" name="category" required="required" aria-invalid="true">
                <option value="" selected="selected" disabled="disabled">-请选择-</option>
                @foreach($categories as $category)
                    <option value="{{$category->id}}">{{$category->name}}</option>
                    @if(!empty($category->children))
                        @foreach($category->children as $cate)
                            <option value="{{$cate->id}}">&nbsp;&nbsp;&nbsp;&nbsp;{{$cate->name}}</option>
                        @endforeach
                    @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">
            标签：
        </label>
        <div class="col-sm-9">
            <select data-placeholder="选择标签" class="chosen-select" multiple style="width:100%;" tabindex="4" name="tags[]">
                <option value="">请选择标签</option>
                @foreach($tags as $tag)
                    <option @if(in_array($tag->id, $article->tags)) selected @endif value="{{ $tag->id }}" hassubinfo="true">{{ $tag->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">
            内容：
        </label>
        <div class="col-sm-9">
            <script id="editor" name="content" type="text/plain"> {!!  $article->content !!}</script>
        </div>
    </div>
    <div class="form-group center">
        <div class="col-sm-offset-3 col-sm-8">
            <button type="submit" class="btn btn-primary" style="margin-bottom:20px">提 交
            </button>
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
@endsection
@section('script')
    <script src="{{ URL::asset('/js/plugins/cropper/cropper.min.js') }}"></script>
    <script src="{{ URL::asset('/js/plugins/cropper/cover.js') }}"></script>
    <script src="{{ URL::asset('/js/plugins/chosen/chosen.jquery.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            @if(isset($article->category_id))
                $('select[name = category]').find("option[value = {{ $article->category_id }}]").attr("selected", "selected");
            @endif

            var config = {
                '.chosen-select': {},
                '.chosen-select-deselect': {
                    allow_single_deselect: true
                },
                '.chosen-select-no-single': {
                    disable_search_threshold: 10
                },
                '.chosen-select-no-results': {
                    no_results_text: 'Oops, nothing found!'
                }
            }
            for (var selector in config) {
                $(selector).chosen(config[selector]);
            }

            var ue = UE.getEditor('editor', {
//                toolbars: [
//                    ['fullscreen', 'source', 'undo', 'redo', 'bold']
//                ],
                initialFrameHeight : 500,
                initialFrameWidth : null,
            });
            $('body').on('click','#edui3_body',function(){
                if($('#edui3_state').hasClass('edui-state-checked')){//全屏，隐藏菜单栏
                    $('#navbar-menu').hide();
                }else{
                    $('#navbar-menu').show();
                }
            });
            $(".ajax-form").on("success", function (event, result) {
                if (result.state == 'success') {
                    swal(result.message, "", "success")
                    setTimeout("window.location.href = '/admin/article'", 1000);
                } else {
                    toastr.options.timeOut = 5000;
                    toastr.error(result.message);
                }
            })
        });
    </script>
@endsection
