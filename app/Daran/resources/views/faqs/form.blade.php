<div id="form" class="row" data-content="{{old('content',$faq->content)}}">
    <!-- LEFT -->
    <div class="col-8">
        <div class="mb-3">
            <label class="control-label">@lang('daran::common.title')*</label>
            <input type="text" name="title"required="required" maxlength="255" placeholder="@lang('daran::common.title')" value="{{old('title',$faq->title)}}" />
        </div>
        <div class="mb-3">
            <label class="control-label">@lang('daran::common.abstract')*</label>
            <input type="text" name="abstract" required="required" maxlength="255" placeholder="@lang('daran::common.abstract')" value="{{old('abstract',$faq->abstract)}}" />
        </div>
        @if($faq->slug)
            <div class="mb-3">
                <label class="control-label">@lang('daran::common.slug')*</label>
                <input type="text" id="slug" name="slug" required="required" placeholder="@lang('daran::common.slug')" value="{{old('slug',$faq->slug)}}" />
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
                                        <input type="text" maxlength="255" name="meta_title" value="{{old('meta_title',$faq->meta_title)}}" />
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="control-label">@lang('daran::common.og-title')</label>
                                        <input type="text" maxlength="255" name="og_title" value="{{old('og_title',$faq->og_title)}}" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label class="control-label">@lang('daran::common.meta-description')</label>
                                        <input type="text" maxlength="255" name="meta_description" value="{{old('meta_description',$faq->meta_description)}}" />
                                    </div>
                                    <div class="col-12">
                                        <label class="control-label">@lang('daran::common.og-description')</label>
                                        <input type="text" maxlength="255" name="og_description" value="{{old('og_description',$faq->og_description)}}" />
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
        <input type="hidden" name="state" id="state-field" value={{$faq->state}} />
        <div class="row">
            <div class="col-12 mb-3">
                <label class="control-label">@lang('daran::common.category')*</label>
                <select name="faq_category_id" class="select2-addible" required="required">
                    <option value="">@lang('daran::common.select')</option>
                    @foreach ($faqCategories as $faqCategory)
                        <option value="{{$faqCategory->id}}" {{($faqCategory->id == old('faq_category_id',$faq->faq_category_id) ? "selected='selected'":"")}}>{{$faqCategory->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 mb-3">
                <label class="control-label">@lang('daran::common.language')*</label>
                <select name="locale" class="select2" required="required" >
                    @foreach ($locales as $k=>$v)
                        <option value="{{$k}}" {{($k == old('locale',$faq->locale) ? "selected='selected'":"")}}>{{$v}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 mb-3">
                <div class="input-checkbox">
                    <input class="input-checkbox__hidden" name="seo" id="checkboxSearchEngine" type="checkbox" value="1" @if(old('seo',$faq->seo)==1) checked="checked"  @endif hidden/>
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
                    <input class="input-checkbox__hidden" name="search" id="checkboxInSearch" type="checkbox" value="1" @if(old('search',$faq->search)==1) checked="checked" @endif hidden/>
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
            @if($faq->translations->count() > 0)
                <div class="col-12 mb-3">
                    <label class="control-label">@lang('daran::common.translations')</label>
                    <table class="table lang" id="table">
                        <tbody>
                            @foreach($faq->translations as $faq_trans)
                                <tr>
                                    <td>@lang('daran::common.'.$faq_trans->locale)</td>
                                    @can('edit faq')
                                        <td class="text-right">
                                            <a class="ico" href="{{ route('admin.faqs.edit', ['id' => $faq_trans->id]) }}" data-icon="N" title="@lang('daran::common.edit')" data-tooltip="tooltip"></a>
                                            <a class="ico" href="{{ route('admin.faqs.clone', ['id' => $faq_trans->id,'locale' => $faq_trans->locale, 'locale_group' => $faq_trans->locale_group]) }}" data-icon="x" title="@lang('daran::common.duplicate')" data-tooltip="tooltip"></a>
                                            @can('delete faq')
                                                <a class="ico" href="#" data-icon="J" title="@lang('daran::common.delete')" data-tooltip="tooltip" data-name="{{$faq_trans->title}}" data-id="{{$faq_trans->id}}" data-toggle="modal" data-target="#confirm-delete"></a>
                                            @endcan
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            @if( $faq->id && $faq->translations->count() < count(config('app.available_translations'))-1)
                <div class="col-12 mb-3">
                    <label class="control-label">@lang('daran::common.translations')</label>
                    <table class="table" id="table">
                        <tbody>
                            @foreach(config('app.available_translations') as $trans)
                                @if ($faq->locale != $trans && !$faq->translations->contains('locale',$trans))
                                    <tr>
                                        <td>
                                            <label class="fc--blue">@lang('daran::common.'.$trans)</label>
                                        </td>
                                        @can('create faq')
                                            <td class="text-right">
                                                <a class="ico" href="{{ route('admin.faqs.create', ['locale' => $trans, 'locale_group' => $faq->locale_group]) }}" data-icon="F" title="@lang('daran::common.create')" data-tooltip="tooltip"></a>
                                                <a class="ico" href="{{ route('admin.faqs.clone', ['id' => $faq->id,'locale' => $trans, 'locale_group' => $faq->locale_group]) }}" data-icon="&#120;" title="@lang('daran::common.duplicate')" data-tooltip="tooltip"></a>
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
