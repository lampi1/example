@extends('daran::layouts.master')

@section('title')
    @lang('daran::setting.edit-contacts')
    @parent
@endsection


@section('content')
    <div id="app" class="row">
        <div class="col-12 mb-3">
            <h3>@lang('daran::setting.edit-contacts')</h3>
        </div>
        <div class="col-12 mb-2 text-right">
            @can('read redirection')
                <a href="{{ route('admin.redirections.index') }}" class="btn btn-primary">@lang('daran::common.redirections')</a>
            @endcan
            <a href="{{ route('admin.settings.edit') }}" class="btn btn-primary">@lang('daran::common.general')</a>
            @if('config.daran.ecommerce_enabled')
                <a href="{{ route('admin.ecommerce-settings.edit') }}" class="btn btn-primary">@lang('daran::common.ecommerce')</a>
            @endif
        </div>
        <div class="col-12 mb-3">
            <form action="{{ route('admin.contact-settings.update', ['id' => $contact->id]) }}" id="contactForm" method="post" class="form-template-1" enctype="multipart/form-data">
                @include('daran::settings.contacts.form-contact')

                <h3>@lang('daran::setting.edit-social')</h3>
                @include('daran::settings.contacts.form-social')
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

@endsection

@section('footer_scripts')
    @parent
    @routes
    <script type="text/javascript">

        $('.social-delete-btn').on('click',function(e){
            e.preventDefault();
            var nome = $(this).data('name');
            var id = $(this).data('id');
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
                            url: route('admin-api.social-settings.delete', {id: id}),
                            success: function(response){
                                if(response.success){
                                    $('#social_'+id).remove();
                                }
                            },
                            error: function(respose){
                                console.log(response);
                            }
                        })
                    }
                }
            })
        })

        $('#charge-social').on('click', function(){
            var send = {};
            send.social_name = $('#social-name').val();
            send.social_url = $('#social-url').val();

            $.ajax({
                url: "{{route('admin-api.social-settings.create')}}",
                type: 'post',
                data: send,
                success: function (){
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                  var err = eval("(" + xhr.responseText + ")");
                  var errorText = '';
                  if(err.errors['social_name']){
                      errorText += err.errors['social_name']+"\n";
                  }
                  if(err.errors['social_url']){
                      errorText += err.errors['social_url'];
                  }
                  alert(errorText);
                }
            });
        });
    </script>

@endsection
