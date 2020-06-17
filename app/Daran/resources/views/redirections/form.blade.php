<div id="form" class="row mb-3">
    <!-- LEFT -->
    <div class="col-6">
        <div class="mb-3">
            <label class="control-label">@lang('daran::common.name')*</label>
            <input type="text" name="name"required="required" maxlength="255" placeholder="@lang('daran::common.name')" value="{{old('title',$redirection->name)}}" />
        </div>
        <div class="form-group">
            <label class="control-label">@lang('daran::redirection.from-uri')*</label><br>
            <span>@lang('daran::redirection.uri-description')</span>
            <input type="text" name="from_uri" required="required" maxlength="255" placeholder="@lang('daran::redirection.from-uri')" value="{{old('from_uri',$redirection->from_uri)}}" />
        </div>
    </div>
    <div class="col-6">
        <div class="mb-3">
            <label class="control-label">@lang('daran::redirection.code')*</label>
            <select name="code" class="select2" required="required">
                <option value="301" {{(301 == old('code',$redirection->code) ? "selected='selected'":"")}}>301</option>
                <option value="302" {{(302 == old('code',$redirection->code) ? "selected='selected'":"")}}>302</option>
            </select>
        </div>
        <div class="form-group">
            <label class="control-label">@lang('daran::redirection.to-uri')*</label><br>
            <span>@lang('daran::redirection.uri-description')</span>
            <input type="text" name="to_uri" required="required" maxlength="255" placeholder="@lang('daran::redirection.to-uri')" value="{{old('to_uri',$redirection->to_uri)}}" />
        </div>
    </div>
</div>
