{{-- <a href="{{route('home')}}" class="logo-box">
<img src="{{asset('images/front/logo.svg')}}" alt="Logo" width="140" height="67">
</a> --}}
{{-- <div id="js--hamburger" class="hamburger">
    <svg viewBox="0 0 290 290">
        <line fill-rule="nonzero" fill="none" stroke-width="15" stroke="#ffffff" x1="217.97" y1="104.82" x2="65.58" y2="104.82"/>
        <line fill-rule="nonzero" fill="none" stroke-width="15" stroke="#ffffff" x1="217.97" y1="141.67" x2="65.58" y2="141.67"/>
        <line fill-rule="nonzero" fill="none" stroke-width="15" stroke="#ffffff" x1="217.97" y1="178.52" x2="141.78" y2="178.52"/>
    </svg>
</div> --}}
<div id="js--menubar" class="h-28 absolute z-10 bg-white w-full flex items-center justify-between pl-16 pr-24">
    <a href="{{route('home')}}" class="">
        <img class="block" src="{{asset('images/front/logo.svg')}}" alt="Logo" width="140" height="67">
    </a>
    <div class="hidden lg:flex w-9/12 justify-between">
        {{-- <a href="{{route('pages.view',['permalink'=>trans('routes.permalinks.contacts')])}}" class="button">
        @lang('menu.contact_us')
        </a>
        <a data-scroll-to="divisions" href="{{route('home')}}/#divisions" class="button">
            @lang('menu.divisions')
        </a>
        <a href="{{route('projects.index')}}" class="button">
            @lang('menu.all_works')
        </a> --}}

        <a href="#" class="">
            Chi siamo
        </a>
        <a href="#" class="">
            Cosa facciamo
        </a>
        <a href="#" class="">
            Prodotti
        </a>
        <a href="#" class="">
            Contatti
        </a>
        <div class="flex justify-between w-1/12"><img width="20" height="20"
                src="{{ asset('images/front/icons/menu-user.svg') }}" alt="">
            <img width="20" height="20" src="{{ asset('images/front/icons/menu-search.svg') }}" alt=""></div>
    </div>
</div>
{{-- <div id="js--menu" class="menu">
    <div class="menu__content">
        <div id="js--sphere" class="sphere"></div>
        <div id="js--menu-open" class="menu__content__open">
            <div class="links">
                <p class="title afterLink mt-2 mt-lg-0">@lang('menu.title-1')</p>
                <a href="{{route('pages.view',['permalink'=>trans('routes.permalinks.promotion-marketing')])}}"
class="link @if(LaravelLocalization::getLocalizedURL() ==
route('pages.view',['permalink'=>trans('routes.permalinks.promotion-marketing')])) active @endif">
@lang('menu.promotion-marketing')
</a>
<a href="{{route('pages.view',['permalink'=>trans('routes.permalinks.events')])}}"
    class="link @if(LaravelLocalization::getLocalizedURL() == route('pages.view',['permalink'=>trans('routes.permalinks.events')])) active @endif">
    @lang('menu.events')
</a>
<a href="{{route('pages.view',['permalink'=>trans('routes.permalinks.packaging')])}}"
    class="link @if(LaravelLocalization::getLocalizedURL() == route('pages.view',['permalink'=>trans('routes.permalinks.packaging')])) active @endif">
    @lang('menu.comunication-packaging')
</a>
<a href="{{route('pages.view',['permalink'=>trans('routes.permalinks.incentive')])}}"
    class="link @if(LaravelLocalization::getLocalizedURL() == route('pages.view',['permalink'=>trans('routes.permalinks.incentive')])) active @endif">
    @lang('menu.incentive')
</a>
<hr class="afterLink">
<a href="{{route('projects.index')}}"
    class="link @if(LaravelLocalization::getLocalizedURL() == route('projects.index')) active @endif">
    @lang('menu.works')
</a>
<a href="{{route('pages.view',['permalink'=>trans('routes.permalinks.about')])}}"
    class="link @if(LaravelLocalization::getLocalizedURL() == route('pages.view',['permalink'=>trans('routes.permalinks.about')])) active @endif">
    @lang('menu.company')
</a>
<a href="{{route('news.index')}}"
    class="link @if(LaravelLocalization::getLocalizedURL() == route('news.index')) active @endif">
    @lang('menu.press')
</a>
<a href="{{route('pages.view',['permalink'=>trans('routes.permalinks.contacts')])}}"
    class="link @if(LaravelLocalization::getLocalizedURL() == route('pages.view',['permalink'=>trans('routes.permalinks.contacts')])) active @endif">
    @lang('menu.contacts')
</a>
</div>
<div class="menu__section-2">
    <div class="menu__socials">
        @foreach ($socials as $social)
        <a href="{{$social->social_url}}" target="_blank">
            <img src="{{asset('images/front/icons/socials/'.$social->social_name.'-white.svg')}}" width="20px"
                height="20px">
        </a>
        @endforeach
    </div>
    <div class="menu__contacts">
        <p class="text--sm fc--orange fw--bold">@lang('menu.contact_us')</p>
        <a class="text--sm" href="mailto:{{$contacts->email}}">{{$contacts->email}}</a>
        <a class="text--sm" href="tel:045616061">{{$contacts->phone}}</a>
    </div>
</div>
</div>
</div>
</div> --}}

