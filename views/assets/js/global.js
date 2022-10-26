/* Fake Loader */
!function(d){d.fakeLoader=function(i){var s=d.extend({targetClass:"fakeLoader",timeToHide:1200,bgColor:"#2ecc71",spinner:"spinner2"},i),c='<div class="fl fl-spinner spinner1"><div class="double-bounce1"></div><div class="double-bounce2"></div></div>',e=d("body").find("."+s.targetClass);e.each(function(){switch(s.spinner){case"spinner1":e.html(c);break;case"spinner2":e.html('<div class="fl fl-spinner spinner2"><div class="spinner-container container1"><div class="circle1"></div><div class="circle2"></div><div class="circle3"></div><div class="circle4"></div></div><div class="spinner-container container2"><div class="circle1"></div><div class="circle2"></div><div class="circle3"></div><div class="circle4"></div></div><div class="spinner-container container3"><div class="circle1"></div><div class="circle2"></div><div class="circle3"></div><div class="circle4"></div></div></div>');break;case"spinner3":e.html('<div class="fl fl-spinner spinner3"><div class="dot1"></div><div class="dot2"></div></div>');break;case"spinner4":e.html('<div class="fl fl-spinner spinner4"></div>');break;case"spinner5":e.html('<div class="fl fl-spinner spinner5"><div class="cube1"></div><div class="cube2"></div></div>');break;case"spinner6":e.html('<div class="fl fl-spinner spinner6"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div>');break;case"spinner7":e.html('<div class="fl fl-spinner spinner7"><div class="circ1"></div><div class="circ2"></div><div class="circ3"></div><div class="circ4"></div></div>');break;default:e.html(c)}}),e.css({backgroundColor:s.bgColor}),setTimeout(function(){d(e).fadeOut()},s.timeToHide)}}(jQuery);
/* Fake Loader */
/* Toast */
"function"!=typeof Object.create&&(Object.create=function(t){function o(){}return o.prototype=t,new o}),function(t,o,i,s){"use strict";var n={_positionClasses:["bottom-left","bottom-right","top-right","top-left","bottom-center","top-center","mid-center"],_defaultIcons:["success","error","info","warning"],init:function(o,i){this.prepareOptions(o,t.toast.options),this.process()},prepareOptions:function(o,i){var s={};"string"==typeof o||o instanceof Array?s.text=o:s=o,this.options=t.extend({},i,s)},process:function(){this.setup(),this.addToDom(),this.position(),this.bindToast(),this.animate()},setup:function(){var o="";if(this._toastEl=this._toastEl||t("<div></div>",{class:"jq-toast-single"}),o+='<span class="jq-toast-loader"></span>',this.options.allowToastClose&&(o+='<span class="close-jq-toast-single">&times;</span>'),this.options.text instanceof Array){this.options.heading&&(o+='<h2 class="jq-toast-heading">'+this.options.heading+"</h2>"),o+='<ul class="jq-toast-ul">';for(var i=0;i<this.options.text.length;i++)o+='<li class="jq-toast-li" id="jq-toast-item-'+i+'">'+this.options.text[i]+"</li>";o+="</ul>"}else this.options.heading&&(o+='<h2 class="jq-toast-heading">'+this.options.heading+"</h2>"),o+=this.options.text;this._toastEl.html(o),!1!==this.options.bgColor&&this._toastEl.css("background-color",this.options.bgColor),!1!==this.options.textColor&&this._toastEl.css("color",this.options.textColor),this.options.textAlign&&this._toastEl.css("text-align",this.options.textAlign),!1!==this.options.icon&&(this._toastEl.addClass("jq-has-icon"),-1!==t.inArray(this.options.icon,this._defaultIcons)&&this._toastEl.addClass("jq-icon-"+this.options.icon)),!1!==this.options.class&&this._toastEl.addClass(this.options.class)},position:function(){"string"==typeof this.options.position&&-1!==t.inArray(this.options.position,this._positionClasses)?"bottom-center"===this.options.position?this._container.css({left:t(o).outerWidth()/2-this._container.outerWidth()/2,bottom:20}):"top-center"===this.options.position?this._container.css({left:t(o).outerWidth()/2-this._container.outerWidth()/2,top:20}):"mid-center"===this.options.position?this._container.css({left:t(o).outerWidth()/2-this._container.outerWidth()/2,top:t(o).outerHeight()/2-this._container.outerHeight()/2}):this._container.addClass(this.options.position):"object"==typeof this.options.position?this._container.css({top:this.options.position.top?this.options.position.top:"auto",bottom:this.options.position.bottom?this.options.position.bottom:"auto",left:this.options.position.left?this.options.position.left:"auto",right:this.options.position.right?this.options.position.right:"auto"}):this._container.addClass("bottom-left")},bindToast:function(){var t=this;this._toastEl.on("afterShown",function(){t.processLoader()}),this._toastEl.find(".close-jq-toast-single").on("click",function(o){o.preventDefault(),"fade"===t.options.showHideTransition?(t._toastEl.trigger("beforeHide"),t._toastEl.fadeOut(function(){t._toastEl.trigger("afterHidden")})):"slide"===t.options.showHideTransition?(t._toastEl.trigger("beforeHide"),t._toastEl.slideUp(function(){t._toastEl.trigger("afterHidden")})):(t._toastEl.trigger("beforeHide"),t._toastEl.hide(function(){t._toastEl.trigger("afterHidden")}))}),"function"==typeof this.options.beforeShow&&this._toastEl.on("beforeShow",function(){t.options.beforeShow(t._toastEl)}),"function"==typeof this.options.afterShown&&this._toastEl.on("afterShown",function(){t.options.afterShown(t._toastEl)}),"function"==typeof this.options.beforeHide&&this._toastEl.on("beforeHide",function(){t.options.beforeHide(t._toastEl)}),"function"==typeof this.options.afterHidden&&this._toastEl.on("afterHidden",function(){t.options.afterHidden(t._toastEl)}),"function"==typeof this.options.onClick&&this._toastEl.on("click",function(){t.options.onClick(t._toastEl)})},addToDom:function(){var o=t(".jq-toast-wrap");if(0===o.length?(o=t("<div></div>",{class:"jq-toast-wrap",role:"alert","aria-live":"polite"}),t("body").append(o)):this.options.stack&&!isNaN(parseInt(this.options.stack,10))||o.empty(),o.find(".jq-toast-single:hidden").remove(),o.append(this._toastEl),this.options.stack&&!isNaN(parseInt(this.options.stack),10)){var i=o.find(".jq-toast-single").length-this.options.stack;i>0&&t(".jq-toast-wrap").find(".jq-toast-single").slice(0,i).remove()}this._container=o},canAutoHide:function(){return!1!==this.options.hideAfter&&!isNaN(parseInt(this.options.hideAfter,10))},processLoader:function(){if(!this.canAutoHide()||!1===this.options.loader)return!1;var t=this._toastEl.find(".jq-toast-loader"),o=(this.options.hideAfter-400)/1e3+"s",i=this.options.loaderBg,s=t.attr("style")||"";s=s.substring(0,s.indexOf("-webkit-transition")),s+="-webkit-transition: width "+o+" ease-in;                       -o-transition: width "+o+" ease-in;                       transition: width "+o+" ease-in;                       background-color: "+i+";",t.attr("style",s).addClass("jq-toast-loaded")},animate:function(){t=this;if(this._toastEl.hide(),this._toastEl.trigger("beforeShow"),"fade"===this.options.showHideTransition.toLowerCase()?this._toastEl.fadeIn(function(){t._toastEl.trigger("afterShown")}):"slide"===this.options.showHideTransition.toLowerCase()?this._toastEl.slideDown(function(){t._toastEl.trigger("afterShown")}):this._toastEl.show(function(){t._toastEl.trigger("afterShown")}),this.canAutoHide()){var t=this;o.setTimeout(function(){"fade"===t.options.showHideTransition.toLowerCase()?(t._toastEl.trigger("beforeHide"),t._toastEl.fadeOut(function(){t._toastEl.trigger("afterHidden")})):"slide"===t.options.showHideTransition.toLowerCase()?(t._toastEl.trigger("beforeHide"),t._toastEl.slideUp(function(){t._toastEl.trigger("afterHidden")})):(t._toastEl.trigger("beforeHide"),t._toastEl.hide(function(){t._toastEl.trigger("afterHidden")}))},this.options.hideAfter)}},reset:function(o){"all"===o?t(".jq-toast-wrap").remove():this._toastEl.remove()},update:function(t){this.prepareOptions(t,this.options),this.setup(),this.bindToast()},close:function(){this._toastEl.find(".close-jq-toast-single").click()}};t.toast=function(t){var o=Object.create(n);return o.init(t,this),{reset:function(t){o.reset(t)},update:function(t){o.update(t)},close:function(){o.close()}}},t.toast.options={text:"",heading:"",showHideTransition:"fade",allowToastClose:!0,hideAfter:3e3,loader:!0,loaderBg:"#9EC600",stack:5,position:"bottom-left",bgColor:!1,textColor:!1,textAlign:"left",icon:!1,beforeShow:function(){},afterShown:function(){},beforeHide:function(){},afterHidden:function(){},onClick:function(){}}}(jQuery,window,document);
/**********/

