<div class="row">
    <div class="col-8">
        <div class="mb-3">
            <label class="control-label">@lang('daran::setting.gtag-manager')</label>
            <input type="text" name="gtag_manager" placeholder="@lang('daran::setting.gtag-manager')" value="{{old('gtag_manager',$seo->gtag_manager)}}" />
        </div>
        <div class="mb-3">
            <label class="control-label">@lang('daran::setting.g-analytics')</label>
            <input type="text" name="g_analytics" placeholder="@lang('daran::setting.g-analytics')" value="{{old('g_analytics',$seo->g_analytics)}}" />
        </div>
        <div class="mb-3">
            <label class="control-label">@lang('daran::setting.faceboox-pixel')</label>
            <input type="text" name="faceboox_pixel" placeholder="@lang('daran::setting.faceboox-pixel')" value="{{old('faceboox_pixel',$seo->faceboox_pixel)}}" />
        </div>
        <div class="row seo-accordion mb-3">
            <div class="col-12">
                <a class="collapseArrow" data-toggle="collapse" href="#collapseSeo" role="button" aria-expanded="false" aria-controls="collapseSeo">
                    <span class="customArrow"></span><label class="control-label">@lang('daran::setting.advanced')</label>
                </a>
                <div id="collapseSeo" class="collapse">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="control-label">@lang('daran::setting.meta-title')</label>
                            <input type="text" name="title" value="{{old('title',$seo->title)}}" />
                        </div>
                        <div class="col-6 mb-3">
                            <label class="control-label">@lang('daran::setting.og-title')</label>
                            <input type="text" name="og_title" value="{{old('og_title',$seo->og_title)}}" />
                        </div>
                        <div class="col-6">
                            <label class="control-label">@lang('daran::setting.meta-description')</label>
                            <input type="text" name="description" value="{{old('meta_description',$seo->description)}}" />
                        </div>
                        <div class="col-6">
                            <label class="control-label">@lang('daran::setting.og-description')</label>
                            <input type="text" name="og_description" value="{{old('og_description',$seo->og_description)}}" />
                        </div>

                        <div class="col-6 mb-3">
                            <label class="control-label">@lang('daran::setting.meta-author')</label>
                            <input type="text" name="author" placeholder="@lang('daran::setting.meta-author')" value="{{old('author',$seo->author)}}" />
                        </div>

                        <div class="col-6 mb-3">
                            <label class="control-label">@lang('daran::setting.og-author')</label>
                            <input type="text" name="og_author" placeholder="@lang('daran::setting.og-author')" value="{{old('og_author',$seo->og_author)}}" />
                        </div>
                        <div class="col-6">
                            <label class="control-label">@lang('daran::setting.og-image')</label>
                            <input type="text" name="og_image" placeholder="@lang('daran::setting.og-image')" value="{{old('og_image',$seo->og_image)}}" />
                        </div>
                        <div class="col-6">
                            <label class="control-label">@lang('daran::setting.og-type')</label>
                            <input type="text" name="og_type" placeholder="@lang('daran::setting.og-type')" value="{{old('og_type',$seo->og_type)}}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
