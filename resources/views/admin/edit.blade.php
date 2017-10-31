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
    <table class="table table-bordered" style="width: 90%">
        @if(!empty($data->id))
            <tr>
                <td class="col-sm-2">id</td>
                <td class="col-sm-10">{{ $data->id??'' }}</td>
            </tr>
        @endif

        <tr>
            <td class="col-sm-2">标题</td>
            <td class="col-sm-10">
                <input class="form-control" name="title" value="{{ $data->title??'' }}">
            </td>
        </tr>
        <tr>
            <td>名称</td>
            <td>
                <input class="form-control" name=" name" value="{{ $data->name??'' }}">
            </td>
        </tr>
        <tr>
            <td>首页图片</td>
            <td>
                <input class="form-control" name="image_url" value="{{ $data->image_url??'' }}">
            </td>
        </tr>
        <tr>
            <td>分类</td>
            <td>
                <select name="category" class="form-control" style="width: 10%">
                    @foreach($category as $k => $c )

                        @if($k == ($data->category_id ?? 0))
                            <option value="{{ $k }}" selected>{{ $c }}</option>
                        @else
                            <option value="{{ $k }}">{{ $c }}</option>
                        @endif

                    @endforeach
                </select>
            </td>
        </tr>
        <tr>
            <td>更新时间</td>
            <td>
                <input class="form-control datetime-picker" name="update_time" value="{{ $data->update_time ?? date('Y-m-d 08:00:00', time()) }}">
            </td>
        </tr>
        <tr>
            <td>内容</td>
            <td>
                <textarea class="col-sm-11" style="min-height: 400px">
                    {{ $data->content }}
                </textarea>
            </td>
        </tr>

    </table>
@endsection


@section('js')
    <script>
        console.log("ling --> edit");
    </script>
    <script src="{{ asset('bootstrap/dist/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('bootstrap/dist/js/bootstrap-datetimepicker.fr.js') }}"></script>
    <script src="{{ asset('bootstrap/dist/js/bootstrap-datetimepicker.zh-CN.js') }}"></script>
    <script>
        $('.datetime-picker').datetimepicker({
            language:  'zh-CN',
            format: 'yyyy-mm-dd 08:00:00',
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