var pUniqueId = [];

$(function(){
    var $body = $('body');

	/* #### FIX DataTable issue in Tab #### */
//	$body.on('a[data-toggle="tab"]','shown.bs.tab', function (e) {
//		$($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
//	});
//	$body.on("init.dt", function(e, settings) {
//		$($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
//	});	
//	$body.on("mouseenter", '.tab-pane.active', function(e, settings) {		
//		$($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
//		console.log('Test1');
//	});
//	$body.on("click", '.tab-pane.active', function(e, settings) {		
//		$($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
//	});

});
//$(window).on("resize",function(){
//	$($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
//});

var melisCommerce = (function(window) {

    function pendingZoneStart(zoneId){
        $("#"+zoneId).append('<div id="loader" class="overlay-loader"><img class="loader-icon spinning-cog" src="/MelisCore/assets/images/cog12.svg" data-cog="cog12"></div>');
    }

    function pendingZoneDone(zoneId){
        $("#"+zoneId+" .loader-icon").removeClass("spinning-cog").addClass("shrinking-cog");
        setTimeout(function() {
            $("#"+zoneId+" #loader").remove();
        },500);
    }

    function initTooltipTable() {
        $(".tooltipTable").each(function() {
            $(this).qtip({
                content: {
                    text: $(this).next('.tooltiptext')
                },
                overwrite: false,
                style: {
                    classes: 'qtip-tipsy qtip-shadow',
                    width: "auto",
                },
                hide: {
                    fixed: true,
                    delay: 300,
                    event: "mouseleave"
                },
                position: {
                    target: 'mouse',
                    adjust: {
                        mouse: false
                    },
                    my: 'center center',
                    at: 'center center',
                    //container : false,
                },
//			    adjust:{
//		          screen: true,
//			    },
            });
        });
    }

    function initTooltipVarTable() {
        $(".tooltipTableVar").each(function() {
            $(this).qtip({
                content: {
                    text: $(this).next('.tooltiptext')
                },
                overwrite: false,
                style: {
                    classes: 'qtip-tipsy qtip-shadow',
                    width: "auto",
                },
                hide: {
                    fixed: true,
                    delay: 300,
                    event: "mouseleave"
                },
                position: {
                    target: 'mouse',
                    adjust: {
                        mouse: false,
                    },
                    my: 'center center',
                    at: 'center center',
                },
            });
        });
    }

    function openProductPage(productId, productName, navTabsGroup)
    {
        melisHelper.tabOpen(melisCore.escapeHtml(productName), "icon-shippment", productId+"_id_meliscommerce_products_page", "meliscommerce_products_page",  { productId: productId }, navTabsGroup );
    }

    function closeCurrentProductPage() {
        melisHelper.tabClose(melisCommerce.getCurrentProductId() + "_id_meliscommerce_products_page");
    }

    function getCurrentProductId()
    {
        return activeTabId.split("_")[0];
    }

    function reloadCurrentProdPage(prodId) {
        var productId = melisCommerce.getCurrentProductId();
        if(prodId != null) {
            productId = prodId;
        }
        melisCoreTool.clearForm("productTextTypeForm");
        melisHelper.zoneReload(productId+"_id_meliscommerce_products_page","meliscommerce_products_page", {productId : productId});
    }

    function initTinyMCE()
    {
        console.log('done');
        var locale = "";

        if(melisLangId == "en_EN") {
            locale = "en";
        }
        else {
            locale = melisLangId;
        }
        setTimeout(function() {
            tinymce.init({
                mode: "specific_textareas",
                //elements: "textarea",
                editor_selector: "mceEditor",
                language: locale,
                height: 200,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table contextmenu paste template'
                ],
                menubar: false,
                toolbar: 'undo redo | styleselect | bold italic | link image |  alignleft aligncenter alignright alignjustify | code',
            });
        }, 1000);
    }


    function initCommerceTable(table, ajaxUrl, columns, filterBarDom, bulkDom) {
        $(table).DataTable({
            responsive: true,
            paging: true,
            ordering: true,
            processing: true,
            serverSide: true,
            searching: true,
            lengthMenu: [ [5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"] ],
            pageLength: 10,
            ajax: {
                url: ajaxUrl,
                type: "POST",
            },
            "order": [[ 1, "asc" ]],
            "columns": columns,
            "columnDefs": [
                { "responsivePriority": 1, "targets": 0 },
                { "responsivePriority": 2, "targets": -1 },
            ],
            "language" : {
                url: "/melis/MelisCore/Language/getDataTableTranslations",
            },
			/* "responsive": true, */
            "dom": '<"bulk-action"><"filter-bar fl">rtip',
            "drawCallback": function( settings ) {
                $(this).css('width','100%');
            }
        });
        $(".filter-bar").html(filterBarDom);
        $(".bulk-action").html(bulkDom);
    }

    function postSave(ajaxUrl, ajaxData, successCallBack, errorCallBack) {
        $.ajax({
            type        : 'POST',
            url         : ajaxUrl,
            data		: ajaxData,
            dataType    : 'json',
            encode		: true,
        }).success(function(data){
            successCallBack(data);
        }).error(function(){
            errorCallBack();
        });
    }

    function getDocFormType() {
        var formType = "";
        formType = $("#"+activeTabId).find("div.ecom-doc-container").data("form-type");
        return formType;
    }

    function setUniqueId(id) {
        if(id == null) {
            $.ajax({
                type        : 'POST',
                url         : '/melis/MelisCommerce/MelisComDocument/setUniqueId',
                data : {id: id},
                dataType    : 'json',
                encode		: true,
            }).success(function(data){
                //$("body").attr("data-uniq-id", data.id);
                pUniqueId[activeTabId] = data.id;
            });
        }
        else {
            //$("body").attr("data-uniq-id", id);
            pUniqueId[activeTabId] = id;
        }


    }

    function getUniqueId() {
        return pUniqueId[activeTabId];
        //return $("body").attr("data-uniq-id");
    }

    function disableTab(tabId) {
        $("li a.tab-element[data-id='"+tabId+"']").css('pointer-events','none').parent().css("cursor", "not-allowed");
    }

    function enableTab(tabId) {
        $("li a.tab-element[data-id='"+tabId+"']").css('pointer-events','auto').parent().css("cursor", "pointer");
    }

    function disableAllTabs()
    {
        $.each($("#melis-id-nav-bar-tabs li a"), function(i, v) {
            var tabId = $(v).data("id");
            disableTab(tabId);
        });

        // disable navigation too
        $.each($("ul.sideMenu"), function(i ,v) {
            $(v).css('pointer-events','none').parent().css("cursor", "not-allowed");
        });
    }

    function enableAllTabs()
    {
        $.each($("#melis-id-nav-bar-tabs li a"), function(i, v) {
            var tabId = $(v).data("id");
            enableTab(tabId);
        });

        $.each($("ul.sideMenu"), function(i ,v) {
            $(v).css('pointer-events','none').css('pointer-events','auto').parent().css("cursor", "pointer");
        });
    }


    return {
        pendingZoneStart : pendingZoneStart,
        pendingZoneDone : pendingZoneDone,
        initTooltipTable: initTooltipTable,
        initTooltipVarTable: initTooltipVarTable,
        initCommerceTable : initCommerceTable,
        openProductPage : openProductPage,
        postSave: postSave,
        getCurrentProductId : getCurrentProductId,
        closeCurrentProductPage : closeCurrentProductPage,
        reloadCurrentProdPage : reloadCurrentProdPage,
        initTinyMCE : initTinyMCE,
        getDocFormType : getDocFormType,
        setUniqueId : setUniqueId,
        getUniqueId : getUniqueId,
        enableTab : enableTab,
        disableTab : disableTab,
        disableAllTabs : disableAllTabs,
        enableAllTabs : enableAllTabs
    }

})(window);

setInterval(function() {
    melisCommerce.enableAllTabs();
}, 10000)
/*
 * Fuel UX Checkbox
 * https://github.com/ExactTarget/fuelux
 *
 * Copyright (c) 2012 ExactTarget
 * Licensed under the MIT license.
 */

$(function(){

	var old = $.fn.checkbox;

	// CHECKBOX CONSTRUCTOR AND PROTOTYPE

	var Checkbox = function (element, options) {

		this.$element = $(element);
		this.options = $.extend({}, $.fn.checkbox.defaults, options);

		// cache elements
		this.$label = this.$element.parent();
		this.$icon = this.$label.find('i');
		this.$chk = this.$label.find('input[type=checkbox]');

		// set default state
		this.setState(this.$chk);

		// handle events
		this.$chk.on('change', $.proxy(this.itemchecked, this));
	};

	Checkbox.prototype = {

		constructor: Checkbox,

		setState: function ($chk) {
			$chk = $chk || this.$chk;

			var checked = $chk.is(':checked');
			var disabled = !!$chk.prop('disabled');

			// reset classes
			this.$icon.removeClass('checked disabled');

			// set state of checkbox
			if (checked === true) {
				this.$icon.addClass('checked');
			}
			if (disabled === true) {
				this.$icon.addClass('disabled');
			}
		},

		enable: function () {
			this.$chk.attr('disabled', false);
			this.$icon.removeClass('disabled');
		},

		disable: function () {
			this.$chk.attr('disabled', true);
			this.$icon.addClass('disabled');
		},

		toggle: function () {
			this.$chk.click();
		},

		itemchecked: function (e) {
			var chk = $(e.target);
			this.setState(chk);
		},
		
		check: function () {
			this.$chk.prop('checked', true);
			this.setState(this.$chk);
		},
		
		uncheck: function () {
			this.$chk.prop('checked', false);
			this.setState(this.$chk);
		},
		
		isChecked: function () {
			return this.$chk.is(':checked');
		}
	};


	// CHECKBOX PLUGIN DEFINITION

	$.fn.checkbox = function (option) {
		var args = Array.prototype.slice.call( arguments, 1 );
		var methodReturn;

		var $set = this.each(function () {
			var $this   = $( this );
			var data    = $this.data('checkbox');
			var options = typeof option === 'object' && option;

			if( !data ) $this.data('checkbox', (data = new Checkbox(this, options)));
			if( typeof option === 'string' ) methodReturn = data[ option ].apply( data, args );
		});

		return ( methodReturn === undefined ) ? $set : methodReturn;
	};

	$.fn.checkbox.defaults = {};

	$.fn.checkbox.Constructor = Checkbox;

	$.fn.checkbox.noConflict = function () {
		$.fn.checkbox = old;
		return this;
	};


	// CHECKBOX DATA-API

	$(function () {
		$(window).on('load', function () {
			//$('i.checkbox').each(function () {
			$('.checkbox-custom > input[type=checkbox]').each(function () {
				var $this = $(this);
				if ($this.data('checkbox')) return;
				$this.checkbox($this.data());
			});
		});
	});
});
/* qtip2 v3.0.3 | Plugins: tips modal viewport svg imagemap ie6 | Styles: core basic css3 | qtip2.com | Licensed MIT | Wed May 11 2016 22:31:31 */

!function(a,b,c){!function(a){"use strict";"function"==typeof define&&define.amd?define(["jquery"],a):jQuery&&!jQuery.fn.qtip&&a(jQuery)}(function(d){"use strict";function e(a,b,c,e){this.id=c,this.target=a,this.tooltip=F,this.elements={target:a},this._id=S+"-"+c,this.timers={img:{}},this.options=b,this.plugins={},this.cache={event:{},target:d(),disabled:E,attr:e,onTooltip:E,lastClass:""},this.rendered=this.destroyed=this.disabled=this.waiting=this.hiddenDuringWait=this.positioning=this.triggering=E}function f(a){return a===F||"object"!==d.type(a)}function g(a){return!(d.isFunction(a)||a&&a.attr||a.length||"object"===d.type(a)&&(a.jquery||a.then))}function h(a){var b,c,e,h;return f(a)?E:(f(a.metadata)&&(a.metadata={type:a.metadata}),"content"in a&&(b=a.content,f(b)||b.jquery||b.done?(c=g(b)?E:b,b=a.content={text:c}):c=b.text,"ajax"in b&&(e=b.ajax,h=e&&e.once!==E,delete b.ajax,b.text=function(a,b){var f=c||d(this).attr(b.options.content.attr)||"Loading...",g=d.ajax(d.extend({},e,{context:b})).then(e.success,F,e.error).then(function(a){return a&&h&&b.set("content.text",a),a},function(a,c,d){b.destroyed||0===a.status||b.set("content.text",c+": "+d)});return h?f:(b.set("content.text",f),g)}),"title"in b&&(d.isPlainObject(b.title)&&(b.button=b.title.button,b.title=b.title.text),g(b.title||E)&&(b.title=E))),"position"in a&&f(a.position)&&(a.position={my:a.position,at:a.position}),"show"in a&&f(a.show)&&(a.show=a.show.jquery?{target:a.show}:a.show===D?{ready:D}:{event:a.show}),"hide"in a&&f(a.hide)&&(a.hide=a.hide.jquery?{target:a.hide}:{event:a.hide}),"style"in a&&f(a.style)&&(a.style={classes:a.style}),d.each(R,function(){this.sanitize&&this.sanitize(a)}),a)}function i(a,b){for(var c,d=0,e=a,f=b.split(".");e=e[f[d++]];)d<f.length&&(c=e);return[c||a,f.pop()]}function j(a,b){var c,d,e;for(c in this.checks)if(this.checks.hasOwnProperty(c))for(d in this.checks[c])this.checks[c].hasOwnProperty(d)&&(e=new RegExp(d,"i").exec(a))&&(b.push(e),("builtin"===c||this.plugins[c])&&this.checks[c][d].apply(this.plugins[c]||this,b))}function k(a){return V.concat("").join(a?"-"+a+" ":" ")}function l(a,b){return b>0?setTimeout(d.proxy(a,this),b):void a.call(this)}function m(a){this.tooltip.hasClass(aa)||(clearTimeout(this.timers.show),clearTimeout(this.timers.hide),this.timers.show=l.call(this,function(){this.toggle(D,a)},this.options.show.delay))}function n(a){if(!this.tooltip.hasClass(aa)&&!this.destroyed){var b=d(a.relatedTarget),c=b.closest(W)[0]===this.tooltip[0],e=b[0]===this.options.show.target[0];if(clearTimeout(this.timers.show),clearTimeout(this.timers.hide),this!==b[0]&&"mouse"===this.options.position.target&&c||this.options.hide.fixed&&/mouse(out|leave|move)/.test(a.type)&&(c||e))try{a.preventDefault(),a.stopImmediatePropagation()}catch(f){}else this.timers.hide=l.call(this,function(){this.toggle(E,a)},this.options.hide.delay,this)}}function o(a){!this.tooltip.hasClass(aa)&&this.options.hide.inactive&&(clearTimeout(this.timers.inactive),this.timers.inactive=l.call(this,function(){this.hide(a)},this.options.hide.inactive))}function p(a){this.rendered&&this.tooltip[0].offsetWidth>0&&this.reposition(a)}function q(a,c,e){d(b.body).delegate(a,(c.split?c:c.join("."+S+" "))+"."+S,function(){var a=y.api[d.attr(this,U)];a&&!a.disabled&&e.apply(a,arguments)})}function r(a,c,f){var g,i,j,k,l,m=d(b.body),n=a[0]===b?m:a,o=a.metadata?a.metadata(f.metadata):F,p="html5"===f.metadata.type&&o?o[f.metadata.name]:F,q=a.data(f.metadata.name||"qtipopts");try{q="string"==typeof q?d.parseJSON(q):q}catch(r){}if(k=d.extend(D,{},y.defaults,f,"object"==typeof q?h(q):F,h(p||o)),i=k.position,k.id=c,"boolean"==typeof k.content.text){if(j=a.attr(k.content.attr),k.content.attr===E||!j)return E;k.content.text=j}if(i.container.length||(i.container=m),i.target===E&&(i.target=n),k.show.target===E&&(k.show.target=n),k.show.solo===D&&(k.show.solo=i.container.closest("body")),k.hide.target===E&&(k.hide.target=n),k.position.viewport===D&&(k.position.viewport=i.container),i.container=i.container.eq(0),i.at=new A(i.at,D),i.my=new A(i.my),a.data(S))if(k.overwrite)a.qtip("destroy",!0);else if(k.overwrite===E)return E;return a.attr(T,c),k.suppress&&(l=a.attr("title"))&&a.removeAttr("title").attr(ca,l).attr("title",""),g=new e(a,k,c,!!j),a.data(S,g),g}function s(a){return a.charAt(0).toUpperCase()+a.slice(1)}function t(a,b){var d,e,f=b.charAt(0).toUpperCase()+b.slice(1),g=(b+" "+va.join(f+" ")+f).split(" "),h=0;if(ua[b])return a.css(ua[b]);for(;d=g[h++];)if((e=a.css(d))!==c)return ua[b]=d,e}function u(a,b){return Math.ceil(parseFloat(t(a,b)))}function v(a,b){this._ns="tip",this.options=b,this.offset=b.offset,this.size=[b.width,b.height],this.qtip=a,this.init(a)}function w(a,b){this.options=b,this._ns="-modal",this.qtip=a,this.init(a)}function x(a){this._ns="ie6",this.qtip=a,this.init(a)}var y,z,A,B,C,D=!0,E=!1,F=null,G="x",H="y",I="width",J="height",K="top",L="left",M="bottom",N="right",O="center",P="flipinvert",Q="shift",R={},S="qtip",T="data-hasqtip",U="data-qtip-id",V=["ui-widget","ui-tooltip"],W="."+S,X="click dblclick mousedown mouseup mousemove mouseleave mouseenter".split(" "),Y=S+"-fixed",Z=S+"-default",$=S+"-focus",_=S+"-hover",aa=S+"-disabled",ba="_replacedByqTip",ca="oldtitle",da={ie:function(){var a,c;for(a=4,c=b.createElement("div");(c.innerHTML="<!--[if gt IE "+a+"]><i></i><![endif]-->")&&c.getElementsByTagName("i")[0];a+=1);return a>4?a:NaN}(),iOS:parseFloat((""+(/CPU.*OS ([0-9_]{1,5})|(CPU like).*AppleWebKit.*Mobile/i.exec(navigator.userAgent)||[0,""])[1]).replace("undefined","3_2").replace("_",".").replace("_",""))||E};z=e.prototype,z._when=function(a){return d.when.apply(d,a)},z.render=function(a){if(this.rendered||this.destroyed)return this;var b=this,c=this.options,e=this.cache,f=this.elements,g=c.content.text,h=c.content.title,i=c.content.button,j=c.position,k=[];return d.attr(this.target[0],"aria-describedby",this._id),e.posClass=this._createPosClass((this.position={my:j.my,at:j.at}).my),this.tooltip=f.tooltip=d("<div/>",{id:this._id,"class":[S,Z,c.style.classes,e.posClass].join(" "),width:c.style.width||"",height:c.style.height||"",tracking:"mouse"===j.target&&j.adjust.mouse,role:"alert","aria-live":"polite","aria-atomic":E,"aria-describedby":this._id+"-content","aria-hidden":D}).toggleClass(aa,this.disabled).attr(U,this.id).data(S,this).appendTo(j.container).append(f.content=d("<div />",{"class":S+"-content",id:this._id+"-content","aria-atomic":D})),this.rendered=-1,this.positioning=D,h&&(this._createTitle(),d.isFunction(h)||k.push(this._updateTitle(h,E))),i&&this._createButton(),d.isFunction(g)||k.push(this._updateContent(g,E)),this.rendered=D,this._setWidget(),d.each(R,function(a){var c;"render"===this.initialize&&(c=this(b))&&(b.plugins[a]=c)}),this._unassignEvents(),this._assignEvents(),this._when(k).then(function(){b._trigger("render"),b.positioning=E,b.hiddenDuringWait||!c.show.ready&&!a||b.toggle(D,e.event,E),b.hiddenDuringWait=E}),y.api[this.id]=this,this},z.destroy=function(a){function b(){if(!this.destroyed){this.destroyed=D;var a,b=this.target,c=b.attr(ca);this.rendered&&this.tooltip.stop(1,0).find("*").remove().end().remove(),d.each(this.plugins,function(){this.destroy&&this.destroy()});for(a in this.timers)this.timers.hasOwnProperty(a)&&clearTimeout(this.timers[a]);b.removeData(S).removeAttr(U).removeAttr(T).removeAttr("aria-describedby"),this.options.suppress&&c&&b.attr("title",c).removeAttr(ca),this._unassignEvents(),this.options=this.elements=this.cache=this.timers=this.plugins=this.mouse=F,delete y.api[this.id]}}return this.destroyed?this.target:(a===D&&"hide"!==this.triggering||!this.rendered?b.call(this):(this.tooltip.one("tooltiphidden",d.proxy(b,this)),!this.triggering&&this.hide()),this.target)},B=z.checks={builtin:{"^id$":function(a,b,c,e){var f=c===D?y.nextid:c,g=S+"-"+f;f!==E&&f.length>0&&!d("#"+g).length?(this._id=g,this.rendered&&(this.tooltip[0].id=this._id,this.elements.content[0].id=this._id+"-content",this.elements.title[0].id=this._id+"-title")):a[b]=e},"^prerender":function(a,b,c){c&&!this.rendered&&this.render(this.options.show.ready)},"^content.text$":function(a,b,c){this._updateContent(c)},"^content.attr$":function(a,b,c,d){this.options.content.text===this.target.attr(d)&&this._updateContent(this.target.attr(c))},"^content.title$":function(a,b,c){return c?(c&&!this.elements.title&&this._createTitle(),void this._updateTitle(c)):this._removeTitle()},"^content.button$":function(a,b,c){this._updateButton(c)},"^content.title.(text|button)$":function(a,b,c){this.set("content."+b,c)},"^position.(my|at)$":function(a,b,c){"string"==typeof c&&(this.position[b]=a[b]=new A(c,"at"===b))},"^position.container$":function(a,b,c){this.rendered&&this.tooltip.appendTo(c)},"^show.ready$":function(a,b,c){c&&(!this.rendered&&this.render(D)||this.toggle(D))},"^style.classes$":function(a,b,c,d){this.rendered&&this.tooltip.removeClass(d).addClass(c)},"^style.(width|height)":function(a,b,c){this.rendered&&this.tooltip.css(b,c)},"^style.widget|content.title":function(){this.rendered&&this._setWidget()},"^style.def":function(a,b,c){this.rendered&&this.tooltip.toggleClass(Z,!!c)},"^events.(render|show|move|hide|focus|blur)$":function(a,b,c){this.rendered&&this.tooltip[(d.isFunction(c)?"":"un")+"bind"]("tooltip"+b,c)},"^(show|hide|position).(event|target|fixed|inactive|leave|distance|viewport|adjust)":function(){if(this.rendered){var a=this.options.position;this.tooltip.attr("tracking","mouse"===a.target&&a.adjust.mouse),this._unassignEvents(),this._assignEvents()}}}},z.get=function(a){if(this.destroyed)return this;var b=i(this.options,a.toLowerCase()),c=b[0][b[1]];return c.precedance?c.string():c};var ea=/^position\.(my|at|adjust|target|container|viewport)|style|content|show\.ready/i,fa=/^prerender|show\.ready/i;z.set=function(a,b){if(this.destroyed)return this;var c,e=this.rendered,f=E,g=this.options;return"string"==typeof a?(c=a,a={},a[c]=b):a=d.extend({},a),d.each(a,function(b,c){if(e&&fa.test(b))return void delete a[b];var h,j=i(g,b.toLowerCase());h=j[0][j[1]],j[0][j[1]]=c&&c.nodeType?d(c):c,f=ea.test(b)||f,a[b]=[j[0],j[1],c,h]}),h(g),this.positioning=D,d.each(a,d.proxy(j,this)),this.positioning=E,this.rendered&&this.tooltip[0].offsetWidth>0&&f&&this.reposition("mouse"===g.position.target?F:this.cache.event),this},z._update=function(a,b){var c=this,e=this.cache;return this.rendered&&a?(d.isFunction(a)&&(a=a.call(this.elements.target,e.event,this)||""),d.isFunction(a.then)?(e.waiting=D,a.then(function(a){return e.waiting=E,c._update(a,b)},F,function(a){return c._update(a,b)})):a===E||!a&&""!==a?E:(a.jquery&&a.length>0?b.empty().append(a.css({display:"block",visibility:"visible"})):b.html(a),this._waitForContent(b).then(function(a){c.rendered&&c.tooltip[0].offsetWidth>0&&c.reposition(e.event,!a.length)}))):E},z._waitForContent=function(a){var b=this.cache;return b.waiting=D,(d.fn.imagesLoaded?a.imagesLoaded():(new d.Deferred).resolve([])).done(function(){b.waiting=E}).promise()},z._updateContent=function(a,b){this._update(a,this.elements.content,b)},z._updateTitle=function(a,b){this._update(a,this.elements.title,b)===E&&this._removeTitle(E)},z._createTitle=function(){var a=this.elements,b=this._id+"-title";a.titlebar&&this._removeTitle(),a.titlebar=d("<div />",{"class":S+"-titlebar "+(this.options.style.widget?k("header"):"")}).append(a.title=d("<div />",{id:b,"class":S+"-title","aria-atomic":D})).insertBefore(a.content).delegate(".qtip-close","mousedown keydown mouseup keyup mouseout",function(a){d(this).toggleClass("ui-state-active ui-state-focus","down"===a.type.substr(-4))}).delegate(".qtip-close","mouseover mouseout",function(a){d(this).toggleClass("ui-state-hover","mouseover"===a.type)}),this.options.content.button&&this._createButton()},z._removeTitle=function(a){var b=this.elements;b.title&&(b.titlebar.remove(),b.titlebar=b.title=b.button=F,a!==E&&this.reposition())},z._createPosClass=function(a){return S+"-pos-"+(a||this.options.position.my).abbrev()},z.reposition=function(c,e){if(!this.rendered||this.positioning||this.destroyed)return this;this.positioning=D;var f,g,h,i,j=this.cache,k=this.tooltip,l=this.options.position,m=l.target,n=l.my,o=l.at,p=l.viewport,q=l.container,r=l.adjust,s=r.method.split(" "),t=k.outerWidth(E),u=k.outerHeight(E),v=0,w=0,x=k.css("position"),y={left:0,top:0},z=k[0].offsetWidth>0,A=c&&"scroll"===c.type,B=d(a),C=q[0].ownerDocument,F=this.mouse;if(d.isArray(m)&&2===m.length)o={x:L,y:K},y={left:m[0],top:m[1]};else if("mouse"===m)o={x:L,y:K},(!r.mouse||this.options.hide.distance)&&j.origin&&j.origin.pageX?c=j.origin:!c||c&&("resize"===c.type||"scroll"===c.type)?c=j.event:F&&F.pageX&&(c=F),"static"!==x&&(y=q.offset()),C.body.offsetWidth!==(a.innerWidth||C.documentElement.clientWidth)&&(g=d(b.body).offset()),y={left:c.pageX-y.left+(g&&g.left||0),top:c.pageY-y.top+(g&&g.top||0)},r.mouse&&A&&F&&(y.left-=(F.scrollX||0)-B.scrollLeft(),y.top-=(F.scrollY||0)-B.scrollTop());else{if("event"===m?c&&c.target&&"scroll"!==c.type&&"resize"!==c.type?j.target=d(c.target):c.target||(j.target=this.elements.target):"event"!==m&&(j.target=d(m.jquery?m:this.elements.target)),m=j.target,m=d(m).eq(0),0===m.length)return this;m[0]===b||m[0]===a?(v=da.iOS?a.innerWidth:m.width(),w=da.iOS?a.innerHeight:m.height(),m[0]===a&&(y={top:(p||m).scrollTop(),left:(p||m).scrollLeft()})):R.imagemap&&m.is("area")?f=R.imagemap(this,m,o,R.viewport?s:E):R.svg&&m&&m[0].ownerSVGElement?f=R.svg(this,m,o,R.viewport?s:E):(v=m.outerWidth(E),w=m.outerHeight(E),y=m.offset()),f&&(v=f.width,w=f.height,g=f.offset,y=f.position),y=this.reposition.offset(m,y,q),(da.iOS>3.1&&da.iOS<4.1||da.iOS>=4.3&&da.iOS<4.33||!da.iOS&&"fixed"===x)&&(y.left-=B.scrollLeft(),y.top-=B.scrollTop()),(!f||f&&f.adjustable!==E)&&(y.left+=o.x===N?v:o.x===O?v/2:0,y.top+=o.y===M?w:o.y===O?w/2:0)}return y.left+=r.x+(n.x===N?-t:n.x===O?-t/2:0),y.top+=r.y+(n.y===M?-u:n.y===O?-u/2:0),R.viewport?(h=y.adjusted=R.viewport(this,y,l,v,w,t,u),g&&h.left&&(y.left+=g.left),g&&h.top&&(y.top+=g.top),h.my&&(this.position.my=h.my)):y.adjusted={left:0,top:0},j.posClass!==(i=this._createPosClass(this.position.my))&&(j.posClass=i,k.removeClass(j.posClass).addClass(i)),this._trigger("move",[y,p.elem||p],c)?(delete y.adjusted,e===E||!z||isNaN(y.left)||isNaN(y.top)||"mouse"===m||!d.isFunction(l.effect)?k.css(y):d.isFunction(l.effect)&&(l.effect.call(k,this,d.extend({},y)),k.queue(function(a){d(this).css({opacity:"",height:""}),da.ie&&this.style.removeAttribute("filter"),a()})),this.positioning=E,this):this},z.reposition.offset=function(a,c,e){function f(a,b){c.left+=b*a.scrollLeft(),c.top+=b*a.scrollTop()}if(!e[0])return c;var g,h,i,j,k=d(a[0].ownerDocument),l=!!da.ie&&"CSS1Compat"!==b.compatMode,m=e[0];do"static"!==(h=d.css(m,"position"))&&("fixed"===h?(i=m.getBoundingClientRect(),f(k,-1)):(i=d(m).position(),i.left+=parseFloat(d.css(m,"borderLeftWidth"))||0,i.top+=parseFloat(d.css(m,"borderTopWidth"))||0),c.left-=i.left+(parseFloat(d.css(m,"marginLeft"))||0),c.top-=i.top+(parseFloat(d.css(m,"marginTop"))||0),g||"hidden"===(j=d.css(m,"overflow"))||"visible"===j||(g=d(m)));while(m=m.offsetParent);return g&&(g[0]!==k[0]||l)&&f(g,1),c};var ga=(A=z.reposition.Corner=function(a,b){a=(""+a).replace(/([A-Z])/," $1").replace(/middle/gi,O).toLowerCase(),this.x=(a.match(/left|right/i)||a.match(/center/)||["inherit"])[0].toLowerCase(),this.y=(a.match(/top|bottom|center/i)||["inherit"])[0].toLowerCase(),this.forceY=!!b;var c=a.charAt(0);this.precedance="t"===c||"b"===c?H:G}).prototype;ga.invert=function(a,b){this[a]=this[a]===L?N:this[a]===N?L:b||this[a]},ga.string=function(a){var b=this.x,c=this.y,d=b!==c?"center"===b||"center"!==c&&(this.precedance===H||this.forceY)?[c,b]:[b,c]:[b];return a!==!1?d.join(" "):d},ga.abbrev=function(){var a=this.string(!1);return a[0].charAt(0)+(a[1]&&a[1].charAt(0)||"")},ga.clone=function(){return new A(this.string(),this.forceY)},z.toggle=function(a,c){var e=this.cache,f=this.options,g=this.tooltip;if(c){if(/over|enter/.test(c.type)&&e.event&&/out|leave/.test(e.event.type)&&f.show.target.add(c.target).length===f.show.target.length&&g.has(c.relatedTarget).length)return this;e.event=d.event.fix(c)}if(this.waiting&&!a&&(this.hiddenDuringWait=D),!this.rendered)return a?this.render(1):this;if(this.destroyed||this.disabled)return this;var h,i,j,k=a?"show":"hide",l=this.options[k],m=this.options.position,n=this.options.content,o=this.tooltip.css("width"),p=this.tooltip.is(":visible"),q=a||1===l.target.length,r=!c||l.target.length<2||e.target[0]===c.target;return(typeof a).search("boolean|number")&&(a=!p),h=!g.is(":animated")&&p===a&&r,i=h?F:!!this._trigger(k,[90]),this.destroyed?this:(i!==E&&a&&this.focus(c),!i||h?this:(d.attr(g[0],"aria-hidden",!a),a?(this.mouse&&(e.origin=d.event.fix(this.mouse)),d.isFunction(n.text)&&this._updateContent(n.text,E),d.isFunction(n.title)&&this._updateTitle(n.title,E),!C&&"mouse"===m.target&&m.adjust.mouse&&(d(b).bind("mousemove."+S,this._storeMouse),C=D),o||g.css("width",g.outerWidth(E)),this.reposition(c,arguments[2]),o||g.css("width",""),l.solo&&("string"==typeof l.solo?d(l.solo):d(W,l.solo)).not(g).not(l.target).qtip("hide",new d.Event("tooltipsolo"))):(clearTimeout(this.timers.show),delete e.origin,C&&!d(W+'[tracking="true"]:visible',l.solo).not(g).length&&(d(b).unbind("mousemove."+S),C=E),this.blur(c)),j=d.proxy(function(){a?(da.ie&&g[0].style.removeAttribute("filter"),g.css("overflow",""),"string"==typeof l.autofocus&&d(this.options.show.autofocus,g).focus(),this.options.show.target.trigger("qtip-"+this.id+"-inactive")):g.css({display:"",visibility:"",opacity:"",left:"",top:""}),this._trigger(a?"visible":"hidden")},this),l.effect===E||q===E?(g[k](),j()):d.isFunction(l.effect)?(g.stop(1,1),l.effect.call(g,this),g.queue("fx",function(a){j(),a()})):g.fadeTo(90,a?1:0,j),a&&l.target.trigger("qtip-"+this.id+"-inactive"),this))},z.show=function(a){return this.toggle(D,a)},z.hide=function(a){return this.toggle(E,a)},z.focus=function(a){if(!this.rendered||this.destroyed)return this;var b=d(W),c=this.tooltip,e=parseInt(c[0].style.zIndex,10),f=y.zindex+b.length;return c.hasClass($)||this._trigger("focus",[f],a)&&(e!==f&&(b.each(function(){this.style.zIndex>e&&(this.style.zIndex=this.style.zIndex-1)}),b.filter("."+$).qtip("blur",a)),c.addClass($)[0].style.zIndex=f),this},z.blur=function(a){return!this.rendered||this.destroyed?this:(this.tooltip.removeClass($),this._trigger("blur",[this.tooltip.css("zIndex")],a),this)},z.disable=function(a){return this.destroyed?this:("toggle"===a?a=!(this.rendered?this.tooltip.hasClass(aa):this.disabled):"boolean"!=typeof a&&(a=D),this.rendered&&this.tooltip.toggleClass(aa,a).attr("aria-disabled",a),this.disabled=!!a,this)},z.enable=function(){return this.disable(E)},z._createButton=function(){var a=this,b=this.elements,c=b.tooltip,e=this.options.content.button,f="string"==typeof e,g=f?e:"Close tooltip";b.button&&b.button.remove(),e.jquery?b.button=e:b.button=d("<a />",{"class":"qtip-close "+(this.options.style.widget?"":S+"-icon"),title:g,"aria-label":g}).prepend(d("<span />",{"class":"ui-icon ui-icon-close",html:"&times;"})),b.button.appendTo(b.titlebar||c).attr("role","button").click(function(b){return c.hasClass(aa)||a.hide(b),E})},z._updateButton=function(a){if(!this.rendered)return E;var b=this.elements.button;a?this._createButton():b.remove()},z._setWidget=function(){var a=this.options.style.widget,b=this.elements,c=b.tooltip,d=c.hasClass(aa);c.removeClass(aa),aa=a?"ui-state-disabled":"qtip-disabled",c.toggleClass(aa,d),c.toggleClass("ui-helper-reset "+k(),a).toggleClass(Z,this.options.style.def&&!a),b.content&&b.content.toggleClass(k("content"),a),b.titlebar&&b.titlebar.toggleClass(k("header"),a),b.button&&b.button.toggleClass(S+"-icon",!a)},z._storeMouse=function(a){return(this.mouse=d.event.fix(a)).type="mousemove",this},z._bind=function(a,b,c,e,f){if(a&&c&&b.length){var g="."+this._id+(e?"-"+e:"");return d(a).bind((b.split?b:b.join(g+" "))+g,d.proxy(c,f||this)),this}},z._unbind=function(a,b){return a&&d(a).unbind("."+this._id+(b?"-"+b:"")),this},z._trigger=function(a,b,c){var e=new d.Event("tooltip"+a);return e.originalEvent=c&&d.extend({},c)||this.cache.event||F,this.triggering=a,this.tooltip.trigger(e,[this].concat(b||[])),this.triggering=E,!e.isDefaultPrevented()},z._bindEvents=function(a,b,c,e,f,g){var h=c.filter(e).add(e.filter(c)),i=[];h.length&&(d.each(b,function(b,c){var e=d.inArray(c,a);e>-1&&i.push(a.splice(e,1)[0])}),i.length&&(this._bind(h,i,function(a){var b=this.rendered?this.tooltip[0].offsetWidth>0:!1;(b?g:f).call(this,a)}),c=c.not(h),e=e.not(h))),this._bind(c,a,f),this._bind(e,b,g)},z._assignInitialEvents=function(a){function b(a){return this.disabled||this.destroyed?E:(this.cache.event=a&&d.event.fix(a),this.cache.target=a&&d(a.target),clearTimeout(this.timers.show),void(this.timers.show=l.call(this,function(){this.render("object"==typeof a||c.show.ready)},c.prerender?0:c.show.delay)))}var c=this.options,e=c.show.target,f=c.hide.target,g=c.show.event?d.trim(""+c.show.event).split(" "):[],h=c.hide.event?d.trim(""+c.hide.event).split(" "):[];this._bind(this.elements.target,["remove","removeqtip"],function(){this.destroy(!0)},"destroy"),/mouse(over|enter)/i.test(c.show.event)&&!/mouse(out|leave)/i.test(c.hide.event)&&h.push("mouseleave"),this._bind(e,"mousemove",function(a){this._storeMouse(a),this.cache.onTarget=D}),this._bindEvents(g,h,e,f,b,function(){return this.timers?void clearTimeout(this.timers.show):E}),(c.show.ready||c.prerender)&&b.call(this,a)},z._assignEvents=function(){var c=this,e=this.options,f=e.position,g=this.tooltip,h=e.show.target,i=e.hide.target,j=f.container,k=f.viewport,l=d(b),q=d(a),r=e.show.event?d.trim(""+e.show.event).split(" "):[],s=e.hide.event?d.trim(""+e.hide.event).split(" "):[];d.each(e.events,function(a,b){c._bind(g,"toggle"===a?["tooltipshow","tooltiphide"]:["tooltip"+a],b,null,g)}),/mouse(out|leave)/i.test(e.hide.event)&&"window"===e.hide.leave&&this._bind(l,["mouseout","blur"],function(a){/select|option/.test(a.target.nodeName)||a.relatedTarget||this.hide(a)}),e.hide.fixed?i=i.add(g.addClass(Y)):/mouse(over|enter)/i.test(e.show.event)&&this._bind(i,"mouseleave",function(){clearTimeout(this.timers.show)}),(""+e.hide.event).indexOf("unfocus")>-1&&this._bind(j.closest("html"),["mousedown","touchstart"],function(a){var b=d(a.target),c=this.rendered&&!this.tooltip.hasClass(aa)&&this.tooltip[0].offsetWidth>0,e=b.parents(W).filter(this.tooltip[0]).length>0;b[0]===this.target[0]||b[0]===this.tooltip[0]||e||this.target.has(b[0]).length||!c||this.hide(a)}),"number"==typeof e.hide.inactive&&(this._bind(h,"qtip-"+this.id+"-inactive",o,"inactive"),this._bind(i.add(g),y.inactiveEvents,o)),this._bindEvents(r,s,h,i,m,n),this._bind(h.add(g),"mousemove",function(a){if("number"==typeof e.hide.distance){var b=this.cache.origin||{},c=this.options.hide.distance,d=Math.abs;(d(a.pageX-b.pageX)>=c||d(a.pageY-b.pageY)>=c)&&this.hide(a)}this._storeMouse(a)}),"mouse"===f.target&&f.adjust.mouse&&(e.hide.event&&this._bind(h,["mouseenter","mouseleave"],function(a){return this.cache?void(this.cache.onTarget="mouseenter"===a.type):E}),this._bind(l,"mousemove",function(a){this.rendered&&this.cache.onTarget&&!this.tooltip.hasClass(aa)&&this.tooltip[0].offsetWidth>0&&this.reposition(a)})),(f.adjust.resize||k.length)&&this._bind(d.event.special.resize?k:q,"resize",p),f.adjust.scroll&&this._bind(q.add(f.container),"scroll",p)},z._unassignEvents=function(){var c=this.options,e=c.show.target,f=c.hide.target,g=d.grep([this.elements.target[0],this.rendered&&this.tooltip[0],c.position.container[0],c.position.viewport[0],c.position.container.closest("html")[0],a,b],function(a){return"object"==typeof a});e&&e.toArray&&(g=g.concat(e.toArray())),f&&f.toArray&&(g=g.concat(f.toArray())),this._unbind(g)._unbind(g,"destroy")._unbind(g,"inactive")},d(function(){q(W,["mouseenter","mouseleave"],function(a){var b="mouseenter"===a.type,c=d(a.currentTarget),e=d(a.relatedTarget||a.target),f=this.options;b?(this.focus(a),c.hasClass(Y)&&!c.hasClass(aa)&&clearTimeout(this.timers.hide)):"mouse"===f.position.target&&f.position.adjust.mouse&&f.hide.event&&f.show.target&&!e.closest(f.show.target[0]).length&&this.hide(a),c.toggleClass(_,b)}),q("["+U+"]",X,o)}),y=d.fn.qtip=function(a,b,e){var f=(""+a).toLowerCase(),g=F,i=d.makeArray(arguments).slice(1),j=i[i.length-1],k=this[0]?d.data(this[0],S):F;return!arguments.length&&k||"api"===f?k:"string"==typeof a?(this.each(function(){var a=d.data(this,S);if(!a)return D;if(j&&j.timeStamp&&(a.cache.event=j),!b||"option"!==f&&"options"!==f)a[f]&&a[f].apply(a,i);else{if(e===c&&!d.isPlainObject(b))return g=a.get(b),E;a.set(b,e)}}),g!==F?g:this):"object"!=typeof a&&arguments.length?void 0:(k=h(d.extend(D,{},a)),this.each(function(a){var b,c;return c=d.isArray(k.id)?k.id[a]:k.id,c=!c||c===E||c.length<1||y.api[c]?y.nextid++:c,b=r(d(this),c,k),b===E?D:(y.api[c]=b,d.each(R,function(){"initialize"===this.initialize&&this(b)}),void b._assignInitialEvents(j))}))},d.qtip=e,y.api={},d.each({attr:function(a,b){if(this.length){var c=this[0],e="title",f=d.data(c,"qtip");if(a===e&&f&&f.options&&"object"==typeof f&&"object"==typeof f.options&&f.options.suppress)return arguments.length<2?d.attr(c,ca):(f&&f.options.content.attr===e&&f.cache.attr&&f.set("content.text",b),this.attr(ca,b))}return d.fn["attr"+ba].apply(this,arguments)},clone:function(a){var b=d.fn["clone"+ba].apply(this,arguments);return a||b.filter("["+ca+"]").attr("title",function(){return d.attr(this,ca)}).removeAttr(ca),b}},function(a,b){if(!b||d.fn[a+ba])return D;var c=d.fn[a+ba]=d.fn[a];d.fn[a]=function(){return b.apply(this,arguments)||c.apply(this,arguments)}}),d.ui||(d["cleanData"+ba]=d.cleanData,d.cleanData=function(a){for(var b,c=0;(b=d(a[c])).length;c++)if(b.attr(T))try{b.triggerHandler("removeqtip")}catch(e){}d["cleanData"+ba].apply(this,arguments)}),y.version="3.0.3",y.nextid=0,y.inactiveEvents=X,y.zindex=15e3,y.defaults={prerender:E,id:E,overwrite:D,suppress:D,content:{text:D,attr:"title",title:E,button:E},position:{my:"top left",at:"bottom right",target:E,container:E,viewport:E,adjust:{x:0,y:0,mouse:D,scroll:D,resize:D,method:"flipinvert flipinvert"},effect:function(a,b){d(this).animate(b,{duration:200,queue:E})}},show:{target:E,event:"mouseenter",effect:D,delay:90,solo:E,ready:E,autofocus:E},hide:{target:E,event:"mouseleave",effect:D,delay:0,fixed:E,inactive:E,leave:"window",distance:E},style:{classes:"",widget:E,width:E,height:E,def:D},events:{render:F,move:F,show:F,hide:F,toggle:F,visible:F,hidden:F,focus:F,blur:F}};var ha,ia,ja,ka,la,ma="margin",na="border",oa="color",pa="background-color",qa="transparent",ra=" !important",sa=!!b.createElement("canvas").getContext,ta=/rgba?\(0, 0, 0(, 0)?\)|transparent|#123456/i,ua={},va=["Webkit","O","Moz","ms"];sa?(ka=a.devicePixelRatio||1,la=function(){var a=b.createElement("canvas").getContext("2d");return a.backingStorePixelRatio||a.webkitBackingStorePixelRatio||a.mozBackingStorePixelRatio||a.msBackingStorePixelRatio||a.oBackingStorePixelRatio||1}(),ja=ka/la):ia=function(a,b,c){return"<qtipvml:"+a+' xmlns="urn:schemas-microsoft.com:vml" class="qtip-vml" '+(b||"")+' style="behavior: url(#default#VML); '+(c||"")+'" />'},d.extend(v.prototype,{init:function(a){var b,c;c=this.element=a.elements.tip=d("<div />",{"class":S+"-tip"}).prependTo(a.tooltip),sa?(b=d("<canvas />").appendTo(this.element)[0].getContext("2d"),b.lineJoin="miter",b.miterLimit=1e5,b.save()):(b=ia("shape",'coordorigin="0,0"',"position:absolute;"),this.element.html(b+b),a._bind(d("*",c).add(c),["click","mousedown"],function(a){a.stopPropagation()},this._ns)),a._bind(a.tooltip,"tooltipmove",this.reposition,this._ns,this),this.create()},_swapDimensions:function(){this.size[0]=this.options.height,this.size[1]=this.options.width},_resetDimensions:function(){this.size[0]=this.options.width,this.size[1]=this.options.height},_useTitle:function(a){var b=this.qtip.elements.titlebar;return b&&(a.y===K||a.y===O&&this.element.position().top+this.size[1]/2+this.options.offset<b.outerHeight(D))},_parseCorner:function(a){var b=this.qtip.options.position.my;return a===E||b===E?a=E:a===D?a=new A(b.string()):a.string||(a=new A(a),a.fixed=D),a},_parseWidth:function(a,b,c){var d=this.qtip.elements,e=na+s(b)+"Width";return(c?u(c,e):u(d.content,e)||u(this._useTitle(a)&&d.titlebar||d.content,e)||u(d.tooltip,e))||0},_parseRadius:function(a){var b=this.qtip.elements,c=na+s(a.y)+s(a.x)+"Radius";return da.ie<9?0:u(this._useTitle(a)&&b.titlebar||b.content,c)||u(b.tooltip,c)||0},_invalidColour:function(a,b,c){var d=a.css(b);return!d||c&&d===a.css(c)||ta.test(d)?E:d},_parseColours:function(a){var b=this.qtip.elements,c=this.element.css("cssText",""),e=na+s(a[a.precedance])+s(oa),f=this._useTitle(a)&&b.titlebar||b.content,g=this._invalidColour,h=[];return h[0]=g(c,pa)||g(f,pa)||g(b.content,pa)||g(b.tooltip,pa)||c.css(pa),h[1]=g(c,e,oa)||g(f,e,oa)||g(b.content,e,oa)||g(b.tooltip,e,oa)||b.tooltip.css(e),d("*",c).add(c).css("cssText",pa+":"+qa+ra+";"+na+":0"+ra+";"),h},_calculateSize:function(a){var b,c,d,e=a.precedance===H,f=this.options.width,g=this.options.height,h="c"===a.abbrev(),i=(e?f:g)*(h?.5:1),j=Math.pow,k=Math.round,l=Math.sqrt(j(i,2)+j(g,2)),m=[this.border/i*l,this.border/g*l];return m[2]=Math.sqrt(j(m[0],2)-j(this.border,2)),m[3]=Math.sqrt(j(m[1],2)-j(this.border,2)),b=l+m[2]+m[3]+(h?0:m[0]),c=b/l,d=[k(c*f),k(c*g)],e?d:d.reverse()},_calculateTip:function(a,b,c){c=c||1,b=b||this.size;var d=b[0]*c,e=b[1]*c,f=Math.ceil(d/2),g=Math.ceil(e/2),h={br:[0,0,d,e,d,0],bl:[0,0,d,0,0,e],tr:[0,e,d,0,d,e],tl:[0,0,0,e,d,e],tc:[0,e,f,0,d,e],bc:[0,0,d,0,f,e],rc:[0,0,d,g,0,e],lc:[d,0,d,e,0,g]};return h.lt=h.br,h.rt=h.bl,h.lb=h.tr,h.rb=h.tl,h[a.abbrev()]},_drawCoords:function(a,b){a.beginPath(),a.moveTo(b[0],b[1]),a.lineTo(b[2],b[3]),a.lineTo(b[4],b[5]),a.closePath()},create:function(){var a=this.corner=(sa||da.ie)&&this._parseCorner(this.options.corner);return this.enabled=!!this.corner&&"c"!==this.corner.abbrev(),this.enabled&&(this.qtip.cache.corner=a.clone(),this.update()),this.element.toggle(this.enabled),this.corner},update:function(b,c){if(!this.enabled)return this;var e,f,g,h,i,j,k,l,m=this.qtip.elements,n=this.element,o=n.children(),p=this.options,q=this.size,r=p.mimic,s=Math.round;b||(b=this.qtip.cache.corner||this.corner),r===E?r=b:(r=new A(r),r.precedance=b.precedance,"inherit"===r.x?r.x=b.x:"inherit"===r.y?r.y=b.y:r.x===r.y&&(r[b.precedance]=b[b.precedance])),f=r.precedance,b.precedance===G?this._swapDimensions():this._resetDimensions(),e=this.color=this._parseColours(b),e[1]!==qa?(l=this.border=this._parseWidth(b,b[b.precedance]),p.border&&1>l&&!ta.test(e[1])&&(e[0]=e[1]),this.border=l=p.border!==D?p.border:l):this.border=l=0,k=this.size=this._calculateSize(b),n.css({width:k[0],height:k[1],lineHeight:k[1]+"px"}),j=b.precedance===H?[s(r.x===L?l:r.x===N?k[0]-q[0]-l:(k[0]-q[0])/2),s(r.y===K?k[1]-q[1]:0)]:[s(r.x===L?k[0]-q[0]:0),s(r.y===K?l:r.y===M?k[1]-q[1]-l:(k[1]-q[1])/2)],sa?(g=o[0].getContext("2d"),g.restore(),g.save(),g.clearRect(0,0,6e3,6e3),h=this._calculateTip(r,q,ja),i=this._calculateTip(r,this.size,ja),o.attr(I,k[0]*ja).attr(J,k[1]*ja),o.css(I,k[0]).css(J,k[1]),this._drawCoords(g,i),g.fillStyle=e[1],g.fill(),g.translate(j[0]*ja,j[1]*ja),this._drawCoords(g,h),g.fillStyle=e[0],g.fill()):(h=this._calculateTip(r),h="m"+h[0]+","+h[1]+" l"+h[2]+","+h[3]+" "+h[4]+","+h[5]+" xe",j[2]=l&&/^(r|b)/i.test(b.string())?8===da.ie?2:1:0,o.css({coordsize:k[0]+l+" "+k[1]+l,antialias:""+(r.string().indexOf(O)>-1),left:j[0]-j[2]*Number(f===G),top:j[1]-j[2]*Number(f===H),width:k[0]+l,height:k[1]+l}).each(function(a){var b=d(this);b[b.prop?"prop":"attr"]({coordsize:k[0]+l+" "+k[1]+l,path:h,fillcolor:e[0],filled:!!a,stroked:!a}).toggle(!(!l&&!a)),!a&&b.html(ia("stroke",'weight="'+2*l+'px" color="'+e[1]+'" miterlimit="1000" joinstyle="miter"'))})),a.opera&&setTimeout(function(){m.tip.css({display:"inline-block",visibility:"visible"})},1),c!==E&&this.calculate(b,k)},calculate:function(a,b){if(!this.enabled)return E;var c,e,f=this,g=this.qtip.elements,h=this.element,i=this.options.offset,j={};
return a=a||this.corner,c=a.precedance,b=b||this._calculateSize(a),e=[a.x,a.y],c===G&&e.reverse(),d.each(e,function(d,e){var h,k,l;e===O?(h=c===H?L:K,j[h]="50%",j[ma+"-"+h]=-Math.round(b[c===H?0:1]/2)+i):(h=f._parseWidth(a,e,g.tooltip),k=f._parseWidth(a,e,g.content),l=f._parseRadius(a),j[e]=Math.max(-f.border,d?k:i+(l>h?l:-h)))}),j[a[c]]-=b[c===G?0:1],h.css({margin:"",top:"",bottom:"",left:"",right:""}).css(j),j},reposition:function(a,b,d){function e(a,b,c,d,e){a===Q&&j.precedance===b&&k[d]&&j[c]!==O?j.precedance=j.precedance===G?H:G:a!==Q&&k[d]&&(j[b]=j[b]===O?k[d]>0?d:e:j[b]===d?e:d)}function f(a,b,e){j[a]===O?p[ma+"-"+b]=o[a]=g[ma+"-"+b]-k[b]:(h=g[e]!==c?[k[b],-g[b]]:[-k[b],g[b]],(o[a]=Math.max(h[0],h[1]))>h[0]&&(d[b]-=k[b],o[b]=E),p[g[e]!==c?e:b]=o[a])}if(this.enabled){var g,h,i=b.cache,j=this.corner.clone(),k=d.adjusted,l=b.options.position.adjust.method.split(" "),m=l[0],n=l[1]||l[0],o={left:E,top:E,x:0,y:0},p={};this.corner.fixed!==D&&(e(m,G,H,L,N),e(n,H,G,K,M),j.string()===i.corner.string()&&i.cornerTop===k.top&&i.cornerLeft===k.left||this.update(j,E)),g=this.calculate(j),g.right!==c&&(g.left=-g.right),g.bottom!==c&&(g.top=-g.bottom),g.user=this.offset,o.left=m===Q&&!!k.left,o.left&&f(G,L,N),o.top=n===Q&&!!k.top,o.top&&f(H,K,M),this.element.css(p).toggle(!(o.x&&o.y||j.x===O&&o.y||j.y===O&&o.x)),d.left-=g.left.charAt?g.user:m!==Q||o.top||!o.left&&!o.top?g.left+this.border:0,d.top-=g.top.charAt?g.user:n!==Q||o.left||!o.left&&!o.top?g.top+this.border:0,i.cornerLeft=k.left,i.cornerTop=k.top,i.corner=j.clone()}},destroy:function(){this.qtip._unbind(this.qtip.tooltip,this._ns),this.qtip.elements.tip&&this.qtip.elements.tip.find("*").remove().end().remove()}}),ha=R.tip=function(a){return new v(a,a.options.style.tip)},ha.initialize="render",ha.sanitize=function(a){if(a.style&&"tip"in a.style){var b=a.style.tip;"object"!=typeof b&&(b=a.style.tip={corner:b}),/string|boolean/i.test(typeof b.corner)||(b.corner=D)}},B.tip={"^position.my|style.tip.(corner|mimic|border)$":function(){this.create(),this.qtip.reposition()},"^style.tip.(height|width)$":function(a){this.size=[a.width,a.height],this.update(),this.qtip.reposition()},"^content.title|style.(classes|widget)$":function(){this.update()}},d.extend(D,y.defaults,{style:{tip:{corner:D,mimic:E,width:6,height:6,border:D,offset:0}}});var wa,xa,ya="qtip-modal",za="."+ya;xa=function(){function a(a){if(d.expr[":"].focusable)return d.expr[":"].focusable;var b,c,e,f=!isNaN(d.attr(a,"tabindex")),g=a.nodeName&&a.nodeName.toLowerCase();return"area"===g?(b=a.parentNode,c=b.name,a.href&&c&&"map"===b.nodeName.toLowerCase()?(e=d("img[usemap=#"+c+"]")[0],!!e&&e.is(":visible")):!1):/input|select|textarea|button|object/.test(g)?!a.disabled:"a"===g?a.href||f:f}function c(a){j.length<1&&a.length?a.not("body").blur():j.first().focus()}function e(a){if(h.is(":visible")){var b,e=d(a.target),g=f.tooltip,i=e.closest(W);b=i.length<1?E:parseInt(i[0].style.zIndex,10)>parseInt(g[0].style.zIndex,10),b||e.closest(W)[0]===g[0]||c(e)}}var f,g,h,i=this,j={};d.extend(i,{init:function(){return h=i.elem=d("<div />",{id:"qtip-overlay",html:"<div></div>",mousedown:function(){return E}}).hide(),d(b.body).bind("focusin"+za,e),d(b).bind("keydown"+za,function(a){f&&f.options.show.modal.escape&&27===a.keyCode&&f.hide(a)}),h.bind("click"+za,function(a){f&&f.options.show.modal.blur&&f.hide(a)}),i},update:function(b){f=b,j=b.options.show.modal.stealfocus!==E?b.tooltip.find("*").filter(function(){return a(this)}):[]},toggle:function(a,e,j){var k=a.tooltip,l=a.options.show.modal,m=l.effect,n=e?"show":"hide",o=h.is(":visible"),p=d(za).filter(":visible:not(:animated)").not(k);return i.update(a),e&&l.stealfocus!==E&&c(d(":focus")),h.toggleClass("blurs",l.blur),e&&h.appendTo(b.body),h.is(":animated")&&o===e&&g!==E||!e&&p.length?i:(h.stop(D,E),d.isFunction(m)?m.call(h,e):m===E?h[n]():h.fadeTo(parseInt(j,10)||90,e?1:0,function(){e||h.hide()}),e||h.queue(function(a){h.css({left:"",top:""}),d(za).length||h.detach(),a()}),g=e,f.destroyed&&(f=F),i)}}),i.init()},xa=new xa,d.extend(w.prototype,{init:function(a){var b=a.tooltip;return this.options.on?(a.elements.overlay=xa.elem,b.addClass(ya).css("z-index",y.modal_zindex+d(za).length),a._bind(b,["tooltipshow","tooltiphide"],function(a,c,e){var f=a.originalEvent;if(a.target===b[0])if(f&&"tooltiphide"===a.type&&/mouse(leave|enter)/.test(f.type)&&d(f.relatedTarget).closest(xa.elem[0]).length)try{a.preventDefault()}catch(g){}else(!f||f&&"tooltipsolo"!==f.type)&&this.toggle(a,"tooltipshow"===a.type,e)},this._ns,this),a._bind(b,"tooltipfocus",function(a,c){if(!a.isDefaultPrevented()&&a.target===b[0]){var e=d(za),f=y.modal_zindex+e.length,g=parseInt(b[0].style.zIndex,10);xa.elem[0].style.zIndex=f-1,e.each(function(){this.style.zIndex>g&&(this.style.zIndex-=1)}),e.filter("."+$).qtip("blur",a.originalEvent),b.addClass($)[0].style.zIndex=f,xa.update(c);try{a.preventDefault()}catch(h){}}},this._ns,this),void a._bind(b,"tooltiphide",function(a){a.target===b[0]&&d(za).filter(":visible").not(b).last().qtip("focus",a)},this._ns,this)):this},toggle:function(a,b,c){return a&&a.isDefaultPrevented()?this:void xa.toggle(this.qtip,!!b,c)},destroy:function(){this.qtip.tooltip.removeClass(ya),this.qtip._unbind(this.qtip.tooltip,this._ns),xa.toggle(this.qtip,E),delete this.qtip.elements.overlay}}),wa=R.modal=function(a){return new w(a,a.options.show.modal)},wa.sanitize=function(a){a.show&&("object"!=typeof a.show.modal?a.show.modal={on:!!a.show.modal}:"undefined"==typeof a.show.modal.on&&(a.show.modal.on=D))},y.modal_zindex=y.zindex-200,wa.initialize="render",B.modal={"^show.modal.(on|blur)$":function(){this.destroy(),this.init(),this.qtip.elems.overlay.toggle(this.qtip.tooltip[0].offsetWidth>0)}},d.extend(D,y.defaults,{show:{modal:{on:E,effect:D,blur:D,stealfocus:D,escape:D}}}),R.viewport=function(c,d,e,f,g,h,i){function j(a,b,c,e,f,g,h,i,j){var k=d[f],s=u[a],t=v[a],w=c===Q,x=s===f?j:s===g?-j:-j/2,y=t===f?i:t===g?-i:-i/2,z=q[f]+r[f]-(n?0:m[f]),A=z-k,B=k+j-(h===I?o:p)-z,C=x-(u.precedance===a||s===u[b]?y:0)-(t===O?i/2:0);return w?(C=(s===f?1:-1)*x,d[f]+=A>0?A:B>0?-B:0,d[f]=Math.max(-m[f]+r[f],k-C,Math.min(Math.max(-m[f]+r[f]+(h===I?o:p),k+C),d[f],"center"===s?k-x:1e9))):(e*=c===P?2:0,A>0&&(s!==f||B>0)?(d[f]-=C+e,l.invert(a,f)):B>0&&(s!==g||A>0)&&(d[f]-=(s===O?-C:C)+e,l.invert(a,g)),d[f]<q[f]&&-d[f]>B&&(d[f]=k,l=u.clone())),d[f]-k}var k,l,m,n,o,p,q,r,s=e.target,t=c.elements.tooltip,u=e.my,v=e.at,w=e.adjust,x=w.method.split(" "),y=x[0],z=x[1]||x[0],A=e.viewport,B=e.container,C={left:0,top:0};return A.jquery&&s[0]!==a&&s[0]!==b.body&&"none"!==w.method?(m=B.offset()||C,n="static"===B.css("position"),k="fixed"===t.css("position"),o=A[0]===a?A.width():A.outerWidth(E),p=A[0]===a?A.height():A.outerHeight(E),q={left:k?0:A.scrollLeft(),top:k?0:A.scrollTop()},r=A.offset()||C,"shift"===y&&"shift"===z||(l=u.clone()),C={left:"none"!==y?j(G,H,y,w.x,L,N,I,f,h):0,top:"none"!==z?j(H,G,z,w.y,K,M,J,g,i):0,my:l}):C},R.polys={polygon:function(a,b){var c,d,e,f={width:0,height:0,position:{top:1e10,right:0,bottom:0,left:1e10},adjustable:E},g=0,h=[],i=1,j=1,k=0,l=0;for(g=a.length;g--;)c=[parseInt(a[--g],10),parseInt(a[g+1],10)],c[0]>f.position.right&&(f.position.right=c[0]),c[0]<f.position.left&&(f.position.left=c[0]),c[1]>f.position.bottom&&(f.position.bottom=c[1]),c[1]<f.position.top&&(f.position.top=c[1]),h.push(c);if(d=f.width=Math.abs(f.position.right-f.position.left),e=f.height=Math.abs(f.position.bottom-f.position.top),"c"===b.abbrev())f.position={left:f.position.left+f.width/2,top:f.position.top+f.height/2};else{for(;d>0&&e>0&&i>0&&j>0;)for(d=Math.floor(d/2),e=Math.floor(e/2),b.x===L?i=d:b.x===N?i=f.width-d:i+=Math.floor(d/2),b.y===K?j=e:b.y===M?j=f.height-e:j+=Math.floor(e/2),g=h.length;g--&&!(h.length<2);)k=h[g][0]-f.position.left,l=h[g][1]-f.position.top,(b.x===L&&k>=i||b.x===N&&i>=k||b.x===O&&(i>k||k>f.width-i)||b.y===K&&l>=j||b.y===M&&j>=l||b.y===O&&(j>l||l>f.height-j))&&h.splice(g,1);f.position={left:h[0][0],top:h[0][1]}}return f},rect:function(a,b,c,d){return{width:Math.abs(c-a),height:Math.abs(d-b),position:{left:Math.min(a,c),top:Math.min(b,d)}}},_angles:{tc:1.5,tr:7/4,tl:5/4,bc:.5,br:.25,bl:.75,rc:2,lc:1,c:0},ellipse:function(a,b,c,d,e){var f=R.polys._angles[e.abbrev()],g=0===f?0:c*Math.cos(f*Math.PI),h=d*Math.sin(f*Math.PI);return{width:2*c-Math.abs(g),height:2*d-Math.abs(h),position:{left:a+g,top:b+h},adjustable:E}},circle:function(a,b,c,d){return R.polys.ellipse(a,b,c,c,d)}},R.svg=function(a,c,e){for(var f,g,h,i,j,k,l,m,n,o=c[0],p=d(o.ownerSVGElement),q=o.ownerDocument,r=(parseInt(c.css("stroke-width"),10)||0)/2;!o.getBBox;)o=o.parentNode;if(!o.getBBox||!o.parentNode)return E;switch(o.nodeName){case"ellipse":case"circle":m=R.polys.ellipse(o.cx.baseVal.value,o.cy.baseVal.value,(o.rx||o.r).baseVal.value+r,(o.ry||o.r).baseVal.value+r,e);break;case"line":case"polygon":case"polyline":for(l=o.points||[{x:o.x1.baseVal.value,y:o.y1.baseVal.value},{x:o.x2.baseVal.value,y:o.y2.baseVal.value}],m=[],k=-1,i=l.numberOfItems||l.length;++k<i;)j=l.getItem?l.getItem(k):l[k],m.push.apply(m,[j.x,j.y]);m=R.polys.polygon(m,e);break;default:m=o.getBBox(),m={width:m.width,height:m.height,position:{left:m.x,top:m.y}}}return n=m.position,p=p[0],p.createSVGPoint&&(g=o.getScreenCTM(),l=p.createSVGPoint(),l.x=n.left,l.y=n.top,h=l.matrixTransform(g),n.left=h.x,n.top=h.y),q!==b&&"mouse"!==a.position.target&&(f=d((q.defaultView||q.parentWindow).frameElement).offset(),f&&(n.left+=f.left,n.top+=f.top)),q=d(q),n.left+=q.scrollLeft(),n.top+=q.scrollTop(),m},R.imagemap=function(a,b,c){b.jquery||(b=d(b));var e,f,g,h,i,j=(b.attr("shape")||"rect").toLowerCase().replace("poly","polygon"),k=d('img[usemap="#'+b.parent("map").attr("name")+'"]'),l=d.trim(b.attr("coords")),m=l.replace(/,$/,"").split(",");if(!k.length)return E;if("polygon"===j)h=R.polys.polygon(m,c);else{if(!R.polys[j])return E;for(g=-1,i=m.length,f=[];++g<i;)f.push(parseInt(m[g],10));h=R.polys[j].apply(this,f.concat(c))}return e=k.offset(),e.left+=Math.ceil((k.outerWidth(E)-k.width())/2),e.top+=Math.ceil((k.outerHeight(E)-k.height())/2),h.position.left+=e.left,h.position.top+=e.top,h};var Aa,Ba='<iframe class="qtip-bgiframe" frameborder="0" tabindex="-1" src="javascript:\'\';"  style="display:block; position:absolute; z-index:-1; filter:alpha(opacity=0); -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";"></iframe>';d.extend(x.prototype,{_scroll:function(){var b=this.qtip.elements.overlay;b&&(b[0].style.top=d(a).scrollTop()+"px")},init:function(c){var e=c.tooltip;d("select, object").length<1&&(this.bgiframe=c.elements.bgiframe=d(Ba).appendTo(e),c._bind(e,"tooltipmove",this.adjustBGIFrame,this._ns,this)),this.redrawContainer=d("<div/>",{id:S+"-rcontainer"}).appendTo(b.body),c.elements.overlay&&c.elements.overlay.addClass("qtipmodal-ie6fix")&&(c._bind(a,["scroll","resize"],this._scroll,this._ns,this),c._bind(e,["tooltipshow"],this._scroll,this._ns,this)),this.redraw()},adjustBGIFrame:function(){var a,b,c=this.qtip.tooltip,d={height:c.outerHeight(E),width:c.outerWidth(E)},e=this.qtip.plugins.tip,f=this.qtip.elements.tip;b=parseInt(c.css("borderLeftWidth"),10)||0,b={left:-b,top:-b},e&&f&&(a="x"===e.corner.precedance?[I,L]:[J,K],b[a[1]]-=f[a[0]]()),this.bgiframe.css(b).css(d)},redraw:function(){if(this.qtip.rendered<1||this.drawing)return this;var a,b,c,d,e=this.qtip.tooltip,f=this.qtip.options.style,g=this.qtip.options.position.container;return this.qtip.drawing=1,f.height&&e.css(J,f.height),f.width?e.css(I,f.width):(e.css(I,"").appendTo(this.redrawContainer),b=e.width(),1>b%2&&(b+=1),c=e.css("maxWidth")||"",d=e.css("minWidth")||"",a=(c+d).indexOf("%")>-1?g.width()/100:0,c=(c.indexOf("%")>-1?a:1*parseInt(c,10))||b,d=(d.indexOf("%")>-1?a:1*parseInt(d,10))||0,b=c+d?Math.min(Math.max(b,d),c):b,e.css(I,Math.round(b)).appendTo(g)),this.drawing=0,this},destroy:function(){this.bgiframe&&this.bgiframe.remove(),this.qtip._unbind([a,this.qtip.tooltip],this._ns)}}),Aa=R.ie6=function(a){return 6===da.ie?new x(a):E},Aa.initialize="render",B.ie6={"^content|style$":function(){this.redraw()}}})}(window,document);
/* =============================================================
 * bootstrap3-typeahead.js v3.1.0
 * https://github.com/bassjobsen/Bootstrap-3-Typeahead
 * =============================================================
 * Original written by @mdo and @fat
 * =============================================================
 * Copyright 2014 Bass Jobsen @bassjobsen
 *
 * Licensed under the Apache License, Version 2.0 (the 'License');
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an 'AS IS' BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ============================================================ */


(function (root, factory) {

  'use strict';

  // CommonJS module is defined
  if (typeof module !== 'undefined' && module.exports) {
    module.exports = factory(require('jquery'));
  }
  // AMD module is defined
  else if (typeof define === 'function' && define.amd) {
    define(['jquery'], function ($) {
      return factory ($);
    });
  } else {
    factory(root.jQuery);
  }

}(this, function ($) {

  'use strict';
  // jshint laxcomma: true


 /* TYPEAHEAD PUBLIC CLASS DEFINITION
  * ================================= */

  var Typeahead = function (element, options) {
    this.$element = $(element);
    this.options = $.extend({}, $.fn.typeahead.defaults, options);
    this.matcher = this.options.matcher || this.matcher;
    this.sorter = this.options.sorter || this.sorter;
    this.select = this.options.select || this.select;
    this.autoSelect = typeof this.options.autoSelect == 'boolean' ? this.options.autoSelect : true;
    this.highlighter = this.options.highlighter || this.highlighter;
    this.render = this.options.render || this.render;
    this.updater = this.options.updater || this.updater;
    this.displayText = this.options.displayText || this.displayText;
    this.source = this.options.source;
    this.delay = this.options.delay;
    this.$menu = $(this.options.menu);
    this.$appendTo = this.options.appendTo ? $(this.options.appendTo) : null;
    this.fitToElement = typeof this.options.fitToElement == 'boolean' ? this.options.fitToElement : false;
    this.shown = false;
    this.listen();
    this.showHintOnFocus = typeof this.options.showHintOnFocus == 'boolean' || this.options.showHintOnFocus === "all" ? this.options.showHintOnFocus : false;
    this.afterSelect = this.options.afterSelect;
    this.addItem = false;
    this.value = this.$element.val() || this.$element.text();
  };

  Typeahead.prototype = {

    constructor: Typeahead,

    select: function () {
      var val = this.$menu.find('.active').data('value');
      this.$element.data('active', val);
      if (this.autoSelect || val) {
        var newVal = this.updater(val);
        // Updater can be set to any random functions via "options" parameter in constructor above.
        // Add null check for cases when updater returns void or undefined.
        if (!newVal) {
          newVal = '';
        }
        this.$element
          .val(this.displayText(newVal) || newVal)
          .text(this.displayText(newVal) || newVal)
          .change();
        this.afterSelect(newVal);
      }
      return this.hide();
    },

    updater: function (item) {
      return item;
    },

    setSource: function (source) {
      this.source = source;
    },

    show: function () {
      var pos = $.extend({}, this.$element.position(), {
        height: this.$element[0].offsetHeight
      });

      var scrollHeight = typeof this.options.scrollHeight == 'function' ?
          this.options.scrollHeight.call() :
          this.options.scrollHeight;

      var element;
      if (this.shown) {
        element = this.$menu;
      } else if (this.$appendTo) {
        element = this.$menu.appendTo(this.$appendTo);
        this.hasSameParent = this.$appendTo.is(this.$element.parent());
      } else {
        element = this.$menu.insertAfter(this.$element);
        this.hasSameParent = true;
      }      
      
      if (!this.hasSameParent) {
          // We cannot rely on the element position, need to position relative to the window
          element.css("position", "fixed");
          var offset = this.$element.offset();
          pos.top =  offset.top;
          pos.left = offset.left;
      }
      // The rules for bootstrap are: 'dropup' in the parent and 'dropdown-menu-right' in the element.
      // Note that to get right alignment, you'll need to specify `menu` in the options to be:
      // '<ul class="typeahead dropdown-menu" role="listbox"></ul>'
      var dropup = $(element).parent().hasClass('dropup');
      var newTop = dropup ? 'auto' : (pos.top + pos.height + scrollHeight);
      var right = $(element).hasClass('dropdown-menu-right');
      var newLeft = right ? 'auto' : pos.left;
      // it seems like setting the css is a bad idea (just let Bootstrap do it), but I'll keep the old
      // logic in place except for the dropup/right-align cases.
      element.css({ top: newTop, left: newLeft }).show();

      if (this.options.fitToElement === true) {
          element.css("width", this.$element.outerWidth() + "px");
      }
    
      this.shown = true;
      return this;
    },

    hide: function () {
      this.$menu.hide();
      this.shown = false;
      return this;
    },

    lookup: function (query) {
      var items;
      if (typeof(query) != 'undefined' && query !== null) {
        this.query = query;
      } else {
        this.query = this.$element.val() || this.$element.text() || '';
      }

      if (this.query.length < this.options.minLength && !this.options.showHintOnFocus) {
        return this.shown ? this.hide() : this;
      }

      var worker = $.proxy(function () {

        if ($.isFunction(this.source)) {
          this.source(this.query, $.proxy(this.process, this));
        } else if (this.source) {
          this.process(this.source);
        }
      }, this);

      clearTimeout(this.lookupWorker);
      this.lookupWorker = setTimeout(worker, this.delay);
    },

    process: function (items) {
      var that = this;

      items = $.grep(items, function (item) {
        return that.matcher(item);
      });

      items = this.sorter(items);

      if (!items.length && !this.options.addItem) {
        return this.shown ? this.hide() : this;
      }

      if (items.length > 0) {
        this.$element.data('active', items[0]);
      } else {
        this.$element.data('active', null);
      }

      // Add item
      if (this.options.addItem){
        items.push(this.options.addItem);
      }

      if (this.options.items == 'all') {
        return this.render(items).show();
      } else {
        return this.render(items.slice(0, this.options.items)).show();
      }
    },

    matcher: function (item) {
      var it = this.displayText(item);
      return ~it.indexOf(this.query.toLowerCase());
    },

    sorter: function (items) {
      var beginswith = [];
      var caseSensitive = [];
      var caseInsensitive = [];
      var item;

      while ((item = items.shift())) {
        var it = this.displayText(item);
        if (!it.toLowerCase().indexOf(this.query.toLowerCase())) beginswith.push(item);
        else if (~it.indexOf(this.query)) caseSensitive.push(item);
        else caseInsensitive.push(item);
      }

      return beginswith.concat(caseSensitive, caseInsensitive);
    },

    highlighter: function (item) {
      var html = $('<div></div>');
      var query = this.query;
      var i = item.toLowerCase().indexOf(query.toLowerCase());
      var len = query.length;
      var leftPart;
      var middlePart;
      var rightPart;
      var strong;
      if (len === 0) {
        return html.text(item).html();
      }
      while (i > -1) {
        leftPart = item.substr(0, i);
        middlePart = item.substr(i, len);
        rightPart = item.substr(i + len);
        strong = $('<strong></strong>').text(middlePart);
        html
          .append(document.createTextNode(leftPart))
          .append(strong);
        item = rightPart;
        i = item.toLowerCase().indexOf(query.toLowerCase());
      }
      return html.append(document.createTextNode(item)).html();
    },

    render: function (items) {
      var that = this;
      var self = this;
      var activeFound = false;
      var data = [];
      var _category = that.options.separator;

      $.each(items, function (key,value) {
        // inject separator
        if (key > 0 && value[_category] !== items[key - 1][_category]){
          data.push({
            __type: 'divider'
          });
        }

        // inject category header
        if (value[_category] && (key === 0 || value[_category] !== items[key - 1][_category])){
          data.push({
            __type: 'category',
            name: value[_category]
          });
        }
        data.push(value);
      });

      items = $(data).map(function (i, item) {
        if ((item.__type || false) == 'category'){
          return $(that.options.headerHtml).text(item.name)[0];
        }

        if ((item.__type || false) == 'divider'){
          return $(that.options.headerDivider)[0];
        }

        var text = self.displayText(item);
        i = $(that.options.item).data('value', item);
        i.find('a').html(that.highlighter(text, item));
        if (text == self.$element.val()) {
          i.addClass('active');
          self.$element.data('active', item);
          activeFound = true;
        }
        return i[0];
      });

      if (this.autoSelect && !activeFound) {
        items.filter(':not(.dropdown-header)').first().addClass('active');
        this.$element.data('active', items.first().data('value'));
      }
      this.$menu.html(items);
      return this;
    },

    displayText: function (item) {
      return typeof item !== 'undefined' && typeof item.name != 'undefined' && item.name || item;
    },

    next: function (event) {
      var active = this.$menu.find('.active').removeClass('active');
      var next = active.next();

      if (!next.length) {
        next = $(this.$menu.find('li')[0]);
      }

      next.addClass('active');
    },

    prev: function (event) {
      var active = this.$menu.find('.active').removeClass('active');
      var prev = active.prev();

      if (!prev.length) {
        prev = this.$menu.find('li').last();
      }

      prev.addClass('active');
    },

    listen: function () {
      this.$element
        .on('focus',    $.proxy(this.focus, this))
        .on('blur',     $.proxy(this.blur, this))
        .on('keypress', $.proxy(this.keypress, this))
        .on('input',    $.proxy(this.input, this))
        .on('keyup',    $.proxy(this.keyup, this));

      if (this.eventSupported('keydown')) {
        this.$element.on('keydown', $.proxy(this.keydown, this));
      }

      this.$menu
        .on('click', $.proxy(this.click, this))
        .on('mouseenter', 'li', $.proxy(this.mouseenter, this))
        .on('mouseleave', 'li', $.proxy(this.mouseleave, this))
        .on('mousedown', $.proxy(this.mousedown,this));
    },

    destroy : function () {
      this.$element.data('typeahead',null);
      this.$element.data('active',null);
      this.$element
        .off('focus')
        .off('blur')
        .off('keypress')
        .off('input')
        .off('keyup');

      if (this.eventSupported('keydown')) {
        this.$element.off('keydown');
      }

      this.$menu.remove();
      this.destroyed = true;
    },

    eventSupported: function (eventName) {
      var isSupported = eventName in this.$element;
      if (!isSupported) {
        this.$element.setAttribute(eventName, 'return;');
        isSupported = typeof this.$element[eventName] === 'function';
      }
      return isSupported;
    },

    move: function (e) {
      if (!this.shown) return;

      switch (e.keyCode) {
        case 9: // tab
        case 13: // enter
        case 27: // escape
          e.preventDefault();
          break;

        case 38: // up arrow
          // with the shiftKey (this is actually the left parenthesis)
          if (e.shiftKey) return;
          e.preventDefault();
          this.prev();
          break;

        case 40: // down arrow
          // with the shiftKey (this is actually the right parenthesis)
          if (e.shiftKey) return;
          e.preventDefault();
          this.next();
          break;
      }
    },

    keydown: function (e) {
      this.suppressKeyPressRepeat = ~$.inArray(e.keyCode, [40,38,9,13,27]);
      if (!this.shown && e.keyCode == 40) {
        this.lookup();
      } else {
        this.move(e);
      }
    },

    keypress: function (e) {
      if (this.suppressKeyPressRepeat) return;
      this.move(e);
    },

    input: function (e) {
      // This is a fixed for IE10/11 that fires the input event when a placehoder is changed
      // (https://connect.microsoft.com/IE/feedback/details/810538/ie-11-fires-input-event-on-focus)
      var currentValue = this.$element.val() || this.$element.text();
      if (this.value !== currentValue) {
        this.value = currentValue;
        this.lookup();
      }
    },

    keyup: function (e) {
      if (this.destroyed) {
        return;
      }
      switch (e.keyCode) {
        case 40: // down arrow
        case 38: // up arrow
        case 16: // shift
        case 17: // ctrl
        case 18: // alt
          break;

        case 9: // tab
        case 13: // enter
          if (!this.shown) return;
          this.select();
          break;

        case 27: // escape
          if (!this.shown) return;
          this.hide();
          break;
      }


    },

    focus: function (e) {
      if (!this.focused) {
        this.focused = true;
        if (this.options.showHintOnFocus && this.skipShowHintOnFocus !== true) {
          if(this.options.showHintOnFocus === "all") {
            this.lookup(""); 
          } else {
            this.lookup();
          }
        }
      }
      if (this.skipShowHintOnFocus) {
        this.skipShowHintOnFocus = false;
      }
    },

    blur: function (e) {
      if (!this.mousedover && !this.mouseddown && this.shown) {
        this.hide();
        this.focused = false;
      } else if (this.mouseddown) {
        // This is for IE that blurs the input when user clicks on scroll.
        // We set the focus back on the input and prevent the lookup to occur again
        this.skipShowHintOnFocus = true;
        this.$element.focus();
        this.mouseddown = false;
      } 
    },

    click: function (e) {
      e.preventDefault();
      this.skipShowHintOnFocus = true;
      this.select();
      this.$element.focus();
      this.hide();
    },

    mouseenter: function (e) {
      this.mousedover = true;
      this.$menu.find('.active').removeClass('active');
      $(e.currentTarget).addClass('active');
    },

    mouseleave: function (e) {
      this.mousedover = false;
      if (!this.focused && this.shown) this.hide();
    },

   /**
     * We track the mousedown for IE. When clicking on the menu scrollbar, IE makes the input blur thus hiding the menu.
     */
    mousedown: function (e) {
      this.mouseddown = true;
      this.$menu.one("mouseup", function(e){
        // IE won't fire this, but FF and Chrome will so we reset our flag for them here
        this.mouseddown = false;
      }.bind(this));
    },

  };


  /* TYPEAHEAD PLUGIN DEFINITION
   * =========================== */

  var old = $.fn.typeahead;

  $.fn.typeahead = function (option) {
    var arg = arguments;
    if (typeof option == 'string' && option == 'getActive') {
      return this.data('active');
    }
    return this.each(function () {
      var $this = $(this);
      var data = $this.data('typeahead');
      var options = typeof option == 'object' && option;
      if (!data) $this.data('typeahead', (data = new Typeahead(this, options)));
      if (typeof option == 'string' && data[option]) {
        if (arg.length > 1) {
          data[option].apply(data, Array.prototype.slice.call(arg, 1));
        } else {
          data[option]();
        }
      }
    });
  };

  $.fn.typeahead.defaults = {
    source: [],
    items: 8,
    menu: '<ul class="typeahead dropdown-menu" role="listbox"></ul>',
    item: '<li><a class="dropdown-item" href="#" role="option"></a></li>',
    minLength: 1,
    scrollHeight: 0,
    autoSelect: true,
    afterSelect: $.noop,
    addItem: false,
    delay: 0,
    separator: 'category',
    headerHtml: '<li class="dropdown-header"></li>',
    headerDivider: '<li class="divider" role="separator"></li>'
  };

  $.fn.typeahead.Constructor = Typeahead;

 /* TYPEAHEAD NO CONFLICT
  * =================== */

  $.fn.typeahead.noConflict = function () {
    $.fn.typeahead = old;
    return this;
  };


 /* TYPEAHEAD DATA-API
  * ================== */

  $(document).on('focus.typeahead.data-api', '[data-provide="typeahead"]', function (e) {
    var $this = $(this);
    if ($this.data('typeahead')) return;
    $this.typeahead($this.data());
  });

}));
// Awesomplete - Lea Verou - MIT license
!function(){function t(t){var e=Array.isArray(t)?{label:t[0],value:t[1]}:"object"==typeof t&&"label"in t&&"value"in t?t:{label:t,value:t};this.label=e.label||e.value,this.value=e.value}function e(t,e,i){for(var n in e){var s=e[n],r=t.input.getAttribute("data-"+n.toLowerCase());"number"==typeof s?t[n]=parseInt(r):s===!1?t[n]=null!==r:s instanceof Function?t[n]=null:t[n]=r,t[n]||0===t[n]||(t[n]=n in i?i[n]:s)}}function i(t,e){return"string"==typeof t?(e||document).querySelector(t):t||null}function n(t,e){return o.call((e||document).querySelectorAll(t))}function s(){n("input.awesomplete").forEach(function(t){new r(t)})}var r=function(t,n){var s=this;this.isOpened=!1,this.input=i(t),this.input.setAttribute("autocomplete","off"),this.input.setAttribute("aria-autocomplete","list"),n=n||{},e(this,{minChars:2,maxItems:10,autoFirst:!1,data:r.DATA,filter:r.FILTER_CONTAINS,sort:r.SORT_BYLENGTH,item:r.ITEM,replace:r.REPLACE},n),this.index=-1,this.container=i.create("div",{className:"awesomplete",around:t}),this.ul=i.create("ul",{hidden:"hidden",inside:this.container}),this.status=i.create("span",{className:"visually-hidden",role:"status","aria-live":"assertive","aria-relevant":"additions",inside:this.container}),i.bind(this.input,{input:this.evaluate.bind(this),blur:this.close.bind(this,{reason:"blur"}),keydown:function(t){var e=t.keyCode;s.opened&&(13===e&&s.selected?(t.preventDefault(),s.select()):27===e?s.close({reason:"esc"}):38!==e&&40!==e||(t.preventDefault(),s[38===e?"previous":"next"]()))}}),i.bind(this.input.form,{submit:this.close.bind(this,{reason:"submit"})}),i.bind(this.ul,{mousedown:function(t){var e=t.target;if(e!==this){for(;e&&!/li/i.test(e.nodeName);)e=e.parentNode;e&&0===t.button&&(t.preventDefault(),s.select(e,t.target))}}}),this.input.hasAttribute("list")?(this.list="#"+this.input.getAttribute("list"),this.input.removeAttribute("list")):this.list=this.input.getAttribute("data-list")||n.list||[],r.all.push(this)};r.prototype={set list(t){if(Array.isArray(t))this._list=t;else if("string"==typeof t&&t.indexOf(",")>-1)this._list=t.split(/\s*,\s*/);else if(t=i(t),t&&t.children){var e=[];o.apply(t.children).forEach(function(t){if(!t.disabled){var i=t.textContent.trim(),n=t.value||i,s=t.label||i;""!==n&&e.push({label:s,value:n})}}),this._list=e}document.activeElement===this.input&&this.evaluate()},get selected(){return this.index>-1},get opened(){return this.isOpened},close:function(t){this.opened&&(this.ul.setAttribute("hidden",""),this.isOpened=!1,this.index=-1,i.fire(this.input,"awesomplete-close",t||{}))},open:function(){this.ul.removeAttribute("hidden"),this.isOpened=!0,this.autoFirst&&this.index===-1&&this.goto(0),i.fire(this.input,"awesomplete-open")},next:function(){var t=this.ul.children.length;this.goto(this.index<t-1?this.index+1:t?0:-1)},previous:function(){var t=this.ul.children.length,e=this.index-1;this.goto(this.selected&&e!==-1?e:t-1)},goto:function(t){var e=this.ul.children;this.selected&&e[this.index].setAttribute("aria-selected","false"),this.index=t,t>-1&&e.length>0&&(e[t].setAttribute("aria-selected","true"),this.status.textContent=e[t].textContent,i.fire(this.input,"awesomplete-highlight",{text:this.suggestions[this.index]}))},select:function(t,e){if(t?this.index=i.siblingIndex(t):t=this.ul.children[this.index],t){var n=this.suggestions[this.index],s=i.fire(this.input,"awesomplete-select",{text:n,origin:e||t});s&&(this.replace(n),this.close({reason:"select"}),i.fire(this.input,"awesomplete-selectcomplete",{text:n}))}},evaluate:function(){var e=this,i=this.input.value;i.length>=this.minChars&&this._list.length>0?(this.index=-1,this.ul.innerHTML="",this.suggestions=this._list.map(function(n){return new t(e.data(n,i))}).filter(function(t){return e.filter(t,i)}).sort(this.sort).slice(0,this.maxItems),this.suggestions.forEach(function(t){e.ul.appendChild(e.item(t,i))}),0===this.ul.children.length?this.close({reason:"nomatches"}):this.open()):this.close({reason:"nomatches"})}},r.all=[],r.FILTER_CONTAINS=function(t,e){return RegExp(i.regExpEscape(e.trim()),"i").test(t)},r.FILTER_STARTSWITH=function(t,e){return RegExp("^"+i.regExpEscape(e.trim()),"i").test(t)},r.SORT_BYLENGTH=function(t,e){return t.length!==e.length?t.length-e.length:t<e?-1:1},r.ITEM=function(t,e){var n=""===e?t:t.replace(RegExp(i.regExpEscape(e.trim()),"gi"),"<mark>$&</mark>");return i.create("li",{innerHTML:n,"aria-selected":"false"})},r.REPLACE=function(t){this.input.value=t.value},r.DATA=function(t){return t},Object.defineProperty(t.prototype=Object.create(String.prototype),"length",{get:function(){return this.label.length}}),t.prototype.toString=t.prototype.valueOf=function(){return""+this.label};var o=Array.prototype.slice;return i.create=function(t,e){var n=document.createElement(t);for(var s in e){var r=e[s];if("inside"===s)i(r).appendChild(n);else if("around"===s){var o=i(r);o.parentNode.insertBefore(n,o),n.appendChild(o)}else s in n?n[s]=r:n.setAttribute(s,r)}return n},i.bind=function(t,e){if(t)for(var i in e){var n=e[i];i.split(/\s+/).forEach(function(e){t.addEventListener(e,n)})}},i.fire=function(t,e,i){var n=document.createEvent("HTMLEvents");n.initEvent(e,!0,!0);for(var s in i)n[s]=i[s];return t.dispatchEvent(n)},i.regExpEscape=function(t){return t.replace(/[-\\^$*+?.()|[\]{}]/g,"\\$&")},i.siblingIndex=function(t){for(var e=0;t=t.previousElementSibling;e++);return e},"undefined"!=typeof Document&&("loading"!==document.readyState?s():document.addEventListener("DOMContentLoaded",s)),r.$=i,r.$$=n,"undefined"!=typeof self&&(self.Awesomplete=r),"object"==typeof module&&module.exports&&(module.exports=r),r}();
//# sourceMappingURL=awesomplete.min.js.map

/*!
 * Isotope PACKAGED v3.0.1
 *
 * Licensed GPLv3 for open source use
 * or Isotope Commercial License for commercial use
 *
 * http://isotope.metafizzy.co
 * Copyright 2016 Metafizzy
 */

!function(t,e){"use strict";"function"==typeof define&&define.amd?define("jquery-bridget/jquery-bridget",["jquery"],function(i){e(t,i)}):"object"==typeof module&&module.exports?module.exports=e(t,require("jquery")):t.jQueryBridget=e(t,t.jQuery)}(window,function(t,e){"use strict";function i(i,s,a){function u(t,e,n){var o,s="$()."+i+'("'+e+'")';return t.each(function(t,u){var h=a.data(u,i);if(!h)return void r(i+" not initialized. Cannot call methods, i.e. "+s);var d=h[e];if(!d||"_"==e.charAt(0))return void r(s+" is not a valid method");var l=d.apply(h,n);o=void 0===o?l:o}),void 0!==o?o:t}function h(t,e){t.each(function(t,n){var o=a.data(n,i);o?(o.option(e),o._init()):(o=new s(n,e),a.data(n,i,o))})}a=a||e||t.jQuery,a&&(s.prototype.option||(s.prototype.option=function(t){a.isPlainObject(t)&&(this.options=a.extend(!0,this.options,t))}),a.fn[i]=function(t){if("string"==typeof t){var e=o.call(arguments,1);return u(this,t,e)}return h(this,t),this},n(a))}function n(t){!t||t&&t.bridget||(t.bridget=i)}var o=Array.prototype.slice,s=t.console,r="undefined"==typeof s?function(){}:function(t){s.error(t)};return n(e||t.jQuery),i}),function(t,e){"function"==typeof define&&define.amd?define("ev-emitter/ev-emitter",e):"object"==typeof module&&module.exports?module.exports=e():t.EvEmitter=e()}("undefined"!=typeof window?window:this,function(){function t(){}var e=t.prototype;return e.on=function(t,e){if(t&&e){var i=this._events=this._events||{},n=i[t]=i[t]||[];return-1==n.indexOf(e)&&n.push(e),this}},e.once=function(t,e){if(t&&e){this.on(t,e);var i=this._onceEvents=this._onceEvents||{},n=i[t]=i[t]||{};return n[e]=!0,this}},e.off=function(t,e){var i=this._events&&this._events[t];if(i&&i.length){var n=i.indexOf(e);return-1!=n&&i.splice(n,1),this}},e.emitEvent=function(t,e){var i=this._events&&this._events[t];if(i&&i.length){var n=0,o=i[n];e=e||[];for(var s=this._onceEvents&&this._onceEvents[t];o;){var r=s&&s[o];r&&(this.off(t,o),delete s[o]),o.apply(this,e),n+=r?0:1,o=i[n]}return this}},t}),function(t,e){"use strict";"function"==typeof define&&define.amd?define("get-size/get-size",[],function(){return e()}):"object"==typeof module&&module.exports?module.exports=e():t.getSize=e()}(window,function(){"use strict";function t(t){var e=parseFloat(t),i=-1==t.indexOf("%")&&!isNaN(e);return i&&e}function e(){}function i(){for(var t={width:0,height:0,innerWidth:0,innerHeight:0,outerWidth:0,outerHeight:0},e=0;h>e;e++){var i=u[e];t[i]=0}return t}function n(t){var e=getComputedStyle(t);return e||a("Style returned "+e+". Are you running this code in a hidden iframe on Firefox? See http://bit.ly/getsizebug1"),e}function o(){if(!d){d=!0;var e=document.createElement("div");e.style.width="200px",e.style.padding="1px 2px 3px 4px",e.style.borderStyle="solid",e.style.borderWidth="1px 2px 3px 4px",e.style.boxSizing="border-box";var i=document.body||document.documentElement;i.appendChild(e);var o=n(e);s.isBoxSizeOuter=r=200==t(o.width),i.removeChild(e)}}function s(e){if(o(),"string"==typeof e&&(e=document.querySelector(e)),e&&"object"==typeof e&&e.nodeType){var s=n(e);if("none"==s.display)return i();var a={};a.width=e.offsetWidth,a.height=e.offsetHeight;for(var d=a.isBorderBox="border-box"==s.boxSizing,l=0;h>l;l++){var f=u[l],c=s[f],m=parseFloat(c);a[f]=isNaN(m)?0:m}var p=a.paddingLeft+a.paddingRight,y=a.paddingTop+a.paddingBottom,g=a.marginLeft+a.marginRight,v=a.marginTop+a.marginBottom,_=a.borderLeftWidth+a.borderRightWidth,I=a.borderTopWidth+a.borderBottomWidth,z=d&&r,x=t(s.width);x!==!1&&(a.width=x+(z?0:p+_));var S=t(s.height);return S!==!1&&(a.height=S+(z?0:y+I)),a.innerWidth=a.width-(p+_),a.innerHeight=a.height-(y+I),a.outerWidth=a.width+g,a.outerHeight=a.height+v,a}}var r,a="undefined"==typeof console?e:function(t){console.error(t)},u=["paddingLeft","paddingRight","paddingTop","paddingBottom","marginLeft","marginRight","marginTop","marginBottom","borderLeftWidth","borderRightWidth","borderTopWidth","borderBottomWidth"],h=u.length,d=!1;return s}),function(t,e){"use strict";"function"==typeof define&&define.amd?define("desandro-matches-selector/matches-selector",e):"object"==typeof module&&module.exports?module.exports=e():t.matchesSelector=e()}(window,function(){"use strict";var t=function(){var t=Element.prototype;if(t.matches)return"matches";if(t.matchesSelector)return"matchesSelector";for(var e=["webkit","moz","ms","o"],i=0;i<e.length;i++){var n=e[i],o=n+"MatchesSelector";if(t[o])return o}}();return function(e,i){return e[t](i)}}),function(t,e){"function"==typeof define&&define.amd?define("fizzy-ui-utils/utils",["desandro-matches-selector/matches-selector"],function(i){return e(t,i)}):"object"==typeof module&&module.exports?module.exports=e(t,require("desandro-matches-selector")):t.fizzyUIUtils=e(t,t.matchesSelector)}(window,function(t,e){var i={};i.extend=function(t,e){for(var i in e)t[i]=e[i];return t},i.modulo=function(t,e){return(t%e+e)%e},i.makeArray=function(t){var e=[];if(Array.isArray(t))e=t;else if(t&&"number"==typeof t.length)for(var i=0;i<t.length;i++)e.push(t[i]);else e.push(t);return e},i.removeFrom=function(t,e){var i=t.indexOf(e);-1!=i&&t.splice(i,1)},i.getParent=function(t,i){for(;t!=document.body;)if(t=t.parentNode,e(t,i))return t},i.getQueryElement=function(t){return"string"==typeof t?document.querySelector(t):t},i.handleEvent=function(t){var e="on"+t.type;this[e]&&this[e](t)},i.filterFindElements=function(t,n){t=i.makeArray(t);var o=[];return t.forEach(function(t){if(t instanceof HTMLElement){if(!n)return void o.push(t);e(t,n)&&o.push(t);for(var i=t.querySelectorAll(n),s=0;s<i.length;s++)o.push(i[s])}}),o},i.debounceMethod=function(t,e,i){var n=t.prototype[e],o=e+"Timeout";t.prototype[e]=function(){var t=this[o];t&&clearTimeout(t);var e=arguments,s=this;this[o]=setTimeout(function(){n.apply(s,e),delete s[o]},i||100)}},i.docReady=function(t){var e=document.readyState;"complete"==e||"interactive"==e?t():document.addEventListener("DOMContentLoaded",t)},i.toDashed=function(t){return t.replace(/(.)([A-Z])/g,function(t,e,i){return e+"-"+i}).toLowerCase()};var n=t.console;return i.htmlInit=function(e,o){i.docReady(function(){var s=i.toDashed(o),r="data-"+s,a=document.querySelectorAll("["+r+"]"),u=document.querySelectorAll(".js-"+s),h=i.makeArray(a).concat(i.makeArray(u)),d=r+"-options",l=t.jQuery;h.forEach(function(t){var i,s=t.getAttribute(r)||t.getAttribute(d);try{i=s&&JSON.parse(s)}catch(a){return void(n&&n.error("Error parsing "+r+" on "+t.className+": "+a))}var u=new e(t,i);l&&l.data(t,o,u)})})},i}),function(t,e){"function"==typeof define&&define.amd?define("outlayer/item",["ev-emitter/ev-emitter","get-size/get-size"],e):"object"==typeof module&&module.exports?module.exports=e(require("ev-emitter"),require("get-size")):(t.Outlayer={},t.Outlayer.Item=e(t.EvEmitter,t.getSize))}(window,function(t,e){"use strict";function i(t){for(var e in t)return!1;return e=null,!0}function n(t,e){t&&(this.element=t,this.layout=e,this.position={x:0,y:0},this._create())}function o(t){return t.replace(/([A-Z])/g,function(t){return"-"+t.toLowerCase()})}var s=document.documentElement.style,r="string"==typeof s.transition?"transition":"WebkitTransition",a="string"==typeof s.transform?"transform":"WebkitTransform",u={WebkitTransition:"webkitTransitionEnd",transition:"transitionend"}[r],h={transform:a,transition:r,transitionDuration:r+"Duration",transitionProperty:r+"Property",transitionDelay:r+"Delay"},d=n.prototype=Object.create(t.prototype);d.constructor=n,d._create=function(){this._transn={ingProperties:{},clean:{},onEnd:{}},this.css({position:"absolute"})},d.handleEvent=function(t){var e="on"+t.type;this[e]&&this[e](t)},d.getSize=function(){this.size=e(this.element)},d.css=function(t){var e=this.element.style;for(var i in t){var n=h[i]||i;e[n]=t[i]}},d.getPosition=function(){var t=getComputedStyle(this.element),e=this.layout._getOption("originLeft"),i=this.layout._getOption("originTop"),n=t[e?"left":"right"],o=t[i?"top":"bottom"],s=this.layout.size,r=-1!=n.indexOf("%")?parseFloat(n)/100*s.width:parseInt(n,10),a=-1!=o.indexOf("%")?parseFloat(o)/100*s.height:parseInt(o,10);r=isNaN(r)?0:r,a=isNaN(a)?0:a,r-=e?s.paddingLeft:s.paddingRight,a-=i?s.paddingTop:s.paddingBottom,this.position.x=r,this.position.y=a},d.layoutPosition=function(){var t=this.layout.size,e={},i=this.layout._getOption("originLeft"),n=this.layout._getOption("originTop"),o=i?"paddingLeft":"paddingRight",s=i?"left":"right",r=i?"right":"left",a=this.position.x+t[o];e[s]=this.getXValue(a),e[r]="";var u=n?"paddingTop":"paddingBottom",h=n?"top":"bottom",d=n?"bottom":"top",l=this.position.y+t[u];e[h]=this.getYValue(l),e[d]="",this.css(e),this.emitEvent("layout",[this])},d.getXValue=function(t){var e=this.layout._getOption("horizontal");return this.layout.options.percentPosition&&!e?t/this.layout.size.width*100+"%":t+"px"},d.getYValue=function(t){var e=this.layout._getOption("horizontal");return this.layout.options.percentPosition&&e?t/this.layout.size.height*100+"%":t+"px"},d._transitionTo=function(t,e){this.getPosition();var i=this.position.x,n=this.position.y,o=parseInt(t,10),s=parseInt(e,10),r=o===this.position.x&&s===this.position.y;if(this.setPosition(t,e),r&&!this.isTransitioning)return void this.layoutPosition();var a=t-i,u=e-n,h={};h.transform=this.getTranslate(a,u),this.transition({to:h,onTransitionEnd:{transform:this.layoutPosition},isCleaning:!0})},d.getTranslate=function(t,e){var i=this.layout._getOption("originLeft"),n=this.layout._getOption("originTop");return t=i?t:-t,e=n?e:-e,"translate3d("+t+"px, "+e+"px, 0)"},d.goTo=function(t,e){this.setPosition(t,e),this.layoutPosition()},d.moveTo=d._transitionTo,d.setPosition=function(t,e){this.position.x=parseInt(t,10),this.position.y=parseInt(e,10)},d._nonTransition=function(t){this.css(t.to),t.isCleaning&&this._removeStyles(t.to);for(var e in t.onTransitionEnd)t.onTransitionEnd[e].call(this)},d.transition=function(t){if(!parseFloat(this.layout.options.transitionDuration))return void this._nonTransition(t);var e=this._transn;for(var i in t.onTransitionEnd)e.onEnd[i]=t.onTransitionEnd[i];for(i in t.to)e.ingProperties[i]=!0,t.isCleaning&&(e.clean[i]=!0);if(t.from){this.css(t.from);var n=this.element.offsetHeight;n=null}this.enableTransition(t.to),this.css(t.to),this.isTransitioning=!0};var l="opacity,"+o(a);d.enableTransition=function(){if(!this.isTransitioning){var t=this.layout.options.transitionDuration;t="number"==typeof t?t+"ms":t,this.css({transitionProperty:l,transitionDuration:t,transitionDelay:this.staggerDelay||0}),this.element.addEventListener(u,this,!1)}},d.onwebkitTransitionEnd=function(t){this.ontransitionend(t)},d.onotransitionend=function(t){this.ontransitionend(t)};var f={"-webkit-transform":"transform"};d.ontransitionend=function(t){if(t.target===this.element){var e=this._transn,n=f[t.propertyName]||t.propertyName;if(delete e.ingProperties[n],i(e.ingProperties)&&this.disableTransition(),n in e.clean&&(this.element.style[t.propertyName]="",delete e.clean[n]),n in e.onEnd){var o=e.onEnd[n];o.call(this),delete e.onEnd[n]}this.emitEvent("transitionEnd",[this])}},d.disableTransition=function(){this.removeTransitionStyles(),this.element.removeEventListener(u,this,!1),this.isTransitioning=!1},d._removeStyles=function(t){var e={};for(var i in t)e[i]="";this.css(e)};var c={transitionProperty:"",transitionDuration:"",transitionDelay:""};return d.removeTransitionStyles=function(){this.css(c)},d.stagger=function(t){t=isNaN(t)?0:t,this.staggerDelay=t+"ms"},d.removeElem=function(){this.element.parentNode.removeChild(this.element),this.css({display:""}),this.emitEvent("remove",[this])},d.remove=function(){return r&&parseFloat(this.layout.options.transitionDuration)?(this.once("transitionEnd",function(){this.removeElem()}),void this.hide()):void this.removeElem()},d.reveal=function(){delete this.isHidden,this.css({display:""});var t=this.layout.options,e={},i=this.getHideRevealTransitionEndProperty("visibleStyle");e[i]=this.onRevealTransitionEnd,this.transition({from:t.hiddenStyle,to:t.visibleStyle,isCleaning:!0,onTransitionEnd:e})},d.onRevealTransitionEnd=function(){this.isHidden||this.emitEvent("reveal")},d.getHideRevealTransitionEndProperty=function(t){var e=this.layout.options[t];if(e.opacity)return"opacity";for(var i in e)return i},d.hide=function(){this.isHidden=!0,this.css({display:""});var t=this.layout.options,e={},i=this.getHideRevealTransitionEndProperty("hiddenStyle");e[i]=this.onHideTransitionEnd,this.transition({from:t.visibleStyle,to:t.hiddenStyle,isCleaning:!0,onTransitionEnd:e})},d.onHideTransitionEnd=function(){this.isHidden&&(this.css({display:"none"}),this.emitEvent("hide"))},d.destroy=function(){this.css({position:"",left:"",right:"",top:"",bottom:"",transition:"",transform:""})},n}),function(t,e){"use strict";"function"==typeof define&&define.amd?define("outlayer/outlayer",["ev-emitter/ev-emitter","get-size/get-size","fizzy-ui-utils/utils","./item"],function(i,n,o,s){return e(t,i,n,o,s)}):"object"==typeof module&&module.exports?module.exports=e(t,require("ev-emitter"),require("get-size"),require("fizzy-ui-utils"),require("./item")):t.Outlayer=e(t,t.EvEmitter,t.getSize,t.fizzyUIUtils,t.Outlayer.Item)}(window,function(t,e,i,n,o){"use strict";function s(t,e){var i=n.getQueryElement(t);if(!i)return void(u&&u.error("Bad element for "+this.constructor.namespace+": "+(i||t)));this.element=i,h&&(this.$element=h(this.element)),this.options=n.extend({},this.constructor.defaults),this.option(e);var o=++l;this.element.outlayerGUID=o,f[o]=this,this._create();var s=this._getOption("initLayout");s&&this.layout()}function r(t){function e(){t.apply(this,arguments)}return e.prototype=Object.create(t.prototype),e.prototype.constructor=e,e}function a(t){if("number"==typeof t)return t;var e=t.match(/(^\d*\.?\d*)(\w*)/),i=e&&e[1],n=e&&e[2];if(!i.length)return 0;i=parseFloat(i);var o=m[n]||1;return i*o}var u=t.console,h=t.jQuery,d=function(){},l=0,f={};s.namespace="outlayer",s.Item=o,s.defaults={containerStyle:{position:"relative"},initLayout:!0,originLeft:!0,originTop:!0,resize:!0,resizeContainer:!0,transitionDuration:"0.4s",hiddenStyle:{opacity:0,transform:"scale(0.001)"},visibleStyle:{opacity:1,transform:"scale(1)"}};var c=s.prototype;n.extend(c,e.prototype),c.option=function(t){n.extend(this.options,t)},c._getOption=function(t){var e=this.constructor.compatOptions[t];return e&&void 0!==this.options[e]?this.options[e]:this.options[t]},s.compatOptions={initLayout:"isInitLayout",horizontal:"isHorizontal",layoutInstant:"isLayoutInstant",originLeft:"isOriginLeft",originTop:"isOriginTop",resize:"isResizeBound",resizeContainer:"isResizingContainer"},c._create=function(){this.reloadItems(),this.stamps=[],this.stamp(this.options.stamp),n.extend(this.element.style,this.options.containerStyle);var t=this._getOption("resize");t&&this.bindResize()},c.reloadItems=function(){this.items=this._itemize(this.element.children)},c._itemize=function(t){for(var e=this._filterFindItemElements(t),i=this.constructor.Item,n=[],o=0;o<e.length;o++){var s=e[o],r=new i(s,this);n.push(r)}return n},c._filterFindItemElements=function(t){return n.filterFindElements(t,this.options.itemSelector)},c.getItemElements=function(){return this.items.map(function(t){return t.element})},c.layout=function(){this._resetLayout(),this._manageStamps();var t=this._getOption("layoutInstant"),e=void 0!==t?t:!this._isLayoutInited;this.layoutItems(this.items,e),this._isLayoutInited=!0},c._init=c.layout,c._resetLayout=function(){this.getSize()},c.getSize=function(){this.size=i(this.element)},c._getMeasurement=function(t,e){var n,o=this.options[t];o?("string"==typeof o?n=this.element.querySelector(o):o instanceof HTMLElement&&(n=o),this[t]=n?i(n)[e]:o):this[t]=0},c.layoutItems=function(t,e){t=this._getItemsForLayout(t),this._layoutItems(t,e),this._postLayout()},c._getItemsForLayout=function(t){return t.filter(function(t){return!t.isIgnored})},c._layoutItems=function(t,e){if(this._emitCompleteOnItems("layout",t),t&&t.length){var i=[];t.forEach(function(t){var n=this._getItemLayoutPosition(t);n.item=t,n.isInstant=e||t.isLayoutInstant,i.push(n)},this),this._processLayoutQueue(i)}},c._getItemLayoutPosition=function(){return{x:0,y:0}},c._processLayoutQueue=function(t){this.updateStagger(),t.forEach(function(t,e){this._positionItem(t.item,t.x,t.y,t.isInstant,e)},this)},c.updateStagger=function(){var t=this.options.stagger;return null===t||void 0===t?void(this.stagger=0):(this.stagger=a(t),this.stagger)},c._positionItem=function(t,e,i,n,o){n?t.goTo(e,i):(t.stagger(o*this.stagger),t.moveTo(e,i))},c._postLayout=function(){this.resizeContainer()},c.resizeContainer=function(){var t=this._getOption("resizeContainer");if(t){var e=this._getContainerSize();e&&(this._setContainerMeasure(e.width,!0),this._setContainerMeasure(e.height,!1))}},c._getContainerSize=d,c._setContainerMeasure=function(t,e){if(void 0!==t){var i=this.size;i.isBorderBox&&(t+=e?i.paddingLeft+i.paddingRight+i.borderLeftWidth+i.borderRightWidth:i.paddingBottom+i.paddingTop+i.borderTopWidth+i.borderBottomWidth),t=Math.max(t,0),this.element.style[e?"width":"height"]=t+"px"}},c._emitCompleteOnItems=function(t,e){function i(){o.dispatchEvent(t+"Complete",null,[e])}function n(){r++,r==s&&i()}var o=this,s=e.length;if(!e||!s)return void i();var r=0;e.forEach(function(e){e.once(t,n)})},c.dispatchEvent=function(t,e,i){var n=e?[e].concat(i):i;if(this.emitEvent(t,n),h)if(this.$element=this.$element||h(this.element),e){var o=h.Event(e);o.type=t,this.$element.trigger(o,i)}else this.$element.trigger(t,i)},c.ignore=function(t){var e=this.getItem(t);e&&(e.isIgnored=!0)},c.unignore=function(t){var e=this.getItem(t);e&&delete e.isIgnored},c.stamp=function(t){t=this._find(t),t&&(this.stamps=this.stamps.concat(t),t.forEach(this.ignore,this))},c.unstamp=function(t){t=this._find(t),t&&t.forEach(function(t){n.removeFrom(this.stamps,t),this.unignore(t)},this)},c._find=function(t){return t?("string"==typeof t&&(t=this.element.querySelectorAll(t)),t=n.makeArray(t)):void 0},c._manageStamps=function(){this.stamps&&this.stamps.length&&(this._getBoundingRect(),this.stamps.forEach(this._manageStamp,this))},c._getBoundingRect=function(){var t=this.element.getBoundingClientRect(),e=this.size;this._boundingRect={left:t.left+e.paddingLeft+e.borderLeftWidth,top:t.top+e.paddingTop+e.borderTopWidth,right:t.right-(e.paddingRight+e.borderRightWidth),bottom:t.bottom-(e.paddingBottom+e.borderBottomWidth)}},c._manageStamp=d,c._getElementOffset=function(t){var e=t.getBoundingClientRect(),n=this._boundingRect,o=i(t),s={left:e.left-n.left-o.marginLeft,top:e.top-n.top-o.marginTop,right:n.right-e.right-o.marginRight,bottom:n.bottom-e.bottom-o.marginBottom};return s},c.handleEvent=n.handleEvent,c.bindResize=function(){t.addEventListener("resize",this),this.isResizeBound=!0},c.unbindResize=function(){t.removeEventListener("resize",this),this.isResizeBound=!1},c.onresize=function(){this.resize()},n.debounceMethod(s,"onresize",100),c.resize=function(){this.isResizeBound&&this.needsResizeLayout()&&this.layout()},c.needsResizeLayout=function(){var t=i(this.element),e=this.size&&t;return e&&t.innerWidth!==this.size.innerWidth},c.addItems=function(t){var e=this._itemize(t);return e.length&&(this.items=this.items.concat(e)),e},c.appended=function(t){var e=this.addItems(t);e.length&&(this.layoutItems(e,!0),this.reveal(e))},c.prepended=function(t){var e=this._itemize(t);if(e.length){var i=this.items.slice(0);this.items=e.concat(i),this._resetLayout(),this._manageStamps(),this.layoutItems(e,!0),this.reveal(e),this.layoutItems(i)}},c.reveal=function(t){if(this._emitCompleteOnItems("reveal",t),t&&t.length){var e=this.updateStagger();t.forEach(function(t,i){t.stagger(i*e),t.reveal()})}},c.hide=function(t){if(this._emitCompleteOnItems("hide",t),t&&t.length){var e=this.updateStagger();t.forEach(function(t,i){t.stagger(i*e),t.hide()})}},c.revealItemElements=function(t){var e=this.getItems(t);this.reveal(e)},c.hideItemElements=function(t){var e=this.getItems(t);this.hide(e)},c.getItem=function(t){for(var e=0;e<this.items.length;e++){var i=this.items[e];if(i.element==t)return i}},c.getItems=function(t){t=n.makeArray(t);var e=[];return t.forEach(function(t){var i=this.getItem(t);i&&e.push(i)},this),e},c.remove=function(t){var e=this.getItems(t);this._emitCompleteOnItems("remove",e),e&&e.length&&e.forEach(function(t){t.remove(),n.removeFrom(this.items,t)},this)},c.destroy=function(){var t=this.element.style;t.height="",t.position="",t.width="",this.items.forEach(function(t){t.destroy()}),this.unbindResize();var e=this.element.outlayerGUID;delete f[e],delete this.element.outlayerGUID,h&&h.removeData(this.element,this.constructor.namespace)},s.data=function(t){t=n.getQueryElement(t);var e=t&&t.outlayerGUID;return e&&f[e]},s.create=function(t,e){var i=r(s);return i.defaults=n.extend({},s.defaults),n.extend(i.defaults,e),i.compatOptions=n.extend({},s.compatOptions),i.namespace=t,i.data=s.data,i.Item=r(o),n.htmlInit(i,t),h&&h.bridget&&h.bridget(t,i),i};var m={ms:1,s:1e3};return s.Item=o,s}),function(t,e){"function"==typeof define&&define.amd?define("isotope/js/item",["outlayer/outlayer"],e):"object"==typeof module&&module.exports?module.exports=e(require("outlayer")):(t.Isotope=t.Isotope||{},t.Isotope.Item=e(t.Outlayer))}(window,function(t){"use strict";function e(){t.Item.apply(this,arguments)}var i=e.prototype=Object.create(t.Item.prototype),n=i._create;i._create=function(){this.id=this.layout.itemGUID++,n.call(this),this.sortData={}},i.updateSortData=function(){if(!this.isIgnored){this.sortData.id=this.id,this.sortData["original-order"]=this.id,this.sortData.random=Math.random();var t=this.layout.options.getSortData,e=this.layout._sorters;for(var i in t){var n=e[i];this.sortData[i]=n(this.element,this)}}};var o=i.destroy;return i.destroy=function(){o.apply(this,arguments),this.css({display:""})},e}),function(t,e){"function"==typeof define&&define.amd?define("isotope/js/layout-mode",["get-size/get-size","outlayer/outlayer"],e):"object"==typeof module&&module.exports?module.exports=e(require("get-size"),require("outlayer")):(t.Isotope=t.Isotope||{},t.Isotope.LayoutMode=e(t.getSize,t.Outlayer))}(window,function(t,e){"use strict";function i(t){this.isotope=t,t&&(this.options=t.options[this.namespace],this.element=t.element,this.items=t.filteredItems,this.size=t.size)}var n=i.prototype,o=["_resetLayout","_getItemLayoutPosition","_manageStamp","_getContainerSize","_getElementOffset","needsResizeLayout","_getOption"];return o.forEach(function(t){n[t]=function(){return e.prototype[t].apply(this.isotope,arguments)}}),n.needsVerticalResizeLayout=function(){var e=t(this.isotope.element),i=this.isotope.size&&e;return i&&e.innerHeight!=this.isotope.size.innerHeight},n._getMeasurement=function(){this.isotope._getMeasurement.apply(this,arguments)},n.getColumnWidth=function(){this.getSegmentSize("column","Width")},n.getRowHeight=function(){this.getSegmentSize("row","Height")},n.getSegmentSize=function(t,e){var i=t+e,n="outer"+e;if(this._getMeasurement(i,n),!this[i]){var o=this.getFirstItemSize();this[i]=o&&o[n]||this.isotope.size["inner"+e]}},n.getFirstItemSize=function(){var e=this.isotope.filteredItems[0];return e&&e.element&&t(e.element)},n.layout=function(){this.isotope.layout.apply(this.isotope,arguments)},n.getSize=function(){this.isotope.getSize(),this.size=this.isotope.size},i.modes={},i.create=function(t,e){function o(){i.apply(this,arguments)}return o.prototype=Object.create(n),o.prototype.constructor=o,e&&(o.options=e),o.prototype.namespace=t,i.modes[t]=o,o},i}),function(t,e){"function"==typeof define&&define.amd?define("masonry/masonry",["outlayer/outlayer","get-size/get-size"],e):"object"==typeof module&&module.exports?module.exports=e(require("outlayer"),require("get-size")):t.Masonry=e(t.Outlayer,t.getSize)}(window,function(t,e){var i=t.create("masonry");return i.compatOptions.fitWidth="isFitWidth",i.prototype._resetLayout=function(){this.getSize(),this._getMeasurement("columnWidth","outerWidth"),this._getMeasurement("gutter","outerWidth"),this.measureColumns(),this.colYs=[];for(var t=0;t<this.cols;t++)this.colYs.push(0);this.maxY=0},i.prototype.measureColumns=function(){if(this.getContainerWidth(),!this.columnWidth){var t=this.items[0],i=t&&t.element;this.columnWidth=i&&e(i).outerWidth||this.containerWidth}var n=this.columnWidth+=this.gutter,o=this.containerWidth+this.gutter,s=o/n,r=n-o%n,a=r&&1>r?"round":"floor";s=Math[a](s),this.cols=Math.max(s,1)},i.prototype.getContainerWidth=function(){var t=this._getOption("fitWidth"),i=t?this.element.parentNode:this.element,n=e(i);this.containerWidth=n&&n.innerWidth},i.prototype._getItemLayoutPosition=function(t){t.getSize();var e=t.size.outerWidth%this.columnWidth,i=e&&1>e?"round":"ceil",n=Math[i](t.size.outerWidth/this.columnWidth);n=Math.min(n,this.cols);for(var o=this._getColGroup(n),s=Math.min.apply(Math,o),r=o.indexOf(s),a={x:this.columnWidth*r,y:s},u=s+t.size.outerHeight,h=this.cols+1-o.length,d=0;h>d;d++)this.colYs[r+d]=u;return a},i.prototype._getColGroup=function(t){if(2>t)return this.colYs;for(var e=[],i=this.cols+1-t,n=0;i>n;n++){var o=this.colYs.slice(n,n+t);e[n]=Math.max.apply(Math,o)}return e},i.prototype._manageStamp=function(t){var i=e(t),n=this._getElementOffset(t),o=this._getOption("originLeft"),s=o?n.left:n.right,r=s+i.outerWidth,a=Math.floor(s/this.columnWidth);a=Math.max(0,a);var u=Math.floor(r/this.columnWidth);u-=r%this.columnWidth?0:1,u=Math.min(this.cols-1,u);for(var h=this._getOption("originTop"),d=(h?n.top:n.bottom)+i.outerHeight,l=a;u>=l;l++)this.colYs[l]=Math.max(d,this.colYs[l])},i.prototype._getContainerSize=function(){this.maxY=Math.max.apply(Math,this.colYs);var t={height:this.maxY};return this._getOption("fitWidth")&&(t.width=this._getContainerFitWidth()),t},i.prototype._getContainerFitWidth=function(){for(var t=0,e=this.cols;--e&&0===this.colYs[e];)t++;return(this.cols-t)*this.columnWidth-this.gutter},i.prototype.needsResizeLayout=function(){var t=this.containerWidth;return this.getContainerWidth(),t!=this.containerWidth},i}),function(t,e){"function"==typeof define&&define.amd?define("isotope/js/layout-modes/masonry",["../layout-mode","masonry/masonry"],e):"object"==typeof module&&module.exports?module.exports=e(require("../layout-mode"),require("masonry-layout")):e(t.Isotope.LayoutMode,t.Masonry)}(window,function(t,e){"use strict";var i=t.create("masonry"),n=i.prototype,o={_getElementOffset:!0,layout:!0,_getMeasurement:!0};for(var s in e.prototype)o[s]||(n[s]=e.prototype[s]);var r=n.measureColumns;n.measureColumns=function(){this.items=this.isotope.filteredItems,r.call(this)};var a=n._getOption;return n._getOption=function(t){return"fitWidth"==t?void 0!==this.options.isFitWidth?this.options.isFitWidth:this.options.fitWidth:a.apply(this.isotope,arguments)},i}),function(t,e){"function"==typeof define&&define.amd?define("isotope/js/layout-modes/fit-rows",["../layout-mode"],e):"object"==typeof exports?module.exports=e(require("../layout-mode")):e(t.Isotope.LayoutMode)}(window,function(t){"use strict";var e=t.create("fitRows"),i=e.prototype;return i._resetLayout=function(){this.x=0,this.y=0,this.maxY=0,this._getMeasurement("gutter","outerWidth")},i._getItemLayoutPosition=function(t){t.getSize();var e=t.size.outerWidth+this.gutter,i=this.isotope.size.innerWidth+this.gutter;0!==this.x&&e+this.x>i&&(this.x=0,this.y=this.maxY);var n={x:this.x,y:this.y};return this.maxY=Math.max(this.maxY,this.y+t.size.outerHeight),this.x+=e,n},i._getContainerSize=function(){return{height:this.maxY}},e}),function(t,e){"function"==typeof define&&define.amd?define("isotope/js/layout-modes/vertical",["../layout-mode"],e):"object"==typeof module&&module.exports?module.exports=e(require("../layout-mode")):e(t.Isotope.LayoutMode)}(window,function(t){"use strict";var e=t.create("vertical",{horizontalAlignment:0}),i=e.prototype;return i._resetLayout=function(){this.y=0},i._getItemLayoutPosition=function(t){t.getSize();var e=(this.isotope.size.innerWidth-t.size.outerWidth)*this.options.horizontalAlignment,i=this.y;return this.y+=t.size.outerHeight,{x:e,y:i}},i._getContainerSize=function(){return{height:this.y}},e}),function(t,e){"function"==typeof define&&define.amd?define(["outlayer/outlayer","get-size/get-size","desandro-matches-selector/matches-selector","fizzy-ui-utils/utils","isotope/js/item","isotope/js/layout-mode","isotope/js/layout-modes/masonry","isotope/js/layout-modes/fit-rows","isotope/js/layout-modes/vertical"],function(i,n,o,s,r,a){return e(t,i,n,o,s,r,a)}):"object"==typeof module&&module.exports?module.exports=e(t,require("outlayer"),require("get-size"),require("desandro-matches-selector"),require("fizzy-ui-utils"),require("isotope/js/item"),require("isotope/js/layout-mode"),require("isotope/js/layout-modes/masonry"),require("isotope/js/layout-modes/fit-rows"),require("isotope/js/layout-modes/vertical")):t.Isotope=e(t,t.Outlayer,t.getSize,t.matchesSelector,t.fizzyUIUtils,t.Isotope.Item,t.Isotope.LayoutMode)}(window,function(t,e,i,n,o,s,r){function a(t,e){return function(i,n){for(var o=0;o<t.length;o++){var s=t[o],r=i.sortData[s],a=n.sortData[s];if(r>a||a>r){var u=void 0!==e[s]?e[s]:e,h=u?1:-1;return(r>a?1:-1)*h}}return 0}}var u=t.jQuery,h=String.prototype.trim?function(t){return t.trim()}:function(t){return t.replace(/^\s+|\s+$/g,"")},d=e.create("isotope",{layoutMode:"masonry",isJQueryFiltering:!0,sortAscending:!0});d.Item=s,d.LayoutMode=r;var l=d.prototype;l._create=function(){this.itemGUID=0,this._sorters={},this._getSorters(),e.prototype._create.call(this),this.modes={},this.filteredItems=this.items,this.sortHistory=["original-order"];for(var t in r.modes)this._initLayoutMode(t)},l.reloadItems=function(){this.itemGUID=0,e.prototype.reloadItems.call(this)},l._itemize=function(){for(var t=e.prototype._itemize.apply(this,arguments),i=0;i<t.length;i++){var n=t[i];n.id=this.itemGUID++}return this._updateItemsSortData(t),t},l._initLayoutMode=function(t){var e=r.modes[t],i=this.options[t]||{};this.options[t]=e.options?o.extend(e.options,i):i,this.modes[t]=new e(this)},l.layout=function(){return!this._isLayoutInited&&this._getOption("initLayout")?void this.arrange():void this._layout()},l._layout=function(){var t=this._getIsInstant();this._resetLayout(),this._manageStamps(),this.layoutItems(this.filteredItems,t),this._isLayoutInited=!0},l.arrange=function(t){this.option(t),this._getIsInstant();var e=this._filter(this.items);this.filteredItems=e.matches,this._bindArrangeComplete(),this._isInstant?this._noTransition(this._hideReveal,[e]):this._hideReveal(e),this._sort(),this._layout()},l._init=l.arrange,l._hideReveal=function(t){this.reveal(t.needReveal),this.hide(t.needHide)},l._getIsInstant=function(){var t=this._getOption("layoutInstant"),e=void 0!==t?t:!this._isLayoutInited;return this._isInstant=e,e},l._bindArrangeComplete=function(){function t(){e&&i&&n&&o.dispatchEvent("arrangeComplete",null,[o.filteredItems])}var e,i,n,o=this;this.once("layoutComplete",function(){e=!0,t()}),this.once("hideComplete",function(){i=!0,t()}),this.once("revealComplete",function(){n=!0,t()})},l._filter=function(t){var e=this.options.filter;e=e||"*";for(var i=[],n=[],o=[],s=this._getFilterTest(e),r=0;r<t.length;r++){var a=t[r];if(!a.isIgnored){var u=s(a);u&&i.push(a),u&&a.isHidden?n.push(a):u||a.isHidden||o.push(a)}}return{matches:i,needReveal:n,needHide:o}},l._getFilterTest=function(t){return u&&this.options.isJQueryFiltering?function(e){return u(e.element).is(t)}:"function"==typeof t?function(e){return t(e.element)}:function(e){return n(e.element,t)}},l.updateSortData=function(t){var e;t?(t=o.makeArray(t),e=this.getItems(t)):e=this.items,this._getSorters(),this._updateItemsSortData(e)},l._getSorters=function(){var t=this.options.getSortData;for(var e in t){var i=t[e];this._sorters[e]=f(i)}},l._updateItemsSortData=function(t){for(var e=t&&t.length,i=0;e&&e>i;i++){var n=t[i];n.updateSortData()}};var f=function(){function t(t){if("string"!=typeof t)return t;var i=h(t).split(" "),n=i[0],o=n.match(/^\[(.+)\]$/),s=o&&o[1],r=e(s,n),a=d.sortDataParsers[i[1]];
return t=a?function(t){return t&&a(r(t))}:function(t){return t&&r(t)}}function e(t,e){return t?function(e){return e.getAttribute(t)}:function(t){var i=t.querySelector(e);return i&&i.textContent}}return t}();d.sortDataParsers={parseInt:function(t){return parseInt(t,10)},parseFloat:function(t){return parseFloat(t)}},l._sort=function(){var t=this.options.sortBy;if(t){var e=[].concat.apply(t,this.sortHistory),i=a(e,this.options.sortAscending);this.filteredItems.sort(i),t!=this.sortHistory[0]&&this.sortHistory.unshift(t)}},l._mode=function(){var t=this.options.layoutMode,e=this.modes[t];if(!e)throw new Error("No layout mode: "+t);return e.options=this.options[t],e},l._resetLayout=function(){e.prototype._resetLayout.call(this),this._mode()._resetLayout()},l._getItemLayoutPosition=function(t){return this._mode()._getItemLayoutPosition(t)},l._manageStamp=function(t){this._mode()._manageStamp(t)},l._getContainerSize=function(){return this._mode()._getContainerSize()},l.needsResizeLayout=function(){return this._mode().needsResizeLayout()},l.appended=function(t){var e=this.addItems(t);if(e.length){var i=this._filterRevealAdded(e);this.filteredItems=this.filteredItems.concat(i)}},l.prepended=function(t){var e=this._itemize(t);if(e.length){this._resetLayout(),this._manageStamps();var i=this._filterRevealAdded(e);this.layoutItems(this.filteredItems),this.filteredItems=i.concat(this.filteredItems),this.items=e.concat(this.items)}},l._filterRevealAdded=function(t){var e=this._filter(t);return this.hide(e.needHide),this.reveal(e.matches),this.layoutItems(e.matches,!0),e.matches},l.insert=function(t){var e=this.addItems(t);if(e.length){var i,n,o=e.length;for(i=0;o>i;i++)n=e[i],this.element.appendChild(n.element);var s=this._filter(e).matches;for(i=0;o>i;i++)e[i].isLayoutInstant=!0;for(this.arrange(),i=0;o>i;i++)delete e[i].isLayoutInstant;this.reveal(s)}};var c=l.remove;return l.remove=function(t){t=o.makeArray(t);var e=this.getItems(t);c.call(this,t);for(var i=e&&e.length,n=0;i&&i>n;n++){var s=e[n];o.removeFrom(this.filteredItems,s)}},l.shuffle=function(){for(var t=0;t<this.items.length;t++){var e=this.items[t];e.sortData.random=Math.random()}this.options.sortBy="random",this._sort(),this._layout()},l._noTransition=function(t,e){var i=this.options.transitionDuration;this.options.transitionDuration=0;var n=t.apply(this,e);return this.options.transitionDuration=i,n},l.getFilteredItemElements=function(){return this.filteredItems.map(function(t){return t.element})},d});
/*!
 * imagesLoaded PACKAGED v4.1.0
 * JavaScript is all like "You images are done yet or what?"
 * MIT License
 */

!function(t,e){"function"==typeof define&&define.amd?define("ev-emitter/ev-emitter",e):"object"==typeof module&&module.exports?module.exports=e():t.EvEmitter=e()}(this,function(){function t(){}var e=t.prototype;return e.on=function(t,e){if(t&&e){var i=this._events=this._events||{},n=i[t]=i[t]||[];return-1==n.indexOf(e)&&n.push(e),this}},e.once=function(t,e){if(t&&e){this.on(t,e);var i=this._onceEvents=this._onceEvents||{},n=i[t]=i[t]||[];return n[e]=!0,this}},e.off=function(t,e){var i=this._events&&this._events[t];if(i&&i.length){var n=i.indexOf(e);return-1!=n&&i.splice(n,1),this}},e.emitEvent=function(t,e){var i=this._events&&this._events[t];if(i&&i.length){var n=0,o=i[n];e=e||[];for(var r=this._onceEvents&&this._onceEvents[t];o;){var s=r&&r[o];s&&(this.off(t,o),delete r[o]),o.apply(this,e),n+=s?0:1,o=i[n]}return this}},t}),function(t,e){"use strict";"function"==typeof define&&define.amd?define(["ev-emitter/ev-emitter"],function(i){return e(t,i)}):"object"==typeof module&&module.exports?module.exports=e(t,require("ev-emitter")):t.imagesLoaded=e(t,t.EvEmitter)}(window,function(t,e){function i(t,e){for(var i in e)t[i]=e[i];return t}function n(t){var e=[];if(Array.isArray(t))e=t;else if("number"==typeof t.length)for(var i=0;i<t.length;i++)e.push(t[i]);else e.push(t);return e}function o(t,e,r){return this instanceof o?("string"==typeof t&&(t=document.querySelectorAll(t)),this.elements=n(t),this.options=i({},this.options),"function"==typeof e?r=e:i(this.options,e),r&&this.on("always",r),this.getImages(),h&&(this.jqDeferred=new h.Deferred),void setTimeout(function(){this.check()}.bind(this))):new o(t,e,r)}function r(t){this.img=t}function s(t,e){this.url=t,this.element=e,this.img=new Image}var h=t.jQuery,a=t.console;o.prototype=Object.create(e.prototype),o.prototype.options={},o.prototype.getImages=function(){this.images=[],this.elements.forEach(this.addElementImages,this)},o.prototype.addElementImages=function(t){"IMG"==t.nodeName&&this.addImage(t),this.options.background===!0&&this.addElementBackgroundImages(t);var e=t.nodeType;if(e&&d[e]){for(var i=t.querySelectorAll("img"),n=0;n<i.length;n++){var o=i[n];this.addImage(o)}if("string"==typeof this.options.background){var r=t.querySelectorAll(this.options.background);for(n=0;n<r.length;n++){var s=r[n];this.addElementBackgroundImages(s)}}}};var d={1:!0,9:!0,11:!0};return o.prototype.addElementBackgroundImages=function(t){var e=getComputedStyle(t);if(e)for(var i=/url\((['"])?(.*?)\1\)/gi,n=i.exec(e.backgroundImage);null!==n;){var o=n&&n[2];o&&this.addBackground(o,t),n=i.exec(e.backgroundImage)}},o.prototype.addImage=function(t){var e=new r(t);this.images.push(e)},o.prototype.addBackground=function(t,e){var i=new s(t,e);this.images.push(i)},o.prototype.check=function(){function t(t,i,n){setTimeout(function(){e.progress(t,i,n)})}var e=this;return this.progressedCount=0,this.hasAnyBroken=!1,this.images.length?void this.images.forEach(function(e){e.once("progress",t),e.check()}):void this.complete()},o.prototype.progress=function(t,e,i){this.progressedCount++,this.hasAnyBroken=this.hasAnyBroken||!t.isLoaded,this.emitEvent("progress",[this,t,e]),this.jqDeferred&&this.jqDeferred.notify&&this.jqDeferred.notify(this,t),this.progressedCount==this.images.length&&this.complete(),this.options.debug&&a&&a.log("progress: "+i,t,e)},o.prototype.complete=function(){var t=this.hasAnyBroken?"fail":"done";if(this.isComplete=!0,this.emitEvent(t,[this]),this.emitEvent("always",[this]),this.jqDeferred){var e=this.hasAnyBroken?"reject":"resolve";this.jqDeferred[e](this)}},r.prototype=Object.create(e.prototype),r.prototype.check=function(){var t=this.getIsImageComplete();return t?void this.confirm(0!==this.img.naturalWidth,"naturalWidth"):(this.proxyImage=new Image,this.proxyImage.addEventListener("load",this),this.proxyImage.addEventListener("error",this),this.img.addEventListener("load",this),this.img.addEventListener("error",this),void(this.proxyImage.src=this.img.src))},r.prototype.getIsImageComplete=function(){return this.img.complete&&void 0!==this.img.naturalWidth},r.prototype.confirm=function(t,e){this.isLoaded=t,this.emitEvent("progress",[this,this.img,e])},r.prototype.handleEvent=function(t){var e="on"+t.type;this[e]&&this[e](t)},r.prototype.onload=function(){this.confirm(!0,"onload"),this.unbindEvents()},r.prototype.onerror=function(){this.confirm(!1,"onerror"),this.unbindEvents()},r.prototype.unbindEvents=function(){this.proxyImage.removeEventListener("load",this),this.proxyImage.removeEventListener("error",this),this.img.removeEventListener("load",this),this.img.removeEventListener("error",this)},s.prototype=Object.create(r.prototype),s.prototype.check=function(){this.img.addEventListener("load",this),this.img.addEventListener("error",this),this.img.src=this.url;var t=this.getIsImageComplete();t&&(this.confirm(0!==this.img.naturalWidth,"naturalWidth"),this.unbindEvents())},s.prototype.unbindEvents=function(){this.img.removeEventListener("load",this),this.img.removeEventListener("error",this)},s.prototype.confirm=function(t,e){this.isLoaded=t,this.emitEvent("progress",[this,this.element,e])},o.makeJQueryPlugin=function(e){e=e||t.jQuery,e&&(h=e,h.fn.imagesLoaded=function(t,e){var i=new o(this,t,e);return i.jqDeferred.promise(h(this))})},o.makeJQueryPlugin(),o});
/*!
 * Lightbox v2.8.2
 * by Lokesh Dhakar
 *
 * More info:
 * http://lokeshdhakar.com/projects/lightbox2/
 *
 * Copyright 2007, 2015 Lokesh Dhakar
 * Released under the MIT license
 * https://github.com/lokesh/lightbox2/blob/master/LICENSE
 */

// Uses Node, AMD or browser globals to create a module.
(function (root, factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(['jquery'], factory);
    } else if (typeof exports === 'object') {
        // Node. Does not work with strict CommonJS, but
        // only CommonJS-like environments that support module.exports,
        // like Node.
        module.exports = factory(require('jquery'));
    } else {
        // Browser globals (root is window)
        root.lightbox = factory(root.jQuery);
    }
}(this, function ($) {

  function Lightbox(options) {
    this.album = [];
    this.currentImageIndex = void 0;
    this.init();

    // options
    this.options = $.extend({}, this.constructor.defaults);
    this.option(options);
  }

  // Descriptions of all options available on the demo site:
  // http://lokeshdhakar.com/projects/lightbox2/index.html#options
  Lightbox.defaults = {
    albumLabel: 'Image %1 of %2',
    alwaysShowNavOnTouchDevices: false,
    fadeDuration: 500,
    fitImagesInViewport: true,
    // maxWidth: 800,
    // maxHeight: 600,
    positionFromTop: 50,
    resizeDuration: 700,
    showImageNumberLabel: true,
    wrapAround: false,
    disableScrolling: false
  };

  Lightbox.prototype.option = function(options) {
    $.extend(this.options, options);
  };

  Lightbox.prototype.imageCountLabel = function(currentImageNum, totalImages) {
    return this.options.albumLabel.replace(/%1/g, currentImageNum).replace(/%2/g, totalImages);
  };

  Lightbox.prototype.init = function() {
    this.enable();
    this.build();
  };

  // Loop through anchors and areamaps looking for either data-lightbox attributes or rel attributes
  // that contain 'lightbox'. When these are clicked, start lightbox.
  Lightbox.prototype.enable = function() {
    var self = this;
    $('body').on('click', 'a[rel^=lightbox], area[rel^=lightbox], a[data-lightbox], area[data-lightbox]', function(event) {
      self.start($(event.currentTarget));
      return false;
    });
  };

  // Build html for the lightbox and the overlay.
  // Attach event handlers to the new DOM elements. click click click
  Lightbox.prototype.build = function() {
    var self = this;
    $('<div id="lightboxOverlay" class="lightboxOverlay"></div><div id="lightbox" class="lightbox"><div class="lb-outerContainer"><div class="lb-container"><img class="lb-image" src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" /><div class="lb-nav"><a class="lb-prev" href="" ></a><a class="lb-next" href="" ></a></div><div class="lb-loader"><a class="lb-cancel"></a></div></div></div><div class="lb-dataContainer"><div class="lb-data"><div class="lb-details"><span class="lb-caption"></span><span class="lb-number"></span></div><div class="lb-closeContainer"><a class="lb-close"></a></div></div></div></div>').appendTo($('body'));

    // Cache jQuery objects
    this.$lightbox       = $('#lightbox');
    this.$overlay        = $('#lightboxOverlay');
    this.$outerContainer = this.$lightbox.find('.lb-outerContainer');
    this.$container      = this.$lightbox.find('.lb-container');

    // Store css values for future lookup
    this.containerTopPadding = parseInt(this.$container.css('padding-top'), 10);
    this.containerRightPadding = parseInt(this.$container.css('padding-right'), 10);
    this.containerBottomPadding = parseInt(this.$container.css('padding-bottom'), 10);
    this.containerLeftPadding = parseInt(this.$container.css('padding-left'), 10);

    // Attach event handlers to the newly minted DOM elements
    this.$overlay.hide().on('click', function() {
      self.end();
      return false;
    });

    this.$lightbox.hide().on('click', function(event) {
      if ($(event.target).attr('id') === 'lightbox') {
        self.end();
      }
      return false;
    });

    this.$outerContainer.on('click', function(event) {
      if ($(event.target).attr('id') === 'lightbox') {
        self.end();
      }
      return false;
    });

    this.$lightbox.find('.lb-prev').on('click', function() {
      if (self.currentImageIndex === 0) {
        self.changeImage(self.album.length - 1);
      } else {
        self.changeImage(self.currentImageIndex - 1);
      }
      return false;
    });

    this.$lightbox.find('.lb-next').on('click', function() {
      if (self.currentImageIndex === self.album.length - 1) {
        self.changeImage(0);
      } else {
        self.changeImage(self.currentImageIndex + 1);
      }
      return false;
    });

    this.$lightbox.find('.lb-loader, .lb-close').on('click', function() {
      self.end();
      return false;
    });
  };

  // Show overlay and lightbox. If the image is part of a set, add siblings to album array.
  Lightbox.prototype.start = function($link) {
    var self    = this;
    var $window = $(window);

    $window.on('resize', $.proxy(this.sizeOverlay, this));

    $('select, object, embed').css({
      visibility: 'hidden'
    });

    this.sizeOverlay();

    this.album = [];
    var imageNumber = 0;

    function addToAlbum($link) {
      self.album.push({
        link: $link.attr('href'),
        title: $link.attr('data-title') || $link.attr('title')
      });
    }

    // Support both data-lightbox attribute and rel attribute implementations
    var dataLightboxValue = $link.attr('data-lightbox');
    var $links;

    if (dataLightboxValue) {
      $links = $($link.prop('tagName') + '[data-lightbox="' + dataLightboxValue + '"]');
      for (var i = 0; i < $links.length; i = ++i) {
        addToAlbum($($links[i]));
        if ($links[i] === $link[0]) {
          imageNumber = i;
        }
      }
    } else {
      if ($link.attr('rel') === 'lightbox') {
        // If image is not part of a set
        addToAlbum($link);
      } else {
        // If image is part of a set
        $links = $($link.prop('tagName') + '[rel="' + $link.attr('rel') + '"]');
        for (var j = 0; j < $links.length; j = ++j) {
          addToAlbum($($links[j]));
          if ($links[j] === $link[0]) {
            imageNumber = j;
          }
        }
      }
    }

    // Position Lightbox
    var top  = $window.scrollTop() + this.options.positionFromTop;
    var left = $window.scrollLeft();
    this.$lightbox.css({
      top: top + 'px',
      left: left + 'px'
    }).fadeIn(this.options.fadeDuration);

    // Disable scrolling of the page while open
    if (this.options.disableScrolling) {
      $('body').addClass('lb-disable-scrolling');
    }

    this.changeImage(imageNumber);
  };

  // Hide most UI elements in preparation for the animated resizing of the lightbox.
  Lightbox.prototype.changeImage = function(imageNumber) {
    var self = this;

    this.disableKeyboardNav();
    var $image = this.$lightbox.find('.lb-image');

    this.$overlay.fadeIn(this.options.fadeDuration);

    $('.lb-loader').fadeIn('slow');
    this.$lightbox.find('.lb-image, .lb-nav, .lb-prev, .lb-next, .lb-dataContainer, .lb-numbers, .lb-caption').hide();

    this.$outerContainer.addClass('animating');

    // When image to show is preloaded, we send the width and height to sizeContainer()
    var preloader = new Image();
    preloader.onload = function() {
      var $preloader;
      var imageHeight;
      var imageWidth;
      var maxImageHeight;
      var maxImageWidth;
      var windowHeight;
      var windowWidth;

      $image.attr('src', self.album[imageNumber].link);

      $preloader = $(preloader);

      $image.width(preloader.width);
      $image.height(preloader.height);

      if (self.options.fitImagesInViewport) {
        // Fit image inside the viewport.
        // Take into account the border around the image and an additional 10px gutter on each side.

        windowWidth    = $(window).width();
        windowHeight   = $(window).height();
        maxImageWidth  = windowWidth - self.containerLeftPadding - self.containerRightPadding - 20;
        maxImageHeight = windowHeight - self.containerTopPadding - self.containerBottomPadding - 120;

        // Check if image size is larger then maxWidth|maxHeight in settings
        if (self.options.maxWidth && self.options.maxWidth < maxImageWidth) {
          maxImageWidth = self.options.maxWidth;
        }
        if (self.options.maxHeight && self.options.maxHeight < maxImageWidth) {
          maxImageHeight = self.options.maxHeight;
        }

        // Is there a fitting issue?
        if ((preloader.width > maxImageWidth) || (preloader.height > maxImageHeight)) {
          if ((preloader.width / maxImageWidth) > (preloader.height / maxImageHeight)) {
            imageWidth  = maxImageWidth;
            imageHeight = parseInt(preloader.height / (preloader.width / imageWidth), 10);
            $image.width(imageWidth);
            $image.height(imageHeight);
          } else {
            imageHeight = maxImageHeight;
            imageWidth = parseInt(preloader.width / (preloader.height / imageHeight), 10);
            $image.width(imageWidth);
            $image.height(imageHeight);
          }
        }
      }
      self.sizeContainer($image.width(), $image.height());
    };

    preloader.src          = this.album[imageNumber].link;
    this.currentImageIndex = imageNumber;
  };

  // Stretch overlay to fit the viewport
  Lightbox.prototype.sizeOverlay = function() {
    this.$overlay
      .width($(document).width())
      .height($(document).height());
  };

  // Animate the size of the lightbox to fit the image we are showing
  Lightbox.prototype.sizeContainer = function(imageWidth, imageHeight) {
    var self = this;

    var oldWidth  = this.$outerContainer.outerWidth();
    var oldHeight = this.$outerContainer.outerHeight();
    var newWidth  = imageWidth + this.containerLeftPadding + this.containerRightPadding;
    var newHeight = imageHeight + this.containerTopPadding + this.containerBottomPadding;

    function postResize() {
      self.$lightbox.find('.lb-dataContainer').width(newWidth);
      self.$lightbox.find('.lb-prevLink').height(newHeight);
      self.$lightbox.find('.lb-nextLink').height(newHeight);
      self.showImage();
    }

    if (oldWidth !== newWidth || oldHeight !== newHeight) {
      this.$outerContainer.animate({
        width: newWidth,
        height: newHeight
      }, this.options.resizeDuration, 'swing', function() {
        postResize();
      });
    } else {
      postResize();
    }
  };

  // Display the image and its details and begin preload neighboring images.
  Lightbox.prototype.showImage = function() {
    this.$lightbox.find('.lb-loader').stop(true).hide();
    this.$lightbox.find('.lb-image').fadeIn('slow');

    this.updateNav();
    this.updateDetails();
    this.preloadNeighboringImages();
    this.enableKeyboardNav();
  };

  // Display previous and next navigation if appropriate.
  Lightbox.prototype.updateNav = function() {
    // Check to see if the browser supports touch events. If so, we take the conservative approach
    // and assume that mouse hover events are not supported and always show prev/next navigation
    // arrows in image sets.
    var alwaysShowNav = false;
    try {
      document.createEvent('TouchEvent');
      alwaysShowNav = (this.options.alwaysShowNavOnTouchDevices) ? true : false;
    } catch (e) {}

    this.$lightbox.find('.lb-nav').show();

    if (this.album.length > 1) {
      if (this.options.wrapAround) {
        if (alwaysShowNav) {
          this.$lightbox.find('.lb-prev, .lb-next').css('opacity', '1');
        }
        this.$lightbox.find('.lb-prev, .lb-next').show();
      } else {
        if (this.currentImageIndex > 0) {
          this.$lightbox.find('.lb-prev').show();
          if (alwaysShowNav) {
            this.$lightbox.find('.lb-prev').css('opacity', '1');
          }
        }
        if (this.currentImageIndex < this.album.length - 1) {
          this.$lightbox.find('.lb-next').show();
          if (alwaysShowNav) {
            this.$lightbox.find('.lb-next').css('opacity', '1');
          }
        }
      }
    }
  };

  // Display caption, image number, and closing button.
  Lightbox.prototype.updateDetails = function() {
    var self = this;

    // Enable anchor clicks in the injected caption html.
    // Thanks Nate Wright for the fix. @https://github.com/NateWr
    if (typeof this.album[this.currentImageIndex].title !== 'undefined' &&
      this.album[this.currentImageIndex].title !== '') {
      this.$lightbox.find('.lb-caption')
        .html(this.album[this.currentImageIndex].title)
        .fadeIn('fast')
        .find('a').on('click', function(event) {
          if ($(this).attr('target') !== undefined) {
            window.open($(this).attr('href'), $(this).attr('target'));
          } else {
            location.href = $(this).attr('href');
          }
        });
    }

    if (this.album.length > 1 && this.options.showImageNumberLabel) {
      var labelText = this.imageCountLabel(this.currentImageIndex + 1, this.album.length);
      this.$lightbox.find('.lb-number').text(labelText).fadeIn('fast');
    } else {
      this.$lightbox.find('.lb-number').hide();
    }

    this.$outerContainer.removeClass('animating');

    this.$lightbox.find('.lb-dataContainer').fadeIn(this.options.resizeDuration, function() {
      return self.sizeOverlay();
    });
  };

  // Preload previous and next images in set.
  Lightbox.prototype.preloadNeighboringImages = function() {
    if (this.album.length > this.currentImageIndex + 1) {
      var preloadNext = new Image();
      preloadNext.src = this.album[this.currentImageIndex + 1].link;
    }
    if (this.currentImageIndex > 0) {
      var preloadPrev = new Image();
      preloadPrev.src = this.album[this.currentImageIndex - 1].link;
    }
  };

  Lightbox.prototype.enableKeyboardNav = function() {
    $(document).on('keyup.keyboard', $.proxy(this.keyboardAction, this));
  };

  Lightbox.prototype.disableKeyboardNav = function() {
    $(document).off('.keyboard');
  };

  Lightbox.prototype.keyboardAction = function(event) {
    var KEYCODE_ESC        = 27;
    var KEYCODE_LEFTARROW  = 37;
    var KEYCODE_RIGHTARROW = 39;

    var keycode = event.keyCode;
    var key     = String.fromCharCode(keycode).toLowerCase();
    if (keycode === KEYCODE_ESC || key.match(/x|o|c/)) {
      this.end();
    } else if (key === 'p' || keycode === KEYCODE_LEFTARROW) {
      if (this.currentImageIndex !== 0) {
        this.changeImage(this.currentImageIndex - 1);
      } else if (this.options.wrapAround && this.album.length > 1) {
        this.changeImage(this.album.length - 1);
      }
    } else if (key === 'n' || keycode === KEYCODE_RIGHTARROW) {
      if (this.currentImageIndex !== this.album.length - 1) {
        this.changeImage(this.currentImageIndex + 1);
      } else if (this.options.wrapAround && this.album.length > 1) {
        this.changeImage(0);
      }
    }
  };

  // Closing time. :-(
  Lightbox.prototype.end = function() {
    this.disableKeyboardNav();
    $(window).off('resize', this.sizeOverlay);
    this.$lightbox.fadeOut(this.options.fadeDuration);
    this.$overlay.fadeOut(this.options.fadeDuration);
    $('select, object, embed').css({
      visibility: 'visible'
    });
    if (this.options.disableScrolling) {
      $('body').removeClass('lb-disable-scrolling');
    }
  };

  return new Lightbox();
}));

$(function(){

	// Modal Save Button
	$("body").on("submit", "form.frmDocAddFile", function(e) {
		var relationId = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-id");
		var relationType = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-type");
		var target = "form#"+relationId+"_"+relationType+"_frmDocUpload";
		var docType = $(target).data("upload-type");
		var formData = new FormData($(target)[0]);
		var saveType = $(target).data("savetype");


		formData.append("docRelationType", relationType);  // doc type if category, product, or variant
		formData.append("relationId", relationId);
		formData.append("docType", docType); // if image or file

		melisCoreTool.pending(".btn");
		$.ajax({
			type : 'POST',
			url  : '/melis/MelisCommerce/MelisComDocument/saveDocument',
			data : formData,
			processData : false,
			cache       : false,
			contentType : false,
			dataType    : 'json',
			xhr: function() {
				var fileXhr = $.ajaxSettings.xhr();
				if(fileXhr.upload){
					fileXhr.upload.addEventListener('progress',progress, false);
				}
				return fileXhr;
			}
		}).done(function(data) {

			if(data && data.success) {
				var docRelationType = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-type");
				var docRelationId   = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-id");
				$("div.modal").modal("hide");
				if(data.type == "file") {
					melisHelper.zoneReload(activeTabId+" #id_meliscommerce_documents_file_attachments_lists", "meliscommerce_documents_file_attachments_lists", {
						docRelationType : docRelationType, docRelationId : docRelationId
					});
				}
				else if(data.type == "image") {
					melisHelper.zoneReload(activeTabId+" #id_meliscommerce_documents_image_lists", "meliscommerce_documents_image_lists", {
						docRelationType : docRelationType, docRelationId : docRelationId
					});
				}
				melisCommerce.setUniqueId(melisCommerce.getUniqueId());
				melisHelper.melisOkNotification(data.textTitle, data.textMessage);
			}
			else {
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
				melisCoreTool.highlightErrors(data.success, data.errors, relationId+"_"+relationType+"_"+"frmDocUpload");
				$("div.progressContent").addClass("hidden");
			}
			melisCoreTool.done(".btn");
			melisCore.flashMessenger();
		}).error(function() {
			melisCoreTool.done(".btn");
			melisHelper.melisKoNotification(translations.tr_meliscommerce_documents_Documents, translations.tr_meliscommerce_documents_save_fail, [], 'closeByButtonOnly');
		}).error(function() {
			$("div.modal").modal("hide");
			melisCoreTool.done(".btn");
			if(docType == "file") {
				melisHelper.zoneReload(activeTabId+" #id_meliscommerce_documents_file_attachments_lists", "meliscommerce_documents_file_attachments_lists");
			}
			else if(docType == "image") {
				melisHelper.zoneReload(activeTabId+" #id_meliscommerce_documents_image_lists", "meliscommerce_documents_image_lists");
			}
			melisCore.flashMessenger();
		});

		e.preventDefault();
	});

	function progress(e) {
		resetProgressBar();
		if(e.lengthComputable){
			var max = e.total;
			var current = e.loaded;
			var percentage = (current * 100)/max;
			$("div.progressContent > div.progress > div.progress-bar").attr("aria-valuenow", percentage);
			$("div.progressContent > div.progress > div.progress-bar").css("width", percentage+"%");

			if(percentage > 100)
			{
				$("div.progressContent").addClass("hidden");
			}
			else {
				$("div.progressContent > div.progress > span.status").html(Math.round(percentage)+"%");
			}
		}
	}

	function resetProgressBar() {
		$("div.progressContent").removeClass("hidden");
		$("div.progressContent > div.progress > div.progress-bar").attr("aria-valuenow", 0);
		$("div.progressContent > div.progress > div.progress-bar").css("width", '0%');
		$("div.progressContent > div.progress > span.status").html("");
	}

	// Deleting File/Image Document Confirmation Dialog
	$("body").on("click", ".deleteFileImageDocument", function(e){
		// Data Attribute on the Selected Element/button
		// Type can be "image" or "file"
		var docId = $(this).data("doc-id");
		var docType = $(this).data("type");

		var docRelationId = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-id");
		var docRelationType = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-type");

		if(docType=='image'){
			var title = translations.tr_meliscommerce_documents_delete_image_title;
			var confirmMsg = translations.tr_meliscommerce_documents_delete_image_msg_confirm;
		}else if(docType=='file'){
			var title = translations.tr_meliscommerce_documents_delete_file_title;
			var confirmMsg = translations.tr_meliscommerce_documents_delete_file_msg_confirm;
		}

		melisCoreTool.confirm(
			translations.tr_meliscommerce_documents_common_label_yes,
			translations.tr_meliscommerce_documents_common_label_no,
			title,
			confirmMsg,
			function() {
				$.ajax({
					type        : 'POST',
					url         : '/melis/MelisCommerce/MelisComDocument/delete',
					data		: {id : docId, docType : docType, formType : docRelationType, uniqueId : docRelationId},
					dataType    : 'json',
					encode		: true,
				}).success(function(data){
					melisCoreTool.pending(".btn");
					if(data && data.success) {
						if(docType == "file") {
							melisHelper.zoneReload(activeTabId+" #id_meliscommerce_documents_file_attachments_lists", "meliscommerce_documents_file_attachments_lists", {docRelationType : docRelationType, docRelationId : docRelationId});
						}
						else if(docType == "image") {
							melisHelper.zoneReload(activeTabId+" #id_meliscommerce_documents_image_lists", "meliscommerce_documents_image_lists", {docRelationType : docRelationType, docRelationId : docRelationId});
						}
						melisCore.flashMessenger();
						melisHelper.melisOkNotification(data.textTitle, data.textMessage);
					}
					else {
						melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
					}
					melisCoreTool.done(".btn");
				}).error(function(){
					$("div.modal").modal("hide");
					melisCoreTool.done(".btn");
					if(docType == "file") {
						melisHelper.zoneReload(activeTabId+" #id_meliscommerce_documents_file_attachments_lists", "meliscommerce_documents_file_attachments_lists");
					}
					else if(docType == "image") {
						melisHelper.zoneReload(activeTabId+" #id_meliscommerce_documents_image_lists", "meliscommerce_documents_image_lists");
					}
					melisCore.flashMessenger();
				});
			});
	});

	$("body").on("click", ".collapseAddImageType", function() {
		var formDiv = $("div.addImageType");
		var form = $("form.frmDocAddFile");
		formDiv.slideToggle();

		$(".collapseAddImageType").find("i[data-class='iconAddImageType']").toggleClass("fa-angle-down");
	});

	$("body").on("click", ".btnDocAddImageType", function() {
		var dataString = $("form.frmDocAddImageType").serialize();
		var relationId = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-id");
		var relationType = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-type");
		var saveType = $("form.frmDocAddImageType").data("upload-type");
		var formData = $(this).parent().prev().parent().find("form.frmDocAddFile").serialize();
		var image = $(this).parent().prev().parent().find("img.imgDocThumbnail").attr("src");
		melisCommerce.postSave('melis/MelisCommerce/MelisComDocument/addImageType?typeUpload='+saveType, dataString, function(data) {
			if(data.success) {
				melisHelper.zoneReload("id_meliscommerce_documents_modal_form", "meliscommerce_documents_modal_form", {typeUpload : "image", saveType : saveType, docRelationId : relationId, docRelationType :relationType});
				melisHelper.melisOkNotification(data.textTitle, data.textMessage);

				// put back all the info
				setTimeout(function() {
					$(".btnDocAddImageType").parent().prev().parent().find("img.imgDocThumbnail").attr("src", image);
					$.each(formData.split('&'), function (index, elem) {
						var vals = elem.split('=');
						$("[name='" + vals[0] + "']").val(vals[1]);
					});
				}, 2000);

			}
			else {
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
				melisCoreTool.highlightErrors(data.success, data.errors, "frmDocAddImageType");
			}
			melisCore.flashMessenger();
		})

	});

	$("body").on("click", ".btnDocAddFileType", function() {
		var dataString = $("form.frmDocAddImageType").serialize();
		var relationId = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-id");
		var relationType = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-type");
		var saveType = $("form.frmDocAddImageType").data("upload-type");
		var formData = $(this).parent().prev().parent().find("form.frmDocAddFile").serialize();

		melisCommerce.postSave('melis/MelisCommerce/MelisComDocument/addFileType?typeUpload='+saveType, dataString, function(data) {
			if(data.success) {
				melisHelper.zoneReload("id_meliscommerce_documents_modal_form", "meliscommerce_documents_modal_form", {typeUpload : "file", saveType : saveType, docRelationId : relationId, docRelationType :relationType});
				melisHelper.melisOkNotification(data.textTitle, data.textMessage);

				// put back all the info
				setTimeout(function() {
					$.each(formData.split('&'), function (index, elem) {
						var vals = elem.split('=');
						$("[name='" + vals[0] + "']").val(vals[1]);
					});
				}, 2000);

			}
			else {
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
				melisCoreTool.highlightErrors(data.success, data.errors, "frmDocAddImageType");
			}
			melisCore.flashMessenger();
		})

	});

	$("body").on("click", ".updateFileDocument", function(){
		var docId = $(this).data("doc-id");
		var docType = $(this).data("type");
		var relationId = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-id");
		var relationType = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-type");
		var zoneId = 'id_meliscommerce_documents_modal_form';
		var melisKey = 'meliscommerce_documents_modal_form';
		var modalUrl = '/melis/MelisCommerce/MelisComDocument/renderDocumentGenericModalContainer';

		melisHelper.createModal(zoneId, melisKey, false, {typeUpload : 'file', docId : docId, saveType : 'updatefile', docRelationId : relationId, docRelationType :relationType},  modalUrl);
	});

	$("body").on("click", ".editImageDocumentModal", function() {
		var typeUpload = "image";
		var zoneId = 'id_meliscommerce_documents_modal_form';
		var melisKey = 'meliscommerce_documents_modal_form';
		var modalUrl = '/melis/MelisCommerce/MelisComDocument/renderDocumentGenericModalContainer';
		var docId = $(this).data("doc-id");
		var relationId = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-id");
		var relationType = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-type");
		melisHelper.createModal(zoneId, melisKey, false, {typeUpload : 'image', docId : docId, saveType : 'editimage', docRelationId : relationId, docRelationType :relationType}, modalUrl);
	});

	// Add File/Image Button, Request Modal Content
	$("body").on("click", ".addFileImageDocument", function(e){
		melisCoreTool.pending(this);
		var typeUpload = $(this).data('type');
		var relationId = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-id");
		var relationType = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-type");
		var zoneId = 'id_meliscommerce_documents_modal_form';
		var melisKey = 'meliscommerce_documents_modal_form';
		var modalUrl = '/melis/MelisCommerce/MelisComDocument/renderDocumentGenericModalContainer';
		// requesitng to create modal and display after
		melisHelper.createModal(zoneId, melisKey, false, {typeUpload:typeUpload, docRelationId: relationId, docRelationType :relationType}, modalUrl, function() {
			melisCoreTool.done(".addFileImageDocument");
		});

	});
	
	$("body").on("click", "#btnDocFileAdd", function(e){
			var button = $(this);
			var formId = '#'+button.attr("form");
			$(formId).trigger("submit");
			e.preventDefault();
	});

});

window.initImageDocuments = function(e) {
	// Fix for Mobile
	var custom_event = $.support.touch ? "touchstart" : "click";

	// Initialize Image Container ISOTOPE
	var $container = $(e).find("div.imageDocumentContainer");
	$container.imagesLoaded( function() {
		$container.isotope({
			itemSelector : '.image'
		});
	});
	filters = {};

	// ISOTOPE filter dropdown
	/* Filter text change for Country */
	$("body").on(custom_event, ".filter-div-country .filter-key-values li a", function() {
		var value = $(this).data('text');
		$('.filter-div-country .filter-dropdown').attr('data-value', value);
		$('.filter-div-country span.filter-key').text(value);
	});
	/* Filter text change for File Type */
	$("body").on(custom_event, ".filter-div-file-type .filter-key-values li a", function() {
		var value = $(this).data('text');
		$('.filter-div-file-type .filter-dropdown').attr('data-value', value);
		$('.filter-div-file-type span.filter-key').text(value);
	});

	// Isotope sorting/filter
	$('.documentImageFilter a').on(custom_event,function() {
		$('.documentImageFilter .current').removeClass('current');
		$(this).addClass('current');

		var $optionSet = $(this).parents('.documentImageFilter');
		// change selected class
		$optionSet.find('.selected').removeClass('selected');
		$(this).addClass('selected');
		var group = $optionSet.attr('data-filter-group');
		filters[ group ] = $(this).attr('data-filter-value');
		// convert object into array
		var isoFilters = [];
		for ( var prop in filters ) {
			isoFilters.push( filters[ prop ] )
		}
		var selector = isoFilters.join('');

		$grid = $container.isotope({
			filter: selector,
			animationOptions: {
				duration: 750,
				easing: 'linear',
				queue: false
			}
		});

		// ISOTOPE Event after filter
		$grid.on( 'arrangeComplete', function( event, filteredItems ) {
			// Deselect Images, to remove from the group of available/selected image uaing filter
			$('.viewImageDocument').attr('data-lightbox','deselected-images');
			$.each(filteredItems, function(){
				// updating data-lightbox attribute to make images available after filtering
				// making images as group and sliding images limited only to the group
				$selectedImgElem = $(this.element).find('.viewImageDocument');
				$selectedImgElem.attr('data-lightbox','selected-images');
			});
		});

		// Lightbox Plugin Initialization after ISOTOPE Filter action
		lightbox.option({
			'resizeDuration': 200,
			'wrapAround': true
		});
	});

	// Isotope sorting/filter Responsive
	$('.documentImageFilter a').on(custom_event,function() {
		$('.documentImageFilter .current').removeClass('current');
		$(this).addClass('current');

		var $optionSet = $(this).parents('.documentImageFilter');
		// change selected class
		$optionSet.find('.selected').removeClass('selected');
		$(this).addClass('selected');
		var group = $optionSet.attr('data-filter-group');
		filters[ group ] = $(this).attr('data-filter-value');
		// convert object into array
		var isoFilters = [];
		for ( var prop in filters ) {
			isoFilters.push( filters[ prop ] )
		}
		var selector = isoFilters.join('');

		$grid = $container.isotope({
			filter: selector,
			animationOptions: {
				duration: 750,
				easing: 'linear',
				queue: false
			}
		});

		// ISOTOPE Event after filter
		$grid.on( 'arrangeComplete', function( event, filteredItems ) {
			// Deselect Images, to remove from the group of available/selected image uaing filter
			$('.viewImageDocument').attr('data-lightbox','deselected-images');
			$.each(filteredItems, function(){
				// updating data-lightbox attribute to make images available after filtering
				// making images as group and sliding images limited only to the group
				$selectedImgElem = $(this.element).find('.viewImageDocument');
				$selectedImgElem.attr('data-lightbox','selected-images');
			});
		});

		// Lightbox Plugin Initialization after ISOTOPE Filter action
		lightbox.option({
			'resizeDuration': 200,
			'wrapAround': true
		});
	});

	// Lightbox Plugin Initialization
	lightbox.option({
		'resizeDuration': 200,
		'wrapAround': true
	});
}

window.updateFormId = function() {
	var form = $("form.frmDocAddFile");
	if(melisCommerce.getUniqueId()) {
		form.attr("id", melisCommerce.getUniqueId()+"_frmDocUpload");
	}
}

window.imagePreview = function(id, fileInput) {
	if(fileInput.files && fileInput.files[0]) {
		var reader = new FileReader();
		reader.onload = function(e) {
			$(id).attr('src', e.target.result);
		}
		reader.readAsDataURL(fileInput.files[0]);
	}
}

/*! jsTree - v3.3.2 - 2016-08-15 - (MIT) */
!function(a){"use strict";"function"==typeof define&&define.amd?define(["jquery"],a):"undefined"!=typeof module&&module.exports?module.exports=a(require("jquery")):a(jQuery)}(function(a,b){"use strict";if(!a.jstree){var c=0,d=!1,e=!1,f=!1,g=[],h=a("script:last").attr("src"),i=window.document,j=i.createElement("LI"),k,l;j.setAttribute("role","treeitem"),k=i.createElement("I"),k.className="jstree-icon jstree-ocl",k.setAttribute("role","presentation"),j.appendChild(k),k=i.createElement("A"),k.className="jstree-anchor",k.setAttribute("href","#"),k.setAttribute("tabindex","-1"),l=i.createElement("I"),l.className="jstree-icon jstree-themeicon",l.setAttribute("role","presentation"),k.appendChild(l),j.appendChild(k),k=l=null,a.jstree={version:"3.3.2",defaults:{plugins:[]},plugins:{},path:h&&-1!==h.indexOf("/")?h.replace(/\/[^\/]+$/,""):"",idregex:/[\\:&!^|()\[\]<>@*'+~#";.,=\- \/${}%?`]/g,root:"#"},a.jstree.create=function(b,d){var e=new a.jstree.core(++c),f=d;return d=a.extend(!0,{},a.jstree.defaults,d),f&&f.plugins&&(d.plugins=f.plugins),a.each(d.plugins,function(a,b){"core"!==a&&(e=e.plugin(b,d[b]))}),a(b).data("jstree",e),e.init(b,d),e},a.jstree.destroy=function(){a(".jstree:jstree").jstree("destroy"),a(i).off(".jstree")},a.jstree.core=function(a){this._id=a,this._cnt=0,this._wrk=null,this._data={core:{themes:{name:!1,dots:!1,icons:!1},selected:[],last_error:{},working:!1,worker_queue:[],focused:null}}},a.jstree.reference=function(b){var c=null,d=null;if(!b||!b.id||b.tagName&&b.nodeType||(b=b.id),!d||!d.length)try{d=a(b)}catch(e){}if(!d||!d.length)try{d=a("#"+b.replace(a.jstree.idregex,"\\$&"))}catch(e){}return d&&d.length&&(d=d.closest(".jstree")).length&&(d=d.data("jstree"))?c=d:a(".jstree").each(function(){var d=a(this).data("jstree");return d&&d._model.data[b]?(c=d,!1):void 0}),c},a.fn.jstree=function(c){var d="string"==typeof c,e=Array.prototype.slice.call(arguments,1),f=null;return c!==!0||this.length?(this.each(function(){var g=a.jstree.reference(this),h=d&&g?g[c]:null;return f=d&&h?h.apply(g,e):null,g||d||c!==b&&!a.isPlainObject(c)||a.jstree.create(this,c),(g&&!d||c===!0)&&(f=g||!1),null!==f&&f!==b?!1:void 0}),null!==f&&f!==b?f:this):!1},a.expr.pseudos.jstree=a.expr.createPseudo(function(c){return function(c){return a(c).hasClass("jstree")&&a(c).data("jstree")!==b}}),a.jstree.defaults.core={data:!1,strings:!1,check_callback:!1,error:a.noop,animation:200,multiple:!0,themes:{name:!1,url:!1,dir:!1,dots:!0,icons:!0,stripes:!1,variant:!1,responsive:!1},expand_selected_onload:!0,worker:!0,force_text:!1,dblclick_toggle:!0},a.jstree.core.prototype={plugin:function(b,c){var d=a.jstree.plugins[b];return d?(this._data[b]={},d.prototype=this,new d(c,this)):this},init:function(b,c){this._model={data:{},changed:[],force_full_redraw:!1,redraw_timeout:!1,default_state:{loaded:!0,opened:!1,selected:!1,disabled:!1}},this._model.data[a.jstree.root]={id:a.jstree.root,parent:null,parents:[],children:[],children_d:[],state:{loaded:!1}},this.element=a(b).addClass("jstree jstree-"+this._id),this.settings=c,this._data.core.ready=!1,this._data.core.loaded=!1,this._data.core.rtl="rtl"===this.element.css("direction"),this.element[this._data.core.rtl?"addClass":"removeClass"]("jstree-rtl"),this.element.attr("role","tree"),this.settings.core.multiple&&this.element.attr("aria-multiselectable",!0),this.element.attr("tabindex")||this.element.attr("tabindex","0"),this.bind(),this.trigger("init"),this._data.core.original_container_html=this.element.find(" > ul > li").clone(!0),this._data.core.original_container_html.find("li").addBack().contents().filter(function(){return 3===this.nodeType&&(!this.nodeValue||/^\s+$/.test(this.nodeValue))}).remove(),this.element.html("<ul class='jstree-container-ul jstree-children' role='group'><li id='j"+this._id+"_loading' class='jstree-initial-node jstree-loading jstree-leaf jstree-last' role='tree-item'><i class='jstree-icon jstree-ocl'></i><a class='jstree-anchor' href='#'><i class='jstree-icon jstree-themeicon-hidden'></i>"+this.get_string("Loading ...")+"</a></li></ul>"),this.element.attr("aria-activedescendant","j"+this._id+"_loading"),this._data.core.li_height=this.get_container_ul().children("li").first().height()||24,this.trigger("loading"),this.load_node(a.jstree.root)},destroy:function(a){if(this._wrk)try{window.URL.revokeObjectURL(this._wrk),this._wrk=null}catch(b){}a||this.element.empty(),this.teardown()},teardown:function(){this.unbind(),this.element.removeClass("jstree").removeData("jstree").find("[class^='jstree']").addBack().attr("class",function(){return this.className.replace(/jstree[^ ]*|$/gi,"")}),this.element=null},bind:function(){var b="",c=null,d=0;this.element.on("dblclick.jstree",function(a){if(a.target.tagName&&"input"===a.target.tagName.toLowerCase())return!0;if(i.selection&&i.selection.empty)i.selection.empty();else if(window.getSelection){var b=window.getSelection();try{b.removeAllRanges(),b.collapse()}catch(c){}}}).on("mousedown.jstree",a.proxy(function(a){a.target===this.element[0]&&(a.preventDefault(),d=+new Date)},this)).on("mousedown.jstree",".jstree-ocl",function(a){a.preventDefault()}).on("click.jstree",".jstree-ocl",a.proxy(function(a){this.toggle_node(a.target)},this)).on("dblclick.jstree",".jstree-anchor",a.proxy(function(a){return a.target.tagName&&"input"===a.target.tagName.toLowerCase()?!0:void(this.settings.core.dblclick_toggle&&this.toggle_node(a.target))},this)).on("click.jstree",".jstree-anchor",a.proxy(function(b){b.preventDefault(),b.currentTarget!==i.activeElement&&a(b.currentTarget).focus(),this.activate_node(b.currentTarget,b)},this)).on("keydown.jstree",".jstree-anchor",a.proxy(function(b){if(b.target.tagName&&"input"===b.target.tagName.toLowerCase())return!0;if(32!==b.which&&13!==b.which&&(b.shiftKey||b.ctrlKey||b.altKey||b.metaKey))return!0;var c=null;switch(this._data.core.rtl&&(37===b.which?b.which=39:39===b.which&&(b.which=37)),b.which){case 32:b.ctrlKey&&(b.type="click",a(b.currentTarget).trigger(b));break;case 13:b.type="click",a(b.currentTarget).trigger(b);break;case 37:b.preventDefault(),this.is_open(b.currentTarget)?this.close_node(b.currentTarget):(c=this.get_parent(b.currentTarget),c&&c.id!==a.jstree.root&&this.get_node(c,!0).children(".jstree-anchor").focus());break;case 38:b.preventDefault(),c=this.get_prev_dom(b.currentTarget),c&&c.length&&c.children(".jstree-anchor").focus();break;case 39:b.preventDefault(),this.is_closed(b.currentTarget)?this.open_node(b.currentTarget,function(a){this.get_node(a,!0).children(".jstree-anchor").focus()}):this.is_open(b.currentTarget)&&(c=this.get_node(b.currentTarget,!0).children(".jstree-children")[0],c&&a(this._firstChild(c)).children(".jstree-anchor").focus());break;case 40:b.preventDefault(),c=this.get_next_dom(b.currentTarget),c&&c.length&&c.children(".jstree-anchor").focus();break;case 106:this.open_all();break;case 36:b.preventDefault(),c=this._firstChild(this.get_container_ul()[0]),c&&a(c).children(".jstree-anchor").filter(":visible").focus();break;case 35:b.preventDefault(),this.element.find(".jstree-anchor").filter(":visible").last().focus();break;case 113:b.preventDefault(),this.edit(b.currentTarget)}},this)).on("load_node.jstree",a.proxy(function(b,c){c.status&&(c.node.id!==a.jstree.root||this._data.core.loaded||(this._data.core.loaded=!0,this._firstChild(this.get_container_ul()[0])&&this.element.attr("aria-activedescendant",this._firstChild(this.get_container_ul()[0]).id),this.trigger("loaded")),this._data.core.ready||setTimeout(a.proxy(function(){if(this.element&&!this.get_container_ul().find(".jstree-loading").length){if(this._data.core.ready=!0,this._data.core.selected.length){if(this.settings.core.expand_selected_onload){var b=[],c,d;for(c=0,d=this._data.core.selected.length;d>c;c++)b=b.concat(this._model.data[this._data.core.selected[c]].parents);for(b=a.vakata.array_unique(b),c=0,d=b.length;d>c;c++)this.open_node(b[c],!1,0)}this.trigger("changed",{action:"ready",selected:this._data.core.selected})}this.trigger("ready")}},this),0))},this)).on("keypress.jstree",a.proxy(function(d){if(d.target.tagName&&"input"===d.target.tagName.toLowerCase())return!0;c&&clearTimeout(c),c=setTimeout(function(){b=""},500);var e=String.fromCharCode(d.which).toLowerCase(),f=this.element.find(".jstree-anchor").filter(":visible"),g=f.index(i.activeElement)||0,h=!1;if(b+=e,b.length>1){if(f.slice(g).each(a.proxy(function(c,d){return 0===a(d).text().toLowerCase().indexOf(b)?(a(d).focus(),h=!0,!1):void 0},this)),h)return;if(f.slice(0,g).each(a.proxy(function(c,d){return 0===a(d).text().toLowerCase().indexOf(b)?(a(d).focus(),h=!0,!1):void 0},this)),h)return}if(new RegExp("^"+e.replace(/[-\/\\^$*+?.()|[\]{}]/g,"\\$&")+"+$").test(b)){if(f.slice(g+1).each(a.proxy(function(b,c){return a(c).text().toLowerCase().charAt(0)===e?(a(c).focus(),h=!0,!1):void 0},this)),h)return;if(f.slice(0,g+1).each(a.proxy(function(b,c){return a(c).text().toLowerCase().charAt(0)===e?(a(c).focus(),h=!0,!1):void 0},this)),h)return}},this)).on("init.jstree",a.proxy(function(){var a=this.settings.core.themes;this._data.core.themes.dots=a.dots,this._data.core.themes.stripes=a.stripes,this._data.core.themes.icons=a.icons,this.set_theme(a.name||"default",a.url),this.set_theme_variant(a.variant)},this)).on("loading.jstree",a.proxy(function(){this[this._data.core.themes.dots?"show_dots":"hide_dots"](),this[this._data.core.themes.icons?"show_icons":"hide_icons"](),this[this._data.core.themes.stripes?"show_stripes":"hide_stripes"]()},this)).on("blur.jstree",".jstree-anchor",a.proxy(function(b){this._data.core.focused=null,a(b.currentTarget).filter(".jstree-hovered").mouseleave(),this.element.attr("tabindex","0")},this)).on("focus.jstree",".jstree-anchor",a.proxy(function(b){var c=this.get_node(b.currentTarget);c&&c.id&&(this._data.core.focused=c.id),this.element.find(".jstree-hovered").not(b.currentTarget).mouseleave(),a(b.currentTarget).mouseenter(),this.element.attr("tabindex","-1")},this)).on("focus.jstree",a.proxy(function(){if(+new Date-d>500&&!this._data.core.focused){d=0;var a=this.get_node(this.element.attr("aria-activedescendant"),!0);a&&a.find("> .jstree-anchor").focus()}},this)).on("mouseenter.jstree",".jstree-anchor",a.proxy(function(a){this.hover_node(a.currentTarget)},this)).on("mouseleave.jstree",".jstree-anchor",a.proxy(function(a){this.dehover_node(a.currentTarget)},this))},unbind:function(){this.element.off(".jstree"),a(i).off(".jstree-"+this._id)},trigger:function(a,b){b||(b={}),b.instance=this,this.element.triggerHandler(a.replace(".jstree","")+".jstree",b)},get_container:function(){return this.element},get_container_ul:function(){return this.element.children(".jstree-children").first()},get_string:function(b){var c=this.settings.core.strings;return a.isFunction(c)?c.call(this,b):c&&c[b]?c[b]:b},_firstChild:function(a){a=a?a.firstChild:null;while(null!==a&&1!==a.nodeType)a=a.nextSibling;return a},_nextSibling:function(a){a=a?a.nextSibling:null;while(null!==a&&1!==a.nodeType)a=a.nextSibling;return a},_previousSibling:function(a){a=a?a.previousSibling:null;while(null!==a&&1!==a.nodeType)a=a.previousSibling;return a},get_node:function(b,c){b&&b.id&&(b=b.id);var d;try{if(this._model.data[b])b=this._model.data[b];else if("string"==typeof b&&this._model.data[b.replace(/^#/,"")])b=this._model.data[b.replace(/^#/,"")];else if("string"==typeof b&&(d=a("#"+b.replace(a.jstree.idregex,"\\$&"),this.element)).length&&this._model.data[d.closest(".jstree-node").attr("id")])b=this._model.data[d.closest(".jstree-node").attr("id")];else if((d=a(b,this.element)).length&&this._model.data[d.closest(".jstree-node").attr("id")])b=this._model.data[d.closest(".jstree-node").attr("id")];else{if(!(d=a(b,this.element)).length||!d.hasClass("jstree"))return!1;b=this._model.data[a.jstree.root]}return c&&(b=b.id===a.jstree.root?this.element:a("#"+b.id.replace(a.jstree.idregex,"\\$&"),this.element)),b}catch(e){return!1}},get_path:function(b,c,d){if(b=b.parents?b:this.get_node(b),!b||b.id===a.jstree.root||!b.parents)return!1;var e,f,g=[];for(g.push(d?b.id:b.text),e=0,f=b.parents.length;f>e;e++)g.push(d?b.parents[e]:this.get_text(b.parents[e]));return g=g.reverse().slice(1),c?g.join(c):g},get_next_dom:function(b,c){var d;if(b=this.get_node(b,!0),b[0]===this.element[0]){d=this._firstChild(this.get_container_ul()[0]);while(d&&0===d.offsetHeight)d=this._nextSibling(d);return d?a(d):!1}if(!b||!b.length)return!1;if(c){d=b[0];do d=this._nextSibling(d);while(d&&0===d.offsetHeight);return d?a(d):!1}if(b.hasClass("jstree-open")){d=this._firstChild(b.children(".jstree-children")[0]);while(d&&0===d.offsetHeight)d=this._nextSibling(d);if(null!==d)return a(d)}d=b[0];do d=this._nextSibling(d);while(d&&0===d.offsetHeight);return null!==d?a(d):b.parentsUntil(".jstree",".jstree-node").nextAll(".jstree-node:visible").first()},get_prev_dom:function(b,c){var d;if(b=this.get_node(b,!0),b[0]===this.element[0]){d=this.get_container_ul()[0].lastChild;while(d&&0===d.offsetHeight)d=this._previousSibling(d);return d?a(d):!1}if(!b||!b.length)return!1;if(c){d=b[0];do d=this._previousSibling(d);while(d&&0===d.offsetHeight);return d?a(d):!1}d=b[0];do d=this._previousSibling(d);while(d&&0===d.offsetHeight);if(null!==d){b=a(d);while(b.hasClass("jstree-open"))b=b.children(".jstree-children").first().children(".jstree-node:visible:last");return b}return d=b[0].parentNode.parentNode,d&&d.className&&-1!==d.className.indexOf("jstree-node")?a(d):!1},get_parent:function(b){return b=this.get_node(b),b&&b.id!==a.jstree.root?b.parent:!1},get_children_dom:function(a){return a=this.get_node(a,!0),a[0]===this.element[0]?this.get_container_ul().children(".jstree-node"):a&&a.length?a.children(".jstree-children").children(".jstree-node"):!1},is_parent:function(a){return a=this.get_node(a),a&&(a.state.loaded===!1||a.children.length>0)},is_loaded:function(a){return a=this.get_node(a),a&&a.state.loaded},is_loading:function(a){return a=this.get_node(a),a&&a.state&&a.state.loading},is_open:function(a){return a=this.get_node(a),a&&a.state.opened},is_closed:function(a){return a=this.get_node(a),a&&this.is_parent(a)&&!a.state.opened},is_leaf:function(a){return!this.is_parent(a)},load_node:function(b,c){var d,e,f,g,h;if(a.isArray(b))return this._load_nodes(b.slice(),c),!0;if(b=this.get_node(b),!b)return c&&c.call(this,b,!1),!1;if(b.state.loaded){for(b.state.loaded=!1,f=0,g=b.parents.length;g>f;f++)this._model.data[b.parents[f]].children_d=a.vakata.array_filter(this._model.data[b.parents[f]].children_d,function(c){return-1===a.inArray(c,b.children_d)});for(d=0,e=b.children_d.length;e>d;d++)this._model.data[b.children_d[d]].state.selected&&(h=!0),delete this._model.data[b.children_d[d]];h&&(this._data.core.selected=a.vakata.array_filter(this._data.core.selected,function(c){return-1===a.inArray(c,b.children_d)})),b.children=[],b.children_d=[],h&&this.trigger("changed",{action:"load_node",node:b,selected:this._data.core.selected})}return b.state.failed=!1,b.state.loading=!0,this.get_node(b,!0).addClass("jstree-loading").attr("aria-busy",!0),this._load_node(b,a.proxy(function(a){b=this._model.data[b.id],b.state.loading=!1,b.state.loaded=a,b.state.failed=!b.state.loaded;var d=this.get_node(b,!0),e=0,f=0,g=this._model.data,h=!1;for(e=0,f=b.children.length;f>e;e++)if(g[b.children[e]]&&!g[b.children[e]].state.hidden){h=!0;break}b.state.loaded&&d&&d.length&&(d.removeClass("jstree-closed jstree-open jstree-leaf"),h?"#"!==b.id&&d.addClass(b.state.opened?"jstree-open":"jstree-closed"):d.addClass("jstree-leaf")),d.removeClass("jstree-loading").attr("aria-busy",!1),this.trigger("load_node",{node:b,status:a}),c&&c.call(this,b,a)},this)),!0},_load_nodes:function(a,b,c,d){var e=!0,f=function(){this._load_nodes(a,b,!0)},g=this._model.data,h,i,j=[];for(h=0,i=a.length;i>h;h++)g[a[h]]&&(!g[a[h]].state.loaded&&!g[a[h]].state.failed||!c&&d)&&(this.is_loading(a[h])||this.load_node(a[h],f),e=!1);if(e){for(h=0,i=a.length;i>h;h++)g[a[h]]&&g[a[h]].state.loaded&&j.push(a[h]);b&&!b.done&&(b.call(this,j),b.done=!0)}},load_all:function(b,c){if(b||(b=a.jstree.root),b=this.get_node(b),!b)return!1;var d=[],e=this._model.data,f=e[b.id].children_d,g,h;for(b.state&&!b.state.loaded&&d.push(b.id),g=0,h=f.length;h>g;g++)e[f[g]]&&e[f[g]].state&&!e[f[g]].state.loaded&&d.push(f[g]);d.length?this._load_nodes(d,function(){this.load_all(b,c)}):(c&&c.call(this,b),this.trigger("load_all",{node:b}))},_load_node:function(b,c){var d=this.settings.core.data,e,f=function g(){return 3!==this.nodeType&&8!==this.nodeType};return d?a.isFunction(d)?d.call(this,b,a.proxy(function(d){d===!1?c.call(this,!1):this["string"==typeof d?"_append_html_data":"_append_json_data"](b,"string"==typeof d?a(a.parseHTML(d)).filter(f):d,function(a){c.call(this,a)})},this)):"object"==typeof d?d.url?(d=a.extend(!0,{},d),a.isFunction(d.url)&&(d.url=d.url.call(this,b)),a.isFunction(d.data)&&(d.data=d.data.call(this,b)),a.ajax(d).done(a.proxy(function(d,e,g){var h=g.getResponseHeader("Content-Type");return h&&-1!==h.indexOf("json")||"object"==typeof d?this._append_json_data(b,d,function(a){c.call(this,a)}):h&&-1!==h.indexOf("html")||"string"==typeof d?this._append_html_data(b,a(a.parseHTML(d)).filter(f),function(a){c.call(this,a)}):(this._data.core.last_error={error:"ajax",plugin:"core",id:"core_04",reason:"Could not load node",data:JSON.stringify({id:b.id,xhr:g})},this.settings.core.error.call(this,this._data.core.last_error),c.call(this,!1))},this)).fail(a.proxy(function(a){c.call(this,!1),this._data.core.last_error={error:"ajax",plugin:"core",id:"core_04",reason:"Could not load node",data:JSON.stringify({id:b.id,xhr:a})},this.settings.core.error.call(this,this._data.core.last_error)},this))):(e=a.isArray(d)||a.isPlainObject(d)?JSON.parse(JSON.stringify(d)):d,b.id===a.jstree.root?this._append_json_data(b,e,function(a){c.call(this,a)}):(this._data.core.last_error={error:"nodata",plugin:"core",id:"core_05",reason:"Could not load node",data:JSON.stringify({id:b.id})},this.settings.core.error.call(this,this._data.core.last_error),c.call(this,!1))):"string"==typeof d?b.id===a.jstree.root?this._append_html_data(b,a(a.parseHTML(d)).filter(f),function(a){c.call(this,a)}):(this._data.core.last_error={error:"nodata",plugin:"core",id:"core_06",reason:"Could not load node",data:JSON.stringify({id:b.id})},this.settings.core.error.call(this,this._data.core.last_error),c.call(this,!1)):c.call(this,!1):b.id===a.jstree.root?this._append_html_data(b,this._data.core.original_container_html.clone(!0),function(a){c.call(this,a)}):c.call(this,!1)},_node_changed:function(a){a=this.get_node(a),a&&this._model.changed.push(a.id)},_append_html_data:function(b,c,d){b=this.get_node(b),b.children=[],b.children_d=[];var e=c.is("ul")?c.children():c,f=b.id,g=[],h=[],i=this._model.data,j=i[f],k=this._data.core.selected.length,l,m,n;for(e.each(a.proxy(function(b,c){l=this._parse_model_from_html(a(c),f,j.parents.concat()),l&&(g.push(l),h.push(l),i[l].children_d.length&&(h=h.concat(i[l].children_d)))},this)),j.children=g,j.children_d=h,m=0,n=j.parents.length;n>m;m++)i[j.parents[m]].children_d=i[j.parents[m]].children_d.concat(h);this.trigger("model",{nodes:h,parent:f}),f!==a.jstree.root?(this._node_changed(f),this.redraw()):(this.get_container_ul().children(".jstree-initial-node").remove(),this.redraw(!0)),this._data.core.selected.length!==k&&this.trigger("changed",{action:"model",selected:this._data.core.selected}),d.call(this,!0)},_append_json_data:function(b,c,d,e){if(null!==this.element){b=this.get_node(b),b.children=[],b.children_d=[],c.d&&(c=c.d,"string"==typeof c&&(c=JSON.parse(c))),a.isArray(c)||(c=[c]);var f=null,g={df:this._model.default_state,dat:c,par:b.id,m:this._model.data,t_id:this._id,t_cnt:this._cnt,sel:this._data.core.selected},h=function(a,b){a.data&&(a=a.data);var c=a.dat,d=a.par,e=[],f=[],g=[],h=a.df,i=a.t_id,j=a.t_cnt,k=a.m,l=k[d],m=a.sel,n,o,p,q,r=function(a,c,d){d=d?d.concat():[],c&&d.unshift(c);var e=a.id.toString(),f,i,j,l,m={id:e,text:a.text||"",icon:a.icon!==b?a.icon:!0,parent:c,parents:d,children:a.children||[],children_d:a.children_d||[],data:a.data,state:{},li_attr:{id:!1},a_attr:{href:"#"},original:!1};for(f in h)h.hasOwnProperty(f)&&(m.state[f]=h[f]);if(a&&a.data&&a.data.jstree&&a.data.jstree.icon&&(m.icon=a.data.jstree.icon),(m.icon===b||null===m.icon||""===m.icon)&&(m.icon=!0),a&&a.data&&(m.data=a.data,a.data.jstree))for(f in a.data.jstree)a.data.jstree.hasOwnProperty(f)&&(m.state[f]=a.data.jstree[f]);if(a&&"object"==typeof a.state)for(f in a.state)a.state.hasOwnProperty(f)&&(m.state[f]=a.state[f]);if(a&&"object"==typeof a.li_attr)for(f in a.li_attr)a.li_attr.hasOwnProperty(f)&&(m.li_attr[f]=a.li_attr[f]);if(m.li_attr.id||(m.li_attr.id=e),a&&"object"==typeof a.a_attr)for(f in a.a_attr)a.a_attr.hasOwnProperty(f)&&(m.a_attr[f]=a.a_attr[f]);for(a&&a.children&&a.children===!0&&(m.state.loaded=!1,m.children=[],m.children_d=[]),k[m.id]=m,f=0,i=m.children.length;i>f;f++)j=r(k[m.children[f]],m.id,d),l=k[j],m.children_d.push(j),l.children_d.length&&(m.children_d=m.children_d.concat(l.children_d));return delete a.data,delete a.children,k[m.id].original=a,m.state.selected&&g.push(m.id),m.id},s=function(a,c,d){d=d?d.concat():[],c&&d.unshift(c);var e=!1,f,l,m,n,o;do e="j"+i+"_"+ ++j;while(k[e]);o={id:!1,text:"string"==typeof a?a:"",icon:"object"==typeof a&&a.icon!==b?a.icon:!0,parent:c,parents:d,children:[],children_d:[],data:null,state:{},li_attr:{id:!1},a_attr:{href:"#"},original:!1};for(f in h)h.hasOwnProperty(f)&&(o.state[f]=h[f]);if(a&&a.id&&(o.id=a.id.toString()),a&&a.text&&(o.text=a.text),a&&a.data&&a.data.jstree&&a.data.jstree.icon&&(o.icon=a.data.jstree.icon),(o.icon===b||null===o.icon||""===o.icon)&&(o.icon=!0),a&&a.data&&(o.data=a.data,a.data.jstree))for(f in a.data.jstree)a.data.jstree.hasOwnProperty(f)&&(o.state[f]=a.data.jstree[f]);if(a&&"object"==typeof a.state)for(f in a.state)a.state.hasOwnProperty(f)&&(o.state[f]=a.state[f]);if(a&&"object"==typeof a.li_attr)for(f in a.li_attr)a.li_attr.hasOwnProperty(f)&&(o.li_attr[f]=a.li_attr[f]);if(o.li_attr.id&&!o.id&&(o.id=o.li_attr.id.toString()),o.id||(o.id=e),o.li_attr.id||(o.li_attr.id=o.id),a&&"object"==typeof a.a_attr)for(f in a.a_attr)a.a_attr.hasOwnProperty(f)&&(o.a_attr[f]=a.a_attr[f]);if(a&&a.children&&a.children.length){for(f=0,l=a.children.length;l>f;f++)m=s(a.children[f],o.id,d),n=k[m],o.children.push(m),n.children_d.length&&(o.children_d=o.children_d.concat(n.children_d));o.children_d=o.children_d.concat(o.children)}return a&&a.children&&a.children===!0&&(o.state.loaded=!1,o.children=[],o.children_d=[]),delete a.data,delete a.children,o.original=a,k[o.id]=o,o.state.selected&&g.push(o.id),o.id};if(c.length&&c[0].id!==b&&c[0].parent!==b){for(o=0,p=c.length;p>o;o++)c[o].children||(c[o].children=[]),k[c[o].id.toString()]=c[o];for(o=0,p=c.length;p>o;o++)k[c[o].parent.toString()].children.push(c[o].id.toString()),l.children_d.push(c[o].id.toString());for(o=0,p=l.children.length;p>o;o++)n=r(k[l.children[o]],d,l.parents.concat()),f.push(n),k[n].children_d.length&&(f=f.concat(k[n].children_d));for(o=0,p=l.parents.length;p>o;o++)k[l.parents[o]].children_d=k[l.parents[o]].children_d.concat(f);q={cnt:j,mod:k,sel:m,par:d,dpc:f,add:g}}else{for(o=0,p=c.length;p>o;o++)n=s(c[o],d,l.parents.concat()),n&&(e.push(n),f.push(n),k[n].children_d.length&&(f=f.concat(k[n].children_d)));for(l.children=e,l.children_d=f,o=0,p=l.parents.length;p>o;o++)k[l.parents[o]].children_d=k[l.parents[o]].children_d.concat(f);q={cnt:j,mod:k,sel:m,par:d,dpc:f,add:g}}return"undefined"!=typeof window&&"undefined"!=typeof window.document?q:void postMessage(q)},i=function(b,c){if(null!==this.element){this._cnt=b.cnt;var e,f=this._model.data;for(e in f)f.hasOwnProperty(e)&&f[e].state&&f[e].state.loading&&b.mod[e]&&(b.mod[e].state.loading=!0);if(this._model.data=b.mod,c){var g,h=b.add,i=b.sel,j=this._data.core.selected.slice();if(f=this._model.data,i.length!==j.length||a.vakata.array_unique(i.concat(j)).length!==i.length){for(e=0,g=i.length;g>e;e++)-1===a.inArray(i[e],h)&&-1===a.inArray(i[e],j)&&(f[i[e]].state.selected=!1);for(e=0,g=j.length;g>e;e++)-1===a.inArray(j[e],i)&&(f[j[e]].state.selected=!0)}}b.add.length&&(this._data.core.selected=this._data.core.selected.concat(b.add)),this.trigger("model",{nodes:b.dpc,parent:b.par}),b.par!==a.jstree.root?(this._node_changed(b.par),this.redraw()):this.redraw(!0),b.add.length&&this.trigger("changed",{action:"model",selected:this._data.core.selected}),d.call(this,!0)}};if(this.settings.core.worker&&window.Blob&&window.URL&&window.Worker)try{null===this._wrk&&(this._wrk=window.URL.createObjectURL(new window.Blob(["self.onmessage = "+h.toString()],{type:"text/javascript"}))),!this._data.core.working||e?(this._data.core.working=!0,f=new window.Worker(this._wrk),f.onmessage=a.proxy(function(a){i.call(this,a.data,!0);try{f.terminate(),f=null}catch(b){}this._data.core.worker_queue.length?this._append_json_data.apply(this,this._data.core.worker_queue.shift()):this._data.core.working=!1},this),g.par?f.postMessage(g):this._data.core.worker_queue.length?this._append_json_data.apply(this,this._data.core.worker_queue.shift()):this._data.core.working=!1):this._data.core.worker_queue.push([b,c,d,!0])}catch(j){i.call(this,h(g),!1),this._data.core.worker_queue.length?this._append_json_data.apply(this,this._data.core.worker_queue.shift()):this._data.core.working=!1}else i.call(this,h(g),!1)}},_parse_model_from_html:function(c,d,e){e=e?[].concat(e):[],d&&e.unshift(d);var f,g,h=this._model.data,i={id:!1,text:!1,icon:!0,parent:d,parents:e,children:[],children_d:[],data:null,state:{},li_attr:{id:!1},a_attr:{href:"#"},original:!1},j,k,l;for(j in this._model.default_state)this._model.default_state.hasOwnProperty(j)&&(i.state[j]=this._model.default_state[j]);if(k=a.vakata.attributes(c,!0),a.each(k,function(b,c){return c=a.trim(c),c.length?(i.li_attr[b]=c,void("id"===b&&(i.id=c.toString()))):!0}),k=c.children("a").first(),k.length&&(k=a.vakata.attributes(k,!0),a.each(k,function(b,c){c=a.trim(c),c.length&&(i.a_attr[b]=c)})),k=c.children("a").first().length?c.children("a").first().clone():c.clone(),k.children("ins, i, ul").remove(),k=k.html(),k=a("<div />").html(k),i.text=this.settings.core.force_text?k.text():k.html(),k=c.data(),i.data=k?a.extend(!0,{},k):null,i.state.opened=c.hasClass("jstree-open"),i.state.selected=c.children("a").hasClass("jstree-clicked"),i.state.disabled=c.children("a").hasClass("jstree-disabled"),i.data&&i.data.jstree)for(j in i.data.jstree)i.data.jstree.hasOwnProperty(j)&&(i.state[j]=i.data.jstree[j]);k=c.children("a").children(".jstree-themeicon"),k.length&&(i.icon=k.hasClass("jstree-themeicon-hidden")?!1:k.attr("rel")),i.state.icon!==b&&(i.icon=i.state.icon),(i.icon===b||null===i.icon||""===i.icon)&&(i.icon=!0),k=c.children("ul").children("li");do l="j"+this._id+"_"+ ++this._cnt;while(h[l]);return i.id=i.li_attr.id?i.li_attr.id.toString():l,k.length?(k.each(a.proxy(function(b,c){f=this._parse_model_from_html(a(c),i.id,e),g=this._model.data[f],i.children.push(f),g.children_d.length&&(i.children_d=i.children_d.concat(g.children_d))},this)),i.children_d=i.children_d.concat(i.children)):c.hasClass("jstree-closed")&&(i.state.loaded=!1),i.li_attr["class"]&&(i.li_attr["class"]=i.li_attr["class"].replace("jstree-closed","").replace("jstree-open","")),i.a_attr["class"]&&(i.a_attr["class"]=i.a_attr["class"].replace("jstree-clicked","").replace("jstree-disabled","")),h[i.id]=i,i.state.selected&&this._data.core.selected.push(i.id),i.id},_parse_model_from_flat_json:function(a,c,d){d=d?d.concat():[],c&&d.unshift(c);var e=a.id.toString(),f=this._model.data,g=this._model.default_state,h,i,j,k,l={id:e,text:a.text||"",icon:a.icon!==b?a.icon:!0,parent:c,parents:d,children:a.children||[],children_d:a.children_d||[],data:a.data,state:{},li_attr:{id:!1},a_attr:{href:"#"},original:!1};for(h in g)g.hasOwnProperty(h)&&(l.state[h]=g[h]);if(a&&a.data&&a.data.jstree&&a.data.jstree.icon&&(l.icon=a.data.jstree.icon),(l.icon===b||null===l.icon||""===l.icon)&&(l.icon=!0),a&&a.data&&(l.data=a.data,a.data.jstree))for(h in a.data.jstree)a.data.jstree.hasOwnProperty(h)&&(l.state[h]=a.data.jstree[h]);if(a&&"object"==typeof a.state)for(h in a.state)a.state.hasOwnProperty(h)&&(l.state[h]=a.state[h]);if(a&&"object"==typeof a.li_attr)for(h in a.li_attr)a.li_attr.hasOwnProperty(h)&&(l.li_attr[h]=a.li_attr[h]);if(l.li_attr.id||(l.li_attr.id=e),a&&"object"==typeof a.a_attr)for(h in a.a_attr)a.a_attr.hasOwnProperty(h)&&(l.a_attr[h]=a.a_attr[h]);for(a&&a.children&&a.children===!0&&(l.state.loaded=!1,l.children=[],l.children_d=[]),f[l.id]=l,h=0,i=l.children.length;i>h;h++)j=this._parse_model_from_flat_json(f[l.children[h]],l.id,d),k=f[j],l.children_d.push(j),k.children_d.length&&(l.children_d=l.children_d.concat(k.children_d));return delete a.data,delete a.children,f[l.id].original=a,l.state.selected&&this._data.core.selected.push(l.id),l.id},_parse_model_from_json:function(a,c,d){d=d?d.concat():[],c&&d.unshift(c);var e=!1,f,g,h,i,j=this._model.data,k=this._model.default_state,l;do e="j"+this._id+"_"+ ++this._cnt;while(j[e]);l={id:!1,text:"string"==typeof a?a:"",icon:"object"==typeof a&&a.icon!==b?a.icon:!0,parent:c,parents:d,children:[],children_d:[],data:null,state:{},li_attr:{id:!1},a_attr:{href:"#"},original:!1};for(f in k)k.hasOwnProperty(f)&&(l.state[f]=k[f]);if(a&&a.id&&(l.id=a.id.toString()),a&&a.text&&(l.text=a.text),a&&a.data&&a.data.jstree&&a.data.jstree.icon&&(l.icon=a.data.jstree.icon),(l.icon===b||null===l.icon||""===l.icon)&&(l.icon=!0),a&&a.data&&(l.data=a.data,a.data.jstree))for(f in a.data.jstree)a.data.jstree.hasOwnProperty(f)&&(l.state[f]=a.data.jstree[f]);if(a&&"object"==typeof a.state)for(f in a.state)a.state.hasOwnProperty(f)&&(l.state[f]=a.state[f]);if(a&&"object"==typeof a.li_attr)for(f in a.li_attr)a.li_attr.hasOwnProperty(f)&&(l.li_attr[f]=a.li_attr[f]);if(l.li_attr.id&&!l.id&&(l.id=l.li_attr.id.toString()),l.id||(l.id=e),l.li_attr.id||(l.li_attr.id=l.id),a&&"object"==typeof a.a_attr)for(f in a.a_attr)a.a_attr.hasOwnProperty(f)&&(l.a_attr[f]=a.a_attr[f]);if(a&&a.children&&a.children.length){for(f=0,g=a.children.length;g>f;f++)h=this._parse_model_from_json(a.children[f],l.id,d),i=j[h],l.children.push(h),i.children_d.length&&(l.children_d=l.children_d.concat(i.children_d));l.children_d=l.children_d.concat(l.children)}return a&&a.children&&a.children===!0&&(l.state.loaded=!1,l.children=[],l.children_d=[]),delete a.data,delete a.children,l.original=a,j[l.id]=l,l.state.selected&&this._data.core.selected.push(l.id),l.id},_redraw:function(){var b=this._model.force_full_redraw?this._model.data[a.jstree.root].children.concat([]):this._model.changed.concat([]),c=i.createElement("UL"),d,e,f,g=this._data.core.focused;for(e=0,f=b.length;f>e;e++)d=this.redraw_node(b[e],!0,this._model.force_full_redraw),d&&this._model.force_full_redraw&&c.appendChild(d);this._model.force_full_redraw&&(c.className=this.get_container_ul()[0].className,c.setAttribute("role","group"),this.element.empty().append(c)),null!==g&&(d=this.get_node(g,!0),d&&d.length&&d.children(".jstree-anchor")[0]!==i.activeElement?d.children(".jstree-anchor").focus():this._data.core.focused=null),this._model.force_full_redraw=!1,this._model.changed=[],this.trigger("redraw",{nodes:b})},redraw:function(a){a&&(this._model.force_full_redraw=!0),this._redraw()},draw_children:function(b){var c=this.get_node(b),d=!1,e=!1,f=!1,g=i;if(!c)return!1;if(c.id===a.jstree.root)return this.redraw(!0);if(b=this.get_node(b,!0),!b||!b.length)return!1;if(b.children(".jstree-children").remove(),b=b[0],c.children.length&&c.state.loaded){for(f=g.createElement("UL"),f.setAttribute("role","group"),f.className="jstree-children",d=0,e=c.children.length;e>d;d++)f.appendChild(this.redraw_node(c.children[d],!0,!0));b.appendChild(f)}},redraw_node:function(b,c,d,e){var f=this.get_node(b),g=!1,h=!1,k=!1,l=!1,m=!1,n=!1,o="",p=i,q=this._model.data,r=!1,s=!1,t=null,u=0,v=0,w=!1,x=!1;if(!f)return!1;if(f.id===a.jstree.root)return this.redraw(!0);if(c=c||0===f.children.length,b=i.querySelector?this.element[0].querySelector("#"+(-1!=="0123456789".indexOf(f.id[0])?"\\3"+f.id[0]+" "+f.id.substr(1).replace(a.jstree.idregex,"\\$&"):f.id.replace(a.jstree.idregex,"\\$&"))):i.getElementById(f.id))b=a(b),
d||(g=b.parent().parent()[0],g===this.element[0]&&(g=null),h=b.index()),c||!f.children.length||b.children(".jstree-children").length||(c=!0),c||(k=b.children(".jstree-children")[0]),r=b.children(".jstree-anchor")[0]===i.activeElement,b.remove();else if(c=!0,!d){if(g=f.parent!==a.jstree.root?a("#"+f.parent.replace(a.jstree.idregex,"\\$&"),this.element)[0]:null,!(null===g||g&&q[f.parent].state.opened))return!1;h=a.inArray(f.id,null===g?q[a.jstree.root].children:q[f.parent].children)}b=j.cloneNode(!0),o="jstree-node ";for(l in f.li_attr)if(f.li_attr.hasOwnProperty(l)){if("id"===l)continue;"class"!==l?b.setAttribute(l,f.li_attr[l]):o+=f.li_attr[l]}for(f.a_attr.id||(f.a_attr.id=f.id+"_anchor"),b.setAttribute("aria-selected",!!f.state.selected),b.setAttribute("aria-level",f.parents.length),b.setAttribute("aria-labelledby",f.a_attr.id),f.state.disabled&&b.setAttribute("aria-disabled",!0),l=0,m=f.children.length;m>l;l++)if(!q[f.children[l]].state.hidden){w=!0;break}if(null!==f.parent&&q[f.parent]&&!f.state.hidden&&(l=a.inArray(f.id,q[f.parent].children),x=f.id,-1!==l))for(l++,m=q[f.parent].children.length;m>l;l++)if(q[q[f.parent].children[l]].state.hidden||(x=q[f.parent].children[l]),x!==f.id)break;f.state.hidden&&(o+=" jstree-hidden"),f.state.loaded&&!w?o+=" jstree-leaf":(o+=f.state.opened&&f.state.loaded?" jstree-open":" jstree-closed",b.setAttribute("aria-expanded",f.state.opened&&f.state.loaded)),x===f.id&&(o+=" jstree-last"),b.id=f.id,b.className=o,o=(f.state.selected?" jstree-clicked":"")+(f.state.disabled?" jstree-disabled":"");for(m in f.a_attr)if(f.a_attr.hasOwnProperty(m)){if("href"===m&&"#"===f.a_attr[m])continue;"class"!==m?b.childNodes[1].setAttribute(m,f.a_attr[m]):o+=" "+f.a_attr[m]}if(o.length&&(b.childNodes[1].className="jstree-anchor "+o),(f.icon&&f.icon!==!0||f.icon===!1)&&(f.icon===!1?b.childNodes[1].childNodes[0].className+=" jstree-themeicon-hidden":-1===f.icon.indexOf("/")&&-1===f.icon.indexOf(".")?b.childNodes[1].childNodes[0].className+=" "+f.icon+" jstree-themeicon-custom":(b.childNodes[1].childNodes[0].style.backgroundImage='url("'+f.icon+'")',b.childNodes[1].childNodes[0].style.backgroundPosition="center center",b.childNodes[1].childNodes[0].style.backgroundSize="auto",b.childNodes[1].childNodes[0].className+=" jstree-themeicon-custom")),this.settings.core.force_text?b.childNodes[1].appendChild(p.createTextNode(f.text)):b.childNodes[1].innerHTML+=f.text,c&&f.children.length&&(f.state.opened||e)&&f.state.loaded){for(n=p.createElement("UL"),n.setAttribute("role","group"),n.className="jstree-children",l=0,m=f.children.length;m>l;l++)n.appendChild(this.redraw_node(f.children[l],c,!0));b.appendChild(n)}if(k&&b.appendChild(k),!d){for(g||(g=this.element[0]),l=0,m=g.childNodes.length;m>l;l++)if(g.childNodes[l]&&g.childNodes[l].className&&-1!==g.childNodes[l].className.indexOf("jstree-children")){t=g.childNodes[l];break}t||(t=p.createElement("UL"),t.setAttribute("role","group"),t.className="jstree-children",g.appendChild(t)),g=t,h<g.childNodes.length?g.insertBefore(b,g.childNodes[h]):g.appendChild(b),r&&(u=this.element[0].scrollTop,v=this.element[0].scrollLeft,b.childNodes[1].focus(),this.element[0].scrollTop=u,this.element[0].scrollLeft=v)}return f.state.opened&&!f.state.loaded&&(f.state.opened=!1,setTimeout(a.proxy(function(){this.open_node(f.id,!1,0)},this),0)),b},open_node:function(c,d,e){var f,g,h,i;if(a.isArray(c)){for(c=c.slice(),f=0,g=c.length;g>f;f++)this.open_node(c[f],d,e);return!0}return c=this.get_node(c),c&&c.id!==a.jstree.root?(e=e===b?this.settings.core.animation:e,this.is_closed(c)?this.is_loaded(c)?(h=this.get_node(c,!0),i=this,h.length&&(e&&h.children(".jstree-children").length&&h.children(".jstree-children").stop(!0,!0),c.children.length&&!this._firstChild(h.children(".jstree-children")[0])&&this.draw_children(c),e?(this.trigger("before_open",{node:c}),h.children(".jstree-children").css("display","none").end().removeClass("jstree-closed").addClass("jstree-open").attr("aria-expanded",!0).children(".jstree-children").stop(!0,!0).slideDown(e,function(){this.style.display="",i.element&&i.trigger("after_open",{node:c})})):(this.trigger("before_open",{node:c}),h[0].className=h[0].className.replace("jstree-closed","jstree-open"),h[0].setAttribute("aria-expanded",!0))),c.state.opened=!0,d&&d.call(this,c,!0),h.length||this.trigger("before_open",{node:c}),this.trigger("open_node",{node:c}),e&&h.length||this.trigger("after_open",{node:c}),!0):this.is_loading(c)?setTimeout(a.proxy(function(){this.open_node(c,d,e)},this),500):void this.load_node(c,function(a,b){return b?this.open_node(a,d,e):d?d.call(this,a,!1):!1}):(d&&d.call(this,c,!1),!1)):!1},_open_to:function(b){if(b=this.get_node(b),!b||b.id===a.jstree.root)return!1;var c,d,e=b.parents;for(c=0,d=e.length;d>c;c+=1)c!==a.jstree.root&&this.open_node(e[c],!1,0);return a("#"+b.id.replace(a.jstree.idregex,"\\$&"),this.element)},close_node:function(c,d){var e,f,g,h;if(a.isArray(c)){for(c=c.slice(),e=0,f=c.length;f>e;e++)this.close_node(c[e],d);return!0}return c=this.get_node(c),c&&c.id!==a.jstree.root?this.is_closed(c)?!1:(d=d===b?this.settings.core.animation:d,g=this,h=this.get_node(c,!0),c.state.opened=!1,this.trigger("close_node",{node:c}),void(h.length?d?h.children(".jstree-children").attr("style","display:block !important").end().removeClass("jstree-open").addClass("jstree-closed").attr("aria-expanded",!1).children(".jstree-children").stop(!0,!0).slideUp(d,function(){this.style.display="",h.children(".jstree-children").remove(),g.element&&g.trigger("after_close",{node:c})}):(h[0].className=h[0].className.replace("jstree-open","jstree-closed"),h.attr("aria-expanded",!1).children(".jstree-children").remove(),this.trigger("after_close",{node:c})):this.trigger("after_close",{node:c}))):!1},toggle_node:function(b){var c,d;if(a.isArray(b)){for(b=b.slice(),c=0,d=b.length;d>c;c++)this.toggle_node(b[c]);return!0}return this.is_closed(b)?this.open_node(b):this.is_open(b)?this.close_node(b):void 0},open_all:function(b,c,d){if(b||(b=a.jstree.root),b=this.get_node(b),!b)return!1;var e=b.id===a.jstree.root?this.get_container_ul():this.get_node(b,!0),f,g,h;if(!e.length){for(f=0,g=b.children_d.length;g>f;f++)this.is_closed(this._model.data[b.children_d[f]])&&(this._model.data[b.children_d[f]].state.opened=!0);return this.trigger("open_all",{node:b})}d=d||e,h=this,e=this.is_closed(b)?e.find(".jstree-closed").addBack():e.find(".jstree-closed"),e.each(function(){h.open_node(this,function(a,b){b&&this.is_parent(a)&&this.open_all(a,c,d)},c||0)}),0===d.find(".jstree-closed").length&&this.trigger("open_all",{node:this.get_node(d)})},close_all:function(b,c){if(b||(b=a.jstree.root),b=this.get_node(b),!b)return!1;var d=b.id===a.jstree.root?this.get_container_ul():this.get_node(b,!0),e=this,f,g;for(d.length&&(d=this.is_open(b)?d.find(".jstree-open").addBack():d.find(".jstree-open"),a(d.get().reverse()).each(function(){e.close_node(this,c||0)})),f=0,g=b.children_d.length;g>f;f++)this._model.data[b.children_d[f]].state.opened=!1;this.trigger("close_all",{node:b})},is_disabled:function(a){return a=this.get_node(a),a&&a.state&&a.state.disabled},enable_node:function(b){var c,d;if(a.isArray(b)){for(b=b.slice(),c=0,d=b.length;d>c;c++)this.enable_node(b[c]);return!0}return b=this.get_node(b),b&&b.id!==a.jstree.root?(b.state.disabled=!1,this.get_node(b,!0).children(".jstree-anchor").removeClass("jstree-disabled").attr("aria-disabled",!1),void this.trigger("enable_node",{node:b})):!1},disable_node:function(b){var c,d;if(a.isArray(b)){for(b=b.slice(),c=0,d=b.length;d>c;c++)this.disable_node(b[c]);return!0}return b=this.get_node(b),b&&b.id!==a.jstree.root?(b.state.disabled=!0,this.get_node(b,!0).children(".jstree-anchor").addClass("jstree-disabled").attr("aria-disabled",!0),void this.trigger("disable_node",{node:b})):!1},is_hidden:function(a){return a=this.get_node(a),a.state.hidden===!0},hide_node:function(b,c){var d,e;if(a.isArray(b)){for(b=b.slice(),d=0,e=b.length;e>d;d++)this.hide_node(b[d],!0);return c||this.redraw(),!0}return b=this.get_node(b),b&&b.id!==a.jstree.root?void(b.state.hidden||(b.state.hidden=!0,this._node_changed(b.parent),c||this.redraw(),this.trigger("hide_node",{node:b}))):!1},show_node:function(b,c){var d,e;if(a.isArray(b)){for(b=b.slice(),d=0,e=b.length;e>d;d++)this.show_node(b[d],!0);return c||this.redraw(),!0}return b=this.get_node(b),b&&b.id!==a.jstree.root?void(b.state.hidden&&(b.state.hidden=!1,this._node_changed(b.parent),c||this.redraw(),this.trigger("show_node",{node:b}))):!1},hide_all:function(b){var c,d=this._model.data,e=[];for(c in d)d.hasOwnProperty(c)&&c!==a.jstree.root&&!d[c].state.hidden&&(d[c].state.hidden=!0,e.push(c));return this._model.force_full_redraw=!0,b||this.redraw(),this.trigger("hide_all",{nodes:e}),e},show_all:function(b){var c,d=this._model.data,e=[];for(c in d)d.hasOwnProperty(c)&&c!==a.jstree.root&&d[c].state.hidden&&(d[c].state.hidden=!1,e.push(c));return this._model.force_full_redraw=!0,b||this.redraw(),this.trigger("show_all",{nodes:e}),e},activate_node:function(a,c){if(this.is_disabled(a))return!1;if(c&&"object"==typeof c||(c={}),this._data.core.last_clicked=this._data.core.last_clicked&&this._data.core.last_clicked.id!==b?this.get_node(this._data.core.last_clicked.id):null,this._data.core.last_clicked&&!this._data.core.last_clicked.state.selected&&(this._data.core.last_clicked=null),!this._data.core.last_clicked&&this._data.core.selected.length&&(this._data.core.last_clicked=this.get_node(this._data.core.selected[this._data.core.selected.length-1])),this.settings.core.multiple&&(c.metaKey||c.ctrlKey||c.shiftKey)&&(!c.shiftKey||this._data.core.last_clicked&&this.get_parent(a)&&this.get_parent(a)===this._data.core.last_clicked.parent))if(c.shiftKey){var d=this.get_node(a).id,e=this._data.core.last_clicked.id,f=this.get_node(this._data.core.last_clicked.parent).children,g=!1,h,i;for(h=0,i=f.length;i>h;h+=1)f[h]===d&&(g=!g),f[h]===e&&(g=!g),this.is_disabled(f[h])||!g&&f[h]!==d&&f[h]!==e?this.deselect_node(f[h],!0,c):this.is_hidden(f[h])||this.select_node(f[h],!0,!1,c);this.trigger("changed",{action:"select_node",node:this.get_node(a),selected:this._data.core.selected,event:c})}else this.is_selected(a)?this.deselect_node(a,!1,c):this.select_node(a,!1,!1,c);else!this.settings.core.multiple&&(c.metaKey||c.ctrlKey||c.shiftKey)&&this.is_selected(a)?this.deselect_node(a,!1,c):(this.deselect_all(!0),this.select_node(a,!1,!1,c),this._data.core.last_clicked=this.get_node(a));this.trigger("activate_node",{node:this.get_node(a),event:c})},hover_node:function(a){if(a=this.get_node(a,!0),!a||!a.length||a.children(".jstree-hovered").length)return!1;var b=this.element.find(".jstree-hovered"),c=this.element;b&&b.length&&this.dehover_node(b),a.children(".jstree-anchor").addClass("jstree-hovered"),this.trigger("hover_node",{node:this.get_node(a)}),setTimeout(function(){c.attr("aria-activedescendant",a[0].id)},0)},dehover_node:function(a){return a=this.get_node(a,!0),a&&a.length&&a.children(".jstree-hovered").length?(a.children(".jstree-anchor").removeClass("jstree-hovered"),void this.trigger("dehover_node",{node:this.get_node(a)})):!1},select_node:function(b,c,d,e){var f,g,h,i;if(a.isArray(b)){for(b=b.slice(),g=0,h=b.length;h>g;g++)this.select_node(b[g],c,d,e);return!0}return b=this.get_node(b),b&&b.id!==a.jstree.root?(f=this.get_node(b,!0),void(b.state.selected||(b.state.selected=!0,this._data.core.selected.push(b.id),d||(f=this._open_to(b)),f&&f.length&&f.attr("aria-selected",!0).children(".jstree-anchor").addClass("jstree-clicked"),this.trigger("select_node",{node:b,selected:this._data.core.selected,event:e}),c||this.trigger("changed",{action:"select_node",node:b,selected:this._data.core.selected,event:e})))):!1},deselect_node:function(b,c,d){var e,f,g;if(a.isArray(b)){for(b=b.slice(),e=0,f=b.length;f>e;e++)this.deselect_node(b[e],c,d);return!0}return b=this.get_node(b),b&&b.id!==a.jstree.root?(g=this.get_node(b,!0),void(b.state.selected&&(b.state.selected=!1,this._data.core.selected=a.vakata.array_remove_item(this._data.core.selected,b.id),g.length&&g.attr("aria-selected",!1).children(".jstree-anchor").removeClass("jstree-clicked"),this.trigger("deselect_node",{node:b,selected:this._data.core.selected,event:d}),c||this.trigger("changed",{action:"deselect_node",node:b,selected:this._data.core.selected,event:d})))):!1},select_all:function(b){var c=this._data.core.selected.concat([]),d,e;for(this._data.core.selected=this._model.data[a.jstree.root].children_d.concat(),d=0,e=this._data.core.selected.length;e>d;d++)this._model.data[this._data.core.selected[d]]&&(this._model.data[this._data.core.selected[d]].state.selected=!0);this.redraw(!0),this.trigger("select_all",{selected:this._data.core.selected}),b||this.trigger("changed",{action:"select_all",selected:this._data.core.selected,old_selection:c})},deselect_all:function(a){var b=this._data.core.selected.concat([]),c,d;for(c=0,d=this._data.core.selected.length;d>c;c++)this._model.data[this._data.core.selected[c]]&&(this._model.data[this._data.core.selected[c]].state.selected=!1);this._data.core.selected=[],this.element.find(".jstree-clicked").removeClass("jstree-clicked").parent().attr("aria-selected",!1),this.trigger("deselect_all",{selected:this._data.core.selected,node:b}),a||this.trigger("changed",{action:"deselect_all",selected:this._data.core.selected,old_selection:b})},is_selected:function(b){return b=this.get_node(b),b&&b.id!==a.jstree.root?b.state.selected:!1},get_selected:function(b){return b?a.map(this._data.core.selected,a.proxy(function(a){return this.get_node(a)},this)):this._data.core.selected.slice()},get_top_selected:function(b){var c=this.get_selected(!0),d={},e,f,g,h;for(e=0,f=c.length;f>e;e++)d[c[e].id]=c[e];for(e=0,f=c.length;f>e;e++)for(g=0,h=c[e].children_d.length;h>g;g++)d[c[e].children_d[g]]&&delete d[c[e].children_d[g]];c=[];for(e in d)d.hasOwnProperty(e)&&c.push(e);return b?a.map(c,a.proxy(function(a){return this.get_node(a)},this)):c},get_bottom_selected:function(b){var c=this.get_selected(!0),d=[],e,f;for(e=0,f=c.length;f>e;e++)c[e].children.length||d.push(c[e].id);return b?a.map(d,a.proxy(function(a){return this.get_node(a)},this)):d},get_state:function(){var b={core:{open:[],scroll:{left:this.element.scrollLeft(),top:this.element.scrollTop()},selected:[]}},c;for(c in this._model.data)this._model.data.hasOwnProperty(c)&&c!==a.jstree.root&&(this._model.data[c].state.opened&&b.core.open.push(c),this._model.data[c].state.selected&&b.core.selected.push(c));return b},set_state:function(c,d){if(c){if(c.core){var e,f,g,h,i;if(c.core.open)return a.isArray(c.core.open)&&c.core.open.length?this._load_nodes(c.core.open,function(a){this.open_node(a,!1,0),delete c.core.open,this.set_state(c,d)}):(delete c.core.open,this.set_state(c,d)),!1;if(c.core.scroll)return c.core.scroll&&c.core.scroll.left!==b&&this.element.scrollLeft(c.core.scroll.left),c.core.scroll&&c.core.scroll.top!==b&&this.element.scrollTop(c.core.scroll.top),delete c.core.scroll,this.set_state(c,d),!1;if(c.core.selected)return h=this,this.deselect_all(),a.each(c.core.selected,function(a,b){h.select_node(b,!1,!0)}),delete c.core.selected,this.set_state(c,d),!1;for(i in c)c.hasOwnProperty(i)&&"core"!==i&&-1===a.inArray(i,this.settings.plugins)&&delete c[i];if(a.isEmptyObject(c.core))return delete c.core,this.set_state(c,d),!1}return a.isEmptyObject(c)?(c=null,d&&d.call(this),this.trigger("set_state"),!1):!0}return!1},refresh:function(b,c){this._data.core.state=c===!0?{}:this.get_state(),c&&a.isFunction(c)&&(this._data.core.state=c.call(this,this._data.core.state)),this._cnt=0,this._model.data={},this._model.data[a.jstree.root]={id:a.jstree.root,parent:null,parents:[],children:[],children_d:[],state:{loaded:!1}},this._data.core.selected=[],this._data.core.last_clicked=null,this._data.core.focused=null;var d=this.get_container_ul()[0].className;b||(this.element.html("<ul class='"+d+"' role='group'><li class='jstree-initial-node jstree-loading jstree-leaf jstree-last' role='treeitem' id='j"+this._id+"_loading'><i class='jstree-icon jstree-ocl'></i><a class='jstree-anchor' href='#'><i class='jstree-icon jstree-themeicon-hidden'></i>"+this.get_string("Loading ...")+"</a></li></ul>"),this.element.attr("aria-activedescendant","j"+this._id+"_loading")),this.load_node(a.jstree.root,function(b,c){c&&(this.get_container_ul()[0].className=d,this._firstChild(this.get_container_ul()[0])&&this.element.attr("aria-activedescendant",this._firstChild(this.get_container_ul()[0]).id),this.set_state(a.extend(!0,{},this._data.core.state),function(){this.trigger("refresh")})),this._data.core.state=null})},refresh_node:function(b){if(b=this.get_node(b),!b||b.id===a.jstree.root)return!1;var c=[],d=[],e=this._data.core.selected.concat([]);d.push(b.id),b.state.opened===!0&&c.push(b.id),this.get_node(b,!0).find(".jstree-open").each(function(){d.push(this.id),c.push(this.id)}),this._load_nodes(d,a.proxy(function(a){this.open_node(c,!1,0),this.select_node(e),this.trigger("refresh_node",{node:b,nodes:a})},this),!1,!0)},set_id:function(b,c){if(b=this.get_node(b),!b||b.id===a.jstree.root)return!1;var d,e,f=this._model.data,g=b.id;for(c=c.toString(),f[b.parent].children[a.inArray(b.id,f[b.parent].children)]=c,d=0,e=b.parents.length;e>d;d++)f[b.parents[d]].children_d[a.inArray(b.id,f[b.parents[d]].children_d)]=c;for(d=0,e=b.children.length;e>d;d++)f[b.children[d]].parent=c;for(d=0,e=b.children_d.length;e>d;d++)f[b.children_d[d]].parents[a.inArray(b.id,f[b.children_d[d]].parents)]=c;return d=a.inArray(b.id,this._data.core.selected),-1!==d&&(this._data.core.selected[d]=c),d=this.get_node(b.id,!0),d&&(d.attr("id",c),this.element.attr("aria-activedescendant")===b.id&&this.element.attr("aria-activedescendant",c)),delete f[b.id],b.id=c,b.li_attr.id=c,f[c]=b,this.trigger("set_id",{node:b,"new":b.id,old:g}),!0},get_text:function(b){return b=this.get_node(b),b&&b.id!==a.jstree.root?b.text:!1},set_text:function(b,c){var d,e;if(a.isArray(b)){for(b=b.slice(),d=0,e=b.length;e>d;d++)this.set_text(b[d],c);return!0}return b=this.get_node(b),b&&b.id!==a.jstree.root?(b.text=c,this.get_node(b,!0).length&&this.redraw_node(b.id),this.trigger("set_text",{obj:b,text:c}),!0):!1},get_json:function(b,c,d){if(b=this.get_node(b||a.jstree.root),!b)return!1;c&&c.flat&&!d&&(d=[]);var e={id:b.id,text:b.text,icon:this.get_icon(b),li_attr:a.extend(!0,{},b.li_attr),a_attr:a.extend(!0,{},b.a_attr),state:{},data:c&&c.no_data?!1:a.extend(!0,{},b.data)},f,g;if(c&&c.flat?e.parent=b.parent:e.children=[],c&&c.no_state)delete e.state;else for(f in b.state)b.state.hasOwnProperty(f)&&(e.state[f]=b.state[f]);if(c&&c.no_li_attr&&delete e.li_attr,c&&c.no_a_attr&&delete e.a_attr,c&&c.no_id&&(delete e.id,e.li_attr&&e.li_attr.id&&delete e.li_attr.id,e.a_attr&&e.a_attr.id&&delete e.a_attr.id),c&&c.flat&&b.id!==a.jstree.root&&d.push(e),!c||!c.no_children)for(f=0,g=b.children.length;g>f;f++)c&&c.flat?this.get_json(b.children[f],c,d):e.children.push(this.get_json(b.children[f],c));return c&&c.flat?d:b.id===a.jstree.root?e.children:e},create_node:function(c,d,e,f,g){if(null===c&&(c=a.jstree.root),c=this.get_node(c),!c)return!1;if(e=e===b?"last":e,!e.toString().match(/^(before|after)$/)&&!g&&!this.is_loaded(c))return this.load_node(c,function(){this.create_node(c,d,e,f,!0)});d||(d={text:this.get_string("New node")}),"string"==typeof d&&(d={text:d}),d.text===b&&(d.text=this.get_string("New node"));var h,i,j,k;switch(c.id===a.jstree.root&&("before"===e&&(e="first"),"after"===e&&(e="last")),e){case"before":h=this.get_node(c.parent),e=a.inArray(c.id,h.children),c=h;break;case"after":h=this.get_node(c.parent),e=a.inArray(c.id,h.children)+1,c=h;break;case"inside":case"first":e=0;break;case"last":e=c.children.length;break;default:e||(e=0)}if(e>c.children.length&&(e=c.children.length),d.id||(d.id=!0),!this.check("create_node",d,c,e))return this.settings.core.error.call(this,this._data.core.last_error),!1;if(d.id===!0&&delete d.id,d=this._parse_model_from_json(d,c.id,c.parents.concat()),!d)return!1;for(h=this.get_node(d),i=[],i.push(d),i=i.concat(h.children_d),this.trigger("model",{nodes:i,parent:c.id}),c.children_d=c.children_d.concat(i),j=0,k=c.parents.length;k>j;j++)this._model.data[c.parents[j]].children_d=this._model.data[c.parents[j]].children_d.concat(i);for(d=h,h=[],j=0,k=c.children.length;k>j;j++)h[j>=e?j+1:j]=c.children[j];return h[e]=d.id,c.children=h,this.redraw_node(c,!0),f&&f.call(this,this.get_node(d)),this.trigger("create_node",{node:this.get_node(d),parent:c.id,position:e}),d.id},rename_node:function(b,c){var d,e,f;if(a.isArray(b)){for(b=b.slice(),d=0,e=b.length;e>d;d++)this.rename_node(b[d],c);return!0}return b=this.get_node(b),b&&b.id!==a.jstree.root?(f=b.text,this.check("rename_node",b,this.get_parent(b),c)?(this.set_text(b,c),this.trigger("rename_node",{node:b,text:c,old:f}),!0):(this.settings.core.error.call(this,this._data.core.last_error),!1)):!1},delete_node:function(b){var c,d,e,f,g,h,i,j,k,l,m,n;if(a.isArray(b)){for(b=b.slice(),c=0,d=b.length;d>c;c++)this.delete_node(b[c]);return!0}if(b=this.get_node(b),!b||b.id===a.jstree.root)return!1;if(e=this.get_node(b.parent),f=a.inArray(b.id,e.children),l=!1,!this.check("delete_node",b,e,f))return this.settings.core.error.call(this,this._data.core.last_error),!1;for(-1!==f&&(e.children=a.vakata.array_remove(e.children,f)),g=b.children_d.concat([]),g.push(b.id),h=0,i=b.parents.length;i>h;h++)this._model.data[b.parents[h]].children_d=a.vakata.array_filter(this._model.data[b.parents[h]].children_d,function(b){return-1===a.inArray(b,g)});for(j=0,k=g.length;k>j;j++)if(this._model.data[g[j]].state.selected){l=!0;break}for(l&&(this._data.core.selected=a.vakata.array_filter(this._data.core.selected,function(b){return-1===a.inArray(b,g)})),this.trigger("delete_node",{node:b,parent:e.id}),l&&this.trigger("changed",{action:"delete_node",node:b,selected:this._data.core.selected,parent:e.id}),j=0,k=g.length;k>j;j++)delete this._model.data[g[j]];return-1!==a.inArray(this._data.core.focused,g)&&(this._data.core.focused=null,m=this.element[0].scrollTop,n=this.element[0].scrollLeft,e.id===a.jstree.root?this._model.data[a.jstree.root].children[0]&&this.get_node(this._model.data[a.jstree.root].children[0],!0).children(".jstree-anchor").focus():this.get_node(e,!0).children(".jstree-anchor").focus(),this.element[0].scrollTop=m,this.element[0].scrollLeft=n),this.redraw_node(e,!0),!0},check:function(b,c,d,e,f){c=c&&c.id?c:this.get_node(c),d=d&&d.id?d:this.get_node(d);var g=b.match(/^move_node|copy_node|create_node$/i)?d:c,h=this.settings.core.check_callback;return"move_node"!==b&&"copy_node"!==b||f&&f.is_multi||c.id!==d.id&&("move_node"!==b||a.inArray(c.id,d.children)!==e)&&-1===a.inArray(d.id,c.children_d)?(g&&g.data&&(g=g.data),g&&g.functions&&(g.functions[b]===!1||g.functions[b]===!0)?(g.functions[b]===!1&&(this._data.core.last_error={error:"check",plugin:"core",id:"core_02",reason:"Node data prevents function: "+b,data:JSON.stringify({chk:b,pos:e,obj:c&&c.id?c.id:!1,par:d&&d.id?d.id:!1})}),g.functions[b]):h===!1||a.isFunction(h)&&h.call(this,b,c,d,e,f)===!1||h&&h[b]===!1?(this._data.core.last_error={error:"check",plugin:"core",id:"core_03",reason:"User config for core.check_callback prevents function: "+b,data:JSON.stringify({chk:b,pos:e,obj:c&&c.id?c.id:!1,par:d&&d.id?d.id:!1})},!1):!0):(this._data.core.last_error={error:"check",plugin:"core",id:"core_01",reason:"Moving parent inside child",data:JSON.stringify({chk:b,pos:e,obj:c&&c.id?c.id:!1,par:d&&d.id?d.id:!1})},!1)},last_error:function(){return this._data.core.last_error},move_node:function(c,d,e,f,g,h,i){var j,k,l,m,n,o,p,q,r,s,t,u,v,w;if(d=this.get_node(d),e=e===b?0:e,!d)return!1;if(!e.toString().match(/^(before|after)$/)&&!g&&!this.is_loaded(d))return this.load_node(d,function(){this.move_node(c,d,e,f,!0,!1,i)});if(a.isArray(c)){if(1!==c.length){for(j=0,k=c.length;k>j;j++)(r=this.move_node(c[j],d,e,f,g,!1,i))&&(d=r,e="after");return this.redraw(),!0}c=c[0]}if(c=c&&c.id?c:this.get_node(c),!c||c.id===a.jstree.root)return!1;if(l=(c.parent||a.jstree.root).toString(),n=e.toString().match(/^(before|after)$/)&&d.id!==a.jstree.root?this.get_node(d.parent):d,o=i?i:this._model.data[c.id]?this:a.jstree.reference(c.id),p=!o||!o._id||this._id!==o._id,m=o&&o._id&&l&&o._model.data[l]&&o._model.data[l].children?a.inArray(c.id,o._model.data[l].children):-1,o&&o._id&&(c=o._model.data[c.id]),p)return(r=this.copy_node(c,d,e,f,g,!1,i))?(o&&o.delete_node(c),r):!1;switch(d.id===a.jstree.root&&("before"===e&&(e="first"),"after"===e&&(e="last")),e){case"before":e=a.inArray(d.id,n.children);break;case"after":e=a.inArray(d.id,n.children)+1;break;case"inside":case"first":e=0;break;case"last":e=n.children.length;break;default:e||(e=0)}if(e>n.children.length&&(e=n.children.length),!this.check("move_node",c,n,e,{core:!0,origin:i,is_multi:o&&o._id&&o._id!==this._id,is_foreign:!o||!o._id}))return this.settings.core.error.call(this,this._data.core.last_error),!1;if(c.parent===n.id){for(q=n.children.concat(),r=a.inArray(c.id,q),-1!==r&&(q=a.vakata.array_remove(q,r),e>r&&e--),r=[],s=0,t=q.length;t>s;s++)r[s>=e?s+1:s]=q[s];r[e]=c.id,n.children=r,this._node_changed(n.id),this.redraw(n.id===a.jstree.root)}else{for(r=c.children_d.concat(),r.push(c.id),s=0,t=c.parents.length;t>s;s++){for(q=[],w=o._model.data[c.parents[s]].children_d,u=0,v=w.length;v>u;u++)-1===a.inArray(w[u],r)&&q.push(w[u]);o._model.data[c.parents[s]].children_d=q}for(o._model.data[l].children=a.vakata.array_remove_item(o._model.data[l].children,c.id),s=0,t=n.parents.length;t>s;s++)this._model.data[n.parents[s]].children_d=this._model.data[n.parents[s]].children_d.concat(r);for(q=[],s=0,t=n.children.length;t>s;s++)q[s>=e?s+1:s]=n.children[s];for(q[e]=c.id,n.children=q,n.children_d.push(c.id),n.children_d=n.children_d.concat(c.children_d),c.parent=n.id,r=n.parents.concat(),r.unshift(n.id),w=c.parents.length,c.parents=r,r=r.concat(),s=0,t=c.children_d.length;t>s;s++)this._model.data[c.children_d[s]].parents=this._model.data[c.children_d[s]].parents.slice(0,-1*w),Array.prototype.push.apply(this._model.data[c.children_d[s]].parents,r);(l===a.jstree.root||n.id===a.jstree.root)&&(this._model.force_full_redraw=!0),this._model.force_full_redraw||(this._node_changed(l),this._node_changed(n.id)),h||this.redraw()}return f&&f.call(this,c,n,e),this.trigger("move_node",{node:c,parent:n.id,position:e,old_parent:l,old_position:m,is_multi:o&&o._id&&o._id!==this._id,is_foreign:!o||!o._id,old_instance:o,new_instance:this}),c.id},copy_node:function(c,d,e,f,g,h,i){var j,k,l,m,n,o,p,q,r,s,t;if(d=this.get_node(d),e=e===b?0:e,!d)return!1;if(!e.toString().match(/^(before|after)$/)&&!g&&!this.is_loaded(d))return this.load_node(d,function(){this.copy_node(c,d,e,f,!0,!1,i)});if(a.isArray(c)){if(1!==c.length){for(j=0,k=c.length;k>j;j++)(m=this.copy_node(c[j],d,e,f,g,!0,i))&&(d=m,e="after");return this.redraw(),!0}c=c[0]}if(c=c&&c.id?c:this.get_node(c),!c||c.id===a.jstree.root)return!1;switch(q=(c.parent||a.jstree.root).toString(),r=e.toString().match(/^(before|after)$/)&&d.id!==a.jstree.root?this.get_node(d.parent):d,s=i?i:this._model.data[c.id]?this:a.jstree.reference(c.id),t=!s||!s._id||this._id!==s._id,s&&s._id&&(c=s._model.data[c.id]),d.id===a.jstree.root&&("before"===e&&(e="first"),"after"===e&&(e="last")),e){case"before":e=a.inArray(d.id,r.children);break;case"after":e=a.inArray(d.id,r.children)+1;break;case"inside":case"first":e=0;break;case"last":e=r.children.length;break;default:e||(e=0)}if(e>r.children.length&&(e=r.children.length),!this.check("copy_node",c,r,e,{core:!0,origin:i,is_multi:s&&s._id&&s._id!==this._id,is_foreign:!s||!s._id}))return this.settings.core.error.call(this,this._data.core.last_error),!1;if(p=s?s.get_json(c,{no_id:!0,no_data:!0,no_state:!0}):c,!p)return!1;if(p.id===!0&&delete p.id,p=this._parse_model_from_json(p,r.id,r.parents.concat()),!p)return!1;for(m=this.get_node(p),c&&c.state&&c.state.loaded===!1&&(m.state.loaded=!1),l=[],l.push(p),l=l.concat(m.children_d),this.trigger("model",{nodes:l,parent:r.id}),n=0,o=r.parents.length;o>n;n++)this._model.data[r.parents[n]].children_d=this._model.data[r.parents[n]].children_d.concat(l);for(l=[],n=0,o=r.children.length;o>n;n++)l[n>=e?n+1:n]=r.children[n];return l[e]=m.id,r.children=l,r.children_d.push(m.id),r.children_d=r.children_d.concat(m.children_d),r.id===a.jstree.root&&(this._model.force_full_redraw=!0),this._model.force_full_redraw||this._node_changed(r.id),h||this.redraw(r.id===a.jstree.root),f&&f.call(this,m,r,e),this.trigger("copy_node",{node:m,original:c,parent:r.id,position:e,old_parent:q,old_position:s&&s._id&&q&&s._model.data[q]&&s._model.data[q].children?a.inArray(c.id,s._model.data[q].children):-1,is_multi:s&&s._id&&s._id!==this._id,is_foreign:!s||!s._id,old_instance:s,new_instance:this}),m.id},cut:function(b){if(b||(b=this._data.core.selected.concat()),a.isArray(b)||(b=[b]),!b.length)return!1;var c=[],g,h,i;for(h=0,i=b.length;i>h;h++)g=this.get_node(b[h]),g&&g.id&&g.id!==a.jstree.root&&c.push(g);return c.length?(d=c,f=this,e="move_node",void this.trigger("cut",{node:b})):!1},copy:function(b){if(b||(b=this._data.core.selected.concat()),a.isArray(b)||(b=[b]),!b.length)return!1;var c=[],g,h,i;for(h=0,i=b.length;i>h;h++)g=this.get_node(b[h]),g&&g.id&&g.id!==a.jstree.root&&c.push(g);return c.length?(d=c,f=this,e="copy_node",void this.trigger("copy",{node:b})):!1},get_buffer:function(){return{mode:e,node:d,inst:f}},can_paste:function(){return e!==!1&&d!==!1},paste:function(a,b){return a=this.get_node(a),a&&e&&e.match(/^(copy_node|move_node)$/)&&d?(this[e](d,a,b,!1,!1,!1,f)&&this.trigger("paste",{parent:a.id,node:d,mode:e}),d=!1,e=!1,void(f=!1)):!1},clear_buffer:function(){d=!1,e=!1,f=!1,this.trigger("clear_buffer")},edit:function(b,c,d){var e,f,g,h,j,k,l,m,n,o=!1;return(b=this.get_node(b))?this.settings.core.check_callback===!1?(this._data.core.last_error={error:"check",plugin:"core",id:"core_07",reason:"Could not edit node because of check_callback"},this.settings.core.error.call(this,this._data.core.last_error),!1):(n=b,c="string"==typeof c?c:b.text,this.set_text(b,""),b=this._open_to(b),n.text=c,e=this._data.core.rtl,f=this.element.width(),this._data.core.focused=n.id,g=b.children(".jstree-anchor").focus(),h=a("<span>"),j=c,k=a("<div />",{css:{position:"absolute",top:"-200px",left:e?"0px":"-1000px",visibility:"hidden"}}).appendTo("body"),l=a("<input />",{value:j,"class":"jstree-rename-input",css:{padding:"0",border:"1px solid silver","box-sizing":"border-box",display:"inline-block",height:this._data.core.li_height+"px",lineHeight:this._data.core.li_height+"px",width:"150px"},blur:a.proxy(function(c){c.stopImmediatePropagation(),c.preventDefault();var e=h.children(".jstree-rename-input"),f=e.val(),i=this.settings.core.force_text,m;""===f&&(f=j),k.remove(),h.replaceWith(g),h.remove(),j=i?j:a("<div></div>").append(a.parseHTML(j)).html(),this.set_text(b,j),m=!!this.rename_node(b,i?a("<div></div>").text(f).text():a("<div></div>").append(a.parseHTML(f)).html()),m||this.set_text(b,j),this._data.core.focused=n.id,setTimeout(a.proxy(function(){var a=this.get_node(n.id,!0);a.length&&(this._data.core.focused=n.id,a.children(".jstree-anchor").focus())},this),0),d&&d.call(this,n,m,o),l=null},this),keydown:function(a){var b=a.which;27===b&&(o=!0,this.value=j),(27===b||13===b||37===b||38===b||39===b||40===b||32===b)&&a.stopImmediatePropagation(),(27===b||13===b)&&(a.preventDefault(),this.blur())},click:function(a){a.stopImmediatePropagation()},mousedown:function(a){a.stopImmediatePropagation()},keyup:function(a){l.width(Math.min(k.text("pW"+this.value).width(),f))},keypress:function(a){return 13===a.which?!1:void 0}}),m={fontFamily:g.css("fontFamily")||"",fontSize:g.css("fontSize")||"",fontWeight:g.css("fontWeight")||"",fontStyle:g.css("fontStyle")||"",fontStretch:g.css("fontStretch")||"",fontVariant:g.css("fontVariant")||"",letterSpacing:g.css("letterSpacing")||"",wordSpacing:g.css("wordSpacing")||""},h.attr("class",g.attr("class")).append(g.contents().clone()).append(l),g.replaceWith(h),k.css(m),l.css(m).width(Math.min(k.text("pW"+l[0].value).width(),f))[0].select(),
void a(i).one("mousedown.jstree touchstart.jstree dnd_start.vakata",function(b){l&&b.target!==l&&a(l).blur()})):!1},set_theme:function(b,c){if(!b)return!1;if(c===!0){var d=this.settings.core.themes.dir;d||(d=a.jstree.path+"/themes"),c=d+"/"+b+"/style.css"}c&&-1===a.inArray(c,g)&&(a("head").append('<link rel="stylesheet" href="'+c+'" type="text/css" />'),g.push(c)),this._data.core.themes.name&&this.element.removeClass("jstree-"+this._data.core.themes.name),this._data.core.themes.name=b,this.element.addClass("jstree-"+b),this.element[this.settings.core.themes.responsive?"addClass":"removeClass"]("jstree-"+b+"-responsive"),this.trigger("set_theme",{theme:b})},get_theme:function(){return this._data.core.themes.name},set_theme_variant:function(a){this._data.core.themes.variant&&this.element.removeClass("jstree-"+this._data.core.themes.name+"-"+this._data.core.themes.variant),this._data.core.themes.variant=a,a&&this.element.addClass("jstree-"+this._data.core.themes.name+"-"+this._data.core.themes.variant)},get_theme_variant:function(){return this._data.core.themes.variant},show_stripes:function(){this._data.core.themes.stripes=!0,this.get_container_ul().addClass("jstree-striped")},hide_stripes:function(){this._data.core.themes.stripes=!1,this.get_container_ul().removeClass("jstree-striped")},toggle_stripes:function(){this._data.core.themes.stripes?this.hide_stripes():this.show_stripes()},show_dots:function(){this._data.core.themes.dots=!0,this.get_container_ul().removeClass("jstree-no-dots")},hide_dots:function(){this._data.core.themes.dots=!1,this.get_container_ul().addClass("jstree-no-dots")},toggle_dots:function(){this._data.core.themes.dots?this.hide_dots():this.show_dots()},show_icons:function(){this._data.core.themes.icons=!0,this.get_container_ul().removeClass("jstree-no-icons")},hide_icons:function(){this._data.core.themes.icons=!1,this.get_container_ul().addClass("jstree-no-icons")},toggle_icons:function(){this._data.core.themes.icons?this.hide_icons():this.show_icons()},set_icon:function(c,d){var e,f,g,h;if(a.isArray(c)){for(c=c.slice(),e=0,f=c.length;f>e;e++)this.set_icon(c[e],d);return!0}return c=this.get_node(c),c&&c.id!==a.jstree.root?(h=c.icon,c.icon=d===!0||null===d||d===b||""===d?!0:d,g=this.get_node(c,!0).children(".jstree-anchor").children(".jstree-themeicon"),d===!1?this.hide_icon(c):d===!0||null===d||d===b||""===d?(g.removeClass("jstree-themeicon-custom "+h).css("background","").removeAttr("rel"),h===!1&&this.show_icon(c)):-1===d.indexOf("/")&&-1===d.indexOf(".")?(g.removeClass(h).css("background",""),g.addClass(d+" jstree-themeicon-custom").attr("rel",d),h===!1&&this.show_icon(c)):(g.removeClass(h).css("background",""),g.addClass("jstree-themeicon-custom").css("background","url('"+d+"') center center no-repeat").attr("rel",d),h===!1&&this.show_icon(c)),!0):!1},get_icon:function(b){return b=this.get_node(b),b&&b.id!==a.jstree.root?b.icon:!1},hide_icon:function(b){var c,d;if(a.isArray(b)){for(b=b.slice(),c=0,d=b.length;d>c;c++)this.hide_icon(b[c]);return!0}return b=this.get_node(b),b&&b!==a.jstree.root?(b.icon=!1,this.get_node(b,!0).children(".jstree-anchor").children(".jstree-themeicon").addClass("jstree-themeicon-hidden"),!0):!1},show_icon:function(b){var c,d,e;if(a.isArray(b)){for(b=b.slice(),c=0,d=b.length;d>c;c++)this.show_icon(b[c]);return!0}return b=this.get_node(b),b&&b!==a.jstree.root?(e=this.get_node(b,!0),b.icon=e.length?e.children(".jstree-anchor").children(".jstree-themeicon").attr("rel"):!0,b.icon||(b.icon=!0),e.children(".jstree-anchor").children(".jstree-themeicon").removeClass("jstree-themeicon-hidden"),!0):!1}},a.vakata={},a.vakata.attributes=function(b,c){b=a(b)[0];var d=c?{}:[];return b&&b.attributes&&a.each(b.attributes,function(b,e){-1===a.inArray(e.name.toLowerCase(),["style","contenteditable","hasfocus","tabindex"])&&null!==e.value&&""!==a.trim(e.value)&&(c?d[e.name]=e.value:d.push(e.name))}),d},a.vakata.array_unique=function(a){var c=[],d,e,f,g={};for(d=0,f=a.length;f>d;d++)g[a[d]]===b&&(c.push(a[d]),g[a[d]]=!0);return c},a.vakata.array_remove=function(a,b){return a.splice(b,1),a},a.vakata.array_remove_item=function(b,c){var d=a.inArray(c,b);return-1!==d?a.vakata.array_remove(b,d):b},a.vakata.array_filter=function(a,b,c,d,e){if(a.filter)return a.filter(b,c);d=[];for(e in a)~~e+""==e+""&&e>=0&&b.call(c,a[e],+e,a)&&d.push(a[e]);return d},a.jstree.plugins.changed=function(a,b){var c=[];this.trigger=function(a,d){var e,f;if(d||(d={}),"changed"===a.replace(".jstree","")){d.changed={selected:[],deselected:[]};var g={};for(e=0,f=c.length;f>e;e++)g[c[e]]=1;for(e=0,f=d.selected.length;f>e;e++)g[d.selected[e]]?g[d.selected[e]]=2:d.changed.selected.push(d.selected[e]);for(e=0,f=c.length;f>e;e++)1===g[c[e]]&&d.changed.deselected.push(c[e]);c=d.selected.slice()}b.trigger.call(this,a,d)},this.refresh=function(a,d){return c=[],b.refresh.apply(this,arguments)}};var m=i.createElement("I");m.className="jstree-icon jstree-checkbox",m.setAttribute("role","presentation"),a.jstree.defaults.checkbox={visible:!0,three_state:!0,whole_node:!0,keep_selected_style:!0,cascade:"",tie_selection:!0},a.jstree.plugins.checkbox=function(c,d){this.bind=function(){d.bind.call(this),this._data.checkbox.uto=!1,this._data.checkbox.selected=[],this.settings.checkbox.three_state&&(this.settings.checkbox.cascade="up+down+undetermined"),this.element.on("init.jstree",a.proxy(function(){this._data.checkbox.visible=this.settings.checkbox.visible,this.settings.checkbox.keep_selected_style||this.element.addClass("jstree-checkbox-no-clicked"),this.settings.checkbox.tie_selection&&this.element.addClass("jstree-checkbox-selection")},this)).on("loading.jstree",a.proxy(function(){this[this._data.checkbox.visible?"show_checkboxes":"hide_checkboxes"]()},this)),-1!==this.settings.checkbox.cascade.indexOf("undetermined")&&this.element.on("changed.jstree uncheck_node.jstree check_node.jstree uncheck_all.jstree check_all.jstree move_node.jstree copy_node.jstree redraw.jstree open_node.jstree",a.proxy(function(){this._data.checkbox.uto&&clearTimeout(this._data.checkbox.uto),this._data.checkbox.uto=setTimeout(a.proxy(this._undetermined,this),50)},this)),this.settings.checkbox.tie_selection||this.element.on("model.jstree",a.proxy(function(a,b){var c=this._model.data,d=c[b.parent],e=b.nodes,f,g;for(f=0,g=e.length;g>f;f++)c[e[f]].state.checked=c[e[f]].state.checked||c[e[f]].original&&c[e[f]].original.state&&c[e[f]].original.state.checked,c[e[f]].state.checked&&this._data.checkbox.selected.push(e[f])},this)),(-1!==this.settings.checkbox.cascade.indexOf("up")||-1!==this.settings.checkbox.cascade.indexOf("down"))&&this.element.on("model.jstree",a.proxy(function(b,c){var d=this._model.data,e=d[c.parent],f=c.nodes,g=[],h,i,j,k,l,m,n=this.settings.checkbox.cascade,o=this.settings.checkbox.tie_selection;if(-1!==n.indexOf("down"))if(e.state[o?"selected":"checked"]){for(i=0,j=f.length;j>i;i++)d[f[i]].state[o?"selected":"checked"]=!0;this._data[o?"core":"checkbox"].selected=this._data[o?"core":"checkbox"].selected.concat(f)}else for(i=0,j=f.length;j>i;i++)if(d[f[i]].state[o?"selected":"checked"]){for(k=0,l=d[f[i]].children_d.length;l>k;k++)d[d[f[i]].children_d[k]].state[o?"selected":"checked"]=!0;this._data[o?"core":"checkbox"].selected=this._data[o?"core":"checkbox"].selected.concat(d[f[i]].children_d)}if(-1!==n.indexOf("up")){for(i=0,j=e.children_d.length;j>i;i++)d[e.children_d[i]].children.length||g.push(d[e.children_d[i]].parent);for(g=a.vakata.array_unique(g),k=0,l=g.length;l>k;k++){e=d[g[k]];while(e&&e.id!==a.jstree.root){for(h=0,i=0,j=e.children.length;j>i;i++)h+=d[e.children[i]].state[o?"selected":"checked"];if(h!==j)break;e.state[o?"selected":"checked"]=!0,this._data[o?"core":"checkbox"].selected.push(e.id),m=this.get_node(e,!0),m&&m.length&&m.attr("aria-selected",!0).children(".jstree-anchor").addClass(o?"jstree-clicked":"jstree-checked"),e=this.get_node(e.parent)}}}this._data[o?"core":"checkbox"].selected=a.vakata.array_unique(this._data[o?"core":"checkbox"].selected)},this)).on(this.settings.checkbox.tie_selection?"select_node.jstree":"check_node.jstree",a.proxy(function(b,c){var d=c.node,e=this._model.data,f=this.get_node(d.parent),g=this.get_node(d,!0),h,i,j,k,l=this.settings.checkbox.cascade,m=this.settings.checkbox.tie_selection,n={},o=this._data[m?"core":"checkbox"].selected;for(h=0,i=o.length;i>h;h++)n[o[h]]=!0;if(-1!==l.indexOf("down"))for(h=0,i=d.children_d.length;i>h;h++)n[d.children_d[h]]=!0,k=e[d.children_d[h]],k.state[m?"selected":"checked"]=!0,k&&k.original&&k.original.state&&k.original.state.undetermined&&(k.original.state.undetermined=!1);if(-1!==l.indexOf("up"))while(f&&f.id!==a.jstree.root){for(j=0,h=0,i=f.children.length;i>h;h++)j+=e[f.children[h]].state[m?"selected":"checked"];if(j!==i)break;f.state[m?"selected":"checked"]=!0,n[f.id]=!0,k=this.get_node(f,!0),k&&k.length&&k.attr("aria-selected",!0).children(".jstree-anchor").addClass(m?"jstree-clicked":"jstree-checked"),f=this.get_node(f.parent)}o=[];for(h in n)n.hasOwnProperty(h)&&o.push(h);this._data[m?"core":"checkbox"].selected=o,-1!==l.indexOf("down")&&g.length&&g.find(".jstree-anchor").addClass(m?"jstree-clicked":"jstree-checked").parent().attr("aria-selected",!0)},this)).on(this.settings.checkbox.tie_selection?"deselect_all.jstree":"uncheck_all.jstree",a.proxy(function(b,c){var d=this.get_node(a.jstree.root),e=this._model.data,f,g,h;for(f=0,g=d.children_d.length;g>f;f++)h=e[d.children_d[f]],h&&h.original&&h.original.state&&h.original.state.undetermined&&(h.original.state.undetermined=!1)},this)).on(this.settings.checkbox.tie_selection?"deselect_node.jstree":"uncheck_node.jstree",a.proxy(function(b,c){var d=c.node,e=this.get_node(d,!0),f,g,h,i=this.settings.checkbox.cascade,j=this.settings.checkbox.tie_selection,k=this._data[j?"core":"checkbox"].selected,l={};if(d&&d.original&&d.original.state&&d.original.state.undetermined&&(d.original.state.undetermined=!1),-1!==i.indexOf("down"))for(f=0,g=d.children_d.length;g>f;f++)h=this._model.data[d.children_d[f]],h.state[j?"selected":"checked"]=!1,h&&h.original&&h.original.state&&h.original.state.undetermined&&(h.original.state.undetermined=!1);if(-1!==i.indexOf("up"))for(f=0,g=d.parents.length;g>f;f++)h=this._model.data[d.parents[f]],h.state[j?"selected":"checked"]=!1,h&&h.original&&h.original.state&&h.original.state.undetermined&&(h.original.state.undetermined=!1),h=this.get_node(d.parents[f],!0),h&&h.length&&h.attr("aria-selected",!1).children(".jstree-anchor").removeClass(j?"jstree-clicked":"jstree-checked");for(l={},f=0,g=k.length;g>f;f++)-1!==i.indexOf("down")&&-1!==a.inArray(k[f],d.children_d)||-1!==i.indexOf("up")&&-1!==a.inArray(k[f],d.parents)||(l[k[f]]=!0);k=[];for(f in l)l.hasOwnProperty(f)&&k.push(f);this._data[j?"core":"checkbox"].selected=k,-1!==i.indexOf("down")&&e.length&&e.find(".jstree-anchor").removeClass(j?"jstree-clicked":"jstree-checked").parent().attr("aria-selected",!1)},this)),-1!==this.settings.checkbox.cascade.indexOf("up")&&this.element.on("delete_node.jstree",a.proxy(function(b,c){var d=this.get_node(c.parent),e=this._model.data,f,g,h,i,j=this.settings.checkbox.tie_selection;while(d&&d.id!==a.jstree.root&&!d.state[j?"selected":"checked"]){for(h=0,f=0,g=d.children.length;g>f;f++)h+=e[d.children[f]].state[j?"selected":"checked"];if(!(g>0&&h===g))break;d.state[j?"selected":"checked"]=!0,this._data[j?"core":"checkbox"].selected.push(d.id),i=this.get_node(d,!0),i&&i.length&&i.attr("aria-selected",!0).children(".jstree-anchor").addClass(j?"jstree-clicked":"jstree-checked"),d=this.get_node(d.parent)}},this)).on("move_node.jstree",a.proxy(function(b,c){var d=c.is_multi,e=c.old_parent,f=this.get_node(c.parent),g=this._model.data,h,i,j,k,l,m=this.settings.checkbox.tie_selection;if(!d){h=this.get_node(e);while(h&&h.id!==a.jstree.root&&!h.state[m?"selected":"checked"]){for(i=0,j=0,k=h.children.length;k>j;j++)i+=g[h.children[j]].state[m?"selected":"checked"];if(!(k>0&&i===k))break;h.state[m?"selected":"checked"]=!0,this._data[m?"core":"checkbox"].selected.push(h.id),l=this.get_node(h,!0),l&&l.length&&l.attr("aria-selected",!0).children(".jstree-anchor").addClass(m?"jstree-clicked":"jstree-checked"),h=this.get_node(h.parent)}}h=f;while(h&&h.id!==a.jstree.root){for(i=0,j=0,k=h.children.length;k>j;j++)i+=g[h.children[j]].state[m?"selected":"checked"];if(i===k)h.state[m?"selected":"checked"]||(h.state[m?"selected":"checked"]=!0,this._data[m?"core":"checkbox"].selected.push(h.id),l=this.get_node(h,!0),l&&l.length&&l.attr("aria-selected",!0).children(".jstree-anchor").addClass(m?"jstree-clicked":"jstree-checked"));else{if(!h.state[m?"selected":"checked"])break;h.state[m?"selected":"checked"]=!1,this._data[m?"core":"checkbox"].selected=a.vakata.array_remove_item(this._data[m?"core":"checkbox"].selected,h.id),l=this.get_node(h,!0),l&&l.length&&l.attr("aria-selected",!1).children(".jstree-anchor").removeClass(m?"jstree-clicked":"jstree-checked")}h=this.get_node(h.parent)}},this))},this._undetermined=function(){if(null!==this.element){var c,d,e,f,g={},h=this._model.data,i=this.settings.checkbox.tie_selection,j=this._data[i?"core":"checkbox"].selected,k=[],l=this;for(c=0,d=j.length;d>c;c++)if(h[j[c]]&&h[j[c]].parents)for(e=0,f=h[j[c]].parents.length;f>e;e++){if(g[h[j[c]].parents[e]]!==b)break;h[j[c]].parents[e]!==a.jstree.root&&(g[h[j[c]].parents[e]]=!0,k.push(h[j[c]].parents[e]))}for(this.element.find(".jstree-closed").not(":has(.jstree-children)").each(function(){var i=l.get_node(this),j;if(i.state.loaded){for(c=0,d=i.children_d.length;d>c;c++)if(j=h[i.children_d[c]],!j.state.loaded&&j.original&&j.original.state&&j.original.state.undetermined&&j.original.state.undetermined===!0)for(g[j.id]===b&&j.id!==a.jstree.root&&(g[j.id]=!0,k.push(j.id)),e=0,f=j.parents.length;f>e;e++)g[j.parents[e]]===b&&j.parents[e]!==a.jstree.root&&(g[j.parents[e]]=!0,k.push(j.parents[e]))}else if(i.original&&i.original.state&&i.original.state.undetermined&&i.original.state.undetermined===!0)for(g[i.id]===b&&i.id!==a.jstree.root&&(g[i.id]=!0,k.push(i.id)),e=0,f=i.parents.length;f>e;e++)g[i.parents[e]]===b&&i.parents[e]!==a.jstree.root&&(g[i.parents[e]]=!0,k.push(i.parents[e]))}),this.element.find(".jstree-undetermined").removeClass("jstree-undetermined"),c=0,d=k.length;d>c;c++)h[k[c]].state[i?"selected":"checked"]||(j=this.get_node(k[c],!0),j&&j.length&&j.children(".jstree-anchor").children(".jstree-checkbox").addClass("jstree-undetermined"))}},this.redraw_node=function(b,c,e,f){if(b=d.redraw_node.apply(this,arguments)){var g,h,i=null,j=null;for(g=0,h=b.childNodes.length;h>g;g++)if(b.childNodes[g]&&b.childNodes[g].className&&-1!==b.childNodes[g].className.indexOf("jstree-anchor")){i=b.childNodes[g];break}i&&(!this.settings.checkbox.tie_selection&&this._model.data[b.id].state.checked&&(i.className+=" jstree-checked"),j=m.cloneNode(!1),this._model.data[b.id].state.checkbox_disabled&&(j.className+=" jstree-checkbox-disabled"),i.insertBefore(j,i.childNodes[0]))}return e||-1===this.settings.checkbox.cascade.indexOf("undetermined")||(this._data.checkbox.uto&&clearTimeout(this._data.checkbox.uto),this._data.checkbox.uto=setTimeout(a.proxy(this._undetermined,this),50)),b},this.show_checkboxes=function(){this._data.core.themes.checkboxes=!0,this.get_container_ul().removeClass("jstree-no-checkboxes")},this.hide_checkboxes=function(){this._data.core.themes.checkboxes=!1,this.get_container_ul().addClass("jstree-no-checkboxes")},this.toggle_checkboxes=function(){this._data.core.themes.checkboxes?this.hide_checkboxes():this.show_checkboxes()},this.is_undetermined=function(b){b=this.get_node(b);var c=this.settings.checkbox.cascade,d,e,f=this.settings.checkbox.tie_selection,g=this._data[f?"core":"checkbox"].selected,h=this._model.data;if(!b||b.state[f?"selected":"checked"]===!0||-1===c.indexOf("undetermined")||-1===c.indexOf("down")&&-1===c.indexOf("up"))return!1;if(!b.state.loaded&&b.original.state.undetermined===!0)return!0;for(d=0,e=b.children_d.length;e>d;d++)if(-1!==a.inArray(b.children_d[d],g)||!h[b.children_d[d]].state.loaded&&h[b.children_d[d]].original.state.undetermined)return!0;return!1},this.disable_checkbox=function(b){var c,d,e;if(a.isArray(b)){for(b=b.slice(),c=0,d=b.length;d>c;c++)this.disable_checkbox(b[c]);return!0}return b=this.get_node(b),b&&b.id!==a.jstree.root?(e=this.get_node(b,!0),void(b.state.checkbox_disabled||(b.state.checkbox_disabled=!0,e&&e.length&&e.children(".jstree-anchor").children(".jstree-checkbox").addClass("jstree-checkbox-disabled"),this.trigger("disable_checkbox",{node:b})))):!1},this.enable_checkbox=function(b){var c,d,e;if(a.isArray(b)){for(b=b.slice(),c=0,d=b.length;d>c;c++)this.enable_checkbox(b[c]);return!0}return b=this.get_node(b),b&&b.id!==a.jstree.root?(e=this.get_node(b,!0),void(b.state.checkbox_disabled&&(b.state.checkbox_disabled=!1,e&&e.length&&e.children(".jstree-anchor").children(".jstree-checkbox").removeClass("jstree-checkbox-disabled"),this.trigger("enable_checkbox",{node:b})))):!1},this.activate_node=function(b,c){return a(c.target).hasClass("jstree-checkbox-disabled")?!1:(this.settings.checkbox.tie_selection&&(this.settings.checkbox.whole_node||a(c.target).hasClass("jstree-checkbox"))&&(c.ctrlKey=!0),this.settings.checkbox.tie_selection||!this.settings.checkbox.whole_node&&!a(c.target).hasClass("jstree-checkbox")?d.activate_node.call(this,b,c):this.is_disabled(b)?!1:(this.is_checked(b)?this.uncheck_node(b,c):this.check_node(b,c),void this.trigger("activate_node",{node:this.get_node(b)})))},this.check_node=function(b,c){if(this.settings.checkbox.tie_selection)return this.select_node(b,!1,!0,c);var d,e,f,g;if(a.isArray(b)){for(b=b.slice(),e=0,f=b.length;f>e;e++)this.check_node(b[e],c);return!0}return b=this.get_node(b),b&&b.id!==a.jstree.root?(d=this.get_node(b,!0),void(b.state.checked||(b.state.checked=!0,this._data.checkbox.selected.push(b.id),d&&d.length&&d.children(".jstree-anchor").addClass("jstree-checked"),this.trigger("check_node",{node:b,selected:this._data.checkbox.selected,event:c})))):!1},this.uncheck_node=function(b,c){if(this.settings.checkbox.tie_selection)return this.deselect_node(b,!1,c);var d,e,f;if(a.isArray(b)){for(b=b.slice(),d=0,e=b.length;e>d;d++)this.uncheck_node(b[d],c);return!0}return b=this.get_node(b),b&&b.id!==a.jstree.root?(f=this.get_node(b,!0),void(b.state.checked&&(b.state.checked=!1,this._data.checkbox.selected=a.vakata.array_remove_item(this._data.checkbox.selected,b.id),f.length&&f.children(".jstree-anchor").removeClass("jstree-checked"),this.trigger("uncheck_node",{node:b,selected:this._data.checkbox.selected,event:c})))):!1},this.check_all=function(){if(this.settings.checkbox.tie_selection)return this.select_all();var b=this._data.checkbox.selected.concat([]),c,d;for(this._data.checkbox.selected=this._model.data[a.jstree.root].children_d.concat(),c=0,d=this._data.checkbox.selected.length;d>c;c++)this._model.data[this._data.checkbox.selected[c]]&&(this._model.data[this._data.checkbox.selected[c]].state.checked=!0);this.redraw(!0),this.trigger("check_all",{selected:this._data.checkbox.selected})},this.uncheck_all=function(){if(this.settings.checkbox.tie_selection)return this.deselect_all();var a=this._data.checkbox.selected.concat([]),b,c;for(b=0,c=this._data.checkbox.selected.length;c>b;b++)this._model.data[this._data.checkbox.selected[b]]&&(this._model.data[this._data.checkbox.selected[b]].state.checked=!1);this._data.checkbox.selected=[],this.element.find(".jstree-checked").removeClass("jstree-checked"),this.trigger("uncheck_all",{selected:this._data.checkbox.selected,node:a})},this.is_checked=function(b){return this.settings.checkbox.tie_selection?this.is_selected(b):(b=this.get_node(b),b&&b.id!==a.jstree.root?b.state.checked:!1)},this.get_checked=function(b){return this.settings.checkbox.tie_selection?this.get_selected(b):b?a.map(this._data.checkbox.selected,a.proxy(function(a){return this.get_node(a)},this)):this._data.checkbox.selected},this.get_top_checked=function(b){if(this.settings.checkbox.tie_selection)return this.get_top_selected(b);var c=this.get_checked(!0),d={},e,f,g,h;for(e=0,f=c.length;f>e;e++)d[c[e].id]=c[e];for(e=0,f=c.length;f>e;e++)for(g=0,h=c[e].children_d.length;h>g;g++)d[c[e].children_d[g]]&&delete d[c[e].children_d[g]];c=[];for(e in d)d.hasOwnProperty(e)&&c.push(e);return b?a.map(c,a.proxy(function(a){return this.get_node(a)},this)):c},this.get_bottom_checked=function(b){if(this.settings.checkbox.tie_selection)return this.get_bottom_selected(b);var c=this.get_checked(!0),d=[],e,f;for(e=0,f=c.length;f>e;e++)c[e].children.length||d.push(c[e].id);return b?a.map(d,a.proxy(function(a){return this.get_node(a)},this)):d},this.load_node=function(b,c){var e,f,g,h,i,j;if(!a.isArray(b)&&!this.settings.checkbox.tie_selection&&(j=this.get_node(b),j&&j.state.loaded))for(e=0,f=j.children_d.length;f>e;e++)this._model.data[j.children_d[e]].state.checked&&(i=!0,this._data.checkbox.selected=a.vakata.array_remove_item(this._data.checkbox.selected,j.children_d[e]));return d.load_node.apply(this,arguments)},this.get_state=function(){var a=d.get_state.apply(this,arguments);return this.settings.checkbox.tie_selection?a:(a.checkbox=this._data.checkbox.selected.slice(),a)},this.set_state=function(b,c){var e=d.set_state.apply(this,arguments);if(e&&b.checkbox){if(!this.settings.checkbox.tie_selection){this.uncheck_all();var f=this;a.each(b.checkbox,function(a,b){f.check_node(b)})}return delete b.checkbox,this.set_state(b,c),!1}return e},this.refresh=function(a,b){return this.settings.checkbox.tie_selection||(this._data.checkbox.selected=[]),d.refresh.apply(this,arguments)}},a.jstree.defaults.conditionalselect=function(){return!0},a.jstree.plugins.conditionalselect=function(a,b){this.activate_node=function(a,c){this.settings.conditionalselect.call(this,this.get_node(a),c)&&b.activate_node.call(this,a,c)}},a.jstree.defaults.contextmenu={select_node:!0,show_at_node:!0,items:function(b,c){return{create:{separator_before:!1,separator_after:!0,_disabled:!1,label:"Create",action:function(b){var c=a.jstree.reference(b.reference),d=c.get_node(b.reference);c.create_node(d,{},"last",function(a){setTimeout(function(){c.edit(a)},0)})}},rename:{separator_before:!1,separator_after:!1,_disabled:!1,label:"Rename",action:function(b){var c=a.jstree.reference(b.reference),d=c.get_node(b.reference);c.edit(d)}},remove:{separator_before:!1,icon:!1,separator_after:!1,_disabled:!1,label:"Delete",action:function(b){var c=a.jstree.reference(b.reference),d=c.get_node(b.reference);c.is_selected(d)?c.delete_node(c.get_selected()):c.delete_node(d)}},ccp:{separator_before:!0,icon:!1,separator_after:!1,label:"Edit",action:!1,submenu:{cut:{separator_before:!1,separator_after:!1,label:"Cut",action:function(b){var c=a.jstree.reference(b.reference),d=c.get_node(b.reference);c.is_selected(d)?c.cut(c.get_top_selected()):c.cut(d)}},copy:{separator_before:!1,icon:!1,separator_after:!1,label:"Copy",action:function(b){var c=a.jstree.reference(b.reference),d=c.get_node(b.reference);c.is_selected(d)?c.copy(c.get_top_selected()):c.copy(d)}},paste:{separator_before:!1,icon:!1,_disabled:function(b){return!a.jstree.reference(b.reference).can_paste()},separator_after:!1,label:"Paste",action:function(b){var c=a.jstree.reference(b.reference),d=c.get_node(b.reference);c.paste(d)}}}}}}},a.jstree.plugins.contextmenu=function(c,d){this.bind=function(){d.bind.call(this);var b=0,c=null,e,f;this.element.on("contextmenu.jstree",".jstree-anchor",a.proxy(function(a,d){"input"!==a.target.tagName.toLowerCase()&&(a.preventDefault(),b=a.ctrlKey?+new Date:0,(d||c)&&(b=+new Date+1e4),c&&clearTimeout(c),this.is_loading(a.currentTarget)||this.show_contextmenu(a.currentTarget,a.pageX,a.pageY,a))},this)).on("click.jstree",".jstree-anchor",a.proxy(function(c){this._data.contextmenu.visible&&(!b||+new Date-b>250)&&a.vakata.context.hide(),b=0},this)).on("touchstart.jstree",".jstree-anchor",function(b){b.originalEvent&&b.originalEvent.changedTouches&&b.originalEvent.changedTouches[0]&&(e=b.originalEvent.changedTouches[0].clientX,f=b.originalEvent.changedTouches[0].clientY,c=setTimeout(function(){a(b.currentTarget).trigger("contextmenu",!0)},750))}).on("touchmove.vakata.jstree",function(a){c&&a.originalEvent&&a.originalEvent.changedTouches&&a.originalEvent.changedTouches[0]&&(Math.abs(e-a.originalEvent.changedTouches[0].clientX)>50||Math.abs(f-a.originalEvent.changedTouches[0].clientY)>50)&&clearTimeout(c)}).on("touchend.vakata.jstree",function(a){c&&clearTimeout(c)}),a(i).on("context_hide.vakata.jstree",a.proxy(function(b,c){this._data.contextmenu.visible=!1,a(c.reference).removeClass("jstree-context")},this))},this.teardown=function(){this._data.contextmenu.visible&&a.vakata.context.hide(),d.teardown.call(this)},this.show_contextmenu=function(c,d,e,f){if(c=this.get_node(c),!c||c.id===a.jstree.root)return!1;var g=this.settings.contextmenu,h=this.get_node(c,!0),i=h.children(".jstree-anchor"),j=!1,k=!1;(g.show_at_node||d===b||e===b)&&(j=i.offset(),d=j.left,e=j.top+this._data.core.li_height),this.settings.contextmenu.select_node&&!this.is_selected(c)&&this.activate_node(c,f),k=g.items,a.isFunction(k)&&(k=k.call(this,c,a.proxy(function(a){this._show_contextmenu(c,d,e,a)},this))),a.isPlainObject(k)&&this._show_contextmenu(c,d,e,k)},this._show_contextmenu=function(b,c,d,e){var f=this.get_node(b,!0),g=f.children(".jstree-anchor");a(i).one("context_show.vakata.jstree",a.proxy(function(b,c){var d="jstree-contextmenu jstree-"+this.get_theme()+"-contextmenu";a(c.element).addClass(d),g.addClass("jstree-context")},this)),this._data.contextmenu.visible=!0,a.vakata.context.show(g,{x:c,y:d},e),this.trigger("show_contextmenu",{node:b,x:c,y:d})}},function(a){var b=!1,c={element:!1,reference:!1,position_x:0,position_y:0,items:[],html:"",is_visible:!1};a.vakata.context={settings:{hide_onmouseleave:0,icons:!0},_trigger:function(b){a(i).triggerHandler("context_"+b+".vakata",{reference:c.reference,element:c.element,position:{x:c.position_x,y:c.position_y}})},_execute:function(b){return b=c.items[b],b&&(!b._disabled||a.isFunction(b._disabled)&&!b._disabled({item:b,reference:c.reference,element:c.element}))&&b.action?b.action.call(null,{item:b,reference:c.reference,element:c.element,position:{x:c.position_x,y:c.position_y}}):!1},_parse:function(b,d){if(!b)return!1;d||(c.html="",c.items=[]);var e="",f=!1,g;return d&&(e+="<ul>"),a.each(b,function(b,d){return d?(c.items.push(d),!f&&d.separator_before&&(e+="<li class='vakata-context-separator'><a href='#' "+(a.vakata.context.settings.icons?"":'style="margin-left:0px;"')+">&#160;</a></li>"),f=!1,e+="<li class='"+(d._class||"")+(d._disabled===!0||a.isFunction(d._disabled)&&d._disabled({item:d,reference:c.reference,element:c.element})?" vakata-contextmenu-disabled ":"")+"' "+(d.shortcut?" data-shortcut='"+d.shortcut+"' ":"")+">",e+="<a href='#' rel='"+(c.items.length-1)+"' "+(d.title?"title='"+d.title+"'":"")+">",a.vakata.context.settings.icons&&(e+="<i ",d.icon&&(e+=-1!==d.icon.indexOf("/")||-1!==d.icon.indexOf(".")?" style='background:url(\""+d.icon+"\") center center no-repeat' ":" class='"+d.icon+"' "),e+="></i><span class='vakata-contextmenu-sep'>&#160;</span>"),e+=(a.isFunction(d.label)?d.label({item:b,reference:c.reference,element:c.element}):d.label)+(d.shortcut?' <span class="vakata-contextmenu-shortcut vakata-contextmenu-shortcut-'+d.shortcut+'">'+(d.shortcut_label||"")+"</span>":"")+"</a>",d.submenu&&(g=a.vakata.context._parse(d.submenu,!0),g&&(e+=g)),e+="</li>",void(d.separator_after&&(e+="<li class='vakata-context-separator'><a href='#' "+(a.vakata.context.settings.icons?"":'style="margin-left:0px;"')+">&#160;</a></li>",f=!0))):!0}),e=e.replace(/<li class\='vakata-context-separator'\><\/li\>$/,""),d&&(e+="</ul>"),d||(c.html=e,a.vakata.context._trigger("parse")),e.length>10?e:!1},_show_submenu:function(c){if(c=a(c),c.length&&c.children("ul").length){var d=c.children("ul"),e=c.offset().left,f=e+c.outerWidth(),g=c.offset().top,h=d.width(),i=d.height(),j=a(window).width()+a(window).scrollLeft(),k=a(window).height()+a(window).scrollTop();b?c[f-(h+10+c.outerWidth())<0?"addClass":"removeClass"]("vakata-context-left"):c[f+h>j&&e>j-f?"addClass":"removeClass"]("vakata-context-right"),g+i+10>k&&d.css("bottom","-1px"),c.hasClass("vakata-context-right")?h>e&&d.css("margin-right",e-h):h>j-f&&d.css("margin-left",j-f-h),d.show()}},show:function(d,e,f){var g,h,i,j,k,l,m,n,o=!0;switch(c.element&&c.element.length&&c.element.width(""),o){case!e&&!d:return!1;case!!e&&!!d:c.reference=d,c.position_x=e.x,c.position_y=e.y;break;case!e&&!!d:c.reference=d,g=d.offset(),c.position_x=g.left+d.outerHeight(),c.position_y=g.top;break;case!!e&&!d:c.position_x=e.x,c.position_y=e.y}d&&!f&&a(d).data("vakata_contextmenu")&&(f=a(d).data("vakata_contextmenu")),a.vakata.context._parse(f)&&c.element.html(c.html),c.items.length&&(c.element.appendTo("body"),h=c.element,i=c.position_x,j=c.position_y,k=h.width(),l=h.height(),m=a(window).width()+a(window).scrollLeft(),n=a(window).height()+a(window).scrollTop(),b&&(i-=h.outerWidth()-a(d).outerWidth(),i<a(window).scrollLeft()+20&&(i=a(window).scrollLeft()+20)),i+k+20>m&&(i=m-(k+20)),j+l+20>n&&(j=n-(l+20)),c.element.css({left:i,top:j}).show().find("a").first().focus().parent().addClass("vakata-context-hover"),c.is_visible=!0,a.vakata.context._trigger("show"))},hide:function(){c.is_visible&&(c.element.hide().find("ul").hide().end().find(":focus").blur().end().detach(),c.is_visible=!1,a.vakata.context._trigger("hide"))}},a(function(){b="rtl"===a("body").css("direction");var d=!1;c.element=a("<ul class='vakata-context'></ul>"),c.element.on("mouseenter","li",function(b){b.stopImmediatePropagation(),a.contains(this,b.relatedTarget)||(d&&clearTimeout(d),c.element.find(".vakata-context-hover").removeClass("vakata-context-hover").end(),a(this).siblings().find("ul").hide().end().end().parentsUntil(".vakata-context","li").addBack().addClass("vakata-context-hover"),a.vakata.context._show_submenu(this))}).on("mouseleave","li",function(b){a.contains(this,b.relatedTarget)||a(this).find(".vakata-context-hover").addBack().removeClass("vakata-context-hover")}).on("mouseleave",function(b){a(this).find(".vakata-context-hover").removeClass("vakata-context-hover"),a.vakata.context.settings.hide_onmouseleave&&(d=setTimeout(function(b){return function(){a.vakata.context.hide()}}(this),a.vakata.context.settings.hide_onmouseleave))}).on("click","a",function(b){b.preventDefault(),a(this).blur().parent().hasClass("vakata-context-disabled")||a.vakata.context._execute(a(this).attr("rel"))===!1||a.vakata.context.hide()}).on("keydown","a",function(b){var d=null;switch(b.which){case 13:case 32:b.type="mouseup",b.preventDefault(),a(b.currentTarget).trigger(b);break;case 37:c.is_visible&&(c.element.find(".vakata-context-hover").last().closest("li").first().find("ul").hide().find(".vakata-context-hover").removeClass("vakata-context-hover").end().end().children("a").focus(),b.stopImmediatePropagation(),b.preventDefault());break;case 38:c.is_visible&&(d=c.element.find("ul:visible").addBack().last().children(".vakata-context-hover").removeClass("vakata-context-hover").prevAll("li:not(.vakata-context-separator)").first(),d.length||(d=c.element.find("ul:visible").addBack().last().children("li:not(.vakata-context-separator)").last()),d.addClass("vakata-context-hover").children("a").focus(),b.stopImmediatePropagation(),b.preventDefault());break;case 39:c.is_visible&&(c.element.find(".vakata-context-hover").last().children("ul").show().children("li:not(.vakata-context-separator)").removeClass("vakata-context-hover").first().addClass("vakata-context-hover").children("a").focus(),b.stopImmediatePropagation(),b.preventDefault());break;case 40:c.is_visible&&(d=c.element.find("ul:visible").addBack().last().children(".vakata-context-hover").removeClass("vakata-context-hover").nextAll("li:not(.vakata-context-separator)").first(),d.length||(d=c.element.find("ul:visible").addBack().last().children("li:not(.vakata-context-separator)").first()),d.addClass("vakata-context-hover").children("a").focus(),b.stopImmediatePropagation(),
b.preventDefault());break;case 27:a.vakata.context.hide(),b.preventDefault()}}).on("keydown",function(a){a.preventDefault();var b=c.element.find(".vakata-contextmenu-shortcut-"+a.which).parent();b.parent().not(".vakata-context-disabled")&&b.click()}),a(i).on("mousedown.vakata.jstree",function(b){c.is_visible&&!a.contains(c.element[0],b.target)&&a.vakata.context.hide()}).on("context_show.vakata.jstree",function(a,d){c.element.find("li:has(ul)").children("a").addClass("vakata-context-parent"),b&&c.element.addClass("vakata-context-rtl").css("direction","rtl"),c.element.find("ul").hide().end()})})}(a),a.jstree.defaults.dnd={copy:!0,open_timeout:500,is_draggable:!0,check_while_dragging:!0,always_copy:!1,inside_pos:0,drag_selection:!0,touch:!0,large_drop_target:!1,large_drag_target:!1,use_html5:!1};var n,o;a.jstree.plugins.dnd=function(b,c){this.init=function(a,b){c.init.call(this,a,b),this.settings.dnd.use_html5=this.settings.dnd.use_html5&&"draggable"in i.createElement("span")},this.bind=function(){c.bind.call(this),this.element.on(this.settings.dnd.use_html5?"dragstart.jstree":"mousedown.jstree touchstart.jstree",this.settings.dnd.large_drag_target?".jstree-node":".jstree-anchor",a.proxy(function(b){if(this.settings.dnd.large_drag_target&&a(b.target).closest(".jstree-node")[0]!==b.currentTarget)return!0;if("touchstart"===b.type&&(!this.settings.dnd.touch||"selected"===this.settings.dnd.touch&&!a(b.currentTarget).closest(".jstree-node").children(".jstree-anchor").hasClass("jstree-clicked")))return!0;var c=this.get_node(b.target),d=this.is_selected(c)&&this.settings.dnd.drag_selection?this.get_top_selected().length:1,e=d>1?d+" "+this.get_string("nodes"):this.get_text(b.currentTarget);if(this.settings.core.force_text&&(e=a.vakata.html.escape(e)),c&&c.id&&c.id!==a.jstree.root&&(1===b.which||"touchstart"===b.type||"dragstart"===b.type)&&(this.settings.dnd.is_draggable===!0||a.isFunction(this.settings.dnd.is_draggable)&&this.settings.dnd.is_draggable.call(this,d>1?this.get_top_selected(!0):[c],b))){if(n={jstree:!0,origin:this,obj:this.get_node(c,!0),nodes:d>1?this.get_top_selected():[c.id]},o=b.currentTarget,!this.settings.dnd.use_html5)return this.element.trigger("mousedown.jstree"),a.vakata.dnd.start(b,n,'<div id="jstree-dnd" class="jstree-'+this.get_theme()+" jstree-"+this.get_theme()+"-"+this.get_theme_variant()+" "+(this.settings.core.themes.responsive?" jstree-dnd-responsive":"")+'"><i class="jstree-icon jstree-er"></i>'+e+'<ins class="jstree-copy" style="display:none;">+</ins></div>');a.vakata.dnd._trigger("start",b,{helper:a(),element:o,data:n})}},this)),this.settings.dnd.use_html5&&this.element.on("dragover.jstree",function(b){return b.preventDefault(),a.vakata.dnd._trigger("move",b,{helper:a(),element:o,data:n}),!1}).on("drop.jstree",a.proxy(function(b){return b.preventDefault(),a.vakata.dnd._trigger("stop",b,{helper:a(),element:o,data:n}),!1},this))},this.redraw_node=function(a,b,d,e){if(a=c.redraw_node.apply(this,arguments),a&&this.settings.dnd.use_html5)if(this.settings.dnd.large_drag_target)a.setAttribute("draggable",!0);else{var f,g,h=null;for(f=0,g=a.childNodes.length;g>f;f++)if(a.childNodes[f]&&a.childNodes[f].className&&-1!==a.childNodes[f].className.indexOf("jstree-anchor")){h=a.childNodes[f];break}h&&h.setAttribute("draggable",!0)}return a}},a(function(){var c=!1,d=!1,e=!1,f=!1,g=a('<div id="jstree-marker">&#160;</div>').hide();a(i).on("dnd_start.vakata.jstree",function(a,b){c=!1,e=!1,b&&b.data&&b.data.jstree&&g.appendTo("body")}).on("dnd_move.vakata.jstree",function(h,i){if(f&&(i.event&&"dragover"===i.event.type&&i.event.target===e.target||clearTimeout(f)),i&&i.data&&i.data.jstree&&(!i.event.target.id||"jstree-marker"!==i.event.target.id)){e=i.event;var j=a.jstree.reference(i.event.target),k=!1,l=!1,m=!1,n,o,p,q,r,s,t,u,v,w,x,y,z,A,B,C,D;if(j&&j._data&&j._data.dnd)if(g.attr("class","jstree-"+j.get_theme()+(j.settings.core.themes.responsive?" jstree-dnd-responsive":"")),C=i.data.origin&&(i.data.origin.settings.dnd.always_copy||i.data.origin.settings.dnd.copy&&(i.event.metaKey||i.event.ctrlKey)),i.helper.children().attr("class","jstree-"+j.get_theme()+" jstree-"+j.get_theme()+"-"+j.get_theme_variant()+" "+(j.settings.core.themes.responsive?" jstree-dnd-responsive":"")).find(".jstree-copy").first()[C?"show":"hide"](),i.event.target!==j.element[0]&&i.event.target!==j.get_container_ul()[0]||0!==j.get_container_ul().children().length){if(k=j.settings.dnd.large_drop_target?a(i.event.target).closest(".jstree-node").children(".jstree-anchor"):a(i.event.target).closest(".jstree-anchor"),k&&k.length&&k.parent().is(".jstree-closed, .jstree-open, .jstree-leaf")&&(l=k.offset(),m=(i.event.pageY!==b?i.event.pageY:i.event.originalEvent.pageY)-l.top,q=k.outerHeight(),t=q/3>m?["b","i","a"]:m>q-q/3?["a","i","b"]:m>q/2?["i","a","b"]:["i","b","a"],a.each(t,function(b,e){switch(e){case"b":o=l.left-6,p=l.top,r=j.get_parent(k),s=k.parent().index();break;case"i":A=j.settings.dnd.inside_pos,B=j.get_node(k.parent()),o=l.left-2,p=l.top+q/2+1,r=B.id,s="first"===A?0:"last"===A?B.children.length:Math.min(A,B.children.length);break;case"a":o=l.left-6,p=l.top+q,r=j.get_parent(k),s=k.parent().index()+1}for(u=!0,v=0,w=i.data.nodes.length;w>v;v++)if(x=i.data.origin&&(i.data.origin.settings.dnd.always_copy||i.data.origin.settings.dnd.copy&&(i.event.metaKey||i.event.ctrlKey))?"copy_node":"move_node",y=s,"move_node"===x&&"a"===e&&i.data.origin&&i.data.origin===j&&r===j.get_parent(i.data.nodes[v])&&(z=j.get_node(r),y>a.inArray(i.data.nodes[v],z.children)&&(y-=1)),u=u&&(j&&j.settings&&j.settings.dnd&&j.settings.dnd.check_while_dragging===!1||j.check(x,i.data.origin&&i.data.origin!==j?i.data.origin.get_node(i.data.nodes[v]):i.data.nodes[v],r,y,{dnd:!0,ref:j.get_node(k.parent()),pos:e,origin:i.data.origin,is_multi:i.data.origin&&i.data.origin!==j,is_foreign:!i.data.origin})),!u){j&&j.last_error&&(d=j.last_error());break}return"i"===e&&k.parent().is(".jstree-closed")&&j.settings.dnd.open_timeout&&(f=setTimeout(function(a,b){return function(){a.open_node(b)}}(j,k),j.settings.dnd.open_timeout)),u?(D=j.get_node(r,!0),D.hasClass(".jstree-dnd-parent")||(a(".jstree-dnd-parent").removeClass("jstree-dnd-parent"),D.addClass("jstree-dnd-parent")),c={ins:j,par:r,pos:"i"!==e||"last"!==A||0!==s||j.is_loaded(B)?s:"last"},g.css({left:o+"px",top:p+"px"}).show(),i.helper.find(".jstree-icon").first().removeClass("jstree-er").addClass("jstree-ok"),i.event.originalEvent&&i.event.originalEvent.dataTransfer&&(i.event.originalEvent.dataTransfer.dropEffect=C?"copy":"move"),d={},t=!0,!1):void 0}),t===!0))return}else{for(u=!0,v=0,w=i.data.nodes.length;w>v;v++)if(u=u&&j.check(i.data.origin&&(i.data.origin.settings.dnd.always_copy||i.data.origin.settings.dnd.copy&&(i.event.metaKey||i.event.ctrlKey))?"copy_node":"move_node",i.data.origin&&i.data.origin!==j?i.data.origin.get_node(i.data.nodes[v]):i.data.nodes[v],a.jstree.root,"last",{dnd:!0,ref:j.get_node(a.jstree.root),pos:"i",origin:i.data.origin,is_multi:i.data.origin&&i.data.origin!==j,is_foreign:!i.data.origin}),!u)break;if(u)return c={ins:j,par:a.jstree.root,pos:"last"},g.hide(),i.helper.find(".jstree-icon").first().removeClass("jstree-er").addClass("jstree-ok"),void(i.event.originalEvent&&i.event.originalEvent.dataTransfer&&(i.event.originalEvent.dataTransfer.dropEffect=C?"copy":"move"))}a(".jstree-dnd-parent").removeClass("jstree-dnd-parent"),c=!1,i.helper.find(".jstree-icon").removeClass("jstree-ok").addClass("jstree-er"),i.event.originalEvent&&i.event.originalEvent.dataTransfer&&(i.event.originalEvent.dataTransfer.dropEffect="none"),g.hide()}}).on("dnd_scroll.vakata.jstree",function(a,b){b&&b.data&&b.data.jstree&&(g.hide(),c=!1,e=!1,b.helper.find(".jstree-icon").first().removeClass("jstree-ok").addClass("jstree-er"))}).on("dnd_stop.vakata.jstree",function(b,h){if(a(".jstree-dnd-parent").removeClass("jstree-dnd-parent"),f&&clearTimeout(f),h&&h.data&&h.data.jstree){g.hide().detach();var i,j,k=[];if(c){for(i=0,j=h.data.nodes.length;j>i;i++)k[i]=h.data.origin?h.data.origin.get_node(h.data.nodes[i]):h.data.nodes[i];c.ins[h.data.origin&&(h.data.origin.settings.dnd.always_copy||h.data.origin.settings.dnd.copy&&(h.event.metaKey||h.event.ctrlKey))?"copy_node":"move_node"](k,c.par,c.pos,!1,!1,!1,h.data.origin)}else i=a(h.event.target).closest(".jstree"),i.length&&d&&d.error&&"check"===d.error&&(i=i.jstree(!0),i&&i.settings.core.error.call(this,d));e=!1,c=!1}}).on("keyup.jstree keydown.jstree",function(b,h){h=a.vakata.dnd._get(),h&&h.data&&h.data.jstree&&("keyup"===b.type&&27===b.which?(f&&clearTimeout(f),c=!1,d=!1,e=!1,f=!1,g.hide().detach(),a.vakata.dnd._clean()):(h.helper.find(".jstree-copy").first()[h.data.origin&&(h.data.origin.settings.dnd.always_copy||h.data.origin.settings.dnd.copy&&(b.metaKey||b.ctrlKey))?"show":"hide"](),e&&(e.metaKey=b.metaKey,e.ctrlKey=b.ctrlKey,a.vakata.dnd._trigger("move",e))))})}),function(a){a.vakata.html={div:a("<div />"),escape:function(b){return a.vakata.html.div.text(b).html()},strip:function(b){return a.vakata.html.div.empty().append(a.parseHTML(b)).text()}};var c={element:!1,target:!1,is_down:!1,is_drag:!1,helper:!1,helper_w:0,data:!1,init_x:0,init_y:0,scroll_l:0,scroll_t:0,scroll_e:!1,scroll_i:!1,is_touch:!1};a.vakata.dnd={settings:{scroll_speed:10,scroll_proximity:20,helper_left:5,helper_top:10,threshold:5,threshold_touch:50},_trigger:function(c,d,e){e===b&&(e=a.vakata.dnd._get()),e.event=d,a(i).triggerHandler("dnd_"+c+".vakata",e)},_get:function(){return{data:c.data,element:c.element,helper:c.helper}},_clean:function(){c.helper&&c.helper.remove(),c.scroll_i&&(clearInterval(c.scroll_i),c.scroll_i=!1),c={element:!1,target:!1,is_down:!1,is_drag:!1,helper:!1,helper_w:0,data:!1,init_x:0,init_y:0,scroll_l:0,scroll_t:0,scroll_e:!1,scroll_i:!1,is_touch:!1},a(i).off("mousemove.vakata.jstree touchmove.vakata.jstree",a.vakata.dnd.drag),a(i).off("mouseup.vakata.jstree touchend.vakata.jstree",a.vakata.dnd.stop)},_scroll:function(b){if(!c.scroll_e||!c.scroll_l&&!c.scroll_t)return c.scroll_i&&(clearInterval(c.scroll_i),c.scroll_i=!1),!1;if(!c.scroll_i)return c.scroll_i=setInterval(a.vakata.dnd._scroll,100),!1;if(b===!0)return!1;var d=c.scroll_e.scrollTop(),e=c.scroll_e.scrollLeft();c.scroll_e.scrollTop(d+c.scroll_t*a.vakata.dnd.settings.scroll_speed),c.scroll_e.scrollLeft(e+c.scroll_l*a.vakata.dnd.settings.scroll_speed),(d!==c.scroll_e.scrollTop()||e!==c.scroll_e.scrollLeft())&&a.vakata.dnd._trigger("scroll",c.scroll_e)},start:function(b,d,e){"touchstart"===b.type&&b.originalEvent&&b.originalEvent.changedTouches&&b.originalEvent.changedTouches[0]&&(b.pageX=b.originalEvent.changedTouches[0].pageX,b.pageY=b.originalEvent.changedTouches[0].pageY,b.target=i.elementFromPoint(b.originalEvent.changedTouches[0].pageX-window.pageXOffset,b.originalEvent.changedTouches[0].pageY-window.pageYOffset)),c.is_drag&&a.vakata.dnd.stop({});try{b.currentTarget.unselectable="on",b.currentTarget.onselectstart=function(){return!1},b.currentTarget.style&&(b.currentTarget.style.touchAction="none",b.currentTarget.style.msTouchAction="none",b.currentTarget.style.MozUserSelect="none")}catch(f){}return c.init_x=b.pageX,c.init_y=b.pageY,c.data=d,c.is_down=!0,c.element=b.currentTarget,c.target=b.target,c.is_touch="touchstart"===b.type,e!==!1&&(c.helper=a("<div id='vakata-dnd'></div>").html(e).css({display:"block",margin:"0",padding:"0",position:"absolute",top:"-2000px",lineHeight:"16px",zIndex:"10000"})),a(i).on("mousemove.vakata.jstree touchmove.vakata.jstree",a.vakata.dnd.drag),a(i).on("mouseup.vakata.jstree touchend.vakata.jstree",a.vakata.dnd.stop),!1},drag:function(b){if("touchmove"===b.type&&b.originalEvent&&b.originalEvent.changedTouches&&b.originalEvent.changedTouches[0]&&(b.pageX=b.originalEvent.changedTouches[0].pageX,b.pageY=b.originalEvent.changedTouches[0].pageY,b.target=i.elementFromPoint(b.originalEvent.changedTouches[0].pageX-window.pageXOffset,b.originalEvent.changedTouches[0].pageY-window.pageYOffset)),c.is_down){if(!c.is_drag){if(!(Math.abs(b.pageX-c.init_x)>(c.is_touch?a.vakata.dnd.settings.threshold_touch:a.vakata.dnd.settings.threshold)||Math.abs(b.pageY-c.init_y)>(c.is_touch?a.vakata.dnd.settings.threshold_touch:a.vakata.dnd.settings.threshold)))return;c.helper&&(c.helper.appendTo("body"),c.helper_w=c.helper.outerWidth()),c.is_drag=!0,a(c.target).one("click.vakata",!1),a.vakata.dnd._trigger("start",b)}var d=!1,e=!1,f=!1,g=!1,h=!1,j=!1,k=!1,l=!1,m=!1,n=!1;return c.scroll_t=0,c.scroll_l=0,c.scroll_e=!1,a(a(b.target).parentsUntil("body").addBack().get().reverse()).filter(function(){return/^auto|scroll$/.test(a(this).css("overflow"))&&(this.scrollHeight>this.offsetHeight||this.scrollWidth>this.offsetWidth)}).each(function(){var d=a(this),e=d.offset();return this.scrollHeight>this.offsetHeight&&(e.top+d.height()-b.pageY<a.vakata.dnd.settings.scroll_proximity&&(c.scroll_t=1),b.pageY-e.top<a.vakata.dnd.settings.scroll_proximity&&(c.scroll_t=-1)),this.scrollWidth>this.offsetWidth&&(e.left+d.width()-b.pageX<a.vakata.dnd.settings.scroll_proximity&&(c.scroll_l=1),b.pageX-e.left<a.vakata.dnd.settings.scroll_proximity&&(c.scroll_l=-1)),c.scroll_t||c.scroll_l?(c.scroll_e=a(this),!1):void 0}),c.scroll_e||(d=a(i),e=a(window),f=d.height(),g=e.height(),h=d.width(),j=e.width(),k=d.scrollTop(),l=d.scrollLeft(),f>g&&b.pageY-k<a.vakata.dnd.settings.scroll_proximity&&(c.scroll_t=-1),f>g&&g-(b.pageY-k)<a.vakata.dnd.settings.scroll_proximity&&(c.scroll_t=1),h>j&&b.pageX-l<a.vakata.dnd.settings.scroll_proximity&&(c.scroll_l=-1),h>j&&j-(b.pageX-l)<a.vakata.dnd.settings.scroll_proximity&&(c.scroll_l=1),(c.scroll_t||c.scroll_l)&&(c.scroll_e=d)),c.scroll_e&&a.vakata.dnd._scroll(!0),c.helper&&(m=parseInt(b.pageY+a.vakata.dnd.settings.helper_top,10),n=parseInt(b.pageX+a.vakata.dnd.settings.helper_left,10),f&&m+25>f&&(m=f-50),h&&n+c.helper_w>h&&(n=h-(c.helper_w+2)),c.helper.css({left:n+"px",top:m+"px"})),a.vakata.dnd._trigger("move",b),!1}},stop:function(b){if("touchend"===b.type&&b.originalEvent&&b.originalEvent.changedTouches&&b.originalEvent.changedTouches[0]&&(b.pageX=b.originalEvent.changedTouches[0].pageX,b.pageY=b.originalEvent.changedTouches[0].pageY,b.target=i.elementFromPoint(b.originalEvent.changedTouches[0].pageX-window.pageXOffset,b.originalEvent.changedTouches[0].pageY-window.pageYOffset)),c.is_drag)b.target!==c.target&&a(c.target).off("click.vakata"),a.vakata.dnd._trigger("stop",b);else if("touchend"===b.type&&b.target===c.target){var d=setTimeout(function(){a(b.target).click()},100);a(b.target).one("click",function(){d&&clearTimeout(d)})}return a.vakata.dnd._clean(),!1}}}(a),a.jstree.defaults.massload=null,a.jstree.plugins.massload=function(b,c){this.init=function(a,b){this._data.massload={},c.init.call(this,a,b)},this._load_nodes=function(b,d,e,f){var g=this.settings.massload,h=JSON.stringify(b),i=[],j=this._model.data,k,l,m;if(!e){for(k=0,l=b.length;l>k;k++)(!j[b[k]]||!j[b[k]].state.loaded&&!j[b[k]].state.failed||f)&&(i.push(b[k]),m=this.get_node(b[k],!0),m&&m.length&&m.addClass("jstree-loading").attr("aria-busy",!0));if(this._data.massload={},i.length){if(a.isFunction(g))return g.call(this,i,a.proxy(function(a){var g,h;if(a)for(g in a)a.hasOwnProperty(g)&&(this._data.massload[g]=a[g]);for(g=0,h=b.length;h>g;g++)m=this.get_node(b[g],!0),m&&m.length&&m.removeClass("jstree-loading").attr("aria-busy",!1);c._load_nodes.call(this,b,d,e,f)},this));if("object"==typeof g&&g&&g.url)return g=a.extend(!0,{},g),a.isFunction(g.url)&&(g.url=g.url.call(this,i)),a.isFunction(g.data)&&(g.data=g.data.call(this,i)),a.ajax(g).done(a.proxy(function(a,g,h){var i,j;if(a)for(i in a)a.hasOwnProperty(i)&&(this._data.massload[i]=a[i]);for(i=0,j=b.length;j>i;i++)m=this.get_node(b[i],!0),m&&m.length&&m.removeClass("jstree-loading").attr("aria-busy",!1);c._load_nodes.call(this,b,d,e,f)},this)).fail(a.proxy(function(a){c._load_nodes.call(this,b,d,e,f)},this))}}return c._load_nodes.call(this,b,d,e,f)},this._load_node=function(b,d){var e=this._data.massload[b.id],f=null,g;return e?(f=this["string"==typeof e?"_append_html_data":"_append_json_data"](b,"string"==typeof e?a(a.parseHTML(e)).filter(function(){return 3!==this.nodeType}):e,function(a){d.call(this,a)}),g=this.get_node(b.id,!0),g&&g.length&&g.removeClass("jstree-loading").attr("aria-busy",!1),delete this._data.massload[b.id],f):c._load_node.call(this,b,d)}},a.jstree.defaults.search={ajax:!1,fuzzy:!1,case_sensitive:!1,show_only_matches:!1,show_only_matches_children:!1,close_opened_onclear:!0,search_leaves_only:!1,search_callback:!1},a.jstree.plugins.search=function(c,d){this.bind=function(){d.bind.call(this),this._data.search.str="",this._data.search.dom=a(),this._data.search.res=[],this._data.search.opn=[],this._data.search.som=!1,this._data.search.smc=!1,this._data.search.hdn=[],this.element.on("search.jstree",a.proxy(function(b,c){if(this._data.search.som&&c.res.length){var d=this._model.data,e,f,g=[],h,i;for(e=0,f=c.res.length;f>e;e++)if(d[c.res[e]]&&!d[c.res[e]].state.hidden&&(g.push(c.res[e]),g=g.concat(d[c.res[e]].parents),this._data.search.smc))for(h=0,i=d[c.res[e]].children_d.length;i>h;h++)d[d[c.res[e]].children_d[h]]&&!d[d[c.res[e]].children_d[h]].state.hidden&&g.push(d[c.res[e]].children_d[h]);g=a.vakata.array_remove_item(a.vakata.array_unique(g),a.jstree.root),this._data.search.hdn=this.hide_all(!0),this.show_node(g,!0),this.redraw(!0)}},this)).on("clear_search.jstree",a.proxy(function(a,b){this._data.search.som&&b.res.length&&(this.show_node(this._data.search.hdn,!0),this.redraw(!0))},this))},this.search=function(c,d,e,f,g,h){if(c===!1||""===a.trim(c.toString()))return this.clear_search();f=this.get_node(f),f=f&&f.id?f.id:null,c=c.toString();var i=this.settings.search,j=i.ajax?i.ajax:!1,k=this._model.data,l=null,m=[],n=[],o,p;if(this._data.search.res.length&&!g&&this.clear_search(),e===b&&(e=i.show_only_matches),h===b&&(h=i.show_only_matches_children),!d&&j!==!1)return a.isFunction(j)?j.call(this,c,a.proxy(function(b){b&&b.d&&(b=b.d),this._load_nodes(a.isArray(b)?a.vakata.array_unique(b):[],function(){this.search(c,!0,e,f,g)})},this),f):(j=a.extend({},j),j.data||(j.data={}),j.data.str=c,f&&(j.data.inside=f),a.ajax(j).fail(a.proxy(function(){this._data.core.last_error={error:"ajax",plugin:"search",id:"search_01",reason:"Could not load search parents",data:JSON.stringify(j)},this.settings.core.error.call(this,this._data.core.last_error)},this)).done(a.proxy(function(b){b&&b.d&&(b=b.d),this._load_nodes(a.isArray(b)?a.vakata.array_unique(b):[],function(){this.search(c,!0,e,f,g)})},this)));if(g||(this._data.search.str=c,this._data.search.dom=a(),this._data.search.res=[],this._data.search.opn=[],this._data.search.som=e,this._data.search.smc=h),l=new a.vakata.search(c,!0,{caseSensitive:i.case_sensitive,fuzzy:i.fuzzy}),a.each(k[f?f:a.jstree.root].children_d,function(a,b){var d=k[b];d.text&&!d.state.hidden&&(!i.search_leaves_only||d.state.loaded&&0===d.children.length)&&(i.search_callback&&i.search_callback.call(this,c,d)||!i.search_callback&&l.search(d.text).isMatch)&&(m.push(b),n=n.concat(d.parents))}),m.length){for(n=a.vakata.array_unique(n),o=0,p=n.length;p>o;o++)n[o]!==a.jstree.root&&k[n[o]]&&this.open_node(n[o],null,0)===!0&&this._data.search.opn.push(n[o]);g?(this._data.search.dom=this._data.search.dom.add(a(this.element[0].querySelectorAll("#"+a.map(m,function(b){return-1!=="0123456789".indexOf(b[0])?"\\3"+b[0]+" "+b.substr(1).replace(a.jstree.idregex,"\\$&"):b.replace(a.jstree.idregex,"\\$&")}).join(", #")))),this._data.search.res=a.vakata.array_unique(this._data.search.res.concat(m))):(this._data.search.dom=a(this.element[0].querySelectorAll("#"+a.map(m,function(b){return-1!=="0123456789".indexOf(b[0])?"\\3"+b[0]+" "+b.substr(1).replace(a.jstree.idregex,"\\$&"):b.replace(a.jstree.idregex,"\\$&")}).join(", #"))),this._data.search.res=m),this._data.search.dom.children(".jstree-anchor").addClass("jstree-search")}this.trigger("search",{nodes:this._data.search.dom,str:c,res:this._data.search.res,show_only_matches:e})},this.clear_search=function(){this.settings.search.close_opened_onclear&&this.close_node(this._data.search.opn,0),this.trigger("clear_search",{nodes:this._data.search.dom,str:this._data.search.str,res:this._data.search.res}),this._data.search.res.length&&(this._data.search.dom=a(this.element[0].querySelectorAll("#"+a.map(this._data.search.res,function(b){return-1!=="0123456789".indexOf(b[0])?"\\3"+b[0]+" "+b.substr(1).replace(a.jstree.idregex,"\\$&"):b.replace(a.jstree.idregex,"\\$&")}).join(", #"))),this._data.search.dom.children(".jstree-anchor").removeClass("jstree-search")),this._data.search.str="",this._data.search.res=[],this._data.search.opn=[],this._data.search.dom=a()},this.redraw_node=function(b,c,e,f){if(b=d.redraw_node.apply(this,arguments),b&&-1!==a.inArray(b.id,this._data.search.res)){var g,h,i=null;for(g=0,h=b.childNodes.length;h>g;g++)if(b.childNodes[g]&&b.childNodes[g].className&&-1!==b.childNodes[g].className.indexOf("jstree-anchor")){i=b.childNodes[g];break}i&&(i.className+=" jstree-search")}return b}},function(a){a.vakata.search=function(b,c,d){d=d||{},d=a.extend({},a.vakata.search.defaults,d),d.fuzzy!==!1&&(d.fuzzy=!0),b=d.caseSensitive?b:b.toLowerCase();var e=d.location,f=d.distance,g=d.threshold,h=b.length,i,j,k,l;return h>32&&(d.fuzzy=!1),d.fuzzy&&(i=1<<h-1,j=function(){var a={},c=0;for(c=0;h>c;c++)a[b.charAt(c)]=0;for(c=0;h>c;c++)a[b.charAt(c)]|=1<<h-c-1;return a}(),k=function(a,b){var c=a/h,d=Math.abs(e-b);return f?c+d/f:d?1:c}),l=function(a){if(a=d.caseSensitive?a:a.toLowerCase(),b===a||-1!==a.indexOf(b))return{isMatch:!0,score:0};if(!d.fuzzy)return{isMatch:!1,score:1};var c,f,l=a.length,m=g,n=a.indexOf(b,e),o,p,q=h+l,r,s,t,u,v,w=1,x=[];for(-1!==n&&(m=Math.min(k(0,n),m),n=a.lastIndexOf(b,e+h),-1!==n&&(m=Math.min(k(0,n),m))),n=-1,c=0;h>c;c++){o=0,p=q;while(p>o)k(c,e+p)<=m?o=p:q=p,p=Math.floor((q-o)/2+o);for(q=p,s=Math.max(1,e-p+1),t=Math.min(e+p,l)+h,u=new Array(t+2),u[t+1]=(1<<c)-1,f=t;f>=s;f--)if(v=j[a.charAt(f-1)],0===c?u[f]=(u[f+1]<<1|1)&v:u[f]=(u[f+1]<<1|1)&v|((r[f+1]|r[f])<<1|1)|r[f+1],u[f]&i&&(w=k(c,f-1),m>=w)){if(m=w,n=f-1,x.push(n),!(n>e))break;s=Math.max(1,2*e-n)}if(k(c+1,e)>m)break;r=u}return{isMatch:n>=0,score:w}},c===!0?{search:l}:l(c)},a.vakata.search.defaults={location:0,distance:100,threshold:.6,fuzzy:!1,caseSensitive:!1}}(a),a.jstree.defaults.sort=function(a,b){return this.get_text(a)>this.get_text(b)?1:-1},a.jstree.plugins.sort=function(b,c){this.bind=function(){c.bind.call(this),this.element.on("model.jstree",a.proxy(function(a,b){this.sort(b.parent,!0)},this)).on("rename_node.jstree create_node.jstree",a.proxy(function(a,b){this.sort(b.parent||b.node.parent,!1),this.redraw_node(b.parent||b.node.parent,!0)},this)).on("move_node.jstree copy_node.jstree",a.proxy(function(a,b){this.sort(b.parent,!1),this.redraw_node(b.parent,!0)},this))},this.sort=function(b,c){var d,e;if(b=this.get_node(b),b&&b.children&&b.children.length&&(b.children.sort(a.proxy(this.settings.sort,this)),c))for(d=0,e=b.children_d.length;e>d;d++)this.sort(b.children_d[d],!1)}};var p=!1;a.jstree.defaults.state={key:"jstree",events:"changed.jstree open_node.jstree close_node.jstree check_node.jstree uncheck_node.jstree",ttl:!1,filter:!1},a.jstree.plugins.state=function(b,c){this.bind=function(){c.bind.call(this);var b=a.proxy(function(){this.element.on(this.settings.state.events,a.proxy(function(){p&&clearTimeout(p),p=setTimeout(a.proxy(function(){this.save_state()},this),100)},this)),this.trigger("state_ready")},this);this.element.on("ready.jstree",a.proxy(function(a,c){this.element.one("restore_state.jstree",b),this.restore_state()||b()},this))},this.save_state=function(){var b={state:this.get_state(),ttl:this.settings.state.ttl,sec:+new Date};a.vakata.storage.set(this.settings.state.key,JSON.stringify(b))},this.restore_state=function(){var b=a.vakata.storage.get(this.settings.state.key);if(b)try{b=JSON.parse(b)}catch(c){return!1}return b&&b.ttl&&b.sec&&+new Date-b.sec>b.ttl?!1:(b&&b.state&&(b=b.state),b&&a.isFunction(this.settings.state.filter)&&(b=this.settings.state.filter.call(this,b)),b?(this.element.one("set_state.jstree",function(c,d){d.instance.trigger("restore_state",{state:a.extend(!0,{},b)})}),this.set_state(b),!0):!1)},this.clear_state=function(){return a.vakata.storage.del(this.settings.state.key)}},function(a,b){a.vakata.storage={set:function(a,b){return window.localStorage.setItem(a,b)},get:function(a){return window.localStorage.getItem(a)},del:function(a){return window.localStorage.removeItem(a)}}}(a),a.jstree.defaults.types={"default":{}},a.jstree.defaults.types[a.jstree.root]={},a.jstree.plugins.types=function(c,d){this.init=function(c,e){var f,g;if(e&&e.types&&e.types["default"])for(f in e.types)if("default"!==f&&f!==a.jstree.root&&e.types.hasOwnProperty(f))for(g in e.types["default"])e.types["default"].hasOwnProperty(g)&&e.types[f][g]===b&&(e.types[f][g]=e.types["default"][g]);d.init.call(this,c,e),this._model.data[a.jstree.root].type=a.jstree.root},this.refresh=function(b,c){d.refresh.call(this,b,c),this._model.data[a.jstree.root].type=a.jstree.root},this.bind=function(){this.element.on("model.jstree",a.proxy(function(c,d){var e=this._model.data,f=d.nodes,g=this.settings.types,h,i,j="default",k;for(h=0,i=f.length;i>h;h++){if(j="default",e[f[h]].original&&e[f[h]].original.type&&g[e[f[h]].original.type]&&(j=e[f[h]].original.type),e[f[h]].data&&e[f[h]].data.jstree&&e[f[h]].data.jstree.type&&g[e[f[h]].data.jstree.type]&&(j=e[f[h]].data.jstree.type),e[f[h]].type=j,e[f[h]].icon===!0&&g[j].icon!==b&&(e[f[h]].icon=g[j].icon),g[j].li_attr!==b&&"object"==typeof g[j].li_attr)for(k in g[j].li_attr)if(g[j].li_attr.hasOwnProperty(k)){if("id"===k)continue;e[f[h]].li_attr[k]===b?e[f[h]].li_attr[k]=g[j].li_attr[k]:"class"===k&&(e[f[h]].li_attr["class"]=g[j].li_attr["class"]+" "+e[f[h]].li_attr["class"])}if(g[j].a_attr!==b&&"object"==typeof g[j].a_attr)for(k in g[j].a_attr)if(g[j].a_attr.hasOwnProperty(k)){if("id"===k)continue;e[f[h]].a_attr[k]===b?e[f[h]].a_attr[k]=g[j].a_attr[k]:"href"===k&&"#"===e[f[h]].a_attr[k]?e[f[h]].a_attr.href=g[j].a_attr.href:"class"===k&&(e[f[h]].a_attr["class"]=g[j].a_attr["class"]+" "+e[f[h]].a_attr["class"])}}e[a.jstree.root].type=a.jstree.root},this)),d.bind.call(this)},this.get_json=function(b,c,e){var f,g,h=this._model.data,i=c?a.extend(!0,{},c,{no_id:!1}):{},j=d.get_json.call(this,b,i,e);if(j===!1)return!1;if(a.isArray(j))for(f=0,g=j.length;g>f;f++)j[f].type=j[f].id&&h[j[f].id]&&h[j[f].id].type?h[j[f].id].type:"default",c&&c.no_id&&(delete j[f].id,j[f].li_attr&&j[f].li_attr.id&&delete j[f].li_attr.id,j[f].a_attr&&j[f].a_attr.id&&delete j[f].a_attr.id);else j.type=j.id&&h[j.id]&&h[j.id].type?h[j.id].type:"default",c&&c.no_id&&(j=this._delete_ids(j));return j},this._delete_ids=function(b){if(a.isArray(b)){for(var c=0,d=b.length;d>c;c++)b[c]=this._delete_ids(b[c]);return b}return delete b.id,b.li_attr&&b.li_attr.id&&delete b.li_attr.id,b.a_attr&&b.a_attr.id&&delete b.a_attr.id,b.children&&a.isArray(b.children)&&(b.children=this._delete_ids(b.children)),b},this.check=function(c,e,f,g,h){if(d.check.call(this,c,e,f,g,h)===!1)return!1;e=e&&e.id?e:this.get_node(e),f=f&&f.id?f:this.get_node(f);var i=e&&e.id?h&&h.origin?h.origin:a.jstree.reference(e.id):null,j,k,l,m;switch(i=i&&i._model&&i._model.data?i._model.data:null,c){case"create_node":case"move_node":case"copy_node":if("move_node"!==c||-1===a.inArray(e.id,f.children)){if(j=this.get_rules(f),j.max_children!==b&&-1!==j.max_children&&j.max_children===f.children.length)return this._data.core.last_error={error:"check",plugin:"types",id:"types_01",reason:"max_children prevents function: "+c,data:JSON.stringify({chk:c,pos:g,obj:e&&e.id?e.id:!1,par:f&&f.id?f.id:!1})},!1;if(j.valid_children!==b&&-1!==j.valid_children&&-1===a.inArray(e.type||"default",j.valid_children))return this._data.core.last_error={error:"check",plugin:"types",id:"types_02",reason:"valid_children prevents function: "+c,data:JSON.stringify({chk:c,pos:g,obj:e&&e.id?e.id:!1,par:f&&f.id?f.id:!1})},!1;if(i&&e.children_d&&e.parents){for(k=0,l=0,m=e.children_d.length;m>l;l++)k=Math.max(k,i[e.children_d[l]].parents.length);k=k-e.parents.length+1}(0>=k||k===b)&&(k=1);do{if(j.max_depth!==b&&-1!==j.max_depth&&j.max_depth<k)return this._data.core.last_error={error:"check",plugin:"types",id:"types_03",reason:"max_depth prevents function: "+c,data:JSON.stringify({chk:c,pos:g,obj:e&&e.id?e.id:!1,par:f&&f.id?f.id:!1})},!1;f=this.get_node(f.parent),j=this.get_rules(f),k++}while(f)}}return!0},this.get_rules=function(a){if(a=this.get_node(a),!a)return!1;var c=this.get_type(a,!0);return c.max_depth===b&&(c.max_depth=-1),c.max_children===b&&(c.max_children=-1),c.valid_children===b&&(c.valid_children=-1),c},this.get_type=function(b,c){return b=this.get_node(b),b?c?a.extend({type:b.type},this.settings.types[b.type]):b.type:!1},this.set_type=function(c,d){var e=this._model.data,f,g,h,i,j,k,l,m;if(a.isArray(c)){for(c=c.slice(),g=0,h=c.length;h>g;g++)this.set_type(c[g],d);return!0}if(f=this.settings.types,c=this.get_node(c),!f[d]||!c)return!1;if(l=this.get_node(c,!0),l&&l.length&&(m=l.children(".jstree-anchor")),i=c.type,j=this.get_icon(c),c.type=d,(j===!0||f[i]&&f[i].icon!==b&&j===f[i].icon)&&this.set_icon(c,f[d].icon!==b?f[d].icon:!0),f[i].li_attr!==b&&"object"==typeof f[i].li_attr)for(k in f[i].li_attr)if(f[i].li_attr.hasOwnProperty(k)){if("id"===k)continue;"class"===k?(e[c.id].li_attr["class"]=(e[c.id].li_attr["class"]||"").replace(f[i].li_attr[k],""),l&&l.removeClass(f[i].li_attr[k])):e[c.id].li_attr[k]===f[i].li_attr[k]&&(e[c.id].li_attr[k]=null,l&&l.removeAttr(k))}if(f[i].a_attr!==b&&"object"==typeof f[i].a_attr)for(k in f[i].a_attr)if(f[i].a_attr.hasOwnProperty(k)){if("id"===k)continue;"class"===k?(e[c.id].a_attr["class"]=(e[c.id].a_attr["class"]||"").replace(f[i].a_attr[k],""),m&&m.removeClass(f[i].a_attr[k])):e[c.id].a_attr[k]===f[i].a_attr[k]&&("href"===k?(e[c.id].a_attr[k]="#",m&&m.attr("href","#")):(delete e[c.id].a_attr[k],m&&m.removeAttr(k)))}if(f[d].li_attr!==b&&"object"==typeof f[d].li_attr)for(k in f[d].li_attr)if(f[d].li_attr.hasOwnProperty(k)){if("id"===k)continue;e[c.id].li_attr[k]===b?(e[c.id].li_attr[k]=f[d].li_attr[k],l&&("class"===k?l.addClass(f[d].li_attr[k]):l.attr(k,f[d].li_attr[k]))):"class"===k&&(e[c.id].li_attr["class"]=f[d].li_attr[k]+" "+e[c.id].li_attr["class"],l&&l.addClass(f[d].li_attr[k]))}if(f[d].a_attr!==b&&"object"==typeof f[d].a_attr)for(k in f[d].a_attr)if(f[d].a_attr.hasOwnProperty(k)){if("id"===k)continue;e[c.id].a_attr[k]===b?(e[c.id].a_attr[k]=f[d].a_attr[k],m&&("class"===k?m.addClass(f[d].a_attr[k]):m.attr(k,f[d].a_attr[k]))):"href"===k&&"#"===e[c.id].a_attr[k]?(e[c.id].a_attr.href=f[d].a_attr.href,m&&m.attr("href",f[d].a_attr.href)):"class"===k&&(e[c.id].a_attr["class"]=f[d].a_attr["class"]+" "+e[c.id].a_attr["class"],m&&m.addClass(f[d].a_attr[k]))}return!0}},a.jstree.defaults.unique={case_sensitive:!1,duplicate:function(a,b){return a+" ("+b+")"}},a.jstree.plugins.unique=function(c,d){this.check=function(b,c,e,f,g){if(d.check.call(this,b,c,e,f,g)===!1)return!1;if(c=c&&c.id?c:this.get_node(c),e=e&&e.id?e:this.get_node(e),!e||!e.children)return!0;var h="rename_node"===b?f:c.text,i=[],j=this.settings.unique.case_sensitive,k=this._model.data,l,m;for(l=0,m=e.children.length;m>l;l++)i.push(j?k[e.children[l]].text:k[e.children[l]].text.toLowerCase());switch(j||(h=h.toLowerCase()),b){case"delete_node":return!0;case"rename_node":return l=-1===a.inArray(h,i)||c.text&&c.text[j?"toString":"toLowerCase"]()===h,l||(this._data.core.last_error={error:"check",plugin:"unique",id:"unique_01",reason:"Child with name "+h+" already exists. Preventing: "+b,data:JSON.stringify({chk:b,pos:f,obj:c&&c.id?c.id:!1,par:e&&e.id?e.id:!1})}),l;case"create_node":return l=-1===a.inArray(h,i),l||(this._data.core.last_error={error:"check",plugin:"unique",id:"unique_04",reason:"Child with name "+h+" already exists. Preventing: "+b,data:JSON.stringify({chk:b,pos:f,obj:c&&c.id?c.id:!1,par:e&&e.id?e.id:!1})}),l;case"copy_node":return l=-1===a.inArray(h,i),l||(this._data.core.last_error={error:"check",plugin:"unique",
id:"unique_02",reason:"Child with name "+h+" already exists. Preventing: "+b,data:JSON.stringify({chk:b,pos:f,obj:c&&c.id?c.id:!1,par:e&&e.id?e.id:!1})}),l;case"move_node":return l=c.parent===e.id&&(!g||!g.is_multi)||-1===a.inArray(h,i),l||(this._data.core.last_error={error:"check",plugin:"unique",id:"unique_03",reason:"Child with name "+h+" already exists. Preventing: "+b,data:JSON.stringify({chk:b,pos:f,obj:c&&c.id?c.id:!1,par:e&&e.id?e.id:!1})}),l}return!0},this.create_node=function(c,e,f,g,h){if(!e||e.text===b){if(null===c&&(c=a.jstree.root),c=this.get_node(c),!c)return d.create_node.call(this,c,e,f,g,h);if(f=f===b?"last":f,!f.toString().match(/^(before|after)$/)&&!h&&!this.is_loaded(c))return d.create_node.call(this,c,e,f,g,h);e||(e={});var i,j,k,l,m,n=this._model.data,o=this.settings.unique.case_sensitive,p=this.settings.unique.duplicate;for(j=i=this.get_string("New node"),k=[],l=0,m=c.children.length;m>l;l++)k.push(o?n[c.children[l]].text:n[c.children[l]].text.toLowerCase());l=1;while(-1!==a.inArray(o?j:j.toLowerCase(),k))j=p.call(this,i,++l).toString();e.text=j}return d.create_node.call(this,c,e,f,g,h)}};var q=i.createElement("DIV");if(q.setAttribute("unselectable","on"),q.setAttribute("role","presentation"),q.className="jstree-wholerow",q.innerHTML="&#160;",a.jstree.plugins.wholerow=function(b,c){this.bind=function(){c.bind.call(this),this.element.on("ready.jstree set_state.jstree",a.proxy(function(){this.hide_dots()},this)).on("init.jstree loading.jstree ready.jstree",a.proxy(function(){this.get_container_ul().addClass("jstree-wholerow-ul")},this)).on("deselect_all.jstree",a.proxy(function(a,b){this.element.find(".jstree-wholerow-clicked").removeClass("jstree-wholerow-clicked")},this)).on("changed.jstree",a.proxy(function(a,b){this.element.find(".jstree-wholerow-clicked").removeClass("jstree-wholerow-clicked");var c=!1,d,e;for(d=0,e=b.selected.length;e>d;d++)c=this.get_node(b.selected[d],!0),c&&c.length&&c.children(".jstree-wholerow").addClass("jstree-wholerow-clicked")},this)).on("open_node.jstree",a.proxy(function(a,b){this.get_node(b.node,!0).find(".jstree-clicked").parent().children(".jstree-wholerow").addClass("jstree-wholerow-clicked")},this)).on("hover_node.jstree dehover_node.jstree",a.proxy(function(a,b){"hover_node"===a.type&&this.is_disabled(b.node)||this.get_node(b.node,!0).children(".jstree-wholerow")["hover_node"===a.type?"addClass":"removeClass"]("jstree-wholerow-hovered")},this)).on("contextmenu.jstree",".jstree-wholerow",a.proxy(function(b){if(this._data.contextmenu){b.preventDefault();var c=a.Event("contextmenu",{metaKey:b.metaKey,ctrlKey:b.ctrlKey,altKey:b.altKey,shiftKey:b.shiftKey,pageX:b.pageX,pageY:b.pageY});a(b.currentTarget).closest(".jstree-node").children(".jstree-anchor").first().trigger(c)}},this)).on("click.jstree",".jstree-wholerow",function(b){b.stopImmediatePropagation();var c=a.Event("click",{metaKey:b.metaKey,ctrlKey:b.ctrlKey,altKey:b.altKey,shiftKey:b.shiftKey});a(b.currentTarget).closest(".jstree-node").children(".jstree-anchor").first().trigger(c).focus()}).on("click.jstree",".jstree-leaf > .jstree-ocl",a.proxy(function(b){b.stopImmediatePropagation();var c=a.Event("click",{metaKey:b.metaKey,ctrlKey:b.ctrlKey,altKey:b.altKey,shiftKey:b.shiftKey});a(b.currentTarget).closest(".jstree-node").children(".jstree-anchor").first().trigger(c).focus()},this)).on("mouseover.jstree",".jstree-wholerow, .jstree-icon",a.proxy(function(a){return a.stopImmediatePropagation(),this.is_disabled(a.currentTarget)||this.hover_node(a.currentTarget),!1},this)).on("mouseleave.jstree",".jstree-node",a.proxy(function(a){this.dehover_node(a.currentTarget)},this))},this.teardown=function(){this.settings.wholerow&&this.element.find(".jstree-wholerow").remove(),c.teardown.call(this)},this.redraw_node=function(b,d,e,f){if(b=c.redraw_node.apply(this,arguments)){var g=q.cloneNode(!0);-1!==a.inArray(b.id,this._data.core.selected)&&(g.className+=" jstree-wholerow-clicked"),this._data.core.focused&&this._data.core.focused===b.id&&(g.className+=" jstree-wholerow-hovered"),b.insertBefore(g,b.childNodes[0])}return b}},i.registerElement&&Object&&Object.create){var r=Object.create(HTMLElement.prototype);r.createdCallback=function(){var b={core:{},plugins:[]},c;for(c in a.jstree.plugins)a.jstree.plugins.hasOwnProperty(c)&&this.attributes[c]&&(b.plugins.push(c),this.getAttribute(c)&&JSON.parse(this.getAttribute(c))&&(b[c]=JSON.parse(this.getAttribute(c))));for(c in a.jstree.defaults.core)a.jstree.defaults.core.hasOwnProperty(c)&&this.attributes[c]&&(b.core[c]=JSON.parse(this.getAttribute(c))||this.getAttribute(c));a(this).jstree(b)};try{i.registerElement("vakata-jstree",{prototype:r})}catch(s){}}}});
/*! ============================================================
 * bootstrapSwitch v1.8 by Larentis Mattia @SpiritualGuru
 * http://www.larentis.eu/
 * 
 * Enhanced for radiobuttons by Stein, Peter @BdMdesigN
 * http://www.bdmdesign.org/
 *
 * Project site:
 * http://www.larentis.eu/switch/
 * ============================================================
 * Licensed under the Apache License, Version 2.0
 * http://www.apache.org/licenses/LICENSE-2.0
 * ============================================================ */

!function ($) {
  "use strict";

  $.fn['bootstrapSwitch'] = function (method) {
    var inputSelector = 'input[type!="hidden"]';
    var methods = {
      init: function () {
        return this.each(function () {
            var $element = $(this)
              , $div
              , $switchLeft
              , $switchRight
              , $label
              , $form = $element.closest('form')
              , myClasses = ""
              , classes = $element.attr('class')
              , color
              , moving
              , onLabel = "ON"
              , offLabel = "OFF"
              , icon = false
              , textLabel = false;

            $.each(['switch-mini', 'switch-small', 'switch-large'], function (i, el) {
              if (classes.indexOf(el) >= 0)
                myClasses = el;
            });

            $element.addClass('has-switch');

            if ($element.data('on') !== undefined)
              color = "switch-" + $element.data('on');

            if ($element.data('on-label') !== undefined)
              onLabel = $element.data('on-label');

            if ($element.data('off-label') !== undefined)
              offLabel = $element.data('off-label');

            if ($element.data('label-icon') !== undefined)
              icon = $element.data('label-icon');

            if ($element.data('text-label') !== undefined)
              textLabel = $element.data('text-label');

            $switchLeft = $('<span>')
              .addClass("switch-left")
              .addClass(myClasses)
              .addClass(color)
              .html(onLabel);

            color = '';
            if ($element.data('off') !== undefined)
              color = "switch-" + $element.data('off');

            $switchRight = $('<span>')
              .addClass("switch-right")
              .addClass(myClasses)
              .addClass(color)
              .html(offLabel);

            $label = $('<label>')
              .html("&nbsp;")
              .addClass(myClasses)
              .attr('for', $element.find(inputSelector).attr('id'));

            if (icon) {
              $label.html('<i class="icon ' + icon + '"></i>');
            }

            if (textLabel) {
              $label.html('' + textLabel + '');
            }

            $div = $element.find(inputSelector).wrap($('<div>')).parent().data('animated', false);

            if ($element.data('animated') !== false)
              $div.addClass('switch-animate').data('animated', true);

            $div
              .append($switchLeft)
              .append($label)
              .append($switchRight);

            $element.find('>div').addClass(
              $element.find(inputSelector).is(':checked') ? 'switch-on' : 'switch-off'
            );

            if ($element.find(inputSelector).is(':disabled'))
              $(this).addClass('deactivate');

            var changeStatus = function ($this) {
              if ($element.parent('label').is('.label-change-switch')) {

              } else {
                $this.siblings('label').trigger('mousedown').trigger('mouseup').trigger('click');
              }
            };

            $element.on('keydown', function (e) {
              if (e.keyCode === 32) {
                e.stopImmediatePropagation();
                e.preventDefault();
                changeStatus($(e.target).find('span:first'));
              }
            });

            $switchLeft.on('click', function (e) {
              changeStatus($(this));
            });

            $switchRight.on('click', function (e) {
              changeStatus($(this));
            });

            $element.find(inputSelector).on('change', function (e, skipOnChange) {
              var $this = $(this)
                , $element = $this.parent()
                , thisState = $this.is(':checked')
                , state = $element.is('.switch-off');

              e.preventDefault();

              $element.css('left', '');

              if (state === thisState) {

                if (thisState)
                  $element.removeClass('switch-off').addClass('switch-on');
                else $element.removeClass('switch-on').addClass('switch-off');

                if ($element.data('animated') !== false)
                  $element.addClass("switch-animate");

                if (typeof skipOnChange === 'boolean' && skipOnChange)
                  return;

                $element.parent().trigger('switch-change', {'el': $this, 'value': thisState})
              }
            });

            $element.find('label').on('mousedown touchstart', function (e) {
              var $this = $(this);
              moving = false;

              e.preventDefault();
              e.stopImmediatePropagation();

              $this.closest('div').removeClass('switch-animate');

              if ($this.closest('.has-switch').is('.deactivate')) {
                $this.unbind('click');
              } else if ($this.closest('.switch-on').parent().is('.radio-no-uncheck')) {
                $this.unbind('click');
              } else {
                $this.on('mousemove touchmove', function (e) {
                  var $element = $(this).closest('.make-switch')
                    , relativeX = (e.pageX || e.originalEvent.targetTouches[0].pageX) - $element.offset().left
                    , percent = (relativeX / $element.width()) * 100
                    , left = 25
                    , right = 75;

                  moving = true;

                  if (percent < left)
                    percent = left;
                  else if (percent > right)
                    percent = right;

                  $element.find('>div').css('left', (percent - right) + "%")
                });

                $this.on('click touchend', function (e) {
                  var $this = $(this)
                    , $target = $(e.target)
                    , $myRadioCheckBox = $target.siblings('input');

                  e.stopImmediatePropagation();
                  e.preventDefault();

                  $this.unbind('mouseleave');

                  if (moving)
                    $myRadioCheckBox.prop('checked', !(parseInt($this.parent().css('left')) < -25));
                  else
                    $myRadioCheckBox.prop("checked", !$myRadioCheckBox.is(":checked"));

                  moving = false;
                  $myRadioCheckBox.trigger('change');
                });

                $this.on('mouseleave', function (e) {
                  var $this = $(this)
                    , $myInputBox = $this.siblings('input');

                  e.preventDefault();
                  e.stopImmediatePropagation();

                  $this.unbind('mouseleave');
                  $this.trigger('mouseup');

                  $myInputBox.prop('checked', !(parseInt($this.parent().css('left')) < -25)).trigger('change');
                });

                $this.on('mouseup', function (e) {
                  e.stopImmediatePropagation();
                  e.preventDefault();

                  $(this).unbind('mousemove');
                });
              }
            });

            if ($form.data('bootstrapSwitch') !== 'injected') {
              $form.bind('reset', function () {
                setTimeout(function () {
                  $form.find('.make-switch').each(function () {
                    var $input = $(this).find(inputSelector);

                    $input.prop('checked', $input.is(':checked')).trigger('change');
                  });
                }, 1);
              });
              $form.data('bootstrapSwitch', 'injected');
            }
          }
        );
      },
      toggleActivation: function () {
        var $this = $(this);

        $this.toggleClass('deactivate');
        $this.find(inputSelector).prop('disabled', $this.is('.deactivate'));
      },
      isActive: function () {
        return !$(this).hasClass('deactivate');
      },
      setActive: function (active) {
        var $this = $(this);

        if (active) {
          $this.removeClass('deactivate');
          $this.find(inputSelector).removeAttr('disabled');
        }
        else {
          $this.addClass('deactivate');
          $this.find(inputSelector).attr('disabled', 'disabled');
        }
      },
      toggleState: function (skipOnChange) {
        var $input = $(this).find(':checkbox');
        $input.prop('checked', !$input.is(':checked')).trigger('change', skipOnChange);
      },
      toggleRadioState: function (skipOnChange) {
        var $radioinput = $(this).find(':radio');
        $radioinput.not(':checked').prop('checked', !$radioinput.is(':checked')).trigger('change', skipOnChange);
      },
      toggleRadioStateAllowUncheck: function (uncheck, skipOnChange) {
        var $radioinput = $(this).find(':radio');
        if (uncheck) {
          $radioinput.not(':checked').trigger('change', skipOnChange);
        }
        else {
          $radioinput.not(':checked').prop('checked', !$radioinput.is(':checked')).trigger('change', skipOnChange);
        }
      },
      setState: function (value, skipOnChange) {
        $(this).find(inputSelector).prop('checked', value).trigger('change', skipOnChange);
      },
      setOnLabel: function (value) {
        var $switchLeft = $(this).find(".switch-left");
        $switchLeft.html(value);
      },
      setOffLabel: function (value) {
        var $switchRight = $(this).find(".switch-right");
        $switchRight.html(value);
      },
      setOnClass: function (value) {
        var $switchLeft = $(this).find(".switch-left");
        var color = '';
        if (value !== undefined) {
          if ($(this).attr('data-on') !== undefined) {
            color = "switch-" + $(this).attr('data-on')
          }
          $switchLeft.removeClass(color);
          color = "switch-" + value;
          $switchLeft.addClass(color);
        }
      },
      setOffClass: function (value) {
        var $switchRight = $(this).find(".switch-right");
        var color = '';
        if (value !== undefined) {
          if ($(this).attr('data-off') !== undefined) {
            color = "switch-" + $(this).attr('data-off')
          }
          $switchRight.removeClass(color);
          color = "switch-" + value;
          $switchRight.addClass(color);
        }
      },
      setAnimated: function (value) {
        var $element = $(this).find(inputSelector).parent();
        if (value === undefined) value = false;
        $element.data('animated', value);
        $element.attr('data-animated', value);

        if ($element.data('animated') !== false) {
          $element.addClass("switch-animate");
        } else {
          $element.removeClass("switch-animate");
        }
      },
      setSizeClass: function (value) {
        var $element = $(this);
        var $switchLeft = $element.find(".switch-left");
        var $switchRight = $element.find(".switch-right");
        var $label = $element.find("label");
        $.each(['switch-mini', 'switch-small', 'switch-large'], function (i, el) {
          if (el !== value) {
            $switchLeft.removeClass(el);
            $switchRight.removeClass(el);
            $label.removeClass(el);
          } else {
            $switchLeft.addClass(el);
            $switchRight.addClass(el);
            $label.addClass(el);
          }
        });
      },
      status: function () {
        return $(this).find(inputSelector).is(':checked');
      },
      destroy: function () {
        var $element = $(this)
          , $div = $element.find('div')
          , $form = $element.closest('form')
          , $inputbox;

        $div.find(':not(input)').remove();

        $inputbox = $div.children();
        $inputbox.unwrap().unwrap();

        $inputbox.unbind('change');

        if ($form) {
          $form.unbind('reset');
          $form.removeData('bootstrapSwitch');
        }

        return $inputbox;
      }
    };

    if (methods[method])
      return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
    else if (typeof method === 'object' || !method)
      return methods.init.apply(this, arguments);
    else
      $.error('Method ' + method + ' does not exist!');
  };
}(jQuery);

/* Category Sticky Top Start */
if( melisCore.screenSize >= 768){
	$(window).on('scroll click resize', function(e) {
		$("#id_meliscommerce_categories_category_header").css("width","100%");
		var stickyCatNav = $("#"+ activeTabId + ' #id_meliscommerce_categories_category');
		if(stickyCatNav.length){
			var position = stickyCatNav.position();
			if (position.top < ($(window).scrollTop() - 10)) {
				$("#id_meliscommerce_categories_category").addClass("fix-cat");
				$("#categoryInfoPanel").css("padding-top","66px");
				$("#saveCategory").css("margin-top","10px");
				$("#id_meliscommerce_categories_category_header").width($("#id_meliscommerce_categories_list").width());
			} else {
				$("#id_meliscommerce_categories_category").removeClass("fix-cat");
				$("#categoryInfoPanel").css("padding-top","0");
				$("#saveCategory").css("margin-top","0");
			}
		}		
	});
}
/* Category Sticky Top End */

var categoryOpeningItemFlag = true;
$(function(){
	
	$("body").on("click", ".addCategory", function(e){ 
		$("#categoryTreeViewPanel").collapse("hide");
		
		var zoneId = 'id_meliscommerce_categories_category';
		var melisKey = 'meliscommerce_categories_category';
		
		var catTree = $('#categoryTreeView').jstree(true);
		
		var catSelected = catTree.get_selected();
		var catFatherId = '';
		if(catSelected.length >= 1){
			catFatherId = catSelected[0];
		}
		
		$("#"+zoneId).removeClass("hidden");
		
		melisHelper.zoneReload(zoneId, melisKey, {catId : 0, catFatherId: catFatherId});
		melisCommerce.setUniqueId(0);
	});
	
	$("body").on("click", ".addCatalog", function(e){ 
		$("#categoryTreeViewPanel").collapse("hide");
		
		var zoneId = 'id_meliscommerce_categories_category';
		var melisKey = 'meliscommerce_categories_category';
		
		$("#"+zoneId).removeClass("hidden");
		
		melisHelper.zoneReload(zoneId, melisKey, {catId : 0, catFatherId: -1});
		melisCommerce.setUniqueId(0);
	});
	
	$("body").on("click", "#saveCategory", function(){ 
		
		
		$(this).button("loading");
		
		var catId = $(this).data('catid');
		var dataString = new Array;
		
		// Serialize Forms of Category Panel
		dataString = $("#id_meliscommerce_categories_category form").not(".category_"+catId+"_seo_form, .cat_trans_form").serializeArray();
		
		
		// Category Id
		dataString.push({
			name : "cat_id",
			value: catId
		});
		
		dataString = melisCommerceSeo.serializeSeo('category', catId, dataString);
		
		// Category Parent Id
		var catFatherId = $(this).data('catfatherid');
		dataString.push({
			name : "cat_father_cat_id",
			value: catFatherId
		});
		
		// Category Status
		var catStatus = 0;
		if($('input[name="cat_status"]').is(':checked')){
			catStatus = 1;
		}
		
		dataString.push({
			name : "cat_status",
			value: catStatus
		});
		
		// Category Transalations
		$("form.cat_trans_form").each(function(){
			langLocale = $(this).data("locale");
			langId = $(this).data("langid");
			
			// convert the serialized form values into an array
			catDataString = $(this).serializeArray();
			
			$.each(catDataString, function(){
				dataString.push({
					name : 'cat_trans['+langId+']['+this.name+']',
					value: this.value
				});
			});
		});
		
		// serialize the new array and send it to server
		dataString = $.param(dataString);
		
		$.ajax({
	        type        : "POST", 
	        url         : "/melis/MelisCommerce/MelisComCategory/saveCategory",
	        data		: dataString,
	        dataType    : "json",
	        encode		: true,
	        cache		: false,
		}).done(function(data) {
			
			$("#saveCategory").button("reset");
			
			if(data.success) {
				$("#categoryTreeViewPanel").collapse("show");
				
				$("body").animate({
			        scrollTop: 0
			    }, 1000); 
				
				melisCore.flashMessenger();
				melisHelper.melisOkNotification(data.textTitle, data.textMessage);
				
				var catTree = $('#categoryTreeView').jstree(true);
				// Get Current Url of the category Tree view
				var realUrl = catTree.settings.core.data.url;
				
				// selected Category Id/Node
				var selectedNode = '';
				if(catId==0){
					// New Category Created
					var nodeData = catTree.get_node(catFatherId);
					
					var nodeParents = new Array;
					
					nodeParentsStr = '';
					
					nodeParents.push(catFatherId);
					
					if(typeof nodeData === "object"){
						if(nodeData.parents.length>1){
							for(i = 0; i<nodeData.parents.length-1 ; i++){
								nodeParents.push(nodeData.parents[i]);
							}
						}
					}
					
					nodeParentsStr = "&openStateParent="+nodeParents.join();
					
					selectedNode = data.cat_id;
					
				}else{
					// Category exist
					var nodeData = catTree.get_node(catId);
					
					var nodeParents = new Array;
					
					nodeParentsStr = ''; 
					
					if(nodeData !== false){
						if(nodeData.parents.length>1 ){
							for(i = 0; i<nodeData.parents.length-1 ; i++){
								nodeParents.push(nodeData.parents[i]);
							}
							nodeParentsStr = "&openStateParent="+nodeParents.join();
						}
						
						catTree.get_node(catId).state.selected = true;
					}
					
					selectedNode = catId;
				}
				
				// Set JsTree Url with Selected Node and Open State Nodes
				catTree.settings.core.data.url = realUrl+"&selected="+selectedNode+nodeParentsStr;
				// Deselect selected node
				catTree.deselect_all();
				// Remove Node Highllight
				$("#categoryTreeView ul li div").removeClass("jstree-wholerow-clicked");
				//refresh Category view
				catTree.refresh();
				// Rollback the real/default url
				catTree.settings.core.data.url = realUrl;
				
	    		var zoneId = 'id_meliscommerce_categories_category';
	    		var melisKey = 'meliscommerce_categories_category';
	    		melisHelper.zoneReload(zoneId, melisKey, {catId : selectedNode});
	    		melisCommerce.setUniqueId(selectedNode);
	    		
	    		// Highlighting the node
	    		$("#categoryTreeView #"+selectedNode+" div").first().addClass("jstree-wholerow-clicked");
			}else{
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors );
				melisCoreTool.highlightErrors(data.success, data.errors, "id_meliscommerce_categories_category_form_transalations");
			}
			
			melisCore.flashMessenger();
			
		}).fail(function(){
			
			$("#saveCategory").button("reset");
			
			alert( translations.tr_meliscore_error_message);
		});
	});
	
	// Category Tree Languages Dropdown
	$("body").on("click", ".category-tree-view-lang li a", function(){
		categoryOpeningItemFlag = false;
		var langText = $(this).text();
		var langLocale = $(this).data('locale');
		$('.cat-tree-view-languages span.filter-key').text(langText);		
		$("#categoryTreeView").data('langlocale',langLocale);
		$("#categoryTreeView").jstree('destroy');
		initCategoryTreeView();
	});
		
	// Search Input
	$("body").on("keyup", "#categoryTreeViewSearchInput", function(e){ 
		categoryOpeningItemFlag = false;
		
		var searchString = $(this).val().trim();
		var searchResult = $('#categoryTreeView').jstree('search', searchString);
		
		setTimeout(function(){ 
			if($(searchResult).find('.jstree-search').length == 0 && searchString != ''){
				$("#searchNoResult").removeClass('hidden');
				$("#searchNoResult").find("strong").text(searchString);
			}else{
				$("#searchNoResult").addClass('hidden');
			}
		}, 1500);
		
	});
	
	$("body").on('keyup keypress', '#categoryTreeViewSearchForm', function(e) {
		var keyCode = e.keyCode || e.which;
		if (keyCode === 13) { 
		    e.preventDefault();
		    return false;
		}
	});
	
	// Clear Input Search
	$("body").on("click", "#clearSearchInputBtn", function(e){ 
		categoryOpeningItemFlag = false;
		var catTree = $('#categoryTreeView').jstree(true);
		$("#categoryTreeViewSearchInput").val("");
		$('#categoryTreeView').jstree('search', '');
	});
	
	// Toggle Buttons for Category Tree View
	$("body").on("click", "#expandCategoryTreeViewBtn", function(e){ 
		categoryOpeningItemFlag = false;
		$("#categoryTreeView").jstree("open_all");
	});
	$("body").on("click", "#collapseCategoryTreeViewBtn", function(e){ 
		categoryOpeningItemFlag = false;
		$("#categoryTreeView").jstree("close_all");
	});
	
	// Refrech Category Tree View
	$("body").on("click", "#refreshCategoryTreeView", function(e){ 
		categoryOpeningItemFlag = false;
		var catTree = $('#categoryTreeView').jstree(true);
		catTree.deselect_all();
		catTree.refresh();
		$("#categoryTreeViewSearchInput").val("");
		$('#categoryTreeView').jstree('search', '');
	});
	
	// Category Information Form Countries Custom Checkboxes
	$("body").on("click", ".ecom-coutries-checkbox", function(evt){
		
		if($(this).find('.fa').hasClass('fa-check-square-o')){ // unchecking category Checkbox
			if(!$(this).find('.fa').hasClass("check-all")){
				$(this).find('.fa').removeClass('fa-check-square-o');
				$(this).find('.fa').addClass('fa-square-o');
				$(this).find('input[type="checkbox"]').removeAttr('checked');
			}
		}else{ // Checking Category Checkboxes
			if($(this).find('.fa').hasClass("check-all")){ // Unchecking "All" Checkbox
				$(".ecom-coutries-checkbox").find('.fa').removeClass('fa-check-square-o');
				$(".ecom-coutries-checkbox").find('.fa').addClass('fa-square-o');
				$(".ecom-coutries-checkbox").find('input[type="checkbox"]').removeAttr('checked');
			}else{ // Checking "All" Checkbox
				$(".ecom-coutries-checkbox").find('.check-all').removeClass('fa-check-square-o');
				$(".ecom-coutries-checkbox").find('.check-all').addClass('fa-square-o');
				$(".ecom-coutries-checkbox").find('.check-all').next('input[type="checkbox"]').removeAttr('checked'); // Unchecking "All" Checkbox
			}
			
			$(this).find('.fa').removeClass('fa-square-o');
			$(this).find('.fa').addClass('fa-check-square-o');
			$(this).find('input[type="checkbox"]').attr('checked','checked');
		}
		
		evt.stopPropagation();
		evt.preventDefault();
	});
	
	// Category Information Form Status, Switch Plugin
	$("body").on("switch-change", "#cat_status", function(event, state) {
		if(state.value == true){
			$(this).find('input[type="checkbox"]').attr('checked','checked');
		}else{
			$(this).find('input[type="checkbox"]').removeAttr('checked');
		}
	});
	
	$("body").on("click", ".categoryProductsRefresh", function(event, state) {
		var catId = $("#categoryProductListTbl").data("catid");
		melisHelper.zoneReload('id_meliscommerce_categories_category_tab_products','meliscommerce_categories_category_tab_products',{catId:catId,activateTab:true});
	});
	
	// Category Products Remove Button
	$("body").on("click", ".categoryProductsRemove", function(){
		
		var pcatId = $(this).parents("tr").attr("id");
		var catId = $("#categoryProductListTbl").data("catid");
		
		var parentId = $("#saveCategory").data("catfatherid");
		
		var deleteTitle = translations.tr_meliscommerce_categories_category_product_remove;
    	var deleteMessage = translations.tr_meliscommerce_categories_category_product_remove_confirm_msg;
    	if(parentId == '-1'){
    		deleteTitle = translations.tr_meliscommerce_categories_catalog_product_remove;
        	deleteMessage = translations.tr_meliscommerce_categories_catalog_product_remove_confirm_msg;
    	}
		
		// deletion confirmation
		melisCoreTool.confirm(
		translations.tr_meliscommerce_categories_common_label_yes,
		translations.tr_meliscommerce_categories_common_label_no,
		deleteTitle, 
		deleteMessage, 
		function() {
			
			var dataString = new Array;
			
			dataString.push({
				name : "pcat_id",
				value: pcatId
			});
			
			dataString.push({
				name : "parent_id",
				value: parentId
			});
			
			dataString.push({
				name : "cat_id",
				value: catId
			});
			
	        dataString = $.param(dataString);
	        
			$.ajax({
 		        type        : "POST", 
 		        url         : "/melis/MelisCommerce/MelisComCategory/removeCategoryProduct",
 		        data		: dataString,
 		        dataType    : "json",
 		        encode		: true
 			}).done(function(data) {
 				if(data.success) {
 					melisHelper.zoneReload('id_meliscommerce_categories_category_tab_products','meliscommerce_categories_category_tab_products',{catId:catId,activateTab:true});
 				}else{
 					alert( translations.tr_meliscore_error_message );
 				}
 				melisCore.flashMessenger();
				melisHelper.melisOkNotification(data.textTitle, data.textMessage);
 			}).fail(function(){
 				alert( translations.tr_meliscore_error_message );
 			});
		});
	});
	
	// Category Products View Button
	$("body").on("click", ".categoryProductsView", function(){
		var productId   = $(this).closest("tr").data("productid");
		//var productName = $(this).parents("tr").find(".toolTipHoverEvent").text();
		var productName = $(this).closest("tr").data("productname");
        var navTabsGroup = "id_meliscommerce_product_list_container";
		// Open parent tab
        melisHelper.tabOpen(translations.tr_meliscommerce_products_Products, 'fa icon-shippment fa-2x', 'id_meliscommerce_product_list_container', 'meliscommerce_product_list_container');
        var alreadyOpen = $("body #melis-id-nav-bar-tabs li a.tab-element[data-id='"+navTabsGroup+"']");
        // check if it exists
        var checkProducts = setInterval(function() {
            if(alreadyOpen.length){
                melisCommerce.openProductPage(productId, productName, navTabsGroup);
                melisCommerce.setUniqueId(productId);
                clearInterval(checkProducts);
            }
        }, 500);
        // melisCommerce.openProductPage(productId, productName, navTabsGroup);
	});
	
	// Category Tree Double Click Item Action
	$("body").on("dblclick", ".jstree-node", function(evt){
		
		$("#categoryTreeViewPanel").collapse("hide");
		
		var catId = parseInt($(this).attr("id"), 10);
    	
		var zoneId = 'id_meliscommerce_categories_category';
		var melisKey = 'meliscommerce_categories_category';
		
		$("#"+zoneId).removeClass("hidden");
		
		melisHelper.zoneReload(zoneId, melisKey, {catId : catId});
		
		// Highlighting the node
		$("#categoryTreeView #"+catId+" div").first().addClass("jstree-wholerow-clicked");
		melisCommerce.setUniqueId(catId);
		evt.stopPropagation();
		evt.preventDefault();
	});
	
	// Open Single Node in JSTree
	$("body").on("click", ".cat-div .jstree-node .jstree-icon", function(){
		categoryOpeningItemFlag = true;
	});
	
    $("body").on("mouseenter mouseout", ".toolTipCatHoverEvent", function(e) {
      $(".thClassColId").attr("style", "");
  	  var productId = $(this).data("productid");
  	  var loaderText = '<div class="qtipLoader"><hr/><span class="text-center col-lg-12">Loading...</span><br/></div>';
  	  $.each($("table#catProductTable"+productId + " thead").nextAll(), function(i,v) {
  		  $(v).remove();
  	  });
  	  $(loaderText).insertAfter("table#catProductTable"+productId + " thead");
  		var xhr = $.ajax({
  	        type        : 'POST', 
  	        url         : 'melis/MelisCommerce/MelisComProductList/getToolTip',
  	        data		: {productId : productId},
  	        dataType    : 'json',
  	        encode		: true,
  	     }).success(function(data){
      	 	 $("div.qtipLoader").remove();
  		     if(data.content.length === 0) {
  		    	 $('<div class="qtipLoader"><hr/><span class="text-center col-lg-12">'+translations.tr_meliscommerce_product_tooltip_no_variants+'</span><br/></div>').insertAfter("table.qtipTable thead");
  		     }
  		     else {
  		    	 // make sure tbody is clear
  				  $.each($("table#catProductTable"+productId + " thead").nextAll(), function(i,v) {
  					  $(v).remove();
  				  });
      		     $.each(data.content.reverse(), function(i ,v) {
      		    	 $(v).insertAfter("table#catProductTable"+productId + " thead")
      		     });
  		    	 
  		     }

  	     });
  		if(e.type === "mouseout") {
  			xhr.abort();
  		}
  	  });
});

window.enableDisableAddCategoryBtn = function(action){
	var addCategory = $('.addCategory');
	if(action == 'enable'){
		addCategory.attr('disabled', false);
		addCategory.attr('title', null);
	}else if (action == 'disable'){
		addCategory.attr('disabled', true);
		addCategory.attr('title', translations.tr_meliscommerce_categories_category_no_selected_catalog_category);
	}
}

window.initCategoryTreeView = function(){
	
	$("body").on("click", "#categoryTreeView", function(evt){
		$("#categoryTreeView ul li div").removeClass("jstree-wholerow-clicked");
		evt.stopPropagation();
		evt.preventDefault();
	});
	
	$('#categoryTreeView')
		.on('changed.jstree', function (e, data) {
			enableDisableAddCategoryBtn('enable');
		})
		.on('refresh.jstree', function (e, data) {
			enableDisableAddCategoryBtn('disable');
		})
		.on('loading.jstree', function (e, data) {
			melisCommerce.pendingZoneStart("meliscommerce_categories_list_search_input");
		})
		.on('loaded.jstree', function (e, data) {
			melisCommerce.pendingZoneDone("meliscommerce_categories_list_search_input");
			var temp = $('ul.jstree-container-ul > li > a');
			temp.each(function(){
				var father = $(this);
				var fatherIcon = father.data('fathericon');
				var temp = father.find('i');
				father.html(temp.get(0).outerHTML + '<b>' + fatherIcon +' ' + father.text() + '</b>');
			})

		})
		.on('refresh.jstree', function (e, data) {
			var temp = $('ul.jstree-container-ul > li > a');
			temp.each(function(){
				var father = $(this);
				var fatherIcon = father.data('fathericon');
				var temp = father.find('i');
				father.html(temp.get(0).outerHTML + '<b>' + fatherIcon +' ' + father.text() + '</b>');
			})

		})
		.on('open_node.jstree', function (e, data) {
			
			if(categoryOpeningItemFlag == true){
				if($(".cat-div").length){
					// if Node open sub nodes and not visible to the parent container, this will scroll down to show the sub nodes
					if($(".cat-div #"+data.node.id).offset().top + $(".cat-div #"+data.node.id).height() > $(".cat-div").offset().top + $(".cat-div").height() ){
						// exucute scroll after the opening animation of the node
						$timeOut = setTimeout(function(){ 
							var catContainer = $('.cat-div').scrollTop();
							var catItemHeight = $(".cat-div #"+data.node.id).innerHeight()
							$('.cat-div').animate({
								scrollTop: catContainer + catItemHeight
							}, 'slow');
							
						}, 1000);
					}
				}
			}
		})
		.on('after_open.jstree', function (e, data) {
			
			$.each(data.node.children_d, function(k, v){
				
				var textlang = $('#'+v+'_anchor').data('textlang');
				var products = $('#'+v+'_anchor').data('numprods');
				var spanHtml = '<span title="' + translations.tr_meliscommerce_categories_list_tree_view_product_num + '">('+ products +')</span>';
				var seoId = $('#'+v+'_anchor').data('seopage');
				if(seoId){
					spanHtml = spanHtml + ' - <span class="fa fa-file-o"></span> ' +  seoId ;
				}
				
				if(textlang){
					spanHtml = ' ' + textlang + spanHtml;
				}
				
				if(!$('#'+v+'_anchor').hasClass('updatedText')){
					$('#'+v+'_anchor').append(spanHtml);
					$('#'+v+'_anchor').addClass('updatedText');
				}
				
			});
		 })
		.on("move_node.jstree", function (e, data) {
	        // Category Id
	        var categoryId = data.node.id;
	        // New category Parent ID
	        // if value is '#', the Category is on the root of the list
	        var newParentId = (data.parent=='#') ? '-1' : data.parent;
	        // Old category Parent ID
	        // if value is '#', the Category is on the root of the list
	        var oldParent = (data.old_parent=='#') ? '-1' : data.old_parent;
	        // New Category Position
	        // Position is the index on the data
	        // Adding One(1) to make to avaoid Zero(0) index of position
	        var categoryNewPosition = data.position + 1;
	        
	        var dataString = new Array();
			// get data from input
	        dataString.push({
				name: "cat_id",
				value: parseInt(categoryId, 10)
			});
			// get date data from param
			dataString.push({
				name: "cat_father_cat_id",
				value: parseInt(newParentId, 10)
			});
			// get date data from param
			dataString.push({
				name: "cat_order",
				value: categoryNewPosition
			});
			// get date data from param
			dataString.push({
				name: "old_parent",
				value: parseInt(oldParent, 10)
			});
			
			dataString = $.param(dataString);
			
	        $.ajax({
		        type        : "POST", 
		        url         : "/melis/MelisCommerce/MelisComCategoryList/saveCategoryTreeView",
		        data		: dataString,
		        dataType    : "json",
		        encode		: true
			}).done(function(data) {
				
				if(data.success) {
					$currentCategoryId = $("#saveCategory").data("catid");
					
					if($currentCategoryId == categoryId){
						$("#saveCategory").data("catfatherid", newParentId);
					}
				}else{
					alert( translations.tr_meliscore_error_message );
				}
			}).fail(function(){
				
				alert( translations.tr_meliscore_error_message );
			});
	    })
	    .jstree({
		"contextmenu" : {
		    "items" : function (node) {
		        return {
		            "Add" : {
		                "label" : translations.tr_meliscommerce_categories_common_btn_add,
		                "icon"  : "fa fa-plus",
		                "action" : function (obj) {
		                	
		                	var parentId = parseInt(node.id);
		                	var position = node.children.length + 1;
		                	
		                	$("#categoryTreeViewPanel").collapse("hide");
		                	
		                	var zoneId = "id_meliscommerce_categories_category";
		                	var melisKey = "meliscommerce_categories_category";
		                	
		            		melisHelper.zoneReload(zoneId, melisKey,{catId:0, catFatherId:parentId, catOrder:position});
		                	
		                }
		            },
		            "Update" : {
		                "label" : translations.tr_meliscommerce_categories_common_btn_update,
		                "icon"  : "fa fa-edit",
		                "action" : function (obj) {
		                	
		            		var catId = parseInt(node.id , 10);
		                	
		            		var zoneId = 'id_meliscommerce_categories_category';
		            		var melisKey = 'meliscommerce_categories_category';
		            		
		            		melisHelper.zoneReload(zoneId, melisKey, {catId : catId});
		            		
		            		$("#categoryTreeViewPanel").collapse("hide");
		                	
		                }
		            },
		            "Delete" : {
		                "label" : translations.tr_meliscommerce_categories_common_btn_delete,
		                "icon"  : "fa fa-trash-o",
		                "action" : function (obj) {
		                		
		                	var dataString = new Array();
		                	
		                	// New category Parent ID
		        	        // if value is '#', the Category is on the root of the list
		        	        var parentId = (node.parent=='#') ? '-1' : parseInt(node.parent, 10);
		        	        
		        	        dataString.push({
		        				name: "cat_father_cat_id",
		        				value: parentId
		        			});
		                	
		                	var cattId = parseInt(node.id);
		                	
		                	dataString.push({
		        				name: "cat_id",
		        				value: cattId
		        			});
		                	
		                	dataString = $.param(dataString);
		                	
		                	var deleteTitle = translations.tr_meliscommerce_categories_category_delete;
		                	var deleteMessage = translations.tr_meliscommerce_categories_category_delete_confirm_msg;
		                	if(parentId == '-1'){
		                		deleteTitle = translations.tr_meliscommerce_categories_catalog_delete;
			                	deleteMessage = translations.tr_meliscommerce_categories_catalog_delete_confirm_msg;
		                	}
		                	
		                	// deletion confirmation
		            		melisCoreTool.confirm(
		            		translations.tr_meliscommerce_categories_common_label_yes,
		            		translations.tr_meliscommerce_categories_common_label_no,
		            		deleteTitle, 
		            		deleteMessage, 
		            		function() {
		            			$.ajax({
			        		        type        : "POST", 
			        		        url         : "/melis/MelisCommerce/MelisComCategory/deleteCategory",
			        		        data		: dataString,
			        		        dataType    : "json",
			        		        encode		: true
			        			}).done(function(data) {
			        				if(data.success) {
			        					var catTree = $('#categoryTreeView').jstree(true);
			        					catTree.delete_node(cattId+'_categoryId_anchor');
			        	            	
			        	            	if($("#saveCategory").data("catid")==cattId){
			        	            		var zoneId = "id_meliscommerce_categories_category";
			    		                	var melisKey = "meliscommerce_categories_category";
			    		                	
			    		            		melisHelper.zoneReload(zoneId, melisKey);
			        	            	}
			        	            	
			        	            	melisCore.flashMessenger();
			        					melisHelper.melisOkNotification(data.textTitle, data.textMessage);
			        				}else{
			        					melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
			        				}
			        			}).fail(function(){
			        				alert( translations.tr_meliscore_error_message );
			        			});
		            		});
		                }
		            },
		        };
		    }
		},
	    "core" : {
	    	"multiple": false,
	        "check_callback": true,
	        "animation" : 500,
	        "themes": {
                "name": "proton",
                "responsive": false
            },
            "dblclick_toggle" : false,
	        "data" : {
	        	"cache" : false,
	            "url" : "/melis/MelisCommerce/MelisComCategoryList/getCategoryTreeView?langlocale="+$("#categoryTreeView").data('langlocale'),
	        },
	    },
	    "types" : {
            "#" : {
                "valid_children" : ["catalog"]
            },
            "catalog" : {
                "valid_children" : ["category"]
            },
            "category" : {
            	"valid_children" : ["category"]
            },
        },
	    "plugins": [
            "contextmenu", // plugin makes it possible to right click nodes and shows a list of configurable actions in a menu.
	        "changed", // Plugins for Change and Click Event
	        "dnd", // Plugins for Drag and Drop
	        "search", // Plugins for Search of the Node(s) of the Tree View
	        "types", // Plugins for Customizing the Nodes
        ]
    });
	
	$("body").on("click", ".categoryProductsExport", function() {
		if(!melisCoreTool.isTableEmpty("categoryProductListTbl")) {
			melisCoreTool.exportData('/melis/MelisCommerce/MelisComCategory/productsExportToCsv?catId='+$(this).data('catid'));
		}
	});
}

// Category Information Status Switch Initialization
window.initCategoryStatus = function(){
	$('#cat_status').bootstrapSwitch();
}

window.initCategoryProducts = function(data, tblSettings) {
	
	// get Category Id from table data
	var catId = $("#" + tblSettings.sTableId ).data("catid");
	
	// Add DataTable Data catId and assign value of CategoryId
	data.catId = catId;
	
	var catLangLocale = $("#categoryTreeView").data('langlocale');
	
	// Add DataTable Data catLangLocale and assign value of catLangLocale
	data.catLangLocale = catLangLocale;
	
	$('#categoryProductListTbl').on( 'row-reorder.dt', function ( e, diff, edit ) {
	    var result = 'Reorder started on row: '+edit.triggerRow.data()[1]+'<br>';

	    for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
	        var rowData = $categoryProductListTbl.row( diff[i].node ).data();
	        console.log('Bogo ', rowData);
	         result += rowData[1]+' updated to be in position '+ diff[i].newData+' (was '+diff[i].oldData+')<br>';
	    }
		
	    if(!$.isEmptyObject(diff)){
	        
	        var dataString = new Array;
	        var prdNodes = new Array;
	        
	        $.each(diff, function(){
	        	prdNodes.push(this.node.id+'-'+this.newPosition);
	        });
	        
	        dataString.push({
				name : "catPrdOrderData",
				value: prdNodes.join()
			});
			
	        dataString = $.param(dataString);
			
		    $.ajax({
			     type        : "POST", 
			     url         : "/melis/MelisCommerce/MelisComCategory/reOrderCategoryProducts",
			     data		: dataString,
			     dataType    : "json",
			     encode		: true
			}).done(function(data) {
				if(!data.success) {
					alert( translations.tr_meliscore_error_message );
				}
			}).fail(function(){
				alert( translations.tr_meliscore_error_message );
			});
		}
	});
}

window.initCategoryProductsImgs = function(){
	// Lightbox Plugin Initialization
    lightbox.option({
	    'resizeDuration': 200,
	    'wrapAround': true
    })
}



$(function(){

	// Modal Save Button
	$("body").on("submit", "form.frmDocAddFile", function(e) {
		var relationId = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-id");
		var relationType = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-type");
		var target = "form#"+relationId+"_"+relationType+"_frmDocUpload";
		var docType = $(target).data("upload-type");
		var formData = new FormData($(target)[0]);
		var saveType = $(target).data("savetype");


		formData.append("docRelationType", relationType);  // doc type if category, product, or variant
		formData.append("relationId", relationId);
		formData.append("docType", docType); // if image or file

		melisCoreTool.pending(".btn");
		$.ajax({
			type : 'POST',
			url  : '/melis/MelisCommerce/MelisComDocument/saveDocument',
			data : formData,
			processData : false,
			cache       : false,
			contentType : false,
			dataType    : 'json',
			xhr: function() {
				var fileXhr = $.ajaxSettings.xhr();
				if(fileXhr.upload){
					fileXhr.upload.addEventListener('progress',progress, false);
				}
				return fileXhr;
			}
		}).done(function(data) {

			if(data && data.success) {
				var docRelationType = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-type");
				var docRelationId   = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-id");
				$("div.modal").modal("hide");
				if(data.type == "file") {
					melisHelper.zoneReload(activeTabId+" #id_meliscommerce_documents_file_attachments_lists", "meliscommerce_documents_file_attachments_lists", {
						docRelationType : docRelationType, docRelationId : docRelationId
					});
				}
				else if(data.type == "image") {
					melisHelper.zoneReload(activeTabId+" #id_meliscommerce_documents_image_lists", "meliscommerce_documents_image_lists", {
						docRelationType : docRelationType, docRelationId : docRelationId
					});
				}
				melisCommerce.setUniqueId(melisCommerce.getUniqueId());
				melisHelper.melisOkNotification(data.textTitle, data.textMessage);
			}
			else {
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
				melisCoreTool.highlightErrors(data.success, data.errors, relationId+"_"+relationType+"_"+"frmDocUpload");
				$("div.progressContent").addClass("hidden");
			}
			melisCoreTool.done(".btn");
			melisCore.flashMessenger();
		}).error(function() {
			melisCoreTool.done(".btn");
			melisHelper.melisKoNotification(translations.tr_meliscommerce_documents_Documents, translations.tr_meliscommerce_documents_save_fail, [], 'closeByButtonOnly');
		}).error(function() {
			$("div.modal").modal("hide");
			melisCoreTool.done(".btn");
			if(docType == "file") {
				melisHelper.zoneReload(activeTabId+" #id_meliscommerce_documents_file_attachments_lists", "meliscommerce_documents_file_attachments_lists");
			}
			else if(docType == "image") {
				melisHelper.zoneReload(activeTabId+" #id_meliscommerce_documents_image_lists", "meliscommerce_documents_image_lists");
			}
			melisCore.flashMessenger();
		});

		e.preventDefault();
	});

	function progress(e) {
		resetProgressBar();
		if(e.lengthComputable){
			var max = e.total;
			var current = e.loaded;
			var percentage = (current * 100)/max;
			$("div.progressContent > div.progress > div.progress-bar").attr("aria-valuenow", percentage);
			$("div.progressContent > div.progress > div.progress-bar").css("width", percentage+"%");

			if(percentage > 100)
			{
				$("div.progressContent").addClass("hidden");
			}
			else {
				$("div.progressContent > div.progress > span.status").html(Math.round(percentage)+"%");
			}
		}
	}

	function resetProgressBar() {
		$("div.progressContent").removeClass("hidden");
		$("div.progressContent > div.progress > div.progress-bar").attr("aria-valuenow", 0);
		$("div.progressContent > div.progress > div.progress-bar").css("width", '0%');
		$("div.progressContent > div.progress > span.status").html("");
	}

	// Deleting File/Image Document Confirmation Dialog
	$("body").on("click", ".deleteFileImageDocument", function(e){
		// Data Attribute on the Selected Element/button
		// Type can be "image" or "file"
		var docId = $(this).data("doc-id");
		var docType = $(this).data("type");

		var docRelationId = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-id");
		var docRelationType = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-type");

		if(docType=='image'){
			var title = translations.tr_meliscommerce_documents_delete_image_title;
			var confirmMsg = translations.tr_meliscommerce_documents_delete_image_msg_confirm;
		}else if(docType=='file'){
			var title = translations.tr_meliscommerce_documents_delete_file_title;
			var confirmMsg = translations.tr_meliscommerce_documents_delete_file_msg_confirm;
		}

		melisCoreTool.confirm(
			translations.tr_meliscommerce_documents_common_label_yes,
			translations.tr_meliscommerce_documents_common_label_no,
			title,
			confirmMsg,
			function() {
				$.ajax({
					type        : 'POST',
					url         : '/melis/MelisCommerce/MelisComDocument/delete',
					data		: {id : docId, docType : docType, formType : docRelationType, uniqueId : docRelationId},
					dataType    : 'json',
					encode		: true,
				}).success(function(data){
					melisCoreTool.pending(".btn");
					if(data && data.success) {
						if(docType == "file") {
							melisHelper.zoneReload(activeTabId+" #id_meliscommerce_documents_file_attachments_lists", "meliscommerce_documents_file_attachments_lists", {docRelationType : docRelationType, docRelationId : docRelationId});
						}
						else if(docType == "image") {
							melisHelper.zoneReload(activeTabId+" #id_meliscommerce_documents_image_lists", "meliscommerce_documents_image_lists", {docRelationType : docRelationType, docRelationId : docRelationId});
						}
						melisCore.flashMessenger();
						melisHelper.melisOkNotification(data.textTitle, data.textMessage);
					}
					else {
						melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
					}
					melisCoreTool.done(".btn");
				}).error(function(){
					$("div.modal").modal("hide");
					melisCoreTool.done(".btn");
					if(docType == "file") {
						melisHelper.zoneReload(activeTabId+" #id_meliscommerce_documents_file_attachments_lists", "meliscommerce_documents_file_attachments_lists");
					}
					else if(docType == "image") {
						melisHelper.zoneReload(activeTabId+" #id_meliscommerce_documents_image_lists", "meliscommerce_documents_image_lists");
					}
					melisCore.flashMessenger();
				});
			});
	});

	$("body").on("click", ".collapseAddImageType", function() {
		var formDiv = $("div.addImageType");
		var form = $("form.frmDocAddFile");
		formDiv.slideToggle();

		$(".collapseAddImageType").find("i[data-class='iconAddImageType']").toggleClass("fa-angle-down");
	});

	$("body").on("click", ".btnDocAddImageType", function() {
		var dataString = $("form.frmDocAddImageType").serialize();
		var relationId = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-id");
		var relationType = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-type");
		var saveType = $("form.frmDocAddImageType").data("upload-type");
		var formData = $(this).parent().prev().parent().find("form.frmDocAddFile").serialize();
		var image = $(this).parent().prev().parent().find("img.imgDocThumbnail").attr("src");
		melisCommerce.postSave('melis/MelisCommerce/MelisComDocument/addImageType?typeUpload='+saveType, dataString, function(data) {
			if(data.success) {
				melisHelper.zoneReload("id_meliscommerce_documents_modal_form", "meliscommerce_documents_modal_form", {typeUpload : "image", saveType : saveType, docRelationId : relationId, docRelationType :relationType});
				melisHelper.melisOkNotification(data.textTitle, data.textMessage);

				// put back all the info
				setTimeout(function() {
					$(".btnDocAddImageType").parent().prev().parent().find("img.imgDocThumbnail").attr("src", image);
					$.each(formData.split('&'), function (index, elem) {
						var vals = elem.split('=');
						$("[name='" + vals[0] + "']").val(vals[1]);
					});
				}, 2000);

			}
			else {
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
				melisCoreTool.highlightErrors(data.success, data.errors, "frmDocAddImageType");
			}
			melisCore.flashMessenger();
		})

	});

	$("body").on("click", ".btnDocAddFileType", function() {
		var dataString = $("form.frmDocAddImageType").serialize();
		var relationId = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-id");
		var relationType = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-type");
		var saveType = $("form.frmDocAddImageType").data("upload-type");
		var formData = $(this).parent().prev().parent().find("form.frmDocAddFile").serialize();

		melisCommerce.postSave('melis/MelisCommerce/MelisComDocument/addFileType?typeUpload='+saveType, dataString, function(data) {
			if(data.success) {
				melisHelper.zoneReload("id_meliscommerce_documents_modal_form", "meliscommerce_documents_modal_form", {typeUpload : "file", saveType : saveType, docRelationId : relationId, docRelationType :relationType});
				melisHelper.melisOkNotification(data.textTitle, data.textMessage);

				// put back all the info
				setTimeout(function() {
					$.each(formData.split('&'), function (index, elem) {
						var vals = elem.split('=');
						$("[name='" + vals[0] + "']").val(vals[1]);
					});
				}, 2000);

			}
			else {
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
				melisCoreTool.highlightErrors(data.success, data.errors, "frmDocAddImageType");
			}
			melisCore.flashMessenger();
		})

	});

	$("body").on("click", ".updateFileDocument", function(){
		var docId = $(this).data("doc-id");
		var docType = $(this).data("type");
		var relationId = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-id");
		var relationType = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-type");
		var zoneId = 'id_meliscommerce_documents_modal_form';
		var melisKey = 'meliscommerce_documents_modal_form';
		var modalUrl = '/melis/MelisCommerce/MelisComDocument/renderDocumentGenericModalContainer';

		melisHelper.createModal(zoneId, melisKey, false, {typeUpload : 'file', docId : docId, saveType : 'updatefile', docRelationId : relationId, docRelationType :relationType},  modalUrl);
	});

	$("body").on("click", ".editImageDocumentModal", function() {
		var typeUpload = "image";
		var zoneId = 'id_meliscommerce_documents_modal_form';
		var melisKey = 'meliscommerce_documents_modal_form';
		var modalUrl = '/melis/MelisCommerce/MelisComDocument/renderDocumentGenericModalContainer';
		var docId = $(this).data("doc-id");
		var relationId = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-id");
		var relationType = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-type");
		melisHelper.createModal(zoneId, melisKey, false, {typeUpload : 'image', docId : docId, saveType : 'editimage', docRelationId : relationId, docRelationType :relationType}, modalUrl);
	});

	// Add File/Image Button, Request Modal Content
	$("body").on("click", ".addFileImageDocument", function(e){
		melisCoreTool.pending(this);
		var typeUpload = $(this).data('type');
		var relationId = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-id");
		var relationType = $("#" + activeTabId + " div.ecom-doc-container").data("doc-relation-type");
		var zoneId = 'id_meliscommerce_documents_modal_form';
		var melisKey = 'meliscommerce_documents_modal_form';
		var modalUrl = '/melis/MelisCommerce/MelisComDocument/renderDocumentGenericModalContainer';
		// requesitng to create modal and display after
		melisHelper.createModal(zoneId, melisKey, false, {typeUpload:typeUpload, docRelationId: relationId, docRelationType :relationType}, modalUrl, function() {
			melisCoreTool.done(".addFileImageDocument");
		});

	});
	
	$("body").on("click", "#btnDocFileAdd", function(e){
			var button = $(this);
			var formId = '#'+button.attr("form");
			$(formId).trigger("submit");
			e.preventDefault();
	});

});

window.initImageDocuments = function(e) {
	// Fix for Mobile
	var custom_event = $.support.touch ? "touchstart" : "click";

	// Initialize Image Container ISOTOPE
	var $container = $(e).find("div.imageDocumentContainer");
	$container.imagesLoaded( function() {
		$container.isotope({
			itemSelector : '.image'
		});
	});
	filters = {};

	// ISOTOPE filter dropdown
	/* Filter text change for Country */
	$("body").on(custom_event, ".filter-div-country .filter-key-values li a", function() {
		var value = $(this).data('text');
		$('.filter-div-country .filter-dropdown').attr('data-value', value);
		$('.filter-div-country span.filter-key').text(value);
	});
	/* Filter text change for File Type */
	$("body").on(custom_event, ".filter-div-file-type .filter-key-values li a", function() {
		var value = $(this).data('text');
		$('.filter-div-file-type .filter-dropdown').attr('data-value', value);
		$('.filter-div-file-type span.filter-key').text(value);
	});

	// Isotope sorting/filter
	$('.documentImageFilter a').on(custom_event,function() {
		$('.documentImageFilter .current').removeClass('current');
		$(this).addClass('current');

		var $optionSet = $(this).parents('.documentImageFilter');
		// change selected class
		$optionSet.find('.selected').removeClass('selected');
		$(this).addClass('selected');
		var group = $optionSet.attr('data-filter-group');
		filters[ group ] = $(this).attr('data-filter-value');
		// convert object into array
		var isoFilters = [];
		for ( var prop in filters ) {
			isoFilters.push( filters[ prop ] )
		}
		var selector = isoFilters.join('');

		$grid = $container.isotope({
			filter: selector,
			animationOptions: {
				duration: 750,
				easing: 'linear',
				queue: false
			}
		});

		// ISOTOPE Event after filter
		$grid.on( 'arrangeComplete', function( event, filteredItems ) {
			// Deselect Images, to remove from the group of available/selected image uaing filter
			$('.viewImageDocument').attr('data-lightbox','deselected-images');
			$.each(filteredItems, function(){
				// updating data-lightbox attribute to make images available after filtering
				// making images as group and sliding images limited only to the group
				$selectedImgElem = $(this.element).find('.viewImageDocument');
				$selectedImgElem.attr('data-lightbox','selected-images');
			});
		});

		// Lightbox Plugin Initialization after ISOTOPE Filter action
		lightbox.option({
			'resizeDuration': 200,
			'wrapAround': true
		});
	});

	// Isotope sorting/filter Responsive
	$('.documentImageFilter a').on(custom_event,function() {
		$('.documentImageFilter .current').removeClass('current');
		$(this).addClass('current');

		var $optionSet = $(this).parents('.documentImageFilter');
		// change selected class
		$optionSet.find('.selected').removeClass('selected');
		$(this).addClass('selected');
		var group = $optionSet.attr('data-filter-group');
		filters[ group ] = $(this).attr('data-filter-value');
		// convert object into array
		var isoFilters = [];
		for ( var prop in filters ) {
			isoFilters.push( filters[ prop ] )
		}
		var selector = isoFilters.join('');

		$grid = $container.isotope({
			filter: selector,
			animationOptions: {
				duration: 750,
				easing: 'linear',
				queue: false
			}
		});

		// ISOTOPE Event after filter
		$grid.on( 'arrangeComplete', function( event, filteredItems ) {
			// Deselect Images, to remove from the group of available/selected image uaing filter
			$('.viewImageDocument').attr('data-lightbox','deselected-images');
			$.each(filteredItems, function(){
				// updating data-lightbox attribute to make images available after filtering
				// making images as group and sliding images limited only to the group
				$selectedImgElem = $(this.element).find('.viewImageDocument');
				$selectedImgElem.attr('data-lightbox','selected-images');
			});
		});

		// Lightbox Plugin Initialization after ISOTOPE Filter action
		lightbox.option({
			'resizeDuration': 200,
			'wrapAround': true
		});
	});

	// Lightbox Plugin Initialization
	lightbox.option({
		'resizeDuration': 200,
		'wrapAround': true
	});
}

window.updateFormId = function() {
	var form = $("form.frmDocAddFile");
	if(melisCommerce.getUniqueId()) {
		form.attr("id", melisCommerce.getUniqueId()+"_frmDocUpload");
	}
}

window.imagePreview = function(id, fileInput) {
	if(fileInput.files && fileInput.files[0]) {
		var reader = new FileReader();
		reader.onload = function(e) {
			$(id).attr('src', e.target.result);
		}
		reader.readAsDataURL(fileInput.files[0]);
	}
}

$(function(){
    // char counter in seo title
	$("body").on("keyup keydown change", "input[name='eseo_meta_title']", { limit: 60 }, seoCharCounter);
	
	// char counter in seo description
	$("body").on("keyup keydown change", "textarea[name='eseo_meta_description']", { limit: 160 }, seoCharCounter);
});

window.preSeoCharCounter = function(){
	$("form.seoForm").each(function(){
		var from = $(this);
		var metaTitle = from.find("input[name='eseo_meta_title']");
		var metaDesc = from.find("textarea[name='eseo_meta_description']");
		metaTitle.trigger('keyup');
		metaDesc.trigger('keyup');
	});
}

// Meta Title and Description Character Counter
window.seoCharCounter = function (event){
	var charLength = $(this).val().length;
	var prevLabel = $(this).prev('label');
	var limit = event.data.limit;
	
	if( prevLabel.find('span').length ){
		
		if(charLength === 0){
			prevLabel.removeClass('limit');
			prevLabel.find('span').remove();
		}else{
			prevLabel.find('span').html('<i class="fa fa-text-width"></i>(' + charLength + ')');
			
			if( charLength > limit ){
				prevLabel.addClass('limit');
				prevLabel.find('span').addClass('limit');
			}else{
				prevLabel.removeClass('limit');
				prevLabel.find('span').removeClass('limit');
			}	
		}
	}else{
		if(charLength !== 0){
			prevLabel.append("<span class='text-counter-indicator'><i class='fa fa-text-width'></i>(" + charLength + ")</span>");
			
			if( charLength > limit ){
				prevLabel.addClass('limit');
				prevLabel.find('span').addClass('limit');
			}
		}
	}
}

var melisCommerceSeo = (function(window) {
	
	function serializeSeo(fromType, typeId, dataString){
		
		// Category Transalations
		var seoDataString = new Array;
		$("form."+fromType+"_"+typeId+"_seo_form").each(function(){
			langId = $(this).data("langid");
			
			seoDataString = $(this).serializeArray();
			
			$.each(seoDataString, function(){
				dataString.push({
					name : fromType+'_seo['+langId+']['+this.name+']',
					value: this.value
				});
			});
		});
		
		return dataString;
	}
	
	return {
		serializeSeo: serializeSeo,
	}
})(window);
window.initProductSwitch = function() {
    setOnOff();
    allLoaded();
}

window.initProductCategoryList = function(productId, langLocale){
    // IE 11 below cross browser js
    if(typeof langLocale === "undefined") langLocale = melisLangId;

    if($("#"+productId+"_productCategoryList").length){

        var target = $("#"+productId+"_productCategoryList");

        if(langLocale !== melisLangId){
            target.data('langlocale', langLocale);
        }

        target.jstree('destroy');

        var dataString = new Array;

        dataString.push({
            name : 'productId',
            value : productId
        });

        dataString.push({
            name : 'idAndNameOnly',
            value : true
        });

        dataString.push({
            name : 'langlocale',
            value : target.data('langlocale')
        });

        var categoriesChecked = new Array;
        $("div#"+productId+"_product_category_area > span.prod-cat-values").each(function(){
            categoriesChecked.push($(this).data('pcat-cat-id'))
        });

        if(categoriesChecked.length){
            dataString.push({
                name : 'categoriesChecked',
                value : categoriesChecked
            });
        }

        dataString = $.param(dataString);

        target
            .on('loading.jstree', function (e, data) {
                melisCommerce.pendingZoneStart("productCategorySearchZone");
            })
            .on('loaded.jstree', function (e, data) {
                melisCommerce.pendingZoneDone("productCategorySearchZone");
            })
            .jstree({
                "types" : {
                    "default" : {
                        "icon" : "fa fa-circle text-success",
                    },
                    "selected": {
                        "select_node": false
                    }
                },
                "core" : {
                    //"multiple": true,
                    "check_callback": true,
                    "animation" : 500,
                    "themes": {
                        "name": "proton",
                        "responsive": false
                    },
                    "dblclick_toggle" : false,
                    "data" : {
                        "cache" : true,
                        "url" : "/melis/MelisCommerce/MelisComCategoryList/getCategoryTreeView?"+dataString,
                    },
                },
                "checkbox": {
                    three_state: false,
                    whole_node : false,
                    tie_selection : false,
                },
                "plugins": [
                    "search",
                    "changed", // Plugins for Change and Click Event
                    "types", // Plugins for Customizing the Nodes
                    "checkbox",
                ]
            });
    }
}

window.initAttribute = function(data) {
    if(melisCommerce.getCurrentProductId() != null) {
        if(!data) {
            populateAttribList(data);
        }
    }
}

window.populateAttribList = function(data) {
    if($("#"+activeTabId).length === 1) {
        melisCommerce.enableAllTabs();
    }
}


window.initProductTextTinyMce = function(productId) {
    var targetEditor = "#"+productId+"_id_meliscommerce_products_page textarea.product-text-mce[data-display='true']:not([id])";

    if($(targetEditor).length){
        $(targetEditor).each(function(index, value){
            var form = $(this);

            var random = Math.random().toString(36).substr(2, 9);
            var targetSelector = random+"_"+productId+"_"+value.name;

            form.attr("id", targetSelector);

            var option = {
                mode : "none",
                height : "400px",
                init_instance_callback  : productTextTinyMCECallback(form, productId),
                plugins : [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table contextmenu paste template',
                    'textcolor',
                ],
                toolbar : 'undo redo | styleselect | bold italic | link image |  alignleft aligncenter alignright alignjustify | forecolor backcolor | code',
            }

            //Initialize TinyMCE editor
            melisTinyMCE.createTinyMCE("tool", "#"+targetSelector, option);
        });
    }else{
        $("#" + productId + "_id_meliscommerce_products_page .notifTinyMcePreloaInfo").fadeOut("slow");
    }
}

window.productTextTinyMCECallback = function(editor, productId){
    $("#"+editor.id).parents("div.form-group").attr("data-tinymce", "true");

    // Removing Alert message for Initializing TinyMCE
    $("#" + productId + "_id_meliscommerce_products_page .notifTinyMcePreloaInfo").fadeOut("slow");
}

window.allLoaded = function() {
    melisCommerce.enableAllTabs();
}
window.reInitProductTextTypeSelect = function(productId) {
    // Remove items that are already existing in the product text
    var productTexts = [];
    var formTextForms = $("#" + productId + "_id_meliscommerce_products_page .product-text-forms > .custom-field-type");
    $.each(formTextForms, function(i, v){
        $.each($(v).find("input#ptt_id"), function(x, y) {
            productTexts.push($(y).val());
        });
    });
    var select = $("form[data-modalform='"+melisCommerce.getCurrentProductId()+"_productTextForm'] select#ptxt_type");
    $.each(productTexts, function(i, v) {
        select.find("option[value='"+v+"']").remove();
    });
}
$(document).ready(function() {

    var body = $("body");

    $body.on("click", ".productCategoryList", function(){
        var btn = $(this);
        var productId = btn.data("productid");

        btn.attr("disabled", true);

        zoneId = 'id_meliscommerce_products_main_tab_categories_modal';
        melisKey = 'meliscommerce_products_main_tab_categories_modal';
        modalUrl = '/melis/MelisCommerce/MelisComProduct/renderProductModal';
        // requesitng to create modal and display after
        melisHelper.createModal(zoneId, melisKey, false, {productId: productId}, modalUrl, function(){
            btn.attr("disabled", false);
        });
    });

    body.on("click", ".addProductCategory", function() {
        var btn = $(this);
        var productId = btn.data("productid");

        var checkedCategories = new Array;
        $.each($("#"+productId+"_productCategoryList").jstree().get_checked(true), function(){
            checkedCategories.push(parseInt(this.id));
        });

        var uncheckedCategories = new Array;
        var addedCategories = new Array;
        $("#"+productId+"_product_category_area span[data-pcat-cat-id]").each(function(){
            var prdCatId = parseInt($(this).data("pcat-cat-id"));
            if(checkedCategories.indexOf(prdCatId) !== -1){
                addedCategories.push(prdCatId);
            }else{
                uncheckedCategories.push(prdCatId);
                $("span.prod-cat-values[data-pcat-cat-id='"+prdCatId+"']").remove();
            }
        });

        $.each($("#"+productId+"_productCategoryList").jstree().get_checked(true), function(){
            var catId = parseInt(this.id);
            if(uncheckedCategories.indexOf(catId) === -1){
                if(addedCategories.indexOf(catId) === -1){
                    var catText = this.text.split(" - ")[1];
                    $.get( "/melis/MelisCommerce/MelisComProduct/getProductCategoryLastOrderNum", {catId : catId, prodId : productId}, function( data ) {
                        $("#"+productId+"_product_category_area").append(
                            '<span class="prod-cat-values" data-pcat-id="'+data.id+'" data-pcat-cat-id="'+catId+'" data-pcat-order="'+data.order+'">' +
                            '<span class="ab-attr">' + catText +'<i class="prdDelCat fa fa-times"></i></span>' +
                            '</span>');
                    });
                }
            }
        });

        if(checkedCategories.length){
            $("p#"+productId+"_no_categories").hide();
        }else{
            $("p#"+productId+"_no_categories").show();
        }

        $("#id_meliscommerce_products_main_tab_categories_modal_container").modal("hide");
    });

    body.on("click", ".product-category-tree-view-lang li a", function() {
        var langText = $(this).text();
        var langLocale = $(this).data('locale');
        var productId = $('.productCategoryLangDropdown').data('productid');

        $('.productCategoryLangDropdown span.filter-key').text(langText);
        initProductCategoryList(productId, langLocale);
    });

    $('body').on("click",".productTextForm .deleteTextInput", function(){
        var text = $(this).parent().attr("data-text-identifier");

        var form  = $(this).parents("form");
        var select = $("form[data-modalform='"+melisCommerce.getCurrentProductId()+"_productTextForm'] select#ptxt_type");

        melisCoreTool.pending(".deleteTextInput");
        melisCoreTool.confirm(
            translations.tr_meliscore_common_yes,
            translations.tr_meliscore_common_no,
            translations.tr_meliscommerce_product_confirm_remove_title,
            translations.tr_meliscommerce_product_confirm_remove_test,
            function() {
                $.each(form, function(i, v) {
                    var text = $(v).find("a[data-text-identifier]").attr('data-text-identifier');
                    var id = $(v).find("input#ptt_id").val();
                    var exists = select.find("option[value='"+id+"']").length;
                    if(!exists) {
                        select.prepend($("<option>", {
                            value: id,
                            text : text
                        }));
                    }
                });
                $("#" + activeTabId + " .productTextForm .deleteTextInput").closest("a[data-text-identifier='"+text+"']").closest("form").remove();
            }
        );
        melisCoreTool.done(".deleteTextInput");

    });

    body.on("click", ".btnProductListEdit", function() {
        var productId   = $(this).parents("tr").attr('id');
        var productName = $(this).parents("tr").find("td span").data("productname");
        var navTabsGroup = "id_meliscommerce_product_list_container";

        melisCommerce.disableAllTabs();
        melisCommerce.openProductPage(productId, productName, navTabsGroup);
        melisCommerce.setUniqueId(productId);
    });

    body.on("click", "#btnAddProduct", function() {
        var navTabsGroup = "id_meliscommerce_product_list_container";

        melisCommerce.openProductPage(0, translations.tr_meliscommerce_products_page_new_product, navTabsGroup);
        melisCommerce.setUniqueId(0);
    });

    body.on("click", "#addProdTextType", function() {
        var productId = $(this).data("productid");
        var dataString = $("form#productTextTypeForm").serialize();
        melisCoreTool.pending("#addProdTextType");
        $.ajax({
            type: 'POST',
            data: dataString,
            url: '/melis/MelisCommerce/MelisComProduct/addProductTextType',
            dataType    : "json",
            encode		: true
        }).success(function(data) {
            if(data.success) {
                melisCoreTool.clearForm("productTextTypeForm");
                melisHelper.zoneReload(melisCommerce.getCurrentProductId()+"_id_meliscommerce_products_page_content_tab_product_text_modal_form_product_type_text", "meliscommerce_products_page_content_tab_product_text_modal_form_product_type_text",  {productId : melisCommerce.getCurrentProductId()});

            }
            else {
                melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
                melisCoreTool.highlightErrors(data.success, data.errors, "productTextTypeForm");
            }
            melisCore.flashMessenger();
            melisCoreTool.done("#addProdTextType");
        });
    });
    body.on('click', '.add-product-text', function() {
        $("div[data-class='addTextFieldNotif']").html("").attr("class", "addTextFieldNotif");
    });

    body.on('click', '.btnAddText', function(){
        var productId = $(this).data("productid");
        var textSelect = $("#" + productId + "_id_meliscommerce_products_page_content_tab_product_text_modal_form").find("select#ptxt_type")
        typeId = textSelect.val();
        var typeText = $("#" + productId + "_id_meliscommerce_products_page_content_tab_product_text_modal_form").find("select#ptxt_type option:selected").text();
        melisCoreTool.pending(this);
        $.ajax({
            type: "GET",
            url : "/melis/MelisCommerce/MelisComProduct/getEmptyProductTextForm?textTypeId=" + typeId + "&text="+typeText,
            dataType : "json",
            encode		: true
        }).success(function(data) {
            var formTextForms = $("#" + productId + "_id_meliscommerce_products_page .product-text-forms > .custom-field-type");
            if(formTextForms.length){
                $("#" + productId + "_id_meliscommerce_products_page .notifTinyMcePreloaInfo").fadeIn("slow");
            }

            $.each(formTextForms, function(i, v){
                var langId =  $(v).data("lang-id");
                var totalLangLength = $("#" + productId + "_id_meliscommerce_products_page .product-text-tab li#languageList").length;
                if($("#" + productId + "_id_meliscommerce_products_page .productTextForm a[data-text='"+typeText+"']").length < totalLangLength) {
                    $(v).find(".custom-field-type-area").append(data.content).data("text-lang-id", langId);
                    $(v).find("form[name='productTextForm']").attr("data-text-lang-id", langId);
                    $(v).find("form[name='productTextForm'] input#ptxt_lang_id").val(langId);
                    initProductTextTinyMce(productId);
                    $("div[data-modalname='genericProductTextModal']").modal('hide');
                    $("div[data-class='addTextFieldNotif']").html("").attr("class", "");
                }else{
                    melisCoreTool.alertDanger("div[data-class='addTextFieldNotif']", "", translations.tr_meliscommerce_product_text_add_exists);
                }
            });
            melisCoreTool.done(".btnAddText");

        });
    });

    body.on("click", ".save_product", function() {
        var productId = $(this).data("productid");
        var forms = $("#" + melisCommerce.getCurrentProductId() +"_id_meliscommerce_products_page form");
        var dataString = [];
        var len = 0;
        var ctr = 0;
        var pageContainer = $(this).closest('.container-level-a');
        var stockAlertForm = $(pageContainer).find('#'+ productId + '_id_meliscommerce_settings_tabs_content_main_details_left form');
        var emails = $(pageContainer).find('.alert-email-values');

        Array.prototype.push.apply(dataString,$(stockAlertForm).serializeArray());

        var sea_id = '';
        var sea_email = '';
        var sea_user_id = '';

        emails.each(function(){

            sea_id = $(this).data('seaid');
            sea_email = $(this).data('alertemail');
            sea_user_id = $(this).data('userid');

            dataString.push({ name : 'recipients['+ctr+'][sea_id]', value : sea_id });
            dataString.push({ name : 'recipients['+ctr+'][sea_email]', value : sea_email });
            dataString.push({ name : 'recipients['+ctr+'][sea_user_id]', value : sea_user_id });

            if (typeof productId !== "undefined") {
                dataString .push({  name : 'recipients['+ctr+'][sea_prd_id]', value : productId});
            }
            ctr++
        });

        ctr = 0;
        forms.each(function(){
            var pre = $(this).attr('name');
            var data = $(this).serializeArray();
            len = data.length;

            for(j=0; j<len; j++ ){
                dataString.push({  name: pre+'['+ctr+']['+data[j].name+']', value : data[j].value});
            }
            ctr++;
        });



        $('#'+productId +'_id_meliscommerce_products_page').find('.make-switch div').each(function(){
            var field = 'product[0]['+$(this).find('input').attr('name')+']';
            var status = $(this).hasClass('switch-on');
            var saveStatus = 0;
            if(status) {
                saveStatus = 1;
            }
            dataString.push({
                name : field,
                value: saveStatus
            })
        });

        dataString.push({
            name: "productId",
            value: productId
        });

        dataString = melisCommerceSeo.serializeSeo('product', productId, dataString);

        dataString.push({ name: "product[0][prd_id]", value: productId });
        ctr = 0;
        $("div#"+melisCommerce.getCurrentProductId()+"_attribute_area > span.attr-values").each(function(){
            dataString .push({ name : 'attributes['+ctr+'][patt_id]', value : $(this).data('patt-id')});
            dataString .push({ name : 'attributes['+ctr+'][patt_attribute_id]', value : $(this).data('patt-attribute-id')});
            ctr++;
        });
        ctr = 0;

        $("div#"+melisCommerce.getCurrentProductId()+"_product_category_area > span.prod-cat-values").each(function(){
            dataString .push({ name : 'categories['+ctr+'][pcat_id]', value : $(this).data('pcat-id')});
            dataString .push({ name : 'categories['+ctr+'][pcat_cat_id]', value : $(this).data('pcat-cat-id')});
            dataString .push({ name : 'categories['+ctr+'][pcat_order]', value : $(this).data('pcat-order')});
            ctr++;
        })
        ctr = 0;

        $("div#"+melisCommerce.getCurrentProductId()+"_deleted_product_category_area > span").each(function(){
            dataString.push({ name : 'delcategories['+ctr+'][pcat_id]', value : $(this).data('pcat-id')});
            ctr++;
        });
        ctr = 0;
        melisCoreTool.pending(".save_product");
        melisCommerce.disableAllTabs();
        $.ajax({
            type: 'POST',
            data: dataString,
            url: '/melis/MelisCommerce/MelisComProduct/saveProduct',
            dataType    : "json",
            encode		: true
        }).success(function(data) {
            if(data.success) {
                var navTabsGroup = "id_meliscommerce_product_list_container";
                var listParent = $('.tab-element[data-id="'+ data.chunk.productId +'_id_meliscommerce_products_page"]').parent();

                melisCommerce.closeCurrentProductPage();
                melisCommerce.openProductPage(data.chunk.productId, data.chunk.prodName, navTabsGroup);
                melisHelper.melisOkNotification( data.textTitle, data.textMessage );
                melisHelper.zoneReload("id_meliscommerce_product_list_content", "meliscommerce_product_list_content");
                melisCommerce.setUniqueId(data.chunk.productId);

                if(listParent.hasClass("has-sub")) {
                    var subNav = listParent.find(".nav-group-dropdown").prop('outerHTML');
                    $('.tab-element[data-id="'+ data.chunk.productId +'_id_meliscommerce_products_page"]').parent().addClass("has-sub").append(subNav);
                }
            }
            else {
                melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
                melisCoreTool.highlightErrors(data.success, data.errors, activeTabId + " form");
            }

            melisCoreTool.done(".save_product");
            melisCore.flashMessenger();
            melisCommerce.enableAllTabs();
        });
    });

    body.on("click", "a[data-meliskey='meliscommerce_products_page_header_save_product_cancel']", function() {
        melisCommerce.closeCurrentProductPage();
    });


    body.on('click','.add-attribute',function() {
        var selAttrVal = $("#"+melisCommerce.getCurrentProductId()+"_prodAttribInput");
        var selAttrValCont = $("#" + activeTabId + " #select2-"+melisCommerce.getCurrentProductId()+"_prodAttribInput-container");
        var select2 = $("#" + activeTabId + " span.select2");
        var attributeLists = $('div#'+activeTabId+' select.dropdown-input'+melisCommerce.getCurrentProductId());
        if(selAttrVal.val()) {
            var selId = selAttrVal.val();
            var selText = $("#"+melisCommerce.getCurrentProductId()+"_prodAttribInput option[value='"+selId+"']").html();
            var selTextEl = $("#"+melisCommerce.getCurrentProductId()+"_prodAttribInput option[value='"+selId+"']");
            if($("#"+melisCommerce.getCurrentProductId()+"_attribute_area").find("span[data-patt-attribute-id='"+selId+"']").length === 0) {
                $("#"+melisCommerce.getCurrentProductId()+"_attribute_area").append('<span class="attr-values" data-patt-attribute-id="'+selId+'"><span class="ab-attr"><span class="attrib-value">'+selText+'</span>'+
                    '<i class="prdDelAttr fa fa-times"></i></span>');
                $("p#" + melisCommerce.getCurrentProductId()+"_no_attributes").hide();

                // manipulate select option items
                var ctr = 0;
                var tmpOptionItems = new Object();
                $.each(selAttrVal.find("option"), function(i,v) {
                    if(selId !== $(v).val()) {
                        tmpOptionItems[ctr] = {id : $(v).val(), value : $(v).html()};
                        ctr++;
                    }
                });
                selTextEl.remove();
                selAttrValCont.html("").attr("title", "");
            }
            selAttrValCont.css("color", "#444");
            select2.css("border", "none");
        }
        else {
            selAttrValCont.css("color", "#e80e05");
            select2.css("border", "1px solid #e80e05");
        }
    });

    body.on("click", ".prdDelAttr", function() {
        var selAttrVal = $("#"+melisCommerce.getCurrentProductId()+"_prodAttribInput");

        var attr = $(this).parent().parent();
        var id = $(this).parents("span.attr-values").attr("data-patt-attribute-id");
        var value = $(this).parent().find("span.attrib-value").html();

        var newOption = $("<option selected></option>");
        newOption.val(id);
        newOption.text(value);
        newOption.val(id); // set the id
        newOption.text(value);
        selAttrVal.append(newOption);
        selAttrVal.val("");
        selAttrVal.select2({
            placeholder: translations.tr_meliscommerce_products_main_tab_attributes_content_label,
            val: '',
        });
        selAttrVal.val("");
        attr.fadeOut("fast").remove();
        var selAttrValCont = $("#" + activeTabId + " #select2-"+melisCommerce.getCurrentProductId()+"_prodAttribInput-container");
        setTimeout(function() {
            //selAttrValCont.html("").attr("title", "");

        },1);
        setTimeout(function() {
            melisCoreTool.pending(".prdDelAttr");
        }, 1000);

        selAttrVal.css("border", "1px solid #e5e5e5");
        if($(".prdDelAttr").length === 0) {
            $("p#" + melisCommerce.getCurrentProductId()+"_no_attributes").show();
        }

    });

    body.on("click", ".prdDelCat", function() {
        var cat = $(this).parent().parent();
        // add in the deleted_product_category
        var delProdCatCont = $("#" + melisCommerce.getCurrentProductId()  + "_deleted_product_category_area");
        delProdCatCont.append('<span data-pcat-id="' + cat.data("pcat-id") + '" data-pcat-cat-id="'+cat.data("pcat-cat-id")+'"></span>');
        cat.fadeOut("slow").remove();
        if($(".prdDelCat").length === 0) {
            $("p#" + melisCommerce.getCurrentProductId()+"_no_categories").show();
        }
    });

    body.on("keyup", "#productCategorySearch", function(e){
        var prdId = $(this).data("productid");
        var searchString = $(this).val();
        $("#"+prdId+"_productCategoryList").jstree('search', searchString);
    });

    // Clear Input Search
    body.on("click", "#clearPrdCatSearchInputBtn", function(e){
        var prdId = $(this).data("productid");
        categoryOpeningItemFlag = false;
        var prdCatTree = $("#"+prdId+"_productCategoryList");
        $("#productCategorySearch").val("");
        prdCatTree.jstree('search', '');
    });

    // Toggle Buttons for Category Tree View
    body.on("click", "#expandPrdCatTreeViewBtn", function(e){
        var prdId = $(this).data("productid");
        var prdCatTree = $("#"+prdId+"_productCategoryList");
        prdCatTree.jstree("open_all");
    });

    body.on("click", "#collapsePrdCatTreeViewBtn", function(e){
        var prdId = $(this).data("productid");
        var prdCatTree = $("#"+prdId+"_productCategoryList");
        prdCatTree.jstree("close_all");
    });

    // Refrech Category Tree View
    body.on("click", "#refreshPrdCatTreeView", function(e){
        var prdId = $(this).data("productid");
        var prdCatTree = $("#"+prdId+"_productCategoryList");
        prdCatTree.jstree(true).refresh("forget_state", true);
        prdCatTree.jstree('search', '');
        $("#productCategorySearch").val("");
    });

    body.on("click", ".btnProductListDelete", function() {

        var productId = $(this).closest("tr").attr("id");
        melisCoreTool.pending(".btnProductListDelete");
        melisCoreTool.confirm(
            translations.tr_meliscommerce_documents_common_label_yes,
            translations.tr_meliscommerce_documents_common_label_no,
            translations.tr_meliscommerce_product_remove_title,
            translations.tr_meliscommerce_product_remove_confirm,
            function() {
                $.ajax({
                    type        : 'POST',
                    url         : '/melis/MelisCommerce/MelisComProduct/delete',
                    data		: {productId : productId},
                    dataType    : 'json',
                    encode		: true,
                }).success(function(data){
                    if(data.success) {
                        $("#" + activeTabId + " .melis-refreshTable").trigger("click");
                        melisHelper.tabClose(productId+ "_id_meliscommerce_products_page");
                        melisCore.flashMessenger();
                        melisHelper.melisOkNotification( data.textTitle, data.textMessage );
                    }
                });
            });
        melisCoreTool.done(".btnProductListDelete");
    });

    body.on("click", ".toggleformCreateTextTypeContainer", function() {
        $("div.formCreateTextTypeContainer").slideToggle();
    });

    $("body").on("mouseenter mouseout", ".toolTipHoverEvent", function(e) {
        $(".thClassColId").attr("style", "");
        var productId = $(this).data("productid");
        var loaderText = '<div class="qtipLoader"><hr/><span class="text-center col-lg-12">Loading...</span><br/></div>';
        $.each($("table#productTable"+productId + " thead").nextAll(), function(i,v) {
            $(v).remove();
        });
        $(loaderText).insertAfter("table#productTable"+productId + " thead");
        var xhr = $.ajax({
            type        : 'POST',
            url         : 'melis/MelisCommerce/MelisComProductList/getToolTip',
            data		: {productId : productId},
            dataType    : 'json',
            encode		: true,
        }).success(function(data){
            $("div.qtipLoader").remove();
            if(data.content.length === 0) {
                $('<div class="qtipLoader"><hr/><span class="text-center col-lg-12">'+translations.tr_meliscommerce_product_tooltip_no_variants+'</span><br/></div>').insertAfter("table.qtipTable thead");
            }
            else {
                // make sure tbody is clear
                $.each($("table#productTable"+productId + " thead").nextAll(), function(i,v) {
                    $(v).remove();
                });
                $.each(data.content.reverse(), function(i ,v) {
                    $(v).insertAfter("table#productTable"+productId + " thead")
                });

            }

        });
        if(e.type === "mouseout") {
            xhr.abort();
        }
    });

    $("body").on("click",".add-product-text", function(){
        melisHelper.zoneReload(melisCommerce.getCurrentProductId()+"_id_meliscommerce_products_page_content_tab_product_text_modal_form_product_type_text", "meliscommerce_products_page_content_tab_product_text_modal_form_product_type_text", {productId : melisCommerce.getCurrentProductId()});
        reInitProductTextTypeSelect(melisCommerce.getCurrentProductId());
    });

    function getSelAttributes() {
        var strInt = [];
        var attribs = $("div#"+melisCommerce.getCurrentProductId()+"_attribute_area > span.attr-values");
        $.each(attribs, function(i, v) {
            strInt.push($(v).data("attr-id"));
        });

        return strInt;

    }

    function getSelCategories() {
        var ctr = 0;
        var strInt = [];
        var attribs = $("div#"+melisCommerce.getCurrentProductId()+"_product_category_area > span.prod-cat-values");
        $.each(attribs, function(i, v) {
            strInt.push({name: 'categories['+ctr+'][pcat_cat_id]', value: $(v).data("pcat-id")});
        });

        return strInt;
    }
});  


$(function(){
    // char counter in seo title
	$("body").on("keyup keydown change", "input[name='eseo_meta_title']", { limit: 60 }, seoCharCounter);
	
	// char counter in seo description
	$("body").on("keyup keydown change", "textarea[name='eseo_meta_description']", { limit: 160 }, seoCharCounter);
});

window.preSeoCharCounter = function(){
	$("form.seoForm").each(function(){
		var from = $(this);
		var metaTitle = from.find("input[name='eseo_meta_title']");
		var metaDesc = from.find("textarea[name='eseo_meta_description']");
		metaTitle.trigger('keyup');
		metaDesc.trigger('keyup');
	});
}

// Meta Title and Description Character Counter
window.seoCharCounter = function (event){
	var charLength = $(this).val().length;
	var prevLabel = $(this).prev('label');
	var limit = event.data.limit;
	
	if( prevLabel.find('span').length ){
		
		if(charLength === 0){
			prevLabel.removeClass('limit');
			prevLabel.find('span').remove();
		}else{
			prevLabel.find('span').html('<i class="fa fa-text-width"></i>(' + charLength + ')');
			
			if( charLength > limit ){
				prevLabel.addClass('limit');
				prevLabel.find('span').addClass('limit');
			}else{
				prevLabel.removeClass('limit');
				prevLabel.find('span').removeClass('limit');
			}	
		}
	}else{
		if(charLength !== 0){
			prevLabel.append("<span class='text-counter-indicator'><i class='fa fa-text-width'></i>(" + charLength + ")</span>");
			
			if( charLength > limit ){
				prevLabel.addClass('limit');
				prevLabel.find('span').addClass('limit');
			}
		}
	}
}

var melisCommerceSeo = (function(window) {
	
	function serializeSeo(fromType, typeId, dataString){
		
		// Category Transalations
		var seoDataString = new Array;
		$("form."+fromType+"_"+typeId+"_seo_form").each(function(){
			langId = $(this).data("langid");
			
			seoDataString = $(this).serializeArray();
			
			$.each(seoDataString, function(){
				dataString.push({
					name : fromType+'_seo['+langId+']['+this.name+']',
					value: this.value
				});
			});
		});
		
		return dataString;
	}
	
	return {
		serializeSeo: serializeSeo,
	}
})(window);

$(document).ready(function() {

    var body = $("body");

    body.on("click", ".editvariant", function(){
        var variantId   = $(this).closest('tr').attr('id');
        var variantName = $(this).closest('tr').find("td a").data("variantname");
        var productId   = $(this).closest('.container-level-a').attr('id').replace(/[^0-9]/g,'');
        var prodTabId   = productId+"_id_meliscommerce_products_page";
        melisCommerce.disableAllTabs();
        melisHelper.tabOpen(variantName, 'icon-tag-2', variantId+'_id_meliscommerce_variants_page', 'meliscommerce_variants_page', { variantId : variantId, productId : productId}, prodTabId);
        melisCommerce.setUniqueId(variantId);
        melisCommerce.enableAllTabs();

    });

    body.on("click", ".add-variant", function(){
        var productId   = $(this).closest('.container-level-a').attr('id').replace(/[^0-9]/g,'');
        var prodTabId   = productId+"_id_meliscommerce_products_page";
        melisCoreTool.processing();
        melisHelper.tabOpen(translations.tr_meliscommerce_variant_main_information_sku_new, 'icon-tag-2', 'id_meliscommerce_variants_page', 'meliscommerce_variants_page', { productId : productId, page : 'newvar'}, prodTabId);
        melisCommerce.setUniqueId(0);
        melisCoreTool.processDone();
    });

    body.on("click", ".save-add-variant", function(){
        var variantPageId   = $(this).closest('.container-level-a').attr('id');
    });

    body.on("click", ".country-price-tab li a", function(){
        var textCountry = $(this).data('country');
        $('.country-price-label').text(textCountry + ' ' + translations.tr_meliscommerce_variant_tab_prices_pricing);
    });

    body.on("click", ".country-stock-tab li a", function(){
        var textCountry = $(this).data('country');
        if(melisLangId == 'fr_FR'){
            $('.country-stock-label').text(translations.tr_meliscommerce_variant_tab_stocks_header + ' ' + textCountry );
        }else{
            $('.country-stock-label').text(textCountry + ' ' + translations.tr_meliscommerce_variant_tab_stocks_header);
        }
    });

    $('body').on('click', '.productvariant-refresh', function(){
        var prodId = melisCommerce.getCurrentProductId();
        melisHelper.zoneReload(prodId+"_id_meliscommerce_products_page_content_tab_variant_content_container", "meliscommerce_products_page_content_tab_variant_content_container", {productId : prodId});
    });

    body.on("click", ".deletevariant", function(){
        var del = this;
        var variantId   = $(del).closest('tr').attr('id');
        var url = 'melis/MelisCommerce/MelisComVariant/deleteVariant';
        var dataString = [];
        dataString.push({
            name : 'var_id',
            value: variantId,
        });
        melisCoreTool.pending(del);
        melisCoreTool.confirm(
            translations.tr_meliscommerce_documents_common_label_yes,
            translations.tr_meliscommerce_documents_common_label_no,
            translations.tr_meliscommerce_variants_delete_title,
            translations.tr_meliscommerce_variants_delete_confirm,
            function(){
                melisCommerce.postSave(url, dataString, function(data){
                    if(data.success){
                        melisHelper.melisOkNotification( data.textTitle, data.textMessage );
                        melisHelper.zoneReload(melisCommerce.getCurrentProductId()+"_id_meliscommerce_products_page_content_tab_variant_content_container", "meliscommerce_products_page_content_tab_variant_content_container", {productId : melisCommerce.getCurrentProductId()});
                        melisHelper.tabClose(  variantId + "_id_meliscommerce_variants_page");
                    }else{
                        melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
                    }
                    melisCore.flashMessenger();
                }, function(data){
                    console.log(data);
                })

            });
        melisCoreTool.done(del);

    });

    body.on("click", ".save-variant", function(){
        melisCoreTool.pending(".save-variant");
        var url = 'melis/MelisCommerce/MelisComVariant/saveVariant';
        var prodId = $(this).closest('.container-level-a').data("prodid");
        var prodTabId   = prodId+"_id_meliscommerce_products_page";
        var id = $(this).closest('.container-level-a').attr('id');
        var varId = isNaN(parseInt(id, 10)) ? '' : parseInt(id, 10);
        var fixVarId = isNaN(parseInt(id, 10)) ? '' : parseInt(id, 10)+'_';
        var forms = $(this).closest('.container-level-a').find('form');
        var dataString = [];
        var len;
        var ctr;
        ctr = 0;
        forms.each(function(){
            var pre = $(this).attr('name');
            var data = $(this).serializeArray();
            len = data.length;
            for(j=0; j<len; j++ ){
                dataString.push({  name: pre+'['+ctr+']['+data[j].name+']', value : data[j].value});
            }
            ctr++;
        });

        dataString.push({
            name: "variantId",
            value: varId
        });

        dataString = melisCommerceSeo.serializeSeo('variant', varId, dataString);

        dataString.push({name : 'variant[0][var_id]', value : varId}, {name : 'variant[0][var_prd_id]', value : prodId})

        $('#'+id).find('.make-switch div').each(function(){
            var field = 'variant[0]['+$(this).find('input').attr('name')+']';
            var status = $(this).hasClass('switch-on');
            var saveStatus = 0;
            if(status) {
                saveStatus = 1;
            }
            dataString.push({
                name : field,
                value: saveStatus
            })
        });

        melisCommerce.postSave(url, dataString, function(data){
            if(data.success){
                melisHelper.tabClose(  fixVarId + "id_meliscommerce_variants_page");
                melisHelper.tabOpen(data.chunk.varSku, 'icon-tag-2', data.chunk.variantId+'_id_meliscommerce_variants_page', 'meliscommerce_variants_page', { variantId : data.chunk.variantId, productId : prodId}, prodTabId);
                melisHelper.melisOkNotification( data.textTitle, data.textMessage );
                melisHelper.zoneReload(prodId+"_id_meliscommerce_products_page_content_tab_variant_content_container", "meliscommerce_products_page_content_tab_variant_content_container", {productId : prodId});
                melisCommerce.setUniqueId(data.chunk.variantId);
                melisCore.flashMessenger();
            }else{
                melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
                var target = 'id_meliscommerce_variant_content';
                if(data.chunk.variantId){
                    target = data.chunk.variantId + 'id_meliscommerce_variant_content'
                }

                melisCoreTool.highlightErrors(0, data.errors, target);
            }
            melisCoreTool.done(".save-variant");
            melisCore.flashMessenger();
        }, function(data){
            console.log(data);
        });

    })

    $("body").on("mouseenter mouseleave", ".toolTipVarHoverEvent", function(e) {

        var variantId = $(this).data("variantid");
        var productId   = $(this).closest('.container-level-a').attr('id').replace(/[^0-9]/g,'');
        var loaderText = '<div class="qtipLoader"><hr/><span class="text-center col-lg-12">Loading...</span><br/></div>';
        $.each($("table#variantTable"+variantId + " thead").nextAll(), function(i,v) {
            $(v).remove();
        });
        $(loaderText).insertAfter("table#variantTable"+variantId + " thead");
        var xhr = $.ajax({
            type        : 'POST',
            url         : 'melis/MelisCommerce/MelisComProductList/getToolTip',
            data		: {variantId : variantId, productId : productId},
            dataType    : 'json',
            encode		: true,
        }).success(function(data){
            $("div.qtipLoader").remove();
            if(data.content.length === 0) {
                $('<div class="qtipLoader"><hr/><span class="text-center col-lg-12">'+translations.tr_meliscommerce_product_tooltip_no_variants+'</span><br/></div>').insertAfter("table.qtipTable thead");
            }
            else {
                // make sure tbody is clear
                $.each($("table#variantTable"+variantId + " thead").nextAll(), function(i,v) {
                    $(v).remove();
                });
                $.each(data.content.reverse(), function(i ,v) {
                    $(v).insertAfter("table#variantTable"+variantId + " thead");
                });

            }

        });
        if(e.type === "mouseout") {
            xhr.abort();
        }
    });

});
//variant list table in product page
window.initProductVariant = function(data, tblSettings) {
    var prodId = $("#" + tblSettings.sTableId ).data("prodid");
    data.prodId = prodId
}
window.variantLoaded = function() {
    var productId = $(".tab-pane#" + activeTabId).data("prodid");
    var prodTabId   = productId+"_id_meliscommerce_products_page";
    melisCommerce.enableTab(prodTabId);
}
$(function(){
    // char counter in seo title
	$("body").on("keyup keydown change", "input[name='eseo_meta_title']", { limit: 60 }, seoCharCounter);
	
	// char counter in seo description
	$("body").on("keyup keydown change", "textarea[name='eseo_meta_description']", { limit: 160 }, seoCharCounter);
});

window.preSeoCharCounter = function(){
	$("form.seoForm").each(function(){
		var from = $(this);
		var metaTitle = from.find("input[name='eseo_meta_title']");
		var metaDesc = from.find("textarea[name='eseo_meta_description']");
		metaTitle.trigger('keyup');
		metaDesc.trigger('keyup');
	});
}

// Meta Title and Description Character Counter
window.seoCharCounter = function (event){
	var charLength = $(this).val().length;
	var prevLabel = $(this).prev('label');
	var limit = event.data.limit;
	
	if( prevLabel.find('span').length ){
		
		if(charLength === 0){
			prevLabel.removeClass('limit');
			prevLabel.find('span').remove();
		}else{
			prevLabel.find('span').html('<i class="fa fa-text-width"></i>(' + charLength + ')');
			
			if( charLength > limit ){
				prevLabel.addClass('limit');
				prevLabel.find('span').addClass('limit');
			}else{
				prevLabel.removeClass('limit');
				prevLabel.find('span').removeClass('limit');
			}	
		}
	}else{
		if(charLength !== 0){
			prevLabel.append("<span class='text-counter-indicator'><i class='fa fa-text-width'></i>(" + charLength + ")</span>");
			
			if( charLength > limit ){
				prevLabel.addClass('limit');
				prevLabel.find('span').addClass('limit');
			}
		}
	}
}

var melisCommerceSeo = (function(window) {
	
	function serializeSeo(fromType, typeId, dataString){
		
		// Category Transalations
		var seoDataString = new Array;
		$("form."+fromType+"_"+typeId+"_seo_form").each(function(){
			langId = $(this).data("langid");
			
			seoDataString = $(this).serializeArray();
			
			$.each(seoDataString, function(){
				dataString.push({
					name : fromType+'_seo['+langId+']['+this.name+']',
					value: this.value
				});
			});
		});
		
		return dataString;
	}
	
	return {
		serializeSeo: serializeSeo,
	}
})(window);
$(function(){

    $("body").on("click", ".addNewClient", function(){
        melisHelper.tabOpen(translations.tr_meliscommerce_clients_add_client, "fa fa-user-plus", "0_id_meliscommerce_client_page", "meliscommerce_client_page", {clientId:0}, "id_meliscommerce_clients_list_page");
    });

    //removes modal elements when clicking outside
    $("body").on("click", function (e) {
        if ($(e.target).hasClass('modal')) {
            $('#id_meliscommerce_client_modal_contact_form_container').modal('hide');
            $('#id_meliscommerce_client_modal_contact_address_form_container').modal('hide');
            $('#id_meliscommerce_client_modal_address_form_container').modal('hide');
        }
    });

    $("body").on("click", ".viewCleintInfo", function(){
        var clientId = $(this).parents("tr").attr("id");


        dataString = new Array;

        dataString.push({
            name: 'clientId',
            value: clientId
        })
        $.ajax({
            type        : "POST",
            url         : "/melis/MelisCommerce/MelisComClient/getClientContactName",
            data		: dataString,
            dataType    : "json",
            encode		: true,
            cache		: false,
        }).done(function(data) {

            $("#saveClientContact").button("reset");

            if(data.success){
                var navTabsGroup = "id_meliscommerce_clients_list_page";

                melisHelper.tabOpen(data.clientContactName, "fa fa-user", clientId+"_id_meliscommerce_client_page", "meliscommerce_client_page", {clientId:clientId}, navTabsGroup);
            }else{
                melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
            }

        }).fail(function(){

            $("#saveClientContact").button("reset");

            alert( translations.tr_meliscore_error_message);
        });

    });

    $("body").on("click", ".addNewContact", function(){
        var clientId = $(this).data('clientid');
        $(".addNewContact").button("loading");
        // initialation of local variable
        zoneId = 'id_meliscommerce_client_modal_contact_form';
        melisKey = 'meliscommerce_client_modal_contact_form';
        modalUrl = '/melis/MelisCommerce/MelisComClient/renderClientModal';
        // requesitng to create modal and display after
        melisHelper.createModal(zoneId, melisKey, false, {clientId: clientId}, modalUrl, function(){
            $(".addNewContact").button("reset");
        });
    });

    $("body").on("click", "#saveClientContact", function(){
        var clientId = $(this).data('clientid');

        // serialize the new array and send it to server
        dataString = $("#melisCommerceClientContactFormModal").serializeArray();

        dataString.push({
            name : 'clientId',
            value : clientId,
        });

        dataString = $.param(dataString);

        $("#saveClientContact").button("loading");

        $.ajax({
            type        : "POST",
            url         : "/melis/MelisCommerce/MelisComClient/addClientContact",
            data		: dataString,
            dataType    : "json",
            encode		: true,
            cache		: false,
        }).done(function(data) {

            $("#saveClientContact").button("reset");

            if(data.success){
                $("#"+clientId+"_client_contact_tab_nav").append(data.clientContactDom.tabNav);
                $("#"+clientId+"_client_contact_tab_content").append(data.clientContactDom.tabContent);
                $('#nav_'+data.clientContactDom.tabId).tab('show');
                $("#id_meliscommerce_client_modal_contact_form_container").modal("hide");
            }else{
                melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
                melisCoreTool.highlightErrors(data.success, data.errors, "melisCommerceClientContactFormModal");
            }

        }).fail(function(){
            $("#saveClientContact").button("reset");
            alert( translations.tr_meliscore_error_message);
        });
    });

    $("body").on("click", ".addNewContactAddress", function(){
        var clientId = $(this).data("clientid");
        var tabId = $(this).data("tabid");
        $(".addNewContactAddress").button("loading");

        // initialation of local variable
        zoneId = 'id_meliscommerce_client_modal_contact_address_form';
        melisKey = 'meliscommerce_client_modal_contact_address_form';
        modalUrl = '/melis/MelisCommerce/MelisComClient/renderClientModal';
        // requesitng to create modal and display after
        melisHelper.createModal(zoneId, melisKey, false, {clientId: clientId, tabId: tabId}, modalUrl, function(){
            $(".addNewContactAddress").button("reset");
        });
    });

    $("body").on("click", "#saveClientContactAddress", function(){
        var clientId = $(this).data("clientid");
        var tabId = $(this).data("tabid");

        // serialize the new array and send it to server
        dataString = $("#melisCommerceClientContactAddressFormModal").serializeArray();

        dataString.push({
            name : 'clientId',
            value : clientId,
        });

        dataString.push({
            name : 'tabId',
            value : tabId,
        });

        dataString = $.param(dataString);

        $("#saveClientContactAddress").button("loading");

        $.ajax({
            type        : "POST",
            url         : "/melis/MelisCommerce/MelisComClient/addClientContactAddress",
            data		: dataString,
            dataType    : "json",
            encode		: true,
            cache		: false,
        }).done(function(data) {

            $("#saveClientContactAddress").button("reset");

            if(data.success){
                $("#"+tabId+"_contact_address").append(data.clientContactAddressDom.accordionContent);
                $('#nav_'+data.clientContactAddressDom.contactAddressId).click();
                $("#id_meliscommerce_client_modal_contact_address_form_container").modal("hide");
            }else{
                melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
                melisCoreTool.highlightErrors(data.success, data.errors, "melisCommerceClientContactAddressFormModal");
            }

        }).fail(function(){
            $("#saveClientContactAddress").button("reset");
            alert( translations.tr_meliscore_error_message);
        });
    });

    $("body").on("click", ".addNewAddress", function(){
        var clientId = $(this).data("clientid");
        $(".addNewAddress").button("loading");

        // initialation of local variable
        zoneId = 'id_meliscommerce_client_modal_address_form';
        melisKey = 'meliscommerce_client_modal_address_form';
        modalUrl = '/melis/MelisCommerce/MelisComClient/renderClientModal';
        // requesitng to create modal and display after
        melisHelper.createModal(zoneId, melisKey, false, {clientId: clientId}, modalUrl, function(){
            $(".addNewAddress").button("reset");
        });
    });

    $("body").on("click", "#saveClientAddress", function(){
        var clientId = $(this).data("clientid");

        // serialize the new array and send it to server
        dataString = $("#melisCommerceClientAddressFormModal").serializeArray();

        dataString.push({
            name : 'clientId',
            value : clientId,
        });

        dataString = $.param(dataString);

        $("#saveClientAddress").button("loading");

        $.ajax({
            type        : "POST",
            url         : "/melis/MelisCommerce/MelisComClient/addClientAddress",
            data		: dataString,
            dataType    : "json",
            encode		: true,
        }).done(function(data) {

            $("#saveClientAddress").button("reset");

            if(data.success){
                $("#"+clientId+"_client_address_tab_nav").append(data.clientAddressDom.tabNav)
                $("#"+clientId+"_client_address_tab_content").append(data.clientAddressDom.tabContent)
                $("#nav_add_"+data.clientAddressDom.addressId).tab("show");
                $("#id_meliscommerce_client_modal_address_form_container").modal("hide");
            }else{
                melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
                melisCoreTool.highlightErrors(data.success, data.errors, "melisCommerceClientAddressFormModal");
            }

        }).fail(function(){
            $("#saveClientAddress").button("reset");
            alert( translations.tr_meliscore_error_message);
        });
    });

    $("body").on("click", ".deleteClientCotactAddress", function(){
        var addressId = $(this).data("addressid");
        var addressAccordionId = $(this).data("addressaccordionid");
        var isNewAdded = $(this).data("isnewadded");

        // deletion confirmation
        melisCoreTool.confirm(
            translations.tr_meliscommerce_clients_common_label_yes,
            translations.tr_meliscommerce_clients_common_label_no,
            translations.tr_meliscommerce_client_delete_address,
            translations.tr_meliscommerce_client_delete_address_confirm_msg,
            function() {
                // Checking if Contact Address is not new entry
                if(isNewAdded == 0){
                    // Address id added to Form Deleted Client Address
                    $("#deletedClientAddress").append("<input name='deletedaddresses[]' value='"+addressId+"'>");
                }
                // Removing Address Content from the list of Contact Addresses
                $("#"+addressAccordionId+"_contact_address_content").remove();
            });
    });

    $("body").on("click", ".deleteClientAddress", function(){
        var addressId = $(this).data("addressid");
        var tabClass = $(this).data("tabclass");
        var isNewAdded = $(this).data("isnewadded");

        // deletion confirmation
        melisCoreTool.confirm(
            translations.tr_meliscommerce_clients_common_label_yes,
            translations.tr_meliscommerce_clients_common_label_no,
            translations.tr_meliscommerce_client_delete_address,
            translations.tr_meliscommerce_client_delete_address_confirm_msg,
            function() {
                // Checking if Contact Address is not new entry
                if(isNewAdded == 0){
                    // Address id added to Form Deleted Client Address
                    $("#deletedClientAddress").append("<input name='deletedaddresses[]' value='"+addressId+"'>");
                }

                if($("."+tabClass+":first").hasClass("active")){

                    if($("."+tabClass+":first").next().find("a").length){
                        // Activate next tab after removing current active tab
                        $("."+tabClass+":first").next().find("a").tab("show");
                    }else if($("."+tabClass+":first").prev().find("a")){
                        // Activate previous tab after removing current active tab
                        $("."+tabClass+":first").prev().find("a").tab("show");
                    }
                }
                // Removing Address Content from the list of Contact Addresses
                $("."+tabClass).remove();
            });
    });

    $("body").on("click", ".clientMainContact", function(){
        var clientId = $(this).data("clientid");
        var tabId = $(this).data("tabid");

        if($(this).hasClass("fa-star-o")){
            // Set other OFF
            $("#"+clientId+"_client_contact_tab_content .clientMainContact").removeClass("fa-star");
            $("#"+clientId+"_client_contact_tab_content .clientMainContact").addClass("fa-star-o");
            // Activate current star
            $(this).addClass("fa-star");
            $(this).removeClass("fa-star-o");
            // Set other form main contact input to zero (0)
            $("#"+clientId+"_client_contact_tab_content").find('form input[name="cper_is_main_person"]').val(0);
            // Set current switch main contact input to one (1)
            $("#"+tabId).find("form input[name='cper_is_main_person']").val(1);
        }else{
            // Deactivate current star
            $(this).removeClass("fa-star");
            $(this).addClass("fa-star-o");
            $("#"+tabId).find("form input[name='cper_is_main_person']").val(0);
        }
    });

    $("body").on("switch-change", ".clientContactStatus", function(){
        var clientId = $(this).data("clientid");
        var tabId = $(this).data("tabid");

        if($(this).find(".switch-animate").hasClass("switch-on")){
            // Set back On the current switch
            $(this).find(".switch-animate").removeClass('switch-off').addClass("switch-on");
            // Set current switch input to one (1)
            $("#"+tabId).find("form input[name='cper_status']").val(1);
        }else{
            $("#"+tabId).find("form input[name='cper_status']").val(0);
        }
    });

    $("body").on("switch-change", ".modalClientContactStatus", function(){
        var clientId = $(this).data("clientid");
        if($(this).find(".switch-animate").hasClass("switch-on")){
            // Set back On the current switch
            $(this).find(".switch-animate").removeClass('switch-off').addClass("switch-on");
            // Set current switch input to one (1)
            $("#melisCommerceClientContactFormModal").find("input[name='cper_status']").val(1);
        }else{
            $("#melisCommerceClientContactFormModal").find("input[name='cper_status']").val(0);
        }
    });

    $("body").on("click", ".deleteClientContactAddress", function(){
        var tabClass = $(this).data("tabclass");

        // deletion confirmation
        melisCoreTool.confirm(
            translations.tr_meliscommerce_clients_common_label_yes,
            translations.tr_meliscommerce_clients_common_label_no,
            translations.tr_meliscommerce_client_delete_new_contact,
            translations.tr_meliscommerce_client_delete_new_contact_confirm_msg,
            function() {

                if($("."+tabClass+"_client_contact:first").hasClass("active")){

                    if($("."+tabClass+"_client_contact:first").next().find("a").length){
                        // Activate next tab after removing current active tab
                        $("."+tabClass+"_client_contact:first").next().find("a").tab("show");
                    }else if($("."+tabClass+"_client_contact:first").prev().find("a")){
                        // Activate previous tab after removing current active tab
                        $("."+tabClass+"_client_contact:first").prev().find("a").tab("show");
                    }
                }
                // Removing Address Content from the list of Contact Addresses
                $("."+tabClass+"_client_contact").remove();
            });
    });

    $("body").on("click", ".saveClientInfo", function(){
        var clientId = $(this).data("clientid");

        var dataString = new Array();

        dataString = $("#"+clientId+"_id_meliscommerce_client_page form").not(".clientContactForm, .clientContactAddressForm, .clientAddressForm").serializeArray();


        // Serializing Client Contact Data
        $("#"+clientId+"_id_meliscommerce_client_page form.clientContactForm").addClass(clientId+"_clientContactForm");
        $("."+clientId+"_clientContactForm").each(function(){
            var tabId = $(this).data("tabid");
            var clientContactForm = $(this).serializeArray();
            $.each(clientContactForm, function(){
                dataString.push({
                    name: 'clientContacts['+tabId+']['+this.name+']',
                    value: this.value
                });
            });

            // Serializing Client Contact Adddresses Data
            $("#"+tabId+"_contact_address form").each(function(){
                var contactAddressId = $(this).data("contactaddressid");
                var clientContactAddressForm = $(this).serializeArray();

                $.each(clientContactAddressForm, function(){
                    dataString.push({
                        name: 'clientContacts['+tabId+'][contact_address]['+contactAddressId+']['+this.name+']',
                        value: this.value
                    });
                });
            });
        });

        // Serializing Client Addresses Data
        $("#"+clientId+"_id_meliscommerce_client_page form.clientAddressForm").addClass(clientId+"_clientAddressForm");
        $("."+clientId+"_clientAddressForm").each(function(){
            var addressId = $(this).data("addressid");
            var clientAddressFrom = $(this).serializeArray();
            $.each(clientAddressFrom, function(){
                dataString.push({
                    name: 'clientAddresses['+addressId+']['+this.name+']',
                    value: this.value
                });
            });
        });

        dataString.push({
            name : 'clientId',
            value : clientId
        });

        var clientStatus = 0;
        if($('#'+clientId+'_cli_status input').is(':checked')){
            clientStatus = 1;
        }

        dataString.push({
            name : "cli_status",
            value: clientStatus
        });

        $(this).button("loading");

        $.ajax({
            type        : "POST",
            url         : "/melis/MelisCommerce/MelisComClient/saveClient",
            data		: dataString,
            dataType    : "json",
            encode		: true,
        }).done(function(data) {
            $(".saveClientInfo").button("reset");

            if(data.success){
                var navTabsGroup = "id_meliscommerce_clients_list_page";
                melisHelper.tabClose(clientId+"_id_meliscommerce_client_page");
                melisHelper.melisOkNotification(data.textTitle, data.textMessage);
                melisHelper.tabOpen(data.clientContactName, "fa fa-user", data.clientId+"_id_meliscommerce_client_page", "meliscommerce_client_page", {clientId:data.clientId}, navTabsGroup);
                melisHelper.zoneReload('id_meliscommerce_clients_list_content', 'meliscommerce_clients_list_content');
            }else{
                melisClientKoNotification(data.textTitle, data.textMessage, data.errors);
                clientHighlightErrors(data.success, data.errors,  activeTabId+" form");
            }

            melisCore.flashMessenger();
        }).fail(function(){
            $(".saveClientInfo").button("reset");
            alert( translations.tr_meliscore_error_message);
        });
    });

    $("body").on("click", ".clientOrderView", function(){
        var orderId   = $(this).closest('tr').attr('id');
        var orderRef  = $(this).closest('tr').find("td:nth-child(2)").text();
        var navTabsGroup = "id_meliscommerce_order_list_page";
        // Open parent tab
        melisHelper.tabOpen(translations.tr_meliscommerce_orders_Orders, 'fa fa fa-cart-plus fa-2x', 'id_meliscommerce_order_list_page', 'meliscommerce_order_list_page');
        var alreadyOpen = $("body #melis-id-nav-bar-tabs li a.tab-element[data-id='id_meliscommerce_order_list_page']");
        // check if it exists
        var checkOrders = setInterval(function() {
            if(alreadyOpen.length){
                melisHelper.tabOpen(orderRef, 'fa fa fa-cart-plus fa-2x', orderId+'_id_meliscommerce_orders_page', 'meliscommerce_orders_page', { orderId : orderId}, navTabsGroup);
                clearInterval(checkOrders);
            }
        }, 500);
    });

    $("body").on("click", ".clientOrderListRefresh", function(){
        var clientId = $(this).data("clientid");
        melisHelper.zoneReload(clientId+"_id_meliscommerce_client_page_tab_orders", "meliscommerce_client_page_tab_orders", {clientId: clientId, activateTab:true});
    });

    $("body").on("click", ".clientsExport", function() {
        if(!melisCoreTool.isTableEmpty("clientListTbl")) {

            // initialation of local variable
            zoneId = 'id_meliscommerce_client_list_content_export_form';
            melisKey = 'meliscommerce_client_list_content_export_form';
            modalUrl = '/melis/MelisCommerce/MelisComClientList/renderClientListModal';
            // requesitng to create modal and display after
            melisHelper.createModal(zoneId, melisKey, false, {}, modalUrl, function(){
                melisCoreTool.done(this);
            });
        }
    });

    $("body").on("click", "#exportClients", function(){
        var button = $(this);
        var formValues = button.closest('#id_meliscommerce_client_list_content_export_form').find('form').serializeArray();
        var target = 'id_meliscommerce_client_list_content_export_form';

        melisCoreTool.pending(button);

        $.ajax({
            type        : "POST",
            url         : "/melis/MelisCommerce/MelisComClientList/clientsExportValidate",
            data		: formValues,
            dataType    : "json",
            encode		: true
        }).done(function(data) {

            if(!data.success) {
                melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
                melisCoreTool.highlightErrors(0, data.errors, target);
                $(".date_start").prev("label").css("color","#686868");
                $(".date_end").prev("label").css("color","#686868");
                $.each( data.errors, function( key, error ) {
                    if( key == 'date_start'){
                        $(".date_start").prev("label").css("color","red");
                    }
                    if( key == 'date_end'){
                        $(".date_end").prev("label").css("color","red");
                    }
                });
            }else{
                melisCoreTool.exportData('/melis/MelisCommerce/MelisComClientList/clientsExportToCsv');
                melisHelper.melisOkNotification( data.textTitle, data.textMessage );
            }
        })

        melisCoreTool.done(button);
    });
});

window.clientHighlightErrors = function(success, errors, divContainer){

//	console.log(errors);
    // if all form fields are error color them red
    if(success === 0){

        if(divContainer !== ''){
            $("#" + divContainer + " .form-group label").css("color","#686868");
        }

        $.each( errors, function( key, error ) {

            if("form" in error){
                $.each(this.form, function( fkey, fvalue ){
                    $("#" + fvalue + " .form-control[name='"+key +"']").prev("label").css("color","red");
                });
            }else{
                if(divContainer !== ''){
                    $("#" + divContainer + " .form-control[name='"+key +"']").prev("label").css("color","red");
                }
            }
        });
    }
    // remove red color for correctly inputted fields
    else{
        $("#" + divContainer + " .form-group label").css("color","#686868");
    }
}

function melisClientKoNotification(title, message, errors, closeByButtonOnly){
    if(typeof closeByButtonOnly === "undefined") closeByButtonOnly = 'closeByButtonOnly';

    ( closeByButtonOnly !== 'closeByButtonOnly' ) ? closeByButtonOnly = 'overlay-hideonclick' : closeByButtonOnly = '';

    var errorTexts = '<h3>'+ melisHelper.melisTranslator(title) +'</h3>';
    errorTexts +='<h4>'+ melisHelper.melisTranslator(message) +'</h4>';

    $.each( errors, function( key, error ) {
        if(key !== 'label'){
            errorTexts += '<p class="modal-error-cont"><b>'+ (( errors[key]['label'] == undefined ) ? ((errors['label']== undefined) ? key : errors['label'] ) : errors[key]['label'] )+ ': </b>  ';
            // catch error level of object
            try {
                $.each( error, function( key, value ) {
                    if(key !== 'label' && key !== 'form'){

                        $errMsg = '';
                        if(value instanceof Object){
                            $errMsg = value[0];
                        }else{
                            $errMsg = value;
                        }
                        errorTexts += '<span><i class="fa fa-circle"></i>'+ $errMsg + '</span>';
                    }
                });
            } catch(Tryerror) {
                if(key !== 'label' && key !== 'form'){
                    errorTexts +=  '<span><i class="fa fa-circle"></i>'+ error + '</span>';
                }
            }
            errorTexts += '</p>';
        }
    });

    var div = "<div class='melis-modaloverlay "+ closeByButtonOnly +"'></div>";
    div += "<div class='melis-modal-cont KOnotif'>  <div class='modal-content'>"+ errorTexts +" <span class='btn btn-block btn-primary'>"+ translations.tr_meliscore_notification_modal_Close +"</span></div> </div>";
    $body.append(div);
}

window.initClientStatus = function(){
    $('#cli_status').bootstrapSwitch();
}

window.initClientOrderList = function(data, tblSettings){

    // get Category Id from table data
    clientId       = $("#" + tblSettings.sTableId ).data("clientid");
    data.clientId  = clientId;
    data.osta_id   = $('#'+ clientId +'_id_meliscommerce_client_page_tab_orders .orderFilterStatus').val();
    data.startDate = $('#'+ clientId +'_tableClientOrderList').data('dStartDate');
    data.endDate   = $('#'+ clientId +'_tableClientOrderList').data('dEndDate');
    var icon = '<i class="glyphicon glyphicon-calendar fa fa-calendar"></i> ';
    if(tblSettings.iDraw > 1) {
        dateSelectionContent = translations.tr_meliscore_datepicker_select_date  + icon + "<span class='sdate'>" + dStartDate + ' - ' + dEndDate + '</span> <b class="caret"></b>';
        $('#'+clientId+'_tableClientOrderList_wrapper .dt_orderdatepicker .dt_dateInfo').html(dateSelectionContent);
    }
    dStartDate = ""; dEndDate = ""; //clear date when Prospects page is reloaded

}

//table client custom title on icon
window.initClientListTitle = function(){
    $('#'+clientId+'_tableClientOrderList .icon-shippment').parent('th').attr('title', translations.tr_meliscommerce_clients_list_col_products);
    $('#'+clientId+'_tableClientOrderList .fa-usd').parent('th').attr('title', translations.tr_meliscommerce_clients_list_col_price_title);
}

window.initClientContactAddressForm = function(){
    var tabId = $("#saveClientContactAddress").data("tabid");
    $("#melisCommerceClientContactAddressFormModal").find("#cadd_civility").val($("#"+tabId+"_contact_form").find("#cper_civility").val());
    $("#melisCommerceClientContactAddressFormModal").find("#cadd_firstname").val($("#"+tabId+"_contact_form").find("#cper_firstname").val());
    $("#melisCommerceClientContactAddressFormModal").find("#cadd_name").val($("#"+tabId+"_contact_form").find("#cper_name").val());
    $("#melisCommerceClientContactAddressFormModal").find("#cadd_middle_name").val($("#"+tabId+"_contact_form").find("#cper_middle_name").val());
}
$(document).ready(function() {

    var body = $("body");

    //removes modal elements when clicking outside
    body.on("click", function (e) {
        if ($(e.target).hasClass('modal')) {
            $('#id_meliscommerce_order_list_content_status_form_container').modal('hide');
            $('#id_meliscommerce_order_modal_content_shipping_form_container').modal('hide');
        }
    });

    // order list - opens specific order for editing
    body.on("click", ".orderInfo", function() {
        var orderId   = $(this).closest('tr').attr('id');
        var orderRef  = $(this).closest('tr').find("td:nth-child(2)").text();
        var tabName = '';
        if(orderRef.length > 0){
            tabName = orderRef;
        }else{
            tabName = orderId;
        }
        // Open parent tab
        melisHelper.tabOpen(translations.tr_meliscommerce_orders_Orders, 'fa fa fa-cart-plus fa-2x', 'id_meliscommerce_order_list_page', 'meliscommerce_order_list_page');
        var alreadyOpen = $("body #melis-id-nav-bar-tabs li a.tab-element[data-id='id_meliscommerce_order_list_page']");
        // check if it exists
        var checkOrders = setInterval(function() {
            if(alreadyOpen.length){
                orderTabOpen(translations.tr_meliscommerce_orders_Order+' '+tabName, orderId);
                clearInterval(checkOrders);
            }
        }, 500);

    });
    // order page - toggles the new message form
    body.on("click", ".addMessage", function(){
        $(this).closest('.container-level-a').find('.add-message').slideToggle();
    });

    // order list - refreshes the order list table
    body.on("click", ".orderListRefresh", function(){
        melisHelper.zoneReload("id_meliscommerce_order_list_page", "meliscommerce_order_list_page");
        if($('#'+activeTabId).data('pageid') == 'coupon'){
            var couponId = activeTabId.split("_")[0];
            melisHelper.zoneReload(couponId+"_id_meliscommerce_coupon_tabs_content_orders_details_table", "meliscommerce_coupon_tabs_content_orders_details_table", {couponId : couponId});
        }
    });

    // order status list - refreshes the order status table
    body.on("click", ".orderStatusRefresh", function(){
        melisHelper.zoneReload("id_meliscommerce_order_status_content_table", "meliscommerce_order_status_content_table");
    });

    // order list - toggles the status form modal
    body.on("click", ".addNewOrderStatus", function(){
        var statusId = $(this).closest('tr').attr('id');
//		melisCoreTool.pending(this);
        // initialation of local variable
        zoneId = 'id_meliscommerce_order_status_form';
        melisKey = 'meliscommerce_order_status_form';
        modalUrl = '/melis/MelisCommerce/MelisComOrderStatus/renderOrderStatusModal';
        // requesitng to create modal and display after
        melisHelper.createModal(zoneId, melisKey, false, {ostaId : statusId}, modalUrl, function(){
//    		melisCoreTool.done(this);
        });
    });

    // order page - refreshes the basket table
    body.on("click", ".orderBasketRefresh", function(){
        var id = $(this).closest('.container-level-a').attr('id');
        var orderId = isNaN(parseInt(id, 10)) ? '' : parseInt(id, 10);
        melisHelper.zoneReload(orderId+"_id_meliscommerce_orders_content_tabs_content_baskets_details_list", "meliscommerce_orders_content_tabs_content_baskets_details_list", { "orderId" : orderId});
    });
    // order page - breadcrumbs
    body.on("click", ".orderList", function(){
        melisHelper.tabOpen(translations.tr_meliscommerce_orders_Orders, 'fa fa fa-cart-plus fa-2x', 'id_meliscommerce_order_list_page', 'meliscommerce_order_list_page');
    });
    // order page -saves the order
    body.on("click", ".saveOrder", function(){
        melisCoreTool.pending(".saveOrder");
        var url = 'melis/MelisCommerce/MelisComOrder/saveOrder';
        var id = $(this).closest('.container-level-a').attr('id');
        var orderId = isNaN(parseInt(id, 10)) ? '' : parseInt(id, 10);
        var forms = $(this).closest('.container-level-a').find('form');
        var dataString = [];
        var len;
        var ctr = 0;
        var statusId = $(this).closest('.container-level-a').find('.selectedStatus').data('statusid');
        var reference = $(this).closest('.container-level-a').find('input[name=ord_reference]').val();
        var orderStats = $(this).data('orderstatus');

        forms.each(function(){
            var pre = $(this).attr('name');
            var data = $(this).serializeArray();
            len = data.length;
            for(j=0; j<len; j++ ){
                dataString.push({  name: pre+'['+ctr+']['+data[j].name+']', value : data[j].value});
            }
            ctr++;
        });
        dataString.push({name : 'orderId', value : orderId});
        dataString.push({name: 'order[0][ord_status]', value : statusId});
        dataString.push({name : 'lastStatus', value : orderStats});

        if(statusId == 5 && orderStats != 5){
            melisCoreTool.confirm(
                translations.tr_meliscommerce_order_common_label_yes,
                translations.tr_meliscommerce_order_common_label_no,
                reference,
                translations.tr_meliscommerce_order_save_status_canceled_confirm,
                function(){
                    melisCommerce.postSave(url, dataString, function(data){
                        if(data.success){
                            melisHelper.tabClose(  orderId + "_id_meliscommerce_orders_page");
                            orderTabOpen(translations.tr_meliscommerce_orders_Order+' '+data.chunk.order.ord_reference, data.chunk.order.ord_id);
                            melisHelper.melisOkNotification( data.textTitle, data.textMessage );
                            melisHelper.zoneReload("id_meliscommerce_order_list_page", "meliscommerce_order_list_page");
                            melisCore.flashMessenger();

                            // Relload Client Order list if id exist
                            if(data.clientId+"_id_meliscommerce_client_page_tab_orders".length){
                                melisHelper.zoneReload(data.clientId+"_id_meliscommerce_client_page_tab_orders", "meliscommerce_client_page_tab_orders", {clientId: data.clientId, activateTab:true});
                            }
                        }else{
                            melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
                            melisCoreTool.highlightErrors(data.success, data.errors, orderId+"_id_meliscommerce_orders_page");
                        }
                    }, function(data){
                        console.log(data);
                    });
                });
        }else{
            melisCommerce.postSave(url, dataString, function(data){
                if(data.success){
                    melisHelper.tabClose(  orderId + "_id_meliscommerce_orders_page");
                    orderTabOpen(translations.tr_meliscommerce_orders_Order+' '+data.chunk.order.ord_reference, data.chunk.order.ord_id);
                    melisHelper.melisOkNotification( data.textTitle, data.textMessage );
                    melisHelper.zoneReload("id_meliscommerce_order_list_page", "meliscommerce_order_list_page");
                    melisCore.flashMessenger();

                    // Relload Client Order list if id exist
                    if(data.clientId+"_id_meliscommerce_client_page_tab_orders".length){
                        melisHelper.zoneReload(data.clientId+"_id_meliscommerce_client_page_tab_orders", "meliscommerce_client_page_tab_orders", {clientId: data.clientId, activateTab:true});
                    }
                }else{
                    melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
                    melisCoreTool.highlightErrors(data.success, data.errors, orderId+"_id_meliscommerce_orders_page");
                }
            }, function(data){
                console.log(data);
            });
        }

        melisCoreTool.done(".saveOrder");

    });
    // order page - saves the new created message
    body.on("click", ".add-order-message", function(){
        var id = $(this).closest('.container-level-a').attr('id');
        var orderId = isNaN(parseInt(id, 10)) ? '' : parseInt(id, 10);
        var dataString = $(this).closest('div').find('form').serializeArray();
        var url = 'melis/MelisCommerce/MelisComOrder/saveOrderMessage';
        dataString.push({name : 'orderId', value : orderId});
        melisCoreTool.pending(this);
        melisCommerce.postSave(url, dataString, function(data){
            if(data.success){
                melisHelper.melisOkNotification( data.textTitle, data.textMessage );
                melisHelper.zoneReload( orderId+"_id_meliscommerce_orders_content_tabs_content_messages_details", "meliscommerce_orders_content_tabs_content_messages_details", { "orderId" : orderId});

            }else{
                melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
                melisCoreTool.highlightErrors(data.success, data.errors, orderId+"_id_meliscommerce_orders_page");
            }
            // melisCore.flashMessenger();
        }, function(data){
            console.log(data);
        });
        melisCoreTool.done(this);
    });
    // order list - toggles the status form modal
    body.on("click", ".updateListStatus", function(){
        var orderId = $(this).data('orderid');
        melisCoreTool.pending(this);
        // initialation of local variable
        zoneId = 'id_meliscommerce_order_list_content_status_form';
        melisKey = 'meliscommerce_order_list_content_status_form';
        modalUrl = '/melis/MelisCommerce/MelisComOrderList/renderOrderListModal';
        // requesitng to create modal and display after
        melisHelper.createModal(zoneId, melisKey, false, {'orderId': orderId}, modalUrl, function(){
            melisCoreTool.done(this);
        });
    });
    // order page - toggles the shipping form modal
    body.on("click", ".addShipping", function(){
        var orderId = $(this).closest('tr').attr('id');
        melisCoreTool.pending(this);
        // initialation of local variable
        zoneId = 'id_meliscommerce_order_modal_content_shipping_form';
        melisKey = 'meliscommerce_order_modal_content_shipping_form';
        modalUrl = '/melis/MelisCommerce/MelisComOrder/renderOrderModal';
        // requesitng to create modal and display after
        melisHelper.createModal(zoneId, melisKey, false, {'orderId': orderId}, modalUrl, function(){

        });
        melisCoreTool.done(this);
    });
    // order list - saves the new order status
    body.on("click", "#saveOrderStatus", function(){
        var dataString = $(this).closest('#id_meliscommerce_order_list_content_status_form').find('form').serializeArray();
        var url = 'melis/MelisCommerce/MelisComOrderList/saveOrderStatus';
        var status = $('#id_meliscommerce_order_list_content_status_form select[name="ord_status"]').val();
        var reference = translations.tr_meliscommerce_orders_Order + ' ' +$(this).data('reference');

        melisCoreTool.pending(this);

        if(status == 5){
            melisCoreTool.confirm(
                translations.tr_meliscommerce_order_common_label_yes,
                translations.tr_meliscommerce_order_common_label_no,
                reference,
                translations.tr_meliscommerce_order_save_status_canceled_confirm,
                function(){
                    melisCommerce.postSave(url, dataString, function(data){
                        if(data.success){;
                            melisHelper.melisOkNotification( data.textTitle, data.textMessage );
                            melisHelper.zoneReload("id_meliscommerce_order_list_page", "meliscommerce_order_list_page");
                            // Relload Client Order list if id exist
                            if(data.clientId+"_id_meliscommerce_client_page_tab_orders".length){
                                melisHelper.zoneReload(data.clientId+"_id_meliscommerce_client_page_tab_orders", "meliscommerce_client_page_tab_orders", {clientId: data.clientId, activateTab:true});
                            }

                            //reload coupon if active
                            if($('#'+activeTabId).data('pageid') == 'coupon'){
                                var couponId = activeTabId.split("_")[0];
                                melisHelper.zoneReload(couponId+"_id_meliscommerce_coupon_tabs_content_orders_details_table", "meliscommerce_coupon_tabs_content_orders_details_table", {couponId : couponId});
                            }

                            melisCore.flashMessenger();
                        }else{
                            melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
                            melisCoreTool.highlightErrors(data.success, data.errors, "id_meliscommerce_order_list_content_status_form");
                        }
                    }, function(data){
                        console.log(data);
                    });
                });
        }else{
            melisCommerce.postSave(url, dataString, function(data){
                if(data.success){;
                    melisHelper.melisOkNotification( data.textTitle, data.textMessage );
                    melisHelper.zoneReload("id_meliscommerce_order_list_page", "meliscommerce_order_list_page");
                    // Relload Client Order list if id exist
                    if(data.clientId+"_id_meliscommerce_client_page_tab_orders".length){
                        melisHelper.zoneReload(data.clientId+"_id_meliscommerce_client_page_tab_orders", "meliscommerce_client_page_tab_orders", {clientId: data.clientId, activateTab:true});
                    }

                    //reload coupon if active
                    if($('#'+activeTabId).data('pageid') == 'coupon'){
                        var couponId = activeTabId.split("_")[0];
                        melisHelper.zoneReload(couponId+"_id_meliscommerce_coupon_tabs_content_orders_details_table", "meliscommerce_coupon_tabs_content_orders_details_table", {couponId : couponId});
                    }

                    melisCore.flashMessenger();
                }else{
                    melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
                    melisCoreTool.highlightErrors(data.success, data.errors, "id_meliscommerce_order_list_content_status_form");
                }
            }, function(data){
                console.log(data);
            });
        }


        melisCoreTool.done(this);
        $("#id_meliscommerce_order_list_content_status_form_container").modal("hide");
    });
    // order page - saves the new shipping
    body.on("click", "#saveOrderShipping", function(){
        var forms = $(this).closest('#id_meliscommerce_order_modal_content_shipping_form').find('form');
        var url = 'melis/MelisCommerce/MelisComOrder/saveOrder';
        var ord_reference = $('#'+activeTabId+' input[name=ord_reference]').val();
        var ord_status = $('#'+activeTabId+' .selectedStatus').data('statusid');
        var id = $('#'+activeTabId).attr('id');;
        var orderId = isNaN(parseInt(id, 10)) ? '' : parseInt(id, 10);
        var dataString = [];
        var len;
        var ctr = 0;
        melisCoreTool.pending(this);
        forms.each(function(){
            var pre = $(this).attr('name');
            var data = $(this).serializeArray();
            len = data.length;
            for(j=0; j<len; j++ ){
                dataString.push({  name: pre+'['+ctr+']['+data[j].name+']', value : data[j].value});
            }
            ctr++;
        });
        dataString.push({name : 'order[0][ord_id]', value : orderId});
        dataString.push({name : 'order[0][ord_reference]', value : ord_reference});
        dataString.push({name : 'order[0][ord_status]', value : ord_status});
        dataString.push({name : 'orderId', value : orderId});
        melisCommerce.postSave(url, dataString, function(data){
            if(data.success){;
                melisHelper.melisOkNotification( data.textTitle, data.textMessage );
                // Relload Client Order list if id exist
                if(data.clientId+"_id_meliscommerce_client_page_tab_orders".length){
                    melisHelper.zoneReload(data.clientId+"_id_meliscommerce_client_page_tab_orders", "meliscommerce_client_page_tab_orders", {clientId: data.clientId, activateTab:true});
                }
                $('#id_meliscommerce_order_modal_content_shipping_form_container').modal('hide');
                melisHelper.zoneReload(orderId+"_id_meliscommerce_orders_content_tabs_content_shipping_details", "meliscommerce_orders_content_tabs_content_shipping_details", {"orderId": orderId});
                melisCore.flashMessenger();
            }else{
                melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
                melisCoreTool.highlightErrors(data.success, data.errors,"id_meliscommerce_order_modal_content_shipping_form");
                $("#id_meliscommerce_order_modal_content_shipping_form .shippingDate").prev("label").css("color","#686868");
                $.each( data.errors, function( key, error ) {
                    if( key == 'oship_date_sent'){
                        $("#id_meliscommerce_order_modal_content_shipping_form .shippingDate").prev("label").css("color","red");
                    }
                });
            }
        }, function(data){
            console.log(data);
        });
        melisCoreTool.done(this);

    });

    body.on("click", ".mainOrderStatus", function(){
        $(this).parent().find('.mainOrderStatus').each(function(){
            $(this).removeClass('selectedStatus');
            var button = $(this).find('span');
            var statusColor = button.css('border-top-color');
            button.css('color', statusColor);
            button.css('background','#fff');
        })
        $(this).addClass('selectedStatus');
        var button = $(this).find('span');
        var statusColor = button.css('border-top-color');
        button.css('color', '#fff');
        button.css('background', statusColor);

    });

    function orderTabOpen(ordername, id){
        var navTabsGroup = "id_meliscommerce_order_list_page";
        melisHelper.tabOpen(ordername, 'fa fa fa-cart-plus fa-2x', id+'_id_meliscommerce_orders_page', 'meliscommerce_orders_page', { orderId : id}, navTabsGroup);
    }

    $("body").on('apply.daterangepicker', ".dt_orderdatepicker", function(ev, picker) {
        // reload table
        var tableId = $(this).parents().eq(5).find('table').attr('id');
        $("#"+tableId).data('dStartDate', dStartDate)
        $("#"+tableId).data('dEndDate', dEndDate)
        $("#"+tableId).DataTable().ajax.reload();
    });

    $("body").on('change', '.orderFilterStatus',function(){
        var tableId = $(this).parents().eq(6).find('table').attr('id');
        $("#"+tableId).DataTable().ajax.reload();
    });

    body.on("click", ".variantInfo", function(){
        var variantId   = $(this).closest('tr').data('variantid');
        var variantName = $(this).closest('tr').data('sku');;
        var productId   = $(this).closest('tr').data('productid');
        melisCommerce.disableAllTabs();
        melisHelper.tabOpen(variantName, 'icon-tag-2', variantId+'_id_meliscommerce_variants_page', 'meliscommerce_variants_page', { variantId : variantId, productId : productId});
        melisCommerce.setUniqueId(variantId);
        melisCommerce.enableAllTabs();
    });

    body.on("click", ".ordersExport", function() {
        if(!melisCoreTool.isTableEmpty("tableOrderList")) {

            // initialation of local variable
            zoneId = 'id_meliscommerce_order_list_content_export_form';
            melisKey = 'meliscommerce_order_list_content_export_form';
            modalUrl = '/melis/MelisCommerce/MelisComOrderList/renderOrderListModal';
            // requesitng to create modal and display after
            melisHelper.createModal(zoneId, melisKey, false, {}, modalUrl, function(){
                melisCoreTool.done(this);
            });
        }
    });

    body.on("click", "#exportOrders", function(){
        var button = $(this);
        var formValues = button.closest('#id_meliscommerce_order_list_content_export_form').find('form').serializeArray();
        var target = 'id_meliscommerce_order_list_content_export_form';
        melisCoreTool.pending(button);

        $.ajax({
            type        : "POST",
            url         : "/melis/MelisCommerce/MelisComOrderList/ordersExportValidate",
            data		: formValues,
            dataType    : "json",
            encode		: true
        }).done(function(data) {

            if(!data.success) {
                melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
                melisCoreTool.highlightErrors(0, data.errors, target);
                $(".date_start").prev("label").css("color","#686868");
                $(".date_end").prev("label").css("color","#686868");
                $.each( data.errors, function( key, error ) {
                    if( key == 'date_start'){
                        $(".date_start").prev("label").css("color","red");
                    }
                    if( key == 'date_end'){
                        $(".date_end").prev("label").css("color","red");
                    }
                });
            }else{
                melisCoreTool.exportData('/melis/MelisCommerce/MelisComOrderList/ordersExportToCsv');
                melisHelper.melisOkNotification( data.textTitle, data.textMessage );
            }
        })

        melisCoreTool.done(button);
    });

    // order status - saves the order status
    body.on("click", "#saveOrderStatusForm", function(){
        var saveButton = $(this);
        var statusId = saveButton.data('statusid');
        var statusDom = $('#id_meliscommerce_order_status_form_container .make-switch div').hasClass('switch-on');
        var forms  = $('#id_meliscommerce_order_status_form_container').find('form');
        var url = 'melis/MelisCommerce/MelisComOrderStatus/saveOrderStatus';
        var dataString = [];
        var len;
        var ctr = 0;
        var status = 0;

        if(statusDom){
            status = 1;
        }

        forms.each(function(){
            var pre = $(this).attr('name');
            var data = $(this).serializeArray();
            len = data.length;
            for(j=0; j<len; j++ ){
                dataString.push({  name: pre+'['+ctr+']['+data[j].name+']', value : data[j].value});
            }
            ctr++;
        });

        dataString.push({ name: 'statusId', value : statusId });
        dataString.push({ name: 'order_status[0][osta_status]', value : status });

        melisCoreTool.pending(this);
        melisCommerce.postSave(url, dataString, function(data){
            if(data.success){;
                melisHelper.melisOkNotification( data.textTitle, data.textMessage );
                melisHelper.zoneReload("id_meliscommerce_order_status_content_table", "meliscommerce_order_status_content_table");
                $("#id_meliscommerce_order_status_form_container").modal("hide");
            }else{
                melisCoreTool.highlightErrors(data.success, data.errors, "id_meliscommerce_order_status_form_container");
                $.each( data.errors, function( key, error ) {
                    if( key == 'osta_color_code'){
                        $(".osta_color_code").prev("label").css("color","red");
                    }
                });
                melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
            }
            melisCore.flashMessenger();
        }, function(data){
            console.log(data);
        });
        melisCoreTool.done(this);

    });

    // order status - deletes the coupon
    body.on("click", ".orderStatusDelete", function(){
        var ostaId   = $(this).closest('tr').attr('id');
        var url = 'melis/MelisCommerce/MelisComOrderStatus/deleteOrderStatus';
        var dataString = [];
        dataString.push({
            name : 'ostaId',
            value: ostaId,
        });
        melisCoreTool.pending(this);

        melisCoreTool.confirm(
            translations.tr_meliscommerce_documents_common_label_yes,
            translations.tr_meliscommerce_documents_common_label_no,
            translations.tr_meliscommerce_order_status_tool_leftmenu,
            translations.tr_meliscommerce_order_status_delete_confirm,
            function(){
                melisCommerce.postSave(url, dataString, function(data){
                    if(data.success){
                        melisHelper.melisOkNotification( data.textTitle, data.textMessage );
                        melisHelper.zoneReload("id_meliscommerce_order_status_content_table", "meliscommerce_order_status_content_table");
                        melisHelper.tabClose(  couponId + "_id_meliscommerce_coupon_page");
                    }else{
                        melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
                    }
                    melisCore.flashMessenger();
                }, function(data){
                    console.log(data);
                })
            });

        melisCoreTool.done(this);
    });

});
// table datafunction for basket
window.initOrderBasket = function(data, tblSettings) {
    var orderId = $("#" + tblSettings.sTableId ).data("orderid");
    data.orderId = orderId;
}

window.hideBasketButton = function (data, tblSettings){
    $('.variantInfo').each(function(){
        variantId = $(this).closest('tr').data('variantid');
        productId = $(this).closest('tr').data('productid');
        if(variantId == null || productId == null){
            $(this).hide();
        }
    });
}

//table order custom filter
window.initOrderList = function(data, tblSettings){

    data.osta_id = $('#id_meliscommerce_order_list_content_table .orderFilterStatus').val();
    data.startDate = $("#tableOrderList").data('dStartDate');
    data.endDate   = $("#tableOrderList").data('dEndDate');
    var icon = '<i class="glyphicon glyphicon-calendar fa fa-calendar"></i> ';

    if(tblSettings.iDraw > 1) {
        dateSelectionContent = translations.tr_meliscore_datepicker_select_date  + icon + "<span class='sdate'>" + dStartDate + ' - ' + dEndDate + '</span> <b class="caret"></b>';
        $('#tableOrderList_wrapper .dt_orderdatepicker .dt_dateInfo').html(dateSelectionContent);
    }
    dStartDate = ""; dEndDate = ""; //clear date when Prospects page is reloaded
}

//table order custom filter
window.initOrderListTitle = function(){
    $('#tableOrderList .icon-shippment').parent('th').attr('title', translations.tr_meliscommerce_order_list_col_products);
    $('#tableOrderList .fa-usd').parent('th').attr('title', translations.tr_meliscommerce_order_list_col_price_title);
}

// order status table remove delete button for permanent status
window.initCheckPermStatus = function(tblSettings){
    var btnDelete = $('tr.primeStatus td').find(".orderStatusDelete");
    btnDelete.remove();
}
/*!
 * Bootstrap Colorpicker v2.5.1
 * https://itsjavi.com/bootstrap-colorpicker/
 *
 * Originally written by (c) 2012 Stefan Petre
 * Licensed under the Apache License v2.0
 * http://www.apache.org/licenses/LICENSE-2.0.txt
 *
 */

(function(root, factory) {
  if (typeof define === 'function' && define.amd) {
    // AMD. Register as an anonymous module unless amdModuleId is set
    define(["jquery"], function(jq) {
      return (factory(jq));
    });
  } else if (typeof exports === 'object') {
    // Node. Does not work with strict CommonJS, but
    // only CommonJS-like environments that support module.exports,
    // like Node.
    module.exports = factory(require("jquery"));
  } else if (jQuery && !jQuery.fn.colorpicker) {
    factory(jQuery);
  }
}(this, function($) {
  'use strict';
  /**
   * Color manipulation helper class
   *
   * @param {Object|String} [val]
   * @param {Object} [predefinedColors]
   * @param {String|null} [fallbackColor]
   * @param {String|null} [fallbackFormat]
   * @param {Boolean} [hexNumberSignPrefix]
   * @constructor
   */
  var Color = function(
    val, predefinedColors, fallbackColor, fallbackFormat, hexNumberSignPrefix) {
    this.fallbackValue = fallbackColor ?
      (
        fallbackColor && (typeof fallbackColor.h !== 'undefined') ?
        fallbackColor :
        this.value = {
          h: 0,
          s: 0,
          b: 0,
          a: 1
        }
      ) :
      null;

    this.fallbackFormat = fallbackFormat ? fallbackFormat : 'rgba';

    this.hexNumberSignPrefix = hexNumberSignPrefix === true;

    this.value = this.fallbackValue;

    this.origFormat = null; // original string format

    this.predefinedColors = predefinedColors ? predefinedColors : {};

    // We don't want to share aliases across instances so we extend new object
    this.colors = $.extend({}, Color.webColors, this.predefinedColors);

    if (val) {
      if (typeof val.h !== 'undefined') {
        this.value = val;
      } else {
        this.setColor(String(val));
      }
    }

    if (!this.value) {
      // Initial value is always black if no arguments are passed or val is empty
      this.value = {
        h: 0,
        s: 0,
        b: 0,
        a: 1
      };
    }
  };

  Color.webColors = { // 140 predefined colors from the HTML Colors spec
    "aliceblue": "f0f8ff",
    "antiquewhite": "faebd7",
    "aqua": "00ffff",
    "aquamarine": "7fffd4",
    "azure": "f0ffff",
    "beige": "f5f5dc",
    "bisque": "ffe4c4",
    "black": "000000",
    "blanchedalmond": "ffebcd",
    "blue": "0000ff",
    "blueviolet": "8a2be2",
    "brown": "a52a2a",
    "burlywood": "deb887",
    "cadetblue": "5f9ea0",
    "chartreuse": "7fff00",
    "chocolate": "d2691e",
    "coral": "ff7f50",
    "cornflowerblue": "6495ed",
    "cornsilk": "fff8dc",
    "crimson": "dc143c",
    "cyan": "00ffff",
    "darkblue": "00008b",
    "darkcyan": "008b8b",
    "darkgoldenrod": "b8860b",
    "darkgray": "a9a9a9",
    "darkgreen": "006400",
    "darkkhaki": "bdb76b",
    "darkmagenta": "8b008b",
    "darkolivegreen": "556b2f",
    "darkorange": "ff8c00",
    "darkorchid": "9932cc",
    "darkred": "8b0000",
    "darksalmon": "e9967a",
    "darkseagreen": "8fbc8f",
    "darkslateblue": "483d8b",
    "darkslategray": "2f4f4f",
    "darkturquoise": "00ced1",
    "darkviolet": "9400d3",
    "deeppink": "ff1493",
    "deepskyblue": "00bfff",
    "dimgray": "696969",
    "dodgerblue": "1e90ff",
    "firebrick": "b22222",
    "floralwhite": "fffaf0",
    "forestgreen": "228b22",
    "fuchsia": "ff00ff",
    "gainsboro": "dcdcdc",
    "ghostwhite": "f8f8ff",
    "gold": "ffd700",
    "goldenrod": "daa520",
    "gray": "808080",
    "green": "008000",
    "greenyellow": "adff2f",
    "honeydew": "f0fff0",
    "hotpink": "ff69b4",
    "indianred": "cd5c5c",
    "indigo": "4b0082",
    "ivory": "fffff0",
    "khaki": "f0e68c",
    "lavender": "e6e6fa",
    "lavenderblush": "fff0f5",
    "lawngreen": "7cfc00",
    "lemonchiffon": "fffacd",
    "lightblue": "add8e6",
    "lightcoral": "f08080",
    "lightcyan": "e0ffff",
    "lightgoldenrodyellow": "fafad2",
    "lightgrey": "d3d3d3",
    "lightgreen": "90ee90",
    "lightpink": "ffb6c1",
    "lightsalmon": "ffa07a",
    "lightseagreen": "20b2aa",
    "lightskyblue": "87cefa",
    "lightslategray": "778899",
    "lightsteelblue": "b0c4de",
    "lightyellow": "ffffe0",
    "lime": "00ff00",
    "limegreen": "32cd32",
    "linen": "faf0e6",
    "magenta": "ff00ff",
    "maroon": "800000",
    "mediumaquamarine": "66cdaa",
    "mediumblue": "0000cd",
    "mediumorchid": "ba55d3",
    "mediumpurple": "9370d8",
    "mediumseagreen": "3cb371",
    "mediumslateblue": "7b68ee",
    "mediumspringgreen": "00fa9a",
    "mediumturquoise": "48d1cc",
    "mediumvioletred": "c71585",
    "midnightblue": "191970",
    "mintcream": "f5fffa",
    "mistyrose": "ffe4e1",
    "moccasin": "ffe4b5",
    "navajowhite": "ffdead",
    "navy": "000080",
    "oldlace": "fdf5e6",
    "olive": "808000",
    "olivedrab": "6b8e23",
    "orange": "ffa500",
    "orangered": "ff4500",
    "orchid": "da70d6",
    "palegoldenrod": "eee8aa",
    "palegreen": "98fb98",
    "paleturquoise": "afeeee",
    "palevioletred": "d87093",
    "papayawhip": "ffefd5",
    "peachpuff": "ffdab9",
    "peru": "cd853f",
    "pink": "ffc0cb",
    "plum": "dda0dd",
    "powderblue": "b0e0e6",
    "purple": "800080",
    "red": "ff0000",
    "rosybrown": "bc8f8f",
    "royalblue": "4169e1",
    "saddlebrown": "8b4513",
    "salmon": "fa8072",
    "sandybrown": "f4a460",
    "seagreen": "2e8b57",
    "seashell": "fff5ee",
    "sienna": "a0522d",
    "silver": "c0c0c0",
    "skyblue": "87ceeb",
    "slateblue": "6a5acd",
    "slategray": "708090",
    "snow": "fffafa",
    "springgreen": "00ff7f",
    "steelblue": "4682b4",
    "tan": "d2b48c",
    "teal": "008080",
    "thistle": "d8bfd8",
    "tomato": "ff6347",
    "turquoise": "40e0d0",
    "violet": "ee82ee",
    "wheat": "f5deb3",
    "white": "ffffff",
    "whitesmoke": "f5f5f5",
    "yellow": "ffff00",
    "yellowgreen": "9acd32",
    "transparent": "transparent"
  };

  Color.prototype = {
    constructor: Color,
    colors: {}, // merged web and predefined colors
    predefinedColors: {},
    /**
     * @return {Object}
     */
    getValue: function() {
      return this.value;
    },
    /**
     * @param {Object} val
     */
    setValue: function(val) {
      this.value = val;
    },
    _sanitizeNumber: function(val) {
      if (typeof val === 'number') {
        return val;
      }
      if (isNaN(val) || (val === null) || (val === '') || (val === undefined)) {
        return 1;
      }
      if (val === '') {
        return 0;
      }
      if (typeof val.toLowerCase !== 'undefined') {
        if (val.match(/^\./)) {
          val = "0" + val;
        }
        return Math.ceil(parseFloat(val) * 100) / 100;
      }
      return 1;
    },
    isTransparent: function(strVal) {
      if (!strVal || !(typeof strVal === 'string' || strVal instanceof String)) {
        return false;
      }
      strVal = strVal.toLowerCase().trim();
      return (strVal === 'transparent') || (strVal.match(/#?00000000/)) || (strVal.match(/(rgba|hsla)\(0,0,0,0?\.?0\)/));
    },
    rgbaIsTransparent: function(rgba) {
      return ((rgba.r === 0) && (rgba.g === 0) && (rgba.b === 0) && (rgba.a === 0));
    },
    // parse a string to HSB
    /**
     * @protected
     * @param {String} strVal
     * @returns {boolean} Returns true if it could be parsed, false otherwise
     */
    setColor: function(strVal) {
      strVal = strVal.toLowerCase().trim();
      if (strVal) {
        if (this.isTransparent(strVal)) {
          this.value = {
            h: 0,
            s: 0,
            b: 0,
            a: 0
          };
          return true;
        } else {
          var parsedColor = this.parse(strVal);
          if (parsedColor) {
            this.value = this.value = {
              h: parsedColor.h,
              s: parsedColor.s,
              b: parsedColor.b,
              a: parsedColor.a
            };
            if (!this.origFormat) {
              this.origFormat = parsedColor.format;
            }
          } else if (this.fallbackValue) {
            // if parser fails, defaults to fallbackValue if defined, otherwise the value won't be changed
            this.value = this.fallbackValue;
          }
        }
      }
      return false;
    },
    setHue: function(h) {
      this.value.h = 1 - h;
    },
    setSaturation: function(s) {
      this.value.s = s;
    },
    setBrightness: function(b) {
      this.value.b = 1 - b;
    },
    setAlpha: function(a) {
      this.value.a = Math.round((parseInt((1 - a) * 100, 10) / 100) * 100) / 100;
    },
    toRGB: function(h, s, b, a) {
      if (arguments.length === 0) {
        h = this.value.h;
        s = this.value.s;
        b = this.value.b;
        a = this.value.a;
      }

      h *= 360;
      var R, G, B, X, C;
      h = (h % 360) / 60;
      C = b * s;
      X = C * (1 - Math.abs(h % 2 - 1));
      R = G = B = b - C;

      h = ~~h;
      R += [C, X, 0, 0, X, C][h];
      G += [X, C, C, X, 0, 0][h];
      B += [0, 0, X, C, C, X][h];

      return {
        r: Math.round(R * 255),
        g: Math.round(G * 255),
        b: Math.round(B * 255),
        a: a
      };
    },
    toHex: function(h, s, b, a) {
      if (arguments.length === 0) {
        h = this.value.h;
        s = this.value.s;
        b = this.value.b;
        a = this.value.a;
      }

      var rgb = this.toRGB(h, s, b, a);

      if (this.rgbaIsTransparent(rgb)) {
        return 'transparent';
      }

      var hexStr = (this.hexNumberSignPrefix ? '#' : '') + (
          (1 << 24) +
          (parseInt(rgb.r) << 16) +
          (parseInt(rgb.g) << 8) +
          parseInt(rgb.b))
        .toString(16)
        .slice(1);

      return hexStr;
    },
    toHSL: function(h, s, b, a) {
      if (arguments.length === 0) {
        h = this.value.h;
        s = this.value.s;
        b = this.value.b;
        a = this.value.a;
      }

      var H = h,
        L = (2 - s) * b,
        S = s * b;
      if (L > 0 && L <= 1) {
        S /= L;
      } else {
        S /= 2 - L;
      }
      L /= 2;
      if (S > 1) {
        S = 1;
      }
      return {
        h: isNaN(H) ? 0 : H,
        s: isNaN(S) ? 0 : S,
        l: isNaN(L) ? 0 : L,
        a: isNaN(a) ? 0 : a
      };
    },
    toAlias: function(r, g, b, a) {
      var c, rgb = (arguments.length === 0) ? this.toHex() : this.toHex(r, g, b, a);

      // support predef. colors in non-hex format too, as defined in the alias itself
      var original = this.origFormat === 'alias' ? rgb : this.toString(this.origFormat, false);

      for (var alias in this.colors) {
        c = this.colors[alias].toLowerCase().trim();
        if ((c === rgb) || (c === original)) {
          return alias;
        }
      }
      return false;
    },
    RGBtoHSB: function(r, g, b, a) {
      r /= 255;
      g /= 255;
      b /= 255;

      var H, S, V, C;
      V = Math.max(r, g, b);
      C = V - Math.min(r, g, b);
      H = (C === 0 ? null :
        V === r ? (g - b) / C :
        V === g ? (b - r) / C + 2 :
        (r - g) / C + 4
      );
      H = ((H + 360) % 6) * 60 / 360;
      S = C === 0 ? 0 : C / V;
      return {
        h: this._sanitizeNumber(H),
        s: S,
        b: V,
        a: this._sanitizeNumber(a)
      };
    },
    HueToRGB: function(p, q, h) {
      if (h < 0) {
        h += 1;
      } else if (h > 1) {
        h -= 1;
      }
      if ((h * 6) < 1) {
        return p + (q - p) * h * 6;
      } else if ((h * 2) < 1) {
        return q;
      } else if ((h * 3) < 2) {
        return p + (q - p) * ((2 / 3) - h) * 6;
      } else {
        return p;
      }
    },
    HSLtoRGB: function(h, s, l, a) {
      if (s < 0) {
        s = 0;
      }
      var q;
      if (l <= 0.5) {
        q = l * (1 + s);
      } else {
        q = l + s - (l * s);
      }

      var p = 2 * l - q;

      var tr = h + (1 / 3);
      var tg = h;
      var tb = h - (1 / 3);

      var r = Math.round(this.HueToRGB(p, q, tr) * 255);
      var g = Math.round(this.HueToRGB(p, q, tg) * 255);
      var b = Math.round(this.HueToRGB(p, q, tb) * 255);
      return [r, g, b, this._sanitizeNumber(a)];
    },
    /**
     * @param {String} strVal
     * @returns {Object} Object containing h,s,b,a,format properties or FALSE if failed to parse
     */
    parse: function(strVal) {
      if (arguments.length === 0) {
        return false;
      }

      var that = this,
        result = false,
        isAlias = (typeof this.colors[strVal] !== 'undefined'),
        values, format;

      if (isAlias) {
        strVal = this.colors[strVal].toLowerCase().trim();
      }

      $.each(this.stringParsers, function(i, parser) {
        var match = parser.re.exec(strVal);
        values = match && parser.parse.apply(that, [match]);
        if (values) {
          result = {};
          format = (isAlias ? 'alias' : (parser.format ? parser.format : that.getValidFallbackFormat()));
          if (format.match(/hsla?/)) {
            result = that.RGBtoHSB.apply(that, that.HSLtoRGB.apply(that, values));
          } else {
            result = that.RGBtoHSB.apply(that, values);
          }
          if (result instanceof Object) {
            result.format = format;
          }
          return false; // stop iterating
        }
        return true;
      });
      return result;
    },
    getValidFallbackFormat: function() {
      var formats = [
        'rgba', 'rgb', 'hex', 'hsla', 'hsl'
      ];
      if (this.origFormat && (formats.indexOf(this.origFormat) !== -1)) {
        return this.origFormat;
      }
      if (this.fallbackFormat && (formats.indexOf(this.fallbackFormat) !== -1)) {
        return this.fallbackFormat;
      }

      return 'rgba'; // By default, return a format that will not lose the alpha info
    },
    /**
     *
     * @param {string} [format] (default: rgba)
     * @param {boolean} [translateAlias] Return real color for pre-defined (non-standard) aliases (default: false)
     * @returns {String}
     */
    toString: function(format, translateAlias) {
      format = format || this.origFormat || this.fallbackFormat;
      translateAlias = translateAlias || false;

      var c = false;

      switch (format) {
        case 'rgb':
          {
            c = this.toRGB();
            if (this.rgbaIsTransparent(c)) {
              return 'transparent';
            }
            return 'rgb(' + c.r + ',' + c.g + ',' + c.b + ')';
          }
          break;
        case 'rgba':
          {
            c = this.toRGB();
            return 'rgba(' + c.r + ',' + c.g + ',' + c.b + ',' + c.a + ')';
          }
          break;
        case 'hsl':
          {
            c = this.toHSL();
            return 'hsl(' + Math.round(c.h * 360) + ',' + Math.round(c.s * 100) + '%,' + Math.round(c.l * 100) + '%)';
          }
          break;
        case 'hsla':
          {
            c = this.toHSL();
            return 'hsla(' + Math.round(c.h * 360) + ',' + Math.round(c.s * 100) + '%,' + Math.round(c.l * 100) + '%,' + c.a + ')';
          }
          break;
        case 'hex':
          {
            return this.toHex();
          }
          break;
        case 'alias':
          {
            c = this.toAlias();

            if (c === false) {
              return this.toString(this.getValidFallbackFormat());
            }

            if (translateAlias && !(c in Color.webColors) && (c in this.predefinedColors)) {
              return this.predefinedColors[c];
            }

            return c;
          }
        default:
          {
            return c;
          }
          break;
      }
    },
    // a set of RE's that can match strings and generate color tuples.
    // from John Resig color plugin
    // https://github.com/jquery/jquery-color/
    stringParsers: [{
      re: /rgb\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*?\)/,
      format: 'rgb',
      parse: function(execResult) {
        return [
          execResult[1],
          execResult[2],
          execResult[3],
          1
        ];
      }
    }, {
      re: /rgb\(\s*(\d*(?:\.\d+)?)\%\s*,\s*(\d*(?:\.\d+)?)\%\s*,\s*(\d*(?:\.\d+)?)\%\s*?\)/,
      format: 'rgb',
      parse: function(execResult) {
        return [
          2.55 * execResult[1],
          2.55 * execResult[2],
          2.55 * execResult[3],
          1
        ];
      }
    }, {
      re: /rgba\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*(?:,\s*(\d*(?:\.\d+)?)\s*)?\)/,
      format: 'rgba',
      parse: function(execResult) {
        return [
          execResult[1],
          execResult[2],
          execResult[3],
          execResult[4]
        ];
      }
    }, {
      re: /rgba\(\s*(\d*(?:\.\d+)?)\%\s*,\s*(\d*(?:\.\d+)?)\%\s*,\s*(\d*(?:\.\d+)?)\%\s*(?:,\s*(\d*(?:\.\d+)?)\s*)?\)/,
      format: 'rgba',
      parse: function(execResult) {
        return [
          2.55 * execResult[1],
          2.55 * execResult[2],
          2.55 * execResult[3],
          execResult[4]
        ];
      }
    }, {
      re: /hsl\(\s*(\d*(?:\.\d+)?)\s*,\s*(\d*(?:\.\d+)?)\%\s*,\s*(\d*(?:\.\d+)?)\%\s*?\)/,
      format: 'hsl',
      parse: function(execResult) {
        return [
          execResult[1] / 360,
          execResult[2] / 100,
          execResult[3] / 100,
          execResult[4]
        ];
      }
    }, {
      re: /hsla\(\s*(\d*(?:\.\d+)?)\s*,\s*(\d*(?:\.\d+)?)\%\s*,\s*(\d*(?:\.\d+)?)\%\s*(?:,\s*(\d*(?:\.\d+)?)\s*)?\)/,
      format: 'hsla',
      parse: function(execResult) {
        return [
          execResult[1] / 360,
          execResult[2] / 100,
          execResult[3] / 100,
          execResult[4]
        ];
      }
    }, {
      re: /#?([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/,
      format: 'hex',
      parse: function(execResult) {
        return [
          parseInt(execResult[1], 16),
          parseInt(execResult[2], 16),
          parseInt(execResult[3], 16),
          1
        ];
      }
    }, {
      re: /#?([a-fA-F0-9])([a-fA-F0-9])([a-fA-F0-9])/,
      format: 'hex',
      parse: function(execResult) {
        return [
          parseInt(execResult[1] + execResult[1], 16),
          parseInt(execResult[2] + execResult[2], 16),
          parseInt(execResult[3] + execResult[3], 16),
          1
        ];
      }
    }],
    colorNameToHex: function(name) {
      if (typeof this.colors[name.toLowerCase()] !== 'undefined') {
        return this.colors[name.toLowerCase()];
      }
      return false;
    }
  };

  /*
   * Default plugin options
   */
  var defaults = {
    horizontal: false, // horizontal mode layout ?
    inline: false, //forces to show the colorpicker as an inline element
    color: false, //forces a color
    format: false, //forces a format
    input: 'input', // children input selector
    container: false, // container selector
    component: '.add-on, .input-group-addon', // children component selector
    fallbackColor: false, // fallback color value. null = keeps current color.
    fallbackFormat: 'hex', // fallback color format
    hexNumberSignPrefix: true, // put a '#' (number sign) before hex strings
    sliders: {
      saturation: {
        maxLeft: 100,
        maxTop: 100,
        callLeft: 'setSaturation',
        callTop: 'setBrightness'
      },
      hue: {
        maxLeft: 0,
        maxTop: 100,
        callLeft: false,
        callTop: 'setHue'
      },
      alpha: {
        maxLeft: 0,
        maxTop: 100,
        callLeft: false,
        callTop: 'setAlpha'
      }
    },
    slidersHorz: {
      saturation: {
        maxLeft: 100,
        maxTop: 100,
        callLeft: 'setSaturation',
        callTop: 'setBrightness'
      },
      hue: {
        maxLeft: 100,
        maxTop: 0,
        callLeft: 'setHue',
        callTop: false
      },
      alpha: {
        maxLeft: 100,
        maxTop: 0,
        callLeft: 'setAlpha',
        callTop: false
      }
    },
    template: '<div class="colorpicker dropdown-menu">' +
      '<div class="colorpicker-saturation"><i><b></b></i></div>' +
      '<div class="colorpicker-hue"><i></i></div>' +
      '<div class="colorpicker-alpha"><i></i></div>' +
      '<div class="colorpicker-color"><div /></div>' +
      '<div class="colorpicker-selectors"></div>' +
      '</div>',
    align: 'right',
    customClass: null, // custom class added to the colorpicker element
    colorSelectors: null // custom color aliases
  };

  /**
   * Colorpicker component class
   *
   * @param {Object|String} element
   * @param {Object} options
   * @constructor
   */
  var Colorpicker = function(element, options) {
    this.element = $(element).addClass('colorpicker-element');
    this.options = $.extend(true, {}, defaults, this.element.data(), options);
    this.component = this.options.component;
    this.component = (this.component !== false) ? this.element.find(this.component) : false;
    if (this.component && (this.component.length === 0)) {
      this.component = false;
    }
    this.container = (this.options.container === true) ? this.element : this.options.container;
    this.container = (this.container !== false) ? $(this.container) : false;

    // Is the element an input? Should we search inside for any input?
    this.input = this.element.is('input') ? this.element : (this.options.input ?
      this.element.find(this.options.input) : false);
    if (this.input && (this.input.length === 0)) {
      this.input = false;
    }
    // Set HSB color
    this.color = this.createColor(this.options.color !== false ? this.options.color : this.getValue());

    this.format = this.options.format !== false ? this.options.format : this.color.origFormat;

    if (this.options.color !== false) {
      this.updateInput(this.color);
      this.updateData(this.color);
    }

    // Setup picker
    var $picker = this.picker = $(this.options.template);
    if (this.options.customClass) {
      $picker.addClass(this.options.customClass);
    }
    if (this.options.inline) {
      $picker.addClass('colorpicker-inline colorpicker-visible');
    } else {
      $picker.addClass('colorpicker-hidden');
    }
    if (this.options.horizontal) {
      $picker.addClass('colorpicker-horizontal');
    }
    if (
      (['rgba', 'hsla', 'alias'].indexOf(this.format) !== -1) ||
      this.options.format === false ||
      this.getValue() === 'transparent'
    ) {
      $picker.addClass('colorpicker-with-alpha');
    }
    if (this.options.align === 'right') {
      $picker.addClass('colorpicker-right');
    }
    if (this.options.inline === true) {
      $picker.addClass('colorpicker-no-arrow');
    }
    if (this.options.colorSelectors) {
      var colorpicker = this,
        selectorsContainer = colorpicker.picker.find('.colorpicker-selectors');

      if (selectorsContainer.length > 0) {
        $.each(this.options.colorSelectors, function(name, color) {
          var $btn = $('<i />')
            .addClass('colorpicker-selectors-color')
            .css('background-color', color)
            .data('class', name).data('alias', name);

          $btn.on('mousedown.colorpicker touchstart.colorpicker', function(event) {
            event.preventDefault();
            colorpicker.setValue(
              colorpicker.format === 'alias' ? $(this).data('alias') : $(this).css('background-color')
            );
          });
          selectorsContainer.append($btn);
        });
        selectorsContainer.show().addClass('colorpicker-visible');
      }
    }

    // Prevent closing the colorpicker when clicking on itself
    $picker.on('mousedown.colorpicker touchstart.colorpicker', $.proxy(function(e) {
      if (e.target === e.currentTarget) {
        e.preventDefault();
      }
    }, this));

    // Bind click/tap events on the sliders
    $picker.find('.colorpicker-saturation, .colorpicker-hue, .colorpicker-alpha')
      .on('mousedown.colorpicker touchstart.colorpicker', $.proxy(this.mousedown, this));

    $picker.appendTo(this.container ? this.container : $('body'));

    // Bind other events
    if (this.input !== false) {
      this.input.on({
        'keyup.colorpicker': $.proxy(this.keyup, this)
      });
      this.input.on({
        'change.colorpicker': $.proxy(this.change, this)
      });
      if (this.component === false) {
        this.element.on({
          'focus.colorpicker': $.proxy(this.show, this)
        });
      }
      if (this.options.inline === false) {
        this.element.on({
          'focusout.colorpicker': $.proxy(this.hide, this)
        });
      }
    }

    if (this.component !== false) {
      this.component.on({
        'click.colorpicker': $.proxy(this.show, this)
      });
    }

    if ((this.input === false) && (this.component === false)) {
      this.element.on({
        'click.colorpicker': $.proxy(this.show, this)
      });
    }

    // for HTML5 input[type='color']
    if ((this.input !== false) && (this.component !== false) && (this.input.attr('type') === 'color')) {

      this.input.on({
        'click.colorpicker': $.proxy(this.show, this),
        'focus.colorpicker': $.proxy(this.show, this)
      });
    }
    this.update();

    $($.proxy(function() {
      this.element.trigger('create');
    }, this));
  };

  Colorpicker.Color = Color;

  Colorpicker.prototype = {
    constructor: Colorpicker,
    destroy: function() {
      this.picker.remove();
      this.element.removeData('colorpicker', 'color').off('.colorpicker');
      if (this.input !== false) {
        this.input.off('.colorpicker');
      }
      if (this.component !== false) {
        this.component.off('.colorpicker');
      }
      this.element.removeClass('colorpicker-element');
      this.element.trigger({
        type: 'destroy'
      });
    },
    reposition: function() {
      if (this.options.inline !== false || this.options.container) {
        return false;
      }
      var type = this.container && this.container[0] !== window.document.body ? 'position' : 'offset';
      var element = this.component || this.element;
      var offset = element[type]();
      if (this.options.align === 'right') {
        offset.left -= this.picker.outerWidth() - element.outerWidth();
      }
      this.picker.css({
        top: offset.top + element.outerHeight(),
        left: offset.left
      });
    },
    show: function(e) {
      if (this.isDisabled()) {
        // Don't show the widget if it's disabled (the input)
        return;
      }
      this.picker.addClass('colorpicker-visible').removeClass('colorpicker-hidden');
      this.reposition();
      $(window).on('resize.colorpicker', $.proxy(this.reposition, this));
      if (e && (!this.hasInput() || this.input.attr('type') === 'color')) {
        if (e.stopPropagation && e.preventDefault) {
          e.stopPropagation();
          e.preventDefault();
        }
      }
      if ((this.component || !this.input) && (this.options.inline === false)) {
        $(window.document).on({
          'mousedown.colorpicker': $.proxy(this.hide, this)
        });
      }
      this.element.trigger({
        type: 'showPicker',
        color: this.color
      });
    },
    hide: function(e) {
      if ((typeof e !== 'undefined') && e.target) {
        // Prevent hide if triggered by an event and an element inside the colorpicker has been clicked/touched
        if (
          $(e.currentTarget).parents('.colorpicker').length > 0 ||
          $(e.target).parents('.colorpicker').length > 0
        ) {
          return false;
        }
      }
      this.picker.addClass('colorpicker-hidden').removeClass('colorpicker-visible');
      $(window).off('resize.colorpicker', this.reposition);
      $(window.document).off({
        'mousedown.colorpicker': this.hide
      });
      this.update();
      this.element.trigger({
        type: 'hidePicker',
        color: this.color
      });
    },
    updateData: function(val) {
      val = val || this.color.toString(this.format, false);
      this.element.data('color', val);
      return val;
    },
    updateInput: function(val) {
      val = val || this.color.toString(this.format, false);
      if (this.input !== false) {
        this.input.prop('value', val);
        this.input.trigger('change');
      }
      return val;
    },
    updatePicker: function(val) {
      if (typeof val !== 'undefined') {
        this.color = this.createColor(val);
      }
      var sl = (this.options.horizontal === false) ? this.options.sliders : this.options.slidersHorz;
      var icns = this.picker.find('i');
      if (icns.length === 0) {
        return;
      }
      if (this.options.horizontal === false) {
        sl = this.options.sliders;
        icns.eq(1).css('top', sl.hue.maxTop * (1 - this.color.value.h)).end()
          .eq(2).css('top', sl.alpha.maxTop * (1 - this.color.value.a));
      } else {
        sl = this.options.slidersHorz;
        icns.eq(1).css('left', sl.hue.maxLeft * (1 - this.color.value.h)).end()
          .eq(2).css('left', sl.alpha.maxLeft * (1 - this.color.value.a));
      }
      icns.eq(0).css({
        'top': sl.saturation.maxTop - this.color.value.b * sl.saturation.maxTop,
        'left': this.color.value.s * sl.saturation.maxLeft
      });

      this.picker.find('.colorpicker-saturation')
        .css('backgroundColor', (this.options.hexNumberSignPrefix ? '' : '#') + this.color.toHex(this.color.value.h, 1, 1, 1));

      this.picker.find('.colorpicker-alpha')
        .css('backgroundColor', (this.options.hexNumberSignPrefix ? '' : '#') + this.color.toHex());

      this.picker.find('.colorpicker-color, .colorpicker-color div')
        .css('backgroundColor', this.color.toString(this.format, true));

      return val;
    },
    updateComponent: function(val) {
      var color;

      if (typeof val !== 'undefined') {
        color = this.createColor(val);
      } else {
        color = this.color;
      }

      if (this.component !== false) {
        var icn = this.component.find('i').eq(0);
        if (icn.length > 0) {
          icn.css({
            'backgroundColor': color.toString(this.format, true)
          });
        } else {
          this.component.css({
            'backgroundColor': color.toString(this.format, true)
          });
        }
      }

      return color.toString(this.format, false);
    },
    update: function(force) {
      var val;
      if ((this.getValue(false) !== false) || (force === true)) {
        // Update input/data only if the current value is not empty
        val = this.updateComponent();
        this.updateInput(val);
        this.updateData(val);
        this.updatePicker(); // only update picker if value is not empty
      }
      return val;

    },
    setValue: function(val) { // set color manually
      this.color = this.createColor(val);
      this.update(true);
      this.element.trigger({
        type: 'changeColor',
        color: this.color,
        value: val
      });
    },
    /**
     * Creates a new color using the instance options
     * @protected
     * @param {String} val
     * @returns {Color}
     */
    createColor: function(val) {
      return new Color(
        val ? val : null,
        this.options.colorSelectors,
        this.options.fallbackColor ? this.options.fallbackColor : this.color,
        this.options.fallbackFormat,
        this.options.hexNumberSignPrefix
      );
    },
    getValue: function(defaultValue) {
      defaultValue = (typeof defaultValue === 'undefined') ? this.options.fallbackColor : defaultValue;
      var val;
      if (this.hasInput()) {
        val = this.input.val();
      } else {
        val = this.element.data('color');
      }
      if ((val === undefined) || (val === '') || (val === null)) {
        // if not defined or empty, return default
        val = defaultValue;
      }
      return val;
    },
    hasInput: function() {
      return (this.input !== false);
    },
    isDisabled: function() {
      if (this.hasInput()) {
        return (this.input.prop('disabled') === true);
      }
      return false;
    },
    disable: function() {
      if (this.hasInput()) {
        this.input.prop('disabled', true);
        this.element.trigger({
          type: 'disable',
          color: this.color,
          value: this.getValue()
        });
        return true;
      }
      return false;
    },
    enable: function() {
      if (this.hasInput()) {
        this.input.prop('disabled', false);
        this.element.trigger({
          type: 'enable',
          color: this.color,
          value: this.getValue()
        });
        return true;
      }
      return false;
    },
    currentSlider: null,
    mousePointer: {
      left: 0,
      top: 0
    },
    mousedown: function(e) {
      if (!e.pageX && !e.pageY && e.originalEvent && e.originalEvent.touches) {
        e.pageX = e.originalEvent.touches[0].pageX;
        e.pageY = e.originalEvent.touches[0].pageY;
      }
      e.stopPropagation();
      e.preventDefault();

      var target = $(e.target);

      //detect the slider and set the limits and callbacks
      var zone = target.closest('div');
      var sl = this.options.horizontal ? this.options.slidersHorz : this.options.sliders;
      if (!zone.is('.colorpicker')) {
        if (zone.is('.colorpicker-saturation')) {
          this.currentSlider = $.extend({}, sl.saturation);
        } else if (zone.is('.colorpicker-hue')) {
          this.currentSlider = $.extend({}, sl.hue);
        } else if (zone.is('.colorpicker-alpha')) {
          this.currentSlider = $.extend({}, sl.alpha);
        } else {
          return false;
        }
        var offset = zone.offset();
        //reference to guide's style
        this.currentSlider.guide = zone.find('i')[0].style;
        this.currentSlider.left = e.pageX - offset.left;
        this.currentSlider.top = e.pageY - offset.top;
        this.mousePointer = {
          left: e.pageX,
          top: e.pageY
        };
        //trigger mousemove to move the guide to the current position
        $(window.document).on({
          'mousemove.colorpicker': $.proxy(this.mousemove, this),
          'touchmove.colorpicker': $.proxy(this.mousemove, this),
          'mouseup.colorpicker': $.proxy(this.mouseup, this),
          'touchend.colorpicker': $.proxy(this.mouseup, this)
        }).trigger('mousemove');
      }
      return false;
    },
    mousemove: function(e) {
      if (!e.pageX && !e.pageY && e.originalEvent && e.originalEvent.touches) {
        e.pageX = e.originalEvent.touches[0].pageX;
        e.pageY = e.originalEvent.touches[0].pageY;
      }
      e.stopPropagation();
      e.preventDefault();
      var left = Math.max(
        0,
        Math.min(
          this.currentSlider.maxLeft,
          this.currentSlider.left + ((e.pageX || this.mousePointer.left) - this.mousePointer.left)
        )
      );
      var top = Math.max(
        0,
        Math.min(
          this.currentSlider.maxTop,
          this.currentSlider.top + ((e.pageY || this.mousePointer.top) - this.mousePointer.top)
        )
      );
      this.currentSlider.guide.left = left + 'px';
      this.currentSlider.guide.top = top + 'px';
      if (this.currentSlider.callLeft) {
        this.color[this.currentSlider.callLeft].call(this.color, left / this.currentSlider.maxLeft);
      }
      if (this.currentSlider.callTop) {
        this.color[this.currentSlider.callTop].call(this.color, top / this.currentSlider.maxTop);
      }
      // Change format dynamically
      // Only occurs if user choose the dynamic format by
      // setting option format to false
      if (
        this.options.format === false &&
        (this.currentSlider.callTop === 'setAlpha' ||
          this.currentSlider.callLeft === 'setAlpha')
      ) {

        // Converting from hex / rgb to rgba
        if (this.color.value.a !== 1) {
          this.format = 'rgba';
          this.color.origFormat = 'rgba';
        }

        // Converting from rgba to hex
        else {
          this.format = 'hex';
          this.color.origFormat = 'hex';
        }
      }
      this.update(true);

      this.element.trigger({
        type: 'changeColor',
        color: this.color
      });
      return false;
    },
    mouseup: function(e) {
      e.stopPropagation();
      e.preventDefault();
      $(window.document).off({
        'mousemove.colorpicker': this.mousemove,
        'touchmove.colorpicker': this.mousemove,
        'mouseup.colorpicker': this.mouseup,
        'touchend.colorpicker': this.mouseup
      });
      return false;
    },
    change: function(e) {
      this.keyup(e);
    },
    keyup: function(e) {
      if ((e.keyCode === 38)) {
        if (this.color.value.a < 1) {
          this.color.value.a = Math.round((this.color.value.a + 0.01) * 100) / 100;
        }
        this.update(true);
      } else if ((e.keyCode === 40)) {
        if (this.color.value.a > 0) {
          this.color.value.a = Math.round((this.color.value.a - 0.01) * 100) / 100;
        }
        this.update(true);
      } else {
        this.color = this.createColor(this.input.val());
        // Change format dynamically
        // Only occurs if user choose the dynamic format by
        // setting option format to false
        if (this.color.origFormat && this.options.format === false) {
          this.format = this.color.origFormat;
        }
        if (this.getValue(false) !== false) {
          this.updateData();
          this.updateComponent();
          this.updatePicker();
        }
      }
      this.element.trigger({
        type: 'changeColor',
        color: this.color,
        value: this.input.val()
      });
    }
  };

  $.colorpicker = Colorpicker;

  $.fn.colorpicker = function(option) {
    var apiArgs = Array.prototype.slice.call(arguments, 1),
      isSingleElement = (this.length === 1),
      returnValue = null;

    var $jq = this.each(function() {
      var $this = $(this),
        inst = $this.data('colorpicker'),
        options = ((typeof option === 'object') ? option : {});

      if (!inst) {
        inst = new Colorpicker(this, options);
        $this.data('colorpicker', inst);
      }

      if (typeof option === 'string') {
        if ($.isFunction(inst[option])) {
          returnValue = inst[option].apply(inst, apiArgs);
        } else { // its a property ?
          if (apiArgs.length) {
            // set property
            inst[option] = apiArgs[0];
          }
          returnValue = inst[option];
        }
      } else {
        returnValue = $this;
      }
    });
    return isSingleElement ? returnValue : $jq;
  };

  $.fn.colorpicker.constructor = Colorpicker;

}));

$(function(){
    // char counter in seo title
	$("body").on("keyup keydown change", "input[name='eseo_meta_title']", { limit: 60 }, seoCharCounter);
	
	// char counter in seo description
	$("body").on("keyup keydown change", "textarea[name='eseo_meta_description']", { limit: 160 }, seoCharCounter);
});

window.preSeoCharCounter = function(){
	$("form.seoForm").each(function(){
		var from = $(this);
		var metaTitle = from.find("input[name='eseo_meta_title']");
		var metaDesc = from.find("textarea[name='eseo_meta_description']");
		metaTitle.trigger('keyup');
		metaDesc.trigger('keyup');
	});
}

// Meta Title and Description Character Counter
window.seoCharCounter = function (event){
	var charLength = $(this).val().length;
	var prevLabel = $(this).prev('label');
	var limit = event.data.limit;
	
	if( prevLabel.find('span').length ){
		
		if(charLength === 0){
			prevLabel.removeClass('limit');
			prevLabel.find('span').remove();
		}else{
			prevLabel.find('span').html('<i class="fa fa-text-width"></i>(' + charLength + ')');
			
			if( charLength > limit ){
				prevLabel.addClass('limit');
				prevLabel.find('span').addClass('limit');
			}else{
				prevLabel.removeClass('limit');
				prevLabel.find('span').removeClass('limit');
			}	
		}
	}else{
		if(charLength !== 0){
			prevLabel.append("<span class='text-counter-indicator'><i class='fa fa-text-width'></i>(" + charLength + ")</span>");
			
			if( charLength > limit ){
				prevLabel.addClass('limit');
				prevLabel.find('span').addClass('limit');
			}
		}
	}
}

var melisCommerceSeo = (function(window) {
	
	function serializeSeo(fromType, typeId, dataString){
		
		// Category Transalations
		var seoDataString = new Array;
		$("form."+fromType+"_"+typeId+"_seo_form").each(function(){
			langId = $(this).data("langid");
			
			seoDataString = $(this).serializeArray();
			
			$.each(seoDataString, function(){
				dataString.push({
					name : fromType+'_seo['+langId+']['+this.name+']',
					value: this.value
				});
			});
		});
		
		return dataString;
	}
	
	return {
		serializeSeo: serializeSeo,
	}
})(window);
$(document).ready(function() {
    var body = $("body");

    $body.on("click", ".orderPaymentCouponLink", function(){
        var couponId   = $(this).data('couponid');
        var couponName   = $(this).data('couponname');
        // Open parent tab
        melisHelper.tabOpen(translations.tr_meliscommerce_coupon_list_page, 'fa fa-ticket', 'id_meliscommerce_coupon_list_page', 'meliscommerce_coupon_list_page', {}, '');
        var alreadyOpen = $("body #melis-id-nav-bar-tabs li a.tab-element[data-id='id_meliscommerce_coupon_list_page']");
        // check if it exists
        var checkCoupons = setInterval(function() {
            if(alreadyOpen.length){
                couponTabOpen(couponName, couponId, "id_meliscommerce_coupon_list_page");
                clearInterval(checkCoupons);
            }
        }, 500);
    });

    // coupon list - refreshes the order list table
    body.on("click", ".couponListRefresh", function(){
        melisHelper.zoneReload("id_meliscommerce_coupon_list_content_table", "meliscommerce_coupon_list_content_table");
    });

    // coupon list - refreshes the order list table
    body.on("click", ".couponAssignedClientListRefresh", function(){
        var couponId = activeTabId.split("_")[0];
        melisHelper.zoneReload(couponId+"_id_meliscommerce_coupon_tabs_content_assigned_details_table", "meliscommerce_coupon_tabs_content_assigned_details_table", { couponId : couponId });
    });

    // coupon list - refreshes the coupon list table
    body.on("click", ".couponClientListRefresh", function(){
        var parentId = $(this).parents().eq(5).attr('id');;
        var meliskey = $(this).parents().eq(5).data('meliskey');
        var couponId = activeTabId.split("_")[0];
        melisHelper.zoneReload(parentId, meliskey, { couponId : couponId });
    });

    // coupon list - opens a blank coupon page for adding
    body.on("click", ".addNewCoupon", function(){
        melisHelper.tabOpen(translations.tr_meliscommerce_coupon_list_add_coupon, 'fa fa-ticket', '0_id_meliscommerce_coupon_page', 'meliscommerce_coupon_page', {}, 'id_meliscommerce_coupon_list_page');
    });

    body.on("click", ".removeCouponFromClient", function(){
        melisCoreTool.pending(this);
        var clientId = $(this).closest('tr').attr('id');
        var dataString = [];
        var ccliId = $(this).closest('td').prev().find('a').data('ccli_id');
        dataString.push({ name : 'ccli_client_id', value: clientId });
        dataString.push({ name : 'method', value : 'remove' });
        dataString.push({ name : 'ccliId', value : ccliId });
        couponManagement(dataString);
        melisCoreTool.done(this);
    });

    body.on("click", ".addCouponToClient", function(){
        melisCoreTool.pending(this);
        var clientId = $(this).closest('tr').attr('id');
        var dataString = [];
        dataString.push({ name : 'ccli_client_id', value: clientId });
        dataString.push({ name : 'method', value : 'add'});
        couponManagement(dataString);
        melisCoreTool.done(this);
    });

    function couponManagement(dataString){
        var couponId = activeTabId.split("_")[0];
        var url = 'melis/MelisCommerce/MelisComCoupon/couponClientManagement';
        dataString.push({ name: 'ccli_coupon_id', value : couponId});
        melisCommerce.postSave(url, dataString, function(data){
            if(data.success){
                melisHelper.melisOkNotification( data.textTitle, data.textMessage );
                melisHelper.zoneReload(couponId+"_id_meliscommerce_coupon_tabs_content_assign_details", "meliscommerce_coupon_tabs_content_assign_details", { couponId : couponId });;
                melisHelper.zoneReload(couponId+"_id_meliscommerce_coupon_tabs_content_assigned_details_table", "meliscommerce_coupon_tabs_content_assigned_details_table", { couponId : couponId });
            }else{
                melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
            }
            melisCore.flashMessenger();
        }, function(data){
            console.log(data);
        });
        $('#'+couponId+"_id_meliscommerce_coupon_tabs_content_assign_details").addClass('active');
    }

    body.on("click", ".saveCoupon", function(){
        melisCoreTool.pending(this);
        var couponId = activeTabId.split("_")[0];
        var forms = $(this).closest('.container-level-a').find('form');
        var url = 'melis/MelisCommerce/MelisComCoupon/saveCouponData';
        var dataString = [];
        var len;
        var ctr = 0;
        // serialize each form
        forms.each(function(){
            var i = 0;
            var pre = $(this).attr('name');
            var data = $(this).serializeArray();
            len = data.length;
            for(j=0; j<len; j++ ){
                dataString.push({  name: pre+'['+i+']['+data[j].name+']', value : data[j].value});
            }
            i++;
            ctr++;
        });
        dataString.push({name : 'couponId', value : couponId});
        // serialize each switch

        $('#'+activeTabId+' .make-switch div').each(function(){
            var field = 'switch['+$(this).find('input').attr('name')+']';
            var status = $(this).hasClass('switch-on');
            var saveStatus = 0;
            if(status) {
                saveStatus = 1;
            }
            dataString.push({
                name : field,
                value: saveStatus
            })
        });

        melisCommerce.postSave(url, dataString, function(data){
            if(data.success){
                melisHelper.tabClose(  couponId + "_id_meliscommerce_coupon_page");
                couponTabOpen(translations.tr_meliscommerce_coupon_page+' '+data.chunk.coup_code, data.chunk.couponId, "id_meliscommerce_coupon_list_page");
                melisHelper.melisOkNotification( data.textTitle, data.textMessage );
                melisHelper.zoneReload("id_meliscommerce_coupon_list_content_table", "meliscommerce_coupon_list_content_table");
            }else{

                melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
                melisCoreTool.highlightErrors(data.success, data.errors, couponId+"_id_meliscommerce_coupon_page");
                $(".couponEnd").prev("label").css("color","#686868");
                $.each( data.errors, function( key, error ) {
                    if( key == 'coup_date_valid_end'){
                        $(".couponEnd").prev("label").css("color","red");
                    }
                    if( key == 'values'){
                        $("#" + couponId+"_id_meliscommerce_coupon_page" + " .form-control[name='coup_percentage']").prev("label").css("color","red");
                        $("#" + couponId+"_id_meliscommerce_coupon_page" + " .form-control[name='coup_discount_value']").prev("label").css("color","red");
                    }
                });
            }
            melisCore.flashMessenger();
        }, function(data){
            console.log(data);
        });
        melisCoreTool.done(this);
    });

    // coupon list - opens specific order for editing
    body.on("click", ".couponInfo", function() {
        var couponId   = $(this).closest('tr').attr('id');
        var couponCode  = $(this).closest('tr').find("td:nth-child(3)").text();
        var tabName = couponId;
        if(couponCode.length > 0){
            tabName = couponCode;
        }
        couponTabOpen(translations.tr_meliscommerce_coupon_page+' '+tabName, couponId, "id_meliscommerce_coupon_list_page");
    });

    // coupon list - deletes the coupon
    body.on("click", ".couponDelete", function(){
        var couponId   = $(this).closest('tr').attr('id');
        var url = 'melis/MelisCommerce/MelisComCouponList/deleteCoupon';
        var dataString = [];
        dataString.push({
            name : 'couponId',
            value: couponId,
        });
        melisCoreTool.pending(this);

        melisCoreTool.confirm(
            translations.tr_meliscommerce_documents_common_label_yes,
            translations.tr_meliscommerce_documents_common_label_no,
            translations.tr_meliscommerce_coupon_list_page_coupon,
            translations.tr_meliscommerce_coupon_delete_confirm,
            function(){
                melisCommerce.postSave(url, dataString, function(data){
                    if(data.success){
                        melisHelper.melisOkNotification( data.textTitle, data.textMessage );
                        melisHelper.zoneReload("id_meliscommerce_coupon_list_content_table", "meliscommerce_coupon_list_content_table");
                        melisHelper.tabClose(  couponId + "_id_meliscommerce_coupon_page");
                    }else{
                        melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
                    }
                    melisCore.flashMessenger();
                }, function(data){
                    console.log(data);
                })
            });

        melisCoreTool.done(this);
    });

    // coupon - remove assigned coupon from client
    body.on("click", ".couponAssignedDelete", function(){
        var clientId   = $(this).closest('tr').attr('id');
        var couponId   = $(this).closest('tr').data('couponid');
        var url = 'melis/MelisCommerce/MelisComCoupon/deleteAssignedCoupon';
        var dataString = [];
        dataString.push({
            name : 'couponId',
            value: couponId,
        });
        dataString.push({
            name : 'clientId',
            value: clientId,
        });
        melisCoreTool.pending(this);

        melisCoreTool.confirm(
            translations.tr_meliscommerce_documents_common_label_yes,
            translations.tr_meliscommerce_documents_common_label_no,
            translations.tr_meliscommerce_coupon_list_page_coupon,
            translations.tr_meliscommerce_coupon_delete_confirm_remove,
            function(){
                melisCommerce.postSave(url, dataString, function(data){
                    if(data.success){
                        melisHelper.melisOkNotification( data.textTitle, data.textMessage );
                        melisHelper.zoneReload(couponId+"_id_meliscommerce_coupon_tabs_content_assign_details", "meliscommerce_coupon_tabs_content_assign_details", { couponId : couponId });;
                        melisHelper.zoneReload(couponId+"_id_meliscommerce_coupon_tabs_content_assigned_details_table", "meliscommerce_coupon_tabs_content_assigned_details_table", { couponId : couponId });
                    }else{
                        melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
                    }
                    melisCore.flashMessenger();
                }, function(data){
                    console.log(data);
                })
            });
        $('#'+couponId+"_id_meliscommerce_coupon_tabs_content_assign_details").addClass('active');
        melisCoreTool.done(this);
    });

    function couponTabOpen(ordername, id, tabParent){
        var navTabsGroup = tabParent;
        melisHelper.tabOpen(ordername, 'fa fa-ticket', id+'_id_meliscommerce_coupon_page', 'meliscommerce_coupon_page', { couponId : id}, navTabsGroup);
    }

    body.on("click", ".addCouponToProduct", function(){
        melisCoreTool.pending(this);
        var productId = $(this).closest('tr').attr('id');
        var dataString = [];
        dataString.push({ name : 'cprod_product_id', value: productId });
        dataString.push({ name : 'method', value : 'add'});
        couponProductManagement(dataString);
        melisCoreTool.done(this);
    });

    body.on("click", ".couponAssignedProductDelete", function(){
        melisCoreTool.pending(this);
        var productId = $(this).closest('tr').attr('id');
        var dataString = [];
        dataString.push({ name : 'cprod_product_id', value: productId });
        dataString.push({ name : 'method', value : 'remove'});
        melisCoreTool.confirm(
            translations.tr_meliscommerce_documents_common_label_yes,
            translations.tr_meliscommerce_documents_common_label_no,
            translations.tr_meliscommerce_coupon_list_page_coupon,
            translations.tr_meliscommerce_coupon_delete_confirm_remove_product,
            function(){
                couponProductManagement(dataString);
            });

        melisCoreTool.done(this);
    });

    function couponProductManagement(dataString){
        var couponId = activeTabId.split("_")[0];
        var url = 'melis/MelisCommerce/MelisComCoupon/couponProductManagement';
        dataString.push({ name: 'cprod_coupon_id', value : couponId});
        melisCommerce.postSave(url, dataString, function(data){
            if(data.success){
                melisHelper.melisOkNotification( data.textTitle, data.textMessage );
                melisHelper.zoneReload(couponId+"_id_meliscommerce_coupon_tabs_content_assign_product_details_table", "meliscommerce_coupon_tabs_content_assign_product_details_table", { couponId : couponId });;
                melisHelper.zoneReload(couponId+"_id_meliscommerce_coupon_tabs_content_assigned_product_details_table", "meliscommerce_coupon_tabs_content_assigned_product_details_table", { couponId : couponId });
            }else{
                melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
            }
            melisCore.flashMessenger();
        }, function(data){
            console.log(data);
        });
// 		$('#'+couponId+"_id_meliscommerce_coupon_tabs_content_assign_details").addClass('active');
    }
});
window.initCouponClient = function(data, tblSettings) {
    var couponId = $("#" + tblSettings.sTableId ).data("couponid");
    data.couponId = couponId;
}

window.initCouponClientTable = function() {
    $('.couponNoAddButton .addCouponToClient').remove();
    $('.couponNoAddButton .assignedClients').show()
}

window.initCouponProductTable = function() {
    $('.couponProductNoAddButton .addCouponToProduct').remove();
    $('.couponProductNoAddButton .assignedProduct').show()
}

window.initCouponOrder = function(data, tblSettings) {
    var couponId = $("#" + tblSettings.sTableId ).data("couponid");
    data.couponId = couponId;

    data.osta_id = $('#'+ couponId +'_id_meliscommerce_coupon_tabs_content_orders_details_table .orderFilterStatus').val();
    data.startDate = $('#'+couponId+'_couponOrderList').data('dStartDate');
    data.endDate   = $('#'+couponId+'_couponOrderList').data('dEndDate');
    var icon = '<i class="glyphicon glyphicon-calendar fa fa-calendar"></i> ';

    if(tblSettings.iDraw > 1) {
        dateSelectionContent = translations.tr_meliscore_datepicker_select_date  + icon + "<span class='sdate'>" + dStartDate + ' - ' + dEndDate + '</span> <b class="caret"></b>';
        $('#'+couponId+'_couponOrderList_wrapper .dt_orderdatepicker .dt_dateInfo').html(dateSelectionContent);
    }
    dStartDate = ""; dEndDate = "";
}

window.drawCouponClient = function() {

}

window.initCheckUsedCoupon = function(){
    var btnDelete = $('#tableCouponList tr.couponUsed td').find(".couponDelete");
    btnDelete.remove();
    $('.couponListNoDeleteButton .couponDelete').remove();
}

window.initCheckClientUsedCoupon = function(tblSettings){
    var btnDelete = $('tr.couponAssigned td').find(".couponAssignedDelete");
    btnDelete.remove();
}

window.initMelisCommerceCouponTbl = function(){
    $('.commerce-coupon-percent').attr('title', translations.tr_meliscommerce_coupon_list_col_percent);
    $('.commerce-coupon-value').attr('title', translations.tr_meliscommerce_coupon_list_col_money);
}


window.initMelisCouponProduct = function(data, tblSettings) {
    var couponId = $("#" + tblSettings.sTableId ).data("couponid");
    var assign = $("#" + tblSettings.sTableId ).data("assign");
    data.couponId = couponId;
    data.assign = assign;
}

$(function(){
	$('body').on("click", ".addNewOrder", function(){
		var navTabsGroup = "id_meliscommerce_order_list_page";
        melisHelper.tabOpen(translations.tr_meliscommerce_order_checkout_title, 'fa fa fa-plus fa-2x', 'id_meliscommerce_order_checkout', 'meliscommerce_order_checkout', '', navTabsGroup);
    });
	// Add event listener for opening and closing details
	// This event will create extra row on DataTable as Product Variant List container
    $('body').on('click', '.orderCheckoutProduListViewVariant', function () {
        var tr = $(this).closest('tr');
        var row = $orderCheckoutProductListTbl.row( tr );
        // Getting the product Id from the row Id
        var productId = tr.attr("id");
        
        if(row.child.isShown()){
            // This row is already open - close it
            row.child.hide();
            tr.removeClass("shown");
        }else{
        	// Extra row Attributes
        	var zoneId = productId+"_id_meliscommerce_order_checkout_product_variant_list";
        	var melisKey = "meliscommerce_order_checkout_product_variant_list";
        	// Extra row Container
        	var variantContainer = '<div class="checkout-product-variant-list" id="'+zoneId+'" melisKey="'+melisKey+'"></div>';
        	
            // Open this row
            row.child(variantContainer).show();
            tr.addClass("shown");
            
            $('.checkout-product-variant-list').attr('style','margin-bottom: 10px;');
            // Reloading the Product variant container,
            // this process will request to server for variant list depend on ProductId
            melisHelper.zoneReload(zoneId, melisKey, {productId: productId});
        }
    });
    
    // Checkout country event
    // This Process will create a Session variable on for Country Id that will use for processing Checkout
    $("body").on("change", "#orderCheckoutCountries", function(){
    	$countryId = $(this).val();
    	var dataString = new Array;
    	
    	dataString.push({
    		name : 'countryId',
    		value : $countryId
    	});
    	
    	$.ajax({
	        type        : "POST", 
	        url         : "/melis/MelisCommerce/MelisComOrderCheckout/orderCheckoutSetCountry",
	        data		: dataString,
	        dataType    : "json",
	        encode		: true
		}).done(function(data) {
			if(data.success) {
				var zoneId = "id_meliscommerce_order_checkout_content";
	        	var melisKey = "meliscommerce_order_checkout_content";
				melisHelper.zoneReload(zoneId, melisKey);
			}else{
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
			}
		}).fail(function(){
			alert( translations.tr_meliscore_error_message );
		});
    });
    
    // Adding variant to Basket List
    $('body').on('click', '.orderCheckoutVariantAddBasket', function () {
    	var variantId = $(this).data('variantid');
    	
    	var dataString = new Array;
    	
    	dataString.push({
    		name : 'var_id',
    		value : variantId
    	});
    	
    	$.ajax({
	        type        : "POST", 
	        url         : "/melis/MelisCommerce/MelisComOrderCheckout/addBasket",
	        data		: dataString,
	        dataType    : "json",
	        encode		: true
		}).done(function(data) {
			if(data.success) {
				var zoneId = "id_meliscommerce_order_checkout_product_bakset";
	        	var melisKey = "meliscommerce_order_checkout_product_bakset";
				melisHelper.zoneReload(zoneId, melisKey);
				melisHelper.melisOkNotification(data.textTitle, data.textMessage);
			}else{
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
			}
		}).fail(function(){
			alert( translations.tr_meliscore_error_message );
		});
    });
    
    // Changing the Quantity by typing the number of the quantity of the variant in Basket List
    $('body').on('change', '.orderBasketVariantQty', function () {
    	var variantId = $(this).data("variantid");
    	var varQty = parseInt($(this).data("quantity"));
    	var variantQty = parseInt($(this).val());
    	// Checking the last Variant qunatity and the New Quantity
    	if(varQty < variantQty){
    		// Adding Variant quantity
    		updateVariantbasket("add", variantId, variantQty);
    	}else{
    		// Deducting Variant quantity
    		updateVariantbasket("deduct", variantId, variantQty);
    	}
    });
    
    // Binding Variant quantity input to Numeric characters only
    $('body').on("keydown", ".orderBasketVariantQty", function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
    
    // Variant quantity + (plus) button
    // This action will add 1 (One) quantity to the Variant current quantity
    $('body').on('click', '.qty-plus', function () {
    	var variantId = $(this).data("variantid");
    	variantQty = parseInt($("#"+variantId+"_orderBasketVariantQty").val()) + 1;
    	$("#"+variantId+"_orderBasketVariantQty").val(variantQty);
    	updateVariantbasket("add", variantId, variantQty);
    });
    
    // Variant quantity - (minus) button
    // This action will deduct 1 (One) quantity to the Variant current quantity
    $('body').on('click', '.qty-minus', function () {
    	var variantId = $(this).data("variantid");
    	$varQty = $("#"+variantId+"_orderBasketVariantQty").val();
    	
    	if(parseInt($varQty) > 0){
    		variantQty = parseInt($("#"+variantId+"_orderBasketVariantQty").val()) - 1;
    		$("#"+variantId+"_orderBasketVariantQty").val(variantQty);
    		updateVariantbasket("deduct", variantId, variantQty);
    	}
    });
    
    // Checkout First step Next button
    // This action will validate if the basket has Content, else this action will show a message
    $('body').on('click', '.orderCheckoutFirstStepBtn', function () {
    	
    	$(".orderCheckoutFirstStepBtn").button("loading");
    	
    	$.ajax({
	        type        : "POST", 
	        url         : "/melis/MelisCommerce/MelisComOrderCheckout/checkBasket",
	        dataType    : "json",
	        encode		: true
		}).done(function(data) {
			if(data.success) {
				$($(".orderCheckoutFirstStepBtn").data("tabid")).tab("show");
			}else{
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
			}
			$(".orderCheckoutFirstStepBtn").button("reset");
		}).fail(function(){
			alert( translations.tr_meliscore_error_message );
			$(".#orderCheckoutFirstStepBtn").button("reset");
		});
    });
    
    // Next button event
    $('body').on('click', '.orderCheckoutNextStep', function () {
    	var body = $("html, body");
    	body.stop().animate({scrollTop:0}, '500', 'swing');
    });
    
    // Preview button, activating previews step of the checkout steps
    $('body').on('click', '.orderCheckoutPrevStep', function () {
		$($(this).data("tabid")).tab("show");
		
		if($(this).data("tabid") == '#id_meliscommerce_order_checkout_select_addresses_step_nav'){
			// Zone reload Checkout Addresses
			melisHelper.zoneReload('id_meliscommerce_order_checkout_billing_address','meliscommerce_order_checkout_billing_address')
			melisHelper.zoneReload('id_meliscommerce_order_checkout_delivery_address','meliscommerce_order_checkout_delivery_address')
		}
		
		var body = $("html, body");
		body.stop().animate({scrollTop:0}, '500', 'swing');
    });
    
    // Selecting Contact on Checkout Second Step
    $('body').on('click', '.orderCheckoutSelectContact', function () {
    	
    	var btn = $(this);
    	var tr = $(this).closest('tr');
    	// Getting the contactId from the row id attribute
    	var contactId = tr.attr("id");
    	var nxtTabid = $(this).data("tabid");
    	
    	btn.attr('disabled', true);
    	
    	var dataString = new Array;
    	
    	dataString.push({
    		name : 'contactId',
    		value : contactId
    	});
    	
    	$.ajax({
	        type        : "POST", 
	        url         : "/melis/MelisCommerce/MelisComOrderCheckout/selectContact",
	        data		: dataString,
	        dataType    : "json",
	        encode		: true
		}).done(function(data) {
			if(data.success) {
				
				$(nxtTabid).tab("show");
				
				setTimeout(function(){ 
					melisHelper.zoneReload('id_meliscommerce_order_checkout_product_bakset', 'meliscommerce_order_checkout_product_bakset');
					melisHelper.zoneReload("id_meliscommerce_order_checkout_billing_address", "meliscommerce_order_checkout_billing_address");
					melisHelper.zoneReload("id_meliscommerce_order_checkout_delivery_address", "meliscommerce_order_checkout_delivery_address");
				}, 300);
				
			}else{
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
			}
			btn.attr('disabled', false);
		}).fail(function(){
			alert( translations.tr_meliscore_error_message );
			btn.attr('disabled', false);
		});
    });
    
    // Refresh button for Contact List table
    $('body').on('click', '.orderCheckoutContactListRefresh', function () {
    	melisHelper.zoneReload("id_meliscommerce_order_checkout_choose_contact_step_content", "meliscommerce_order_checkout_choose_contact_step_content");
    });
    
    // Selecting Checkout Billing Address
    $('body').on('change', '#orderCheckoutBillingSelect', function () {
    	var clientAddId = $(this).val();
    	melisHelper.zoneReload("id_meliscommerce_order_checkout_billing_address", "meliscommerce_order_checkout_billing_address", {cadd_id: clientAddId, action: 1});
    });
    
    $("body").on("change", "#order-checkout-billing-address-same", function(){
    	if($(this).is(":checked")){
    		$("#order-checkout-billing-form-zone").addClass("hidden");
    	}else{
    		$("#order-checkout-billing-form-zone").removeClass("hidden");
    	}
    });
    
    // Selecting Checkout Delivery Address
    $('body').on('change', '#orderCheckoutDeliverySelect', function () {
    	var clientAddId = $(this).val();
    	melisHelper.zoneReload("id_meliscommerce_order_checkout_delivery_address", "meliscommerce_order_checkout_delivery_address", {cadd_id: clientAddId});
    });
    // Create new Billing Address button by clearing the Form fields for address
    $('body').on('click', '#orderCheckoutCreateBillingAdd', function () {
    	melisHelper.zoneReload("id_meliscommerce_order_checkout_billing_address", "meliscommerce_order_checkout_billing_address", {emptyBillingAddress : 1});
    });
    // Create new Billing Address button by clearing the Form fields for address
    $('body').on('click', '#orderCheckoutCreateDeliveryAdd', function () {
    	melisHelper.zoneReload("id_meliscommerce_order_checkout_delivery_address", "meliscommerce_order_checkout_delivery_address", {emptyDeliveryAddress : 1});
    });
    // Checkout Addresses validations
    $('body').on('click', '.orderCheckoutValidateAddresses', function () {
    	
    	var btn = $(this);
    	var dataString = new Array;
    	var nxtTabid = $(this).data("tabid");
    	
    	// Serializing Delivery address form
    	$("#id_meliscommerce_order_checkout_delivery_address form").each(function(){
    		var billingAddressFrom = $(this).serializeArray();
    		$.each(billingAddressFrom, function(){
    			dataString.push({
    				name: 'delivery['+this.name+']',
					value: this.value
    			});
    		});
    	});
    	
    	if($('#deliveryAddressOrderCheckoutForm').hasClass('hidden')){
    		dataString.push({
				name: 'delivery[noSelected]',
				value: true
			});
    	}
    	
    	// Serializing Billing address form
    	$("#id_meliscommerce_order_checkout_billing_address form").each(function(){
    		var billingAddressFrom = $(this).serializeArray();
    		$.each(billingAddressFrom, function(){
    			dataString.push({
    				name: 'billing['+this.name+']',
					value: this.value
    			});
    		});
    	});
    	
    	if($('#billingAddressOrderCheckoutForm').hasClass('hidden')){
    		dataString.push({
				name: 'billing[noSelected]',
				value: true
			});
    	}
    	
    	var sameAddress = 0;
    	if($("#order-checkout-billing-address-same").is(":checked")){
    		sameAddress = 1;
    	}
    	
    	dataString.push({
			name: 'billing[sameAddress]',
			value: sameAddress
		});
    	
    	btn.attr('disabled', true);
    	
    	$.ajax({
	        type        : "POST", 
	        url         : "/melis/MelisCommerce/MelisComOrderCheckout/selectAddresses",
	        data		: dataString,
	        dataType    : "json",
	        encode		: true
		}).done(function(data) {
			if(data.success) {
				$(nxtTabid).tab("show");
				
				melisHelper.zoneReload('id_meliscommerce_order_checkout_summary_basket','meliscommerce_order_checkout_summary_basket');
				melisHelper.zoneReload('id_meliscommerce_order_checkout_summary_billing_address','meliscommerce_order_checkout_summary_billing_address');
				melisHelper.zoneReload('id_meliscommerce_order_checkout_summary_delivery_address','meliscommerce_order_checkout_summary_delivery_address');
				
			}else{
				melisHelper.melisMultiKoNotification(data.textTitle, data.textMessage, data.errors);
				melisHelper.highlightMultiErrors(data.success, data.errors,  activeTabId+" form");
			}
			btn.attr('disabled', false);
		}).fail(function(){
			alert( translations.tr_meliscore_error_message );
			btn.attr('disabled', false);
		});
    	
    });
    
    // validating Coupon code
    $("body").on("click", "#orderCheckoutValidateCoupon", function(){
    	var couponCode = $("#orderCheckoutCouponCode").val();
    	if(couponCode != ''){
    		melisHelper.zoneReload("id_meliscommerce_order_checkout_summary_basket", "meliscommerce_order_checkout_summary_basket", {couponCode: couponCode});
    	}
    });
    
    // Deleting validated coupon
    $("body").on("click", ".orderValidCoupons i", function(){
    	var couponCode = $(this).closest('.orderValidCoupons').data('couponcode');
    	if(couponCode != ''){
    		melisHelper.zoneReload("id_meliscommerce_order_checkout_summary_basket", "meliscommerce_order_checkout_summary_basket", {removeCoupon: couponCode});
    	}
    });
    
    // Changing the Quantity by typing the number of the quantity of the variant in Basket List in Summary Step
    $('body').on('change', '.orderSummaryBasketVariantQty', function () {
    	var variantId = $(this).data("variantid");
    	var varQty = parseInt($(this).data("quantity"));
    	var variantQty = parseInt($(this).val());
    	// Checking the last Variant qunatity and the New Quantity
    	if(varQty < variantQty){
    		// Adding Variant quantity
    		updateSummaryVariantbasket("add", variantId, variantQty);
    	}else{
    		// Deducting Variant quantity
    		updateSummaryVariantbasket("deduct", variantId, variantQty);
    	}
    });
    
    // Binding Variant quantity input to Numeric characters only
    $('body').on("keydown", ".orderSummaryBasketVariantQty", function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
    
    // Variant quantity + (plus) button
    // This action will add 1 (One) quantity to the Variant current quantity
    $('body').on('click', '.summary-qty-plus', function () {
    	var variantId = $(this).data("variantid");
    	variantQty = parseInt($("#"+variantId+"_orderSummaryBasketVariantQty").val()) + 1;
    	$("#"+variantId+"_orderSummaryBasketVariantQty").val(variantQty);
		updateSummaryVariantbasket("add", variantId, variantQty);
    });
    
    // Variant quantity - (minus) button
    // This action will deduct 1 (One) quantity to the Variant current quantity
    $('body').on('click', '.summary-qty-minus', function () {
    	var variantId = $(this).data("variantid");
    	$varQty = $("#"+variantId+"_orderSummaryBasketVariantQty").val();
    	
    	if(parseInt($varQty) > 0){
    		variantQty = parseInt($("#"+variantId+"_orderSummaryBasketVariantQty").val()) - 1;
    		$("#"+variantId+"_orderSummaryBasketVariantQty").val(variantQty);
    		updateSummaryVariantbasket("deduct", variantId, variantQty);
    	}
    });
    // Confirming Client basket button
    $('body').on('click', '.orderCheckoutConfirmSummary', function () {
    	var btn = $(this);
    	var nxtTabid = $(this).data("tabid");
    	
    	var dataString = new Array;
    	dataString.push({
    		name : 'couponCode',
    		value : ($("#orderCheckoutCouponCode").length) ? $("#orderCheckoutCouponCode").val() : ''
    	});
    	
    	$.ajax({
	        type        : "POST", 
	        url         : "/melis/MelisCommerce/MelisComOrderCheckout/confirmOrderCheckoutSummary",
	        data		: dataString,
	        dataType    : "json",
	        encode		: true
		}).done(function(data) {
			if(data.success) {
				$(nxtTabid).tab("show");
				
				melisHelper.zoneReload("id_meliscommerce_order_checkout_payment_step_content", "meliscommerce_order_checkout_payment_step_content");
				
			}else{
				melisHelper.melisMultiKoNotification(data.textTitle, data.textMessage, data.errors);
			}
			btn.attr('disabled', false);
		}).fail(function(){
			alert( translations.tr_meliscore_error_message );
			btn.attr('disabled', false);
		});
    });
    
    $('body').on('click', '.orderCheckoutConfirmPayment', function () {
    	var btn = $(this);
    	var nxtTabid = $(this).data("tabid");
    	$(nxtTabid).tab("show");
    	
    	var zoneId = "id_meliscommerce_order_checkout_confirmation_step";
		var melisKey = "meliscommerce_order_checkout_confirmation_step";
		melisHelper.zoneReload(zoneId, melisKey, {activateTab : true});
		
		melisHelper.zoneReload('id_meliscommerce_order_list_content_table', 'meliscommerce_order_list_content_table');
    });
    
    $('body').on('change', '#orderCheckoutCouponCode', function () {
    	if($(this).val() == ''){
    		$(this).parent('.input-group').next().fadeOut('slow');
    	}
    });
});

window.productNextButtonState = function(){
	
	var nextButton = $(".orderCheckoutFirstStepBtn");
	$.ajax({
        type        : "POST", 
        url         : "/melis/MelisCommerce/MelisComOrderCheckout/checkBasket",
        dataType    : "json",
        encode		: true
	}).done(function(data) {
		if(data.success) {
			nextButton.attr('disabled', false);
			nextButton.attr('title', '');
		}else{
			nextButton.attr('disabled', true);
			nextButton.attr('title', translations.tr_meliscommerce_order_checkout_product_basket_empty);
		}
	}).fail(function(){});
}

// This method will update the Summary step basket list
window.updateSummaryVariantbasket = function(action, variantId, variantQty){
	var zoneId = "id_meliscommerce_order_checkout_summary_basket";
	var melisKey = "meliscommerce_order_checkout_summary_basket";
	
	var couponCode = $("#orderCheckoutCouponCode").val();
	if(couponCode == ''){
		couponCode = null;
	}
	melisHelper.zoneReload(zoneId, melisKey, {action: action, variantId : variantId, variantQty : variantQty, couponCode : couponCode});
	// this will also update the basket list at First step
	setTimeout(function(){ 
		melisHelper.zoneReload("id_meliscommerce_order_checkout_product_bakset", "meliscommerce_order_checkout_product_bakset");
	}, 3000);
	
}
// This method will update the Basket list at First Step
window.updateVariantbasket = function(action, variantId, variantQty){
	var zoneId = "id_meliscommerce_order_checkout_product_bakset";
	var melisKey = "meliscommerce_order_checkout_product_bakset";
	melisHelper.zoneReload(zoneId, melisKey, {action: action, variantId : variantId, variantQty : variantQty});
}

window.initCheckoutSelectContactTable = function(){
	$('.checkoutSelectContactOrderHeader').attr('title', translations.tr_meliscommerce_checkout_tbl_cper_num_orders);
}

$(function() {
	var body = $("body");
	var zoneId = "id_meliscommerce_currency_content_modal_form";
	var melisKey = 'meliscommerce_currency_content_modal_form';
	var modalUrl = '/melis/MelisCommerce/MelisComCurrency/renderCurrencyModalContainer';
	
	body.on("click", "#btnComAddCurrency", function() {
		melisCoreTool.pending("#btnComAddCurrency");
		melisHelper.createModal(zoneId, melisKey, false, {curId: null, saveType : "new"},  modalUrl, function() {
			melisCoreTool.done("#btnComAddCurrency");
		});
	});

	//removes modal elements when clicking outside
	body.on("click", function (e) {
		if ($(e.target).hasClass('modal')) {
			$('#id_meliscommerce_currency_content_modal_form_container').modal('hide');
		}
	});
	
	body.on("click", ".btnEditComCurrency", function() {
		melisCoreTool.pending(".btnEditComCurrency");
		var id = $(this).parents("tr").attr("id");
		melisHelper.createModal(zoneId, melisKey, false, {curId: id, saveType : "edit"},  modalUrl, function() {
			melisCoreTool.done(".btnEditComCurrency");
		});
	});
	
	body.on("click", "#btnComSaveCurrency", function() {
		var dataString = $("form#ecomCurrencyForm").serializeArray();
		saveType = $("form#ecomCurrencyForm").data("savetype");

		var status = $("#cur_status").parent().hasClass("switch-on");
		var saveStatus = 0;
		if(status) {
			saveStatus = 1;
		}
		dataString.push({
			name : 'cur_status',
			value: saveStatus
		});

		dataString.push({
			name : 'saveType',
			value: saveType
		});
		dataString = $.param(dataString);
		
		melisCoreTool.pending("#btnComSaveCurrency");
		melisCommerce.postSave('/melis/MelisCommerce/MelisComCurrency/save', dataString, function(data) {
			if(data.success) {
				$("div.modal").modal("hide");
				$("#" + activeTabId + " .melis-refreshTable").trigger("click");
				melisHelper.melisOkNotification(data.textTitle, data.textMessage);
			}
			else {
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
				melisCoreTool.highlightErrors(data.success, data.errors, "id_meliscommerce_currency_content_modal_form form#ecomCurrencyForm");
			}
			melisCore.flashMessenger();
			melisCoreTool.done("#btnComSaveCurrency");
		}, function() {
			melisCoreTool.done("#btnComSaveCurrency");
		});
	});
	
	body.on("click", ".btnComCurrencyDelete", function() {
		var id = $(this).parents("tr").attr("id");
		melisCoreTool.pending(".btnComCountryDelete");
		melisCoreTool.confirm(
			translations.tr_meliscore_common_yes, 
			translations.tr_meliscore_common_no, 
			translations.tr_meliscommerce_currency_delete_currency, 
			translations.tr_meliscommerce_currency_delete_confirm, 
			function() {
	    		$.ajax({
	    	        type        : 'POST', 
	    	        url         : '/melis/MelisCommerce/MelisComCurrency/delete',
	    	        data		: {id : id},
	    	        dataType    : 'json',
	    	        encode		: true,
	    	     }).success(function(data){
	    	    	 	melisCoreTool.pending(".btnComCurrencyDelete");
		    	    	if(data.success) {
		    	    		$("#" + activeTabId + " .melis-refreshTable").trigger("click");
		    	    		melisHelper.melisOkNotification(data.textTitle, data.textMessage);
		    	    	}
		    	    	else {
		    	    		melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
		    	    	}
		    	    	melisCoreTool.done(".btnComCurrencyDelete");
		    	    	melisCore.flashMessenger();
	    	     }).error(function(){
	    	    	 alert( translations.tr_meliscore_error_message );
	    	     });
		});
		melisCoreTool.done(".btnComCurrencyDelete");
	});
	
	body.on("click", ".btnComCurrencyMakeDefault", function(){
		var id = $(this).parents("tr").attr("id");
		melisCoreTool.pending(".btnComCurrencyMakeDefault");
		$.ajax({
	        type        : 'POST', 
	        url         : '/melis/MelisCommerce/MelisComCurrency/setDefaultCurrency',
	        data		: {id : id},
	        dataType    : 'json',
	        encode		: true,
	    }).success(function(data){
    	    	if(data.success) {
    	    		$("#" + activeTabId + " .melis-refreshTable").trigger("click");
    	    		melisHelper.melisOkNotification(data.textTitle, data.textMessage);
    	    	}else{
    	    		melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
    	    	}
    	    	melisCoreTool.done(".btnComCurrencyMakeDefault");
    	    	melisCore.flashMessenger();
	    }).error(function(){
	    	alert( translations.tr_meliscore_error_message );
	    	melisCoreTool.done(".btnComCurrencyMakeDefault");
	    });
	});
});

window.reInitTableEcomCurrency = function(){
	$('tr.defaultEcomCurrency').find(".btnComCurrencyDelete").remove();
	$('tr.defaultEcomCurrency').find(".btnComCurrencyMakeDefault").remove();
}
$(document).ready(function() {
    var body = $("body");

    // attribute list - refreshes the attribute list table
    body.on("click", ".attributeListRefresh", function(){
        melisHelper.zoneReload("id_meliscommerce_attribute_list_content_table", "meliscommerce_attribute_list_content_table");
    });

    //removes modal elements when clicking outside
    body.on("click", function (e) {
        if ($(e.target).hasClass('modal')) {
            $('#id_meliscommerce_attribute_value_modal_value_form_container').modal('hide');
        }
    });

    // attribute value - refreshes the attribute value table
    body.on("click", ".attributeValueRefresh", function(){
        var attributeId = activeTabId.split("_")[0];
        melisHelper.zoneReload(attributeId+"_id_meliscommerce_attributes_tabs_content_values_details_table", "meliscommerce_attributes_tabs_content_values_details_table", {attributeId: attributeId});
    });

    // attribute list - opens a blank attribute page for adding
    body.on("click", ".addNewAttribute", function(){
        var attributeId = 0;
        attributeTabOpen(translations.tr_meliscommerce_attribute_page_new_attribute, attributeId);
    });

    // attribute list - opens specific attribute for editing
    body.on("click", ".attributeInfo", function() {
        var attributeId   = $(this).closest('tr').attr('id');
        var attributeName  = $(this).closest('tr').find("td:nth-child(5)").text();
        var tabName = attributeId;
        if(attributeName.length > 0){
            tabName = attributeName;
        }
        attributeTabOpen(translations.tr_meliscommerce_attribute_page+' '+tabName, attributeId);
    });

    // attribute - opens the attribue list
    body.on("click", ".attributeHeading a", function(){
        melisHelper.tabOpen(translations.tr_meliscommerce_attribute_list_page, 'fa fa-cubes','id_meliscommerce_attribute_list_page', 'meliscommerce_attribute_list_page');
    });

    // attribute - toggles the create new value form modal
    body.on("click", ".addAttributeValue", function(){
        var attributeId = activeTabId.split("_")[0];
        melisCoreTool.pending(this);
        // initialation of local variable
        zoneId = 'id_meliscommerce_attribute_value_modal_value_form';
        melisKey = 'meliscommerce_attribute_value_modal_value_form';
        modalUrl = '/melis/MelisCommerce/MelisComAttribute/renderAttributeModal';
        // requesitng to create modal and display after
        melisHelper.createModal(zoneId, melisKey, false, {'attributeId': attributeId}, modalUrl, function(){

        });
        melisCoreTool.done(this);
    });

    // attribute - toggles the edit value form modal
    body.on("click", ".attributeValueInfo", function(){
        var attributeId = activeTabId.split("_")[0];
        var attributeValueId   = $(this).closest('tr').attr('id');
        melisCoreTool.pending(this);
        // initialation of local variable
        zoneId = 'id_meliscommerce_attribute_value_modal_value_form';
        melisKey = 'meliscommerce_attribute_value_modal_value_form';
        modalUrl = '/melis/MelisCommerce/MelisComAttribute/renderAttributeModal';
        // requesitng to create modal and display after
        melisHelper.createModal(zoneId, melisKey, false, {'attributeId': attributeId, 'attributeValueId' : attributeValueId}, modalUrl, function(){

        });
        melisCoreTool.done(this);
    });

    // attribute - deletes the attribute value
    body.on("click", ".attributeValueDelete", function(){
        var attributeId = activeTabId.split("_")[0];
        var attributeValueId   = $(this).closest('tr').attr('id');
        var url = 'melis/MelisCommerce/MelisComAttribute/deleteAttributeValue';
        var dataString = [];
        dataString.push({
            name : 'attributeValueId',
            value: attributeValueId,
        });
        melisCoreTool.pending(this);

        melisCoreTool.confirm(
            translations.tr_meliscommerce_documents_common_label_yes,
            translations.tr_meliscommerce_documents_common_label_no,
            translations.tr_meliscommerce_attribute_value_delete_title,
            translations.tr_meliscommerce_attribute_value_delete_confirm,
            function(){
                melisCommerce.postSave(url, dataString, function(data){
                    if(data.success){
                        melisHelper.melisOkNotification( data.textTitle, data.textMessage );
                        melisHelper.zoneReload(attributeId+"_id_meliscommerce_attributes_tabs_content_values_details_table", "meliscommerce_attributes_tabs_content_values_details_table", {attributeId: attributeId});
                    }else{
                        melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
                    }
                    melisCore.flashMessenger();
                }, function(data){
                    console.log(data);
                })
            });

        melisCoreTool.done(this);
    });

    // order list - saves the attribute value
    body.on("click", "#saveAttributeValue", function(){
        var attributeId = activeTabId.split("_")[0];
        var forms  = $(this).closest('#'+attributeId+'_id_meliscommerce_attribute_value_modal_value_form').find('form');
        var url = 'melis/MelisCommerce/MelisComAttribute/saveAttributeValues';
        var dataString = [];
        var len;
        var ctr = 0;

        forms.each(function(){
            var pre = $(this).attr('name');
            var data = $(this).serializeArray();
            len = data.length;
            for(j=0; j<len; j++ ){
                dataString.push({  name: pre+'['+ctr+']['+data[j].name+']', value : data[j].value});
            }
            ctr++;
        });

        dataString.push({ name: 'attributeId', value : attributeId });

        melisCoreTool.pending(this);
        melisCommerce.postSave(url, dataString, function(data){
            if(data.success){;
                melisHelper.melisOkNotification( data.textTitle, data.textMessage );
                melisHelper.zoneReload(attributeId+"_id_meliscommerce_attributes_tabs_content_values_details_table", "meliscommerce_attributes_tabs_content_values_details_table", {attributeId: attributeId});
                $("#id_meliscommerce_attribute_value_modal_value_form_container").modal("hide");
            }else{
                melisCoreTool.highlightErrors(data.success, data.errors, attributeId+"_id_meliscommerce_attribute_value_modal_value_form");
                melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
            }
            melisCore.flashMessenger();
        }, function(data){
            console.log(data);
        });
        melisCoreTool.done(this);

    });

    // attribute - saves the attribute
    body.on("click", ".attributeSave", function(){
        melisCoreTool.pending(this);

        var attributeId = activeTabId.split("_")[0];
        var forms  = $(this).closest('.container-level-a').find('form');
        var url = 'melis/MelisCommerce/MelisComAttribute/saveAttribute';
        var dataString = [];
        var len;
        var ctr = 0;

        forms.each(function(){
            //serialize disabled array, temporary remove disable
            var disabled = $(this).find(':input:disabled').removeAttr('disabled');
            var pre = $(this).attr('name');
            var data = $(this).serializeArray();
            len = data.length;
            for(j=0; j<len; j++ ){
                dataString.push({  name: pre+'['+ctr+']['+data[j].name+']', value : data[j].value});
            }
            disabled.attr('disabled','disabled');
            ctr++;
        });

        dataString.push({
            name: "attributeId",
            value: attributeId
        });

        $('#'+activeTabId+' .make-switch div').each(function(){
            var field = 'switch['+$(this).find('input').attr('name')+']';
            var status = $(this).hasClass('switch-on');
            var saveStatus = 0;
            if(status) {
                saveStatus = 1;
            }
            dataString.push({
                name : field,
                value: saveStatus
            })
        });

        melisCommerce.postSave(url, dataString, function(data){
            if(data.success){;
                melisHelper.melisOkNotification( data.textTitle, data.textMessage );
                melisHelper.tabClose(  attributeId + "_id_meliscommerce_attribute_page");
                attributeTabOpen(translations.tr_meliscommerce_attribute_page+' '+data.chunk.tabName, data.chunk.attributeId);
                melisHelper.zoneReload("id_meliscommerce_attribute_list_content_table", "meliscommerce_attribute_list_content_table");
            }else{
                melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
                melisCoreTool.highlightErrors(data.success, data.errors, attributeId+"_id_meliscommerce_attribute_page");
            }
            melisCore.flashMessenger();
        }, function(data){
            console.log(data);
        });

        melisCoreTool.done(this);
    });

    // attribute list - deletes the attribute
    body.on("click", ".attributeDelete", function(){
        var attributeId   = $(this).closest('tr').attr('id');
        var url = 'melis/MelisCommerce/MelisComAttributeList/deleteAttribute';
        var dataString = [];
        dataString.push({
            name : 'attributeId',
            value: attributeId,
        });
        melisCoreTool.pending(this);

        melisCoreTool.confirm(
            translations.tr_meliscommerce_documents_common_label_yes,
            translations.tr_meliscommerce_documents_common_label_no,
            translations.tr_meliscommerce_attribute_delete_title,
            translations.tr_meliscommerce_attribute_delete_confirm,
            function(){
                melisCommerce.postSave(url, dataString, function(data){
                    if(data.success){
                        melisHelper.melisOkNotification( data.textTitle, data.textMessage, '#72af46' );
                        melisHelper.zoneReload("id_meliscommerce_attribute_list_content_table", "meliscommerce_attribute_list_content_table");

                    }else{
                        melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors, 'closeByButtonOnly');
                    }
                    melisCore.flashMessenger();
                }, function(data){
                    console.log(data);
                })

            });

        melisCoreTool.done(this);
    });

    function attributeTabOpen(tabName, id){
        var navTabsGroup = "id_meliscommerce_attribute_list_page";

        melisHelper.tabOpen(tabName, 'fa fa-cubes', id+'_id_meliscommerce_attribute_page', 'meliscommerce_attribute_page', { attributeId : id}, navTabsGroup);
    }
});
window.initAttributeValue = function(data, tblSettings) {
    var attributeId = $("#" + tblSettings.sTableId ).data("attributeid");
    data.attributeId = attributeId;
}


$(function(){
	var body = $("body");
	var zoneId = "id_meliscommerce_language_list_page_content_modal_form";
	var melisKey = 'meliscommerce_language_list_page_content_modal_form';
	var modalUrl = '/melis/MelisCommerce/MelisComLanguage/renderLanguageListPageModalContainer';

	//removes modal elements when clicking outside
	body.on("click", function (e) {
		if ($(e.target).hasClass('modal')) {
			$('#id_meliscommerce_language_list_page_content_modal_form_container').modal('hide');
		}
	});
	
	$(document).on("submit", "form#ecomlanguageform", function(e) {
		saveType = $(this).data("savetype");
		var formData = new FormData($(this)[0]);
		var status = $("#elang_status").parent().hasClass("switch-on");
		var saveStatus = 0;
		if(status) {
			saveStatus = 1;
		}

		formData.append("elang_status", saveStatus);
		formData.append("saveType", saveType);

		melisCoreTool.pending("#btnComSaveLang");
		$.ajax({
			type : 'POST',
			url  : '/melis/MelisCommerce/MelisComLanguage/save',
			data : formData,
			processData : false,
			cache       : false,
			contentType : false,
			dataType    : 'json',
		}).done(function(data) {
			if(data.success) {
				$("div.modal").modal("hide");
				$("#" + activeTabId + " .melis-refreshTable").trigger("click");
				melisHelper.melisOkNotification(data.textTitle, data.textMessage);
			}
			else {
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
				melisCoreTool.highlightErrors(data.success, data.errors, "id_meliscommerce_language_list_page_content_modal_form form#ecomlanguageform");
			}
			melisCore.flashMessenger();
			melisCoreTool.done("#btnComSaveLang");
		}).error(function(xhr) {
			console.log(xhr);
			melisCoreTool.done("#btnComSaveLang");
		});
		e.preventDefault();
	});


	body.on("click", ".btnEcomLangDelete", function() {
		var id = $(this).parents("tr").attr("id");
		melisCoreTool.pending(".btnEcomLangDelete");
		melisCoreTool.confirm(
			translations.tr_meliscore_common_yes, 
			translations.tr_meliscore_common_no, 
			translations.tr_meliscommerce_language_delete, 
			translations.tr_meliscore_tool_language_delete_confirm, 
			function() {
	    		$.ajax({
	    	        type        : 'POST', 
	    	        url         : '/melis/MelisCommerce/MelisComLanguage/delete',
	    	        data		: {id : id},
	    	        dataType    : 'json',
	    	        encode		: true,
	    	     }).success(function(data){
	    	    	 	melisCoreTool.pending(".btnEcomLangDelete");
		    	    	if(data.success) {
		    	    		$("#" + activeTabId + " .melis-refreshTable").trigger("click");
		    	    		melisHelper.melisOkNotification(data.textTitle, data.textMessage);
		    	    	}
		    	    	else {
		    	    		melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
		    	    	}
		    	    	melisCoreTool.done(".btnEcomLangDelete");
		    	    	melisCore.flashMessenger();
	    	     }).error(function(){
	    	    	 alert( translations.tr_meliscore_error_message );
	    	     });
		});
		melisCoreTool.done(".btnEcomLangDelete");
	});
	
	body.on("click", ".btnEditComLang", function() {
		melisCoreTool.pending(".btnEditComLang");
		var id = $(this).parents("tr").attr("id");
		melisHelper.createModal(zoneId, melisKey, false, {langId: id, saveType : "edit"},  modalUrl, function() {
			melisCoreTool.done(".btnEditComLang");
		});
		
	});
	
	body.on("click", "#btnComAddLang", function() {
		melisCoreTool.pending("#btnComAddLang");
		melisHelper.createModal(zoneId, melisKey, false, {langId: null, saveType : "new"},  modalUrl, function() {
			melisCoreTool.done("#btnComAddLang");
		});
		
	});
});
$(function() {
	var body = $("body");
	var zoneId = "id_meliscommerce_country_list_page_content_modal_form";
	var melisKey = 'meliscommerce_country_list_page_content_modal_form';
	var modalUrl = '/melis/MelisCommerce/MelisComCountry/renderCountryListPageModalContainer';
	
	body.on("click", "#btnComAddCountry", function() {
		melisCoreTool.pending("#btnComAddCountry");
		melisHelper.createModal(zoneId, melisKey, false, {ctryId: null, saveType : "new"},  modalUrl, function() {
			melisCoreTool.done("#btnComAddCountry");
		});
	});

	//removes modal elements when clicking outside
	body.on("click", function (e) {
		if ($(e.target).hasClass('modal')) {
			$('#id_meliscommerce_country_list_page_content_modal_form_container').modal('hide');
		}
	});
	
	body.on("click", ".btnEditComCountry", function() {
		melisCoreTool.pending(".btnEditComCountry");
		var id = $(this).parents("tr").attr("id");
		melisHelper.createModal(zoneId, melisKey, false, {ctryId: id, saveType : "edit"},  modalUrl, function() {
			melisCoreTool.done(".btnEditComCountry");
		});
		
	});

	$(document).on("submit", "form#ecomCountryform", function(e) {
		saveType = $(this).data("savetype");
		var formData = new FormData($(this)[0]);
		var status = $("#ctry_status").parent().hasClass("switch-on");
		var saveStatus = 0;
		if(status) {
			saveStatus = 1;
		}

		formData.append("ctry_status", saveStatus);
		formData.append("saveType", saveType);

		melisCoreTool.pending("#btnComSaveCountry");
		$.ajax({
			type : 'POST',
			url  : '/melis/MelisCommerce/MelisComCountry/save',
			data : formData,
			processData : false,
			cache       : false,
			contentType : false,
			dataType    : 'json',
		}).done(function(data) {
			if(data.success) {
				$("div.modal").modal("hide");
				$("#" + activeTabId + " .melis-refreshTable").trigger("click");
				melisHelper.melisOkNotification(data.textTitle, data.textMessage);
			}
			else {
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
				melisCoreTool.highlightErrors(data.success, data.errors, "id_meliscommerce_country_list_page_content_modal_form form#ecomCountryform");
			}
			melisCore.flashMessenger();
			melisCoreTool.done("#btnComSaveCountry");
		}).error(function(xhr) {
			melisCoreTool.done("#btnComSaveCountry");
		});
		e.preventDefault();
	});
	
	body.on("click", ".btnComCountryDelete", function() {
		var id = $(this).parents("tr").attr("id");
		melisCoreTool.pending(".btnComCountryDelete");
		melisCoreTool.confirm(
			translations.tr_meliscore_common_yes, 
			translations.tr_meliscore_common_no, 
			translations.tr_meliscommerce_country_delete_country, 
			translations.tr_meliscommerce_country_delete_confirm, 
			function() {
	    		$.ajax({
	    	        type        : 'POST', 
	    	        url         : '/melis/MelisCommerce/MelisComCountry/delete',
	    	        data		: {id : id},
	    	        dataType    : 'json',
	    	        encode		: true,
	    	    }).success(function(data){
    	    	 	melisCoreTool.pending(".btnComCountryDelete");
	    	    	if(data.success) {
	    	    		$("#" + activeTabId + " .melis-refreshTable").trigger("click");
	    	    		melisHelper.melisOkNotification(data.textTitle, data.textMessage);
	    	    	}
	    	    	else {
	    	    		melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
	    	    	}
	    	    	melisCoreTool.done(".btnComCountryDelete");
	    	    	melisCore.flashMessenger();
	    	    }).error(function(){
	    	    	alert( translations.tr_meliscore_error_message );
	    	    });
		});
		melisCoreTool.done(".btnComCountryDelete");
	});
});
window.loadAssocVariantList = function(data, tblSettings) {
    // get varaint Id from table data
	var variantId = $("#" + tblSettings.sTableId ).data("variantid");
	data.variantId = variantId;
}

$(function() {
	
    $("body").on("click", ".btnAssocVAssign", function() {
    	var variantId = $(this).data("variantid");
    	var productId = $(this).data("productid");
    	var parentTable = $(this).parents('.tableAssocVariantList2');
    	var tableId = parentTable.attr("id");
    	var currentVariantId = parentTable.data("variantid");
    	var parentContainer = $(this).parents(".variant-assoc-product-variant-list");
    	
    	melisCommerce.disableAllTabs();
    	
    	$.ajax({
		    type        : 'POST',
		    url         : '/melis/MelisCommerce/MelisComAssociateVariant/assignVariant',
		    data		: {assignVariantid : variantId, assignToVariantId: currentVariantId},
		    dataType    : 'json',
		    encode		: true,
		}).success(function(data){
			
		    if(data.success) {
		        melisHelper.zoneReload(currentVariantId+"_id_meliscommerce_avar_tab_assoc_vars_list", "meliscommerce_avar_tab_assoc_vars_list", {variantId : currentVariantId});
		        if(parentContainer.length){
		    		var zoneId = parentContainer.attr("id");
		    		var melisKey = parentContainer.attr("data-melisKey");
		    		var search = $("#"+tableId+"_filter input[type='search']").val();
		    		
		        	melisHelper.zoneReload(zoneId, melisKey, {productId: productId, variantId: currentVariantId, search : search});
		    	}
		        
		        melisHelper.melisOkNotification(data.textTitle, data.textMessage );
		    
		    }else{
		        melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors );
		    }
		    
		    melisCommerce.enableAllTabs();
		    melisCore.flashMessenger();
		    
		}).error(function(){
			melisCommerce.enableAllTabs();
		    alert( translations.tr_meliscore_error_message );
		});
    });

    $("body").on("click", ".removeAssoc", function() {
        var varId = $(this).closest('tr').attr('id');
        var parentTable = $(this).parents('.tableAssocVariantList1');
        var currentVariantId = parentTable.data("variantid");
        var productId = $(this).closest('tr').attr("data-productid");
         
        melisCoreTool.pending(".removeAssociation");
        melisCommerce.disableAllTabs();
        $.ajax({
            type        : 'POST',
            url         : '/melis/MelisCommerce/MelisComAssociateVariant/removeAssociation',
            data		: {assignedVariantId : varId, variantId: currentVariantId},
            dataType    : 'json',
            encode		: true,
        }).success(function(data){

            if(data.success) {
            	
                melisHelper.zoneReload(currentVariantId+"_id_meliscommerce_avar_tab_assoc_vars_list", "meliscommerce_avar_tab_assoc_vars_list", {variantId : currentVariantId});
                
                var prdVariantsContainer = $("#"+productId+"_"+currentVariantId+"_id_meliscommerce_avar_product_variants");
                
                if(prdVariantsContainer.length){
		    		var zoneId = prdVariantsContainer.attr("id");
		    		var melisKey = prdVariantsContainer.attr("data-melisKey");
		    		var prdVariantsTableId = $(this).parents('.tableAssocVariantList2').attr("id");
		    		var search = $("#"+prdVariantsTableId+"_filter input[type='search']").val();
		        	melisHelper.zoneReload(zoneId, melisKey, {productId: productId, variantId: currentVariantId, search : search});
		    	}
                
                melisHelper.melisOkNotification(data.textTitle, data.textMessage);
            }
            else {
                melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
            }
            
            melisCoreTool.done(".removeAssociation");
            melisCore.flashMessenger();
            melisCommerce.enableAllTabs();
        }).error(function(){
            alert( translations.tr_meliscore_error_message );
        });
    });


    $("body").on("click", ".refreshVarList", function() {
        var varId = $(this).closest('.container-level-a').attr('id').replace(/[^0-9]/g,'');
        melisHelper.zoneReload(varId+"_id_meliscommerce_avar_tab_var_lists", "meliscommerce_avar_tab_var_lists", {variantId : varId});
    });

    $("body").on("click", ".refreshAssocVarList", function() {
        var varId =$(this).closest('.container-level-a').attr('id').replace(/[^0-9]/g,'');
        melisHelper.zoneReload(varId+"_id_meliscommerce_avar_tab_assoc_vars_list", "meliscommerce_avar_tab_assoc_vars_list", {variantId : varId});
    });

    $("body").on("click", ".btnAVVV1", function() {
        var productId = $(this).parents("tr").children().eq(2).find("span").data().prodId;
        var sku = $(this).parents("tr").children().eq(3).find("span").data().sku;
        melisCommerce.disableAllTabs();
        var variantId = $(this).closest('tr').attr('id');
        melisHelper.tabOpen(sku, 'icon-tag-2', variantId+'_id_meliscommerce_variants_page', 'meliscommerce_variants_page', { variantId : variantId, productId : productId});
        melisCommerce.enableAllTabs();
    });

    $("body").on("click", ".btnAVVV2", function() {
    	var sku = $(this).data("variantsku");
        var productId = $(this).data("productid");
        var variantId = $(this).data("variantid");
        melisCommerce.disableAllTabs();
        melisHelper.tabOpen(sku, 'icon-tag-2', variantId+'_id_meliscommerce_variants_page', 'meliscommerce_variants_page', { variantId : variantId, productId : productId});
        melisCommerce.enableAllTabs();
    });


    var curVarId = activeTabId.split("_")[0];
    var assocTableSearch = $("input[type='search'][aria-controls='tableAssocVariantList1_" + curVarId + "']");
    var varTableSearch   = $("input[type='search'][aria-controls='tableAssocVariantList2_" + curVarId + "']");

    assocTableSearch.keyup(function () {
        // Filter on the column (the index) of this element
        //oTable1.fnFilterAll(this.value);
    });
    //("#tableAssocVariantList2_58").fnFilterAll("test");
    
    
    // This event will create extra row on DataTable as Product Variant List container
    $('body').on('click', '.showPrdVariants', function () {
    	
    	var parentTable = $(this).parents('.tableAssocVariantList2');
    	var tableId = parentTable.attr("id");
    	var tableInstance = eval("$"+tableId);
    	var currentVariantId = parentTable.data("variantid");
    	
        var tr = $(this).closest('tr');
        var row = tableInstance.row( tr );
        
        // Getting the product Id from the row Id
        var productId = tr.attr("id");
        
        if(row.child.isShown()){
            // This row is already open - close it
            row.child.hide();
            tr.removeClass("shown");
        }else{
        	// Extra row Attributes
        	var zoneId = productId+"_"+currentVariantId+"_id_meliscommerce_avar_product_variants";
        	var melisKey = "meliscommerce_avar_product_variants";
        	// Extra row Container
        	var variantContainer = '<div class="variant-assoc-product-variant-list" id="'+zoneId+'" melisKey="'+melisKey+'" style="height:50px"></div>';
            // Open this row
            row.child(variantContainer).show();
            tr.addClass("shown");
            
            var search = $("#"+tableId+"_filter input[type='search']").val();
            
            $('.checkout-product-variant-list').attr('style','margin-bottom: 10px;');
            // Reloading the Product variant container,
            // this process will request to server for variant list depend on ProductId
            melisHelper.zoneReload(zoneId, melisKey, {productId: productId, variantId: currentVariantId, search : search});
        }
    });

});
$(function(){
    $("body").on("click", ".melisComDuplicateVariant", function(){
        var btn = $(this);
        var variantId   = btn.parents("tr").attr('id');
        btn.attr("disabled", true);

        zoneId = 'id_meliscommerce_variant_duplication';
        melisKey = 'meliscommerce_variant_duplication';
        modalUrl = '/melis/MelisCommerce/MelisComPrdVarDuplication/renderDuplicateModal';

        melisHelper.createModal(zoneId, melisKey, false, {variantId: variantId}, modalUrl, function(){
            btn.attr("disabled", false);
        });
    });

    $("body").on("click", ".melisComDuplicateProduct", function(){
        var btn = $(this);
        var productId   = btn.parents("tr").attr('id');
        btn.attr("disabled", true);

        zoneId = 'id_meliscommerce_product_duplication';
        melisKey = 'meliscommerce_product_duplication';
        modalUrl = '/melis/MelisCommerce/MelisComPrdVarDuplication/renderDuplicateModal';

        melisHelper.createModal(zoneId, melisKey, false, {productId: productId}, modalUrl, function(){
            btn.attr("disabled", false);
        });
    });

    $("body").on("click", "#melisComStartDuplicateVariant", function(){
        var btn = $(this);
        var dataString = new Array;

        $("#id_meliscommerce_variant_duplication form").each(function(){
            var var_id = $(this).find("input[name='var_id']").val();
            var tempData = $(this).serializeArray();
            $.each(tempData, function(){
                if(this.name !== 'var_id'){
                    dataString.push({
                        name : 'variantSku['+var_id+']['+this.name+']',
                        value: this.value
                    });
                }
            });
        });

        var duplicateImages = 0;
        if($('#duplicate_images').is(':checked')){
            duplicateImages = 1;
        }

        dataString.push({
            name : "duplicate_images",
            value: duplicateImages
        });

        var duplicateDocs = 0;
        if($('#duplicate_documents').is(':checked')){
            duplicateDocs = 1;
        }

        dataString.push({
            name : "duplicate_documents",
            value: duplicateDocs
        });

        var putOnline = 0;
        if($('#put_online').is(':checked')){
            putOnline = 1;
        }

        dataString.push({
            name : "var_status",
            value: putOnline
        });

        dataString.push({
            name : "duplication_type",
            value: "variant"
        });

        btn.attr('disabled', true);

        $.ajax({
            type        : "POST",
            url         : "/melis/MelisCommerce/MelisComPrdVarDuplication/duplicateVariant",
            data		: dataString,
            dataType    : "json",
            encode		: true
        }).done(function(data){

            btn.attr('disabled', false);

            if(data.success){
                $("#id_meliscommerce_variant_duplication_container").modal("hide");

                melisHelper.melisOkNotification( data.textTitle, data.textMessage, '#72af46' );
                melisHelper.zoneReload(melisCommerce.getCurrentProductId()+"_id_meliscommerce_products_page_content_tab_variant_content_container", "meliscommerce_products_page_content_tab_variant_content_container", {productId : melisCommerce.getCurrentProductId()});

            }else{
                melisSKUKoNotification(data.textTitle, data.textMessage, data.errors, 'closeByButtonOnly');
            }

            highlightSKUErrors(data.success, data.errors);
            melisCore.flashMessenger();
        }).fail(function(){
            btn.attr('disabled', false);
            alert(translations.tr_meliscore_error_message);
        });
    });

    $("body").on("click", ".main-variant-radio i", function(){
        console.log($(this).data("variantid"));

        $(this).addClass("fa-dot-circle-o");
        $(this).addClass("text-success");

        $(".main-variant-radio i").not(this).addClass("fa-circle-o");
        $(".main-variant-radio i").not(this).removeClass("fa-dot-circle-o");
        $(".main-variant-radio i").not(this).removeClass("text-success");
    })

    $("body").on("click", "#melisComStartDuplicateProduct", function(){

        var btn = $(this);
        var dataString = new Array;

        $("#id_meliscommerce_product_duplication form").each(function(){
            var var_id = $(this).find("input[name='var_id']").val();
            var tempData = $(this).serializeArray();
            $.each(tempData, function(){
                if(this.name !== 'var_id'){
                    dataString.push({
                        name : 'variantSku['+var_id+']['+this.name+']',
                        value: this.value
                    });
                }
            });
        });

        dataString.push({
            name : "product_id",
            value: btn.data("productid")
        });

        var duplicateImages = 0;
        if($('#duplicate_images').is(':checked')){
            duplicateImages = 1;
        }

        dataString.push({
            name : "duplicate_images",
            value: duplicateImages
        });

        var duplicateDocs = 0;
        if($('#duplicate_documents').is(':checked')){
            duplicateDocs = 1;
        }

        dataString.push({
            name : "duplicate_documents",
            value: duplicateDocs
        });

        var putOnline = 0;
        if($('#put_online').is(':checked')){
            putOnline = 1;
        }

        dataString.push({
            name : "prd_status",
            value: putOnline
        });

        dataString.push({
            name : "main_variant_id",
            value: $(".main-variant-radio i.fa-dot-circle-o").data("variantid")
        });

        dataString.push({
            name : "duplication_type",
            value: "product"
        });

        btn.attr('disabled', true);

        $.ajax({
            type        : "POST",
            url         : "/melis/MelisCommerce/MelisComPrdVarDuplication/duplicateProduct",
            data		: dataString,
            dataType    : "json",
            encode		: true
        }).done(function(data){

            btn.attr('disabled', false);

            if(data.success){
                $("#id_meliscommerce_product_duplication_container").modal("hide");

                melisHelper.melisOkNotification( data.textTitle, data.textMessage, '#72af46' );
                melisHelper.zoneReload("id_meliscommerce_product_list_container", "meliscommerce_product_list_container");
            }else{
                melisSKUKoNotification(data.textTitle, data.textMessage, data.errors);
            }

            highlightSKUErrors(data.success, data.errors);
            melisCore.flashMessenger();
        }).fail(function(){
            btn.attr('disabled', false);
            alert(translations.tr_meliscore_error_message);
        });
    });
});

function melisSKUKoNotification(title, message, errors, closeByButtonOnly){
    if(typeof closeByButtonOnly === "undefined") closeByButtonOnly = 'closeByButtonOnly';

    ( closeByButtonOnly !== 'closeByButtonOnly' ) ? closeByButtonOnly = 'overlay-hideonclick' : closeByButtonOnly = '';

    var errorTexts = '<h3>'+ melisHelper.melisTranslator(title) +'</h3>';
    errorTexts +='<h4>'+ melisHelper.melisTranslator(message) +'</h4>';

    $.each( errors, function( key, error ) {
        if(key !== 'label'){
            errorTexts += '<p class="modal-error-cont"><b>'+ (( errors[key]['label'] == undefined ) ? ((errors['label']== undefined) ? key : errors['label'] ) : errors[key]['label'] )+ ': </b>  ';
            // catch error level of object
            try {
                $.each( error, function( key, value ) {
                    if(key !== 'label' && key !== 'form'){

                        $errMsg = '';
                        if(value instanceof Object){
                            $errMsg = value[0];
                        }else{
                            $errMsg = value;
                        }
                        errorTexts += '<span><i class="fa fa-circle"></i>'+ $errMsg + '</span>';
                    }
                });
            } catch(Tryerror) {
                if(key !== 'label' && key !== 'form'){
                    errorTexts +=  '<span><i class="fa fa-circle"></i>'+ error + '</span>';
                }
            }
            errorTexts += '</p>';
        }
    });

    var div = "<div class='melis-modaloverlay "+ closeByButtonOnly +"'></div>";
    div += "<div class='melis-modal-cont KOnotif'>  <div class='modal-content'>"+ errorTexts +" <span class='btn btn-block btn-primary'>"+ translations.tr_meliscore_notification_modal_Close +"</span></div> </div>";
    $body.append(div);
}

function highlightSKUErrors(success, errors, divContainer){

    $(".duplicate-table-label").css("color", "inherit");

    // if all form fields are error color them red
    if(success === 0){

        $.each( errors, function( key, error ) {
            if("form" in error){
                $.each(this.form, function( fkey, fvalue ){
                    $("#" + fvalue + "_label").css("color","red");
                });
            }
        });
    }
}
$(document).ready(function() {
	var body = $("body");
	
	// add alert email recipients
	body.on('click', '.addStockAlertEmail', function() {
		var stockAlert = $(this);
		var userId = stockAlert.closest('div').find('.stockAlertSelect').val();
		var selectEmail = stockAlert.closest('div').find('.stockAlertSelect option:selected');
		var emailArea = stockAlert.closest('.email-alert-area').find('.email_area');
		var recipient = '';
		
		recipient += '<span class="alert-email-values" data-seaid="" data-userid="' + userId + '" data-alertemail="' + selectEmail.text() + '">';
		recipient += 	'<span class="ab-attr">';
		recipient += 		'<span class="alert-email-value-email">';
		recipient +=			selectEmail.text();
		recipient += 		'</span>';
		recipient += 		'<i class="alert-email-remove fa fa-times"></i>';
		recipient += 	'</span>';
		recipient += '</span>';
		
		if(userId != ''){
			selectEmail.remove();
			emailArea.append(recipient);
			stockAlert.closest('.email-alert-area').find('.noAlertRecipients').hide();
		}
		
	});
	
	// remove email recipients
	body.on('click', '.alert-email-values .alert-email-remove', function(){
		var alertEmail = $(this);
		var recipient = alertEmail.closest('.alert-email-values');
		var userId 	= recipient.data('userid');
		var email 	= recipient.data('alertemail');
		var selectEmail = alertEmail.closest('.email-alert-area').find('.stockAlertSelect');
		var selected = selectEmail.val();
		
		selectEmail.append($('<option>', {
		    value: userId,
		    text: email
		}));
		
		var my_options = alertEmail.closest('.email-alert-area').find('.stockAlertSelect option');
//		// sort the options
		my_options.sort(function(a,b) {
		    if (a.value > b.value) return 1;
		    if (a.value < b.value) return -1;
		    return 0
		})

		selectEmail.empty().append( my_options );
		selectEmail.val(selected);
		recipient.remove();
	});
	
	// saves the main settings
	body.on('click', '.saveSettings', function(){
		
		var pageContainer = $(this).closest('.container-level-a');
		
		melisCommerceSettings.submitStockAlertSettings(pageContainer);
	});
})

var melisCommerceSettings = (function(window) {
	
	function submitStockAlertSettings(pageContainer){
		
		var stockAlertForm = $(pageContainer).find('#id_meliscommerce_settings_tabs_content_main_details_left form');
		var dataString = [];
		var url = 'melis/MelisCommerce/MelisComSettings/saveSettings';
		var emails = $(pageContainer).find('.alert-email-values');
		Array.prototype.push.apply(dataString,$(stockAlertForm).serializeArray());
		
		var ctr = 0;
		var sea_id = '';
		var sea_email = '';
		var sea_user_id = '';
		
		emails.each(function(){
			
			sea_id = $(this).data('seaid');
			sea_email = $(this).data('alertemail');
			sea_user_id = $(this).data('userid');
			
			dataString.push({ name : 'recipients['+ctr+'][sea_id]', value : sea_id });
			dataString.push({ name : 'recipients['+ctr+'][sea_email]', value : sea_email });
			dataString.push({ name : 'recipients['+ctr+'][sea_user_id]', value : sea_user_id });
			ctr++
		});
		
		melisCommerce.postSave(url, dataString, function(data){
			
			if(data.success){
				
				melisHelper.tabClose("id_meliscommerce_settings_page");
				melisHelper.tabOpen(translations.tr_meliscommerce_settings, 'fa fa-wrench', 'id_meliscommerce_settings_page', 'meliscommerce_settings_page', {});
				melisHelper.melisOkNotification( data.textTitle, data.textMessage );
			}else{
				
				melisHelper.melisKoNotification(data.textTitle, data.textMessage, data.errors);
				melisCoreTool.highlightErrors(data.success, data.errors, "id_meliscommerce_settings_page");
			}	
			melisCore.flashMessenger();	
		}, function(data){
			
		});
	}
	
	return{
		submitStockAlertSettings : submitStockAlertSettings
	}
})(window);
$(document).ready(function() {
    //instance counter
    var instanceCount = 0;

    //when a filter is selected
    $("body").on("change", '.com-orders-dash-chart-line', function() {
        commerceDashboardOrdersLineGraphInit($(this));

        //get the hidden plugin config
        var $hiddenJsonConfig = $(this).closest('.grid-stack-item').find('.grid-stack-item-content .widget .widget-parent .widget-body .dashboard-plugin-json-config');
        var pluginConfig = JSON.parse($hiddenJsonConfig.text());

        //update the filter and save the plugin
        pluginConfig.activeFilter = $(this).val();
        $($hiddenJsonConfig).text(JSON.stringify(pluginConfig));
        melisDashBoardDragnDrop.saveCurrentDashboard($(this));
    });

    //opening order tab when an order is clicked
    $("body").on("click", ".melis-commerce-dashboard-plugin-orders-number-item", function(){
        var orderId = $(this).find('.melis-commerce-dashboard-plugin-orders-number-item-id').text();
        var orderReference = $(this).find('.melis-commerce-dashboard-plugin-orders-number-item-ref').text();

        // Open parent tab
        melisHelper.tabOpen(translations.tr_meliscommerce_orders_Orders, 'fa fa fa-cart-plus fa-2x', 'id_meliscommerce_order_list_page', 'meliscommerce_order_list_page');
        var alreadyOpen = $("body #melis-id-nav-bar-tabs li a.tab-element[data-id='id_meliscommerce_order_list_page']");
        // check if it exists
        var checkOrders = setInterval(function() {
            if (alreadyOpen.length) {
                var navTabsGroup = "id_meliscommerce_order_list_page";

                melisHelper.tabOpen(
                    translations.tr_meliscommerce_orders_Order+' '+orderReference,
                    'fa fa fa-cart-plus fa-2x',
                    orderId+'_id_meliscommerce_orders_page',
                    'meliscommerce_orders_page',
                    { orderId : orderId},
                    navTabsGroup);
                clearInterval(checkOrders);
            }
        }, 500);
    });

    charts.commerceDashboardOrdersLineGraph = {
        //chart data
        data: {
            d1: []
        },
        //will hold the chart object
        plot: null,
        options: {
            grid: {
                color: "#dedede",
                borderWidth: 1,
                borderColor: "#eee",
                clickable: true,
                hoverable: true,
                labelMargin: 20,
            },
            series: {
                lines: {
                    show: true,
                    fill: false,
                    lineWidth: 2,
                    steps: false
                },
                points: {
                    show:true,
                    radius: 5,
                    lineWidth: 3,
                    fill: true,
                    fillColor: "#000"
                }
            },
            xaxis: {
                // we are not using any plugin for the xaxis, we use ticks instead.
                tickColor: '#eee',
            },
            yaxis: {
                show : true,
                tickColor: '#eee',
                min: 0,
                tickDecimals: 0,
            },
            legend: {
                position: "nw",
                noColumns: 2,
                backgroundColor: null,
                backgroundOpacity: 0
            },
            shadowSize: 0,
            tooltip: true,
            tooltipOpts: {
                content: "%y %s - %x",
                shifts: {
                    x: -30,
                    y: -50
                },
                defaultTheme: false
            }
        },
        placeholder: ".commerce-dashboard-orders-chart-linegraph-placeholder",
        init: function() {
            if (this.plot == null) {
                // hook the init function for plotting the chart
                commerceDashboardOrdersLineGraphInit();
            }
        }
    };

    // INIT PLOTTING FUNCTION [also used as callback in the app.interface for when we refresh the chart]
    window.commerceDashboardOrdersLineGraphInit = function(target = null, placeholder = null) {
        var chartFor = "";

        if (target === null) {
            var chartsArray = $body.find(".commerce-dashboard-orders-chart-linegraph-placeholder");
            var emptyChartCount = 0;

            //count the number of empty charts
            $body.find(".commerce-dashboard-orders-chart-linegraph-placeholder").each(function(index, value) {
                if($(this).text() == "") {
                    emptyChartCount++;
                }
            });

            if (emptyChartCount == $body.find(".commerce-dashboard-orders-chart-linegraph-placeholder").length) {
                //when count of empty charts is equal to the count of charts then it mean the tab is closed and opened again.
                var pluginConfig = $(chartsArray[instanceCount]).closest('.grid-stack-item').find('.grid-stack-item-content .widget .widget-parent .widget-body .dashboard-plugin-json-config').text();
                instanceCount++;

                if (instanceCount == chartsArray.length) {
                    instanceCount = 0;
                }

                chartFor = JSON.parse(pluginConfig).activeFilter;
                placeholder = "#commerce-dashboard-orders-chart-linegraph-placeholder-" + JSON.parse(pluginConfig).plugin_id;
            } else {
                //when a new plugin is dragged to the grid stack
                var lastItem = body.find(".commerce-dashboard-orders-chart-linegraph-placeholder").length - 1;
                var pluginConfig = $(chartsArray[lastItem]).closest('.grid-stack-item').find('.grid-stack-item-content .widget .widget-parent .widget-body .dashboard-plugin-json-config').text();

                chartFor = JSON.parse(pluginConfig).activeFilter;
                placeholder = "#commerce-dashboard-orders-chart-linegraph-placeholder-" + JSON.parse(pluginConfig).plugin_id;
            }
        } else if (typeof target == "string") {
            //when initializing the charts on the first load of dashboard
            chartFor = target;
            placeholder = "#commerce-dashboard-orders-chart-linegraph-placeholder-" + placeholder;
        } else {
            //when filter is selected
            chartFor = target.val();
            placeholder = "#"+target.closest(".tab-pane").find(".commerce-dashboard-orders-chart-linegraph-placeholder").attr("id");
        }

        // get the orders data
        $.ajax({
            type        : 'POST',
            url         : '/melis/dashboard-plugin/MelisCommerceDashboardPluginOrdersNumber/getDashboardOrdersData',
            data		: {chartFor : chartFor},
            dataType 	: 'json',
            encode		: true
        }).success(function(data){
            var finalData = [];
            var tick = [];
            var counter = data.values.length;
            var window_width = $(window).width();

            for (var i = 0; i < data.values.length ; i++) {
                if (chartFor == 'hourly') {
                    // displays the hour only
                    var dataString  = moment(data.values[i][0], 'YYYY-MM-DD HH').format('HH');
                } else if(chartFor == 'daily') {
                    var date = moment(data.values[i][0], 'YYYY-MM-DD');
                    // displays month name in 3 letters and the day is in another line
                    var dataString = date.format("MMM") + '\n' + date.format("DD");
                } else if (chartFor == 'weekly') {
                    var week = moment(data.values[i][0], 'YYYY-MM-DD').format('W');
                    var weekday = moment().day("Monday").week(week);
                    // displays month name in 3 letters
                    var dataString = weekday.format("MMM") + "\n" + weekday.format("DD");
                } else if (chartFor == 'monthly') {
                    // displays month name in 3 letters
                    var dataString = moment(data.values[i][0], 'YYYY-MM-DD').format("MMM");
                }

                //pushing the data to the finalData which will be used in the charts
                finalData.push([ counter ,  data.values[i][1]]);

                //when chart is for weekly we need to use ticks to display strings in x axis.
                tick.push([counter, dataString]);
                counter--;
            }

            charts.commerceDashboardOrdersLineGraph.options.xaxis.ticks = tick;
            charts.commerceDashboardOrdersLineGraph.options.tooltipOpts.content = "%y %s";

            charts.commerceDashboardOrdersLineGraph.plot = $.plot(
                $(placeholder),
                [{
                    label: translations.tr_melis_commerce_dashboard_plugin_orders_number,
                    data: finalData,
                    color: "#3997d4",
                    lines: { fill: 0.2 },
                    points: { fillColor: "#fff"}
                }],
                charts.commerceDashboardOrdersLineGraph.options
            );

        }).error(function(xhr, textStatus, errorThrown){
            console.log("ERROR !! Status = "+ textStatus + "\n Error = "+ errorThrown + "\n xhr = "+ xhr.statusText);
        });
    }

    //initialize all the charts on the dashboard on first load of dashboard.
    $body.find(".commerce-dashboard-orders-chart-linegraph-placeholder").each(function(index, value){
        var pluginConfig = $(value).closest('.grid-stack-item').find('.grid-stack-item-content .widget .widget-parent .widget-body .dashboard-plugin-json-config').text();
        var filter = JSON.parse(pluginConfig).activeFilter;
        var placeholder = JSON.parse(pluginConfig).plugin_id;

        commerceDashboardOrdersLineGraphInit(filter, placeholder);
    });
});
$(document).ready(function() {
    //instance counter
    var instanceCount = 0;

    //when a filter is selected
    $("body").on("change", '.commerce-dashboard-plugin-sales-revenue', function() {
        commerceDashboardPluginSalesRevenueChartStackedBarsInit($(this));

        //get the hidden plugin config
        var $hiddenJsonConfig = $(this).closest('.grid-stack-item').find('.grid-stack-item-content .widget .widget-parent .widget-body .dashboard-plugin-json-config');
        var pluginConfig = JSON.parse($hiddenJsonConfig.text());

        //update the filter and save the plugin
        pluginConfig.activeFilter = $(this).val();
        $($hiddenJsonConfig).text(JSON.stringify(pluginConfig));
        melisDashBoardDragnDrop.saveCurrentDashboard($(this));
    });

    charts.commerceDashboardPluginSalesRevenueChartStackedBars = {
        //chart data
        data: null,
        //will hold the chart object
        plot: null,
        options: {
            grid: {
                color: "#dedede",
                borderWidth: 1,
                borderColor: "transparent",
                clickable: true,
                hoverable: true,
                backgroundColor: {
                    colors: [
                        "#fff", "#fff"
                    ],
                },
            },
            series: {
                stack: true,
                grow: {
                    active: false,
                },
                bars: {
                    show: true,
                    barWidth: 0.5,
                    fill: 1,
                    align: 'center',
                },
            },
            xaxis: {
                // we are not using any plugin for the xaxis, we use ticks instead.
            },
            yaxis: {
                min: 0,
                tickDecimals: 0,
            },
            legend: {
                position: "ne",
                backgroundColor: null,
                backgroundOpacity: 0,
                noColumns: 2,
            },
            colors: [
                "#7acc66",
                "#66cccc",
            ],
            shadowSize: 0,
            tooltip: true,
            tooltipOpts: {
                content: "%s : %y",
                shifts: {
                    x: -30,
                    y: -50
                },
                defaultTheme: false
            }
        },
        placeholder: ".commerce-dashboard-plugin-sales-revenue-placeholder",
        // initialize
        init: function() {
            if (this.plot == null) {
                // hook the init function for plotting the chart
                commerceDashboardPluginSalesRevenueChartStackedBarsInit();
            }
        }
    };

    window.commerceDashboardPluginSalesRevenueChartStackedBarsInit = function(target = null, placeholder = null){
        var chartFor = "";

        if (target == null) {
            var chartsArray = $body.find(".commerce-dashboard-plugin-sales-revenue-placeholder");
            var emptyChartCount = 0;

            //count the number of empty charts
            $body.find(".commerce-dashboard-plugin-sales-revenue-placeholder").each(function(index, value) {
                if($(this).text() == "") {
                    emptyChartCount++;
                }
            });

            if (emptyChartCount == $body.find(".commerce-dashboard-plugin-sales-revenue-placeholder").length) {
                //when count of empty charts is equal to the count of charts then it mean the tab is closed and opened again.
                var pluginConfig = $(chartsArray[instanceCount]).closest('.grid-stack-item').find('.grid-stack-item-content .widget .widget-parent .widget-body .dashboard-plugin-json-config').text();
                instanceCount++;

                if (instanceCount == chartsArray.length) {
                    instanceCount = 0;
                }

                chartFor = JSON.parse(pluginConfig).activeFilter;
                placeholder = "#commerce-dashboard-plugin-sales-revenue-placeholder-" + JSON.parse(pluginConfig).plugin_id;
            } else {
                //when a new plugin is dragged to the grid stack
                var lastItem = body.find(".commerce-dashboard-plugin-sales-revenue-placeholder").length - 1;
                var pluginConfig = $(chartsArray[lastItem]).closest('.grid-stack-item').find('.grid-stack-item-content .widget .widget-parent .widget-body .dashboard-plugin-json-config').text();

                chartFor = JSON.parse(pluginConfig).activeFilter;
                placeholder = "#commerce-dashboard-plugin-sales-revenue-placeholder-" + JSON.parse(pluginConfig).plugin_id;
            }
        } else if (typeof target === "string") {
            //when initializing the charts on first load of dashboard
            chartFor = target;
            placeholder = "#commerce-dashboard-plugin-sales-revenue-placeholder-"+placeholder;
        } else {
            //when a filter is selected
            chartFor = target.val();
            placeholder = "#"+target.closest(".tab-pane").find(".commerce-dashboard-plugin-sales-revenue-placeholder").attr("id");
        }

        $.ajax({
            type        : 'POST',
            url         : '/melis/dashboard-plugin/MelisCommerceDashboardPluginSalesRevenue/getDashboardSalesRevenueData',
            data        : {chartFor : chartFor},
            dataType    : 'json',
            encode      : true
        }).success(function(data){
            // for total order price.
            var data1 = [];
            // for total shipping fee.
            var data2 = [];
            var ticks = [];
            var counter = data.values.length;
            var window_width = $(window).width();

            //the first value of the data.values is the current date / time.
            for (var i = 0; i < data.values.length; i++) {
                if (chartFor == 'hourly') {
                    // displays the hour only
                    var dataString  = moment(data.values[i][0], 'YYYY-MM-DD HH').format('HH');
                } else if(chartFor == 'daily') {
                    var date = moment(data.values[i][0], 'YYYY-MM-DD');
                    // displays month name in 3 letters and the day is in another line
                    var dataString = date.format("MMM") + '\n' + date.format("DD");
                } else if (chartFor == 'weekly') {
                    var week = moment(data.values[i][0], 'YYYY-MM-DD').format('W');
                    var weekday = moment().day("Monday").week(week);
                    // displays month name in 3 letters
                    var dataString = weekday.format("MMM") + "\n" + weekday.format("DD");
                } else if (chartFor == 'monthly') {
                    // displays month name in 3 letters
                    var dataString = moment(data.values[i][0], 'YYYY-MM-DD').format("MMM");
                }

                /*
                 *  first parameter is for the xaxis. second param is the total order price/ shipping fee.
                 *  we use counter so that the date / time would be ordered from left to right. The time / date
                 *  on the right most is the current date / time.
                 */
                data1.push([counter, data.values[i][1]]);
                data2.push([counter, data.values[i][2]]);
                /*
                 *   first parameter is for the data identifier.
                 *   Example: data[0,1000] ticks[0,'June 11 2018']
                 *   y axis = 1000 and x axis = June 11 2018
                 *   the counter is the identifier for the x and y axis.
                 */
                ticks.push([counter, dataString]);
                counter--;
            }
            //insert the ticks to the charts object
            charts.commerceDashboardPluginSalesRevenueChartStackedBars.options.xaxis.ticks = ticks;
            //chart data
            charts.commerceDashboardPluginSalesRevenueChartStackedBars.data = [];
            charts.commerceDashboardPluginSalesRevenueChartStackedBars.data.push({
                label: translations.tr_melis_commerce_dashboard_plugin_sales_revenue_order_price,
                data: data1
            });
            charts.commerceDashboardPluginSalesRevenueChartStackedBars.data.push({
                label: translations.tr_melis_commerce_dashboard_plugin_sales_revenue_shipping_price,
                data: data2
            });
            //plot the chart
            charts.commerceDashboardPluginSalesRevenueChartStackedBars.plot = $.plot(
                $(placeholder),
                charts.commerceDashboardPluginSalesRevenueChartStackedBars.data,
                charts.commerceDashboardPluginSalesRevenueChartStackedBars.options
            );
        }).error(function(xhr, textStatus, errorThrown){
            console.log("ERROR !! Status = "+ textStatus + "\n Error = "+ errorThrown + "\n xhr = "+ xhr.statusText);
        });
    }

    //initialize all the charts on the dashboard on first load of dashboard.
    $body.find(".commerce-dashboard-plugin-sales-revenue-placeholder").each(function(index, value){
        var pluginConfig = $(value).closest('.grid-stack-item').find('.grid-stack-item-content .widget .widget-parent .widget-body .dashboard-plugin-json-config').text();
        var filter = JSON.parse(pluginConfig).activeFilter;
        var placeholder = JSON.parse(pluginConfig).plugin_id;

        commerceDashboardPluginSalesRevenueChartStackedBarsInit(filter, placeholder);
    });
});
var commerceDashPluginOrderMessagesAllMessagesInterval = '';
var commerceDashPluginOrderMessagesUnseenMessagesInterval = '';
var commerceDashPluginorderMessagesInstanceCount = 0;
var commDashPluginOrderMessagesWithUnansweredFilterInstance = 0;

$(document).ready(function() {
    var placeholder = '';
    var messagecountplaceholder = '';
    var intervalDelay = 60000;

    // changing filter for charts
    $("body").on("change", '.commerce-dashboard-plugin-order-messages', function() {
        commerceDashboardPluginOrderMessagesInit($(this));
    });

    //opening the message in orders
    $("body").on("click", ".commerce-dashboard-plugin-order-messages", function(){
        orderMessages.openOrderMessages(this);
    });

    window.commerceDashboardPluginOrderMessagesInit = function(target){
        var filter = '';
        messagecountplaceholder = '.message-count';
        if (typeof target === "undefined") {
            //first load or when using "all" filter
            filter = 'all';
            placeholder = '.commerce-dashboard-plugin-order-messages-list';
        } else {
            //when "unanswered" filter is used
            filter = target.val();
            placeholder = "#"+target.closest(".melis-commerce-dashboard-plugin-order-messages-parent").find(".commerce-dashboard-plugin-order-messages-list").attr("id");
        }

        commDashPluginOrderMessagesWithUnansweredFilterInstance = $(".melis-commerce-dashboard-plugin-order-messages-parent").find('label.active input[value="unseen"]').length;
        commerceDashPluginorderMessagesInstanceCount = $(".melis-commerce-dashboard-plugin-order-messages-parent").find('label.active input[value="all"]').length;
        appendMessages(filter);

        if (commDashPluginOrderMessagesWithUnansweredFilterInstance == 0) {
            clearInterval(commerceDashPluginOrderMessagesUnseenMessagesInterval);
            commerceDashPluginOrderMessagesUnseenMessagesInterval = '';
        }

        if (commerceDashPluginorderMessagesInstanceCount == 0) {
            clearInterval(commerceDashPluginOrderMessagesAllMessagesInterval);
            commerceDashPluginOrderMessagesAllMessagesInterval = '';
        }

        if (filter == 'all') {
            if (commerceDashPluginOrderMessagesAllMessagesInterval == '') {
                commerceDashPluginOrderMessagesAllMessagesInterval = setInterval(appendMessages, intervalDelay, filter);
            }
        } else {
            if (commerceDashPluginOrderMessagesUnseenMessagesInterval == '') {
                commerceDashPluginOrderMessagesUnseenMessagesInterval = setInterval(appendMessages, intervalDelay, filter);
            }
        }
    }

    //initialize the order messages that are already in the dashboard
    if ($('.commerce-dashboard-plugin-order-messages-list').length > 0) {
        commerceDashboardPluginOrderMessagesInit();
    }

    function appendMessages(filter) {
        $.ajax({
            type        : 'POST',
            url         : '/melis/dashboard-plugin/MelisCommerceDashboardPluginOrderMessages/getMessages',
            data		: {filter : filter},
            dataType 	: 'json',
            encode		: true
        }).success(function(data) {
            //empty divs first
            $(".melis-commerce-dashboard-plugin-order-messages-parent").find('label.active input[value=' + '"' + filter + '"' +']').each(function(index, element) {
                orderMessages.clear(element);
                orderMessages.setUnansweredMessages(data.unansweredMessages, element);
            });

            $.each(data.messages, function(index, message){
                orderMessages.setMessages(placeholder, message, filter);
            });
        }).error(function(xhr, textStatus, errorThrown) {
            console.log("ERROR !! Status = "+ textStatus + "\n Error = "+ errorThrown + "\n xhr = "+ xhr.statusText);
        });
    }

    var orderMessages = {
        openOrderMessages: function(element) {
            var orderId = $(element).find('.order-message-id').val();
            var orderReference = $(element).find('.order-message-reference').val();

            // Open parent tab
            melisHelper.tabOpen(
                translations.tr_meliscommerce_orders_Orders,
                'fa fa fa-cart-plus fa-2x',
                'id_meliscommerce_order_list_page',
                'meliscommerce_order_list_page'
            );

            var alreadyOpen = $("body #melis-id-nav-bar-tabs li a.tab-element[data-id='id_meliscommerce_order_list_page']");
            // check if it exists
            var checkOrders = setInterval(function() {
                if (alreadyOpen.length) {
                    var navTabsGroup = "id_meliscommerce_order_list_page";

                    melisHelper.tabOpen(
                        translations.tr_meliscommerce_orders_Order+' '+orderReference,
                        'fa fa fa-cart-plus fa-2x',
                        orderId+'_id_meliscommerce_orders_page',
                        'meliscommerce_orders_page',
                        { orderId : orderId},
                        navTabsGroup,
                        function() {
                            //JS CALLBACK FOR THE ORDER MESSAGES
                            //TO OPEN THE MESSAGE TAB OF A SPECIFIC ORDER
                            var parent = orderId + '_id_meliscommerce_orders_content_tabs';

                            $('#' + parent).find('.widget-head').find('ul').find('li').each(function(){
                                if ($(this).find('a').attr('href') == "#" + orderId + "_id_meliscommerce_orders_content_tabs_content_messages") {
                                    $(this).addClass("active");
                                } else {
                                    if($(this).hasClass("active")) {
                                        $(this).removeClass("active");
                                    }
                                }
                            });

                            var parent = orderId + '_id_meliscommerce_orders_content_tabs_content';

                            $('#'+parent).find('.tab-pane').each( function(){
                                if ($(this).attr('id') == orderId + "_id_meliscommerce_orders_content_tabs_content_messages") {
                                    $(this).addClass("active");
                                } else {
                                    if ($(this).hasClass("active")) {
                                        $(this).removeClass("active");
                                    }
                                }
                            });
                        });
                    clearInterval(checkOrders);
                }
            }, 500);
        },
        clear: function(element) {
            $(element).closest('.melis-commerce-dashboard-plugin-order-messages-parent').find('.commerce-dashboard-plugin-order-messages-list').empty();
            $(element).closest('.melis-commerce-dashboard-plugin-order-messages-parent').find('.message-count').empty();
        },
        setUnansweredMessages: function(unansweredMessages, element) {
            var message = '';
            var newMessage = '';

            if (unansweredMessages > 1) {
                message = translations.tr_melis_commerce_dashboard_plugin_order_messages_unanswered_messages;
                newMessage = message.replace("%d", unansweredMessages);
            } else {
                message = translations.tr_melis_commerce_dashboard_plugin_order_messages_unanswered_messages;
                newMessage = message.replace("messages", "message").replace("%d", unansweredMessages);
            }

            $(element).closest('.melis-commerce-dashboard-plugin-order-messages-parent').find(messagecountplaceholder).append(newMessage);
        },
        setMessages: function(placeholder, message, filter) {
            var colorRed = '';
            var bgColorRed = '';
            var classStrong = '';
            var text = message.omsg_message.length > 70 ? message.omsg_message.substring(0, 70) + '...' : message.omsg_message;
            var message_created = moment(message.omsg_date_creation, "YYYY-MM-DD HH:mm:ss");

            if (message.noReply) {
                colorRed = 'style="color: #981a1f;"';
                classStrong = 'strong';
                bgColorRed = 'style="background-color: #981a1f;"';
            }

            var dateHtml = '<span class="label label-inverse pull-right" ' + bgColorRed + '>' +
                                message_created.format("HH:mm:ss DD MMM") +
                            '</span>';

            var doubleArrow = '<i class="fa fa-angle-double-right" ' + colorRed + '<i/>';

            var nameHtml =  '<span class="' + classStrong + '" ' + colorRed + '>' +
                                message.clientFirstName + ' ' + message.clientLastName +
                            '</span> ' + doubleArrow +
                            ' <small class="' + classStrong + '" ' + colorRed + '>' +
                                translations.tr_melis_commerce_dashboard_plugin_order_messages_message_order_amount + message.totalOrderAmount +
                            '</small> ' + doubleArrow +
                            ' <small class="' + classStrong + '" ' + colorRed + '>' +
                                translations.tr_melis_commerce_dashboard_plugin_order_messages_message_placed_on + message.orderDate +
                            '</small>';

            var messageHtml =  '<a href="#" class="list-group-item commerce-dashboard-plugin-order-messages" ' +
                                'style="border-radius: 0px;border-top: 0px;border-right: 0px;border-left:0px;margin-bottom: 0px;">' +
                                '  <input class="order-message-id" type="text" value="' + message.omsg_order_id + '" hidden="hidden">' +
                                '  <input class="order-message-reference" type="text" value="' + message.reference + '" hidden="hidden">' +
                                '  <span class="media">' +
                                '    <span class="media-body media-body-inline">' +
                                     dateHtml +
                                     nameHtml +
                                '    <p class="list-group-item-text" style="font-size:12px;"> ' +
                                      text + ' ' +
                                '    </p>' +
                                '    </span>' +
                                '  </span>' +
                                '</a>';

            $(".melis-commerce-dashboard-plugin-order-messages-parent").find('label.active input[value=' + '"' + filter + '"' +']').each(function(index, element) {
                    $(element).closest('.melis-commerce-dashboard-plugin-order-messages-parent').find('.commerce-dashboard-plugin-order-messages-list').append(messageHtml);
            });
        }
    };
});

//delete callback if there is only one plugin and it is deleted the interval will be cleared
function commerceDasboardPluginOrderMessagesDelete(element) {
    if (element.find(".melis-commerce-dashboard-plugin-order-messages-parent").length == 1) {
        if (element.find(".melis-commerce-dashboard-plugin-order-messages-parent label.active input[value='all']").length > 0) {
            commerceDashPluginorderMessagesInstanceCount--;
            if (commerceDashPluginorderMessagesInstanceCount == 0) {
                clearInterval(commerceDashPluginOrderMessagesAllMessagesInterval);
                commerceDashPluginOrderMessagesAllMessagesInterval = '';
            }
        } else {
            commDashPluginOrderMessagesWithUnansweredFilterInstance--;
            if (commDashPluginOrderMessagesWithUnansweredFilterInstance == 0) {
                clearInterval(commerceDashPluginOrderMessagesUnseenMessagesInterval);
                commerceDashPluginOrderMessagesUnseenMessagesInterval = '';
            }
        }
    }
}