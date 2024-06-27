(function (factory) {
	"use strict";
	if (typeof define === 'function' && define.amd) {
		define('jstree.checkbox', ['jquery','jstree'], factory);
	}
	else if(typeof exports === 'object') {
		factory(require('jquery'), require('jstree'));
	}
	else {
		factory(jQuery);
	}
}(function ($, undefined) {
	"use strict";
	/*
	* document.registerElement("vakata-jstree", { prototype: proto });
	* [Deprecation] document.registerElement is deprecated and will be removed in M80, around February 2020. 
	* Please use window.customElements.define instead. 
	* See https://www.chromestatus.com/features/4642138092470272 and https://developers.google.com/web/updates/2019/07/web-components-time-to-upgrade for more details.
	*/
	if(window.customElements && Object && Object.create) {
		var proto = Object.create(HTMLElement.prototype);
		proto.createdCallback = function () {
			var c = { core : {}, plugins : [] }, i;
			for(i in $.jstree.plugins) {
				if($.jstree.plugins.hasOwnProperty(i) && this.attributes[i]) {
					c.plugins.push(i);
					if(this.getAttribute(i) && JSON.parse(this.getAttribute(i))) {
						c[i] = JSON.parse(this.getAttribute(i));
					}
				}
			}
			for(i in $.jstree.defaults.core) {
				if($.jstree.defaults.core.hasOwnProperty(i) && this.attributes[i]) {
					c.core[i] = JSON.parse(this.getAttribute(i)) || this.getAttribute(i);
				}
			}
			$(this).jstree(c);
		};
		// proto.attributeChangedCallback = function (name, previous, value) { };
		try {
			/*
			 * document.registerElement("vakata-jstree", { prototype: proto });
			 * [Deprecation] document.registerElement is deprecated and will be removed in M80, around February 2020. 
			 * Please use window.customElements.define instead. 
			 * See https://www.chromestatus.com/features/4642138092470272 and https://developers.google.com/web/updates/2019/07/web-components-time-to-upgrade for more details.
			 */
			window.customElements.define("vakata-jstree", { prototype: proto });
		} catch(ignore) { }
	}
}));
