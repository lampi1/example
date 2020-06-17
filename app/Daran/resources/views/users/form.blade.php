<div id="form" class="row">
    <!-- LEFT -->
    <div class="col-8">
        <div class="mb-3">
            <label class="control-label">@lang('daran::user.name')</label>
            <input type="text" name="name" required="required" maxlength="255" placeholder="@lang('daran::user.name')" value="{{old('name',$user->name)}}" />
        </div>
        <div class="mb-3">
            <label class="control-label">@lang('daran::user.business')*</label>
            <input type="text" name="business" required="required" maxlength="255" placeholder="@lang('daran::user.business')" value="{{old('business',$user->business)}}" />
        </div>
        <div class="mb-3">
            <label class="control-label">@lang('daran::user.email')*</label>
            <input type="email" name="email" required="required" maxlength="255" placeholder="@lang('daran::user.email')" value="{{old('email',$user->email)}}" />
        </div>
        <div class="mb-3">
            <label class="control-label">@lang('daran::user.password')</label>
            <input type="text" name="password" maxlength="25" placeholder="@lang('daran::user.password')" value="{{old('password')}}" />
        </div>
        <div class="mb-3">
            <label class="control-label">@lang('daran::user.password_confirmation')</label>
            <input type="text" name="password_confirmation" maxlength="25" placeholder="@lang('daran::user.password_confirmation')" value="" />
        </div>
    </div>
    <div class="col-4">
        <div class="row">
            <div class="col-12 mb-3">
                <div class="row">

                    <div class="col-6">
                        <label for="locale" class="control-label">@lang('daran::user.locale')</label>

                        <select class="select2" required="required" id="locale" name="locale">
                            @foreach ($langs as $lang)
                                <option value="{{$lang}}" @if($user->locale == $lang) selected="selected" @endif>{{$lang}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6">
                        <label for="active" class="control-label">@lang('daran::user.active')</label>

                        <select class="select2" required="required" id="active" name="active">
                            <option value="1" @if($user->active == 1) selected="selected" @endif >Attivo</option>
                            <option value="0" @if($user->active == 0) selected="selected" @endif >NON Attivo</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mb-3">
                <div class="row">

                    <div class="col-12">
                        <label for="locale" class="control-label">Listino</label>

                        <select class="select2" id="pricelist_id" name="pricelist_id">
                            <option value="">Nessuno</option>
                            @foreach ($pricelists as $pricelist)
                                <option value="{{$pricelist->id}}" @if($user->pricelist_id == $pricelist->id) selected="selected" @endif>{{$pricelist->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
