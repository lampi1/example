<div class="row">
    <!-- LEFT -->
    <div class="col-8">
        <div class="mb-3">
            <label class="control-label">@lang('daran::common.title')*</label>
            <input type="text" name="name" required="required" placeholder="@lang('daran::common.title')" value="{{old('name',$category->name)}}" />
        </div>
        @if($category->slug)
            <div class="mb-3">
                <label class="control-label">@lang('daran::common.slug')*</label>
                <input type="text" id="slug" name="slug" required="required" placeholder="@lang('daran::common.slug')" value="{{old('slug',$category->slug)}}" />
            </div>
        @endif

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
                                        <input type="text" maxlength="255" name="meta_title" value="{{old('meta_title',$category->meta_title)}}" />
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="control-label">@lang('daran::common.og-title')</label>
                                        <input type="text" maxlength="255" name="og_title" value="{{old('og_title',$category->og_title)}}" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label class="control-label">@lang('daran::common.meta-description')</label>
                                        <input type="text" maxlength="255" name="meta_description" value="{{old('meta_description',$category->meta_description)}}" />
                                    </div>
                                    <div class="col-12">
                                        <label class="control-label">@lang('daran::common.og-description')</label>
                                        <input type="text" maxlength="255" name="og_description" value="{{old('og_description',$category->og_description)}}" />
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
        <div class="row">
            <div class="col-12 mb-3">
                <label class="control-label">@lang('daran::common.language')*</label>
                <select name="locale" class="select2">
                    @foreach ($locales as $k=>$v)
                        <option value="{{$k}}" {{($k == old('locale',$category->locale) ? "selected='selected'":"")}}>{{$v}}</option>
                    @endforeach
                </select>
            </div>
            @if($category->translations->count() > 0)
                <div class="col-12">
                    <label class="control-label">@lang('daran::common.translations')</label>
                    <table class="table" id="table">
                        <tbody>
                            @foreach($category->translations as $category_trans)
                                <tr>
                                    <td>@lang('daran::common.'.$category_trans->locale)</td>
                                    @can('edit news')
                                        <td class="text-right">
                                            <a class="ico" href="{{ route('admin.gallery-categories.edit', ['id' => $category_trans->id]) }}" data-icon="N" title="@lang('daran::common.edit')" data-tooltip="tooltip"></a>
                                            @can('delete news')
                                                <a class="ico" href="#" data-icon="J" title="@lang('daran::common.delete')" data-tooltip="tooltip" data-name="{{$category_trans->title}}" data-id="{{$category_trans->id}}" data-toggle="modal" data-target="#confirm-delete"></a>
                                            @endcan
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            @if($category->id && $category->translations->count() < count(config('app.available_translations'))-1)
                <div class="col-12">
                    <label class="control-label">@lang('daran::common.translations')</label>
                    <table class="table" id="table">
                        <tbody>
                            @foreach(config('app.available_translations') as $trans)
                                @if ($category->locale != $trans && !$category->translations->contains('locale',$trans))
                                    <tr>
                                        <td>@lang('daran::common.'.$trans)</td>
                                        @can('create news')
                                            <td class="text-right">
                                                <a class="ico" href="{{ route('admin.gallery-categories.create', ['locale' => $trans, 'locale_group' => $category->locale_group]) }}" data-icon="F" title="@lang('daran::common.create')" data-tooltip="tooltip"></a>
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
