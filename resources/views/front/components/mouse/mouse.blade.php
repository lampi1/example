<div id="cursor">
	<svg viewBox="0 0 109.31 109.31">
	    <path class="arrow-left" d="M59.5,48.66l-6,6,6,6L57.66,62.5l-7.85-7.85,7.85-7.85Z" fill="#6882b7"/>
	    <path class="arrow-right" d="M49.81,60.65l6-6-6-6,1.84-1.84,7.85,7.85-7.85,7.85Z" fill="#6882b7"/>
	    <circle class="circle-in" cx="54.65" cy="54.66" r="5.32" fill="#6882b7"/>
	</svg>
</div>
<div id="circle">
	<svg viewBox="0 0 109.31 109.31">
		<circle class="circle-out" cx="54.66" cy="54.66" r="42.66" fill="none" stroke="#6882b7" stroke-miterlimit="10"/>
	</svg>
</div>

@section('footerScripts')
    @parent
	<script type="text/javascript">
		if (!isMobile()) {
			var cursor = document.getElementById("cursor");
			let svg = cursor.getElementsByTagName('svg')[0];
			let circleIn = svg.getElementsByClassName('circle-in')[0];
			let circleOut = svg.getElementsByClassName('circle-out')[0];
			let arrowLeft = svg.getElementsByClassName('arrow-left')[0];
			let arrowRight = svg.getElementsByClassName('arrow-right')[0];
			var circle = document.getElementById("circle");
			let circleOuter = circle.getElementsByClassName('circle-out')[0];

			function moveCircle(e) {
			    gsap.to(cursor, 0.05, {
			        css: {
			            x: e.clientX,
			            y: e.clientY
			        }
			    });

				gsap.to(circle, 0.3, {
					css: {
						x: e.clientX,
						y: e.clientY
					}
				});
			}
			var mouseState = {
			  aInternal: 'default',
			  aListener: function(val) {},
			  set a(val) {
			    this.aInternal = val;
			    this.aListener(val);
			  },
			  get a() {
			    return this.aInternal;
			  },
			  registerListener: function(listener) {
			    this.aListener = listener;
			  }
			}
			mouseState.registerListener(function(val) {
			  console.log(val);
			  if (val == 'default') {

				  let tl = gsap.timeline();
				  tl.set(arrowLeft, {opacity:0})
				  .addLabel('start')
				  tl.to(arrowLeft, .5, {opacity:0},'start')
				  .to(arrowRight, .5, {opacity:0},'start')
				  .to(circleIn, .5,{opacity:1},'start')
					  .fromTo(svg, .5, {scale:4},{scale:1})
					  .to(circle, .5, {scale:4}, '-=.5')
					  .fromTo(circle, .5, {scale:4},{scale:1},'-=.5')
					  .fromTo(circleOuter, .5, {strokeWidth:2}, {strokeWidth:4}, '-.5')
					  .to(circleIn, .5, {opacity:1}, '-=1');
			  } else if (val == 'arrow-right') {
				  let tl = gsap.timeline();
				  tl.set(arrowLeft, {opacity:0});
				  tl.addLabel('first')
				  .to(circleIn, .5, {opacity:0}, 'first')
				  .to(svg, .5, {scale:4}, 'first')
				  .to(circle, .5, {scale:4}, 'first')
				  .to(circleOuter, .5, {strokeWidth:2}, 'first')
				  .to(arrowRight, .5, {opacity:1},'-=.25');
			  } else if (val == 'arrow-left') {
				  let tl = gsap.timeline();
				  tl.set(arrowRight, {opacity:0});
				  tl.addLabel('first')
				  .to(circleIn, .5, {opacity:0}, 'first')
				  .to(svg, .5, {scale:4}, 'first')
				  .to(circle, .5, {scale:4}, 'first')
				  .to(circleOuter, .5, {strokeWidth:2}, 'first')
				  .to(arrowLeft, .5, {opacity:1},'-=.25');
			  } else if (val == 'over') {
				  tl.set(arrowRight, {opacity:0});
				  tl.set(arrowLeft, {opacity:0});
				  tl.addLabel('first')
				  .to(svg, .5, {scale:4}, 'first')
				  .to(circle, .5, {scale:4}, 'first')
				  .to(circleOuter, .5, {strokeWidth:2}, 'first')
				  .to(circleIn, .5, {opacity:1},'-=.25');
			  }
			});

			nextTl = gsap.timeline({paused:true});
			document.addEventListener('mousemove',moveCircle)

			$(".glide__arrow--right").mouseover(function(){
				mouseState.a = 'arrow-right';
			});

			$(".glide__arrow--right").mouseout(function(){
				mouseState.a = 'default';
			});

			$(".glide__arrow--left").mouseover(function(){
				mouseState.a = 'arrow-left';
			});

			$(".glide__arrow--left").mouseout(function(){
				mouseState.a = 'default';
			});

			$(".button, .hamburger").mouseover(function(){
				mouseState.a = 'over';
			});

			$(".button, .hamburger").mouseout(function(){
				mouseState.a = 'default';;
			});
		}
	</script>
@endsection
