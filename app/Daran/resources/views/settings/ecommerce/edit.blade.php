@extends('daran::layouts.master')

@section('title')
    @lang('daran::setting.edit-ecommerce')
    @parent
@endsection


@section('content')
    <div id="app" class="row">
        <div class="col-12 mb-3">
            <h3>@lang('daran::setting.edit-ecommerce')</h3>
        </div>
        <div class="col-12 mb-2 text-right">
            @can('read redirection')
                <a href="{{ route('admin.redirections.index') }}" class="btn bt-primary">@lang('daran::common.redirections')</a>
            @endcan
            <a href="{{ route('admin.settings.edit') }}" class="btn btn-primary">@lang('daran::common.general')</a>
            <a href="{{ route('admin.contact-settings.edit') }}" class="btn btn-primary">@lang('daran::common.contacts')</a>
        </div>
        <div class="col-12 mb-3">
            <div class="row">
                <div class="col-md-3"><label class="control-label">@lang('daran::setting.free_from')</label></div>
                <div class="col-md-2"><label class="control-label">@lang('daran::setting.free_start')</label></div>
                <div class="col-md-2"><label class="control-label">@lang('daran::setting.free_end')</label></div>
            </div>
            @foreach ($fees as $fee)
                <div class="row mb-2" id="fee_{{$fee->id}}">
                    <div class="col-md-3"><p>{{number_format($fee->shipping_free_from,2,',','.')}}</p></div>
                    <div class="col-md-2">{{$fee->valid_from->format('d/m/Y')}}</div>
                    <div class="col-md-2">{{optional($fee->valid_until)->format('d/m/Y')}}</div>
                    <div class="col-md-3">
                        <button class="btn btn-primary fee-edit-btn" data-id="{{$fee->id}}" data-free="{{$fee->shipping_free_from}}" data-from="{{$fee->valid_from->format('d/m/Y')}}" data-to="{{optional($fee->valid_until)->format('d/m/Y')}}">@lang('daran::common.edit')</button>
                        <button class="btn btn-danger fee-delete-btn" data-id="{{$fee->id}}">@lang('daran::common.delete')</button>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="col-12 mb-3 mt-3">
            <form id="postForm" class="form-template-1" method="post" action="{{ route('admin.ecommerce-settings.update') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <input type="hidden" name="id" id="setting_id" value="" />
                <div class="row">
                    <div class="col-3">
                        <label class="control-label">@lang('daran::setting.free_from')*</label>
                        <input type="number" name="shipping_free_from" id="shipping_free_from" min="10" step="0.01" required="required"  value="" />
                    </div>
                    <div class="col-3">
                        <label class="control-label">@lang('daran::setting.free_start')*</label>
                        <input type="text" class="form_date" name="valid_from" id="valid_from" required value=""></input>
                    </div>
                    <div class="col-3">
                        <label class="control-label">@lang('daran::setting.free_end')</label>
                        <input type="text" class="form_date" id="valid_until" name="valid_until" value=""></input>
                    </div>
                    <div class="col-3 text-right pt-2">
                        <button type="submit" class="btn btn-primary">@lang('daran::common.save')</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-12 mb-3">
            <h3>@lang('daran::setting.active-countries')</h3>
        </div>
        <div class="col-12 mb-3">
            <div class="row">
                <div class="col-md-3"><label class="control-label">@lang('daran::setting.country_code')</label></div>
                <div class="col-md-2"><label class="control-label">@lang('daran::setting.country')</label></div>
                <div class="col-md-2"><label class="control-label">@lang('daran::setting.duty')</label></div>
            </div>
            @foreach ($countries as $country)
                <div class="row mb-2" id="country_{{$country->code}}">
                    <div class="col-md-2">{{$country->code}}</div>
                    <div class="col-md-3">{{$all_countries->firstWhere('key',$country->code)->value}}</div>
                    <div class="col-md-2"><p>{{number_format($country->cost,2,',','.')}}</p></div>
                    <div class="col-md-3">
                        <button class="btn btn-primary country-edit-btn" data-id="{{$country->code}}" data-cost="{{$country->cost}}">@lang('daran::common.edit')</button>
                        <button class="btn btn-danger country-delete-btn" data-id="{{$country->code}}" data-name="{{$country->code}}">@lang('daran::common.delete')</button>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="col-12 mb-3 mt-3">
            <form id="postForm" class="form-template-1" method="post" action="{{ route('admin.ecommerce-countries.update') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <input type="hidden" name="id" id="country_id" value="" />
                <div class="row">
                    <div class="col-3">
                        <label class="control-label">@lang('daran::setting.country')*</label>
                        <select name="country_code" id="country_code" class="select2" required="required" >
                            <option value=""></option>
                            @foreach ($new_countries as $new)
                                <option value="{{$new->key}}">{{$new->value}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-2">
                        <label class="control-label">@lang('daran::setting.duty')*</label>
                        <input type="number" name="cost" id="cost" min="0" step="0.01" required="required"  value="" />
                    </div>
                    <div class="col-2 text-right pt-2">
                        <button type="submit" class="btn btn-primary">@lang('daran::common.save')</button>
                    </div>
                </div>
            </form>
        </div>

@endsection

@section('footer_scripts')
    @parent
    @routes
    <script type="text/javascript">

        $('.fee-edit-btn').on('click',function(e){
            e.preventDefault();
            $('#setting_id').val($(this).data('id'));
            $('#shipping_free_from').val($(this).data('free'));
            $('#valid_from').val($(this).data('from'));
            $('#valid_until').val($(this).data('to'));
        });

        $('.fee-delete-btn').on('click',function(e){
            e.preventDefault();
            var id = $(this).data('id');
            var nome = '';
            bootbox.confirm({
                centerVertical: true,
                buttons: {
                    confirm: {
                        label: 'Elimina',
                        className: 'btn btn-primary'
                    },
                    cancel: {
                        label: 'Annulla',
                        className: 'btn btn-info'
                    }
                },
                title: '<h3>Conferma Eliminazione</h3>',
                message: '<p>Sei sicuro di volere eliminare il record ' + '<b class="fc--blue">' + nome + '</b>?</p>',
                callback: function(result){
                    if (result == true) {
                        $.ajax({
                            method: 'delete',
                            url: route('admin-api.ecommerce-settings.delete', {id: id}),
                            success: function(response){
                                if(response.success){
                                    $('#fee_'+id).remove();
                                }
                            },
                            error: function(respose){
                                console.log(response);
                            }
                        })
                    }
                }
            })
        });

        $('.country-edit-btn').on('click',function(e){
            e.preventDefault();
            $('#country_id').val($(this).data('id'));
            $('#country_code').val('');
            $('#country_code').trigger('change');
            $('#country_code').attr('disabled',true);
            $('#country_code').attr('required',false);
            $('#cost').val($(this).data('cost'));
        });

        $('.country-delete-btn').on('click',function(e){
            e.preventDefault();
            var id = $(this).data('id');
            var nome = $(this).data('name');
            bootbox.confirm({
                centerVertical: true,
                buttons: {
                    confirm: {
                        label: 'Elimina',
                        className: 'btn btn-primary'
                    },
                    cancel: {
                        label: 'Annulla',
                        className: 'btn btn-info'
                    }
                },
                title: '<h3>Conferma Eliminazione</h3>',
                message: '<p>Sei sicuro di volere eliminare il record ' + '<b class="fc--blue">' + nome + '</b>?</p>',
                callback: function(result){
                    if (result == true) {
                        $.ajax({
                            method: 'delete',
                            url: route('admin-api.ecommerce-countries.delete', {id: id}),
                            success: function(response){
                                if(response.success){
                                    $('#country_'+id).remove();
                                }
                            },
                            error: function(respose){
                                console.log(response);
                            }
                        })
                    }
                }
            })
        });
    </script>

@endsection
