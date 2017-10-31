@extends('admin.layout.index')
@section('title', '标签')
@section('link')
    <link href="{{ URL::asset('/css/plugins/tag/jquery.tag-editor.css') }}" rel="stylesheet">
    <style>
        .tag-editor{
            margin-left: 10%;
        }
        .btn{

        }
    </style>
@endsection

@section('page')
    <div class="wrapper article">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">
                <div class="ibox">
                    <div class="ibox-content">
                        <div>
                            <h2>标签管理</h2>
                        </div>
                        <div class="clients-list">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="full-height-scroll">
                                        <form class="form-horizontal ajax-form" action="/admin/tag" method="post">
                                            <div class="form-group">
                                                <textarea id="tag" name="tag"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-4 col-sm-4">
                                                    <button class="btn btn-block btn-primary" type="submit">保 存</button>
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
    <script src="{{ URL::asset('/js/plugins/tag/jquery.tag-editor.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#tag').tagEditor({
                initialTags: [{!! $tags !!}],
                delimiter: ', ',
                placeholder: 'Enter tags ...'
            });
            $('.tag-editor').addClass('col-sm-10');
            $(".ajax-form").on("success", function (event, result) {
                if (result.state == 'success') {
                    swal({
                        title: result.message,
                        text: "",
                        timer: 1000,
                        showConfirmButton: false,
                        type: "success"
                    });
                } else {
                    toastr.options.timeOut = 5000;
                    toastr.error(result.message);
                }
            })
        });
    </script>
@endsection
