@extends('daran::layouts.master')

@section('content')
    <div id="bg-auth" class="w-100 min-vh-100 d-flex flex-column justify-content-center align-items-center">
        <div class="row">
            <div class="col-6 offset-3 login-bg">
                <img class="mx-auto d-flex mb-5" src="{{asset('vendor/daran/images/logo.svg')}}" alt="logo" width="120px" height="120px">
                <form id="login-form" method="POST" action="{{ route('admin.login') }}" class="form-template-auth row" autocomplete="off" novalidate>
                    @csrf
                    <div class="col-12 mb-3">
                        <input id="email" type="email" class="{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" placeholder="Email*" value="{{ old('email') }}" required autofocus>
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="col-12 mb-4">
                        <input id="password" type="password" class="{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Password*" required>
                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="col-12 mb-3">
                        <button type="submit" class="btn btn-primary w-100 btn-lg">
                            Accedi
                        </button>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            {{-- <div class="col-6">
                                <div class="input-checkbox">
                                    <input class="input-checkbox__hidden" name="remember" id="remember" type="checkbox" hidden {{ old('remember') ? 'checked' : '' }}/>
                                    <label class="input-checkbox__icon" for="remember">
                                        <span>
                                            <svg width="12px" height="10px" viewbox="0 0 12 10">
                                                <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                            </svg>
                                        </span>
                                    </label>
                                    <p class="input-checkbox__text fc--white text--xs">
                                        Remember Me
                                    </p>
                                </div>
                            </div> --}}
                            <div class="col-12 text-center">
                                @if (Route::has('admin.password.request'))
                                    <a class="text--sm" href="{{ route('admin.password.request') }}">
                                        <u>Hai dimenticato la tua password?</u>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @section('footer_scripts')
        @parent
        <script src="{{ asset('vendor/daran/js/jquery.validate.min.js') }}" type="text/javascript"></script>


        <script>
            $("#login-form").validate({
                ignore: "",
                rules: {
                    email: "required",
                    password: "required"
                },
                messages: {
                    password: "Inserisci una password",
                    email: "Inserisci una mail valida",
                },
                submitHandler: function(form) {
                    form.submit();
                },
                highlight: function(element, errorClass) {
                    if ($(element).attr("type") == "checkbox") {
                        $(element).parent().addClass('error');
                    }else{
                        $(element).addClass('error');
                    }
                },
                unhighlight: function (element, errorClass) {
                    if ($(element).attr("type") == "checkbox") {
                        $(element).parent().removeClass('error');
                    }else{
                        $(element).removeClass('error');
                    }
                }
            });
        </script>
    @endsection


@endsection
