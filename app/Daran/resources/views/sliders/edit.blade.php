@extends('daran::layouts.master')

@section('title')
    @lang('daran::slider.edit-slider')
    @parent
@endsection

@section('content')
    <div id="app" class="row">
        <div class="col-12 mb-3">
            <h3>@lang('daran::slider.edit-slider')</h3>
        </div>
        <div class="col-12 mb-3">
            <form class="form-template-1">
                <div id="form" class="row">
                    <div class="col-11">
                        <div class="mb-3">
                            <label class="control-label">@lang('daran::common.name')*</label>
                            <input type="text" name="name" required="required" id="name" readonly maxlength="255" placeholder="@lang('daran::common.name')" value="{{old('name',$slider->name)}}" />
                        </div>
                    </div>
                    <div class="col-1">
                        <div class="mb-3">
                            <button type="button" class="ico mt-4" id="edit-slider-btn" data-icon="N" title="Modifica" data-tooltip="tooltip"></button>
                            <button type="button" class="ico mt-4 d-none" id="save-slider-btn" data-icon="S" title="Save" data-tooltip="tooltip"></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-12 mb-3" id="app">
            <daran-vuetable ref="vuetable"
                fields-array="Slide"
                api-url="{{route('admin-api.sliders-slides.index',['slider_id'=>$slider->id])}}"
                :paginate="25"
                :route-prefix="'admin.slides'"
                :route-api-prefix="'admin-api.sliders-slides'"
                :is-sortable="true"
                :sort-order="[{field: 'priority', direction: 'asc'}]"
                :parent-id="{{$slider->id}}"
                >
            </daran-vuetable>
        </div>
        <div class="col-12 mb-3">
            <a href="{{route('admin.slides.create',['id'=>$slider->id, 'type' => 'image'])}}" class="btn btn-primary">@lang('daran::slider.add-slide')</a>
            <a href="{{route('admin.slides.create',['id'=>$slider->id, 'type' => 'video'])}}" class="btn btn-primary">@lang('daran::slider.add-video-slide')</a>
            <a href="{{ url()->previous() }}" id="bt-annulla" class="btn btn-info">@lang('daran::common.discard')</a>
        </div>
    </div>

@endsection

@section('footer_scripts')
    @routes

    @parent

    <script type="text/javascript">
        urlreorder = "{{ config('app.url') }}/admin/slides/reorder";
        urldelete = "{{ config('app.url') }}/admin/slides/";

        $('#edit-slider-btn').on('click',function(e){
            e.preventDefault();
            $('#name').prop('readonly',false);
            $(this).addClass('d-none');
            $('#save-slider-btn').removeClass('d-none');
        })

        $('#save-slider-btn').on('click',function(e){
            e.preventDefault();
            $('#name').prop('readonly',true);
            $(this).addClass('d-none');
            $('#edit-slider-btn').removeClass('d-none');

            $.ajax({
                url: "{{route('admin-api.sliders.update',['id'=>$slider->id])}}",
                type: 'put',
                data: {name: $('#name').val()},
                success: function (){

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
        })
    </script>
@endsection
