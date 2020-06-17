<div id="form" class="row">
    <!-- LEFT -->
    <div class="col-8">
        <div class="mb-3">
            <label class="control-label">@lang('daran::item.code')</label>
            <input type="text" name="code" maxlength="15" placeholder="@lang('daran::item.code')" value="{{old('code',$category->code)}}" />
        </div>
        @foreach($langs as $lang)
            <div class="mb-3">
                <label class="control-label">@lang('daran::item.name') <span class="text-uppercase">{{$lang}}</span>*</label>
                <input type="text" name="name_{{$lang}}" required="required" maxlength="150" placeholder="@lang('daran::item.name')" value="{{old('name_',$category->translate($lang)->name)}}" />
            </div>
            <div class="mb-3">
                <label class="control-label">@lang('daran::common.description') <span class="text-uppercase">{{$lang}}</span>*</label>
                <input type="text" name="description_{{$lang}}" maxlength="255" placeholder="@lang('daran::common.description')" value="{{old('description_',$category->translate($lang)->description)}}" />
            </div>
        @endforeach
        <!--seo -->
        @can('edit seo')
            <div class="row seo-accordion mb-3">
                <div class="col-12">
                    <div class="card">
                        <a class="card-header collapsed" data-toggle="collapse" href="#collapseSeo" role="button" aria-expanded="false" aria-controls="collapseAttach">
                            <label class="control-label mb-0">@lang('daran::common.seo')</label>
                        </a>
                        <div id="collapseSeo" class="collapse">
                            <div class="card-body">
                                @foreach($langs as $lang)
                                    <div class="mb-3">
                                        <label class="control-label">@lang('daran::common.meta-title') <span class="text-uppercase">{{$lang}}</span></label>
                                        <input type="text" name="meta_title_{{$lang}}" maxlength="255" placeholder="@lang('daran::common.meta-title')" value="{{old('meta_title_',$category->translate($lang)->meta_title)}}" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="control-label">@lang('daran::common.meta-description') <span class="text-uppercase">{{$lang}}</span></label>
                                        <input type="text" name="meta_description_{{$lang}}" maxlength="255" placeholder="@lang('daran::common.meta-description')" value="{{old('meta_description_',$category->translate($lang)->meta_description)}}" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="control-label">@lang('daran::common.og-title') <span class="text-uppercase">{{$lang}}</span></label>
                                        <input type="text" name="og_title_{{$lang}}" maxlength="255" placeholder="@lang('daran::common.og-title')" value="{{old('og_title_',$category->translate($lang)->og_title)}}" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="control-label">@lang('daran::common.og-description') <span class="text-uppercase">{{$lang}}</span></label>
                                        <input type="text" name="og_description_{{$lang}}" maxlength="255" placeholder="@lang('daran::common.og-description')" value="{{old('og_description',$category->translate($lang)->og_description)}}" />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
    </div>
    <div class="col-4">
        <div class="row">
            <div class="col-12 mb-3">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="control-label">@lang('daran::item.family')*</label>
                        <select name="family_id" class="select2" required="required">
                            <option value="">@lang('daran::common.select')</option>
                            @foreach ($families as $family)
                                <option value="{{$family->id}}" {{($family->id == old('family_id',$category->family_id) ? "selected='selected'":"")}}>{{$family->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="image" class="control-label">@lang('daran::common.featured-img')</label>
                    </div>
                    <div class="col-12">
                        <!-- input file img - x duplicazione, cambiare id="image1", $category->image, name  -->
                        <div class="input--image">
                            <input id="image1" type="file" name="image" accept="image/*" hidden>
                            <div class="input--image__preview">
                                @if ($category->image)
                                    <img src="{{config('app.url').'/'.$category->image}}">
                                @endif
                            </div>
                            <div class="input--image__actions">
                                <label for="image1" class="add btn btn-primary w-100" type="button" name="button" @if ($category->image) hidden="hidden" @endif>@lang('daran::common.select')</label>
                                <label data-type="standard" class="delete btn btn-danger w-100" type="button" name="button" @if (!$category->image) hidden="hidden" @endif>@lang('daran::common.delete')</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="image-sm" class="control-label">@lang('daran::common.featured-img') - @lang('daran::common.mobile')</label>
                    </div>
                    <div class="col-12">
                        <div class="input--image">
                            <input id="image2" type="file" name="image_sm" accept="image/*" hidden>
                            <div class="input--image__preview">
                                @if ($category->image_sm)
                                    <img src="{{config('app.url').'/'.$category->image_sm}}">
                                @endif
                            </div>
                            <div class="input--image__actions">
                                <label for="image2" class="add btn btn-primary w-100" type="button" name="button" @if ($category->image_sm) hidden="hidden" @endif>@lang('daran::common.select')</label>
                                <label data-type="mobile" class="delete btn btn-danger w-100" type="button" name="button" @if (!$category->image_sm) hidden="hidden" @endif>@lang('daran::common.delete')</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
