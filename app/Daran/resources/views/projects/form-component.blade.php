<div class="row">
    <div class="col-8">
        <div class="mb-3">
            <label class="control-label">@lang('daran::common.name')*</label>
            <input type="text" name="name" required="required" maxlength="255" placeholder="@lang('daran::common.name')" value="{{old('name',$component->name)}}" />
        </div>
        <div class="mb-3">
            <label class="control-label">@lang('daran::common.title') Primo Piano</label>
            <input type="text" name="title"  maxlength="255" placeholder="@lang('daran::common.title') Primo Piano" value="{{old('title',$component->title)}}" />
        </div>
        <div class="mb-3">
            <label class="control-label">Testo Primo Piano</label>
            <textarea name="text" rows="5">{{old('text',$component->text)}}</textarea>
        </div>
        <div class="mb-3">
            <label class="control-label">Testo Semplice</label>
            <textarea name="content" rows="5">{{old('content',$component->content)}}</textarea>
        </div>
    </div>
    <div class="col-4">
        <div class="row">
            <div class="col-12 mb-3">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="control-label">Formato Gallery</label>
                        <select name="gallery_format" class="select2">
                            <option value="">@lang('daran::common.select')</option>
                            <option value="50" {{50 == old('gallery_format',$component->gallery_format) ? "selected='selected'":""}}>50%</option>
                            <option value="100" {{100 == old('gallery_format',$component->gallery_format) ? "selected='selected'":""}}>100%</option>
                            <option value="0" {{0 == old('gallery_format',$component->gallery_format) ? "selected='selected'":""}}>Slideshow</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="image" class="control-label">@lang('daran::common.image')</label>
                    </div>
                    <div class="col-12">
                        <div class="input--image">
                            <input id="image1" type="file" name="image" accept="image/*" hidden>
                            <div class="input--image__preview">
                                @if ($component->image)
                                    <img src="{{config('app.url').'/'.$component->image}}">
                                @endif
                            </div>
                            <div class="input--image__actions">
                                <label for="image1" class="add btn btn-primary w-100" type="button" name="button" @if ($component->image) hidden="hidden" @endif>@lang('daran::common.select')</label>
                                <label data-type="standard" class="delete btn btn-danger w-100" type="button" name="button" @if (!$component->image) hidden="hidden" @endif>@lang('daran::common.delete')</label>
                            </div>
                        </div>

                    </div>
                    {{-- <div class="col-12">
                        <label for="image-sm" class="control-label">@lang('daran::common.image') - @lang('daran::common.mobile')</label>
                    </div> --}}
                    {{-- <div class="col-12">
                        <div class="input--image">
                            <input id="image2" type="file" name="image_sm" accept="image/*" hidden>
                            <div class="input--image__preview">
                                @if ($component->image_sm)
                                    <img src="{{config('app.url').'/'.$component->image_sm}}">
                                @endif
                            </div>
                            <div class="input--image__actions">
                                <label for="image2" class="add btn btn-primary w-100" type="button" name="button" @if ($component->image_sm) hidden="hidden" @endif>@lang('daran::common.select')</label>
                                <label data-type="mobile" class="delete btn btn-danger w-100" type="button" name="button" @if (!$component->image_sm) hidden="hidden" @endif>@lang('daran::common.delete')</label>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>
