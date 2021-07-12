const preventBack = {
	methods: {
		preventBackSet (callback) {
			window.pushStateFun = function () {
				console.log('pushStateFun')
				callback()
				history.pushState(null, null, document.URL);
				window.removeEventListener("popstate", window.pushStateFun, false);
			}

			history.pushState(null, null, document.URL);
			window.addEventListener("popstate", window.pushStateFun, false);
		},

		preventBackUnset () {
			window.history.back(-1);
			window.removeEventListener("popstate", window.pushStateFun, false);
		}
	},

	beforeRouteLeave (to, from, next) {
		window.removeEventListener("popstate", window.pushStateFun, false);
		next();
	}
};
export default preventBack;