<div class="row">
    <!-- LEFT -->
    <div class="col-8">
        <div class="mb-3">
            <label class="control-label">@lang('daran::common.title')*</label>
            <input type="text" name="name" required="required" placeholder="@lang('daran::common.title')" value="{{old('name',$faqCategory->name)}}" />
        </div>
        @if($faqCategory->slug)
            <div class="mb-3">
                <label class="control-label">@lang('daran::common.slug')*</label>
                <input type="text" id="slug" name="slug" required="required" placeholder="@lang('daran::common.slug')" value="{{old('slug',$faqCategory->slug)}}" />
            </div>
        @endif
    </div>
    <div class="col-4">
        <div class="row">
            <div class="col-12 mb-3">
                <label class="control-label">@lang('daran::common.language')*</label>
                <select name="locale" class="select2">
                    @foreach ($locales as $k=>$v)
                        <option value="{{$k}}" {{($k == old('locale',$faqCategory->locale) ? "selected='selected'":"")}}>{{$v}}</option>
                    @endforeach
                </select>
            </div>
            @if($faqCategory->translations->count() > 0)
                <div class="col-12">
                    <label class="control-label">@lang('daran::common.translations')</label>
                    <table class="table" id="table">
                        <tbody>
                            @foreach($faqCategory->translations as $faqCategory_trans)
                                <tr>
                                    <td>@lang('daran::common.'.$faqCategory_trans->locale)</td>
                                    @can('edit faq')
                                        <td class="text-right">
                                            <a class="ico" href="{{ route('admin.faq-categories.edit', ['id' => $faqCategory_trans->id]) }}" data-icon="N" title="@lang('daran::common.edit')" data-tooltip="tooltip"></a>
                                            @can('delete faq')
                                                <a class="ico" href="#" data-icon="J" title="@lang('daran::common.delete')" data-tooltip="tooltip" data-name="{{$faqCategory_trans->title}}" data-id="{{$faqCategory_trans->id}}" data-toggle="modal" data-target="#confirm-delete"></a>
                                            @endcan
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            @if($faqCategory->id && $faqCategory->translations->count() < count(config('app.available_translations'))-1)
                <div class="col-12">
                    <label class="control-label">@lang('daran::common.translations')</label>
                    <table class="table" id="table">
                        <tbody>
                            @foreach(config('app.available_translations') as $trans)
                                @if ($faqCategory->locale != $trans && !$faqCategory->translations->contains('locale',$trans))
                                    <tr>
                                        <td>@lang('daran::common.'.$trans)</td>
                                        @can('create faq')
                                            <td class="text-right">
                                                <a class="ico" href="{{ route('admin.faq-categories.create', ['locale' => $trans, 'locale_group' => $faqCategory->locale_group]) }}" data-icon="F" title="@lang('daran::common.create')" data-tooltip="tooltip"></a>
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
