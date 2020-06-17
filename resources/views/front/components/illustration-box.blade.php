<div class="illustration-box">
    <div class="js--ib box" data-img="{{asset('images/front/illustr-'.$i.'.json')}}">
        <div class="lottie">
        </div>
        <p class="text--md text-center fw--bold fc--black">
            {!! __('page.home.title-box-'.$i) !!}
        </p>
        <div class="content">
            <p class="text--lg fw--bold fc--orange mb-1">{!! __('page.home.title-box-'.$i) !!}</p>
            <p class="text--md fw--bold fc--black mb-3">{!! __('page.home.subtitle-box-'.$i) !!}</p>
            <p class="fw--light text--sm">{!! __('page.home.desc-box-'.$i) !!}</p>
            <a class="btn btn-primary fc--black mt-3" href="{{route('pages.view',['permalink'=>trans('page.home.permalink-'.$i)])}}">
                {!! __('page.home.discover') !!}
            </a>
        </div>
    </div>
</div>
