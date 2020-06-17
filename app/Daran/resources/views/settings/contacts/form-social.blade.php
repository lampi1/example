<div class="row mb-3">
    @foreach ($socials as $social)
        <div class="col-6" id="social_{{$social->id}}">
            <div class="mb-3">
                <label class="control-label">{{ucfirst($social->social_name)}}</label>
            </div>
            <div class="mb-3">
                <a href="{{$social->social_url}}" target="_blank">{{$social->social_url}}</a>
                <a class="btn btn-danger float-right social-delete-btn" href="#" data-name="{{$social->social_url}}" data-id="{{$social->id}}">
                    @lang('daran::common.remove')
                </a>
            </div>
        </div>
    @endforeach
</div>

<div class="row">
    <div class="col-6">
        <div class="mb-3">
            <select class='select2' id="social-name" name="social_name">
                <option value="facebook">Facebook</option>
                <option value="twitter">Twitter</option>
                <option value="linkedin">Linkedin</option>
                <option value="instagram">Instagram</option>
                <option value="youtube">Youtube</option>
            </select>
        </div>
    </div>
    <div class="col-5">
        <div class="mb-3">
            <input type="text" name="social_url" id="social-url" placeholder="@lang('daran::setting.social_url')" value="{{old('social_url')}}" />
        </div>
    </div>
    <div class="col-1">
        <button type="button" id="charge-social" class="btn btn-primary float-right">@lang('daran::common.charge')</button>
    </div>
</div>
