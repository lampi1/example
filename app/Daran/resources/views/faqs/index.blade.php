@extends('daran::layouts.master')

@section('title')
    @lang('daran::faq.faqs')
@endsection

@section('header_styles')
    @parent
    @routes
@endsection

@section('content')
@include('daran::layouts._messages')
    <div class="row">
        <div class="col-12 mb-2 text-right">
            <!-- Actions -->
            <a href="{{ route('admin.faq-categories.index') }}" class="btn btn-secondary">@lang('daran::common.categories')</a>
            @can('create faq')
                <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary">@lang('daran::common.create')</a>
            @endcan
        </div>
        <div class="col-12">
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3" id="ref" api-url="">
            <h3>@lang('daran::faq.faqs')</h3>
        </div>
        <div class="col-12 mb-3" id="app">
            <daran-vuetable ref="vuetable"
                fields-array="Faq"
                api-url="{{route('admin-api.faqs.index')}}?lang={{session('working_lang', Lang::getLocale())}}"
                :paginate="25"
                :route-prefix="'admin.faqs'"
                :route-api-prefix="'admin-api.faqs'"
                :is-sortable="false"
                :sort-order="[{field: 'title', direction: 'asc'}]"
                >
            </daran-vuetable>
        </div>
    </div>
@endsection

@section('footer_scripts')
    @parent

@endsection
