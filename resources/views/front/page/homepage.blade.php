@extends('front.layouts.app')

@section('content')

<section class="fullpage-intro" style="background-image:url({{asset('images/front/pages/homepage/bg.png')}})">
    <div class="absolute z-10 h-full flex items-center justify-center top-0 text-white">
        <div class="w-4/12 ml-50vw text-left">
            <p class="text-6xl font-bold mb-2 leading-tight">
                Lorem ipsum dolor sit amet
            </p>
            <p class="text-3xl font-light">
                Lorem ipsum dolor sit amet
            </p>
            <button class="btn text-black bg-white font-bold flex items-center mt-20" href="#">
                <p> SCOPRI I NOSTRI PRODOTTI </p>
                <img class="ml-4" src="{{ asset('images/front/icons/header-button-arrow.svg') }}" alt="">
            </button>
        </div>
    </div>
</section>

<section id="js--show-menu" class="bg-primary flex justify-between px-64 font-semibold py-20" data-anim="">
    @for ($i=0; $i < 4; $i++) <div class=" hover:children:hidden flex flex-col items-center text-white">
        <img class="h-16 w-16" src="{{asset('images/front/pages/homepage/ico-'.$i.'.svg')}}">
        <p class="mt-4">MIGLIOR PREZZO</p>
        <p class="">garantito</p>
        </div>
        @endfor
</section>

{{-- data-anim fadeInUp --}}
<h3 class="text-center mt-20 font-bold text-6xl text-primary">I nostri Best Sellers</h3>

<section class="">
    <div class="glide">
        <div class="glide__track" data-glide-el="track">
            <ul class="glide__slides">
                @for ($i=0; $i < 10; $i++) <li class="glide__slide">
                    <p class="">EC-FEVERSCAN {{$i}}</p>
                    <p class="">
                        Eco-Feverscan è una colonnina intelligente, dotata di misuratore di
                        temperatura corporea a distanza. Dal design moderno, la colonnina è stata
                        realizzata in acciaio inox.
                    </p>
                    <a class="" href="#">Scopri di più</a>
                    </li>
                    @endfor
            </ul>
        </div>
        <div class="glide__arrows" data-glide-el="controls">
            <button class="glide__arrow glide__arrow--left" data-glide-dir="<"></button>
            <button class="glide__arrow glide__arrow--right" data-glide-dir=">"></button>
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