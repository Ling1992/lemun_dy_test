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
    <button class="btn btn-danger" style="margin-bottom: 10px" onclick="_save()">保存</button>
    <table class="table table-bordered" style="width: 90%">
        @if(!empty($data->id))
            <tr>
                <td class="col-sm-2">id</td>
                <td class="col-sm-10">
                    <input class="form-control" name="id" value="{{ $data->id??'' }}">
                </td>
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
                <input class="form-control" name="name" value="{{ $data->name??'' }}">
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
                <select name="category_id" class="form-control" style="width: 10%">
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
                <script id="editor" type="text/plain" class="col-sm-10" style="height:500px;">
                    {!! $data->content??'' !!}
                </script>
                <script> </script>
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

    <script type="text/javascript" charset="utf-8" src="{{ asset('ueditor/ueditor.config.js') }}"></script>
    <script type="text/javascript" charset="utf-8" src="{{ asset('ueditor/ueditor.all.min.js') }}"> </script>
    <!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
    <!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
    <script type="text/javascript" charset="utf-8" src="{{ asset('ueditor/lang/zh-cn/zh-cn.js') }}"></script>
    <script>
        //实例化编辑器
        //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
        var ue = UE.getEditor('editor');
    </script>

    <script>
        function _save() {
            console.log('save');

            var data = {};
            data.title = $('input[name="title"]').val();
            data.name = $('input[name="name"]').val();
            data.image_url = $('input[name="image_url"]').val();
            data.category_id = $('select[name="category_id"]').val();
            data.update_time = $('input[name="update_time"]').val();
            data.content = ue.getContent();
            console.log(data);
            var options = {};
            options.url = "{{ url('addOneDY') }}";
            options.data = data;
            options.type = "POST";
            options.success = function (info) {
                console.log(info);
            };
            $.ajax(options);
        }
    </script>


@endsection