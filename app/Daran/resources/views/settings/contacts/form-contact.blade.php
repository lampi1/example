<div class="row">
    <div class="col-6">
        <div class="mb-3">
            <label class="control-label">@lang('daran::setting.business-name')</label>
            <input type="text" name="business_name" required maxlength="255" placeholder="@lang('daran::setting.business-name')" value="{{old('business_name',$contact->business_name)}}" />
        </div>
        <div class="mb-3">
            <label class="control-label">@lang('daran::setting.phone')</label>
            <input type="text" name="phone" required maxlength="255" placeholder="@lang('daran::setting.phone')" value="{{old('phone',$contact->phone)}}" />
        </div>
        <div class="mb-3">
            <label class="control-label">@lang('daran::setting.fax')</label>
            <input type="text" name="fax" placeholder="@lang('daran::setting.fax')" value="{{old('fax',$contact->fax)}}" />
        </div>
        <div class="mb-3">
            <label class="control-label">@lang('daran::setting.free-number')</label>
            <input type="text" name="free_number" placeholder="@lang('daran::setting.free-number')" value="{{old('free_number',$contact->free_number)}}" />
        </div>
        <div class="mb-3">
            <label class="control-label">@lang('daran::setting.google-map-api-key')</label>
            <input type="text" name="google_map_api_key" placeholder="@lang('daran::setting.google-map-api-key')" value="{{old('google_map_api_key',$contact->google_map_api_key)}}" />
        </div>
        <div class="mb-3">
            <label class="control-label">@lang('daran::setting.google-map-address')</label>
            <input type="text" name="google_map_address" placeholder="@lang('daran::setting.google-map-address')" value="{{old('google_map_address',$contact->google_map_address)}}" />
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="mb-3">
            <label class="control-label">@lang('daran::setting.email')</label>
            <input type="email" name="email" required maxlength="255" placeholder="@lang('daran::setting.email')" value="{{old('email',$contact->email)}}" />
        </div>
        <div class="mb-3">
            <label class="control-label">@lang('daran::setting.mobile')</label>
            <input type="text" name="mobile" placeholder="@lang('daran::setting.mobile')" value="{{old('mobile',$contact->mobile)}}" />
        </div>
        <div class="mb-3">
            <label class="control-label">@lang('daran::setting.whatsapp')</label>
            <input type="text" name="whatsapp" placeholder="@lang('daran::setting.whatsapp')" value="{{old('whatsapp',$contact->whatsapp)}}" />
        </div>
        <div class="mb-3">
            <label class="control-label">@lang('daran::setting.piva')</label>
            <input type="text" name="piva" placeholder="@lang('daran::setting.piva')" value="{{old('piva',$contact->piva)}}" />
        </div>
    </div>
</div>
