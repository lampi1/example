@extends('daran::layouts.master')

@section('title')
    @lang('daran::item.edit-category')
    @parent
@endsection

@section('content')
    <div id="app" class="row">
        <div class="col-12 mb-3">
            <h3>@lang('daran::item.edit-category')</h3>
        </div>
        <div class="col-12 mb-3">
            <form id="pageForm" class="form-template-1" method="post" action="{{ route('admin.categories.update',['category'=>$category->id]) }}" enctype="multipart/form-data">
                @include('daran::categories.form')
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
        var url_img_cover_delete = "{{ config('app.url') }}"+ "/admin-api/category-image/";
        urldelete = "{{ config('app.url') }}/admin-api/categories/";
        var locale = '{{app()->getLocale()}}';

        $('.input--image').on('click', '.delete',function(e){
            var current_id = {{$category->id}};
            var type = $(this).data('type');
            $.ajax({
                url:url_img_cover_delete + current_id + '?type=' + type,
                type: 'post',
                data: {_method: 'put' },
                success: function (data){
                    if (data.success){
                        e.currentTarget.hidden = true;
                        e.delegateTarget.children[2].children[0].hidden = false;
                        e.delegateTarget.children[1].innerHTML= ""
                    } else {
                        bootbox.alert("@lang('daran::common.default-error')");
                    }
                },
                error: function (data){
                    bootbox.alert("@lang('daran::common.default-error')");
                }
            });
        });
        </script>
@endsection
