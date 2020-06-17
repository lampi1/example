<div id="form" class="row" data-content="{{old('content',$project->content)}}">
    <!-- LEFT -->
    <div class="col-8">
        <div class="mb-3">
            <label class="control-label">@lang('daran::common.title')*</label>
            <input type="text" name="title" required="required" maxlength="255" placeholder="@lang('daran::common.title')" value="{{old('title',$project->title)}}" />
        </div>
        <div class="mb-3">
            <label class="control-label">@lang('daran::common.subtitle')</label>
            <input type="text" name="subtitle" required="required" maxlength="255" placeholder="@lang('daran::common.subtitle')" value="{{old('subtitle',$project->subtitle)}}" />
        </div>
        <div class="mb-3">
            <label class="control-label">@lang('daran::common.abstract')*</label>
            <input type="text" name="abstract" required="required" maxlength="255" placeholder="@lang('daran::common.abstract')" value="{{old('abstract',$project->abstract)}}" />
        </div>
        @if($project->slug)
            <div class="mb-3">
                <label class="control-label">@lang('daran::common.slug')*</label>
                <input type="text" id="slug" name="slug" required="required" placeholder="@lang('daran::common.slug')" value="{{old('slug',$project->slug)}}" />
            </div>
        @endif
        <div class="mb-3">
            <label class="control-label">Titolo contenuto </label>
            <input type="text" name="content_title" maxlength="255" placeholder="Titolo contenuto" value="{{old('content_title',$project->content_title)}}" />
        </div>
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
                                        <input type="text" maxlength="255" name="meta_title" value="{{old('meta_title',$project->meta_title)}}" />
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="control-label">@lang('daran::common.og-title')</label>
                                        <input type="text" maxlength="255" name="og_title" value="{{old('og_title',$project->og_title)}}" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label class="control-label">@lang('daran::common.meta-description')</label>
                                        <input type="text" maxlength="255" name="meta_description" value="{{old('meta_description',$project->meta_description)}}" />
                                    </div>
                                    <div class="col-12">
                                        <label class="control-label">@lang('daran::common.og-description')</label>
                                        <input type="text" maxlength="255" name="og_description" value="{{old('og_description',$project->og_description)}}" />
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
        <input type="hidden" name="state" id="state-field" value={{$project->state}} />
        <div class="row">
            <div class="col-12 mb-3">
                <label class="control-label">@lang('daran::common.category')*</label>
                <select name="project_category_id" class="select2-addible" required="required">
                    <option value="">@lang('daran::common.select')</option>
                    @foreach ($categories as $postCategory)
                        <option value="{{$postCategory->id}}" {{($postCategory->id == old('project_category_id',$project->project_category_id) ? "selected='selected'":"")}}>{{$postCategory->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 mb-3">
                <label class="control-label">@lang('daran::common.language')*</label>
                <select name="locale" class="select2" required="required" >
                    @foreach ($locales as $k=>$v)
                        <option value="{{$k}}" {{($k == old('locale',$project->locale) ? "selected='selected'":"")}}>{{$v}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 mb-3">
                <label class="control-label">@lang('daran::common.tags')</label>
                <daran-vuetags :own-tags="{{$vue_tags}}" :all-tags="{{$vue_all_tags}}"></daran-vuetags>
            </div>
            <div class="col-12 mb-3">
                <div class="input-checkbox">
                    <input class="input-checkbox__hidden" name="seo" id="checkboxSearchEngine" type="checkbox" value="1" @if(old('seo',$project->seo)==1) checked="checked"  @endif hidden/>
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
                    <input class="input-checkbox__hidden" name="search" id="checkboxInSearch" type="checkbox" value="1" @if(old('search',$project->search)==1) checked="checked" @endif hidden/>
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
            <div class="col-12">
                <div class="row mb-3">
                    <div class="col-12 mt-4">
                        <label for="video_mp4" class="control-label">Copertina Video</label>
                    </div>
                    <div class="col-12 mb-4">
                        <!-- input file img - x duplicazione, cambiare id="image1", $project->image, name  -->
                        <div class="input--image">
                            <input id="imagevideo" type="file" name="image_video" accept="image/*" hidden>
                            <div class="input--image__preview">
                                @if ($project->image_video)
                                    <img src="{{config('app.url').'/'.$project->image_video}}">
                                @endif
                            </div>
                            <div class="input--image__actions">
                                <label for="imagevideo" class="add btn btn-primary w-100" type="button" name="button" @if ($project->image_video) hidden="hidden" @endif>@lang('daran::common.select')</label>
                                <label data-type="mobile" class="delete btn btn-danger w-100" type="button" name="button" @if (!$project->image_video) hidden="hidden" @endif>@lang('daran::common.delete')</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="video_mp4" class="control-label">Video Formato MP4</label>
                    </div>
                    <div class="col-12 mb-4">
                        @if($project->video_mp4)
                            <a target="_blank" class="delete btn btn-primary w-100 mb-2" href="{{config('app.url').$project->video_mp4}}">Vedi</a>
                            <button data-type="video_mp4" class="delete-video btn btn-danger w-100 mb-2" type="button" name="button">@lang('daran::common.delete')</button>
                        @endif
                        <input type="file" name="video_mp4" value="" @if ($project->video_mp4) style="display:none"  @endif />
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="video_ogv" class="control-label">Video Formato OGV</label>
                    </div>
                    <div class="col-12 mb-4">
                        @if($project->video_ogv)
                            <a target="_blank" class="delete btn btn-primary w-100 mb-2" href="{{config('app.url').$project->video_ogv}}">Vedi</a>
                            <button data-type="video_ogv" class="delete-video btn btn-danger w-100 mb-2" type="button" name="button">@lang('daran::common.delete')</button>
                        @endif
                        <input type="file" name="video_ogv" value="" @if ($project->video_ogv) style="display:none" @endif />
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="video_webm" class="control-label">Video Formato WebM</label>
                    </div>
                    <div class="col-12">
                        @if($project->video_webm)
                            <a target="_blank" class="delete btn btn-primary w-100 mb-2" href="{{config('app.url').$project->video_webm}}">Vedi</a>
                            <button data-type="video_webm" class="delete-video btn btn-danger w-100 mb-2" type="button" name="button">@lang('daran::common.delete')</button>
                        @endif
                        <input type="file" name="video_webm" value="" @if ($project->video_webm) style="display:none" @endif />
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="image" class="control-label">@lang('daran::common.featured-img')</label>
                    </div>
                    <div class="col-12">

                        <!-- input file img - x duplicazione, cambiare id="image1", $project->image, name  -->
                        <div class="input--image">
                            <input id="image1" type="file" name="image" accept="image/*" hidden>
                            <div class="input--image__preview">
                                @if ($project->image)
                                    <img src="{{config('app.url').'/'.$project->image}}">
                                @endif
                            </div>
                            <div class="input--image__actions">
                                <label for="image1" class="add btn btn-primary w-100" type="button" name="button" @if ($project->image) hidden="hidden" @endif>@lang('daran::common.select')</label>
                                <label data-type="standard" class="delete btn btn-danger w-100" type="button" name="button" @if (!$project->image) hidden="hidden" @endif>@lang('daran::common.delete')</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="image-sm" class="control-label">Immagine di preview</label>
                    </div>
                    <div class="col-12">
                        <!-- input file img - x duplicazione, cambiare id="image1", $project->image, name  -->
                        <div class="input--image">
                            <input id="image2" type="file" name="image_sm" accept="image/*" hidden>
                            <div class="input--image__preview">
                                @if ($project->image_sm)
                                    <img src="{{config('app.url').'/'.$project->image_sm}}">
                                @endif
                            </div>
                            <div class="input--image__actions">
                                <label for="image2" class="add btn btn-primary w-100" type="button" name="button" @if ($project->image_sm) hidden="hidden" @endif>@lang('daran::common.select')</label>
                                <label data-type="mobile" class="delete btn btn-danger w-100" type="button" name="button" @if (!$project->image_sm) hidden="hidden" @endif>@lang('daran::common.delete')</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if($project->translations->count() > 0)
                <div class="col-12 mb-3">
                    <label class="control-label">@lang('daran::common.translations')</label>
                    <table class="table lang" id="table">
                        <tbody>
                            @foreach($project->translations as $project_trans)
                                <tr>
                                    <td>@lang('daran::common.'.$project_trans->locale)</td>
                                    @can('edit page')
                                        <td class="text-right">
                                            <a class="ico" href="{{ route('admin.pages.edit', ['id' => $project_trans->id]) }}" data-icon="N" title="@lang('daran::common.edit')" data-tooltip="tooltip"></a>
                                            <a class="ico" href="{{ route('admin.pages.clone', ['id' => $project_trans->id,'locale' => $project_trans->locale, 'locale_group' => $project_trans->locale_group]) }}" data-icon="x" title="@lang('daran::common.duplicate')" data-tooltip="tooltip"></a>
                                            @can('delete page')
                                                <a class="ico" href="#" data-icon="J" title="@lang('daran::common.delete')" data-tooltip="tooltip" data-name="{{$project_trans->title}}" data-id="{{$project_trans->id}}" data-toggle="modal" data-target="#confirm-delete"></a>
                                            @endcan
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            @if( $project->id && $project->translations->count() < count(config('app.available_translations'))-1)
                <div class="col-12 mb-3">
                    <label class="control-label">@lang('daran::common.translations')</label>
                    <table class="table" id="table">
                        <tbody>
                            @foreach(config('app.available_translations') as $trans)
                                @if ($project->locale != $trans && !$project->translations->contains('locale',$trans))
                                    <tr>
                                        <td>
                                            <label class="fc--blue">@lang('daran::common.'.$trans)</label>
                                        </td>
                                        @can('create page')
                                            <td class="text-right">
                                                <a class="ico" href="{{ route('admin.pages.create', ['locale' => $trans, 'locale_group' => $project->locale_group]) }}" data-icon="F" title="@lang('daran::common.create')" data-tooltip="tooltip"></a>
                                                <a class="ico" href="{{ route('admin.pages.clone', ['id' => $project->id,'locale' => $trans, 'locale_group' => $project->locale_group]) }}" data-icon="&#120;" title="@lang('daran::common.duplicate')" data-tooltip="tooltip"></a>
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