/* Awesome Selectbox */
var awselect_count=0,mobile_width=800;!function(t){function e(e){return t('.awselect[data-select="'+e.attr("id")+'"]')}function a(e,a){var i=e.attr("data-placeholder"),n=e.attr("id"),s=e.children("option"),o=!1,l="awselect",c="",d=a.background,f=a.active_background,u=a.placeholder_color,h=a.placeholder_active_color,v=a.option_color,m=a.vertical_padding,w=a.horizontal_padding,p=a.immersive;if(p!==!0)var p=!1;s.each(function(){"undefined"!=typeof t(this).attr("selected")&&t(this).attr("selected")!==!1&&(o=t(this).text()),c+='<li><a style="padding: 2px '+w+'">'+t(this).text()+"</a></li>"}),o!==!1&&(l+=" hasValue"),"undefined"!=typeof n&&n!==!1?id_html=n:(id_html="awselect_"+awselect_count,t(e).attr("id",id_html));var _='<div data-immersive="'+p+'" id="awselect_'+id_html+'" data-select="'+id_html+'" class = "'+l+'"><div style="background:'+f+'" class = "bg"></div>';_+='<div style="padding:'+m+" "+w+'" class = "front_face">',_+='<div style="background:'+d+'" class = "bg"></div>',_+='<div data-inactive-color="'+h+'" style="color:'+u+'" class = "content">',o!==!1&&(_+='<span class="current_value">'+o+"</span>"),_+='<span class = "placeholder">'+i+"</span>",_+='<i class = "icon">'+r(u)+"</i>",_+="</div>",_+="</div>",_+='<div style="padding:'+m+' 0;" class = "back_face"><ul style="color:'+v+'">',_+=c,_+="</ul></div>",_+="</div>",t(_).insertAfter(e),e.hide()}function i(e){if(0==e.hasClass("animating")){if(e.addClass("animating"),t(".awselect.animate").length>0){s(t(".awselect").not(e));var a=600}else var a=100;var i=e.attr("data-immersive");(t(window).width()<mobile_width||"true"==i)&&(n(e),a+=200),setTimeout(function(){var a=e.find(".back_face");a.show();var n=e.find("> .bg");n.css({height:e.outerHeight()+a.outerHeight()}),a.css({"margin-top":t(e).outerHeight()}),(t(window).width()<mobile_width||"true"===i)&&e.css({top:parseInt(e.css("top"))-a.height()}),e.addClass("placeholder_animate"),setTimeout(function(){l(e),setTimeout(function(){200==a.outerHeight()&&a.addClass("overflow")},200),e.addClass("placeholder_animate2"),e.addClass("animate"),e.addClass("animate2"),e.removeClass("animating")},100)},a)}}function n(e){t(".awselect_bg").remove(),t("body, html").addClass("immersive_awselect"),t("body").prepend('<div class = "awselect_bg"></div>'),setTimeout(function(){t(".awselect_bg").addClass("animate")},100);var a=e.outerWidth(),n=e.outerHeight(),s=e.offset().left,o=e.offset().top-t(window).scrollTop();e.attr("data-o-width",a),e.attr("data-o-left",s),e.attr("data-o-top",o),e.addClass("transition_paused").css({width:a,"z-index":"9999"}),setTimeout(function(){t('<div class = "awselect_placebo" style="position:relative; width:'+a+"px; height:"+n+'px; float:left;ß"></div>').insertAfter(e),e.css({position:"fixed",top:o,left:s}),e.removeClass("transition_paused"),setTimeout(function(){t(window).width()<mobile_width?e.css("width",t(window).outerWidth()-40):e.css("width",t(window).outerWidth()/2),e.css({top:t(window).outerHeight()/2+e.outerHeight()/2,left:"50%",transform:"translateX(-50%) translateY(-50%)"}),setTimeout(function(){i(e)},100)},100)},50)}function s(e){if(null==e)var a=t(".awselect");else var a=e;t(a).each(function(){var e=t(this);e.hasClass("animate")&&(setTimeout(function(){},300),e.removeClass("animate2"),e.find(".back_face").hide(),e.find(".back_face").removeClass("overflow"),e.removeClass("animate"),l(e),e.children(".bg").css({height:0}),e.removeClass("placeholder_animate2"),setTimeout(function(){o(e),e.removeClass("placeholder_animate")},100))})}function o(e){e.siblings(".awselect_placebo").length>0&&setTimeout(function(){var a=e.attr("data-o-width"),i=e.attr("data-o-left"),n=e.attr("data-o-top");e.css({width:a,left:i+"px",transform:"translateX(0) translateY(0)",top:n+"px"}),t(".awselect_bg").removeClass("animate"),setTimeout(function(){t(".awselect_placebo").remove(),t("body, html").removeClass("immersive_awselect"),setTimeout(function(){t(".awselect_bg").removeClass("animate").remove()},200),e.attr("style","")},300)},100)}function l(t){var e=t.find(".front_face .content").attr("data-inactive-color"),a=t.find(".front_face .content").css("color");t.find(".front_face .content").attr("data-inactive-color",a),t.find(".front_face .content").css("color",e),t.find(".front_face .icon svg").css("fill",e)}function c(a){var i=t(a).val(),n=e(t(a)),o=t(a).children('option[value="'+i+'"]').eq(0),l=t(a).attr("data-callback");t(n).find(".current_value").remove(),t(n).find(".front_face .content").prepend('<span class = "current_value">'+o.text()+"</span>"),t(n).addClass("hasValue"),"undefined"!=typeof l&&l!==!1&&window[l](o.val()),setTimeout(function(){s()},100)}function r(t){return'<svg style="fill:'+t+'" version="1.1" id="Chevron_thin_down" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 20 20" enable-background="new 0 0 20 20" xml:space="preserve"><path d="M17.418,6.109c0.272-0.268,0.709-0.268,0.979,0c0.27,0.268,0.271,0.701,0,0.969l-7.908,7.83c-0.27,0.268-0.707,0.268-0.979,0l-7.908-7.83c-0.27-0.268-0.27-0.701,0-0.969c0.271-0.268,0.709-0.268,0.979,0L10,13.25L17.418,6.109z"/></svg>'}t(document).mouseup(function(e){var a=t(".awselect");a.is(e.target)||0!==a.has(e.target).length||s()}),t.fn.awselect=function(n){var o=t(this),l=t.extend({},t.fn.awselect.defaults,n);return o.each(function(){awselect_count+=1,a(t(this),l)}),this.on("aw:animate",function(){i(e(t(this)))}),this.on("change",function(){c(this)}),this.on("aw:deanimate",function(){s(e(t(this)))}),console.log(o.attr("id")),{blue:function(){o.css("color","blue")}}},t.fn.awselect.defaults={background:"#e5e5e5",active_background:"#fff",placeholder_color:"#000",placeholder_active_color:"#000",option_color:"#000",vertical_padding:"15px",horizontal_padding:"40px",immersive:!1}}(jQuery),$(document).ready(function(){$("body").on("click",".awselect .front_face",function(){var t=$(this).parent(".awselect");0==t.hasClass("animate")?$("select#"+t.attr("id").replace("awselect_","")).trigger("aw:animate"):$("select#"+t.attr("id").replace("awselect_","")).trigger("aw:deanimate")}),$("body").on("click",".awselect ul li a",function(){var t=$(this).parents(".awselect"),e=$(this).parent("li").index(),a=t.attr("data-select"),i=$("select#"+a),n=$(i).children("option").eq(e);$(i).attr("data-callback");$(i).val(n.val()),$(i).trigger("change")})});
/*****/

