@extends('daran::layouts.master')

@section('title')
    Modifica Progetto
    @parent
@endsection

@section('content')
    <div id="app" class="row">
        <div class="col-12 mb-3">
            <h3>Modifica Progetto</h3>
        </div>
        <div class="col-12 mb-3">
            <form id="pageForm" class="form-template-1" method="post" action="{{ route('admin.projects.update',['project'=>$project->id]) }}" enctype="multipart/form-data">
                @include('daran::projects.form')
                <div class="row">
                    <div class="col-12">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="_method" value="put" />
                        @can('publish page')
                            <button type="button" class="btn btn-primary" id="save-publish">
                                @if($project->state == 'published')
                                    @lang('daran::common.update')
                                @else
                                    @lang('daran::common.publish')
                                @endif
                            </button>
                        @endcan
                        <button type="button" class="btn btn-primary" id="save-draft">@lang('daran::common.draft')</button>
                        <a target="_blank" href="{{LaravelLocalization::getLocalizedURL($project->locale, route('projects.view',['permalink'=>$project->slug]))}}" class="btn btn-secondary" >@lang('daran::common.preview')</a>
                        <a href="{{ url()->previous() }}" id="bt-annulla" class="btn btn-info">@lang('daran::common.discard')</a>

                    </div>
                </div>
            </form>
        </div>
        <div class="col-12 mb-3" id="app">
            <daran-vuetable ref="vuetable"
                fields-array="Component"
                api-url="{{route('admin-api.projects-components.index',['project_id'=>$project->id])}}"
                :paginate="25"
                :route-prefix="'admin.components'"
                :route-api-prefix="'admin-api.projects-components'"
                :is-sortable="true"
                :sort-order="[{field: 'priority', direction: 'asc'}]"
                :parent-id="{{$project->id}}"
                >
            </daran-vuetable>
        </div>
        <div class="col-12 mb-3">
            <a href="{{route('admin.projects.add-component',['id'=>$project->id])}}" class="btn btn-primary">Aggiungi Componente</a>
            <a href="{{ url()->previous() }}" id="bt-annulla" class="btn btn-info">@lang('daran::common.discard')</a>
        </div>
    </div>
    @include('daran::layouts._modal_delete')
@endsection

@section('footer_scripts')
    @routes
    @parent

    <script type="text/javascript">
        var url_img_cover_delete = "{{ config('app.url') }}"+ "/admin-api/project-image/";
        var url_video_delete = "{{ config('app.url') }}"+ "/admin-api/project-video/";
        urldelete = "{{ config('app.url') }}/admin-api/projects/";
        var locale = '{{app()->getLocale()}}';

        $('#save-publish').on('click',function(){
            publishOrDraft('pageForm', true, 'published', error_attach, true);
        });
        $('#save-draft').on('click',function(){
            publishOrDraft('pageForm', true, 'draft', error_attach, true);
        });

        //img 1
        $('.input--image').on('click', '.delete',function(e){
            var type = $(this).data('type');
            var page_id = {{$project->id}};
            $.ajax({
                url:url_img_cover_delete + page_id + '?type=' + type,
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

        $('.delete-video').on('click', function(e){
            var type = $(this).data('type');
            var page_id = {{$project->id}};
            var element = $(this);
            $.ajax({
                url:url_video_delete + page_id + '?type=' + type,
                type: 'post',
                data: {_method: 'put' },
                success: function (data){
                    if (data.success){
                        element.hide();
                        element.prev().hide();
                        element.next().show();
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
