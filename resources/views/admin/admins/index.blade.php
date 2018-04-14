@extends('layouts.admin')

@section('content')
<div class="container">
    <script lang="">
        function deleteByUrl(url) {
            var form = document.getElementById("delete-form");
            form.setAttribute("action", url);
            form.submit();
        }
    </script>
    <form id="delete-form" method="post" action="{{ route('admin.admins.destroy', ['id' => '__PLACEHOLDER_ID__']) }}" data-parsley-validate class="form-horizontal form-label-left">
        <input type="hidden" name="_token" value="{{ Session::token() }}">
        <input name="_method" type="hidden" value="DELETE">

    </form>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Admin Index') }} <a href="{{route('admin.admins.create')}}" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> {{ __('create') }}</a></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <td>{{ __('ID') }}</td>
                                <td>{{ __('Name') }}</td>
                                <td>{{ __('Email') }}</td>
                                <td>{{ __('Actions') }}</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($admins as $key => $value)
                                <tr>
                                    <td>{{ $value->id }}</td>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->email }}</td>

                                    <td>

                                        <a class="btn btn-small btn-success" href="{{ route('admin.admins.show', ['id' => $value->id]) }}">{{ __('Show') }}</a>
                                        <a class="btn btn-small btn-info" href="{{ route('admin.admins.edit', ['id' => $value->id]) }}">{{ __('Edit') }}</a>
                                        <a class="btn btn-small btn-info" onclick="deleteByUrl('{{ route('admin.admins.destroy', ['id' => $value->id]) }}');" dusk="delete-button-{{ $value->id }}">{{ __('Delete') }}</a>

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
