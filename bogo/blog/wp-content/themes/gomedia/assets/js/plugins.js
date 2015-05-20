// Avoid `console` errors in browsers that lack a console.
(function() {
    var method;
    var noop = function () {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());

/*global jQuery */
/*jshint browser:true */
/*!
* FitVids 1.1
*
* Copyright 2013, Chris Coyier - http://css-tricks.com + Dave Rupert - http://daverupert.com
* Credit to Thierry Koblentz - http://www.alistapart.com/articles/creating-intrinsic-ratios-for-video/
* Released under the WTFPL license - http://sam.zoy.org/wtfpl/
*
*/

(function( $ ){

  "use strict";

  $.fn.fitVids = function( options ) {
    var settings = {
      customSelector: null,
      ignore: null,
    };

    if(!document.getElementById('fit-vids-style')) {
      // appendStyles: https://github.com/toddmotto/fluidvids/blob/master/dist/fluidvids.js
      var head = document.head || document.getElementsByTagName('head')[0];
      var css = '.fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}';
      var div = document.createElement('div');
      div.innerHTML = '<p>x</p><style id="fit-vids-style">' + css + '</style>';
      head.appendChild(div.childNodes[1]);
    }

    if ( options ) {
      $.extend( settings, options );
    }

    return this.each(function(){
      var selectors = [
        "iframe[src*='player.vimeo.com']",
        "iframe[src*='youtube.com']",
        "iframe[src*='youtube-nocookie.com']",
        "iframe[src*='kickstarter.com'][src*='video.html']",
        "object",
        "embed"
      ];

      if (settings.customSelector) {
        selectors.push(settings.customSelector);
      }

      var ignoreList = '.fitvidsignore';

      if(settings.ignore) {
        ignoreList = ignoreList + ', ' + settings.ignore;
      }

      var $allVideos = $(this).find(selectors.join(','));
      $allVideos = $allVideos.not("object object"); // SwfObj conflict patch
      $allVideos = $allVideos.not(ignoreList); // Disable FitVids on this video.

      $allVideos.each(function(){
        var $this = $(this);
        if($this.parents(ignoreList).length > 0) {
          return; // Disable FitVids on this video.
        }
        if (this.tagName.toLowerCase() === 'embed' && $this.parent('object').length || $this.parent('.fluid-width-video-wrapper').length) { return; }
        if ((!$this.css('height') && !$this.css('width')) && (isNaN($this.attr('height')) || isNaN($this.attr('width'))))
        {
          $this.attr('height', 9);
          $this.attr('width', 16);
        }
        var height = ( this.tagName.toLowerCase() === 'object' || ($this.attr('height') && !isNaN(parseInt($this.attr('height'), 10))) ) ? parseInt($this.attr('height'), 10) : $this.height(),
            width = !isNaN(parseInt($this.attr('width'), 10)) ? parseInt($this.attr('width'), 10) : $this.width(),
            aspectRatio = height / width;
        if(!$this.attr('id')){
          var videoID = 'fitvid' + Math.floor(Math.random()*999999);
          $this.attr('id', videoID);
        }
        $this.wrap('<div class="fluid-width-video-wrapper"></div>').parent('.fluid-width-video-wrapper').css('padding-top', (aspectRatio * 100)+"%");
        $this.removeAttr('height').removeAttr('width');
      });
    });
  };
// Works with either jQuery or Zepto
})( window.jQuery || window.Zepto );

/**
 * hoverIntent is similar to jQuery's built-in "hover" method except that
 * instead of firing the handlerIn function immediately, hoverIntent checks
 * to see if the user's mouse has slowed down (beneath the sensitivity
 * threshold) before firing the event. The handlerOut function is only
 * called after a matching handlerIn.
 *
 * hoverIntent r7 // 2013.03.11 // jQuery 1.9.1+
 * http://cherne.net/brian/resources/jquery.hoverIntent.html
 *
 * You may use hoverIntent under the terms of the MIT license. Basically that
 * means you are free to use hoverIntent as long as this header is left intact.
 * Copyright 2007, 2013 Brian Cherne
 *
 * // basic usage ... just like .hover()
 * .hoverIntent( handlerIn, handlerOut )
 * .hoverIntent( handlerInOut )
 *
 * // basic usage ... with event delegation!
 * .hoverIntent( handlerIn, handlerOut, selector )
 * .hoverIntent( handlerInOut, selector )
 *
 * // using a basic configuration object
 * .hoverIntent( config )
 *
 * @param  handlerIn   function OR configuration object
 * @param  handlerOut  function OR selector for delegation OR undefined
 * @param  selector    selector OR undefined
 * @author Brian Cherne <brian(at)cherne(dot)net>
 **/
(function($) {
    $.fn.hoverIntent = function(handlerIn,handlerOut,selector) {

        // default configuration values
        var cfg = {
            interval: 100,
            sensitivity: 7,
            timeout: 0
        };

        if ( typeof handlerIn === "object" ) {
            cfg = $.extend(cfg, handlerIn );
        } else if ($.isFunction(handlerOut)) {
            cfg = $.extend(cfg, { over: handlerIn, out: handlerOut, selector: selector } );
        } else {
            cfg = $.extend(cfg, { over: handlerIn, out: handlerIn, selector: handlerOut } );
        }

        // instantiate variables
        // cX, cY = current X and Y position of mouse, updated by mousemove event
        // pX, pY = previous X and Y position of mouse, set by mouseover and polling interval
        var cX, cY, pX, pY;

        // A private function for getting mouse position
        var track = function(ev) {
            cX = ev.pageX;
            cY = ev.pageY;
        };

        // A private function for comparing current and previous mouse position
        var compare = function(ev,ob) {
            ob.hoverIntent_t = clearTimeout(ob.hoverIntent_t);
            // compare mouse positions to see if they've crossed the threshold
            if ( ( Math.abs(pX-cX) + Math.abs(pY-cY) ) < cfg.sensitivity ) {
                $(ob).off("mousemove.hoverIntent",track);
                // set hoverIntent state to true (so mouseOut can be called)
                ob.hoverIntent_s = 1;
                return cfg.over.apply(ob,[ev]);
            } else {
                // set previous coordinates for next time
                pX = cX; pY = cY;
                // use self-calling timeout, guarantees intervals are spaced out properly (avoids JavaScript timer bugs)
                ob.hoverIntent_t = setTimeout( function(){compare(ev, ob);} , cfg.interval );
            }
        };

        // A private function for delaying the mouseOut function
        var delay = function(ev,ob) {
            ob.hoverIntent_t = clearTimeout(ob.hoverIntent_t);
            ob.hoverIntent_s = 0;
            return cfg.out.apply(ob,[ev]);
        };

        // A private function for handling mouse 'hovering'
        var handleHover = function(e) {
            // copy objects to be passed into t (required for event object to be passed in IE)
            var ev = jQuery.extend({},e);
            var ob = this;

            // cancel hoverIntent timer if it exists
            if (ob.hoverIntent_t) { ob.hoverIntent_t = clearTimeout(ob.hoverIntent_t); }

            // if e.type == "mouseenter"
            if (e.type == "mouseenter") {
                // set "previous" X and Y position based on initial entry point
                pX = ev.pageX; pY = ev.pageY;
                // update "current" X and Y position based on mousemove
                $(ob).on("mousemove.hoverIntent",track);
                // start polling interval (self-calling timeout) to compare mouse coordinates over time
                if (ob.hoverIntent_s != 1) { ob.hoverIntent_t = setTimeout( function(){compare(ev,ob);} , cfg.interval );}

                // else e.type == "mouseleave"
            } else {
                // unbind expensive mousemove event
                $(ob).off("mousemove.hoverIntent",track);
                // if hoverIntent state is true, then call the mouseOut function after the specified delay
                if (ob.hoverIntent_s == 1) { ob.hoverIntent_t = setTimeout( function(){delay(ev,ob);} , cfg.timeout );}
            }
        };

        // listen for mouseenter and mouseleave
        return this.on({'mouseenter.hoverIntent':handleHover,'mouseleave.hoverIntent':handleHover}, cfg.selector);
    };
})(jQuery);

/*!
 * imagesLoaded PACKAGED v3.1.8
 * JavaScript is all like "You images are done yet or what?"
 * MIT License
 */

(function(){function e(){}function t(e,t){for(var n=e.length;n--;)if(e[n].listener===t)return n;return-1}function n(e){return function(){return this[e].apply(this,arguments)}}var i=e.prototype,r=this,o=r.EventEmitter;i.getListeners=function(e){var t,n,i=this._getEvents();if("object"==typeof e){t={};for(n in i)i.hasOwnProperty(n)&&e.test(n)&&(t[n]=i[n])}else t=i[e]||(i[e]=[]);return t},i.flattenListeners=function(e){var t,n=[];for(t=0;e.length>t;t+=1)n.push(e[t].listener);return n},i.getListenersAsObject=function(e){var t,n=this.getListeners(e);return n instanceof Array&&(t={},t[e]=n),t||n},i.addListener=function(e,n){var i,r=this.getListenersAsObject(e),o="object"==typeof n;for(i in r)r.hasOwnProperty(i)&&-1===t(r[i],n)&&r[i].push(o?n:{listener:n,once:!1});return this},i.on=n("addListener"),i.addOnceListener=function(e,t){return this.addListener(e,{listener:t,once:!0})},i.once=n("addOnceListener"),i.defineEvent=function(e){return this.getListeners(e),this},i.defineEvents=function(e){for(var t=0;e.length>t;t+=1)this.defineEvent(e[t]);return this},i.removeListener=function(e,n){var i,r,o=this.getListenersAsObject(e);for(r in o)o.hasOwnProperty(r)&&(i=t(o[r],n),-1!==i&&o[r].splice(i,1));return this},i.off=n("removeListener"),i.addListeners=function(e,t){return this.manipulateListeners(!1,e,t)},i.removeListeners=function(e,t){return this.manipulateListeners(!0,e,t)},i.manipulateListeners=function(e,t,n){var i,r,o=e?this.removeListener:this.addListener,s=e?this.removeListeners:this.addListeners;if("object"!=typeof t||t instanceof RegExp)for(i=n.length;i--;)o.call(this,t,n[i]);else for(i in t)t.hasOwnProperty(i)&&(r=t[i])&&("function"==typeof r?o.call(this,i,r):s.call(this,i,r));return this},i.removeEvent=function(e){var t,n=typeof e,i=this._getEvents();if("string"===n)delete i[e];else if("object"===n)for(t in i)i.hasOwnProperty(t)&&e.test(t)&&delete i[t];else delete this._events;return this},i.removeAllListeners=n("removeEvent"),i.emitEvent=function(e,t){var n,i,r,o,s=this.getListenersAsObject(e);for(r in s)if(s.hasOwnProperty(r))for(i=s[r].length;i--;)n=s[r][i],n.once===!0&&this.removeListener(e,n.listener),o=n.listener.apply(this,t||[]),o===this._getOnceReturnValue()&&this.removeListener(e,n.listener);return this},i.trigger=n("emitEvent"),i.emit=function(e){var t=Array.prototype.slice.call(arguments,1);return this.emitEvent(e,t)},i.setOnceReturnValue=function(e){return this._onceReturnValue=e,this},i._getOnceReturnValue=function(){return this.hasOwnProperty("_onceReturnValue")?this._onceReturnValue:!0},i._getEvents=function(){return this._events||(this._events={})},e.noConflict=function(){return r.EventEmitter=o,e},"function"==typeof define&&define.amd?define("eventEmitter/EventEmitter",[],function(){return e}):"object"==typeof module&&module.exports?module.exports=e:this.EventEmitter=e}).call(this),function(e){function t(t){var n=e.event;return n.target=n.target||n.srcElement||t,n}var n=document.documentElement,i=function(){};n.addEventListener?i=function(e,t,n){e.addEventListener(t,n,!1)}:n.attachEvent&&(i=function(e,n,i){e[n+i]=i.handleEvent?function(){var n=t(e);i.handleEvent.call(i,n)}:function(){var n=t(e);i.call(e,n)},e.attachEvent("on"+n,e[n+i])});var r=function(){};n.removeEventListener?r=function(e,t,n){e.removeEventListener(t,n,!1)}:n.detachEvent&&(r=function(e,t,n){e.detachEvent("on"+t,e[t+n]);try{delete e[t+n]}catch(i){e[t+n]=void 0}});var o={bind:i,unbind:r};"function"==typeof define&&define.amd?define("eventie/eventie",o):e.eventie=o}(this),function(e,t){"function"==typeof define&&define.amd?define(["eventEmitter/EventEmitter","eventie/eventie"],function(n,i){return t(e,n,i)}):"object"==typeof exports?module.exports=t(e,require("wolfy87-eventemitter"),require("eventie")):e.imagesLoaded=t(e,e.EventEmitter,e.eventie)}(window,function(e,t,n){function i(e,t){for(var n in t)e[n]=t[n];return e}function r(e){return"[object Array]"===d.call(e)}function o(e){var t=[];if(r(e))t=e;else if("number"==typeof e.length)for(var n=0,i=e.length;i>n;n++)t.push(e[n]);else t.push(e);return t}function s(e,t,n){if(!(this instanceof s))return new s(e,t);"string"==typeof e&&(e=document.querySelectorAll(e)),this.elements=o(e),this.options=i({},this.options),"function"==typeof t?n=t:i(this.options,t),n&&this.on("always",n),this.getImages(),a&&(this.jqDeferred=new a.Deferred);var r=this;setTimeout(function(){r.check()})}function f(e){this.img=e}function c(e){this.src=e,v[e]=this}var a=e.jQuery,u=e.console,h=u!==void 0,d=Object.prototype.toString;s.prototype=new t,s.prototype.options={},s.prototype.getImages=function(){this.images=[];for(var e=0,t=this.elements.length;t>e;e++){var n=this.elements[e];"IMG"===n.nodeName&&this.addImage(n);var i=n.nodeType;if(i&&(1===i||9===i||11===i))for(var r=n.querySelectorAll("img"),o=0,s=r.length;s>o;o++){var f=r[o];this.addImage(f)}}},s.prototype.addImage=function(e){var t=new f(e);this.images.push(t)},s.prototype.check=function(){function e(e,r){return t.options.debug&&h&&u.log("confirm",e,r),t.progress(e),n++,n===i&&t.complete(),!0}var t=this,n=0,i=this.images.length;if(this.hasAnyBroken=!1,!i)return this.complete(),void 0;for(var r=0;i>r;r++){var o=this.images[r];o.on("confirm",e),o.check()}},s.prototype.progress=function(e){this.hasAnyBroken=this.hasAnyBroken||!e.isLoaded;var t=this;setTimeout(function(){t.emit("progress",t,e),t.jqDeferred&&t.jqDeferred.notify&&t.jqDeferred.notify(t,e)})},s.prototype.complete=function(){var e=this.hasAnyBroken?"fail":"done";this.isComplete=!0;var t=this;setTimeout(function(){if(t.emit(e,t),t.emit("always",t),t.jqDeferred){var n=t.hasAnyBroken?"reject":"resolve";t.jqDeferred[n](t)}})},a&&(a.fn.imagesLoaded=function(e,t){var n=new s(this,e,t);return n.jqDeferred.promise(a(this))}),f.prototype=new t,f.prototype.check=function(){var e=v[this.img.src]||new c(this.img.src);if(e.isConfirmed)return this.confirm(e.isLoaded,"cached was confirmed"),void 0;if(this.img.complete&&void 0!==this.img.naturalWidth)return this.confirm(0!==this.img.naturalWidth,"naturalWidth"),void 0;var t=this;e.on("confirm",function(e,n){return t.confirm(e.isLoaded,n),!0}),e.check()},f.prototype.confirm=function(e,t){this.isLoaded=e,this.emit("confirm",this,t)};var v={};return c.prototype=new t,c.prototype.check=function(){if(!this.isChecked){var e=new Image;n.bind(e,"load",this),n.bind(e,"error",this),e.src=this.src,this.isChecked=!0}},c.prototype.handleEvent=function(e){var t="on"+e.type;this[t]&&this[t](e)},c.prototype.onload=function(e){this.confirm(!0,"onload"),this.unbindProxyEvents(e)},c.prototype.onerror=function(e){this.confirm(!1,"onerror"),this.unbindProxyEvents(e)},c.prototype.confirm=function(e,t){this.isConfirmed=!0,this.isLoaded=e,this.emit("confirm",this,t)},c.prototype.unbindProxyEvents=function(e){n.unbind(e.target,"load",this),n.unbind(e.target,"error",this)},s});

/*! jCarousel - v0.3.0 - 2013-11-22
* http://sorgalla.com/jcarousel
* Copyright (c) 2013 Jan Sorgalla; Licensed MIT */
(function(t){"use strict";var i=t.jCarousel={};i.version="0.3.0";var s=/^([+\-]=)?(.+)$/;i.parseTarget=function(t){var i=!1,e="object"!=typeof t?s.exec(t):null;return e?(t=parseInt(e[2],10)||0,e[1]&&(i=!0,"-="===e[1]&&(t*=-1))):"object"!=typeof t&&(t=parseInt(t,10)||0),{target:t,relative:i}},i.detectCarousel=function(t){for(var i;t.length>0;){if(i=t.filter("[data-jcarousel]"),i.length>0)return i;if(i=t.find("[data-jcarousel]"),i.length>0)return i;t=t.parent()}return null},i.base=function(s){return{version:i.version,_options:{},_element:null,_carousel:null,_init:t.noop,_create:t.noop,_destroy:t.noop,_reload:t.noop,create:function(){return this._element.attr("data-"+s.toLowerCase(),!0).data(s,this),!1===this._trigger("create")?this:(this._create(),this._trigger("createend"),this)},destroy:function(){return!1===this._trigger("destroy")?this:(this._destroy(),this._trigger("destroyend"),this._element.removeData(s).removeAttr("data-"+s.toLowerCase()),this)},reload:function(t){return!1===this._trigger("reload")?this:(t&&this.options(t),this._reload(),this._trigger("reloadend"),this)},element:function(){return this._element},options:function(i,s){if(0===arguments.length)return t.extend({},this._options);if("string"==typeof i){if(s===void 0)return this._options[i]===void 0?null:this._options[i];this._options[i]=s}else this._options=t.extend({},this._options,i);return this},carousel:function(){return this._carousel||(this._carousel=i.detectCarousel(this.options("carousel")||this._element),this._carousel||t.error('Could not detect carousel for plugin "'+s+'"')),this._carousel},_trigger:function(i,e,r){var n,o=!1;return r=[this].concat(r||[]),(e||this._element).each(function(){n=t.Event((s+":"+i).toLowerCase()),t(this).trigger(n,r),n.isDefaultPrevented()&&(o=!0)}),!o}}},i.plugin=function(s,e){var r=t[s]=function(i,s){this._element=t(i),this.options(s),this._init(),this.create()};return r.fn=r.prototype=t.extend({},i.base(s),e),t.fn[s]=function(i){var e=Array.prototype.slice.call(arguments,1),n=this;return"string"==typeof i?this.each(function(){var r=t(this).data(s);if(!r)return t.error("Cannot call methods on "+s+" prior to initialization; "+'attempted to call method "'+i+'"');if(!t.isFunction(r[i])||"_"===i.charAt(0))return t.error('No such method "'+i+'" for '+s+" instance");var o=r[i].apply(r,e);return o!==r&&o!==void 0?(n=o,!1):void 0}):this.each(function(){var e=t(this).data(s);e instanceof r?e.reload(i):new r(this,i)}),n},r}})(jQuery),function(t,i){"use strict";var s=function(t){return parseFloat(t)||0};t.jCarousel.plugin("jcarousel",{animating:!1,tail:0,inTail:!1,resizeTimer:null,lt:null,vertical:!1,rtl:!1,circular:!1,underflow:!1,relative:!1,_options:{list:function(){return this.element().children().eq(0)},items:function(){return this.list().children()},animation:400,transitions:!1,wrap:null,vertical:null,rtl:null,center:!1},_list:null,_items:null,_target:null,_first:null,_last:null,_visible:null,_fullyvisible:null,_init:function(){var t=this;return this.onWindowResize=function(){t.resizeTimer&&clearTimeout(t.resizeTimer),t.resizeTimer=setTimeout(function(){t.reload()},100)},this},_create:function(){this._reload(),t(i).on("resize.jcarousel",this.onWindowResize)},_destroy:function(){t(i).off("resize.jcarousel",this.onWindowResize)},_reload:function(){this.vertical=this.options("vertical"),null==this.vertical&&(this.vertical=this.list().height()>this.list().width()),this.rtl=this.options("rtl"),null==this.rtl&&(this.rtl=function(i){if("rtl"===(""+i.attr("dir")).toLowerCase())return!0;var s=!1;return i.parents("[dir]").each(function(){return/rtl/i.test(t(this).attr("dir"))?(s=!0,!1):void 0}),s}(this._element)),this.lt=this.vertical?"top":"left",this.relative="relative"===this.list().css("position"),this._list=null,this._items=null;var i=this._target&&this.index(this._target)>=0?this._target:this.closest();this.circular="circular"===this.options("wrap"),this.underflow=!1;var s={left:0,top:0};return i.length>0&&(this._prepare(i),this.list().find("[data-jcarousel-clone]").remove(),this._items=null,this.underflow=this._fullyvisible.length>=this.items().length,this.circular=this.circular&&!this.underflow,s[this.lt]=this._position(i)+"px"),this.move(s),this},list:function(){if(null===this._list){var i=this.options("list");this._list=t.isFunction(i)?i.call(this):this._element.find(i)}return this._list},items:function(){if(null===this._items){var i=this.options("items");this._items=(t.isFunction(i)?i.call(this):this.list().find(i)).not("[data-jcarousel-clone]")}return this._items},index:function(t){return this.items().index(t)},closest:function(){var i,e=this,r=this.list().position()[this.lt],n=t(),o=!1,l=this.vertical?"bottom":this.rtl&&!this.relative?"left":"right";return this.rtl&&this.relative&&!this.vertical&&(r+=this.list().width()-this.clipping()),this.items().each(function(){if(n=t(this),o)return!1;var a=e.dimension(n);if(r+=a,r>=0){if(i=a-s(n.css("margin-"+l)),!(0>=Math.abs(r)-a+i/2))return!1;o=!0}}),n},target:function(){return this._target},first:function(){return this._first},last:function(){return this._last},visible:function(){return this._visible},fullyvisible:function(){return this._fullyvisible},hasNext:function(){if(!1===this._trigger("hasnext"))return!0;var t=this.options("wrap"),i=this.items().length-1;return i>=0&&(t&&"first"!==t||i>this.index(this._last)||this.tail&&!this.inTail)?!0:!1},hasPrev:function(){if(!1===this._trigger("hasprev"))return!0;var t=this.options("wrap");return this.items().length>0&&(t&&"last"!==t||this.index(this._first)>0||this.tail&&this.inTail)?!0:!1},clipping:function(){return this._element["inner"+(this.vertical?"Height":"Width")]()},dimension:function(t){return t["outer"+(this.vertical?"Height":"Width")](!0)},scroll:function(i,s,e){if(this.animating)return this;if(!1===this._trigger("scroll",null,[i,s]))return this;t.isFunction(s)&&(e=s,s=!0);var r=t.jCarousel.parseTarget(i);if(r.relative){var n,o,l,a,h,u,c,f,d=this.items().length-1,_=Math.abs(r.target),p=this.options("wrap");if(r.target>0){var v=this.index(this._last);if(v>=d&&this.tail)this.inTail?"both"===p||"last"===p?this._scroll(0,s,e):t.isFunction(e)&&e.call(this,!1):this._scrollTail(s,e);else if(n=this.index(this._target),this.underflow&&n===d&&("circular"===p||"both"===p||"last"===p)||!this.underflow&&v===d&&("both"===p||"last"===p))this._scroll(0,s,e);else if(l=n+_,this.circular&&l>d){for(f=d,h=this.items().get(-1);l>f++;)h=this.items().eq(0),u=this._visible.index(h)>=0,u&&h.after(h.clone(!0).attr("data-jcarousel-clone",!0)),this.list().append(h),u||(c={},c[this.lt]=this.dimension(h),this.moveBy(c)),this._items=null;this._scroll(h,s,e)}else this._scroll(Math.min(l,d),s,e)}else if(this.inTail)this._scroll(Math.max(this.index(this._first)-_+1,0),s,e);else if(o=this.index(this._first),n=this.index(this._target),a=this.underflow?n:o,l=a-_,0>=a&&(this.underflow&&"circular"===p||"both"===p||"first"===p))this._scroll(d,s,e);else if(this.circular&&0>l){for(f=l,h=this.items().get(0);0>f++;){h=this.items().eq(-1),u=this._visible.index(h)>=0,u&&h.after(h.clone(!0).attr("data-jcarousel-clone",!0)),this.list().prepend(h),this._items=null;var g=this.dimension(h);c={},c[this.lt]=-g,this.moveBy(c)}this._scroll(h,s,e)}else this._scroll(Math.max(l,0),s,e)}else this._scroll(r.target,s,e);return this._trigger("scrollend"),this},moveBy:function(t,i){var e=this.list().position(),r=1,n=0;return this.rtl&&!this.vertical&&(r=-1,this.relative&&(n=this.list().width()-this.clipping())),t.left&&(t.left=e.left+n+s(t.left)*r+"px"),t.top&&(t.top=e.top+n+s(t.top)*r+"px"),this.move(t,i)},move:function(i,s){s=s||{};var e=this.options("transitions"),r=!!e,n=!!e.transforms,o=!!e.transforms3d,l=s.duration||0,a=this.list();if(!r&&l>0)return a.animate(i,s),void 0;var h=s.complete||t.noop,u={};if(r){var c=a.css(["transitionDuration","transitionTimingFunction","transitionProperty"]),f=h;h=function(){t(this).css(c),f.call(this)},u={transitionDuration:(l>0?l/1e3:0)+"s",transitionTimingFunction:e.easing||s.easing,transitionProperty:l>0?function(){return n||o?"all":i.left?"left":"top"}():"none",transform:"none"}}o?u.transform="translate3d("+(i.left||0)+","+(i.top||0)+",0)":n?u.transform="translate("+(i.left||0)+","+(i.top||0)+")":t.extend(u,i),r&&l>0&&a.one("transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd",h),a.css(u),0>=l&&a.each(function(){h.call(this)})},_scroll:function(i,s,e){if(this.animating)return t.isFunction(e)&&e.call(this,!1),this;if("object"!=typeof i?i=this.items().eq(i):i.jquery===void 0&&(i=t(i)),0===i.length)return t.isFunction(e)&&e.call(this,!1),this;this.inTail=!1,this._prepare(i);var r=this._position(i),n=this.list().position()[this.lt];if(r===n)return t.isFunction(e)&&e.call(this,!1),this;var o={};return o[this.lt]=r+"px",this._animate(o,s,e),this},_scrollTail:function(i,s){if(this.animating||!this.tail)return t.isFunction(s)&&s.call(this,!1),this;var e=this.list().position()[this.lt];this.rtl&&this.relative&&!this.vertical&&(e+=this.list().width()-this.clipping()),this.rtl&&!this.vertical?e+=this.tail:e-=this.tail,this.inTail=!0;var r={};return r[this.lt]=e+"px",this._update({target:this._target.next(),fullyvisible:this._fullyvisible.slice(1).add(this._visible.last())}),this._animate(r,i,s),this},_animate:function(i,s,e){if(e=e||t.noop,!1===this._trigger("animate"))return e.call(this,!1),this;this.animating=!0;var r=this.options("animation"),n=t.proxy(function(){this.animating=!1;var t=this.list().find("[data-jcarousel-clone]");t.length>0&&(t.remove(),this._reload()),this._trigger("animateend"),e.call(this,!0)},this),o="object"==typeof r?t.extend({},r):{duration:r},l=o.complete||t.noop;return s===!1?o.duration=0:t.fx.speeds[o.duration]!==void 0&&(o.duration=t.fx.speeds[o.duration]),o.complete=function(){n(),l.call(this)},this.move(i,o),this},_prepare:function(i){var e,r,n,o,l=this.index(i),a=l,h=this.dimension(i),u=this.clipping(),c=this.vertical?"bottom":this.rtl?"left":"right",f=this.options("center"),d={target:i,first:i,last:i,visible:i,fullyvisible:u>=h?i:t()};if(f&&(h/=2,u/=2),u>h)for(;;){if(e=this.items().eq(++a),0===e.length){if(!this.circular)break;if(e=this.items().eq(0),i.get(0)===e.get(0))break;if(r=this._visible.index(e)>=0,r&&e.after(e.clone(!0).attr("data-jcarousel-clone",!0)),this.list().append(e),!r){var _={};_[this.lt]=this.dimension(e),this.moveBy(_)}this._items=null}if(o=this.dimension(e),0===o)break;if(h+=o,d.last=e,d.visible=d.visible.add(e),n=s(e.css("margin-"+c)),u>=h-n&&(d.fullyvisible=d.fullyvisible.add(e)),h>=u)break}if(!this.circular&&!f&&u>h)for(a=l;;){if(0>--a)break;if(e=this.items().eq(a),0===e.length)break;if(o=this.dimension(e),0===o)break;if(h+=o,d.first=e,d.visible=d.visible.add(e),n=s(e.css("margin-"+c)),u>=h-n&&(d.fullyvisible=d.fullyvisible.add(e)),h>=u)break}return this._update(d),this.tail=0,f||"circular"===this.options("wrap")||"custom"===this.options("wrap")||this.index(d.last)!==this.items().length-1||(h-=s(d.last.css("margin-"+c)),h>u&&(this.tail=h-u)),this},_position:function(t){var i=this._first,s=i.position()[this.lt],e=this.options("center"),r=e?this.clipping()/2-this.dimension(i)/2:0;return this.rtl&&!this.vertical?(s-=this.relative?this.list().width()-this.dimension(i):this.clipping()-this.dimension(i),s+=r):s-=r,!e&&(this.index(t)>this.index(i)||this.inTail)&&this.tail?(s=this.rtl&&!this.vertical?s-this.tail:s+this.tail,this.inTail=!0):this.inTail=!1,-s},_update:function(i){var s,e=this,r={target:this._target||t(),first:this._first||t(),last:this._last||t(),visible:this._visible||t(),fullyvisible:this._fullyvisible||t()},n=this.index(i.first||r.first)<this.index(r.first),o=function(s){var o=[],l=[];i[s].each(function(){0>r[s].index(this)&&o.push(this)}),r[s].each(function(){0>i[s].index(this)&&l.push(this)}),n?o=o.reverse():l=l.reverse(),e._trigger(s+"in",t(o)),e._trigger(s+"out",t(l)),e["_"+s]=i[s]};for(s in i)o(s);return this}})}(jQuery,window),function(t){"use strict";t.jcarousel.fn.scrollIntoView=function(i,s,e){var r,n=t.jCarousel.parseTarget(i),o=this.index(this._fullyvisible.first()),l=this.index(this._fullyvisible.last());if(r=n.relative?0>n.target?Math.max(0,o+n.target):l+n.target:"object"!=typeof n.target?n.target:this.index(n.target),o>r)return this.scroll(r,s,e);if(r>=o&&l>=r)return t.isFunction(e)&&e.call(this,!1),this;for(var a,h=this.items(),u=this.clipping(),c=this.vertical?"bottom":this.rtl?"left":"right",f=0;;){if(a=h.eq(r),0===a.length)break;if(f+=this.dimension(a),f>=u){var d=parseFloat(a.css("margin-"+c))||0;f-d!==u&&r++;break}if(0>=r)break;r--}return this.scroll(r,s,e)}}(jQuery),function(t){"use strict";t.jCarousel.plugin("jcarouselControl",{_options:{target:"+=1",event:"click",method:"scroll"},_active:null,_init:function(){this.onDestroy=t.proxy(function(){this._destroy(),this.carousel().one("jcarousel:createend",t.proxy(this._create,this))},this),this.onReload=t.proxy(this._reload,this),this.onEvent=t.proxy(function(i){i.preventDefault();var s=this.options("method");t.isFunction(s)?s.call(this):this.carousel().jcarousel(this.options("method"),this.options("target"))},this)},_create:function(){this.carousel().one("jcarousel:destroy",this.onDestroy).on("jcarousel:reloadend jcarousel:scrollend",this.onReload),this._element.on(this.options("event")+".jcarouselcontrol",this.onEvent),this._reload()},_destroy:function(){this._element.off(".jcarouselcontrol",this.onEvent),this.carousel().off("jcarousel:destroy",this.onDestroy).off("jcarousel:reloadend jcarousel:scrollend",this.onReload)},_reload:function(){var i,s=t.jCarousel.parseTarget(this.options("target")),e=this.carousel();if(s.relative)i=e.jcarousel(s.target>0?"hasNext":"hasPrev");else{var r="object"!=typeof s.target?e.jcarousel("items").eq(s.target):s.target;i=e.jcarousel("target").index(r)>=0}return this._active!==i&&(this._trigger(i?"active":"inactive"),this._active=i),this}})}(jQuery),function(t){"use strict";t.jCarousel.plugin("jcarouselPagination",{_options:{perPage:null,item:function(t){return'<a href="#'+t+'">'+t+"</a>"},event:"click",method:"scroll"},_pages:{},_items:{},_currentPage:null,_init:function(){this.onDestroy=t.proxy(function(){this._destroy(),this.carousel().one("jcarousel:createend",t.proxy(this._create,this))},this),this.onReload=t.proxy(this._reload,this),this.onScroll=t.proxy(this._update,this)},_create:function(){this.carousel().one("jcarousel:destroy",this.onDestroy).on("jcarousel:reloadend",this.onReload).on("jcarousel:scrollend",this.onScroll),this._reload()},_destroy:function(){this._clear(),this.carousel().off("jcarousel:destroy",this.onDestroy).off("jcarousel:reloadend",this.onReload).off("jcarousel:scrollend",this.onScroll)},_reload:function(){var i=this.options("perPage");if(this._pages={},this._items={},t.isFunction(i)&&(i=i.call(this)),null==i)this._pages=this._calculatePages();else for(var s,e=parseInt(i,10)||0,r=this.carousel().jcarousel("items"),n=1,o=0;;){if(s=r.eq(o++),0===s.length)break;this._pages[n]=this._pages[n]?this._pages[n].add(s):s,0===o%e&&n++}this._clear();var l=this,a=this.carousel().data("jcarousel"),h=this._element,u=this.options("item");t.each(this._pages,function(i,s){var e=l._items[i]=t(u.call(l,i,s));e.on(l.options("event")+".jcarouselpagination",t.proxy(function(){var t=s.eq(0);if(a.circular){var e=a.index(a.target()),r=a.index(t);parseFloat(i)>parseFloat(l._currentPage)?e>r&&(t="+="+(a.items().length-e+r)):r>e&&(t="-="+(e+(a.items().length-r)))}a[this.options("method")](t)},l)),h.append(e)}),this._update()},_update:function(){var i,s=this.carousel().jcarousel("target");t.each(this._pages,function(t,e){return e.each(function(){return s.is(this)?(i=t,!1):void 0}),i?!1:void 0}),this._currentPage!==i&&(this._trigger("inactive",this._items[this._currentPage]),this._trigger("active",this._items[i])),this._currentPage=i},items:function(){return this._items},_clear:function(){this._element.empty(),this._currentPage=null},_calculatePages:function(){for(var t,i=this.carousel().data("jcarousel"),s=i.items(),e=i.clipping(),r=0,n=0,o=1,l={};;){if(t=s.eq(n++),0===t.length)break;l[o]=l[o]?l[o].add(t):t,r+=i.dimension(t),r>=e&&(o++,r=0)}return l}})}(jQuery),function(t){"use strict";t.jCarousel.plugin("jcarouselAutoscroll",{_options:{target:"+=1",interval:3e3,autostart:!0},_timer:null,_init:function(){this.onDestroy=t.proxy(function(){this._destroy(),this.carousel().one("jcarousel:createend",t.proxy(this._create,this))},this),this.onAnimateEnd=t.proxy(this.start,this)},_create:function(){this.carousel().one("jcarousel:destroy",this.onDestroy),this.options("autostart")&&this.start()},_destroy:function(){this.stop(),this.carousel().off("jcarousel:destroy",this.onDestroy)},start:function(){return this.stop(),this.carousel().one("jcarousel:animateend",this.onAnimateEnd),this._timer=setTimeout(t.proxy(function(){this.carousel().jcarousel("scroll",this.options("target"))},this),this.options("interval")),this},stop:function(){return this._timer&&(this._timer=clearTimeout(this._timer)),this.carousel().off("jcarousel:animateend",this.onAnimateEnd),this}})}(jQuery);

/*! http://tinynav.viljamis.com v1.2 by @viljamis */
(function ($, window, i) {
  $.fn.tinyNav = function (options) {

    // Default settings
    var settings = $.extend({
      'active' : 'selected', // String: Set the "active" class
      'header' : '', // String: Specify text for "header" and show header instead of the active item
      'indent' : '- ', // String: Specify text for indenting sub-items
      'label'  : '' // String: sets the <label> text for the <select> (if not set, no label will be added)
    }, options);

    return this.each(function () {

      // Used for namespacing
      i++;

      var $nav = $(this),
        // Namespacing
        namespace = 'tinynav',
        namespace_i = namespace + i,
        l_namespace_i = '.l_' + namespace_i,
        $select = $('<select/>').attr("id", namespace_i).addClass(namespace + ' ' + namespace_i);

      if ($nav.is('ul,ol')) {

        if (settings.header !== '') {
          $select.append(
            $('<option/>').text(settings.header)
          );
        }

        // Build options
        var options = '';

        $nav
          .addClass('l_' + namespace_i)
          .find('a')
          .each(function () {
            options += '<option value="' + $(this).attr('href') + '">';
            var j;
            for (j = 0; j < $(this).parents('ul, ol').length - 1; j++) {
              options += settings.indent;
            }
            options += $(this).text() + '</option>';
          });

        // Append options into a select
        $select.append(options);

        // Select the active item
        if (!settings.header) {
          $select
            .find(':eq(' + $(l_namespace_i + ' li')
            .index($(l_namespace_i + ' li.' + settings.active)) + ')')
            .attr('selected', true);
        }

        // Change window location
        $select.change(function () {
          window.location.href = $(this).val();
        });

        // Inject select
        $(l_namespace_i).after($select);

        // Inject label
        if (settings.label) {
          $select.before(
            $("<label/>")
              .attr("for", namespace_i)
              .addClass(namespace + '_label ' + namespace_i + '_label')
              .append(settings.label)
          );
        }

      }

    });

  };
})(jQuery, this, 0);

/*!
 *  Sharrre.com - Make your sharing widget!
 *  Version: beta 1.3.5
 *  Author: Julien Hany
 *  License: MIT http://en.wikipedia.org/wiki/MIT_License or GPLv2 http://en.wikipedia.org/wiki/GNU_General_Public_License
 */

;(function ( $, window, document, undefined ) {

  /* Defaults
  ================================================== */
  var pluginName = 'sharrre',
  defaults = {
    className: 'sharrre',
    share: {
      googlePlus: false,
      facebook: false,
      twitter: false,
      digg: false,
      delicious: false,
      stumbleupon: false,
      linkedin: false,
      pinterest: false
    },
    shareTotal: 0,
    template: '',
    title: '',
    url: document.location.href,
    text: document.title,
    urlCurl: 'sharrre.php',  //PHP script for google plus...
    count: {}, //counter by social network
    total: 0,  //total of sharing
    shorterTotal: true, //show total by k or M when number is to big
    enableHover: true, //disable if you want to personalize hover event with callback
    enableCounter: true, //disable if you just want use buttons
    enableTracking: false, //tracking with google analitycs
    hover: function(){}, //personalize hover event with this callback function
    hide: function(){}, //personalize hide event with this callback function
    click: function(){}, //personalize click event with this callback function
    render: function(){}, //personalize render event with this callback function
    buttons: {  //settings for buttons
      googlePlus : {  //http://www.google.com/webmasters/+1/button/
        url: '',  //if you need to personnalize button url
        urlCount: false,  //if you want to use personnalize button url on global counter
        size: 'medium',
        lang: 'en-US',
        annotation: ''
      },
      facebook: { //http://developers.facebook.com/docs/reference/plugins/like/
        url: '',  //if you need to personalize url button
        urlCount: false,  //if you want to use personnalize button url on global counter
        action: 'like',
        layout: 'button_count',
        width: '',
        send: 'false',
        faces: 'false',
        colorscheme: '',
        font: '',
        lang: 'en_US'
      },
      twitter: {  //http://twitter.com/about/resources/tweetbutton
        url: '',  //if you need to personalize url button
        urlCount: false,  //if you want to use personnalize button url on global counter
        count: 'horizontal',
        hashtags: '',
        via: '',
        related: '',
        lang: 'en'
      },
      digg: { //http://about.digg.com/downloads/button/smart
        url: '',  //if you need to personalize url button
        urlCount: false,  //if you want to use personnalize button url on global counter
        type: 'DiggCompact'
      },
      delicious: {
        url: '',  //if you need to personalize url button
        urlCount: false,  //if you want to use personnalize button url on global counter
        size: 'medium' //medium or tall
      },
      stumbleupon: {  //http://www.stumbleupon.com/badges/
        url: '',  //if you need to personalize url button
        urlCount: false,  //if you want to use personnalize button url on global counter
        layout: '1'
      },
      linkedin: {  //http://developer.linkedin.com/plugins/share-button
        url: '',  //if you need to personalize url button
        urlCount: false,  //if you want to use personnalize button url on global counter
        counter: ''
      },
      pinterest: { //http://pinterest.com/about/goodies/
        url: '',  //if you need to personalize url button
        media: '',
        description: '',
        layout: 'horizontal'
      }
    }
  },
  /* Json URL to get count number
  ================================================== */
  urlJson = {
    googlePlus: "",

    //new FQL method by Sire
    facebook: "https://graph.facebook.com/fql?q=SELECT%20url,%20normalized_url,%20share_count,%20like_count,%20comment_count,%20total_count,commentsbox_count,%20comments_fbid,%20click_count%20FROM%20link_stat%20WHERE%20url=%27{url}%27&callback=?",
    //old method facebook: "http://graph.facebook.com/?id={url}&callback=?",
    //facebook : "http://api.ak.facebook.com/restserver.php?v=1.0&method=links.getStats&urls={url}&format=json"
    
    twitter: "http://cdn.api.twitter.com/1/urls/count.json?url={url}&callback=?",
    digg: "http://services.digg.com/2.0/story.getInfo?links={url}&type=javascript&callback=?",
    delicious: 'http://feeds.delicious.com/v2/json/urlinfo/data?url={url}&callback=?',
    //stumbleupon: "http://www.stumbleupon.com/services/1.01/badge.getinfo?url={url}&format=jsonp&callback=?",
    stumbleupon: "",
    linkedin: "http://www.linkedin.com/countserv/count/share?format=jsonp&url={url}&callback=?",
    pinterest: "http://api.pinterest.com/v1/urls/count.json?url={url}&callback=?"
  },
  /* Load share buttons asynchronously
  ================================================== */
  loadButton = {
    googlePlus : function(self){
      var sett = self.options.buttons.googlePlus;
      //$(self.element).find('.buttons').append('<div class="button googleplus"><g:plusone size="'+self.options.buttons.googlePlus.size+'" href="'+self.options.url+'"></g:plusone></div>');
      $(self.element).find('.buttons').append('<div class="button googleplus"><div class="g-plusone" data-size="'+sett.size+'" data-href="'+(sett.url !== '' ? sett.url : self.options.url)+'" data-annotation="'+sett.annotation+'"></div></div>');
      window.___gcfg = {
        lang: self.options.buttons.googlePlus.lang
      };
      var loading = 0;
      if(typeof gapi === 'undefined' && loading == 0){
        loading = 1;
        (function() {
          var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
          po.src = '//apis.google.com/js/plusone.js';
          var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
        })();
      }
      else{
        gapi.plusone.go();
      }
    },
    facebook : function(self){
      var sett = self.options.buttons.facebook;
      $(self.element).find('.buttons').append('<div class="button facebook"><div id="fb-root"></div><div class="fb-like" data-href="'+(sett.url !== '' ? sett.url : self.options.url)+'" data-send="'+sett.send+'" data-layout="'+sett.layout+'" data-width="'+sett.width+'" data-show-faces="'+sett.faces+'" data-action="'+sett.action+'" data-colorscheme="'+sett.colorscheme+'" data-font="'+sett.font+'" data-via="'+sett.via+'"></div></div>');
      var loading = 0;
      if(typeof FB === 'undefined' && loading == 0){
        loading = 1;
        (function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) {return;}
          js = d.createElement(s); js.id = id;
          js.src = '//connect.facebook.net/'+sett.lang+'/all.js#xfbml=1';
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
      }
      else{
        FB.XFBML.parse();
      }
    },
    twitter : function(self){
      var sett = self.options.buttons.twitter;
      $(self.element).find('.buttons').append('<div class="button twitter"><a href="https://twitter.com/share" class="twitter-share-button" data-url="'+(sett.url !== '' ? sett.url : self.options.url)+'" data-count="'+sett.count+'" data-text="'+self.options.text+'" data-via="'+sett.via+'" data-hashtags="'+sett.hashtags+'" data-related="'+sett.related+'" data-lang="'+sett.lang+'">Tweet</a></div>');
      var loading = 0;
      if(typeof twttr === 'undefined' && loading == 0){
        loading = 1;
        (function() {
          var twitterScriptTag = document.createElement('script');
          twitterScriptTag.type = 'text/javascript';
          twitterScriptTag.async = true;
          twitterScriptTag.src = '//platform.twitter.com/widgets.js';
          var s = document.getElementsByTagName('script')[0];
          s.parentNode.insertBefore(twitterScriptTag, s);
        })();
      }
      else{
        $.ajax({ url: '//platform.twitter.com/widgets.js', dataType: 'script', cache:true}); //http://stackoverflow.com/q/6536108
      }
    },
    digg : function(self){
      var sett = self.options.buttons.digg;
      $(self.element).find('.buttons').append('<div class="button digg"><a class="DiggThisButton '+sett.type+'" rel="nofollow external" href="http://digg.com/submit?url='+encodeURIComponent((sett.url !== '' ? sett.url : self.options.url))+'"></a></div>');
      var loading = 0;
      if(typeof __DBW === 'undefined' && loading == 0){
        loading = 1;
        (function() {
          var s = document.createElement('SCRIPT'), s1 = document.getElementsByTagName('SCRIPT')[0];
          s.type = 'text/javascript';
          s.async = true;
          s.src = '//widgets.digg.com/buttons.js';
          s1.parentNode.insertBefore(s, s1);
        })();
      }
    },
    delicious : function(self){
      if(self.options.buttons.delicious.size == 'tall'){//tall
        var css = 'width:50px;',
        cssCount = 'height:35px;width:50px;font-size:15px;line-height:35px;',
        cssShare = 'height:18px;line-height:18px;margin-top:3px;';
      }
      else{//medium
        var css = 'width:93px;',
        cssCount = 'float:right;padding:0 3px;height:20px;width:26px;line-height:20px;',
        cssShare = 'float:left;height:20px;line-height:20px;';
      }
      var count = self.shorterTotal(self.options.count.delicious);
      if(typeof count === "undefined"){
        count = 0;
      }
      $(self.element).find('.buttons').append(
      '<div class="button delicious"><div style="'+css+'font:12px Arial,Helvetica,sans-serif;cursor:pointer;color:#666666;display:inline-block;float:none;height:20px;line-height:normal;margin:0;padding:0;text-indent:0;vertical-align:baseline;">'+
      '<div style="'+cssCount+'background-color:#fff;margin-bottom:5px;overflow:hidden;text-align:center;border:1px solid #ccc;border-radius:3px;">'+count+'</div>'+
      '<div style="'+cssShare+'display:block;padding:0;text-align:center;text-decoration:none;width:50px;background-color:#7EACEE;border:1px solid #40679C;border-radius:3px;color:#fff;">'+
      '<img src="http://www.delicious.com/static/img/delicious.small.gif" height="10" width="10" alt="Delicious" /> Add</div></div></div>');
      
      $(self.element).find('.delicious').on('click', function(){
        self.openPopup('delicious');
      });
    },
    stumbleupon : function(self){
      var sett = self.options.buttons.stumbleupon;
      $(self.element).find('.buttons').append('<div class="button stumbleupon"><su:badge layout="'+sett.layout+'" location="'+(sett.url !== '' ? sett.url : self.options.url)+'"></su:badge></div>');
      var loading = 0;
      if(typeof STMBLPN === 'undefined' && loading == 0){
        loading = 1;
        (function() {
          var li = document.createElement('script');li.type = 'text/javascript';li.async = true;
          li.src = '//platform.stumbleupon.com/1/widgets.js'; 
          var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(li, s);
        })();
        s = window.setTimeout(function(){
          if(typeof STMBLPN !== 'undefined'){
            STMBLPN.processWidgets();
            clearInterval(s);
          }
        },500);
      }
      else{
        STMBLPN.processWidgets();
      }
    },
    linkedin : function(self){
      var sett = self.options.buttons.linkedin;
      $(self.element).find('.buttons').append('<div class="button linkedin"><script type="in/share" data-url="'+(sett.url !== '' ? sett.url : self.options.url)+'" data-counter="'+sett.counter+'"></script></div>');
      var loading = 0;
      if(typeof window.IN === 'undefined' && loading == 0){
        loading = 1;
        (function() {
          var li = document.createElement('script');li.type = 'text/javascript';li.async = true;
          li.src = '//platform.linkedin.com/in.js'; 
          var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(li, s);
        })();
      }
      else{
        window.IN.init();
      }
    },
    pinterest : function(self){
      var sett = self.options.buttons.pinterest;
      $(self.element).find('.buttons').append('<div class="button pinterest"><a href="http://pinterest.com/pin/create/button/?url='+(sett.url !== '' ? sett.url : self.options.url)+'&media='+sett.media+'&description='+sett.description+'" class="pin-it-button" count-layout="'+sett.layout+'">Pin It</a></div>');

      (function() {
        var li = document.createElement('script');li.type = 'text/javascript';li.async = true;
        li.src = '//assets.pinterest.com/js/pinit.js'; 
        var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(li, s);
      })();
    }
  },
  /* Tracking for Google Analytics
  ================================================== */
  tracking = {
    googlePlus: function(){},
    facebook: function(){
      //console.log('facebook');
      fb = window.setInterval(function(){
        if (typeof FB !== 'undefined') {
          FB.Event.subscribe('edge.create', function(targetUrl) {
            _gaq.push(['_trackSocial', 'facebook', 'like', targetUrl]);
          });
          FB.Event.subscribe('edge.remove', function(targetUrl) {
            _gaq.push(['_trackSocial', 'facebook', 'unlike', targetUrl]);
          });
          FB.Event.subscribe('message.send', function(targetUrl) {
            _gaq.push(['_trackSocial', 'facebook', 'send', targetUrl]);
          });
          //console.log('ok');
          clearInterval(fb);
        }
      },1000);
    },
    twitter: function(){
      //console.log('twitter');
      tw = window.setInterval(function(){
        if (typeof twttr !== 'undefined') {
          twttr.events.bind('tweet', function(event) {
            if (event) {
              _gaq.push(['_trackSocial', 'twitter', 'tweet']);
            }
          });
          //console.log('ok');
          clearInterval(tw);
        }
      },1000);
    },
    digg: function(){
      //if somenone find a solution, mail me !
      /*$(this.element).find('.digg').on('click', function(){
        _gaq.push(['_trackSocial', 'digg', 'add']);
      });*/
    },
    delicious: function(){},
    stumbleupon: function(){},
    linkedin: function(){
      function LinkedInShare() {
        _gaq.push(['_trackSocial', 'linkedin', 'share']);
      }
    },
    pinterest: function(){
      //if somenone find a solution, mail me !
    }
  },
  /* Popup for each social network
  ================================================== */
  popup = {
    googlePlus: function(opt){
      window.open("https://plus.google.com/share?hl="+opt.buttons.googlePlus.lang+"&url="+encodeURIComponent((opt.buttons.googlePlus.url !== '' ? opt.buttons.googlePlus.url : opt.url)), "", "toolbar=0, status=0, width=900, height=500");
    },
    facebook: function(opt){
      window.open("http://www.facebook.com/sharer/sharer.php?u="+encodeURIComponent((opt.buttons.facebook.url !== '' ? opt.buttons.facebook.url : opt.url))+"&t="+opt.text+"", "", "toolbar=0, status=0, width=900, height=500");
    },
    twitter: function(opt){
      window.open("https://twitter.com/intent/tweet?text="+encodeURIComponent(opt.text)+"&url="+encodeURIComponent((opt.buttons.twitter.url !== '' ? opt.buttons.twitter.url : opt.url))+(opt.buttons.twitter.via !== '' ? '&via='+opt.buttons.twitter.via : ''), "", "toolbar=0, status=0, width=650, height=360");
    },
    digg: function(opt){
      window.open("http://digg.com/tools/diggthis/submit?url="+encodeURIComponent((opt.buttons.digg.url !== '' ? opt.buttons.digg.url : opt.url))+"&title="+opt.text+"&related=true&style=true", "", "toolbar=0, status=0, width=650, height=360");
    },
    delicious: function(opt){
      window.open('http://www.delicious.com/save?v=5&noui&jump=close&url='+encodeURIComponent((opt.buttons.delicious.url !== '' ? opt.buttons.delicious.url : opt.url))+'&title='+opt.text, 'delicious', 'toolbar=no,width=550,height=550');
    },
    stumbleupon: function(opt){
      window.open('http://www.stumbleupon.com/badge/?url='+encodeURIComponent((opt.buttons.delicious.url !== '' ? opt.buttons.delicious.url : opt.url)), 'stumbleupon', 'toolbar=no,width=550,height=550');
    },
    linkedin: function(opt){
      window.open('https://www.linkedin.com/cws/share?url='+encodeURIComponent((opt.buttons.delicious.url !== '' ? opt.buttons.delicious.url : opt.url))+'&token=&isFramed=true', 'linkedin', 'toolbar=no,width=550,height=550');
    },
    pinterest: function(opt){
      window.open('http://pinterest.com/pin/create/button/?url='+encodeURIComponent((opt.buttons.pinterest.url !== '' ? opt.buttons.pinterest.url : opt.url))+'&media='+encodeURIComponent(opt.buttons.pinterest.media)+'&description='+opt.buttons.pinterest.description, 'pinterest', 'toolbar=no,width=700,height=300');
    }
  };

  /* Plugin constructor
  ================================================== */
  function Plugin( element, options ) {
    this.element = element;
    
    this.options = $.extend( true, {}, defaults, options);
    this.options.share = options.share; //simple solution to allow order of buttons
    
    this._defaults = defaults;
    this._name = pluginName;
    
    this.init();
  };
  
  /* Initialization method
  ================================================== */
  Plugin.prototype.init = function () {
    var self = this;
    if(this.options.urlCurl !== ''){
      urlJson.googlePlus = this.options.urlCurl + '?url={url}&type=googlePlus'; // PHP script for GooglePlus...
      urlJson.stumbleupon = this.options.urlCurl + '?url={url}&type=stumbleupon'; // PHP script for Stumbleupon...
    }
    $(this.element).addClass(this.options.className); //add class
    
    //HTML5 Custom data
    if(typeof $(this.element).data('title') !== 'undefined'){
      this.options.title = $(this.element).attr('data-title');
    }
    if(typeof $(this.element).data('url') !== 'undefined'){
      this.options.url = $(this.element).data('url');
    }
    if(typeof $(this.element).data('text') !== 'undefined'){
      this.options.text = $(this.element).data('text');
    }
    
    //how many social website have been selected
    $.each(this.options.share, function(name, val) {
      if(val === true){
        self.options.shareTotal ++;
      }
    });
    
    if(self.options.enableCounter === true){  //if for some reason you don't need counter
      //get count of social share that have been selected
      $.each(this.options.share, function(name, val) {
        if(val === true){
          //self.getSocialJson(name);
          try {
            self.getSocialJson(name);
          } catch(e){
          }
        }
      });
    }
    else if(self.options.template !== ''){  //for personalized button (with template)
      this.options.render(this, this.options);
    }
    else{ // if you want to use official button like example 3 or 5
      this.loadButtons();
    }
    
    //add hover event
    $(this.element).hover(function(){
      //load social button if enable and 1 time
      if($(this).find('.buttons').length === 0 && self.options.enableHover === true){
        self.loadButtons();
      }
      self.options.hover(self, self.options);
    }, function(){
      self.options.hide(self, self.options);
    });
    
    //click event
    $(this.element).click(function(){
      self.options.click(self, self.options);
      return false;
    });
  };
  
  /* loadButtons methode
  ================================================== */
  Plugin.prototype.loadButtons = function () {
    var self = this;
    $(this.element).append('<div class="buttons"></div>');
    $.each(self.options.share, function(name, val) {
      if(val == true){
        loadButton[name](self);
        if(self.options.enableTracking === true){ //add tracking
          tracking[name]();
        }
      }
    });
  };
  
  /* getSocialJson methode
  ================================================== */
  Plugin.prototype.getSocialJson = function (name) {
    var self = this,
    count = 0,
    url = urlJson[name].replace('{url}', encodeURIComponent(this.options.url));
    if(this.options.buttons[name].urlCount === true && this.options.buttons[name].url !== ''){
      url = urlJson[name].replace('{url}', this.options.buttons[name].url);
    }
    //console.log('name : ' + name + ' - url : '+url); //debug
    if(url != '' && self.options.urlCurl !== ''){  //urlCurl = '' if you don't want to used PHP script but used social button
      $.getJSON(url, function(json){
        if(typeof json.count !== "undefined"){  //GooglePlus, Stumbleupon, Twitter, Pinterest and Digg
          var temp = json.count + '';
          temp = temp.replace('\u00c2\u00a0', '');  //remove google plus special chars
          count += parseInt(temp, 10);
        }
        //get the FB total count (shares, likes and more)
        else if(json.data && json.data.length > 0 && typeof json.data[0].total_count !== "undefined"){ //Facebook total count
          count += parseInt(json.data[0].total_count, 10);
        }
        else if(typeof json[0] !== "undefined"){  //Delicious
          count += parseInt(json[0].total_posts, 10);
        }
        else if(typeof json[0] !== "undefined"){  //Stumbleupon
        }
        self.options.count[name] = count;
        self.options.total += count;
        self.renderer();
        self.rendererPerso();
        //console.log(json); //debug
      })
      .error(function() { 
        self.options.count[name] = 0;
        self.rendererPerso();
       });
    }
    else{
      self.renderer();
      self.options.count[name] = 0;
      self.rendererPerso();
    }
  };
  
  /* launch render methode
  ================================================== */
  Plugin.prototype.rendererPerso = function () {
    //check if this is the last social website to launch render
    var shareCount = 0;
    for (e in this.options.count) { shareCount++; }
    if(shareCount === this.options.shareTotal){
      this.options.render(this, this.options);
    }
  };
  
  /* render methode
  ================================================== */
  Plugin.prototype.renderer = function () {
    var total = this.options.total,
    template = this.options.template;
    if(this.options.shorterTotal === true){  //format number like 1.2k or 5M
      total = this.shorterTotal(total);
    }
    
    if(template !== ''){  //if there is a template
      template = template.replace('{total}', total);
      $(this.element).html(template);
    }
    else{ //template by defaults
      $(this.element).html(
                            '<div class="box"><a class="count" href="#">' + total + '</a>' + 
                            (this.options.title !== '' ? '<a class="share" href="#">' + this.options.title + '</a>' : '') +
                            '</div>'
                          );
    }
  };
  
  /* format total numbers like 1.2k or 5M
  ================================================== */
  Plugin.prototype.shorterTotal = function (num) {
    if (num >= 1e6){
      num = (num / 1e6).toFixed(2) + "M"
    } else if (num >= 1e3){ 
      num = (num / 1e3).toFixed(1) + "k"
    }
    return num;
  };
  
  /* Methode for open popup
  ================================================== */
  Plugin.prototype.openPopup = function (site) {
    popup[site](this.options);  //open
    if(this.options.enableTracking === true){ //tracking!
      var tracking = {
        googlePlus: {site: 'Google', action: '+1'},
        facebook: {site: 'facebook', action: 'like'},
        twitter: {site: 'twitter', action: 'tweet'},
        digg: {site: 'digg', action: 'add'},
        delicious: {site: 'delicious', action: 'add'},
        stumbleupon: {site: 'stumbleupon', action: 'add'},
        linkedin: {site: 'linkedin', action: 'share'},
        pinterest: {site: 'pinterest', action: 'pin'}
      };
      _gaq.push(['_trackSocial', tracking[site].site, tracking[site].action]);
    }
  };
  
  /* Methode for add +1 to a counter
  ================================================== */
  Plugin.prototype.simulateClick = function () {
    var html = $(this.element).html();
    $(this.element).html(html.replace(this.options.total, this.options.total+1));
  };
  
  /* Methode for add +1 to a counter
  ================================================== */
  Plugin.prototype.update = function (url, text) {
    if(url !== ''){
      this.options.url = url;
    }
    if(text !== ''){
      this.options.text = text;
    }
  };

  /* A really lightweight plugin wrapper around the constructor, preventing against multiple instantiations
  ================================================== */
  $.fn[pluginName] = function ( options ) {
    var args = arguments;
    if (options === undefined || typeof options === 'object') {
      return this.each(function () {
        if (!$.data(this, 'plugin_' + pluginName)) {
          $.data(this, 'plugin_' + pluginName, new Plugin( this, options ));
        }
      });
    } else if (typeof options === 'string' && options[0] !== '_' && options !== 'init') {
      return this.each(function () {
        var instance = $.data(this, 'plugin_' + pluginName);
        if (instance instanceof Plugin && typeof instance[options] === 'function') {
          instance[options].apply( instance, Array.prototype.slice.call( args, 1 ) );
        }
      });
    }
  };
})(jQuery, window, document);

/*  
 * jQuery mmenu v4.2.7
 * @requires jQuery 1.7.0 or later
 *
 * mmenu.frebsite.nl
 *  
 * Copyright (c) Fred Heusschen
 * www.frebsite.nl
 *
 * Dual licensed under the MIT and GPL licenses.
 * http://en.wikipedia.org/wiki/MIT_License
 * http://en.wikipedia.org/wiki/GNU_General_Public_License
 */


(function( $ ) {

    var _PLUGIN_    = 'mmenu',
        _VERSION_   = '4.2.7';


    //  Plugin already excists
    if ( $[ _PLUGIN_ ] )
    {
        return;
    }

    //  Global variables
    var glbl = {
        $wndw: null,
        $html: null,
        $body: null,
        $page: null,
        $blck: null,

        $allMenus: null
    };

    var _c = {}, _d = {}, _e = {},
        _serialnr   = 0,
        _strollTop  = 0;


    $[ _PLUGIN_ ] = function( $menu, opts, conf )
    {
        glbl.$allMenus = glbl.$allMenus.add( $menu );

        this.$menu = $menu;
        this.opts  = opts
        this.conf  = conf;

        this.opened     = false;
        this.serialnr   = _serialnr++;

        this._init();

        return this;
    };

    $[ _PLUGIN_ ].prototype = {

        open: function()
        {
            if ( this.opened )
            {
                return false;
            }
            var that = this;

            this._openSetup();

            //  For some reason, some browsers need a (pretty long) delay before the .mm-opened class sets the needed styles
            //  Without it, the page isn't animated
            setTimeout(
                function()
                {
                    that._openFinish();
                }, 50
            );

            return 'open';
        },
        _openSetup: function()
        {
            _strollTop = glbl.$wndw.scrollTop() || 0;

            //  Set opened
            this.$menu.addClass( _c.current );

            //  Close others
            glbl.$allMenus.not( this.$menu ).trigger( _e.close );

            //  Store style and position
            glbl.$page.data( _d.style, glbl.$page.attr( 'style' ) || '' );

            //  Trigger window-resize to measure height
            glbl.$wndw.trigger( _e.resize, [ true ] );

            //  Add options
            if ( this.opts.modal )
            {
                glbl.$html.addClass( _c.modal );
            }
            if ( this.opts.moveBackground )
            {
                glbl.$html.addClass( _c.background );
            }
            if ( this.opts.position != 'left' )
            {
                glbl.$html.addClass( _c.mm( this.opts.position ) );
            }
            if ( this.opts.zposition != 'back' )
            {
                glbl.$html.addClass( _c.mm( this.opts.zposition ) );
            }
            if ( this.opts.classes )
            {
                glbl.$html.addClass( this.opts.classes );
            }

            //  Open
            glbl.$html.addClass( _c.opened );
            this.$menu.addClass( _c.opened );
        },
        _openFinish: function()
        {
            var that = this;

            //  Callback
            transitionend( glbl.$page,
                function()
                {
                    that.opened = true;
                    that.$menu.trigger( _e.opened );
                }, this.conf.transitionDuration
            );

            //  Opening
            glbl.$html.addClass( _c.opening );
            this.$menu.trigger( _e.opening );
        },
        close: function()
        {
            var that = this;

            if ( !this.opened )
            {
                return false;
            }

            //  Callback
            transitionend( glbl.$page,
                function()
                {
                    that.$menu
                        .removeClass( _c.current )
                        .removeClass( _c.opened );

                    glbl.$html
                        .removeClass( _c.opened )
                        .removeClass( _c.modal )
                        .removeClass( _c.background )
                        .removeClass( _c.mm( that.opts.position ) )
                        .removeClass( _c.mm( that.opts.zposition ) );

                    if ( that.opts.classes )
                    {
                        glbl.$html.removeClass( that.opts.classes );
                    }

                    //  Restore style and position
                    glbl.$page.attr( 'style', glbl.$page.data( _d.style ) );

                    that.opened = false;
                    that.$menu.trigger( _e.closed );
    
                }, this.conf.transitionDuration
            );

            //  Closing
            glbl.$html.removeClass( _c.opening );
            this.$menu.trigger( _e.closing );
    
            return 'close';
        },
    
        _init: function()
        {
            this.opts = extendOptions( this.opts, this.conf, this.$menu );
            this.direction = ( this.opts.slidingSubmenus ) ? 'horizontal' : 'vertical';
    
            //  INIT PAGE & MENU
            this._initPage( glbl.$page );
            this._initMenu();
            this._initBlocker();
            this._initPanles();
            this._initLinks();
            this._initOpenClose();
            this._bindCustomEvents();

            if ( $[ _PLUGIN_ ].addons )
            {
                for ( var a = 0; a < $[ _PLUGIN_ ].addons.length; a++ )
                {
                    if ( typeof this[ '_addon_' + $[ _PLUGIN_ ].addons[ a ] ] == 'function' )
                    {
                        this[ '_addon_' + $[ _PLUGIN_ ].addons[ a ] ]();
                    }
                }
            }
        },

        _bindCustomEvents: function()
        {
            var that = this;

            this.$menu
                .off( _e.open + ' ' + _e.close + ' ' + _e.setPage+ ' ' + _e.update )
                .on( _e.open + ' ' + _e.close + ' ' + _e.setPage+ ' ' + _e.update,
                    function( e )
                    {
                        e.stopPropagation();
                    }
                );

            //  Menu-events
            this.$menu
                .on( _e.open,
                    function( e )
                    {
                        if ( $(this).hasClass( _c.current ) )
                        {
                            e.stopImmediatePropagation();
                            return false;
                        }
                        return that.open();
                    }
                )
                .on( _e.close,
                    function( e )
                    {
                        if ( !$(this).hasClass( _c.current ) )
                        {
                            e.stopImmediatePropagation();
                            return false;
                        }
                        return that.close();
                    }
                )
                .on( _e.setPage,
                    function( e, $p )
                    {
                        that._initPage( $p );
                        that._initOpenClose();
                    }
                );

            //  Panel-events
            var $panels = this.$menu.find( this.opts.isMenu && this.direction != 'horizontal' ? 'ul, ol' : '.' + _c.panel );
            $panels
                .off( _e.toggle + ' ' + _e.open + ' ' + _e.close )
                .on( _e.toggle + ' ' + _e.open + ' ' + _e.close,
                    function( e )
                    {
                        e.stopPropagation();
                    }
                );

            if ( this.direction == 'horizontal' )
            {
                $panels
                    .on( _e.open,
                        function( e )
                        {
                            return openSubmenuHorizontal( $(this), that.$menu );
                        }
                    );
            }
            else
            {
                $panels
                    .on( _e.toggle,
                        function( e )
                        {
                            var $t = $(this);
                            return $t.triggerHandler( $t.parent().hasClass( _c.opened ) ? _e.close : _e.open );
                        }
                    )
                    .on( _e.open,
                        function( e )
                        {
                            $(this).parent().addClass( _c.opened );
                            return 'open';
                        }
                    )
                    .on( _e.close,
                        function( e )
                        {
                            $(this).parent().removeClass( _c.opened );
                            return 'close';
                        }
                    );
            }
        },
        
        _initBlocker: function()
        {
            var that = this;

            if ( !glbl.$blck )
            {
                glbl.$blck = $( '<div id="' + _c.blocker + '" />' )
                    .appendTo( glbl.$body );
            }

            glbl.$blck
                .off( _e.touchstart )
                .on( _e.touchstart,
                    function( e )
                    {
                        e.preventDefault();
                        e.stopPropagation();
                        glbl.$blck.trigger( _e.mousedown );
                    }
                )
                .on( _e.mousedown,
                    function( e )
                    {
                        e.preventDefault();
                        if ( !glbl.$html.hasClass( _c.modal ) )
                        {
                            that.$menu.trigger( _e.close );
                        }
                    }
                );
        },
        _initPage: function( $p )
        {
            if ( !$p )
            {
                $p = $(this.conf.pageSelector, glbl.$body);
                if ( $p.length > 1 )
                {
                    $[ _PLUGIN_ ].debug( 'Multiple nodes found for the page-node, all nodes are wrapped in one <' + this.conf.pageNodetype + '>.' );
                    $p = $p.wrapAll( '<' + this.conf.pageNodetype + ' />' ).parent();
                }
            }
    
            $p.addClass( _c.page );
            glbl.$page = $p;
        },
        _initMenu: function()
        {
            var that = this;

            //  Clone if needed
            if ( this.conf.clone )
            {
                this.$menu = this.$menu.clone( true );
                this.$menu.add( this.$menu.find( '*' ) ).filter( '[id]' ).each(
                    function()
                    {
                        $(this).attr( 'id', _c.mm( $(this).attr( 'id' ) ) );
                    }
                );
            }

            //  Strip whitespace
            this.$menu.contents().each(
                function()
                {
                    if ( $(this)[ 0 ].nodeType == 3 )
                    {
                        $(this).remove();
                    }
                }
            );

            //  Inject to body
            this.$menu[ this.conf.menuInjectMethod + 'To' ]( this.conf.menuWrapperSelector )
                .addClass( _c.menu );

            //  Add direction class
            this.$menu.addClass( _c.mm( this.direction ) );

            //  Add options classes
            if ( this.opts.classes )
            {
                this.$menu.addClass( this.opts.classes );
            }
            if ( this.opts.isMenu )
            {
                this.$menu.addClass( _c.ismenu );
            }
            if ( this.opts.position != 'left' )
            {
                this.$menu.addClass( _c.mm( this.opts.position ) );
            }
            if ( this.opts.zposition != 'back' )
            {
                this.$menu.addClass( _c.mm( this.opts.zposition ) );
            }
        },
        _initPanles: function()
        {
            var that = this;


            //  Refactor List class
            this.__refactorClass( $('.' + this.conf.listClass, this.$menu), 'list' );

            //  Add List class
            if ( this.opts.isMenu )
            {
                $('ul, ol', this.$menu)
                    .not( '.mm-nolist' )
                    .addClass( _c.list );
            }

            var $lis = $('.' + _c.list + ' > li', this.$menu);

            //  Refactor Selected class
            this.__refactorClass( $lis.filter( '.' + this.conf.selectedClass ), 'selected' );

            //  Refactor Label class
            this.__refactorClass( $lis.filter( '.' + this.conf.labelClass ), 'label' );

            //  Refactor Spacer class
            this.__refactorClass( $lis.filter( '.' + this.conf.spacerClass ), 'spacer' );

            //  setSelected-event
            $lis
                .off( _e.setSelected )
                .on( _e.setSelected,
                    function( e, selected )
                    {
                        e.stopPropagation();
    
                        $lis.removeClass( _c.selected );
                        if ( typeof selected != 'boolean' )
                        {
                            selected = true;
                        }
                        if ( selected )
                        {
                            $(this).addClass( _c.selected );
                        }
                    }
                );

            //  Refactor Panel class
            this.__refactorClass( $('.' + this.conf.panelClass, this.$menu), 'panel' );

            //  Add Panel class
            this.$menu
                .children()
                .filter( this.conf.panelNodetype )
                .add( this.$menu.find( '.' + _c.list ).children().children().filter( this.conf.panelNodetype ) )
                .addClass( _c.panel );

            var $panels = $('.' + _c.panel, this.$menu);

            //  Add an ID to all panels
            $panels
                .each(
                    function( i )
                    {
                        var $t = $(this),
                            id = $t.attr( 'id' ) || _c.mm( 'm' + that.serialnr + '-p' + i );

                        $t.attr( 'id', id );
                    }
            );

            //  Add open and close links to menu items
            $panels
                .find( '.' + _c.panel )
                .each(
                    function( i )
                    {
                        var $t = $(this),
                            $u = $t.is( 'ul, ol' ) ? $t : $t.find( 'ul ,ol' ).first(),
                            $l = $t.parent(),
                            $a = $l.find( '> a, > span' ),
                            $p = $l.closest( '.' + _c.panel );

                        $t.data( _d.parent, $l );

                        if ( $l.parent().is( '.' + _c.list ) )
                        {
                            var $btn = $( '<a class="' + _c.subopen + '" href="#' + $t.attr( 'id' ) + '" />' ).insertBefore( $a );
                            if ( !$a.is( 'a' ) )
                            {
                                $btn.addClass( _c.fullsubopen );
                            }
                            if ( that.direction == 'horizontal' )
                            {
                                $u.prepend( '<li class="' + _c.subtitle + '"><a class="' + _c.subclose + '" href="#' + $p.attr( 'id' ) + '">' + $a.text() + '</a></li>' );
                            }
                        }
                    }
                );

            //  Link anchors to panels
            var evt = this.direction == 'horizontal' ? _e.open : _e.toggle;
            $panels
                .each(
                    function( i )
                    {
                        var $opening = $(this),
                            id = $opening.attr( 'id' );

                        $('a[href="#' + id + '"]', that.$menu)
                            .off( _e.click )
                            .on( _e.click,
                                function( e )
                                {
                                    e.preventDefault();
                                    $opening.trigger( evt );
                                }
                            );
                    }
            );

            if ( this.direction == 'horizontal' )
            {
                //  Add opened-classes
                var $selected = $('.' + _c.list + ' > li.' + _c.selected, this.$menu);
                $selected
                    .parents( 'li' )
                    .removeClass( _c.selected )
                    .end()
                    .add( $selected.parents( 'li' ) )
                    .each(
                        function()
                        {
                            var $t = $(this),
                                $u = $t.find( '> .' + _c.panel );

                            if ( $u.length )
                            {
                                $t.parents( '.' + _c.panel ).addClass( _c.subopened );
                                $u.addClass( _c.opened );
                            }
                        }
                    )
                    .closest( '.' + _c.panel )
                    .addClass( _c.opened )
                    .parents( '.' + _c.panel )
                    .addClass( _c.subopened );
            }
            else
            {
                //  Replace Selected-class with opened-class in parents from .Selected
                var $selected = $('li.' + _c.selected, this.$menu);
                $selected
                    .parents( 'li' )
                    .removeClass( _c.selected )
                    .end()
                    .add( $selected.parents( 'li' ) )
                    .addClass( _c.opened );
            }

            //  Set current opened
            var $current = $panels.filter( '.' + _c.opened );
            if ( !$current.length )
            {
                $current = $panels.first();
            }
            $current
                .addClass( _c.opened )
                .last()
                .addClass( _c.current );

            //  Rearrange markup
            if ( this.direction == 'horizontal' )
            {
                $panels.find( '.' + _c.panel ).appendTo( this.$menu );
            }
        },
        _initLinks: function()
        {
            var that = this;
    
            $('.' + _c.list + ' > li > a', this.$menu)
                .not( '.' + _c.subopen )
                .not( '.' + _c.subclose )
                .not( '[rel="external"]' )
                .not( '[target="_blank"]' )
                .off( _e.click )
                .on( _e.click,
                    function( e )
                    {
                        var $t = $(this),
                            href = $t.attr( 'href' );

                        //  Set selected item
                        if ( that.__valueOrFn( that.opts.onClick.setSelected, $t ) )
                        {
                            $t.parent().trigger( _e.setSelected );
                        }

                        //  Prevent default / don't follow link. Default: false
                        var preventDefault = that.__valueOrFn( that.opts.onClick.preventDefault, $t, href.slice( 0, 1 ) == '#' );
                        if ( preventDefault )
                        {
                            e.preventDefault();
                        }

                        //  Block UI. Default: false if preventDefault, true otherwise
                        if ( that.__valueOrFn( that.opts.onClick.blockUI, $t, !preventDefault ) )
                        {
                            glbl.$html.addClass( _c.blocking );
                        }

                        //  Close menu. Default: true if preventDefault, false otherwise
                        if ( that.__valueOrFn( that.opts.onClick.close, $t, preventDefault ) )
                        {
                            that.$menu.triggerHandler( _e.close );
                        }
                    }
                );
        },
        _initOpenClose: function()
        {
            var that = this;

            //  Open menu
            var id = this.$menu.attr( 'id' );
            if ( id && id.length )
            {
                if ( this.conf.clone )
                {
                    id = _c.umm( id );
                }

                $('a[href="#' + id + '"]')
                    .off( _e.click )
                    .on( _e.click,
                        function( e )
                        {
                            e.preventDefault();
                            that.$menu.trigger( _e.open );
                        }
                    );
            }

            //  Close menu
            var id = glbl.$page.attr( 'id' );
            if ( id && id.length )
            {
                $('a[href="#' + id + '"]')
                    .on( _e.click,
                        function( e )
                        {
                            e.preventDefault();
                            that.$menu.trigger( _e.close );
                        }
                    );
            }
        },
        
        __valueOrFn: function( o, $e, d )
        {
            if ( typeof o == 'function' )
            {
                return o.call( $e[ 0 ] );
            }
            if ( typeof o == 'undefined' && typeof d != 'undefined' )
            {
                return d;
            }
            return o;
        },
        
        __refactorClass: function( $e, c )
        {
            $e.removeClass( this.conf[ c + 'Class' ] ).addClass( _c[ c ] );
        }
    };


    $.fn[ _PLUGIN_ ] = function( opts, conf )
    {
        //  First time plugin is fired
        if ( !glbl.$wndw )
        {
            _initPlugin();
        }

        //  Extend options
        opts = extendOptions( opts, conf );
        conf = extendConfiguration( conf );

        return this.each(
            function()
            {
                var $menu = $(this);
                if ( $menu.data( _PLUGIN_ ) )
                {
                    return;
                }
                $menu.data( _PLUGIN_, new $[ _PLUGIN_ ]( $menu, opts, conf ) );
            }
        );
    };

    $[ _PLUGIN_ ].version = _VERSION_;
    
    $[ _PLUGIN_ ].defaults = {
        position        : 'left',
        zposition       : 'back',
        moveBackground  : true,
        slidingSubmenus : true,
        modal           : false,
        classes         : '',
        onClick         : {
//          close               : true,
//          blockUI             : null,
//          preventDefault      : null,
            setSelected         : true
        }
    };
    $[ _PLUGIN_ ].configuration = {
        panelClass          : 'Panel',
        listClass           : 'List',
        selectedClass       : 'Selected',
        labelClass          : 'Label',
        spacerClass         : 'Spacer',
        pageNodetype        : 'div',
        panelNodetype       : 'ul, ol, div',
        pageSelector        : null,
        menuWrapperSelector : 'body',
        menuInjectMethod    : 'prepend',
        transitionDuration  : 400
    };



    /*
        SUPPORT
    */
    (function() {

        var wd = window.document,
            ua = window.navigator.userAgent,
            ds = document.createElement( 'div' ).style;

        var _touch              = 'ontouchstart' in wd,
            _overflowscrolling  = 'WebkitOverflowScrolling' in wd.documentElement.style,
            _oldAndroidBrowser  = (function() {
                if ( ua.indexOf( 'Android' ) >= 0 )
                {
                    return 2.4 > parseFloat( ua.slice( ua.indexOf( 'Android' ) +8 ) );
                }
                return false;
            })();

        $[ _PLUGIN_ ].support = {

            touch: _touch,
            oldAndroidBrowser: _oldAndroidBrowser,
            overflowscrolling: (function() {
                if ( !_touch )
                {
                    return true;
                }
                if ( _overflowscrolling )
                {
                    return true;
                }
                if ( _oldAndroidBrowser )
                {
                    return false;
                }
                return true;
            })()
        };
    })();


    /*
        DEBUG
    */
    $[ _PLUGIN_ ].debug = function( msg ) {};
    $[ _PLUGIN_ ].deprecated = function( depr, repl )
    {
        if ( typeof console != 'undefined' && typeof console.warn != 'undefined' )
        {
            console.warn( 'MMENU: ' + depr + ' is deprecated, use ' + repl + ' instead.' );
        }
    };


    function extendOptions( o, c, $m )
    {

        if ( $m )
        {
            if ( typeof o != 'object' )
            {
                o = {};
            }
            if ( typeof o.isMenu != 'boolean' )
            {
                var $c = $m.children();
                o.isMenu = ( $c.length == 1 && $c.is( c.panelNodetype ) );
            }
            return o;
        }
        
        //  Extend from defaults
        o = $.extend( true, {}, $[ _PLUGIN_ ].defaults, o );


        //  DEPRECATED
        if ( o.position == 'top' || o.position == 'bottom' )
        {
            if ( o.zposition == 'back' || o.zposition == 'next' )
            {
                $[ _PLUGIN_ ].deprecated( 'Using position "' + o.position + '" in combination with zposition "' + o.zposition + '"', 'zposition "front"' );
                o.zposition = 'front';
            }
        }
        //  /DEPRECATED

        return o;
    }
    function extendConfiguration( c )
    {
        c = $.extend( true, {}, $[ _PLUGIN_ ].configuration, c )

        //  Set pageSelector
        if ( typeof c.pageSelector != 'string' )
        {
            c.pageSelector = '> ' + c.pageNodetype;
        }

        //  Restrict injectMethod
        if ( c.menuInjectMethod != 'append' )
        {
            c.menuInjectMethod = 'prepend';
        }

        return c;
    }

    function _initPlugin()
    {
        glbl.$wndw = $(window);
        glbl.$html = $('html');
        glbl.$body = $('body');
        
        glbl.$allMenus = $();


        //  Classnames, Datanames, Eventnames
        $.each( [ _c, _d, _e ],
            function( i, o )
            {
                o.add = function( c )
                {
                    c = c.split( ' ' );
                    for ( var d in c )
                    {
                        o[ c[ d ] ] = o.mm( c[ d ] );
                    }
                };
            }
        );

        //  Classnames
        _c.mm = function( c ) { return 'mm-' + c; };
        _c.add( 'menu ismenu panel list subtitle selected label spacer current highest hidden page blocker modal background opened opening subopened subopen fullsubopen subclose' );
        _c.umm = function( c )
        {
            if ( c.slice( 0, 3 ) == 'mm-' )
            {
                c = c.slice( 3 );
            }
            return c;
        };

        //  Datanames
        _d.mm = function( d ) { return 'mm-' + d; };
        _d.add( 'parent style' );

        //  Eventnames
        _e.mm = function( e ) { return e + '.mm'; };
        _e.add( 'toggle open opening opened close closing closed update setPage setSelected transitionend webkitTransitionEnd mousedown touchstart mouseup touchend scroll touchmove click keydown keyup resize' );


        //  Prevent tabbing
        glbl.$wndw
            .on( _e.keydown,
                function( e )
                {
                    if ( glbl.$html.hasClass( _c.opened ) )
                    {
                        if ( e.keyCode == 9 )
                        {
                            e.preventDefault();
                            return false;
                        }
                    }
                }
            );

        //  Set page min-height to window height
        var _h = 0;
        glbl.$wndw
            .on( _e.resize,
                function( e, force )
                {
                    if ( force || glbl.$html.hasClass( _c.opened ) )
                    {
                        var nh = glbl.$wndw.height();
                        if ( force || nh != _h )
                        {
                            _h = nh;
                            glbl.$page.css( 'minHeight', nh );
                        }
                    }
                }
            );


        $[ _PLUGIN_ ]._c = _c;
        $[ _PLUGIN_ ]._d = _d;
        $[ _PLUGIN_ ]._e = _e;

        $[ _PLUGIN_ ].glbl = glbl;
    }

    function openSubmenuHorizontal( $opening, $m )
    {
        if ( $opening.hasClass( _c.current ) )
        {
            return false;
        } 

        var $panels = $('.' + _c.panel, $m),
            $current = $panels.filter( '.' + _c.current );
        
        $panels
            .removeClass( _c.highest )
            .removeClass( _c.current )
            .not( $opening )
            .not( $current )
            .addClass( _c.hidden );

        if ( $opening.hasClass( _c.opened ) )
        {
            $current
                .addClass( _c.highest )
                .removeClass( _c.opened )
                .removeClass( _c.subopened );
        }
        else
        {
            $opening
                .addClass( _c.highest );

            $current
                .addClass( _c.subopened );
        }

        $opening
            .removeClass( _c.hidden )
            .removeClass( _c.subopened )
            .addClass( _c.current )
            .addClass( _c.opened );

        return 'open';
    }

    function transitionend( $e, fn, duration )
    {
        var _ended = false,
            _fn = function()
            {
                if ( !_ended )
                {
                    fn.call( $e[ 0 ] );
                }
                _ended = true;
            };

        $e.one( _e.transitionend, _fn );
        $e.one( _e.webkitTransitionEnd, _fn );
        setTimeout( _fn, duration * 1.1 );
    }

})( jQuery );

/*
 * jQuery Superfish Menu Plugin - v1.7.4
 * Copyright (c) 2013 Joel Birch
 *
 * Dual licensed under the MIT and GPL licenses:
 *  http://www.opensource.org/licenses/mit-license.php
 *  http://www.gnu.org/licenses/gpl.html
 */

;(function ($) {
    "use strict";

    var methods = (function () {
        // private properties and methods go here
        var c = {
                bcClass: 'sf-breadcrumb',
                menuClass: 'sf-js-enabled',
                anchorClass: 'sf-with-ul',
                menuArrowClass: 'sf-arrows'
            },
            ios = (function () {
                var ios = /iPhone|iPad|iPod/i.test(navigator.userAgent);
                if (ios) {
                    // iOS clicks only bubble as far as body children
                    $(window).load(function () {
                        $('body').children().on('click', $.noop);
                    });
                }
                return ios;
            })(),
            wp7 = (function () {
                var style = document.documentElement.style;
                return ('behavior' in style && 'fill' in style && /iemobile/i.test(navigator.userAgent));
            })(),
            toggleMenuClasses = function ($menu, o) {
                var classes = c.menuClass;
                if (o.cssArrows) {
                    classes += ' ' + c.menuArrowClass;
                }
                $menu.toggleClass(classes);
            },
            setPathToCurrent = function ($menu, o) {
                return $menu.find('li.' + o.pathClass).slice(0, o.pathLevels)
                    .addClass(o.hoverClass + ' ' + c.bcClass)
                        .filter(function () {
                            return ($(this).children(o.popUpSelector).hide().show().length);
                        }).removeClass(o.pathClass);
            },
            toggleAnchorClass = function ($li) {
                $li.children('a').toggleClass(c.anchorClass);
            },
            toggleTouchAction = function ($menu) {
                var touchAction = $menu.css('ms-touch-action');
                touchAction = (touchAction === 'pan-y') ? 'auto' : 'pan-y';
                $menu.css('ms-touch-action', touchAction);
            },
            applyHandlers = function ($menu, o) {
                var targets = 'li:has(' + o.popUpSelector + ')';
                if ($.fn.hoverIntent && !o.disableHI) {
                    $menu.hoverIntent(over, out, targets);
                }
                else {
                    $menu
                        .on('mouseenter.superfish', targets, over)
                        .on('mouseleave.superfish', targets, out);
                }
                var touchevent = 'MSPointerDown.superfish';
                if (!ios) {
                    touchevent += ' touchend.superfish';
                }
                if (wp7) {
                    touchevent += ' mousedown.superfish';
                }
                $menu
                    .on('focusin.superfish', 'li', over)
                    .on('focusout.superfish', 'li', out)
                    .on(touchevent, 'a', o, touchHandler);
            },
            touchHandler = function (e) {
                var $this = $(this),
                    $ul = $this.siblings(e.data.popUpSelector);

                if ($ul.length > 0 && $ul.is(':hidden')) {
                    $this.one('click.superfish', false);
                    if (e.type === 'MSPointerDown') {
                        $this.trigger('focus');
                    } else {
                        $.proxy(over, $this.parent('li'))();
                    }
                }
            },
            over = function () {
                var $this = $(this),
                    o = getOptions($this);
                clearTimeout(o.sfTimer);
                $this.siblings().superfish('hide').end().superfish('show');
            },
            out = function () {
                var $this = $(this),
                    o = getOptions($this);
                if (ios) {
                    $.proxy(close, $this, o)();
                }
                else {
                    clearTimeout(o.sfTimer);
                    o.sfTimer = setTimeout($.proxy(close, $this, o), o.delay);
                }
            },
            close = function (o) {
                o.retainPath = ($.inArray(this[0], o.$path) > -1);
                this.superfish('hide');

                if (!this.parents('.' + o.hoverClass).length) {
                    o.onIdle.call(getMenu(this));
                    if (o.$path.length) {
                        $.proxy(over, o.$path)();
                    }
                }
            },
            getMenu = function ($el) {
                return $el.closest('.' + c.menuClass);
            },
            getOptions = function ($el) {
                return getMenu($el).data('sf-options');
            };

        return {
            // public methods
            hide: function (instant) {
                if (this.length) {
                    var $this = this,
                        o = getOptions($this);
                    if (!o) {
                        return this;
                    }
                    var not = (o.retainPath === true) ? o.$path : '',
                        $ul = $this.find('li.' + o.hoverClass).add(this).not(not).removeClass(o.hoverClass).children(o.popUpSelector),
                        speed = o.speedOut;

                    if (instant) {
                        $ul.show();
                        speed = 0;
                    }
                    o.retainPath = false;
                    o.onBeforeHide.call($ul);
                    $ul.stop(true, true).animate(o.animationOut, speed, function () {
                        var $this = $(this);
                        o.onHide.call($this);
                    });
                }
                return this;
            },
            show: function () {
                var o = getOptions(this);
                if (!o) {
                    return this;
                }
                var $this = this.addClass(o.hoverClass),
                    $ul = $this.children(o.popUpSelector);

                o.onBeforeShow.call($ul);
                $ul.stop(true, true).animate(o.animation, o.speed, function () {
                    o.onShow.call($ul);
                });
                return this;
            },
            destroy: function () {
                return this.each(function () {
                    var $this = $(this),
                        o = $this.data('sf-options'),
                        $hasPopUp;
                    if (!o) {
                        return false;
                    }
                    $hasPopUp = $this.find(o.popUpSelector).parent('li');
                    clearTimeout(o.sfTimer);
                    toggleMenuClasses($this, o);
                    toggleAnchorClass($hasPopUp);
                    toggleTouchAction($this);
                    // remove event handlers
                    $this.off('.superfish').off('.hoverIntent');
                    // clear animation's inline display style
                    $hasPopUp.children(o.popUpSelector).attr('style', function (i, style) {
                        return style.replace(/display[^;]+;?/g, '');
                    });
                    // reset 'current' path classes
                    o.$path.removeClass(o.hoverClass + ' ' + c.bcClass).addClass(o.pathClass);
                    $this.find('.' + o.hoverClass).removeClass(o.hoverClass);
                    o.onDestroy.call($this);
                    $this.removeData('sf-options');
                });
            },
            init: function (op) {
                return this.each(function () {
                    var $this = $(this);
                    if ($this.data('sf-options')) {
                        return false;
                    }
                    var o = $.extend({}, $.fn.superfish.defaults, op),
                        $hasPopUp = $this.find(o.popUpSelector).parent('li');
                    o.$path = setPathToCurrent($this, o);

                    $this.data('sf-options', o);

                    toggleMenuClasses($this, o);
                    toggleAnchorClass($hasPopUp);
                    toggleTouchAction($this);
                    applyHandlers($this, o);

                    $hasPopUp.not('.' + c.bcClass).superfish('hide', true);

                    o.onInit.call(this);
                });
            }
        };
    })();

    $.fn.superfish = function (method, args) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || ! method) {
            return methods.init.apply(this, arguments);
        }
        else {
            return $.error('Method ' +  method + ' does not exist on jQuery.fn.superfish');
        }
    };

    $.fn.superfish.defaults = {
        popUpSelector: 'ul,.sf-mega', // within menu context
        hoverClass: 'sfHover',
        pathClass: 'overrideThisToUse',
        pathLevels: 1,
        delay: 800,
        animation: {opacity: 'show'},
        animationOut: {opacity: 'hide'},
        speed: 'normal',
        speedOut: 'fast',
        cssArrows: true,
        disableHI: false,
        onInit: $.noop,
        onBeforeShow: $.noop,
        onShow: $.noop,
        onBeforeHide: $.noop,
        onHide: $.noop,
        onIdle: $.noop,
        onDestroy: $.noop
    };

    // soon to be deprecated
    $.fn.extend({
        hideSuperfishUl: methods.hide,
        showSuperfishUl: methods.show
    });

})(jQuery);
