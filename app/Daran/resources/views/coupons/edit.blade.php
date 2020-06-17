@extends('daran::layouts.master')

@section('title')
    @lang('daran::coupon.edit-coupon')
    @parent
@endsection

@section('content')
    <div id="app" class="row">
        <div class="col-12 mb-3">
            <h3>@lang('daran::coupon.edit-coupon')</h3>
        </div>
        <div class="col-12 mb-3">
            <form id="couponForm" class="form-template-1" method="post" action="{{ route('admin.coupons.update',['coupon'=>$coupon->id]) }}" enctype="multipart/form-data">
                @include('daran::coupons.form')
                <div class="row">
                    <div class="col-12">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="_method" value="put" />
                        <button type="submit" class="btn btn-primary" id="save-publish">@lang('daran::common.save')</button>
                        <a href="{{ url()->previous() }}" id="bt-annulla" class="btn btn-info">@lang('daran::common.discard')</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @include('daran::layouts._modal_delete')
@endsection

@section('footer_scripts')
    @parent

    <script type="text/javascript">
        urldelete = "{{ config('app.url') }}/admin-api/coupons/";
        var locale = '{{app()->getLocale()}}';

        var categorie = Array();
        var categoria = 0;
        $(document).ready(function(){
            @foreach ($categories as $categoria)
                var item = {}
                item.family_id = "{{$categoria->family_id}}";
                item.value = "{{$categoria->id}}";
                item.text = "{{$categoria->name}}";
                categorie.push(item);
            @endforeach

            @if($coupon->category_id)
                categoria = {{$coupon->category_id}};
            @else
                categoria = '';
            @endif
            $('#family_id').trigger("change");

            @if(\Carbon\Carbon::now() >= $coupon->date_start)
                $('#discount').prop('readonly',true);
                $('#amount').prop('readonly',true);
                $('#category_id').prop('disabled',true);
                $('#family_id').prop('disabled',true);
                $('#user_id').prop('disabled',true);
                $('#date_start').prop('readonly',true);
                $('#code').prop('readonly',true);
            @endif

            setTimeout(function(){
                _isDirty = false;
            }, 1000);
        });

        // mostro nascondo categorie a seconda della famiglia
        $('#family_id').change(function(){
            var family_id = $('#family_id').val();

            $("#category_id").children('option').remove();
            $("#category_id").append('<option value="">Seleziona</option>');
            for(var i=0;i<categorie.length;i++){
                if(categorie[i].family_id == family_id){
                    $("#category_id").append('<option value="'+categorie[i].value+'">'+categorie[i].text+'</option>');
                }
            }

            if(categoria > 0){
                $("#category_id").val(categoria).trigger("change");
                categoria = 0;
            }
        });

        </script>
@endsection
