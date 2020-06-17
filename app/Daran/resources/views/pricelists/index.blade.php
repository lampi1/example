@extends('daran::layouts.master')

@section('title')
    @lang('daran::pricelist.pricelists')
@endsection

@section('header_styles')
    @parent
    @routes
@endsection

@section('content')
@include('daran::layouts._messages')
    <div class="row">
        <div class="col-12 mb-2 text-right">
            @can('create item')
                <a href="{{ route('admin.pricelists.create') }}" class="btn btn-primary">@lang('daran::common.create')</a>
            @endcan
        </div>
        <div class="col-12">
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3">
            <h3>@lang('daran::pricelist.pricelists')</h3>
        </div>
        <div class="col-12 mb-3">
            <table role="grid" class="table table-hover table-striped dataTable mb-0" data-page-length="50" data-searching="false" data-length-change="false">
                <thead>
                    <tr>
                        <th data-orderable="false">ID</th>
                        <th data-orderable="false">NOME</th>
                        <th data-orderable="false">CREATO IL</th>
                        <th data-orderable="false">GESTIONE</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    @foreach($items as $item)
                        <tr data-id="{{$item->id}}">
                            <td>{{$item->id}}</td>
                            <td>{{$item->name}}</td>
                            <td>{{$item->created_at->format('d/m/Y')}}</td>
                            <td>
                                <a class="ico" data-icon="N" title="Modifica" data-tooltip="tooltip"  href="{{ route('admin.pricelists.edit', ['pricelist' => $item]) }}"></a>
                                <a class="ico" data-icon="J" title="Elimina" href="#" data-tooltip="tooltip" data-name="{{$item->name}}" data-id="{{$item->id}}" data-toggle="modal" data-target="#confirm-delete"></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-12 mb-3">
            {{ $items->links() }}
        </div>
    </div>

    @include('daran::layouts._modal_delete')
@endsection

@section('footer_scripts')
    @parent

    <script type="text/javascript">
        var urldelete = "/admin-api/pricelists/";
    </script>
@endsection
