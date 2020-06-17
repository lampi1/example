@extends('daran::layouts.master')

@section('title')
    @lang('daran::redirection.create-redirection')
    @parent
@endsection

@section('content')
    <div id="app" class="row">
        <div class="col-12 mb-3">
            <h3>@lang('daran::redirection.create-redirection')</h3>
        </div>
        <div class="col-12 mb-3">
            <form id="pageForm" class="form-template-1" method="post" action="{{ route('admin.redirections.store') }}" enctype="multipart/form-data">
                @include('daran::redirections.form')
                <div class="row">
                    <div class="col-12">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        @can('create redirection')
                            <button type="submit" id="save-publish" class="btn btn-primary">@lang('daran::common.save')</button>
                        @endcan
                        <a href="{{ url()->previous() }}" id="bt-annulla" class="btn btn-info">@lang('daran::common.discard')</a>
                    </div>
                </div>
            </form>
        </div>
@endsection

@section('footer_scripts')
    @parent
    <script type="text/javascript">
        var locale = '{{app()->getLocale()}}';


    </script>
@endsection
