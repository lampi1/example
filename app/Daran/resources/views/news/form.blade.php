<div id="form" class="row" data-content="{{old('content',$news->content)}}">
    <!-- LEFT -->
    <div class="col-8">
        <div class="mb-3">
            <label class="control-label">@lang('daran::common.title')*</label>
            <input type="text" name="title"required="required" maxlength="255" placeholder="@lang('daran::common.title')" value="{{old('title',$news->title)}}" />
        </div>
        {{-- <div class="mb-3">
            <label class="control-label">@lang('daran::common.abstract')*</label>
            <input type="text" name="abstract" required="required" maxlength="255" placeholder="@lang('daran::common.abstract')" value="{{old('abstract',$news->abstract)}}" />
        </div> --}}
        @if($news->slug)
            <div class="mb-3">
                <label class="control-label">@lang('daran::common.slug')*</label>
                <input type="text" id="slug" name="slug" required="required" placeholder="@lang('daran::common.slug')" value="{{old('slug',$news->slug)}}" />
            </div>
        @endif
        <div class="mb-3">
            <label class="control-label">@lang('daran::common.content')</label>
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
                                        <input type="text" maxlength="255" name="meta_title" value="{{old('meta_title',$news->meta_title)}}" />
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="control-label">@lang('daran::common.og-title')</label>
                                        <input type="text" maxlength="255" name="og_title" value="{{old('og_title',$news->og_title)}}" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label class="control-label">@lang('daran::common.meta-description')</label>
                                        <input type="text" maxlength="255" name="meta_description" value="{{old('meta_description',$news->meta_description)}}" />
                                    </div>
                                    <div class="col-12">
                                        <label class="control-label">@lang('daran::common.og-description')</label>
                                        <input type="text" maxlength="255" name="og_description" value="{{old('og_description',$news->og_description)}}" />
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
                            <table class="table table-striped" id="table-attachement">
                                <thead>
                                    <tr>
                                        <th>@lang('daran::common.table-title')</th>
                                        <th>@lang('daran::common.table-actions')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($news->news_attachments->count() > 0)
                                        @foreach($news->news_attachments as $newsatt)
                                            <tr>
                                                <td style="width:60%;">
                                                    <a class="imgAttachmentUrl" href="{{$newsatt->file}}" target="_blank">{{$newsatt->title}}</a>
                                                </td>
                                                <td>
                                                    <span class="attachment-remove-old" data-id="{{$newsatt->id}}">
                                                        <img class="customIco" height="25" width="25" src="{{ asset('admin/images/icons/rimuovi-black.svg') }}" alt="@lang('daran::common.remove-attachment')">
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    <tr>
                                        <td style="width:60%;" class="title-attachment">
                                            <input type="text" name="attachment_title[]" placeholder="@lang('daran::common.title')*" value="" />
                                        </td>
                                        <td>
                                            <input id="loadNewAttachment" type="file" name="attachment_file[]" class="" value="" />
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
        <input type="hidden" name="state" id="state-field" value={{$news->state}} />
        <div class="row">
            <div class="col-12 mb-3">
                <label class="control-label">@lang('daran::common.category')*</label>
                <select name="news_category_id" class="select2-addible" required="required">
                    <option value="">@lang('daran::common.select')</option>
                    @foreach ($newsCategories as $newsCategory)
                        <option value="{{$newsCategory->id}}" {{($newsCategory->id == old('news_category_id',$news->news_category_id) ? "selected='selected'":"")}}>{{$newsCategory->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 mb-3">
                <label class="control-label">@lang('daran::common.language')*</label>
                <select name="locale" class="select2" required="required" >
                    @foreach ($locales as $k=>$v)
                        <option value="{{$k}}" {{($k == old('locale',$news->locale) ? "selected='selected'":"")}}>{{$v}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 mb-3">
                <label for="link" class="control-label">@lang('daran::common.link')</label>
                <input type="text" name="link" value="{{$news->link}}" />
            </div>
            <div class="col-12 mb-3">
                <label for="from" class="control-label">@lang('daran::common.schedule')</label>
                <input type="text" class="form_datetime" name="scheduled_at" value="@if(isset($news->scheduled_at)) {{$news->scheduled_at->format('d/m/Y H:i')}} @endif"></input>
            </div>
            <div class="col-12 mb-3">
                <label for="from" class="control-label">@lang('daran::common.expiry')</label>
                <input type="text" class="form_datetime" name="ended_at" value="@if(isset($news->ended_at)) {{$news->ended_at->format('d/m/Y H:i')}} @endif"></input>
            </div>
            <div class="col-12 mb-3">
                <div class="input-checkbox">
                    <input class="input-checkbox__hidden" name="seo" id="checkboxSearchEngine" type="checkbox" value="1" @if(old('seo',$news->seo)==1) checked="checked"  @endif hidden/>
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
                    <input class="input-checkbox__hidden" name="search" id="checkboxInSearch" type="checkbox" value="1" @if(old('search',$news->search)==1) checked="checked" @endif hidden/>
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

                        <!-- input file img - x duplicazione, cambiare id="image1", $news->image, name  -->
                        <div class="input--image">
                            <input id="image1" type="file" name="image" accept="image/*" hidden>
                            <div class="input--image__preview">
                                @if ($news->image)
                                    <img src="{{config('app.url').'/'.$news->image}}">
                                @endif
                            </div>
                            <div class="input--image__actions">
                                <label for="image1" class="add btn btn-primary w-100" type="button" name="button" @if ($news->image) hidden="hidden" @endif>@lang('daran::common.select')</label>
                                <label data-type="standard" class="delete btn btn-danger w-100" type="button" name="button" @if (!$news->image) hidden="hidden" @endif>@lang('daran::common.delete')</label>
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
                                @if ($news->image_sm)
                                    <img src="{{config('app.url').'/'.$news->image_sm}}">
                                @endif
                            </div>
                            <div class="input--image__actions">
                                <label for="image2" class="add btn btn-primary w-100" type="button" name="button" @if ($news->image_sm) hidden="hidden" @endif>@lang('daran::common.select')</label>
                                <label data-type="mobile" class="delete btn btn-danger w-100" type="button" name="button" @if (!$news->image_sm) hidden="hidden" @endif>@lang('daran::common.delete')</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if($news->translations->count() > 0)
                <div class="col-12 mb-3">
                    <label class="control-label">@lang('daran::common.translations')</label>
                    <table class="table lang" id="table">
                        <tbody>
                            @foreach($news->translations as $news_trans)
                                <tr>
                                    <td>@lang('daran::common.'.$news_trans->locale)</td>
                                    @can('edit news')
                                        <td class="text-right">
                                            <a class="ico" href="{{ route('admin.news.edit', ['id' => $news_trans->id]) }}" data-icon="N" title="@lang('daran::common.edit')" data-tooltip="tooltip"></a>
                                            <a class="ico" href="{{ route('admin.news.clone', ['id' => $news_trans->id,'locale' => $news_trans->locale, 'locale_group' => $news_trans->locale_group]) }}" data-icon="x" title="@lang('daran::common.duplicate')" data-tooltip="tooltip"></a>
                                            @can('delete news')
                                                <a class="ico" href="#" data-icon="J" title="@lang('daran::common.delete')" data-tooltip="tooltip" data-name="{{$news_trans->title}}" data-id="{{$news_trans->id}}" data-toggle="modal" data-target="#confirm-delete"></a>
                                            @endcan
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            @if( $news->id && $news->translations->count() < count(config('app.available_translations'))-1)
                <div class="col-12 mb-3">
                    <label class="control-label">@lang('daran::common.translations')</label>
                    <table class="table" id="table">
                        <tbody>
                            @foreach(config('app.available_translations') as $trans)
                                @if ($news->locale != $trans && !$news->translations->contains('locale',$trans))
                                    <tr>
                                        <td>
                                            <label class="fc--blue">@lang('daran::common.'.$trans)</label>
                                        </td>
                                        @can('create news')
                                            <td class="text-right">
                                                <a class="ico" href="{{ route('admin.news.create', ['locale' => $trans, 'locale_group' => $news->locale_group]) }}" data-icon="F" title="@lang('daran::common.create')" data-tooltip="tooltip"></a>
                                                <a class="ico" href="{{ route('admin.news.clone', ['id' => $news->id,'locale' => $trans, 'locale_group' => $news->locale_group]) }}" data-icon="&#120;" title="@lang('daran::common.duplicate')" data-tooltip="tooltip"></a>
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
