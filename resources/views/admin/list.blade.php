@extends('base.base')

@section('css')
    <link rel="stylesheet" href="{{ asset('bootstrap/dist/css/bootstrap-datetimepicker.min.css') }}">
@endsection

@section('head_menu')
    @include('base.header')
@endsection

@section('side')
    @include('base.side')
@endsection

@section('content')

    <div class="row search">
        <form action="/list" method="GET" id="search_form">

            <div class="col-lg-12">
                <div role="form" class="form-inline">
                    <div class="form-group col-lg-4" style="margin-bottom:5px;">
                        <label>id：</label>
                        <input type="text" class="form-control" name="id" value="{{ $params['id']??'' }}">
                    </div>

                    <div class="form-group col-lg-4" style="margin-bottom:5px;">
                        <label>标题：</label>
                        <input type="text" class="form-control" name="title" value="{{ $params['title']??'' }}">
                    </div>

                    <div class="form-group col-lg-4" style="margin-bottom:5px;">
                        <label>标题：</label>
                        <select name="category_id" class="form-control">
                            @if(empty($params['category_id']))
                                <option value="" selected>全部</option>
                            @else
                                <option value="">全部</option>
                            @endif
                            @foreach($category as $k => $c )

                                @if($k == ($params['category_id'] ?? 0) && !empty($params['category_id']))
                                    <option value="{{ $k }}" selected>{{ $c }}</option>
                                @else
                                    <option value="{{ $k }}">{{ $c }}</option>
                                @endif

                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div role="form" class="form-inline">
                    <div class="form-group col-lg-5">
                        <label>更新时间：</label>
                        <div style="display:inline-block;">
                            <input type="text" class="form-control datetime-picker" name="updateTimeStart" value="{{ $params['updateTimeStart']??'' }}">
                            <span>至：</span>
                            <input type="text" class="form-control datetime-picker" name="updateTimeEnd" value="{{ $params['updateTimeEnd']??'' }}">
                        </div>
                    </div>
                    <div class="form-group col-lg-2">
                        <a class="btn btn-default" href="/detail/0">新增</a>
                    </div>

                    <div class="form-group col-lg-4">
                        <input type="hidden" name="page" value="{{ $list->currentPage() }}"/>
                        <button type="submit" class="btn btn-default" id="search_input">查询</button>
                    </div>

                </div>
            </div>

        </form>
    </div>

    <h2 class="sub-header">Section title</h2>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>id</th>
                <th>标题</th>
                <th>电影名称</th>
                <th>类型</th>
                <th>更新时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($list as $l)
                <tr>
                    <td>{{ $l->id }}</td>
                    <td>{{ $l->title }}</td>
                    <td>{{ $l->name }}</td>
                    <td>{{ $category[$l->category_id] }}</td>
                    <td>{{ $l->update_time }}</td>
                    <td><a class="btn btn-default" href="/detail/{{ $l->id }}">编辑</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('page')
    {{ $list->appends($params)->links('vendor/pagination/bootstrap-4') }}
@endsection

@section('js')
    <script src="{{ asset('bootstrap/dist/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('bootstrap/dist/js/bootstrap-datetimepicker.fr.js') }}"></script>
    <script src="{{ asset('bootstrap/dist/js/bootstrap-datetimepicker.zh-CN.js') }}"></script>
    <script>
        console.log("ling --> test");
    </script>
    <script>
        $('#search_form .datetime-picker').datetimepicker({
            language:  'zh-CN',
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayBtn: true,
            forceParse: true,
            todayHighlight: true,
            startView:2,
            minView: 2,
            showMeridian:true,
            minuteStep: 10
        });
    </script>
@endsection