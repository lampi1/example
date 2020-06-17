
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <!-- Styles -->
    <link href="{{ asset('vendor/daran/css/Chart.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('vendor/daran/css/bootstrap-datetimepicker.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('vendor/daran/css/filepond.css')}}" rel="stylesheet">
    <link href="{{ asset('vendor/daran/css/filepond-plugin-image-preview.css')}}" rel="stylesheet">
    <link href="{{ asset('vendor/daran/css/app.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('vendor/daran/css/icons.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('vendor/daran/css/select2.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('vendor/daran/css/tippy.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('vendor/daran/css/simplebar.css')}}" rel="stylesheet" type="text/css" />
    @yield('header_styles')
</head>
<body>
    <div>
        @include('daran::layouts._messages')
        @auth
        <div id="header" class="px-5">
            <a class="navbar-brand" href="{{route('admin.dashboard')}}">
                <b>{{config('app.name')}}</b>
            </a>
            <ul class="navbar-nav ml-auto flex-row">
                <li class="nav-item dropdown mr-4">
                    <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{strtoupper(session('working_lang', Lang::getLocale()))}}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbardrop">
                        @foreach(config('app.available_translations') as $l)
                            <a class="dropdown-item" href="{{route('admin.language',['lang'=>$l])}}" title="@lang('daran::common.set-lang') @lang('daran::common.'.$l)">{{strtoupper($l)}}</a>
                        @endforeach
                    </div>
                </li>
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.login') }}">{{ __('Login') }}</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.logout') }}">{{ __('Logout') }}</a>
                    </li>
                @endguest
            </ul>
        </div>
        <main id="header-offset" class="min-vh-100 w-100">
            <div class="row no-gutters">
                <div class="col-2 px-0">
                     @include('daran::layouts._sidebar')
                </div>
                <div class="col-10 p-5">
                    <div id="content" class="w-100 p-4" style="display:none">
                        @yield('content')
                    </div>
                </div>
            </div>
        @else
            <main class="min-vh-100 w-100">
                <div class="row no-gutters">
                    <div class="col-12">
                        <div id="content" class="w-100" style="display:none">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </main>
            @endauth
        </main>
    </div>
    @section('footer_scripts')

        <script src="{{ asset('vendor/daran/js/jquery-3.3.1.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('vendor/daran/js/popper.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('vendor/daran/js/bootstrap.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('vendor/daran/js/select2.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('vendor/daran/js/bootstrap-datetimepicker.js') }}" type="text/javascript"></script>
        <script src="{{ asset('vendor/daran/js/bootstrap-datetimepicker.it.js') }}" type="text/javascript"></script>
        <script src="{{ asset('vendor/daran/js/tippy.min.js') }}" type="text/javascript"></script>
        <!-- momentaneamente non utilizzato -->
        {{-- <script src="{{ asset('vendor/daran/js/filepond-plugin-image-exif-orientation.js') }}" type="text/javascript"></script>
        <script src="{{ asset('vendor/daran/js/filepond-plugin-image-preview.js') }}" type="text/javascript"></script>
        <script src="{{ asset('vendor/daran/js/filepond-plugin-file-encode.js') }}" type="text/javascript"></script>
        <script src="{{ asset('vendor/daran/js/filepond.js') }}" type="text/javascript"></script> --}}
        <script src="{{ asset('vendor/daran/js/bootbox.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('vendor/daran/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('vendor/daran/js/jquery-ui.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('vendor/daran/js/Chart.min.js') }}"></script>
        <script src="{{ asset('vendor/daran/js/app.js') }}" type="text/javascript"></script>




    <script type="text/javascript">
    var error_attach = "@lang('admin/common.attachment-error')";
    var draft_state = "{{trans('admin/modal.state.draft_state')}}";
    var public_state = "{{trans('admin/modal.state.public_state')}}";
    var _isDirty = false;

    tok = "{{csrf_token()}}";

    var attachment_row = '<tr>';
        attachment_row += '<td style="width:60%;" class="title-attachment">'
        attachment_row += '<input type="text" name="attachment_title[]" class="form-control input-lg" value="" />'
        attachment_row += '</td>';
        attachment_row += '<td>';
        attachment_row += '<span class="attachment-upload-new">';
        attachment_row += '<input id="loadNewAttachment" type="file" name="attachment_file[]" class="attachment-file" value="" / >';
        attachment_row += '</span>';
        attachment_row += '</td>';
        attachment_row += '</tr>';

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function () {
            $('#content').fadeIn('fast');
        });
    </script>

    @if ( session('message') || session('success') || session('error') )
        <script>
            setTimeout(function () {
                $(".alert").fadeOut();
            }, 4000);
        </script>
    @endif

    @show

</body>
</html>
