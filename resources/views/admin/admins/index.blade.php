@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Admin Index') }} <a href="{{route('admins.create')}}" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i> {{ __('create') }}</a></div>

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

                                        <a class="btn btn-small btn-success" href="admins/{{ $value->id }}">{{ __('Show') }}</a>
                                        <a class="btn btn-small btn-info" href="admins/{{ $value->id }}/edit">{{ __('Edit') }}</a>

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
