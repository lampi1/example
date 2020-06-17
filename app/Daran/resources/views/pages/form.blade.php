<div id="form" class="row" data-content="{{old('content',$page->content)}}">
    <!-- LEFT -->
    <div class="col-8">
        <div class="mb-3">
            <label class="control-label">@lang('daran::common.title')*</label>
            <input type="text" name="title" required="required" maxlength="255" placeholder="@lang('daran::common.title')" value="{{old('title',$page->title)}}" />
        </div>
        <div class="mb-3">
            <label class="control-label">@lang('daran::common.abstract')*</label>
            <input type="text" name="abstract" required="required" maxlength="255" placeholder="@lang('daran::common.abstract')" value="{{old('abstract',$page->abstract)}}" />
        </div>
        @if($page->slug)
            <div class="mb-3">
                <label class="control-label">@lang('daran::common.slug')*</label>
                <input type="text" id="slug" name="slug" required="required" placeholder="@lang('daran::common.slug')" value="{{old('slug',$page->slug)}}" />
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
                                        <input type="text" maxlength="255" name="meta_title" value="{{old('meta_title',$page->meta_title)}}" />
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="control-label">@lang('daran::common.og-title')</label>
                                        <input type="text" maxlength="255" name="og_title" value="{{old('og_title',$page->og_title)}}" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label class="control-label">@lang('daran::common.meta-description')</label>
                                        <input type="text" maxlength="255" name="meta_description" value="{{old('meta_description',$page->meta_description)}}" />
                                    </div>
                                    <div class="col-12">
                                        <label class="control-label">@lang('daran::common.og-description')</label>
                                        <input type="text" maxlength="255" name="og_description" value="{{old('og_description',$page->og_description)}}" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
        <!-- attachments -->
        <div class="row attachments-accordion mb-4">
            <div class="col-12">
                <div class="card">
                    <a class="card-header collapsed" data-toggle="collapse" href="#collapseAttach" role="button" aria-expanded="false" aria-controls="collapseAttach">
                        <label class="control-label mb-0">@lang('daran::common.attachments')</label>
                    </a>
                    <div id="collapseAttach" class="collapse">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 text-right">
                                    <button class="ico" id="add-attachment" data-icon="F" title="@lang('daran::common.create')" data-tooltip="tooltip"></a>
                                </div>
                            </div>
                            <table class="table table-striped" id="table-attachement">
                                <thead>
                                    <tr>
                                        <th>@lang('daran::common.table-title')</th>
                                        <th>@lang('daran::common.table-actions')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($page->page_attachments->count() > 0)
                                        @foreach($page->page_attachments as $pageatt)
                                            <tr>
                                                <td style="width:60%;">
                                                    <a class="imgAttachmentUrl" href="{{$pageatt->file}}" target="_blank">{{$pageatt->title}}</a>
                                                </td>
                                                <td>
                                                    <button class="attachment-remove-old ico" data-id="{{$pageatt->id}}" data-icon="J" title="@lang('daran::common.remove-attachment')" data-tooltip="tooltip"></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    <tr>
                                        <td style="width:60%;" class="title-attachment">
                                            <input type="text" name="attachment_title[]" placeholder="@lang('daran::common.title')*" value="" />
                                        </td>
                                        <td>
                                            <input type="file" name="attachment_file[]" class="" value="" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <input type="hidden" name="state" id="state-field" value={{$page->state}} />
        <div class="row">
            <div class="col-12 mb-3">
                <label class="control-label">@lang('daran::common.category')*</label>
                <select name="page_category_id" class="select2-addible" required="required">
                    <option value="">@lang('daran::common.select')</option>
                    @foreach ($pageCategories as $pageCategory)
                        <option value="{{$pageCategory->id}}" {{($pageCategory->id == old('page_category_id',$page->page_category_id) ? "selected='selected'":"")}}>{{$pageCategory->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 mb-3">
                <label class="control-label">@lang('daran::common.language')*</label>
                <select name="locale" class="select2" required="required" >
                    @foreach ($locales as $k=>$v)
                        <option value="{{$k}}" {{($k == old('locale',$page->locale) ? "selected='selected'":"")}}>{{$v}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 mb-3">
                <label class="control-label">@lang('daran::common.template')*</label>
                <select name="page_template_id" required="required" class="select2">
                    @foreach ($pageTemplates as $pageTemplate)
                        <option value="{{$pageTemplate->id}}" {{($pageTemplate->id == old('page_template_id',$page->page_template_id) ? "selected='selected'":"")}}>{{$pageTemplate->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 mb-3">
                <label class="control-label">@lang('daran::page.associate_slider')</label>
                <select name="slider_id" class="select2">
                    <option value="">@lang('daran::page.no_slider')</option>
                    @foreach ($sliders as $slider)
                        <option value="{{$slider->id}}" {{($slider->id == old('slider_id',$page->slider_id) ? "selected='selected'":"")}}>{{$slider->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 mb-3">
                <label class="control-label">@lang('daran::page.father_page')</label>
                <select name="father_page_id" class="select2">
                    <option value="">@lang('daran::page.no_father')</option>
                    @foreach ($fatherPages as $fatherPage)
                        <option value="{{$fatherPage->id}}" {{($fatherPage->id == old('father_page_id',$page->father_page_id) ? "selected='selected'":"")}}>{{$fatherPage->title.' ('.$fatherPage->locale.')'}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 mb-3">
                <label class="control-label">@lang('daran::common.tags')</label>
                <daran-vuetags :own-tags="{{$vue_tags}}" :all-tags="{{$vue_all_tags}}"></daran-vuetags>
            </div>
            <div class="col-12 mb-3">
                <div class="input-checkbox">
                    <input class="input-checkbox__hidden" name="seo" id="checkboxSearchEngine" type="checkbox" value="1" @if(old('seo',$page->seo)==1) checked="checked"  @endif hidden/>
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
                    <input class="input-checkbox__hidden" name="search" id="checkboxInSearch" type="checkbox" value="1" @if(old('search',$page->search)==1) checked="checked" @endif hidden/>
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
                        <label for="video_mp4" class="control-label">Video Formato MP4</label>
                    </div>
                    <div class="col-12">
                        @if($page->video_mp4)
                            <a target="_blank" href="{{config('app.url').$page->video_mp4}}">Vedi</a>
                        @endif
                        <input type="file" name="video_mp4" value="" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label for="video_ovg" class="control-label">Video Formato OVG</label>
                    </div>
                    <div class="col-12">
                        @if($page->video_ovg)
                            <a target="_blank" href="{{config('app.url').$page->video_ovg}}">Vedi</a>
                        @endif
                        <input type="file" name="video_ovg" value="" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label for="video_webm" class="control-label">Video Formato WebM</label>
                    </div>
                    <div class="col-12">
                        @if($page->video_webm)
                            <a target="_blank" href="{{config('app.url').$page->video_webm}}">Vedi</a>
                        @endif
                        <input type="file" name="video_webm" value="" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label for="image" class="control-label">@lang('daran::common.featured-img')</label>
                    </div>
                    <div class="col-12">

                        <!-- input file img - x duplicazione, cambiare id="image1", $page->image, name  -->
                        <div class="input--image">
                            <input id="image1" type="file" name="image" accept="image/*" hidden>
                            <div class="input--image__preview">
                                @if ($page->image)
                                    <img src="{{config('app.url').'/'.$page->image}}">
                                @endif
                            </div>
                            <div class="input--image__actions">
                                <label for="image1" class="add btn btn-primary w-100" type="button" name="button" @if ($page->image) hidden="hidden" @endif>@lang('daran::common.select')</label>
                                <label data-type="standard" class="delete btn btn-danger w-100" type="button" name="button" @if (!$page->image) hidden="hidden" @endif>@lang('daran::common.delete')</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label for="image-sm" class="control-label">@lang('daran::common.featured-img') - @lang('daran::common.mobile')</label>
                    </div>
                    <div class="col-12">
                        <!-- input file img - x duplicazione, cambiare id="image1", $page->image, name  -->
                        <div class="input--image">
                            <input id="image2" type="file" name="image_sm" accept="image/*" hidden>
                            <div class="input--image__preview">
                                @if ($page->image_sm)
                                    <img src="{{config('app.url').'/'.$page->image_sm}}">
                                @endif
                            </div>
                            <div class="input--image__actions">
                                <label for="image2" class="add btn btn-primary w-100" type="button" name="button" @if ($page->image_sm) hidden="hidden" @endif>@lang('daran::common.select')</label>
                                <label data-type="mobile" class="delete btn btn-danger w-100" type="button" name="button" @if (!$page->image_sm) hidden="hidden" @endif>@lang('daran::common.delete')</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if($page->translations->count() > 0)
                <div class="col-12 mb-3">
                    <label class="control-label">@lang('daran::common.translations')</label>
                    <table class="table lang" id="table">
                        <tbody>
                            @foreach($page->translations as $page_trans)
                                <tr>
                                    <td>@lang('daran::common.'.$page_trans->locale)</td>
                                    @can('edit page')
                                        <td class="text-right">
                                            <a class="ico" href="{{ route('admin.pages.edit', ['id' => $page_trans->id]) }}" data-icon="N" title="@lang('daran::common.edit')" data-tooltip="tooltip"></a>
                                            <a class="ico" href="{{ route('admin.pages.clone', ['id' => $page_trans->id,'locale' => $page_trans->locale, 'locale_group' => $page_trans->locale_group]) }}" data-icon="x" title="@lang('daran::common.duplicate')" data-tooltip="tooltip"></a>
                                            @can('delete page')
                                                <a class="ico" href="#" data-icon="J" title="@lang('daran::common.delete')" data-tooltip="tooltip" data-name="{{$page_trans->title}}" data-id="{{$page_trans->id}}" data-toggle="modal" data-target="#confirm-delete"></a>
                                            @endcan
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            @if( $page->id && $page->translations->count() < count(config('app.available_translations'))-1)
                <div class="col-12 mb-3">
                    <label class="control-label">@lang('daran::common.translations')</label>
                    <table class="table" id="table">
                        <tbody>
                            @foreach(config('app.available_translations') as $trans)
                                @if ($page->locale != $trans && !$page->translations->contains('locale',$trans))
                                    <tr>
                                        <td>
                                            <label class="fc--blue">@lang('daran::common.'.$trans)</label>
                                        </td>
                                        @can('create page')
                                            <td class="text-right">
                                                <a class="ico" href="{{ route('admin.pages.create', ['locale' => $trans, 'locale_group' => $page->locale_group]) }}" data-icon="F" title="@lang('daran::common.create')" data-tooltip="tooltip"></a>
                                                <a class="ico" href="{{ route('admin.pages.clone', ['id' => $page->id,'locale' => $trans, 'locale_group' => $page->locale_group]) }}" data-icon="&#120;" title="@lang('daran::common.duplicate')" data-tooltip="tooltip"></a>
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
