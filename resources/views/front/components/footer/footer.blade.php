<footer class="footer container-fluid bg--blue py-5">
        <div class="row px-2 px-lg-5">
            {{-- <div class="col-lg-3 col-6 text-left mb-4 mb-lg-0"> --}}
                {{-- <div id="js--logo-lottie-footer" class="footer__logo">
                </div>
                <div class="row mt-3 footer-ml">
                    <div class="col-5 d-flex align-items-end">
                        <p class="text--xs fc--white text-uppercase">@lang('footer.underlogo')</p>
                    </div>
                    <div class="col-7">
                        <img class="d-block w-100" src="{{asset('images/front/logo-una.svg')}}" alt="">
                    </div>
                    <div class="col-12 mt-3">
                        <img class="d-block w-100" src="{{asset('images/front/logo-conf.png')}}" alt="">
                    </div>
                </div> --}}
            {{-- </div> --}}
            <div class="col-lg-4 col-6 mt-3 text-center mt-lg-1 mb-4">
                <div class="d-flex align-items-center mb-lg-3 mb-1">
                    <p class="text-left fc--white text--sm font--2">
                        {{$contacts->google_map_address}}
                    </p>
                </div>
                <div class="d-flex align-items-center mb-1">
                    <p class="text-left fc--white text--sm font--2">
                        <a href="tel:{{$contacts->phone}}" class="fc--white">{{$contacts->phone}}</a>
                    </p>
                </div>
                <div class="d-flex align-items-center mb-0">
                    <p class="text-left fc--white text--sm font--2">
                        <a href="mailto:{{$contacts->email}}" class="fc--white">{{$contacts->email}}</a>
                    </p>
                </div>
            </div>
            <div class="col-lg-4 text-center mt-lg-1 mb-4">
                <div class="d-flex justify-content-center mt-0">
                    <a class="footer__link text--sm fc--white" href="{{route('pages.view',['permalink'=>trans('routes.permalinks.privacy-policy')])}}">Cookie & Privacy policy</a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="d-block position-relative w-100">
                    <p class="fc--white font--2 text--md fw--bold d-block mb-0">
                        {!!__('footer.title')!!}
                    </p>
                    <p class="fc--white font--2 text--sm d-block mb-4">
                        {!!__('footer.subtitle')!!}
                    </p>
                    <form id="newsletter-subscribe" class="footer__newsletter-form form-template-1" action="{{route('newsletter.subscribe')}}" method="post">
                        <div class="w-100">
                            <div class="col-12 px-0 d-flex w-100 position-relative">
                                <div id="js--newsletter-message" class="newsetter-message text-center">
                                    <p class="text--md fc--white">
                                        <!-- js -->
                                    </p>
                                </div>
                                <input id="js--newsletter-email" class="text--sm" type="email" name="email" placeholder="Email">
                                <button class="btn  btn-primary text--sm" type="submit">{!!__('footer.subscribe')!!}</button>
                            </div>
                            <div class="col-12 px-0 mt-2">
                                <div class="input-checkbox">
                                    <input placeholder="@lang('footer.placeholder')" class="input-checkbox__hidden" name="privacy" id="privacy_footer" type="checkbox" value="1" hidden/>
                                    <label class="input-checkbox__icon" for="privacy_footer">
                                        <span>
                                            <svg width="10" height="8" viewbox="0 0 11 9">
                                                <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                            </svg>
                                        </span>
                                    </label>
                                    <p class="input-checkbox__text text--sm fc--white">
                                        {!! __('footer.info_privacy',['url'=>route('pages.view',['permalink'=>trans('routes.permalinks.policy')])])!!}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</footer>

@section('footerScripts')
    @parent
    {{-- <script type="text/javascript">
        var logoFooter = bodymovin.loadAnimation({
            container: document.getElementById('js--logo-lottie-footer'), // Required
            path: '{{asset('images/front/logo_anim.json')}}', // Required
            renderer: 'svg', // Required
            loop: true, // Optional
            autoplay: false, // Optional
            filterSize: {
                width: '100%',
                height: '100%'
            }
        })
        document.addEventListener("DOMContentLoaded", function() {
            logoFooter.play();
            // console.log('DOM CONTENT LOADED');
        });
    </script> --}}


    <script type="text/javascript">
    $("#newsletter-subscribe").validate({
        ignore: "",
        rules: {
            email: "required",
            privacy: "required",
        },
        submitHandler: function(form) {
            // form.submit();


            let toSend = {};
            // toSend.name = $('#contact-form input[name=name]').val();
            // toSend.surname = $('#contact-form input[name=surname]').val();
            toSend.email = $('#newsletter-subscribe input[name=email]').val();
            // toSend.message = $('#contact-form textarea[name=message]').val();
            toSend._token = '{{ csrf_token() }}';
            let mexBox = document.getElementById('js--newsletter-message');
            let txt = mexBox.getElementsByTagName('p')[0];
            $.ajax({
                url: "{{route('newsletter.subscribe') }}",
                type: "POST",
                data: toSend,
                dataType: "json",
                success: function(data, status){
                    console.log(data);
                    if (data.status == 'ok') {
                        txt.innerText = data.message;
                        gsap.to(mexBox,.3,{autoAlpha:1});
                        setTimeout(function() {
                            gsap.to(mexBox,.3,{autoAlpha:0});
                        }, 4500);
                    }


                    $('#newsletter-subscribe input').val('');
                },
                error: function(xhr, status, error){
                    var errori = JSON.parse(xhr.responseText);
                    var string = "";
                    $.each(errori, function(key, value){
                        string+=(value + "\n");
                    });
                    console.log(string);
                }
            });



        },
        errorPlacement: function(error, element) {
          if (element.attr("type") == "checkbox") {
              element.parent('.input-checkbox').find('.input-checkbox__icon span:first-child').addClass('error');
              element.parent('.input-checkbox').find('.input-checkbox__text').addClass('error');
          }
        }
        });
    </script>
@endsection
