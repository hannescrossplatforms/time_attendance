/* 
 * Copyright (c) 2007 Halmat Ferello (http://greydust.com/halmat)
 * Licensed under the MIT License:
 * http://www.opensource.org/licenses/mit-license.php
 * Build v2
 * October 2007
*/

$.fn.jSearch = function(options) {
	options = options || {};
	$.jSearchInit(this[0], options);
};
   
// jSearch initialization
$.jSearchInit = function(el, o) {
	
	var o = $.extend({
		form : el.id,
		input : 'search_q',
		width : null,
		submit : 'q_sub',
		div : 'jSearchResults',
		wrapperDivLeft : null,
		wrapperDivTop : null,
		wrapperDiv : 'jSearchWrapper',
		loadingDiv : 'jSearchLoading',
		loadingText : 'Searching...',
		errorDiv : 'jSearchError',
		errorText : 'Error. Please try again.',
		file : el.action,
		spinner : '/images/ajax-loader.gif'
	}, o); //p

	var f = {
		
		doSearch: function()
		{
			$.ajax({
				url: o.file,
				type: "POST",
				timeout: 5000,
				data: $form.serialize(),
				
				beforeSend: function() {
					$submit.attr('disabled', 'disabled');
					$("#nav").hide();
					$wrapper.after('<div id="'+o.loadingDiv+'" class="'+o.loadingDiv+'"><img src="images/ajax-loader.gif" />'+o.loadingText+'</div>');
				},
			
				complete: function() {
				},

				success: function(msg) {
					$div.html(msg)
					$("#nav").hide();
					$wrapper.slideDown('slow', function(){ f.reset(); });
					$("#jSearchLegend").show();
				},

				error: function(obj) {
					$submit.after('<span id="'+o.errorDiv+'">'+o.errorText+'</span>');
					$("#nav").show();
					$("#jSearchLegend").hide();
					$wrapper.hide();
					f.reset();
					obj.abort();
				}
			});
		}, //do

		reset: function()
		{
			$submit.removeAttr('disabled');
			$('#'+o.loadingDiv).remove();
		}, //reset
		
		findPos: function(obj) {
			var curleft = obj.offsetLeft || 0;
			var curtop = obj.offsetTop || 0;
			while (obj = obj.offsetParent) {
				curleft += obj.offsetLeft
				curtop += obj.offsetTop
			}
			return {x:curleft,y:curtop};
		} //findPos
		
	}; // f
	
	// init variables
	var $form = $('form#'+o.form);
	var $input = $('input#'+o.input);
	var inputPos = f.findPos($input.get(0));
	var $submit = $('input#'+o.submit);
	
	// get the width and location of the input field
	o.width = o.width || $input.width();
	o.wrapperDivLeft = o.wrapperDivLeft || inputPos.x;
	o.wrapperDivTop = o.wrapperDivTop || inputPos.y;
	
	// insert wrapper and results div after form
	//$("#sidebar").append('<div id="jSearchLegend"><img src="images/markers/blue_Marker_small.png"/> Accomodation<br><img src="images/markers/green_Marker_small.png"/> Restaurant / Cafe<br/><img src="images/markers/orange_Marker_small.png"/> Other<br/><img src="images/markers/grey_Marker_small.png"/> Offline</div>');
	$("#sidebar").append('<div id="' + o.wrapperDiv + '"><div id="' + o.div + '"></div></div>');
	
	// init divs variable
	var $wrapper = $('div#'+o.wrapperDiv);
	var $div = $('div#'+o.div);
	
	// reset form
	f.reset();

	// Hdie both the Legend and the Wrapper
	$("#jSearchLegend").hide();
	$wrapper.hide();
	
	// CSS styles
	$wrapper.css({
		position: "absolute",
		height: $('#map').height()-$("#jSearchLegend").height(),
		width: $('#page').width()-$("#map").width(),
		zIndex: 20000
	});
	
	// on submit do()
	$(el).submit(function(){
		f.doSearch();
		return false;
	});
	
	$(window).keypress(function(e){
		if (e.keyCode == 27 && $wrapper.is(':visible')) {
			e.preventDefault();
			$wrapper.hide();
		}
	});
	
}; // jSearchInit
