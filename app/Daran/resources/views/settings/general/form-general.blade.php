<div class="row">
    <div class="col-8">
        <div class="mb-3">
            <label class="control-label">@lang('daran::setting.site-name')*</label>
            <input type="text" name="site_name" required="required" maxlength="255" placeholder="@lang('daran::setting.site-name')" value="{{old('site_name',$branding->site_name)}}" />
        </div>
        <div class="mb-3">
            <label class="control-label">@lang('daran::setting.site-title')*</label>
            <input type="text" name="site_title" required="required" maxlength="255" placeholder="@lang('daran::setting.site-title')" value="{{old('site_title',$branding->site_title)}}" />
        </div>
        <div class="mb-3">
            <label class="control-label">@lang('daran::setting.site-description')*</label>
            <input type="text" name="site_description" required="required" maxlength="255" placeholder="@lang('daran::setting.site-description')" value="{{old('site_description',$branding->site_description)}}" />
        </div>
    </div>
    <div class="col-4">
        <div class="row">
            <div class="col-6">
                <label class="control-label">@lang('daran::setting.logo')*</label><br>&nbsp;
                {{-- <span>@lang('daran::setting.logo-dimension')</span> --}}
                <div class="input--image">
                    <input id="image1" type="file" name="logo" accept="image/*" hidden>
                    <div class="input--image__preview">
                        @if ($branding->logo)
                            <img src="{{config('app.url').'/'.$branding->logo}}">
                        @endif
                    </div>
                    <div class="input--image__actions">
                        <label for="image1" class="add btn btn-primary w-100" type="button" name="button" >@lang('daran::common.select')</label>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <label class="control-label">@lang('daran::setting.favicon')*</label><br>
                <span>@lang('daran::setting.favicon-dimension')</span>
                <div class="input--image">
                    <input id="image2" type="file" name="favicon" accept="image/*" hidden>
                    <div class="input--image__preview">
                        @if ($branding->favicon)
                            <img src="{{config('app.url').'/'.$branding->favicon}}">
                        @endif
                    </div>
                    <div class="input--image__actions">
                        <label for="image2" class="add btn btn-primary w-100" type="button" name="button" >@lang('daran::common.select')</label>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
