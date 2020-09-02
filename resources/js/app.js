// gsap.registerPlugin(ScrollToPlugin);

/* scrollo all'ID nel data-scroll-to (x HOMEPAGE) */
let scrollsTo = document.querySelectorAll("[data-scroll-to]");
let menuHeight = 100;

scrollsTo.forEach(function(el, i) {
	let dataScr = el.getAttribute("data-scroll-to");
	scrollTo = "#" + dataScr;
	// console.log(scrollTo);
	let find = document.getElementById(dataScr);
	// console.log(find);

	console.log("click");
	if (scrollTo && find) {
		el.addEventListener("click", (e) => {
			// console.log('click');
			// console.log(scrollTo);
			// console.log(find);
			e.preventDefault();
			// gsap.to(window, {duration: .75, to: {y:find
			//     // , offsetY:menuHeight
			// }});
			$("html,body").animate(
				{
					scrollTop: $(find).offset().top - 100,
				},
				"2000"
			);
		});
	}
});

//select2
$(".select2").select2({ minimumResultsForSearch: -1 });

let controller = new ScrollMagic.Controller();

//menu
let menu = document.getElementById("js--menubar");
// let hamburger = document.getElementById('js--hamburger');
let logo = document.getElementById("logo-lottie");

// let hbLine1 = hamburger.getElementsByTagName('line')[0];
// let hbLine2 = hamburger.getElementsByTagName('line')[1];
// let hbLine3 = hamburger.getElementsByTagName('line')[2];

// console.log(hbLine3);

let tl = new TimelineMax({ paused: true });
tl.addLabel("start");
// tl.to(hbLine1, 0.01, {stroke:'#ffffff'}, 'start');
// tl.to(hbLine2, 0.01, {stroke:'#ffffff'}, 'start');
// tl.to(hbLine3, 0.01, {stroke:'#ffffff'}, 'start');

tl.to(menu, 0.01, { position: "fixed", top: "-100px" }, "start");
// tl.to(hamburger, 0.01, {position:'fixed', top:'-100px'}, 'start');
tl.to(logo, 0.01, { position: "fixed" }, "start");

tl.addLabel("middle");
tl.to(menu, 0.3, { top: "0px" }, " middle");
// tl.to(hamburger, 0.3, {top:'25px'}, 'middle');

menu.timeline = tl;

// script animate menu
let menuTrigger = document.getElementById("js--show-menu");

let menuAnimPlayed = false;
let scene = new ScrollMagic.Scene({
	triggerElement: menuTrigger,
	// offset: -120
	triggerHook: 0,
	reverse: true,
})
	.on("start", function() {
		// console.log('play');
		menu.timeline.play();
	})
	.on("leave", function() {
		// console.log('reverse');
		menu.timeline.reverse();
	})
	// .addIndicators({
	//     colorTrigger: "red",
	//     colorStart: "black",
	//     colorEnd: "black",
	//     indent: 10
	// })
	.addTo(controller);

//script generico animate
window.startSMAnimation = () => {
	let animatedItems = $("[data-anim]");
	$(animatedItems).each(function(i, e) {
		let delay = 0;
		// console.log(e);
		// if ($(e).data('anim-delay')) {
		//   console.log('delay si');
		// }
		// if ($(e).data('anim-durarion')) {
		//   console.log('duration si');
		// }
		let played = false;
		let scene = new ScrollMagic.Scene({
			triggerElement: e,
			// offset: -120
			triggerHook: 0.75,
		})
			.on("start", function() {
				if (played == false) {
					// console.log('animation started');
					// console.log(e.timeline);
					e.timeline.play();
				}
				played = true;
			})
			// .addIndicators({
			//     colorTrigger: "red",
			//     colorStart: "black",
			//     colorEnd: "black",
			//     indent: 10
			// })
			.addTo(controller);
	});
};

document.addEventListener("DOMContentLoaded", function() {
	startSMAnimation();
});

//animation x hover menu box
function injectAnimations() {
	$("[data-anim]").each(function(i, e) {
		let delay = 0;
		let duration = 0.5;
		// TODO: rimuovere data anim e duration x questo progetto, gestirli direttamente in tl
		if ($(e).data("anim-delay")) {
			delay = $(e).data("anim-delay");
		}
		//DURATION
		if ($(e).data("anim-duration")) {
			duration = $(e).data("anim-duration");
		}
		//ANIMATION
		let animationType = $(e).data("anim");
		console.log(animationType);
		let tl = new TimelineMax({ paused: true });
		tl.defaultEase = Expo.easeInOut;
		//tl
		switch (animationType) {
			case "fadeInUp":
				tl.fromTo(
					e,
					duration,
					{ delay: delay, y: 10, autoAlpha: 0 },
					{ y: 0, autoAlpha: 1 }
				);
				break;

			// case 'fadeInLeft':
			//   tl.fromTo(e, duration, {delay: delay, xPercent: -100, ease:Expo.easeInOut},{xPercent: 0, ease:Expo.easeInOut});
			//   break;
			// case 'fadeInRight':
			//   tl.fromTo(e, duration, {delay: delay, xPercent: 100, ease:Expo.easeInOut},{xPercent: 0, ease:Expo.easeInOut});
			//   break;
			// case 'fadeInUp':
			//   tl.fromTo(e, duration, {delay: delay, yPercent: 100, ease:Expo.easeInOut},{yPercent: 0, ease:Expo.easeInOut});
			//   break;
			// case 'fadeInDown':
			//   tl.fromTo(e, duration, {delay: delay, yPercent: -100, ease:Expo.easeInOut},{yPercent: 0, ease:Expo.easeInOut});
			//   break;
		}

		e.timeline = tl;
	});
}

injectAnimations();

//news box preview
// function animateNewsBox(elements){
//     $(elements).each(function(i,e){
//         let tl = new TimelineMax({paused: true});
//         tl.addLabel("start")
//         .fromTo(e, .75, {translateY: '-5vh', autoAlpha:0, ease:Expo.easeInOut}, {translateY: '0vh', autoAlpha:1, ease:Expo.easeInOut})
//         e.timeline = tl;
//     });
// };
// let newsBoxes = $('.js--box-news');
// animateNewsBox(newsBoxes);
