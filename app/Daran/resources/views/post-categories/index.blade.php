
@extends('daran::layouts.master')

@section('title')
    @lang('admin/post.post-category')
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
            <a href="{{ route('admin.posts.index') }}" class="btn btn-info">@lang('daran::post.posts')</a>
            @can('create post')
                <a href="{{ route('admin.post-categories.create') }}" class="btn btn-primary">@lang('daran::common.create')</a>
            @endcan
        </div>
        <div class="col-12">
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3" id="ref" api-url="">
            <h3>@lang('daran::post.post-category')</h3>
        </div>
        <div class="col-12 mb-3" id="app">
            <daran-vuetable ref="vuetable"
                fields-array="Category"
                api-url="{{route('admin-api.post-categories.index')}}?lang={{session('working_lang', Lang::getLocale())}}"
                :paginate="25"
                :route-prefix="'admin.post-categories'"
                :route-api-prefix="'admin-api.post-categories'"
                :is-sortable="true"
                :sort-order="[{field: 'priority', direction: 'asc'}]"
                >
            </daran-vuetable>
        </div>
    </div>

@endsection

@section('footer_scripts')
    @parent

@endsection
