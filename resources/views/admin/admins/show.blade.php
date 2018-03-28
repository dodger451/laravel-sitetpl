@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Admin show') }}</div>

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
                                <tr>
                                    <td>{{ $admin->id }}</td>
                                    <td>{{ $admin->name }}</td>
                                    <td>{{ $admin->email }}</td>

                                    <td>

                                        <a class="btn btn-small btn-info" href="{{ route('admin.admins.edit', ['id' => $admin->id]) }}">{{ __('Edit') }}</a>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
