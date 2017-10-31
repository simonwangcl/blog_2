<nav class="navbar-default navbar-static-side" role="navigation" id="navbar-menu">
    <div class="nav-close"><i class="fa fa-times-circle"></i>
    </div>
    <div class="sidebar-collapse">
        <ul class="nav" id="side-menu">
            <li class="nav-header">
                <div class="profile-element">
                    <span>
                        <a href="/admin/info?type=avatar">
                        <img alt="image" class="img-circle" @if($userModel['image'])src="{{$userModel['image']}}"@else src="/img/default/avatar65.jpg" @endif style="width: 65px"/>
                        </a>
                    </span>
                        <span class="clear" style="margin-left:10px">
                            <a href="/admin/info?type=info">
                                <span class="block m-t-xs">
                                    <strong class="font-bold">{{ucwords($userModel['name'])}}</strong>
                                </span>
                            </a>
                            <span class="text-muted text-xs block">
                                {{ $userModel->role->name }}
                            </span>
                        </span>
                </div>
            </li>
            @foreach($userMenu as $menu)
                <li class="menus-list">
                    <a href="{{$menu['path']}}">
                        <i class="{{$menu['icon']}}"></i>
                        <span class="nav-label">{{$menu['name']}}</span>
                        @if($menu['children'])
                            <span class="fa arrow"></span>
                        @endif
                    </a>
                    @if($menu['children'])
                        <ul class="nav nav-second-level">
                            @foreach($menu['children'] as $child)
                                <li>
                                    <a href="{{$child['path']}}" data-index="0">{{$child['name']}}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</nav>