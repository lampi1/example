@extends('daran::layouts.master')

@section('title')
    @lang('daran::coupon.create-coupon')
    @parent
@endsection

@section('content')
    <div id="app" class="row">
        <div class="col-12 mb-3">
            <h3>@lang('daran::coupon.create-coupon')</h3>
        </div>
        <div class="col-12 mb-3">
            <form id="faqForm" class="form-template-1" method="post" action="{{ route('admin.coupons.store') }}" enctype="multipart/form-data">
                @include('daran::coupons.form')
                <div class="row">
                    <div class="col-12">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <button type="submit" class="btn btn-primary" id="save-publish">@lang('daran::common.save')</button>
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
        var locale = '{{app()->getLocale()}}';

        var categorie = Array();

        $(document).ready(function(){
            @foreach ($categories as $categoria)
                var item = {}
                item.family_id = "{{$categoria->family_id}}";
                item.value = "{{$categoria->id}}";
                item.text = "{{$categoria->name}}";
                categorie.push(item);
            @endforeach
        });

        // mostro nascondo categorie a seconda della famiglia
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