/** jQuery Mask ***/
var $jscomp=$jscomp||{};$jscomp.scope={},$jscomp.findInternal=function(t,a,e){t instanceof String&&(t=String(t));for(var n=t.length,s=0;s<n;s++){var r=t[s];if(a.call(e,r,s,t))return{i:s,v:r}}return{i:-1,v:void 0}},$jscomp.ASSUME_ES5=!1,$jscomp.ASSUME_NO_NATIVE_MAP=!1,$jscomp.ASSUME_NO_NATIVE_SET=!1,$jscomp.SIMPLE_FROUND_POLYFILL=!1,$jscomp.defineProperty=$jscomp.ASSUME_ES5||"function"==typeof Object.defineProperties?Object.defineProperty:function(t,a,e){t!=Array.prototype&&t!=Object.prototype&&(t[a]=e.value)},$jscomp.getGlobal=function(t){return"undefined"!=typeof window&&window===t?t:"undefined"!=typeof global&&null!=global?global:t},$jscomp.global=$jscomp.getGlobal(this),$jscomp.polyfill=function(t,a,e,n){if(a){for(e=$jscomp.global,t=t.split("."),n=0;n<t.length-1;n++){var s=t[n];s in e||(e[s]={}),e=e[s]}(a=a(n=e[t=t[t.length-1]]))!=n&&null!=a&&$jscomp.defineProperty(e,t,{configurable:!0,writable:!0,value:a})}},$jscomp.polyfill("Array.prototype.find",function(t){return t||function(t,a){return $jscomp.findInternal(this,t,a).v}},"es6","es3"),function(t,a,e){"function"==typeof define&&define.amd?define(["jquery"],t):"object"==typeof exports&&"undefined"==typeof Meteor?module.exports=t(require("jquery")):t(a||e)}(function(t){var a=function(a,e,n){var s={invalid:[],getCaret:function(){try{var t=0,e=a.get(0),n=document.selection,r=e.selectionStart;if(n&&-1===navigator.appVersion.indexOf("MSIE 10")){var o=n.createRange();o.moveStart("character",-s.val().length),t=o.text.length}else(r||"0"===r)&&(t=r);return t}catch(t){}},setCaret:function(t){try{if(a.is(":focus")){var e=a.get(0);if(e.setSelectionRange)e.setSelectionRange(t,t);else{var n=e.createTextRange();n.collapse(!0),n.moveEnd("character",t),n.moveStart("character",t),n.select()}}}catch(t){}},events:function(){a.on("keydown.mask",function(t){a.data("mask-keycode",t.keyCode||t.which),a.data("mask-previus-value",a.val()),a.data("mask-previus-caret-pos",s.getCaret()),s.maskDigitPosMapOld=s.maskDigitPosMap}).on(t.jMaskGlobals.useInput?"input.mask":"keyup.mask",s.behaviour).on("paste.mask drop.mask",function(){setTimeout(function(){a.keydown().keyup()},100)}).on("change.mask",function(){a.data("changed",!0)}).on("blur.mask",function(){i===s.val()||a.data("changed")||a.trigger("change"),a.data("changed",!1)}).on("blur.mask",function(){i=s.val()}).on("focus.mask",function(a){!0===n.selectOnFocus&&t(a.target).select()}).on("focusout.mask",function(){n.clearIfNotMatch&&!r.test(s.val())&&s.val("")})},getRegexMask:function(){for(var t,a,n,s,r=[],i=0;i<e.length;i++)(t=o.translation[e.charAt(i)])?(a=t.pattern.toString().replace(/.{1}$|^.{1}/g,""),n=t.optional,(t=t.recursive)?(r.push(e.charAt(i)),s={digit:e.charAt(i),pattern:a}):r.push(n||t?a+"?":a)):r.push(e.charAt(i).replace(/[-\/\\^$*+?.()|[\]{}]/g,"\\$&"));return r=r.join(""),s&&(r=r.replace(new RegExp("("+s.digit+"(.*"+s.digit+")?)"),"($1)?").replace(new RegExp(s.digit,"g"),s.pattern)),new RegExp(r)},destroyEvents:function(){a.off("input keydown keyup paste drop blur focusout ".split(" ").join(".mask "))},val:function(t){var e=a.is("input")?"val":"text";return 0<arguments.length?(a[e]()!==t&&a[e](t),e=a):e=a[e](),e},calculateCaretPosition:function(t){var e=s.getMasked(),n=s.getCaret();if(t!==e){var r=a.data("mask-previus-caret-pos")||0;e=e.length;var o,i=t.length,l=t=0,c=0,u=0;for(o=n;o<e&&s.maskDigitPosMap[o];o++)l++;for(o=n-1;0<=o&&s.maskDigitPosMap[o];o--)t++;for(o=n-1;0<=o;o--)s.maskDigitPosMap[o]&&c++;for(o=r-1;0<=o;o--)s.maskDigitPosMapOld[o]&&u++;n>i?n=10*e:r>=n&&r!==i?s.maskDigitPosMapOld[n]||(r=n,n=n-(u-c)-t,s.maskDigitPosMap[n]&&(n=r)):n>r&&(n=n+(c-u)+l)}return n},behaviour:function(e){e=e||window.event,s.invalid=[];var n=a.data("mask-keycode");if(-1===t.inArray(n,o.byPassKeys)){n=s.getMasked();var r=s.getCaret(),i=a.data("mask-previus-value")||"";return setTimeout(function(){s.setCaret(s.calculateCaretPosition(i))},t.jMaskGlobals.keyStrokeCompensation),s.val(n),s.setCaret(r),s.callbacks(e)}},getMasked:function(t,a){var r,i=[],l=void 0===a?s.val():a+"",c=0,u=e.length,p=0,f=l.length,d=1,v="push",k=-1,m=0;if(a=[],n.reverse){v="unshift",d=-1;var h=0;c=u-1,p=f-1;var g=function(){return-1<c&&-1<p}}else h=u-1,g=function(){return c<u&&p<f};for(;g();){var M=e.charAt(c),y=l.charAt(p),b=o.translation[M];b?(y.match(b.pattern)?(i[v](y),b.recursive&&(-1===k?k=c:c===h&&c!==k&&(c=k-d),h===k&&(c-=d)),c+=d):y===r?(m--,r=void 0):b.optional?(c+=d,p-=d):b.fallback?(i[v](b.fallback),c+=d,p-=d):s.invalid.push({p:p,v:y,e:b.pattern}),p+=d):(t||i[v](M),y===M?(a.push(p),p+=d):(r=M,a.push(p+m),m++),c+=d)}return t=e.charAt(h),u!==f+1||o.translation[t]||i.push(t),i=i.join(""),s.mapMaskdigitPositions(i,a,f),i},mapMaskdigitPositions:function(t,a,e){for(t=n.reverse?t.length-e:0,s.maskDigitPosMap={},e=0;e<a.length;e++)s.maskDigitPosMap[a[e]+t]=1},callbacks:function(t){var r=s.val(),o=r!==i,l=[r,t,a,n],c=function(t,a,e){"function"==typeof n[t]&&a&&n[t].apply(this,e)};c("onChange",!0===o,l),c("onKeyPress",!0===o,l),c("onComplete",r.length===e.length,l),c("onInvalid",0<s.invalid.length,[r,t,a,s.invalid,n])}};a=t(a);var r,o=this,i=s.val();e="function"==typeof e?e(s.val(),void 0,a,n):e,o.mask=e,o.options=n,o.remove=function(){var t=s.getCaret();return o.options.placeholder&&a.removeAttr("placeholder"),a.data("mask-maxlength")&&a.removeAttr("maxlength"),s.destroyEvents(),s.val(o.getCleanVal()),s.setCaret(t),a},o.getCleanVal=function(){return s.getMasked(!0)},o.getMaskedVal=function(t){return s.getMasked(!1,t)},o.init=function(i){if(i=i||!1,n=n||{},o.clearIfNotMatch=t.jMaskGlobals.clearIfNotMatch,o.byPassKeys=t.jMaskGlobals.byPassKeys,o.translation=t.extend({},t.jMaskGlobals.translation,n.translation),o=t.extend(!0,{},o,n),r=s.getRegexMask(),i)s.events(),s.val(s.getMasked());else{n.placeholder&&a.attr("placeholder",n.placeholder),a.data("mask")&&a.attr("autocomplete","off"),i=0;for(var l=!0;i<e.length;i++){var c=o.translation[e.charAt(i)];if(c&&c.recursive){l=!1;break}}l&&a.attr("maxlength",e.length).data("mask-maxlength",!0),s.destroyEvents(),s.events(),i=s.getCaret(),s.val(s.getMasked()),s.setCaret(i)}},o.init(!a.is("input"))};t.maskWatchers={};var e=function(){var e=t(this),s={},r=e.attr("data-mask");if(e.attr("data-mask-reverse")&&(s.reverse=!0),e.attr("data-mask-clearifnotmatch")&&(s.clearIfNotMatch=!0),"true"===e.attr("data-mask-selectonfocus")&&(s.selectOnFocus=!0),n(e,r,s))return e.data("mask",new a(this,r,s))},n=function(a,e,n){n=n||{};var s=t(a).data("mask"),r=JSON.stringify;a=t(a).val()||t(a).text();try{return"function"==typeof e&&(e=e(a)),"object"!=typeof s||r(s.options)!==r(n)||s.mask!==e}catch(t){}},s=function(t){var a=document.createElement("div"),e=(t="on"+t)in a;return e||(a.setAttribute(t,"return;"),e="function"==typeof a[t]),e};t.fn.mask=function(e,s){s=s||{};var r=this.selector,o=t.jMaskGlobals,i=o.watchInterval;o=s.watchInputs||o.watchInputs;var l=function(){if(n(this,e,s))return t(this).data("mask",new a(this,e,s))};return t(this).each(l),r&&""!==r&&o&&(clearInterval(t.maskWatchers[r]),t.maskWatchers[r]=setInterval(function(){t(document).find(r).each(l)},i)),this},t.fn.masked=function(t){return this.data("mask").getMaskedVal(t)},t.fn.unmask=function(){return clearInterval(t.maskWatchers[this.selector]),delete t.maskWatchers[this.selector],this.each(function(){var a=t(this).data("mask");a&&a.remove().removeData("mask")})},t.fn.cleanVal=function(){return this.data("mask").getCleanVal()},t.applyDataMask=function(a){((a=a||t.jMaskGlobals.maskElements)instanceof t?a:t(a)).filter(t.jMaskGlobals.dataMaskAttr).each(e)},s={maskElements:"input,td,span,div",dataMaskAttr:"*[data-mask]",dataMask:!0,watchInterval:300,watchInputs:!0,keyStrokeCompensation:10,useInput:!/Chrome\/[2-4][0-9]|SamsungBrowser/.test(window.navigator.userAgent)&&s("input"),watchDataMask:!1,byPassKeys:[9,16,17,18,36,37,38,39,40,91],translation:{0:{pattern:/\d/},9:{pattern:/\d/,optional:!0},"#":{pattern:/\d/,recursive:!0},A:{pattern:/[a-zA-Z0-9]/},S:{pattern:/[a-zA-Z]/}}},t.jMaskGlobals=t.jMaskGlobals||{},(s=t.jMaskGlobals=t.extend(!0,{},s,t.jMaskGlobals)).dataMask&&t.applyDataMask(),setInterval(function(){t.jMaskGlobals.watchDataMask&&t.applyDataMask()},s.watchInterval)},window.jQuery,window.Zepto);
/**************/

