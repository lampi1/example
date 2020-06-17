@extends('daran::layouts.master')

@section('title')
    @lang('daran::item.create-subcategory')
    @parent
@endsection

@section('content')
    <div id="app" class="row">
        <div class="col-12 mb-3">
            <h3>@lang('daran::item.create-subcategory')</h3>
        </div>
        <div class="col-12 mb-3">
            <form id="pageForm" class="form-template-1" method="post" action="{{ route('admin.subcategories.store') }}" enctype="multipart/form-data">
                @include('daran::subcategories.form')
                <div class="row">
                    <div class="col-12">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
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
        var url_img_cover_delete = "{{ config('app.url') }}"+ "/admin-api/subcategory-image/";
        var locale = '{{app()->getLocale()}}';
        var categories = {!!$families!!};

        @if($subcategory->category_id)
            var old_category = {{old('category_id',$subcategory->category_id)}};
        @else
            var old_category = {{old('category_id',0)}};
        @endif

        $('#family_id').on('change',changeCategories);

        $(document ).ready(function() {
            changeCategories();
            $('#category_id').val(old_category).trigger('change');
        });

        //img 1
        $('.input--image').on('click', '.delete',function(e){
            e.currentTarget.hidden = true;
            e.delegateTarget.children[2].children[0].hidden = false;
            e.delegateTarget.children[1].innerHTML= ""
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

    </script>
@endsection
