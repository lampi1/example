@extends('daran::layouts.master')

@section('title')
    @lang('daran::pricelist.create')
    @parent
@endsection

@section('content')
    <div id="app" class="row">
        <div class="col-12 mb-3">
            <h3>@lang('daran::pricelist.create')</h3>
        </div>
        <div class="col-12 mb-3">
            <form id="pageForma" class="form-template-1" method="post" action="{{ route('admin.pricelists.store') }}" enctype="multipart/form-data">
                @include('daran::pricelists.form')
            </form>
        </div>
    </div>
@endsection

@section('footer_scripts')
    @parent
    <script type="text/javascript">
        var locale = '{{app()->getLocale()}}';


    </script>
@endsection
