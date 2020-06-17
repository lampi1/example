@extends('daran::layouts.master')

@section('content')
    <div id="bg-auth" class="w-100 min-vh-100 d-flex flex-column justify-content-center align-items-center">
        <div class="row">
            <div class="col-6 offset-3 login-bg">
                <div class="card-header">{{ __('Reset Password') }}</div>
                <img class="mx-auto d-flex mb-5" src="{{asset('vendor/daran/images/logo.svg')}}" alt="logo" width="120px" height="120px">
                <form id="login-form" method="POST" action="{{ route('admin.password.update') }}" class="form-template-auth row" autocomplete="off" novalidate>
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
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
                    <div class="col-12 mb-4">
                        <input id="password-confirm" type="password" class="{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation" placeholder="Conferma Password*" required>
                        @if ($errors->has('password_confirmation'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="col-12 mb-3">
                        <button type="submit" class="btn btn-primary w-100 btn-lg">
                            CONFERMA
                        </button>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12 text-center">
                                <a class="text--sm" href="{{ route('admin.login') }}">
                                    <u>Torna alla schermata di login?</u>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @section('footer_scripts')
        @parent
        <script src="{{asset('js/jquery.validate.min.js')}}"></script>
        <script>
            $("#login-form").validate({
                ignore: "",
                rules: {
                    email: "required",
                    password: "required"
                    password_confirmation: "required"
                },
                messages: {
                    password_confirmation: "Inserisci password di conferma",
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
