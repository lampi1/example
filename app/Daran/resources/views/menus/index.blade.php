@extends('daran::layouts.master')

@section('title')
    @lang('daran::menu.menus')
@endsection

@section('header_styles')
    @parent
    @routes
@endsection

@section('content')
@include('daran::layouts._messages')
    <div class="row">
        <div class="col-12 mb-2 text-right">
            <!-- Actions -->

        </div>
        <div class="col-12">
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3" id="ref" api-url="">
            <h3>@lang('daran::menu.menus')</h3>
        </div>
        <div class="col-12 mb-3" id="app">
            <div class="row no-gutters mt-4">
                <div class="col-12 mt-4">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <td>ID</td>
                                <td>POSIZIONE</td>
                                <td>LINGUA</td>
                                <td>GESTIONE</td>
                            </tr>
                        </thead>
                        <tbody >
                            @foreach ($menus as $menu)
                                <tr>
                                    <td>{{$menu->id}}</td>
                                    <td>{{$menu->position}}</td>
                                    <td>{{strtoupper($menu->locale)}}</td>
                                    <td class="custom-actions">
                                        <a href="{{route('admin.menus.edit',['id'=>$menu->id])}}" data-icon="N" title="Modifica" data-tooltip="tooltip" class="ico"></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
    @parent

@endsection
