<div id="form" class="row">
    <!-- LEFT -->
    <div class="col-8">
        <div class="mb-3">
            <label class="control-label">@lang('daran::item.code')</label>
            <input type="text" name="code" maxlength="25" placeholder="@lang('daran::item.code')" value="{{old('code',$item->code)}}" />
            <label class="control-label">@lang('daran::item.name')</label>
            <input type="text" name="name" maxlength="150" placeholder="@lang('daran::item.name')" required value="{{old('name',$item->name)}}" />
        </div>
        @foreach($langs as $lang)
            <div class="mb-3">
                <label class="control-label">@lang('daran::common.description') <span class="text-uppercase">{{$lang}}</span></label>
                <textarea name="description_{{$lang}}" placeholder="@lang('daran::common.description')">{{old('description_'.$lang,$item->translate($lang)->description)}}</textarea>
            </div>
            <div class="mb-3">
                <label class="control-label">@lang('daran::item.material') <span class="text-uppercase">{{$lang}}</span>*</label>
                <input type="text" name="material_{{$lang}}" maxlength="255" placeholder="@lang('daran::item.material')" value="{{old('material_'.$lang,$item->translate($lang)->material)}}" />
            </div>
            <div class="mb-3">
                <label class="control-label">@lang('daran::item.color') <span class="text-uppercase">{{$lang}}</span>*</label>
                <input type="text" name="color_{{$lang}}" maxlength="255" placeholder="@lang('daran::item.color')" value="{{old('color_'.$lang,$item->translate($lang)->color)}}" />
            </div>
            <div class="mb-3">
                <label class="control-label">@lang('daran::item.sizes') <span class="text-uppercase">{{$lang}}</span>*</label>
                <input type="text" name="sizes_{{$lang}}" maxlength="255" placeholder="@lang('daran::item.sizes')" value="{{old('sizes_'.$lang,$item->translate($lang)->sizes)}}" />
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
                                        <input type="text" name="meta_title_{{$lang}}" maxlength="255" placeholder="@lang('daran::common.meta-title')" value="{{old('meta_title_',$item->translate($lang)->meta_title)}}" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="control-label">@lang('daran::common.meta-description') <span class="text-uppercase">{{$lang}}</span></label>
                                        <input type="text" name="meta_description_{{$lang}}" maxlength="255" placeholder="@lang('daran::common.meta-description')" value="{{old('meta_description_',$item->translate($lang)->meta_description)}}" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="control-label">@lang('daran::common.og-title') <span class="text-uppercase">{{$lang}}</span></label>
                                        <input type="text" name="og_title_{{$lang}}" maxlength="255" placeholder="@lang('daran::common.og-title')" value="{{old('og_title_',$item->translate($lang)->og_title)}}" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="control-label">@lang('daran::common.og-description') <span class="text-uppercase">{{$lang}}</span></label>
                                        <input type="text" name="og_description_{{$lang}}" maxlength="255" placeholder="@lang('daran::common.og-description')" value="{{old('og_description',$item->translate($lang)->og_description)}}" />
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
                        <select name="family_id" id="family_id" class="select2" required="required">
                            <option value="">@lang('daran::common.select')</option>
                            @foreach ($families as $family)
                                <option value="{{$family->id}}" {{($family->id == old('family_id',$item->family_id) ? "selected='selected'":"")}}>{{$family->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="control-label">@lang('daran::item.category')</label>
                        <select name="category_id" id="category_id" class="select2">
                            <option value="">@lang('daran::common.select')</option>
                        </select>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="control-label">@lang('daran::item.category')</label>
                        <select name="subcategory_id" id="subcategory_id" class="select2">
                            <option value="">@lang('daran::common.select')</option>
                        </select>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="input-checkbox">
                            <input class="input-checkbox__hidden" name="published" id="checkboxPublished" type="checkbox" value="1" @if(old('published',$item->published)==1) checked="checked" @endif hidden/>
                            <label class="input-checkbox__icon" for="checkboxPublished">
                                <span>
                                    <svg width="12px" height="10px" viewbox="0 0 12 10">
                                        <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                    </svg>
                                </span>
                            </label>
                            <p class="input-checkbox__text fc--blue text--xs">
                                @lang('daran::common.published')
                            </p>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="input-checkbox">
                            <input class="input-checkbox__hidden" name="featured" id="checkboxFeatured" type="checkbox" value="1" @if(old('featured',$item->featured)==1) checked="checked" @endif hidden/>
                            <label class="input-checkbox__icon" for="checkboxFeatured">
                                <span>
                                    <svg width="12px" height="10px" viewbox="0 0 12 10">
                                        <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                    </svg>
                                </span>
                            </label>
                            <p class="input-checkbox__text fc--blue text--xs">
                                @lang('daran::common.featured')
                            </p>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="input-checkbox">
                            <input class="input-checkbox__hidden" name="is_new" id="checkboxNew" type="checkbox" value="1" @if(old('is_new',$item->is_new)==1) checked="checked" @endif hidden/>
                            <label class="input-checkbox__icon" for="checkboxNew">
                                <span>
                                    <svg width="12px" height="10px" viewbox="0 0 12 10">
                                        <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                    </svg>
                                </span>
                            </label>
                            <p class="input-checkbox__text fc--blue text--xs">
                                @lang('daran::common.new')
                            </p>
                        </div>
                    </div>
                    <div class="col-6 mb-3"></div>
                    <div class="col-6 mb-3">
                        <label class="control-label">@lang('daran::item.price')</label>
                        <input type="number" name="price" min="0" max="999999" step="0.01" required placeholder="@lang('daran::item.price')" value="{{old('price',$item->price)}}" />
                    </div>
                    <div class="col-6 mb-3">
                        <label class="control-label">@lang('daran::item.discount')</label>
                        <input type="number" name="discount" min="0" max="100" step="0.01" placeholder="@lang('daran::item.discount')" value="{{old('discount',$item->discount)}}" />
                    </div>
                    <div class="col-12 mb-3">
                        <label class="control-label">@lang('daran::item.datasheet')</label>
                        @if($item->datasheet)
                            <a target="_blank" href="{{config('app.url').$item->datasheet}}">Vedi</a>
                        @endif
                        <input type="file" name="datasheet" value="" />
                    </div>
                    <div class="col-4 mb-3">
                        <label class="control-label">@lang('daran::item.weight')</label>
                        <input type="number" name="weight" min="0" max="999999" step="0.01" placeholder="@lang('daran::item.weight')" value="{{old('weight',$item->weight)}}" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
