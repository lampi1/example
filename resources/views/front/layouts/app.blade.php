<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@100;300;400;500;700&display=swap"
        rel="stylesheet">

    @if ($brandings)
    <link rel="apple-touch-icon" sizes="180x180"
        href="{{ config('app.url') . $brandings->where('lang', app()->getLocale())->first()->favicon_ipad }}">
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ config('app.url') . $brandings->where('lang', app()->getLocale())->first()->favicon_iphone }}">
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ config('app.url') . $brandings->where('lang', app()->getLocale())->first()->favicon }}">
    @endif

    @if (MetaTag::get('title') != '')
    <title>{{ config('app.name') }} | {{ MetaTag::get('title') }}</title>
    @else
    <title>{{ config('app.name') }} | {{ MetaTag::get('title') }}</title>
    @endif
    {!! MetaTag::tag('description') !!}
    {!! MetaTag::openGraph() !!}
    {!! MetaTag::canonical() !!}
    {!! MetaTag::tag('robots') !!}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    {{--
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> --}}

    <!-- Styles -->
    @section('headerStyles')
    {{-- <link href="{{ asset('css/app.min.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @show

    @if (MetaTag::get('gtag_manager'))
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start': new Date().getTime(),
                    event: 'gtm.js'
                });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s),
                    dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                    'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', '{{ MetaTag::get('
                gtag_manager ') }}');

    </script>
    <!-- End Google Tag Manager -->
    @elseif(MetaTag::get('g_analytics'))
    <!-- Global Site Tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ MetaTag::get('g_analytics') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());
            gtag('config', '{{ MetaTag::get('
                g_analytics ') }}');
            gtag('config', 'AW-1001493730');

    </script>
    @endif

    @if (MetaTag::get('facebook_pixel'))
    <!-- Facebook Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function() {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
            }(window, document, 'script',
                'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ MetaTag::get('
                facebook_pixel ') }}');
            fbq('track', 'PageView');

    </script>
    <noscript>
        <img height="1" width="1" src="https://www.facebook.com/tr?id={{ MetaTag::get('facebook_pixel') }}&ev=PageView
            &noscript=1" />
    </noscript>
    <!-- End Facebook Pixel Code -->
    @endif

</head>

<body class="debug-screens font-medium">
    @if (MetaTag::get('gtag_manager'))
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ MetaTag::get('gtag_manager') }}" height="0"
            width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    @endif

    <!-- ROUTEX VUE ZIGGY -->
    @routes
    @include('front.components.header.header')
    <main class="">
        @yield('content')
    </main>
    @include('front.components.footer.footer')
    {{-- @include('front.components.mouse.mouse') --}}
</body>
@section('footerScripts')

<script type="text/javascript">
    // breakpoint scripts js
        window.mobileBreakpoint = 991;
        window.isMobile = () => {
            if (screen.width < window.mobileBreakpoint) {
                return true;
            } else {
                return false;
            }
        };

</script>

<script type="text/javascript" src="{{ asset('js/jquery-1.9.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/ScrollToPlugin.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/gsap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/RoundPropsPlugin.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/SplitText.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/glide.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/ScrollMagic.min.js') }}"></script>
{{-- <script type="text/javascript" src="{{ asset('js/debug.addIndicators.min.js') }}">
</script> --}}
<script type="text/javascript" src="{{ asset('js/select2.full.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/lottie.js') }}"></script>
<script type="module" src="{{ asset('js/app.js') }}"></script>
{{-- <script type="module" src="{{ asset('js/vue.js') }}"></script>
--}}
@show

</html>