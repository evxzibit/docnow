"use strict";
/*--------------------------------------------------------------------------
		Google Analytics change UA-XXXXX-X to be your site's ID.
--------------------------------------------------------------------------*/
(function (b, o, i, l, e, r) {
	b.GoogleAnalyticsObject = l;
	b[l] || (b[l] =
			function () {
				(b[l].q = b[l].q || []).push(arguments)
			});
	b[l].l = +new Date;
	e = o.createElement(i);
	r = o.getElementsByTagName(i)[0];
	e.src = '//www.google-analytics.com/analytics.js';
	r.parentNode.insertBefore(e, r)
}(window, document, 'script', 'ga'));
ga('create', 'UA-XXXXX-X', 'auto');
ga('send', 'pageview');
/*--------------------------------------
			CUSTOM FUNCTION WRITE HERE
--------------------------------------*/
$(document).ready(function (e) {
	/*--------------------------------------
			COMMING SOON COUNTER
	--------------------------------------*/
	$('#comming-countdown').countdown({
		date: '10/5/2017 13:41:59',
		offset: -100,
		day: 'Day',
		days: 'Days'
	},function () {
		alert('Done!');
	});
	/*--------------------------------------
			DOCTOR'S GALLERY
	--------------------------------------*/
	$("#tg-photosgallery").owlCarousel({
		autoPlay: true,
		items: 3,
		itemsDesktop: [1199, 3],
		itemsDesktopSmall: [991, 2],
		itemsTabletSmall: [568, 1],
		slideSpeed: 300,
		paginationSpeed: 400,
		pagination: false,
		navigation: true,
		navigationText: [
			"<i class='tg-prev fa fa-angle-left'></i>",
			"<i class='tg-next fa fa-angle-right'></i>"
		]
	});
	/*--------------------------------------
			PRETTY PHOTO GALLERY
	--------------------------------------*/
	$("a[data-rel]").each(function () {
		$(this).attr("rel", $(this).data("rel"));
	});
	$("a[data-rel^='prettyPhoto']").prettyPhoto({
		animation_speed: 'normal',
		theme: 'dark_square',
		slideshow: 3000,
		autoplay_slideshow: false,
		social_tools: false
	});
	/* -------------------------------------
			THEME ACCORDION
	 -------------------------------------- */
	/*$('#accordion .tg-panel-heading').on('click',function () {
		$('.tg-panel-heading').removeClass('.active');
		$(this).parents('.tg-panel-heading').addClass('.active');
		$('.panel').removeClass('.active');
		$(this).parent().addClass('.active');
	});*/
//	/* -------------------------------------
//			THEME ACCORDION
//	 -------------------------------------- */
//	$('#accordion .tg-panel-heading').on('click',function () {
//		$('.tg-panel-heading').removeClass('.active');
//		$(this).parents('.tg-panel-heading').addClass('.active');
//		$('.panel').removeClass('.active');
//		$(this).parent().addClass('.active');
//	});
	/*--------------------------------------
			NICE SCROLL
	--------------------------------------*/
	 $("#tg-reviewscrol").niceScroll({
		cursorcolor:"#7dbb00",
		background: "#ddd",
		autohidemode: false,
		cursorborder: false
	 });
	/*--------------------------------------
			NICE SCROLL
	--------------------------------------*/
	 $("#tg-photoscroll").niceScroll({
		cursorwidth: "4px",
		cursorcolor:"#7dbb00",
		background: "#ddd",
		autohidemode: false,
		cursorborder: false
	 });
	/*--------------------------------------
			GRAPH
	--------------------------------------*/
	
	/*--------------------------------------
			THEME ACCORDION
	--------------------------------------*/
	$('#accordion .tg-panel-heading').on('click',function () {
		$('.tg-panel-heading').removeClass('active');
		$(this).parents('.tg-panel-heading').addClass('active');
		$('.panel').removeClass('active');
		$(this).parent().addClass('active');
	});
});