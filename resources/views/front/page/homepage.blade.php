@extends('front.layouts.app')

@section('content')

<section class="fullpage-intro" style="background-image:url({{asset('images/front/pages/homepage/bg.png')}})">
    <div class="absolute z-10 w-full h-full flex items-center justify-center top-0">
        <div class="max-w-35vw ml-50vw text-left">
            <p class="text-white text-6xl font-bold mb-4">
                Lorem ipsum dolor sit amet
            </p>
            <p class="fc--white text--lg mb-5">
                Lorem ipsum dolor sit amet
            </p>
            <a class="default-button border border-white text-white text--md" href="#">
                Scopri i nostri prodotti
            </a>
        </div>
    </div>
</section>

<section id="js--show-menu" class="bg--blue" data-anim="fadeInUp">
    <div class="container py-5">
        <div class="row py-5">
            @for ($i=0; $i < 4; $i++) <div class="col-lg-3 text-center">
                <img class="mb-4" width="auto" height="50px"
                    src="{{asset('images/front/pages/homepage/ico-'.$i.'.svg')}}">
                <p class="text--sm text-uppercase fw--bold fc--white letter-spacing--2">Miglior prezzo</p>
                <p class="text--sm fw--bold fc--white letter-spacing--2">garantito</p>
        </div>
        @endfor
    </div>
    </div>
</section>

<section data-anim="fadeInUp">
    <div class="container-fluid mb-5">
        <div class="row">
            <div class="col-12 text-center">
                <p class="text--xxl fc--blue fw--bold py-5 mt-5">
                    I nostri Best Sellers
                </p>
            </div>
            <div class="col-12 px-0 position-relative">
                <div id="js--product-slider" class="glide slider-product">
                    <div class="glide__track" data-glide-el="track">
                        <ul class="glide__slides">
                            @for ($i=2; $i < 10; $i++) <li class="glide__slide">
                                <div class="slider-product-preview">
                                    <div class="image mb-3"
                                        style="background-image:url('{{asset('images/front/wip/mascherina-2.png')}}')">
                                    </div>
                                    <p class="fc--blue fw--bold text-uppercase text--x mb-3">
                                        Mascherine ffp1
                                    </p>
                                    <p class="text--sm fc--blue mb-5">
                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                        incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
                                        nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                    </p>
                                    <a class="btn btn-outline-primary float-right d-inline-block" href="#">Acquista</a>
                                </div>
                                </li>
                                @endfor
                        </ul>
                    </div>
                    <div class="glide__arrows" data-glide-el="controls">
                        <button class="glide__arrow glide__arrow--left" data-glide-dir="<">
                            <svg viewBox="0 0 16.45 26.64">
                                <path d="M0,3.13,10.17,13.32,0,23.51l3.13,3.13L16.45,13.32,3.13,0Z" />
                            </svg>
                        </button>
                        <button class="glide__arrow glide__arrow--right" data-glide-dir=">">
                            <svg viewBox="0 0 16.45 26.64">
                                <path d="M0,3.13,10.17,13.32,0,23.51l3.13,3.13L16.45,13.32,3.13,0Z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container-fluid bg--blue position-relative">
    <div class="row">
        <div class="col-12 p-0">
            <div id="js--half-content-slider" class="glide slider-half-content">
                <div class="glide__track" data-glide-el="track">
                    <ul class="glide__slides">
                        @for ($i=0; $i < 10; $i++) <li class="glide__slide">
                            <div class="row no-gutters">
                                <div class="col-6 p-5 my-5">
                                    <div class="w-100 p-5">
                                        <p class="text--xl fw--bold fc--white mb-3">EC-FEVERSCAN {{$i}}</p>
                                        <p class="text--md fc--white mb-5">
                                            Eco-Feverscan è una colonnina intelligente, dotata di misuratore di
                                            temperatura corporea a distanza. Dal design moderno, la colonnina è stata
                                            realizzata in acciaio inox.
                                        </p>
                                        <a class="btn btn-outline-light d-inline-block" href="#">Scopri di più</a>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-full"
                                        style="background-image:url('https://via.placeholder.com/600x400')">

                                    </div>
                                </div>
                            </div>
                            </li>
                            @endfor
                    </ul>
                </div>
                <div class="glide__arrows" data-glide-el="controls">
                    <button class="glide__arrow glide__arrow--left" data-glide-dir="<"></button>
                    <button class="glide__arrow glide__arrow--right" data-glide-dir=">"></button>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="w-100">
    <div class="row no-gutters">
        <div class="col-5">
            <div class="bg-full" style="background-image:url('https://via.placeholder.com/600x400')">

            </div>
        </div>
        <div class="col-7 p-5 my-5">
            <div class="w-100 p-5">
                <p class="text--xl fw--bold fc--blue mb-3">ECO-FEVERSCAN</p>
                <p class="text--md fc--blue mb-5">
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                    labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                </p>
                <a class="btn btn-outline-primary d-inline-block" href="#">Scopri di più</a>
            </div>
        </div>
    </div>
</section>


<section class="pt-5 mt-5 mb-5" data-anim="fadeInUp">
    <div class="row">
        <div class="col-12 text-center">
            <p class="text--xxl fw--bold fc--blue mb-3">
                Lorem ipsum dolor sit amet, consetetur sadipscing
            </p>
            <p class="text--lg fw--lighter fc--blue mb-5">
                Lorem ipsum dolor sit amet, consetetur sadipscing
            </p>
            <a class="btn btn-outline-primary d-inline-block" href="#">Scopri di più</a>
        </div>
    </div>
</section>

@endsection

@section('footerScripts')
@parent

    <script type="text/javascript">
        var productSlider = new Glide('#js--product-slider', {
            type: 'carousel',
            startAt: 0,
            perView: 3,
            gap: 200,
            autoplay: 5000,
            focusAt: 'center',
            mode: 'vertical',
            breakpoints: {
                768: {
                    startAt: 0,
                    gap: 0,
                    perView: 1
                }
            }
            }).mount();

            var halfContentSlider = new Glide('#js--half-content-slider', {
                type: 'carousel',
                startAt: 0,
                perView: 1,
                gap: 0,
                autoplay: 5000,
                focusAt: 'center',
                mode: 'vertical',
                breakpoints: {
                    768: {
                        startAt: 0,
                        gap: 0,
                        perView: 1
                    }
                }
                }).mount();
    </script>
@endsection
