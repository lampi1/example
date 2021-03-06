@extends('daran::layouts.master')

@section('title')
    @lang('daran::user.edit')
    @parent
@endsection

@section('content')
    <div id="app" class="row">
        <div class="col-12 mb-3">
            <h3>@lang('daran::user.edit')</h3>
        </div>
        <div class="col-12 mb-3">
            <form id="pageForm" class="form-template-1" method="post" action="{{ route('admin.users.update',['user'=>$user->id]) }}" enctype="multipart/form-data">
                @include('daran::users.form')
                <div class="row">
                    <div class="col-12">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="_method" value="put" />
                        <button type="submit" id="save-publish" class="btn btn-primary">@lang('daran::common.save')</button>
                        <a href="{{ url()->previous() }}" id="bt-annulla" class="btn btn-info">@lang('daran::common.discard')</a>

                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('footer_scripts')
    @parent

    <script type="text/javascript">
        urldelete = "{{ config('app.url') }}/admin-api/users/";
        var locale = '{{app()->getLocale()}}';
        </script>
@endsection
