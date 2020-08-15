<div id="form" class="row">
    <div class="col-8">

            <div class="mb-3">
                <label class="control-label">@lang('daran::common.name')</label>
                <input type="text" maxlength="255" name="name"  required="required" placeholder="@lang('daran::common.name')" value="{{old('name',$coupon->name)}}" />
            </div>
            <div class="mb-3">
                <label class="control-label">@lang('daran::common.code')</label>
                <input type="text" maxlength="255" name="code" id="code" placeholder="@lang('daran::common.code')" value="{{old('code',$coupon->code)}}" />
            </div>

            <div class="mb-3">
                <label class="control-label">@lang('daran::coupon.user_id')</label>
                <select name="user_id" id="user_id" class="select2 form-control input-lg">
                    <option value="">Tutti</option>
                    @foreach($users as $fam)
                        <option value="{{$fam->id}}" @if(old('user_id',$coupon->user_id) == $fam->id) selected="selected" @endif>{{$fam->surname.' '.$fam->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="control-label">@lang('daran::coupon.family_id')</label>
                <select name="family_id" id="family_id" class="select2 form-control input-lg">
                    <option value="">@lang('daran::common.select')</option>
                    @foreach($families as $fam)
                        <option value="{{$fam->id}}" @if(old('family_id',$coupon->family_id) == $fam->id) selected="selected" @endif>{{$fam->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="control-label">@lang('daran::coupon.category_id')</label>
                <select name="category_id" id="category_id" class="select2 form-control input-lg">
                    <option value="">@lang('daran::common.select')</option>
                </select>
            </div>


    </div>
    <div class="col-4">
        <div class="row">
            <div class="col-12 mb-3">
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="control-label">@lang('daran::coupon.discount')</label>
                        <input type="number" name="amount" id="amount" min="0" step="1"  value="{{old('amount',$coupon->amount)}}" />
                    </div>
                    <div class="col-12 mb-3">
                        <label class="control-label">@lang('daran::coupon.discount_perc')</label>
                        <input type="number" name="discount" id="discount" min="0" step="0.01" max="100"  value="{{old('discount',$coupon->discount)}}" />
                    </div>
                    <div class="col-12 mb-3">
                        <label class="control-label">@lang('daran::coupon.minimun')</label>
                        <input type="number" name="min" id="min" min="0" step="1"  value="{{old('min',$coupon->min)}}" />
                    </div>
                    <div class="col-12 mb-3">
                        <label class="control-label">@lang('daran::coupon.date_start')</label>
                        <input type="text" class="form_date" required="required" id="date_start" name="date_start" value="@if($coupon->date_start){{$coupon->date_start->format('d/m/Y')}}@endif" />
                    </div>
                    <div class="col-12 mb-3">
                        <label class="control-label">@lang('daran::coupon.date_end')</label>
                        <input type="text" class="form_date" name="date_end" id="date_end" value="@if($coupon->date_end){{$coupon->date_end->format('d/m/Y')}}@endif" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
