@extends('admin.layout.index')
@section('title', '角色')
@section('link')

@endsection

@section('page')
    <div class="wrapper article">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">
                <div class="ibox">
                    <div class="ibox-content">
                        <div>
                            <h2>权限管理</h2>
                        </div>
                        <div class="clients-list">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <div class="full-height-scroll">
<form class="form-horizontal" action="/admin/role/{{$role->id}}" method="post">
    {{ csrf_field() }}
    {{ method_field('PUT') }}
    <input type="hidden" value="role" name='type'>
    @foreach($menus as $menu)
        <div class="form-group">
            <label class="col-sm-3 control-label">
                {{$menu->name}} &nbsp;
                <input type="checkbox" value="{{$menu->id}}"
                       name="menus_id[{{$menu->id}}]"
                       id="menu_{{$menu->id}}"> &nbsp; &nbsp;
                &nbsp; &nbsp;
            </label>

            <div class="col-sm-8" style="margin-top:6px">
                @foreach($menu->children as $child)
                    <span class="col-sm-3">
                    <input type="checkbox"
                           value="{{$child->id}}"
                           name="menus_id[{{$child->id}}]"
                           id="child_{{$menu->id}}_{{$child->id}}">  &nbsp; {{$child->name}}
                </span>
                @endforeach
            </div>
        </div>
        @if(!$loop->last)
            <hr style="filter:alpha(opacity=100,finishopacity=0,style=3);margin:0 0 0 25%;padding:0" width="65%" color="#FF0000" size="10"/>
        @endif
    @endforeach

    <div class="form-group center">
        <div class="col-sm-offset-3 col-sm-8">
            <button type="submit" class="btn btn-primary"
                    style="margin-bottom:20px">提 交
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
    <script>
        $(document).ready(function () {
//				根据是否选中一级菜单，全选或全不选二级菜单
            $('body').on('click', "input[id^='menu_']", function () {
                if ($(this).is(':checked')) {
                    $('input[id^="child_' + $(this).val() + '_"]').each(function () {
                        $(this).prop("checked", 'true');
                    })
                } else {
                    $('input[id^="child_' + $(this).val() + '_"]').each(function () {
                        $(this).removeAttr("checked");
                    })
                }
            })
            $('body').on('click', "input[id^='child_']", function () {
                var id = $(this).attr('id');
                id = id.match(/(\S*)_(\S*)_(\S*)/)[2];
                if ($(this).is(':checked')) {
                    id = 'menu_' + id;
                    $('input[id="' + id + '"]').prop("checked", 'true');
                } else {
                    var result = 1;
                    $('input[id^="child_' + id + '_"]').each(function () {
                        if ($(this).is(':checked')) {
                            result = 0;
                        }
                    })
                    if (result) {
                        id = 'menu_' + id;
                        $('input[id="' + id + '"]').removeAttr("checked");
                    }
                }
            })
            @foreach($role->menu as $menu)
            $('input[value="'+{{$menu}}+'"]' ).prop("checked", 'true');
            @endforeach
        });
    </script>
@endsection
