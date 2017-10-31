@extends('web.layout.index')
@section('content')
    <div class="col-xs-12 col-sm-12 col-md-8">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                @if($articles->count())
                    @foreach($articles as $article)
                        <p><h3><a href="/post/{{ $article->id }}">{{ $article->title }}</a></h3></p>
                        <p><i class="fa fa-clock-o"></i>&nbsp;&nbsp;{{ $article->created_at }}&nbsp;&nbsp;/&nbsp;&nbsp;
                            <i class="fa fa-folder-open-o"></i>&nbsp;&nbsp;
                            <a href="/?category={{ $article->category->id }}">{{ $article->category->name }}</a>&nbsp;&nbsp;/&nbsp;&nbsp;
                            <i class="fa fa-user"></i>&nbsp;&nbsp;{{ $article->author }}&nbsp;&nbsp;/&nbsp;&nbsp;
                            <i class="fa fa-eye"></i>&nbsp;&nbsp;{{ $article->count }}&nbsp;&nbsp;/&nbsp;&nbsp;
                            {{--<i class="fa fa-commenting-o"></i>&nbsp;&nbsp;{{ $article->comments_count }}&nbsp;&nbsp;/&nbsp;&nbsp;--}}
                            <i class="fa fa-thumbs-o-up"></i>&nbsp;&nbsp;{{ $article->points_count }}
                        </p>
                        <div class="col-sm-12">
                            <div class="col-sm-4 article-cover"><a href="/post/{{ $article->id }}"><img src="{{ $article->cover }}"></a></div>
                            <div class="col-sm-8">
                                <p class="sketch"><a href="/post/{{ $article->id }}">{{ $article->sketch }}</a></p>
                                <p><i class="fa fa-tags"></i>&nbsp;&nbsp;
                                    @foreach($article->tags as $tag)
                                        <a href="/?tag={{ $tag->id }}">{{ $tag->name }}</a>&nbsp;&nbsp;
                                    @endforeach
                                </p>
                            </div>
                        </div>
                        <div class="page-header col-sm-12"></div>
                    @endforeach
                    <div class="page-links">{{ $articles->links() }}</div>
                    @else
                    <p class="article-message">{{ $param['message'] or ''}}</p>
                    @endif
            </div>
        </div>
    </div>
@endsection