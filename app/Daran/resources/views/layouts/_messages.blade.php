<div class="container-fluid">
    <div class="row">
        <div class="col-12 p-0">
            @if (count($errors) > 0)
                    <div class="alert alert-warning alert-dismissible w-100" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="{{asset('vendor/daran/images/icons/close-white.svg')}}"></span></button>
                @foreach ($errors->all() as $error)
                      <strong>@lang('daran::common.warning')</strong> {{ $error }}<br><br>
                @endforeach
                    </div>
            @endif
            @if (session('message'))
                <div class="alert alert-success alert-dismissible w-100" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="{{asset('vendor/daran/images/icons/close-white.svg')}}"></span></button>
                  <strong>@lang(session('message'))</strong>
                  @if (session('download_link'))
                    <a href="{{route('download',['type'=>session('download_type'),'filename'=>session('download_link')])}}">@lang('daran::common.start_download')</a>
                  @endif
                </div>
            @endif
            @if (session('error'))
                    <div class="alert alert-warning alert-dismissible w-100" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="{{asset('vendor/daran/images/icons/close-white.svg')}}"></span></button>
                    <strong>@lang(session('error'))</strong>
                    </div>
            @endif
            @if (session('success'))
                    <div class="alert alert-success alert-dismissible w-100" role="success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><img src="{{asset('vendor/daran/images/icons/close-white.svg')}}"></span></button>
                    <strong>@lang(session('success'))</strong>
                    </div>
            @endif
        </div>
    </div>
</div>