var twitter = null;

function get_citys(id, target) {
    $.post(baseURL + 'cart/get_citys', {'country_id':id}, function(data) {
        $(target).html(data);
        cargoCompanyInit();
    })
}

function changeaddress(address_id) {
    $.post(baseURL + 'cart/cargo_company/',{'address_id':address_id},function(data) {
        $('#cargo_company_list').html(data);
        cargoCompanyInit();
    });
}

function changecity(city_id) {
    $.post(baseURL + 'cart/cargo_company/',{'city_id':city_id},function(data) {
        $('#cargo_company_list').html(data);
        cargoCompanyInit();
    });
}

function startLoader(){
    $('body').append('<div class="loader"></div>');

    $.fakeLoader({
        targetClass : 'loader',
        timeToHide:1200000,
        zIndex:"9999",
        bgColor:"rgba(0, 82, 148, 0.2)",
        spinner:"spinner7"
    });
}

function hideLoader(){
    $('.loader').hide('slow').remove();
}

function quickcart(){
    $.get(baseURL + 'cart/quickcart',function(data){
        $('#quickcartview').html(data);
        removecartInit();
    });
}

function removecartInit(){
    $('[data-quickcart-remove]').on('click',function(){
        var sku = $(this).data('sku');
        var reload = $(this).data('reload');
        if(sku) {
            $.post(baseURL + 'cart/removecart',{'id':sku,'reload':reload},function(json) {
                console.log(reload);
                if(reload) {
                    window.location.reload()
                } else {
                    if(json.status == 'success') {
                        $.toast({
                            heading: json.head,
                            text: json.message,
                            hideAfter : 5000,
                            icon: 'success',
                            position : 'mid-center',
                            afterShown : function() {
                                quickcart();
                                $('#cart-count').html(json.count);
                            }
                        });
                    } else {
                        $.toast({
                            heading: json.head,
                            text: json.message,
                            hideAfter : 5000,
                            icon: 'error',
                            position : 'mid-center'
                        });
                    }
                }
            });
        }
    });
}

