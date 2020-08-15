@extends('daran::layouts.master')

@section('title')
    @lang('daran::setting.edit-general')
    @parent
@endsection

@section('content')
    <div id="app" class="row">
        <div class="col-12 mb-3">
            <h3>@lang('daran::setting.edit-general')</h3>
        </div>
        <div class="col-12 mb-2 text-right">
            @can('read redirection')
                <a href="{{ route('admin.redirections.index') }}" class="btn btn-primary">@lang('daran::common.redirections')</a>
            @endcan
            <a href="{{ route('admin.contact-settings.edit') }}" class="btn btn-primary">@lang('daran::common.contacts')</a>
            @if(config('daran.ecommerce_enabled'))
                <a href="{{ route('admin.ecommerce-settings.edit') }}" class="btn btn-primary">@lang('daran::common.ecommerce')</a>
            @endif
        </div>
        <div class="col-12 mb-3">
            <form action="{{ route('admin.settings.update', ['branding_id' => $branding->id, 'seo_id' => $seo->id]) }}" id="settingGeneralForm" method="post" class="form-template-1" enctype="multipart/form-data">
                @include('daran::settings.general.form-general')

                <h3>@lang('daran::setting.edit-seo')</h3>
                @include('daran::settings.general.form-seo')
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
@endsection

@section('footer_scripts')
    @parent
    <script type="text/javascript">

    </script>
@endsection
