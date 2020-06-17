<div class="row">
    <div class="col-8">
        <div class="mb-3">
            <label class="control-label">@lang('daran::common.title')*</label>
            <input type="text" name="title" required="required" maxlength="255" placeholder="@lang('daran::common.title')" value="{{old('title',$slide->title)}}" />
        </div>
        <div class="mb-3">
            <label class="control-label">@lang('daran::common.subtitle')</label>
            <input type="text" name="subtitle" maxlength="255" placeholder="@lang('daran::common.subtitle')" value="{{old('subtitle',$slide->subtitle)}}" />
        </div>
        <div class="mb-3">
            <label class="control-label">@lang('daran::slider.caption')</label>
            <input type="text" name="caption" maxlength="255" placeholder="@lang('daran::slider.caption')" value="{{old('caption',$slide->caption)}}" />
        </div>
        <div class="mb-3">
            <label class="control-label">@if($slide->type == 'image') @lang('daran::slider.link') @elseif($slide->type == 'video') @lang('daran::slider.sourceurl') @endif</label>
            <input type="text" name="link" maxlength="255" placeholder="@lang('daran::slider.link')" value="{{old('link',$slide->link)}}" />
        </div>
    </div>
    <div class="col-4">
        <div class="row">
            @if($slide->type == 'image')
                <div class="col-12 mb-3">
                    <div class="row">
                        <div class="col-12">
                            <label for="image" class="control-label">@lang('daran::common.image')</label>
                        </div>
                        <div class="col-12">
                            <div class="input--image">
                                <input id="image1" type="file" name="image" accept="image/*" hidden>
                                <div class="input--image__preview">
                                    @if ($slide->image)
                                        <img src="{{config('app.url').'/'.$slide->image}}">
                                    @endif
                                </div>
                                <div class="input--image__actions">
                                    <label for="image1" class="add btn btn-primary w-100" type="button" name="button" @if ($slide->image) hidden="hidden" @endif>@lang('daran::common.select')</label>
                                    <label data-type="standard" class="delete btn btn-danger w-100" type="button" name="button" @if (!$slide->image) hidden="hidden" @endif>@lang('daran::common.delete')</label>
                                </div>
                            </div>

                        </div>
                        <div class="col-12">
                            <label for="image-sm" class="control-label">@lang('daran::common.image') - @lang('daran::common.mobile')</label>
                        </div>
                        <div class="col-12">
                            <div class="input--image">
                                <input id="image2" type="file" name="image_sm" accept="image/*" hidden>
                                <div class="input--image__preview">
                                    @if ($slide->image_sm)
                                        <img src="{{config('app.url').'/'.$slide->image_sm}}">
                                    @endif
                                </div>
                                <div class="input--image__actions">
                                    <label for="image2" class="add btn btn-primary w-100" type="button" name="button" @if ($slide->image_sm) hidden="hidden" @endif>@lang('daran::common.select')</label>
                                    <label data-type="mobile" class="delete btn btn-danger w-100" type="button" name="button" @if (!$slide->image_sm) hidden="hidden" @endif>@lang('daran::common.delete')</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
