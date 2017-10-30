@extends('base.base')

@section('head_menu')
    @include('base.header')
@endsection

@section('side')
    @include('base.side')
@endsection

@section('content')
    <h2 class="sub-header">Section title</h2>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Header</th>
                <th>Header</th>
                <th>Header</th>
                <th>Header</th>
            </tr>
            </thead>
            <tbody>

            <tr>
                <td>1,001</td>
                <td>Lorem</td>
                <td>ipsum</td>
                <td>dolor</td>
                <td>sit</td>
            </tr>

            </tbody>
        </table>
    </div>
@endsection
@section('page')
    {{ $paginator->links('vendor/pagination/bootstrap-4') }}
@endsection


@section('js')
    <script>
        console.log("ling --> test");
    </script>
@endsection