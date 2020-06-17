@extends('daran::layouts.master')

@section('title')
    @lang('daran::item.create-item')
    @parent
@endsection

@section('content')
    <div id="app" class="row">
        <div class="col-12 mb-3">
            <h3>@lang('daran::item.create-item')</h3>
        </div>
        <div class="col-12 mb-3">
            <form id="pageForm" class="form-template-1" method="post" action="{{ route('admin.items.store') }}" enctype="multipart/form-data">
                @include('daran::items.form')
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
        var url_img_cover_delete = "{{ config('app.url') }}"+ "/admin-api/item-image/";
        var locale = '{{app()->getLocale()}}';
        var categorie = Array();

        $(document).ready(function(){

        });

        //img 1
        $('.input--image').on('click', '.delete',function(e){
            e.currentTarget.hidden = true;
            e.delegateTarget.children[2].children[0].hidden = false;
            e.delegateTarget.children[1].innerHTML= ""
        });

        $('#family_id').change(function(){
            $('#category_id').prop('disabled', false);
            $("#category_id").val($("#category_id option:first").val());

            var family_id = $('#family_id').val();

            $("#category_id").children('option').remove();
            $("#category_id").append('<option value="">Seleziona</option>');
            for(var i=0;i<categorie.length;i++){
                if(categorie[i].family_id == family_id){
                    $("#category_id").append('<option value="'+categorie[i].value+'">'+categorie[i].text+'</option>');
                }
            }
        });

    </script>
@endsection
