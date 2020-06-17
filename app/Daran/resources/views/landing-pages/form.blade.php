<div id="form" class="row" data-content="{{old('content',$landing_page->content)}}">
    <!-- LEFT -->
    <div class="col-8">
        <div class="mb-3">
            <label class="control-label">@lang('daran::common.title')*</label>
            <input type="text" name="title" required="required" maxlength="255" placeholder="@lang('daran::common.title')" value="{{old('title',$landing_page->title)}}" />
        </div>
        <div class="mb-3">
            <label class="control-label">@lang('daran::common.abstract')*</label>
            <input type="text" name="abstract" required="required" maxlength="255" placeholder="@lang('daran::common.abstract')" value="{{old('abstract',$landing_page->abstract)}}" />
        </div>
        @if($landing_page->slug)
            <div class="mb-3">
                <label class="control-label">@lang('daran::common.slug')*</label>
                <input type="text" id="slug" name="slug" required="required" placeholder="@lang('daran::common.slug')" value="{{old('slug',$landing_page->slug)}}" />
            </div>
        @endif
        <div class="mb-3">
            <label class="control-label">@lang('daran::common.content')*</label>
            <daran-tiptap></daran-tiptap>
        </div>
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
                                <div class="row mb-3">
                                    <div class="col-12 col-md-6">
                                        <label class="control-label">@lang('daran::common.meta-title')</label>
                                        <input type="text" maxlength="255" name="meta_title" value="{{old('meta_title',$landing_page->meta_title)}}" />
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="control-label">@lang('daran::common.og-title')</label>
                                        <input type="text" maxlength="255" name="og_title" value="{{old('og_title',$landing_page->og_title)}}" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label class="control-label">@lang('daran::common.meta-description')</label>
                                        <input type="text" maxlength="255" name="meta_description" value="{{old('meta_description',$landing_page->meta_description)}}" />
                                    </div>
                                    <div class="col-12">
                                        <label class="control-label">@lang('daran::common.og-description')</label>
                                        <input type="text" maxlength="255" name="og_description" value="{{old('og_description',$landing_page->og_description)}}" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan

    </div>
    <div class="col-4">
        <input type="hidden" name="state" id="state-field" value={{$landing_page->state}} />
        <div class="row">
            <div class="col-12 mb-3">
                <label class="control-label">@lang('daran::common.language')*</label>
                <select name="locale" class="select2" required="required" >
                    @foreach ($locales as $k=>$v)
                        <option value="{{$k}}" {{($k == old('locale',$landing_page->locale) ? "selected='selected'":"")}}>{{$v}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 mb-3">
                <label class="control-label">@lang('daran::common.template')*</label>
                <select name="landing_page_template_id" required="required" class="select2">
                    @foreach ($pageTemplates as $landing_pageTemplate)
                        <option value="{{$landing_pageTemplate->id}}" {{($landing_pageTemplate->id == old('landing_page_template_id',$landing_page->landing_page_template_id) ? "selected='selected'":"")}}>{{$landing_pageTemplate->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 mb-3">
                <label class="control-label">@lang('daran::landing_page.associate_slider')</label>
                <select name="slider_id" class="select2">
                    <option value="">@lang('daran::landing_page.no_slider')</option>
                    @foreach ($sliders as $slider)
                        <option value="{{$slider->id}}" {{($slider->id == old('slider_id',$landing_page->slider_id) ? "selected='selected'":"")}}>{{$slider->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 mb-3">
                <label class="control-label">@lang('daran::landing_page.associate_form')</label>
                <select name="form_id" class="select2">
                    <option value="">@lang('daran::landing_page.no_form')</option>
                    @foreach ($forms as $form)
                        <option value="{{$form->id}}" {{($form->id == old('form_id',$landing_page->form_id) ? "selected='selected'":"")}}>{{$form->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 mb-3">
                <div class="input-checkbox">
                    <input class="input-checkbox__hidden" name="seo" id="checkboxSearchEngine" type="checkbox" value="1" @if(old('seo',$landing_page->seo)==1) checked="checked"  @endif hidden/>
                    <label class="input-checkbox__icon" for="checkboxSearchEngine">
                        <span>
                            <svg width="12px" height="10px" viewbox="0 0 12 10">
                                <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                            </svg>
                        </span>
                    </label>
                    <p class="input-checkbox__text fc--blue text--xs">
                        @lang('daran::common.index-seo')
                    </p>
                </div>
            </div>
            <div class="col-12 mb-3">
                <div class="input-checkbox">
                    <input class="input-checkbox__hidden" name="search" id="checkboxInSearch" type="checkbox" value="1" @if(old('search',$landing_page->search)==1) checked="checked" @endif hidden/>
                    <label class="input-checkbox__icon" for="checkboxInSearch">
                        <span>
                            <svg width="12px" height="10px" viewbox="0 0 12 10">
                                <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                            </svg>
                        </span>
                    </label>
                    <p class="input-checkbox__text fc--blue text--xs">
                        @lang('daran::common.search-show')
                    </p>
                </div>
            </div>
            <div class="col-12 mb-3">
                <div class="row">
                    <div class="col-12">
                        <label for="image" class="control-label">@lang('daran::common.featured-img')</label>
                    </div>
                    <div class="col-12">

                        <!-- input file img - x duplicazione, cambiare id="image1", $landing_page->image, name  -->
                        <div class="input--image">
                            <input id="image1" type="file" name="image" accept="image/*" hidden>
                            <div class="input--image__preview">
                                @if ($landing_page->image)
                                    <img src="{{config('app.url').'/'.$landing_page->image}}">
                                @endif
                            </div>
                            <div class="input--image__actions">
                                <label for="image1" class="add btn btn-primary w-100" type="button" name="button" @if ($landing_page->image) hidden="hidden" @endif>@lang('daran::common.select')</label>
                                <label data-type="standard" class="delete btn btn-danger w-100" type="button" name="button" @if (!$landing_page->image) hidden="hidden" @endif>@lang('daran::common.delete')</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label for="image-sm" class="control-label">@lang('daran::common.featured-img') - @lang('daran::common.mobile')</label>
                    </div>
                    <div class="col-12">
                        <!-- input file img - x duplicazione, cambiare id="image1", $landing_page->image, name  -->
                        <div class="input--image">
                            <input id="image2" type="file" name="image_sm" accept="image/*" hidden>
                            <div class="input--image__preview">
                                @if ($landing_page->image_sm)
                                    <img src="{{config('app.url').'/'.$landing_page->image_sm}}">
                                @endif
                            </div>
                            <div class="input--image__actions">
                                <label for="image2" class="add btn btn-primary w-100" type="button" name="button" @if ($landing_page->image_sm) hidden="hidden" @endif>@lang('daran::common.select')</label>
                                <label data-type="mobile" class="delete btn btn-danger w-100" type="button" name="button" @if (!$landing_page->image_sm) hidden="hidden" @endif>@lang('daran::common.delete')</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if($landing_page->translations->count() > 0)
                <div class="col-12 mb-3">
                    <label class="control-label">@lang('daran::common.translations')</label>
                    <table class="table lang" id="table">
                        <tbody>
                            @foreach($landing_page->translations as $landing_page_trans)
                                <tr>
                                    <td>@lang('daran::common.'.$landing_page_trans->locale)</td>
                                    @can('edit page')
                                        <td class="text-right">
                                            <a class="ico" href="{{ route('admin.pages.edit', ['id' => $landing_page_trans->id]) }}" data-icon="N" title="@lang('daran::common.edit')" data-tooltip="tooltip"></a>
                                            <a class="ico" href="{{ route('admin.pages.clone', ['id' => $landing_page_trans->id,'locale' => $landing_page_trans->locale, 'locale_group' => $landing_page_trans->locale_group]) }}" data-icon="x" title="@lang('daran::common.duplicate')" data-tooltip="tooltip"></a>
                                            @can('delete page')
                                                <a class="ico" href="#" data-icon="J" title="@lang('daran::common.delete')" data-tooltip="tooltip" data-name="{{$landing_page_trans->title}}" data-id="{{$landing_page_trans->id}}" data-toggle="modal" data-target="#confirm-delete"></a>
                                            @endcan
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            @if( $landing_page->id && $landing_page->translations->count() < count(config('app.available_translations'))-1)
                <div class="col-12 mb-3">
                    <label class="control-label">@lang('daran::common.translations')</label>
                    <table class="table" id="table">
                        <tbody>
                            @foreach(config('app.available_translations') as $trans)
                                @if ($landing_page->locale != $trans && !$landing_page->translations->contains('locale',$trans))
                                    <tr>
                                        <td>
                                            <label class="fc--blue">@lang('daran::common.'.$trans)</label>
                                        </td>
                                        @can('create page')
                                            <td class="text-right">
                                                <a class="ico" href="{{ route('admin.pages.create', ['locale' => $trans, 'locale_group' => $landing_page->locale_group]) }}" data-icon="F" title="@lang('daran::common.create')" data-tooltip="tooltip"></a>
                                                <a class="ico" href="{{ route('admin.pages.clone', ['id' => $landing_page->id,'locale' => $trans, 'locale_group' => $landing_page->locale_group]) }}" data-icon="&#120;" title="@lang('daran::common.duplicate')" data-tooltip="tooltip"></a>
                                            </td>
                                        @endcan
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
