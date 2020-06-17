@extends('daran::layouts.master')

@section('title')
    @lang('daran::slider.create-slider')
    @parent
@endsection

@section('content')
    <div id="app" class="row">
        <div class="col-12 mb-3">
            <h3>@lang('daran::slider.create-slider')</h3>
        </div>
        <div class="col-12 mb-3">
            <form action="{{ route('admin.sliders.store') }}" id="sliderForm" method="post" class="form-template-1" enctype="multipart/form-data">
                <div id="form" class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="control-label">@lang('daran::common.name')*</label>
                            <input type="text" name="name"required="required" maxlength="255" placeholder="@lang('daran::common.name')" value="{{old('name',$slider->name)}}" />
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <button type="submit" id="save-publish" class="btn btn-primary">@lang('daran::common.save')</button>
                    <a href="{{ url()->previous() }}" id="bt-annulla" class="btn btn-info">@lang('daran::common.discard')</a>
                </div>
            </form>
        </div>
    </div>
@endsection
