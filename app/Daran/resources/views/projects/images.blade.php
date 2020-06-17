@extends('daran::layouts.master')

@section('title')
    Gestione Immagini Componente
@endsection

@section('header_styles')
    @parent
    @routes
@endsection

@section('content')
@include('daran::layouts._messages')
    <div class="row">
        <div class="col-12 mb-2 text-right">
            <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">Progetti</a>
        </div>
        <div class="col-12">
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3" id="ref" api-url="">
            <h3>Gestione Immagini Componente</h3>
        </div>
        <div class="col-12 mb-3" id="app">
            <daran-item-images ref="item-images"
                :id="{{$id}}"
                :route-prefix="'admin.items'"
                :route-api-prefix="'admin-api.components'"
                api-url="{{route('admin-api.components.add-image',['id'=>$id])}}"
                :is-sortable="true"
                >
            </daran-item-images>
        </div>
    </div>
@endsection

@section('footer_scripts')
    @parent

@endsection
