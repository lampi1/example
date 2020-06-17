<div class="row">
    <!-- LEFT -->
    <div class="col-8">
        <div class="mb-3">
            <label class="control-label">@lang('daran::common.title')*</label>
            <input type="text" name="name" required="required" placeholder="@lang('daran::common.title')" value="{{old('name',$pageCategory->name)}}" />
        </div>
        @if($pageCategory->slug)
            <div class="mb-3">
                <label class="control-label">@lang('daran::common.slug')*</label>
                <input type="text" id="slug" name="slug" required="required" placeholder="@lang('daran::common.slug')" value="{{old('slug',$pageCategory->slug)}}" />
            </div>
        @endif
    </div>
    <div class="col-4">
        <div class="row">
            <div class="col-12 mb-3">
                <label class="control-label">@lang('daran::common.language')*</label>
                <select name="locale" class="select2">
                    @foreach ($locales as $k=>$v)
                        <option value="{{$k}}" {{($k == old('locale',$pageCategory->locale) ? "selected='selected'":"")}}>{{$v}}</option>
                    @endforeach
                </select>
            </div>
            @if($pageCategory->translations->count() > 0)
                <div class="col-12">
                    <label class="control-label">@lang('daran::common.translations')</label>
                    <table class="table" id="table">
                        <tbody>
                            @foreach($pageCategory->translations as $pageCategory_trans)
                                <tr>
                                    <td>@lang('daran::common.'.$pageCategory_trans->locale)</td>
                                    @can('edit page')
                                        <td class="text-right">
                                            <a class="ico" href="{{ route('admin.page-categories.edit', ['id' => $pageCategory_trans->id]) }}" data-icon="N" title="@lang('daran::common.edit')" data-tooltip="tooltip"></a>
                                            @can('delete page')
                                                <a class="ico" href="#" data-icon="J" title="@lang('daran::common.delete')" data-tooltip="tooltip" data-name="{{$pageCategory_trans->title}}" data-id="{{$pageCategory_trans->id}}" data-toggle="modal" data-target="#confirm-delete"></a>
                                            @endcan
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            @if($pageCategory->id && $pageCategory->translations->count() < count(config('app.available_translations'))-1)
                <div class="col-12">
                    <label class="control-label">@lang('daran::common.translations')</label>
                    <table class="table" id="table">
                        <tbody>
                            @foreach(config('app.available_translations') as $trans)
                                @if ($pageCategory->locale != $trans && !$pageCategory->translations->contains('locale',$trans))
                                    <tr>
                                        <td>@lang('daran::common.'.$trans)</td>
                                        @can('create page')
                                            <td class="text-right">
                                                <a class="ico" href="{{ route('admin.page-categories.create', ['locale' => $trans, 'locale_group' => $pageCategory->locale_group]) }}" data-icon="F" title="@lang('daran::common.create')" data-tooltip="tooltip"></a>
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
