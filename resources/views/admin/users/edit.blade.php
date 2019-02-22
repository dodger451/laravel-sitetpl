@extends('layouts.admin')

@section('content')
    <div class="container">

        <div class="clearfix"></div>
        <div class="row justify-content-center">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>{{ _('Update User') }} <a href="{{route('admin.users.index')}}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> {{ __('back') }} </a></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br />
                        <form method="post" action="{{ route('admin.users.update', ['id' => $user->id]) }}" data-parsley-validate class="form-horizontal form-label-left">

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{ __('name') }} <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" value="{{ $user->name }}" id="name" name="name" class="form-control col-md-7 col-xs-12" dusk="name-input">
                                    @if ($errors->has('name'))
                                        <span class="help-block" dusk="name-error">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">{{ __('email') }} <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" value="{{ $user->email }}" id="email" name="email" class="form-control col-md-7 col-xs-12" dusk="email-input">
                                    @if ($errors->has('email'))
                                        <span class="help-block" dusk="email-error">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                            </div>





                            <div class="ln_solid"></div>

                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <input type="hidden" name="_token" value="{{ Session::token() }}">
                                    <input name="_method" type="hidden" value="PUT">
                                    <button type="submit" class="btn btn-success" dusk="submit-button">{{ __('update') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop