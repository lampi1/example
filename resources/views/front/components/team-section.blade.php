<section class="bg--white">
    <div class="container-fluid pb-5">
        <div class="row">
            <div data-anim="fadeInUp" class="col-12 p-0">
                <div id="js--team" class="glide slider-team">
                    <div class="glide__track" data-glide-el="track">
                        <ul class="glide__slides">
                            @foreach ($members as $member)
                                <li class="glide__slide">
                                    <div class="image-box">
                                        <div class="image mb-4" style="background-image:url('{{$member->image}}')">
                                            <div class="content">
                                                <p class="text--xxl fw--bold fc--white">{{$member->name}}</p>
                                            </div>
                                        </div>
                                        <p class="px-4 text--sm fw--bold fc--orange mb-1">{{$member->job}}</p>
                                        <p class="px-4 text--sm">
                                            {{$member->content}}
                                        </p>
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
            </div>
        </div>
    </div>
</section>

@section('footerScripts')
    @parent
    <script type="text/javascript">
    var teamSlider = new Glide('#js--team', {
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