function AjaxModalInit(){
    $('[data-toggle="ajaxModal"]').unbind('click');
    $('[data-toggle="ajaxModal"]').on('click',
        function(e) {

            $('#ajaxModal').remove();
            $('.modal-backdrop').remove();
            e.preventDefault();
            var $this = $(this)
            , $remote = $this.data('remote') || $this.attr('href')
            , $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"></div></div>');
            $('body').append($modal);
            $modal.modal({backdrop: 'static', keyboard: false});
            $modal.load($remote);
            setTimeout(function() {
                diffFaturaFormInit();
                },1000);
        }
    );
}

function cargoCompanyInit() {
    if($('#cart_summary')[0]) $.get(baseURL + 'cart/cart_summary',function(data) {$('#cart_summary').html(data);});
    $('._cargo-list input[type="radio"]').unbind('change');
    $('._cargo-list input[type="radio"]').on('change', function() {

        $.post(baseURL + 'cart/cart_summary', {'cargo_company_id':$(this).val(), 'address_id': $('#ship_id').val(), 'city_id': $('#city_id').val()}, function(data) {
            $('#cart_summary').html(data);
        })
    });
}

function diffFaturaFormInit() {
    $('#checkout-diff-address').on('change', function() {
        var checked = $(this).prop('checked');
        if(checked)  {
            $('#billingWrapper').slideDown().find('input, select, textarea').removeAttr('disabled');
            $('.kurumsal').slideUp().find('input, select, textarea').attr('disabled','disabled');
            $('.bireysel').slideDown().find('input, select, textarea').removeAttr('disabled');
        } else {
            $('#billingWrapper').slideUp().find('input, select, textarea').attr('disabled','disabled');
            $('#billingWrapper .bireysel, #billingWrapper .kurumsal').slideUp()
        }
    });

    $('.faturaType').on('change',function() {
        var val = $(this).val();
        $('.bireysel, .kurumsal').slideUp().find('input, select, textarea').attr('disabled','disabled');
        if(val == 1) {
            $('.kurumsal').slideDown().find('input, select, textarea').removeAttr('disabled');
        } else $('.bireysel').slideDown().find('input, select, textarea').removeAttr('disabled');
    })
}

