<section class="bg--black">
    <div class="container-fluid  pb-5 pt-0 pt-lg-5 bg--black">
        <div class="row">
            <div class="col-12 p-0" data-anim="fadeInUp">
                <div id="js--company" class="glide slider-company">
                    <div class="glide__track" data-glide-el="track">
                        <ul class="glide__slides">
                            @for ($e=2; $e < 9; $e++)
                                <li class="glide__slide">
                                    <div class="image-box">
                                        <div class="image mb-3" style="background-image:url('{{asset('images/front/homepage/company_'.$e.'.jpg')}}')">
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
            <div class="col-12 orizzontal-offset">
                <p data-anim="fadeInUp" class="text--lg fc--white mt-3 mb-lg-5">
                    {!! __('page.home.text-1') !!}
                </p>
            </div>
        </div>
    </div>
</section>

@section('footerScripts')
    @parent
    <script type="text/javascript">
    var companySlider = new Glide('#js--company', {
        type: 'carousel',
        startAt: 0.5,
        perView: 2,
        gap: 100,
        autoplay: 0,
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
