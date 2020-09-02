<div id="js--menubar"
    class="h-28 absolute z-10 bg-white w-full flex items-center justify-between pl-16 pr-24 py-6 text-primary">
    <a href="{{route('home')}}" class="">
        <img class="block" src="{{asset('images/front/logo.svg')}}" alt="Logo" width="140" height="67">
    </a>
    <div class="hidden lg:flex w-9/12 justify-between">
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


@section('footerScripts')
@parent

@endsection