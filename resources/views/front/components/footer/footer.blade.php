<footer class="flex justify-between bg-primary text-secondary text py-10 px-20">
    <div class="flex flex-col space-y-10">
        <a class="" href="">Chi siamo</a>
        <a href="">Cosa facciamo</a>
        <a href="">Prodotti</a>
        <a href="">Contatti</a>
    </div>
    <div class="flex flex-col space-y-10">
        <a href="">FAQ</a>
        <a href="">Return & Service policy</a>
        <a href="">Privacy Policy</a>
        <a href="">Terms and Conditions</a>
    </div>
    <div class="flex flex-col justify-between">
        <a href=""><img height="15" width="15" src="{{ asset('images/front/icons/socials/facebook-white.svg') }}"
                alt=""></a>
        <a href=""><img height="15" width="15" src="{{ asset('images/front/icons/socials/instagram-white.svg') }}"
                alt=""></a>
        <a href=""><img height="15" width="15" src="{{ asset('images/front/icons/socials/twitter-white.svg') }}"
                alt=""></a>
        <a href=""><img height="15" width="15" src="{{ asset('images/front/icons/socials/linkedin-white.svg') }}"
                alt=""></a>
    </div>
    <div class="text-white">
        <div class="flex ">
            <div class="flex items-start">
                <img src="{{ asset('images/front/icons/phone-footer.svg') }}" alt="">
                <div class="ml-2">333 2074793</div>
            </div>
            <div class="flex items-start ml-10">
                <img src="{{ asset('images/front/icons/footer-map.svg') }}" alt="">
                <div class="ml-2">
                    <div>via Staffali 11</div>
                    <div>Dossobuono (vr)</div>
                </div>
            </div>
        </div>
        <div class="mt-16 font-bold">SUBSCRIBE TO NEWSLETTER!</div>
        <p class="font-light">for more info</p>
        <input placeholder="E-mail" class="px-2 h-8 mt-10 bg-transparent border border-white" type="text"><button
            class="mt-8 h-8 px-4 font-light bg-white text-primary">SUBSCRIBE</button>
    </div>
</footer>

@section('footerScripts')
@parent
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