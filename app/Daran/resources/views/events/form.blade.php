<div id="form" class="row" data-content="{{old('content',$event->content)}}">
    <!-- LEFT -->
    <div class="col-8">
        <div class="mb-3">
            <label class="control-label">@lang('daran::common.title')*</label>
            <input type="text" name="title"required="required" maxlength="255" placeholder="@lang('daran::common.title')" value="{{old('title',$event->title)}}" />
        </div>
        <div class="mb-3">
            <label class="control-label">@lang('daran::common.abstract')*</label>
            <input type="text" name="abstract" required="required" maxlength="255" placeholder="@lang('daran::common.abstract')" value="{{old('abstract',$event->abstract)}}" />
        </div>
        @if($event->slug)
            <div class="mb-3">
                <label class="control-label">@lang('daran::common.slug')*</label>
                <input type="text" id="slug" name="slug" required="required" placeholder="@lang('daran::common.slug')" value="{{old('slug',$event->slug)}}" />
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
                                        <input type="text" maxlength="255" name="meta_title" value="{{old('meta_title',$event->meta_title)}}" />
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="control-label">@lang('daran::common.og-title')</label>
                                        <input type="text" maxlength="255" name="og_title" value="{{old('og_title',$event->og_title)}}" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label class="control-label">@lang('daran::common.meta-description')</label>
                                        <input type="text" maxlength="255" name="meta_description" value="{{old('meta_description',$event->meta_description)}}" />
                                    </div>
                                    <div class="col-12">
                                        <label class="control-label">@lang('daran::common.og-description')</label>
                                        <input type="text" maxlength="255" name="og_description" value="{{old('og_description',$event->og_description)}}" />
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
                                    @if($event->event_attachments->count() > 0)
                                        @foreach($event->event_attachments as $eventatt)
                                            <tr>
                                                <td style="width:60%;">
                                                    <a class="imgAttachmentUrl" href="{{$eventatt->file}}" target="_blank">{{$eventatt->title}}</a>
                                                </td>
                                                <td>
                                                    <span class="attachment-remove-old" data-id="{{$eventatt->id}}">
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
        <input type="hidden" name="state" id="state-field" value={{$event->state}} />
        <div class="row">
            <div class="col-12 mb-3">
                <label class="control-label">@lang('daran::common.category')*</label>
                <select name="event_category_id" class="select2-addible" required="required">
                    <option value="">@lang('daran::common.select')</option>
                    @foreach ($eventCategories as $eventCategory)
                        <option value="{{$eventCategory->id}}" {{($eventCategory->id == old('event_category_id',$event->event_category_id) ? "selected='selected'":"")}}>{{$eventCategory->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 mb-3">
                <label class="control-label">@lang('daran::common.language')*</label>
                <select name="locale" class="select2" required="required" >
                    @foreach ($locales as $k=>$v)
                        <option value="{{$k}}" {{($k == old('locale',$event->locale) ? "selected='selected'":"")}}>{{$v}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 mb-3">
                <label for="from" class="control-label">@lang('daran::common.start-date')</label>
                <input type="text" class="form_datetime" name="date_start" value="@if(isset($event->date_start)) {{$event->date_start->format('d/m/Y H:i')}} @endif"></input>
            </div>
            <div class="col-12 mb-3">
                <label for="from" class="control-label">@lang('daran::common.end-date')</label>
                <input type="text" class="form_datetime" name="date_end" value="@if(isset($event->date_end)) {{$event->date_end->format('d/m/Y H:i')}} @endif"></input>
            </div>
            <div class="col-12 mb-3">
                <div class="input-checkbox">
                    <input class="input-checkbox__hidden" name="seo" id="checkboxSearchEngine" type="checkbox" value="1" @if(old('seo',$event->seo)==1) checked="checked"  @endif hidden/>
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
                    <input class="input-checkbox__hidden" name="search" id="checkboxInSearch" type="checkbox" value="1" @if(old('search',$event->search)==1) checked="checked" @endif hidden/>
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

                        <!-- input file img - x duplicazione, cambiare id="image1", $event->image, name  -->
                        <div class="input--image">
                            <input id="image1" type="file" name="image" accept="image/*" hidden>
                            <div class="input--image__preview">
                                @if ($event->image)
                                    <img src="{{config('app.url').'/'.$event->image}}">
                                @endif
                            </div>
                            <div class="input--image__actions">
                                <label for="image1" class="add btn btn-primary w-100" type="button" name="button" @if ($event->image) hidden="hidden" @endif>@lang('daran::common.select')</label>
                                <label data-type="standard" class="delete btn btn-danger w-100" type="button" name="button" @if (!$event->image) hidden="hidden" @endif>@lang('daran::common.delete')</label>
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
                                @if ($event->image_sm)
                                    <img src="{{config('app.url').'/'.$event->image_sm}}">
                                @endif
                            </div>
                            <div class="input--image__actions">
                                <label for="image2" class="add btn btn-primary w-100" type="button" name="button" @if ($event->image_sm) hidden="hidden" @endif>@lang('daran::common.select')</label>
                                <label data-type="mobile" class="delete btn btn-danger w-100" type="button" name="button" @if (!$event->image_sm) hidden="hidden" @endif>@lang('daran::common.delete')</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if($event->translations->count() > 0)
                <div class="col-12 mb-3">
                    <label class="control-label">@lang('daran::common.translations')</label>
                    <table class="table lang" id="table">
                        <tbody>
                            @foreach($event->translations as $event_trans)
                                <tr>
                                    <td>@lang('daran::common.'.$event_trans->locale)</td>
                                    @can('edit event')
                                        <td class="text-right">
                                            <a class="ico" href="{{ route('admin.events.edit', ['id' => $event_trans->id]) }}" data-icon="N" title="@lang('daran::common.edit')" data-tooltip="tooltip"></a>
                                            <a class="ico" href="{{ route('admin.events.clone', ['id' => $event_trans->id,'locale' => $event_trans->locale, 'locale_group' => $event_trans->locale_group]) }}" data-icon="x" title="@lang('daran::common.duplicate')" data-tooltip="tooltip"></a>
                                            @can('delete event')
                                                <a class="ico" href="#" data-icon="J" title="@lang('daran::common.delete')" data-tooltip="tooltip" data-name="{{$event_trans->title}}" data-id="{{$event_trans->id}}" data-toggle="modal" data-target="#confirm-delete"></a>
                                            @endcan
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            @if( $event->id && $event->translations->count() < count(config('app.available_translations'))-1)
                <div class="col-12 mb-3">
                    <label class="control-label">@lang('daran::common.translations')</label>
                    <table class="table" id="table">
                        <tbody>
                            @foreach(config('app.available_translations') as $trans)
                                @if ($event->locale != $trans && !$event->translations->contains('locale',$trans))
                                    <tr>
                                        <td>
                                            <label class="fc--blue">@lang('daran::common.'.$trans)</label>
                                        </td>
                                        @can('create event')
                                            <td class="text-right">
                                                <a class="ico" href="{{ route('admin.events.create', ['locale' => $trans, 'locale_group' => $event->locale_group]) }}" data-icon="F" title="@lang('daran::common.create')" data-tooltip="tooltip"></a>
                                                <a class="ico" href="{{ route('admin.events.clone', ['id' => $event->id,'locale' => $trans, 'locale_group' => $event->locale_group]) }}" data-icon="&#120;" title="@lang('daran::common.duplicate')" data-tooltip="tooltip"></a>
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