function twitterLogin(){
    var url = 'https://www.organizma.web.tr/app/twitter/index.php?url='+ baseURL;
    twitter =  window.open(url, "twitter", "width=600,height=400");
    twitter.onclose = function () {
        console.log('kapandi');
        top.location.reload();
    }
}

function facebookLogin(){
    var url = 'http://organizma.web.tr/app/facebook/index.php?url='+ baseURL;
    window.open(url, "", "width=600,height=400");
}

$(document).ready(function() {
    $('.aselect').awselect({
        immersive: true
    });

    InitPey();



    $('.credit').mask("9999 9999 9999 9999", {placeholder : "0000 0000 0000 0000"});

    /*
    $('#ajax_form').on('submit',function() {
        startLoader();
        var form = $(this);
        $.post(baseURL +'auctions/ajax',form.serialize(),function(data){
            $('#result_cat').html(data);
            InitPey();
            owlCarousels();
            $('.Filter-close').click();
            $('html, body').animate({ scrollTop:0 }, 'slow',function(){
                hideLoader();
            });
            $('.lazy').each(function(){
                if( $(this).offset().top < ($(window).scrollTop() + $(window).height() + 100) )
                {
                    $(this).attr('src', $(this).attr('data-src'));
                }
            });
        })

        return false;
    });
    */

    $('#ajax_form .checkbox').on('change',function() {
        $('#ajax_form').submit();
    });

    quickcart();
    AjaxModalInit();
    diffFaturaFormInit();
    cargoCompanyInit();

    $('.paymenttab').find('input, select, textarea').attr('disabled','disabled');
    $('.paymenttab .active').find('input, select, textarea').removeAttr('disabled');

    $('.pay-tabs a').on('shown.bs.tab', function(event){
        $('.paymenttab').find('input, select, textarea').attr('disabled','disabled');
        $('.paymenttab .active').find('input, select, textarea').removeAttr('disabled');
        var form = $(event.currentTarget).parents('form');
        if(form) {
            $.post(baseURL + 'cart/cart_summary', {'payment_type': $('[name="payment[payment_type]"]',$('.paymenttab .active')).val()}, function(data) {
                $('#cart_summary').html(data);
            })
        }
    });


    /*
    $('[data-addcart-form]').on('click',function() {
        var ProductForm = $(this).parents('form');
        if(ProductForm[0].reportValidity()) {
            $.post(baseURL + 'cart/addcart/',$(ProductForm).serialize(),function(json) {
                if(json.status == 'success') {
                    $.toast({
                        heading: json.head,
                        text: json.message,
                        hideAfter : 5000,
                        icon: json.cls,
                        position : 'mid-center',
                        afterShown : function() {
                            quickcart();
                            $('#cart-count').html(json.count);

                            if(fbq) {
                                fbq('track', 'AddToCart', {
                                    content_ids : [json.sku],
                                    content_name : json.name,
                                    content_type : 'product',
                                    contents : [{'id': json.sku, 'quantity': json.quantity}]
                                });
                            }
                        }
                    });
                } else {
                    $.toast({
                        heading: json.head,
                        text: json.message,
                        hideAfter : 5000,
                        icon: 'error',
                        position : 'mid-center'
                    });
                }
            })
        }
    });
*/
    $('.qinput').on('change',function() {
        $('.updatecart').show();
        $('#sitemsg').show();
    })
    $('.updatecart').on('click', function() {
        var form = $(this).data('target');
        if(form) {
            $.post(baseURL + 'cart/updatecart', $(form).serialize() , function() {
                top.location.reload();
            })
        }
    });

})