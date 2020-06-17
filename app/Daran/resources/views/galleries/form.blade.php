<div id="form" class="row" data-content="{{old('content',$gallery->content)}}">
    <!-- LEFT -->
    <div class="col-8">
        <div class="mb-3">
            <label class="control-label">@lang('daran::common.title')*</label>
            <input type="text" name="title"required="required" maxlength="255" placeholder="@lang('daran::common.title')" value="{{old('title',$gallery->title)}}" />
        </div>
        @if($gallery->slug)
            <div class="mb-3">
                <label class="control-label">@lang('daran::common.slug')*</label>
                <input type="text" id="slug" name="slug" required="required" placeholder="@lang('daran::common.slug')" value="{{old('slug',$gallery->slug)}}" />
            </div>
        @endif
    </div>
    <div class="col-4">
        <input type="hidden" name="state" id="state-field" value={{$gallery->state}} />
        <div class="row">
            <div class="col-12 mb-3">
                <label class="control-label">@lang('daran::common.language')*</label>
                <select name="locale" class="select2" required="required" >
                    @foreach ($locales as $k=>$v)
                        <option value="{{$k}}" {{($k == old('locale',$gallery->locale) ? "selected='selected'":"")}}>{{$v}}</option>
                    @endforeach
                </select>
            </div>
            @if($gallery->translations->count() > 0)
                <div class="col-12 mb-3">
                    <label class="control-label">@lang('daran::common.translations')</label>
                    <table class="table lang" id="table">
                        <tbody>
                            @foreach($gallery->translations as $post_trans)
                                <tr>
                                    <td>@lang('daran::common.'.$post_trans->locale)</td>
                                    @can('edit post')
                                        <td class="text-right">
                                            <a class="ico" href="{{ route('admin.posts.edit', ['id' => $post_trans->id]) }}" data-icon="N" title="@lang('daran::common.edit')" data-tooltip="tooltip"></a>
                                            <a class="ico" href="{{ route('admin.posts.clone', ['id' => $post_trans->id,'locale' => $post_trans->locale, 'locale_group' => $post_trans->locale_group]) }}" data-icon="x" title="@lang('daran::common.duplicate')" data-tooltip="tooltip"></a>
                                            @can('delete post')
                                                <a class="ico" href="#" data-icon="J" title="@lang('daran::common.delete')" data-tooltip="tooltip" data-name="{{$post_trans->title}}" data-id="{{$post_trans->id}}" data-toggle="modal" data-target="#confirm-delete"></a>
                                            @endcan
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            @if( $gallery->id && $gallery->translations->count() < count(config('app.available_translations'))-1)
                <div class="col-12 mb-3">
                    <label class="control-label">@lang('daran::common.translations')</label>
                    <table class="table" id="table">
                        <tbody>
                            @foreach(config('app.available_translations') as $trans)
                                @if ($gallery->locale != $trans && !$gallery->translations->contains('locale',$trans))
                                    <tr>
                                        <td>
                                            <label class="fc--blue">@lang('daran::common.'.$trans)</label>
                                        </td>
                                        @can('create post')
                                            <td class="text-right">
                                                <a class="ico" href="{{ route('admin.posts.create', ['locale' => $trans, 'locale_group' => $gallery->locale_group]) }}" data-icon="F" title="@lang('daran::common.create')" data-tooltip="tooltip"></a>
                                                <a class="ico" href="{{ route('admin.posts.clone', ['id' => $gallery->id,'locale' => $trans, 'locale_group' => $gallery->locale_group]) }}" data-icon="&#120;" title="@lang('daran::common.duplicate')" data-tooltip="tooltip"></a>
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
