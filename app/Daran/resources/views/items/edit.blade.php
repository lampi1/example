@extends('daran::layouts.master')

@section('title')
    @lang('daran::item.edit-item')
    @parent
@endsection

@section('content')
    <div id="app" class="row">
        <div class="col-12 mb-3">
            <h3>@lang('daran::item.edit-item')</h3>
        </div>
        <div class="col-12 mb-3">
            <form id="pageForm" class="form-template-1" method="post" action="{{ route('admin.items.update',['item'=>$item->id]) }}" enctype="multipart/form-data">
                @include('daran::items.form')
                <div class="row">
                    <div class="col-6">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="_method" value="put" />
                        <button type="submit" id="save-publish" class="btn btn-primary">@lang('daran::common.save')</button>
                        <a href="{{ url()->previous() }}" id="bt-annulla" class="btn btn-info">@lang('daran::common.discard')</a>
                    </div>
                    <div class="col-6 text-right">
                        <a href="{{route('admin.items.edit_related',['id'=>$item->id])}}" class="btn btn-secondary">@lang('daran::item.manage-related')</a>
                        <a href="{{route('admin.items.edit_images',['id'=>$item->id])}}" class="btn btn-secondary">@lang('daran::item.manage-images')</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-12 mb-3">
            <h3>@lang('daran::item.images')</h3>
        </div>
        <div class="row mb-3">
            @foreach ($item->images as $img)
                <div class="col-2">
                    <img src="{{config('app.url').$img->filename}}" alt="" width="100%" />
                </div>
            @endforeach
        </div>

    </div>

@endsection

@section('footer_scripts')
    @parent


    <script type="text/javascript">
        var url_img_cover_delete = "{{ config('app.url') }}"+ "/admin-api/item-image/";
        urldelete = "{{ config('app.url') }}/admin-api/items/";
        var locale = '{{app()->getLocale()}}';

        var categories = {!!$families!!};

        @if($item->category_id)
            var old_category = {{old('category_id',$item->category_id)}};
        @else
            var old_category = {{old('category_id',0)}};
        @endif

        @if($item->subcategory_id)
            var old_subcategory = {{old('subcategory_id',$item->subcategory_id)}};
        @else
            var old_subcategory = {{old('subcategory_id',0)}};
        @endif

        $(document).ready(function(){
            changeCategories();
            $('#category_id').val(old_category).trigger('change');

            changeSubcategories();
            $('#subcategory_id').val(old_subcategory).trigger('change');

            setTimeout(function(){
                _isDirty = false;
            }, 1000);
        });


        function changeCategories(){
            $('#category_id').empty();
            $('#category_id').append($('<option></option>').attr("value", "").text("Seleziona un valore"));
            if($('#family_id').val() != ''){
                for(var i=0;i<categories.length;i++){
                    if(categories[i].id == $('#family_id').val()){
                        var c_sel = categories[i];
                        for(var j=0;j<c_sel.categories.length;j++){
                            $('#category_id').append($('<option></option>').attr("value", c_sel.categories[j].id).text(c_sel.categories[j].name));
                        }
                    }
                }
            }
        }

        function changeSubcategories(){
            $('#subcategory_id').empty();
            $('#subcategory_id').append($('<option></option>').attr("value", "").text("Seleziona un valore"));
            if($('#family_id').val() != '' && $('#category_id').val() != ''){
                for(var i=0;i<categories.length;i++){
                    if(categories[i].id == $('#family_id').val()){
                        for(var j=0;j<categories[i].categories.length;j++){
                            if(categories[i].categories[j].id == $('#category_id').val()){
                                var c_sel = categories[i].categories[j];
                                for(var x=0;x<c_sel.subcategories.length;x++){
                                    $('#subcategory_id').append($('<option></option>').attr("value", c_sel.subcategories[x].id).text(c_sel.subcategories[x].name));
                                }
                            }
                        }
                    }
                }
            }
        }


        $('.input--image').on('click', '.delete',function(e){
            var current_id = {{$item->id}};
            $.ajax({
                url:url_img_cover_delete + current_id,
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
