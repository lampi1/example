// Ogni volta che modifichiamo questo file bisogna far ripartire la compilazione anche se siamo in "watch" se si utilizza l'estensione di vs code "tailwindCSS intelliSense" tutte le proprietà che aggiungiamo in questo file ci compariranno come suggeriti alla digitazione della classi.
module.exports = {
	theme: {
		//qui si può midificare la posizione dell'icona che indica il breakpoint in cui ci si trova al momento
		debugScreens: {
			position: ["bottom", "right"],
		},
		//dopo aver importato il font interessato nell' app.scss si deve aggingere qui per poterlo utilizzare nel template come classe, Eempio: class="font-medium" applichera il font Avenir Medium all' elemento
		fontFamily: {
			medium: ["Avenir Medium", "sans-serif"],
			light: ["Avenir Light", "sans-serif"],
			black: ["Avenir Black", "sans-serif"],
			book: ["Avenir Book", "sans-serif"],
		},
		screens: {
			sm: "640px",
			// => @media (min-width: 640px) { ... }

			md: "768px",
			// => @media (min-width: 768px) { ... }

			lg: "1024px",
			// => @media (min-width: 1024px) { ... }

			xl: "1280px",
			// => @media (min-width: 1280px) { ... }
		},

		/* FONTS */
		// fontSize: {
		//   xxs: '',
		//   xs: '',
		//   sm: '',
		//   md: '',
		//   lg: '',
		//   xl: '',
		//   xxl: ''
		// },

		//estensioni delle proprietà di default, come da esempio si possono aggiungere sia valori fissi che dinamici. Ma dentro questa sezione non si possono sovrascrivere i valori. Questa sezione è utilizzato solo per ESTENDERE ciò che già offre tailwind.
		extend: {
			height: {
				"25vh": "25vh",
				"screen/2": "50vh",
				"screen/3": "calc(100vh / 3)",
				"screen/4": "calc(100vh / 4)",
				"screen/5": "calc(100vh / 5)",
			},
			width: {
				"35vw": "35vw",
				"screen/2": "50vh",
				"screen/3": "calc(100vh / 3)",
				"screen/4": "calc(100vh / 4)",
				"screen/5": "calc(100vh / 5)",
			},
			maxHeight: {
				"35vh": "35vh",
				"1/2": "50%",
				"3/4": "75%",
			},
			maxWidth: {
				"35vw": "35vw",
				"1/2": "50%",
				"3/4": "75%",
			},
			// si riverisce al magin e al padding.
			spacing: {
				"50vw": "50vw",
			},
			borderWidth: {
				//il valore default si attiva senza definire un valore al border. esempio: class="border" l'elemento avrà un bordo di 1px
				// default: "1px",
				// "0": "0",
				// "2": "2px",
				// "4": "4px",
			},
			colors: {
				primary: "#1D2768",
				secondary: "#406CB0",
			},
		},
	},
	//le varianti possono estendere le pseudoclassi utilizzabili dalle nostre utility, ad esempio grazie al plug-in "tailwindcss-children" adesso possiamo usare la pseudo classe "children" per definire in un div genitore il tipo di display che devono avere i figli, assegnando la classe "children:block" nel div genitore.
	// Si possono anche concatenare le pseudoclassi con le varianti responsive ad esempio lg:hover:bg-black attiverà il bg-black solo quando entrambe le varianti saranno soddisfatte.

	variants: {
		display: [
			"children",
			"default",
			"children-first",
			"children-last",
			"children-odd",
			"children-even",
			"children-not-first",
			"children-not-last",
			"children-hover",
			"hover",
			"children-focus",
			"focus",
			"children-focus-within",
			"focus-within",
			"children-active",
			"active",
			"children-visited",
			"visited",
			"children-disabled",
			"disabled",
			"responsive",
		],
		//è importante l'ordine in cui scriviamo le varianti, in compilazione si darà la precedenza all' ultima variante della lista a seguire fino alla prima (ad eccezione della variante "responsive che ha sempre la precedenza"). In questo caso se abbiamo un hover:bg-black e un focus:bg-white nello stesso input ed entrambe le pseudoclassi sono attive il bg sarà black, in quanto, nell' array dell'utility backgroundColor, "hover" ha un indice più alto di "focus", però appena l'input perderà lo stato di hover ma manterrà il focus il bg sarà white.
		backgroundColor: ["responsive", "focus", "hover"],
	},
	//qui si aggiungono tutti i plug-in che si vuole utilizzare per il progetto dopo averli scaricati tramite npm. Questo è sufficiente per renderli utilizzabili salvo diverse indicazioni da documentazione.
	plugins: [
		require("tailwindcss-debug-screens"), //crea l'iconcina nera che indica il breakpoint
		require("@tailwindcss/custom-forms"), //custom input per i form
		require("tailwindcss-children"), //esempio plug-in per esetendere le varianti
	],
};
//IMPORTANTE: ogni volta che si vuole aggiungere una variante per una determinata utility bisogna riscriverle tutte per quell' utility proprio perchè è importante definirne l'ordine. Di seguito tutte le utility di default di tailwind con le rispettive varianti.
//   variants: {
//     accessibility: ['responsive', 'focus'],
//     alignContent: ['responsive'],
//     alignItems: ['responsive'],
//     alignSelf: ['responsive'],
//     appearance: ['responsive'],
//     backgroundAttachment: ['responsive'],
//     backgroundClip: ['responsive'],
//     backgroundColor: ['responsive', 'hover', 'focus'],
//     backgroundImage: ['responsive'],
//     gradientColorStops: ['responsive', 'hover', 'focus'],
//     backgroundOpacity: ['responsive', 'hover', 'focus'],
//     backgroundPosition: ['responsive'],
//     backgroundRepeat: ['responsive'],
//     backgroundSize: ['responsive'],
//     borderCollapse: ['responsive'],
//     borderColor: ['responsive', 'hover', 'focus'],
//     borderOpacity: ['responsive', 'hover', 'focus'],
//     borderRadius: ['responsive'],
//     borderStyle: ['responsive'],
//     borderWidth: ['responsive'],
//     boxShadow: ['responsive', 'hover', 'focus'],
//     boxSizing: ['responsive'],
//     container: ['responsive'],
//     cursor: ['responsive'],
//     display: ['responsive'],
//     divideColor: ['responsive'],
//     divideOpacity: ['responsive'],
//     divideStyle: ['responsive'],
//     divideWidth: ['responsive'],
//     fill: ['responsive'],
//     flex: ['responsive'],
//     flexDirection: ['responsive'],
//     flexGrow: ['responsive'],
//     flexShrink: ['responsive'],
//     flexWrap: ['responsive'],
//     float: ['responsive'],
//     clear: ['responsive'],
//     fontFamily: ['responsive'],
//     fontSize: ['responsive'],
//     fontSmoothing: ['responsive'],
//     fontStyle: ['responsive'],
//     fontWeight: ['responsive', 'hover', 'focus'],
//     height: ['responsive'],
//     inset: ['responsive'],
//     justifyContent: ['responsive'],
//     letterSpacing: ['responsive'],
//     lineHeight: ['responsive'],
//     listStylePosition: ['responsive'],
//     listStyleType: ['responsive'],
//     margin: ['responsive'],
//     maxHeight: ['responsive'],
//     maxWidth: ['responsive'],
//     minHeight: ['responsive'],
//     minWidth: ['responsive'],
//     objectFit: ['responsive'],
//     objectPosition: ['responsive'],
//     opacity: ['responsive', 'hover', 'focus'],
//     order: ['responsive'],
//     outline: ['responsive', 'focus'],
//     overflow: ['responsive'],
//     overscrollBehavior: ['responsive'],
//     padding: ['responsive'],
//     placeholderColor: ['responsive', 'focus'],
//     placeholderOpacity: ['responsive', 'focus'],
//     pointerEvents: ['responsive'],
//     position: ['responsive'],
//     resize: ['responsive'],
//     space: ['responsive'],
//     stroke: ['responsive'],
//     strokeWidth: ['responsive'],
//     tableLayout: ['responsive'],
//     textAlign: ['responsive'],
//     textColor: ['responsive', 'hover', 'focus'],
//     textOpacity: ['responsive', 'hover', 'focus'],
//     textDecoration: ['responsive', 'hover', 'focus'],
//     textTransform: ['responsive'],
//     userSelect: ['responsive'],
//     verticalAlign: ['responsive'],
//     visibility: ['responsive'],
//     whitespace: ['responsive'],
//     width: ['responsive'],
//     wordBreak: ['responsive'],
//     zIndex: ['responsive'],
//     gap: ['responsive'],
//     gridAutoFlow: ['responsive'],
//     gridTemplateColumns: ['responsive'],
//     gridColumn: ['responsive'],
//     gridColumnStart: ['responsive'],
//     gridColumnEnd: ['responsive'],
//     gridTemplateRows: ['responsive'],
//     gridRow: ['responsive'],
//     gridRowStart: ['responsive'],
//     gridRowEnd: ['responsive'],
//     transform: ['responsive'],
//     transformOrigin: ['responsive'],
//     scale: ['responsive', 'hover', 'focus'],
//     rotate: ['responsive', 'hover', 'focus'],
//     translate: ['responsive', 'hover', 'focus'],
//     skew: ['responsive', 'hover', 'focus'],
//     transitionProperty: ['responsive'],
//     transitionTimingFunction: ['responsive'],
//     transitionDuration: ['responsive'],
//     transitionDelay: ['responsive'],
//     animation: ['responsive']
//   }
