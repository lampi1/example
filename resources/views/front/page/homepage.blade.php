@extends('front.layouts.app')

@section('content')

<!-- video hp -->
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




{{-- <script type="text/javascript">
        let changeTexts = document.getElementsByClassName('changeText');

        Array.prototype.forEach.call(changeTexts, function (e, i) {
            e.split = new SplitText(e, {type:"chars"});
            e.timeline = new gsap.timeline();
        });

        function changeMessage(index){
            Array.prototype.forEach.call(changeTexts, function (e, i) {
                let myIndex = $(e).data('index');
                if (myIndex == index) {
                    e.timeline.staggerFromTo(e.split.chars, 2,{ opacity:0,translateX:15,rotate:1.5, ease: Expo.easeInOut}, {opacity:1,translateX:0,rotate:0, ease: Expo.easeInOut}, .05, '+=1');
                } else {
                    e.timeline.staggerTo(e.split.chars, 2,{ opacity:0,translateX:15,rotate:1.5, ease: Expo.easeInOut}, .05);
                }
            });
        }
        changeMessage(0);

        // let titleSplit = new SplitText(document.getElementById('js--home-title'), {type:"chars"});
        let introTxt = document.getElementById('js--home-title');
        introTl = new gsap.timeline();
        introTl.fromTo(introTxt, 2,{ opacity:0,translateX:15,rotate:1.5, ease: Expo.easeInOut}, {opacity:1,translateX:0,rotate:0, ease: Expo.easeInOut}, '+=0');
        let msgIndex = 0;
        setInterval(() => {
            if (msgIndex == (changeTexts.length)) {
                msgIndex = 0;
            } else {
                msgIndex++;
            }
            changeMessage(msgIndex);
        },5000);

    </script> --}}

{{-- <script type="text/javascript">
        let tc = document.getElementById('team-spheres');
        let spheres = tc.getElementsByClassName('team-sphere');

        Array.prototype.forEach.call(spheres, function (e, i) {
            let tl = gsap.timeline ()
            .to(e,{
              x: "random(-200, 200)", //chooses a random number between -20 and 20 for each target, rounding to the closest 5!
              y: "random(-200, 200)",
              duration:"random(8, 12)",
              ease:"none",
              repeat:-1,
              repeatRefresh:true // gets a new random x and y value on each repeat
          });
        });
    </script> --}}

{{-- <script type="text/javascript">
         let bxs = document.getElementsByClassName('js--ib');

         Array.prototype.forEach.call(bxs, function (e, i) {
             let img = e.dataset.img;
             e.lottieAnim = bodymovin.loadAnimation({
               container: e.getElementsByClassName('lottie')[0], // Required
               path: img, // Required
               renderer: 'svg', // Required
               loop: true, // Optional
               autoplay: true, // Optional
               // name: "logo", // Name for future reference. Optional.
               filterSize: {
                   width: '100%',
                   height: '100%'
                 }
             });

             let svg = e.getElementsByClassName('lottie')[0];
             let cnt = e.getElementsByClassName('content')[0];
             let tl = new TimelineMax({paused:true});

             tl.defaultEase = Expo.easeInOut;
             tl.to(cnt, 0.001, {zIndex:2})
             .to(cnt, 1, {autoAlpha:1});
             e.tl = tl;

             svg.addEventListener('click', () => {
                 // e.lottieAnim.goToAndPlay(0);
                  e.tl.play();
             });
             cnt.addEventListener('click', () => {
                 e.tl.reverse();
             });}
         });
     </script> --}}
@endsection