@section('footerScripts')
@parent
{{-- <script type="text/javascript">
    let menu = document.getElementById('js--menu');
    // let menuContentRects = document.getElementsByClassName('menu__content__rect');
    let menuOpen = document.getElementById('js--menu-open');
    let menuBtn = document.getElementById('js--hamburger');
    let hbLine1 = menuBtn.getElementsByTagName('line')[0];
    let hbLine2 = menuBtn.getElementsByTagName('line')[1];
    let hbLine3 = menuBtn.getElementsByTagName('line')[2];
    let menuLinks = menuOpen.getElementsByClassName('link');
    let afterLink = menuOpen.getElementsByClassName('afterLink');
    let section2 = menuOpen.getElementsByClassName('menu__section-2')[0];
    let socials = document.getElementsByClassName('menu__socials')[0];
    let langs = document.getElementsByClassName('menu__langs')[0];
    let sphere = document.getElementById('js--sphere');
    let size = Math.sqrt(window.innerWidth * window.innerWidth + window.innerHeight * window.innerHeight) * 2.5;
    let canHoverAnimate = true;
    let menuTl = new TimelineMax({paused: true, reversed:true});
    let hamburgerHoverTl = new TimelineMax({paused: true});

    //animazione hamburger x click
    menuTl.addLabel('start')
    .to(hbLine1, .35, {attr:{ x2:65.58}, ease:Expo.easeInOut},'start')
    .to(hbLine2, .35, {autoAlpha:0, ease:Expo.easeInOut},'start')
    .addLabel('middle')
    .to(hbLine1, .35, {attr:{x1:195.65, y1:195.55 ,x2:87.9 ,y2:87.9, stroke:'#ffffff'}, ease:Expo.easeInOut}, 'middle')
    .to(hbLine3, .35, {attr:{x1:195.65, y1:87.74 ,x2:87.74 ,y2:195.65, stroke:'#ffffff'}, ease:Expo.easeInOut}, 'middle')
    .to(hbLine2, .35, {stroke:'#ffffff', ease:Expo.easeInOut}, 'middle')

    menuTl.to('html', 0, {overflow:'hidden'})
    .to(sphere, 1.25,{    width: size,height: size, ease: Expo.easeInOut})
    .to(menuOpen, .75, {autoAlpha:1, pointerEvents:'auto', ease: Expo.easeInOut},'-=2')
    .staggerFromTo(menuLinks, .75,{duration:1, autoAlpha:0},{autoAlpha:1, ease:Expo.easeInOut}, .05)
    .fromTo(afterLink,.7,{duration:1, autoAlpha:0},{autoAlpha:1, ease:Expo.easeInOut}, '-=1')
    .fromTo(section2, .3, {autoAlpha:0, ease:Expo.easeInOut, },{autoAlpha:1, ease:Expo.easeInOut, clearProps:'all'}, '-=.3')
    menuTl.set(menuLinks, {clearProps: 'all'});

    //animazione hamburger x hover
    hamburgerHoverTl.addLabel('start')
    .to(hbLine1, .4, {attr:{ x2:141.78}, ease:Expo.out}, 'start')
    .to(hbLine3, .4, {attr:{ x2:65.58}, ease:Expo.out}, 'start');

    //bind events
    menuBtn.addEventListener('click', () => {
        if (menuTl.reversed()) {
            canHoverAnimate = false;
            menuTl.play();
        } else {
            menuTl.reverse().eventCallback('onReverseComplete', function () {
                canHoverAnimate = true;
            });
        }
    });
    menuBtn.addEventListener('mouseover', () => {
        if (canHoverAnimate) {
            hamburgerHoverTl.play();
        }
     });
     menuBtn.addEventListener('mouseout', () => {
         if (canHoverAnimate) {
            hamburgerHoverTl.reverse();
         }
      });
    </script> --}}

{{-- <script type="text/javascript">
        var logoAnim = bodymovin.loadAnimation({
          container: document.getElementById('logo-lottie'), // Required
          path: '{{asset('images/front/logo_anim.json')}}', // Required
renderer: 'svg', // Required
loop: true, // Optional
autoplay: false, // Optional
// name: "logo", // Name for future reference. Optional.
filterSize: {
width: '100%',
height: '100%'
}
});
document.addEventListener("DOMContentLoaded", function() {
logoAnim.play();
});
</script> --}}
@endsection