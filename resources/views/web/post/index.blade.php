@extends('web.layout.index')
@section('content')
    <div class="col-xs-12 col-sm-12 col-md-8">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12" id="article-page" user-id="{{ $userModel->id or 0}}">
                <p class="title">{{ $article->title }}<p>
                <p class="info"><i class="fa fa-clock-o"></i>&nbsp;&nbsp;{{ $article->created_at }}&nbsp;&nbsp;/&nbsp;&nbsp;
                    <i class="fa fa-folder-open-o"></i>&nbsp;&nbsp;
                    <a href="/?category={{ $article->category->id }}">{{ $article->category->name }}</a>&nbsp;&nbsp;/&nbsp;&nbsp;
                    <i class="fa fa-user"></i>&nbsp;&nbsp;{{ $article->author->name }}&nbsp;&nbsp;/&nbsp;&nbsp;
                    <i class="fa fa-eye"></i>&nbsp;&nbsp;{{ $article->count }}
                </p>
                {!! $article->content !!}
                <p class="tag"><i class="fa fa-tags"></i>：
                    @foreach($article->tags as $tag)
                        <a href="/?tag={{ $tag->id }}">{{ $tag->name }}</a>&nbsp;&nbsp;
                    @endforeach
                    <p class="point" style="text-align: center">
                        <a href="javascript:void(0);" class="point-display" id="point-true"
                           @if(!$userModel || !$point) style="display:none" @endif>
                            <i class="fa fa-thumbs-up" style="font-size: 50px;color: #E3534F"></i>
                        </a>
                        <a href="javascript:void(0);" class="point-display" id="point-false"
                           @if($userModel && $point) style="display:none" @endif>
                            <i class="fa fa-thumbs-o-up" style="font-size: 50px;color: #337ab7;"></i>
                        </a>
                    </p>
                <p class="point">( <span class="point-num">{{ $article->points }}</span> )</p>
                <p>
            </div>
        </div>
        @if($show)
        <div>
            测试
        </div>
        @endif
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            $('body').on('click', '.point-display', function () {
                if ($('#article-page').attr('user-id') != 0) {
                    var html = $('.point-num').html();
                    $.ajax({
                        url: '/post/{{ $article->id }}',
                        type: 'PUT',
                        dataType: "json",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: function (data) {
                            if (data.result) {
                                $('#point-true').hide();
                                $('#point-false').show();
                                $('.point-num').html(parseInt(html) - 1);
                            } else {
                                $('#point-true').show();
                                $('#point-false').hide();
                                $('.point-num').html(parseInt(html) + 1);
                            }
                        }
                    });
                } else {
                    var title = "请于 <a href='/qq' style='color: #43ff25;'>QQ登录</a> 后继续操作！";
                    layer.tips(title, $(this).children('i'), {tips: [1, '#3595CC'], time: 5000});
                }
            });
        });
    </script>
@endsection