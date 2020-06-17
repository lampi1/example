@extends('daran::layouts.master')

@section('title')
    @lang('daran::item.item')
@endsection

@section('header_styles')
    @parent

@endsection

@section('content')
@include('daran::layouts._messages')
    <div class="row">
        <div class="col-12 mb-2 text-right">
            @can('edit item')
                <a href="{{ route('admin.items.edit',['item'=>$item->id]) }}" class="btn btn-primary">@lang('daran::common.edit')</a>
            @endcan
        </div>
        <div class="col-12">
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3" id="ref" api-url="">
            <h3>@lang('daran::item.item'): {{$item->name}}</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-6 mb-3">
            @if ($item->images->count() > 0)
                <img src="{{config('app.url').$item->images->first()->filename}}" alt="" width="30%">
            @endif
        </div>
        <div class="col-6 mb-3">
            <div class="row align-items-stretch mb-3">
                <div class="col-12 col-md-4">
                    <div class="content-wrapper bordered @if(!$item->published) content-wrapper-disabled @endif">
                        <img src="{{ asset('images/pubblicato.png')}}" width="100%" alt="@lang('daran::item.published'): @if($item->published) Si @else No @endif" title="@lang('daran::item.published'): @if($item->published) Si @else No @endif" />
                        <div class="text-wrapper">
                            <h5>@lang('daran::item.published'): @if($item->published) Si @else No @endif</h5>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="content-wrapper bordered @if(!$item->is_new) content-wrapper-disabled @endif">
                        <img src="{{ asset('images/novita.png')}}" width="100%" alt="@lang('daran::item.is_new'): @if($item->is_new) Si @else No @endif" title="@lang('daran::item.is_new'): @if($item->is_new) Si @else No @endif" />
                        <div class="text-wrapper text-wrapper-top">
                            <h5>@lang('daran::item.is_new'): @if($item->is_new) Si @else No @endif</h5>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="content-wrapper bordered @if(!$item->featured) content-wrapper-disabled @endif">
                        <img src="{{ asset('images/primo-piano.png')}}" width="100%" alt="@lang('daran::item.featured'): @if($item->featured) Si @else No @endif" title="@lang('daran::item.featured'): @if($item->featured) Si @else No @endif" />
                        <div class="text-wrapper">
                            <h5>@lang('daran::item.featured'): @if($item->featured) Si @else No @endif</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row align-items-stretch mb-3">
                <div class="col-12 col-md-4">
                    <div class="content-wrapper bordered px-3 py-4">
                        <h5>@lang('daran::item.current_price')</h5>
                        <h4 class="boxer">&euro; {{number_format($item->current_price,2,',','.')}}</h1>
                        @if($item->discount>0)
                            <h5 class="mt-3">@lang('daran::item.discount_p')</h5>
                            <h4 class="boxer">{{number_format($item->discount,2,',','.')}} %</h4>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('footer_scripts')
    @parent


    <script type="text/javascript">
    </script>
@endsection
