var $=jQuery.noConflict();
(function($) {
	$(function() {
		$('#carousel-0').jcarousel({
			wrap: 'circular'
		});

		$('#carousel-0').jcarouselAutoscroll({
			autostart: true
		});

		$('.jcarousel-pagination')
			.on('jcarouselpagination:active', 'a', function() {
				$(this).addClass('active');
			})
			.on('jcarouselpagination:inactive', 'a', function() {
				$(this).removeClass('active');
			})
			.jcarouselPagination();
		});
})(jQuery);


(function($) {
	$(function() {
		/*
		Carousel initialization
		*/
		$('.jcarousel-carousel')
			.on('jcarousel:create jcarousel:reload', function() {
				var element = $(this),
					width = element.innerWidth();

				if (width >= 1280) {
					width = width / 6 - 5;
				} else if (width >= 1200) {
					width = width / 5;
				} else if (width >= 900) {
					width = width / 4;
				} else if (width >= 600) {
					width = width / 3;
				} else if (width >= 320) {
					width = width / 2;
				}

				width = width - 20;

				element.jcarousel('items').css('width', width + 'px');
			})
			.jcarousel({
				wrap: 'circular'
			});

		/*
		 Prev control initialization
		 */
		$('.jcarousel-control-prev')
			.on('jcarouselcontrol:active', function() {
				$(this).removeClass('inactive');
			})
			.on('jcarouselcontrol:inactive', function() {
				$(this).addClass('inactive');
			})
			.jcarouselControl({
				// Options go here
				target: '-=1'
			});

		/*
		 Next control initialization
		 */
		$('.jcarousel-control-next')
			.on('jcarouselcontrol:active', function() {
				$(this).removeClass('inactive');
			})
			.on('jcarouselcontrol:inactive', function() {
				$(this).addClass('inactive');
			})
			.jcarouselControl({
				// Options go here
				target: '+=1'
			});
	});
})(jQuery);


$(document).ready(function(){
	$("[data-toggle=tooltip]").tooltip({ placement: 'bottom'});

$('.nav-tabs > li > a').hover( function(){
	  $(this).tab('show');
   });

$("a[href='#top']").click(function() {
  $("html, body").animate({ scrollTop: 0 }, "slow");
  return false;
});


/*----------------------------------------------------*/
/*  Tabs
/*----------------------------------------------------*/

	var $tabsNav    = $('.tabs-nav'),
		$tabsNavLis = $tabsNav.children('li'),
		$tabContent = $('.tab-content');

	$tabsNav.each(function() {
		var $this = $(this);

		$this.next().children('.tab-content').stop(true,true).hide()
											 .first().show();

		$this.children('li').first().addClass('active').stop(true,true).show();
	});

	$tabsNavLis.on('click', function(e) {
		var $this = $(this);

		$this.siblings().removeClass('active').end()
			 .addClass('active');

		$this.parent().next().children('.tab-content').stop(true,true).hide()
													  .siblings( $this.find('a').attr('href') ).fadeIn();

		e.preventDefault();
	});


/*----------------------------------------------------*/
/*  Accordion
/*----------------------------------------------------*/

	var $accor = $('.accordion');

	$accor.each(function() {
		$(this).addClass('ui-accordion ui-widget ui-helper-reset');
		$(this).find('h3').addClass('ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all');
		$(this).find('div').addClass('ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom');
		$(this).find("div").hide().first().show();
		$(this).find("h3").first().removeClass('ui-accordion-header-active ui-state-active ui-corner-top').addClass('ui-accordion-header-active ui-state-active ui-corner-top');
		$(this).find("span").first().addClass('ui-accordion-icon-active');
	});

	$trigger = $accor.find('h3');

	$trigger.on('click', function(e) {
		var location = $(this).parent();

	   if( $(this).next().is(':hidden') ) {
			$triggerloc = $('h3',location);
			$triggerloc.removeClass('ui-accordion-header-active ui-state-active ui-corner-top').next().slideUp(300);
			$triggerloc.find('span').removeClass('ui-accordion-icon-active');
			$(this).find('span').addClass('ui-accordion-icon-active');
			$(this).addClass('ui-accordion-header-active ui-state-active ui-corner-top').next().slideDown(300);
		}
		e.preventDefault();
	});


/*----------------------------------------------------*/
/*  Toggle
/*----------------------------------------------------*/

	$(".toggle-container").hide();
	$(".trigger").toggle(function(){
		$(this).addClass("active");
		}, function () {
		$(this).removeClass("active");
	});
	$(".trigger").click(function(){
		$(this).next(".toggle-container").slideToggle();
	});

	$(".trigger.opened").toggle(function(){
		$(this).removeClass("active");
		}, function () {
		$(this).addClass("active");
	});

	$(".trigger.opened").addClass("active").next(".toggle-container").show();



});

/*----------------------------------------------------*/
/*  Skill Bar Animation
/*----------------------------------------------------*/

		setTimeout(function(){

		$('.skill-bar .skill-bar-content').each(function() {
			var me = $(this);
			var perc = me.attr("data-percentage");

			var current_perc = 0;

			var progress = setInterval(function() {
				if (current_perc>=perc) {
					clearInterval(progress);
				} else {
					current_perc +=1;
					me.css('width', (current_perc)+'%');
				}

				me.text((current_perc)+'%');

			}, 10);

		});

	},10);

/*----------------------------------------------------*/
/*  Share button
/*----------------------------------------------------*/
$('#twitter').sharrre({
	share: {
		twitter: true
	},
	enableHover: false,
	click: function(api, options){
		api.simulateClick();
		api.openPopup('twitter');
	}
});

$('#facebook').sharrre({
	share: {
		facebook: true
	},
	enableHover: false,
	click: function(api, options){
		api.simulateClick();
		api.openPopup('facebook');
	}
});

$('#google-plus').sharrre({
	share: {
		googlePlus: true
	},
	enableHover: false,
	click: function(api, options){
		api.simulateClick();
		api.openPopup('googlePlus');
	}
});

$('#linkedin').sharrre({
	share: {
		linkedin: true
	},
	enableHover: false,
	click: function(api, options){
		api.simulateClick();
		api.openPopup('linkedin');
	}
});

$(document).ready(function(){

	/**
	 * Turn navigation into mobile navigation
	 */
	$("#secondary-menu").tinyNav({
		active: 'current-menu-item'
	});

	/**
	 * Mobile Menu
	 */
	$('#primary-nav').clone().attr('id', 'primary-navigation').insertBefore('#primary-nav');
	$('#primary-nav ul').removeClass('sf-menu');
	$('#primary-nav').mmenu();

	$('ul.sf-menu').superfish({
		delay:       100,                            // one second delay on mouseout
		speed:       'fast',                          // faster animation speed
		autoArrows:  false                            // disable generation of arrow mark-up       
	});

	// Responsive video
	$(".entry-content").fitVids();

});

$(window).load(function() {

});