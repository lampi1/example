<section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 orizzontal-offset">
                <div class="row">
                    <div class="col-12">
                        <p class="text--xl fc--black fw--bold">
                            {!! __('section.clients.title-1') !!}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-12 p-lg-5">
                <div class="row">
                    <div class="col-12 px-lg-5 pb-5">
                        @php
                            $cl = $clients->gallery_medias->split(3);
                        @endphp
                        <div id="js--clients-1" class="glide slider-clients">
                            <div class="glide__track" data-glide-el="track">
                                <ul class="glide__slides">
                                    @foreach ($cl[0] as $client)
                                        <li class="glide__slide">
                                            <div class="client-box py-4">
                                                <img src="{{$client->image}}" class="d-block w-100">
                                        </div>
                                       </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div id="js--clients-2" class="glide slider-clients">
                            <div class="glide__track" data-glide-el="track">
                                <ul class="glide__slides">
                                    @foreach ($cl[1] as $client)
                                        <li class="glide__slide">
                                            <div class="client-box py-4">
                                                <img src="{{$client->image}}" class="d-block w-100">
                                            </div>
                                       </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="glide__arrows" data-glide-el="controls">
                                <button class="glide__arrow glide__arrow--left" data-glide-dir="<"></button>
                                <button class="glide__arrow glide__arrow--right" data-glide-dir=">"></button>
                            </div>
                        </div>
                        <div id="js--clients-3" class="glide slider-clients">
                            <div class="glide__track" data-glide-el="track">
                                <ul class="glide__slides">
                                    @foreach ($cl[2] as $client)
                                        <li class="glide__slide">
                                            <div class="client-box py-4">
                                                <img src="{{$client->image}}" class="d-block w-100">
                                            </div>
                                       </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</section>

@section('footerScripts')
    @parent
    <script type="text/javascript">
    var clientsSlider = new Glide('#js--clients-2', {
        type: 'carousel',
        startAt: 0,
        perView: 6,
        gap: 50,
        autoplay: 0,
        mode: 'vertical',
        breakpoints: {
            768: {
                perView: 3,
                startAt: 0,
                gap: 0,
            }
        }
        }).mount();

        var clientsSliderTwo = new Glide('#js--clients-1', {
            type: 'carousel',
            startAt: 0,
            perView: 6,
            gap: 50,
            autoplay: 0,
            mode: 'vertical',
            breakpoints: {
                768: {
                    perView: 3,
                    startAt: 0,
                    gap: 0,
                }
            }
            }).mount();

            var clientsSliderThree = new Glide('#js--clients-3', {
                type: 'carousel',
                startAt: 0,
                perView: 6,
                gap: 50,
                autoplay: 0,
                mode: 'vertical',
                breakpoints: {
                    768: {
                        perView: 3,
                        startAt: 0,
                        gap: 0,
                    }
                }
                }).mount();

                clientsSlider.on('run', function(event) {
                    let index = clientsSlider.index;
                    clientsSliderTwo.go('='+index+'');
                    clientsSliderThree.go('='+index+'');
                });

    </script>

@endsection
