@extends('daran::layouts.master')

@section('title')
    @lang('daran::item.massive-discounts')
    @parent
@stop


@section('content')
    <div id="app" class="row">
        <div class="col-12 mb-3">
            <h3>
                @lang('daran::item.massive-discounts')<br>
                <span>@lang('daran::item.massive-discounts-p1')</span><br>
                <span>@lang('daran::item.massive-discounts-p2')</span><br>
            </h3>
        </div>
        <div class="col-12 mb-3">
            <form id="pageForm" class="form-template-1" method="post" action="{{ route('admin.items.save-discount') }}" enctype="multipart/form-data">
                <div id="form" class="row">
                    <div class="col-4">
                        <div class="mb-3">
                            <label class="control-label">@lang('daran::item.family')</label>
                            <select name="family_id" id="family_id" class="select2">
                                <option value="">@lang('daran::common.select')</option>
                                @foreach($families as $fam)
                                    <option value="{{$fam->id}}" @if(old('category_id') == $fam->id) selected="selected" @endif>{{$fam->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <label class="control-label">@lang('daran::item.category')</label>
                            <select name="category_id" id="category_id" class="select2">
                                <option value="">@lang('daran::common.select')</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <label class="control-label">@lang('daran::item.discount')</label>
                            <input type="number" required="required" name="discount" min="0" step="0.01" value="{{old('discount')}}" />
                        </div>
                    </div>
                </div>
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
@stop

@section('footer_scripts')
    @parent
    <script type="text/javascript">
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

        var tipologie = Array();

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
        });

    </script>
@endsection
