<div class="col-xs-12 col-sm-12 col-md-4">
    @if($menuStickies->count())
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <h3>推荐阅读</h3>
                @foreach($menuStickies as $sticky)
                    <p><a href="/post/{{ $sticky->id }}">{{ $sticky->title }}</a></p>
                @endforeach
            </div>
        </div>
    @endif
    @if($menuHotArticles->count())
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <h3>热门文章</h3>
                @foreach($menuHotArticles as $sticky)
                    <p><a href="/post/{{ $sticky->id }}">{{ $sticky->title }}</a></p>
                @endforeach
            </div>
        </div>
    @endif
    {{--    @if($menuComments)
        <div class="row" id="menuComment">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <h3>最新评论</h3>
                @foreach($menuComments as $comment)
                    <div class="col-sm-3">
                        <img src="{{ $comment->user->image }}">
                    </div>
                    <div class="col-sm-9">
                        <p><a href="/post/{{ $comment->article_id }}">{{ $comment->content }}</a></p>
                    </div>
                @endforeach
            </div>
        </div>
        @endif--}}
    @if($menuTags->count())
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <h3>所有标签</h3>
                @foreach($menuTags as $tag)
                    <ul class="list-unstyled list-inline">
                        <li class="tags{{ rand(1,12) }}">
                            <a href="/?tag={{ $tag->id }}">{{ $tag->name }}({{ $tag->articles }})</a>
                        </li>
                    </ul>
                @endforeach
            </div>
        </div>
    @endif
    @if($friendLink->count())
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <h3>友情链接</h3>
                @foreach($friendLink as $friend)
                    <ul class="list-unstyled list-inline">
                        <li class="tags{{ rand(1,12) }}">
                            <a href="{{ $friend->href }}">{{ $friend->name }}</a>
                        </li>
                    </ul>
                @endforeach
            </div>
        </div>
    @endif
    @if($menuResumes->count())
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <h3><a href="/about"  style="color: #333;">个人资料</a></h3>
                @foreach($menuResumes as $resume)
                    <p>{{ $resume->name }}：{{ $resume->content }}</p>
                @endforeach
            </div>
        </div>
    @endif
</div>