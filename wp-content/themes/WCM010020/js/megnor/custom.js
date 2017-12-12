/*! Customized Jquery from Mahesh Vaghani.  mahesh@templatemela.com  : www.templatemela.com
Authors & copyright (c) 2013: TemplateMela - Megnor Computer Private Limited. */
// Megnor Start
"use strict";
jQuery(document).ready(function() {	
	
	//=================== Jquery For Lightbox ========================//
	Shadowbox.init({
	overlayOpacity: 0.8
	}, setupDemos); 
	
	jQuery('br', '.liststyle_content').remove();
	jQuery('input[type="checkbox"]').tmMark();
	jQuery('input[type="radio"]').tmMark();
	jQuery('select.orderby').customSelect();
	jQuery("ul li:empty").remove();
	jQuery('br', '.brand_block').remove();
	jQuery('br', '.pricing-content-inner').remove();
	jQuery('br', '#vertical_tab .tabs').remove();
	jQuery('.site-main .primary-sidebar .widget ul').addClass('main-ul');
	jQuery('.tagcloud').addClass('main-ul');
	jQuery('.woocommerce ul.products .container-inner > p').contents().unwrap();
	jQuery('p').each(function() {
	var $this = jQuery(this);
	if($this.html().replace(/\s|&nbsp;/g, '').length == 0)
		$this.remove();
	});
	
	var $responsive_menu = jQuery.noConflict(); 
	$responsive_menu(".nav-button").click(function () {
		$responsive_menu(".nav-button, .primary-nav").toggleClass("open");
	});	
	// Add megamenu to wordpress simple menu
	function setmegamenu() {	
	var menu_effect;
		if (jQuery(window).width() > 767){
			menu_effect = 'hover';
				
		jQuery('.mega-menu .mega').dcMegaMenu({
			rowItems: 4,
			speed: 'fast',
			effect: 'slide',
			event: menu_effect,
			fullWidth: false,
			mbarIcon: true
		});	
		}
	}
	jQuery(window).resize(setmegamenu);
	jQuery(setmegamenu);
	
	function categorymenu() {	
	var menu_effect;
		if (jQuery(window).width() > 979){
			menu_effect = 'hover';
				
		jQuery('.sidebar-category .mega').dcMegaMenu({
			rowItems: 3,
			speed: 'fast',
			effect: 'slide',
			event: menu_effect,
			fullWidth: false,
			mbarIcon: true
		});	
		}
	}
	jQuery(window).resize(categorymenu);
	jQuery(categorymenu);
	// apends breadcrumbs in page title div	
	jQuery(".woocommerce-breadcrumb").appendTo(jQuery(".main_inner .page-title .page-title-inner"));
	jQuery(".gridlist-toggle").prependTo(jQuery("#primary #content"));
	
	if (jQuery(window).width() < 767){
		jQuery('.mega-menu .mega').attr('id', 'menu-menu');
	}else {
		jQuery('.mega-menu .mega > ul').addClass('mega');
		jQuery('.mega-menu .mega > ul').attr('id', 'menu-menu');
	}
	jQuery('.woocommerce-result-count').wrap(" <div class='category-toolbar'> </div>");
	jQuery('.woocommerce a.add_to_cart_button, .woocommerce a.product_type_variable, .woocommerce a.product_type_simple,.woocommerce a.product_type_external, .woocommerce a.product_type_grouped ').wrap(" <div class='addto-cart-hover'> </div>");
	
	jQuery(".woocommerce ul.products li .add_to_cart_button , .woocommerce ul.products li .product_type_grouped ,.woocommerce ul.products li .product_type_external, .woocommerce ul.products li .product_type_simple").wrap("<div class='block-hover'></div>");
	
	jQuery(".products .container-inner").find(".yith-wcwl-add-to-wishlist").each(function(i){
		jQuery(this).appendTo(jQuery(this).parent().find(".addto-cart-hover"));
	});
	
	jQuery(".products .container-inner").find(".yith-wcqv-button").each(function(i){
		jQuery(this).appendTo(jQuery(this).parent().parent().find(".addto-cart-hover"));
	});
	
	jQuery(".products .container-inner").find(".block-hover").each(function(i){
		jQuery(this).before(jQuery(this).parent().find(".yith-wcwl-add-to-wishlist"));
	});
	jQuery('.woocommerce-ordering').appendTo(".category-toolbar");
	jQuery('.gridlist-toggle').prependTo(".category-toolbar");
	
	
	jQuery(".accordion.style5 .single_accordion").each(function(i){
		jQuery(this).addClass("accord-" + (i + 1));
 	});
	//removes number tag +/-
	jQuery(".quantity.buttons_added").find("input.input-text").attr({type:"text"});	
	
	jQuery(".nav-menu:first > li").each(function (i) {
        jQuery(this).addClass("main-li");
    });	
	
	jQuery("#woo-small-products p img").each(function (i) {
        jQuery(this).wrap("<div class='image-block'> </div>");
    });
	
	jQuery(".sub-container .inner-image").each(function(i){
	jQuery(this).addClass("image-" + (i + 1));
	});

	
	jQuery(document).ready(function () {
	  jQuery('#horizontalTab').easyResponsiveTabs({
		type: 'default', //Types: default, vertical, accordion 
		width: 'auto', //auto or any width like 600px
		fit: true, // 100% fit in a container
		closed: 'accordion', // Start closed if in accordion view
		activate: function(event) { // Callback function if tab is switched
		  var $tab = jQuery(this);
		  var $info = jQuery('#tabInfo');
		  var $name = jQuery('span', $info);
	
		  $name.text($tab.text());
	
		  $info.show();
		}
	  });
	});	
	jQuery(document).ready(function () {
	  jQuery('#categorytab').easyResponsiveTabs({
		type: 'default', //Types: default, vertical, accordion 
		width: 'auto', //auto or any width like 600px
		fit: true, // 100% fit in a container
		closed: 'accordion', // Start closed if in accordion view
		activate: function(event) { // Callback function if tab is switched
		  var $tab = jQuery(this);
		  var $info = jQuery('#tabInfo');
		  var $name = jQuery('span', $info);
		  $name.text($tab.text());
		  $info.show();
		}
	  });
	});	
	
	
	
	jQuery("ul.products li").wrapInner("<div class='product-block'><div class='product-block-inner'></div></div>");
	jQuery('h1.widget-title').parent().addClass('toggled-off');

	/***********************  TOP Button ***********************/	
	(function ($) {	
		
		// To top button
		/* set variables locally for increased performance */
		var scroll_timer;
		var displayed = false;
		var $message = $('#to_top');
		var $window = $(window);
		var top = $(document.body).children(0).position().top;
	 
	 	$('#to_top').click(function (event) {
	 		event.preventDefault();
	 		$('html, body').animate({scrollTop : 0},'slow');
	 	});
	 	
		/* react to scroll event on window */
		$window.scroll(function () {
			window.clearTimeout(scroll_timer);
			scroll_timer = window.setTimeout(function () { // use a timer for performance
				if($window.scrollTop()<=top)
				{
					displayed = false;
					$message.fadeOut(500);
				}
				else if(displayed == false) // show if scrolling down
				{
					displayed = true;
					$message.stop(true, true).show().click(function () { $message.fadeOut(500); });
				}
			}, 100);
		});
   
	} )(jQuery);
		
	/* Toggle */
	(function(e) {
        e(".toogle_div a.tog").click(function(t) {
            var n = e(this).parent().find(".tab_content");
            e(this).parent().find(".tab_content").not(n).slideUp();
            if (e(this).hasClass("current")) {
                e(this).removeClass("current")
            } else {
                e(this).addClass("current")
            }
            n.stop(false, true).slideToggle().css({
                display: "block"
            });
            t.preventDefault()
        })
    })(jQuery);
	
	/*=== Accordion ===*/

		(function ($) { 
		 var allPanels = $('.accordion .tab_content').hide();
		  $('.accordion .tog').click(function() {
			allPanels.slideUp();
			$(this).parent().next().slideDown();
			return false;
		  });
		
			} )(jQuery);

	/**Toggle**/
		(function ($) {	
		$('.togg div.tog').click(function (i) { 
			var dropDown = $(this).parent().find('.tab_content');
			$(this).parent().find('.tab_content').not(dropDown).slideUp();	
			if ($(this).hasClass('current')) { 
				$(this).removeClass('current');
			} else { 
				$(this).addClass('current');				
			}
			dropDown.stop(false, true).slideToggle().css( { display : 'block' } );
			i.preventDefault();
		} );
	} )(jQuery);
	/* Accordion */
	
;	
(function ($) { 
    $('.accordion a.tog').click(function (j) { 
      var dropDown = $(this).parent().find('.tab_content');
          
      $(this).parent().parent().find('.tab_content').not(dropDown).slideUp();
      
      if ($(this).hasClass('current')) { 
        $(this).removeClass('current');
      } else { 
        $(this).parent().parent().find('.tog').removeClass('current');
        $(this).addClass('current');
        dropDown.stop(false, true).slideToggle().css( { display : 'block' } );
      }
      j.preventDefault();
    } );
  } )(jQuery);

	

(function ($) { 
    $('.accordion.style5 .accord-1 a.tog').addClass('current');
    $('.accordion.style5 .accord-1 a.tog').parent().find('.tab_content').stop(false, true).slideToggle().css( { display : 'block' } );
    $('.accordion.style5 .accord-1 a.tog').click(function (b) { 
           
      var dropDown = $(this).parent().find('.tab_content');
          
      $(this).parent().parent().find('.tab_content').not(dropDown).slideUp();
      
      if ($(this).hasClass('current')) { 
        $(this).removeClass('current');
		$('.accordion.style5 .accord-1 a.tog').removeClass('current');
      } else { 
	  	 $(this).parent().parent().find('.tog').removeClass('current');
        $(this).addClass('current');
        dropDown.stop(false, true).slideToggle().css( { display : 'block' } );
      }
     b.preventDefault();
	 
    } );
  } )(jQuery);




	/* Tabs */
	(function(e) {
        e(".tab ul.tabs li:first-child a").addClass("current");
        e(".tab .tab_groupcontent div.tabs_tab").hide();
        e(".tab .tab_groupcontent div.tabs_tab:first-child").css("display", "block");
        e(".tab ul.tabs li a").click(function(t) {
            var n = e(this).parent().parent().parent(),
                r = e(this).parent().index();
            n.find("ul.tabs").find("a").removeClass("current");
            e(this).addClass("current");
            n.find(".tab_groupcontent").find("div.tabs_tab").not("div.tabs_tab:eq(" + r + ")").slideUp();
            n.find(".tab_groupcontent").find("div.tabs_tab:eq(" + r + ")").slideDown();
            t.preventDefault()
        })
    })(jQuery);
	(function ($) { 
	  $(".animated").each(function () {
        $(this).one('inview', function (event, visible) {
            var $delay = "";
			var $this = $(this),
			    $animated = ($this.data("animated") !== undefined) ? $this.data("animated") : "slideUp";
				$delay = ($this.data("delay") !== undefined) ? $this.data("delay") : 300;
			if (visible === true) {
				setTimeout(function () {
				$this.addClass($animated);
						  $this.css('opacity', 1);
				}, $delay);
			} else {
			setTimeout(function () {
			$this.removeClass($animated);
					  $this.css('opacity', 0);
			}, $delay);
			}  
        });
    	});
	})(jQuery);

	
	/* Progress Bar */
	(function ($) { 
		$(".active_progresbar > span").each(function() {
			$(this)
				.data("origWidth", $(this).width())
				.width(0)
				.animate({
					width: $(this).data("origWidth")
				}, 1200);
		});
	})(jQuery);
	
	//================== Small Custom =====================================//
	jQuery("#commentform textarea").addClass("required");
	jQuery("#commentform").validate();
    jQuery("#shortcode_contactform").validate();
	
	/* Start OWL carousel*/
	var $owl_carousel=jQuery.noConflict();	
	
	jQuery(".portfolio-carousel").each(function () {  
		if( $owl_carousel(this).attr('id') ) {
			var portfolio_products = $owl_carousel(this).attr('id').replace('_portfolio_carousel','');	
			$owl_carousel('.portfolio-carousel').addClass('owl-carousel');
			$owl_carousel(".portfolio-carousel").owlCarousel({
				navigation : true,
				pagination : false,
				items : portfolio_products, //10 items above 1000px browser width
				itemsDesktop : [1199,3], //5 items between 1000px and 901px
				itemsDesktopSmall : [979,2], // betweem 900px and 601px
				itemsTablet: [768,2], //2 items between 600 and 0
				itemsMobile : [479,1] // itemsMobile disabled - inherit from itemsTablet option
			});
		}
	});
	
	jQuery(".latest-news-carousel").each(function () {  
		if( $owl_carousel(this).attr('id') ) {
			var latest_news_products = $owl_carousel(this).attr('id').replace('_latest_news_carousel','');
			$owl_carousel('.latest-news-carousel').addClass('owl-carousel');
			$owl_carousel(".latest-news-carousel").owlCarousel({
				navigation : true,
				pagination : false,
				items : latest_news_products, //10 items above 1000px browser width
				itemsDesktop : [1199,latest_news_products], //5 items between 1000px and 901px
				itemsDesktopSmall : [979,1], // betweem 900px and 601px
				itemsTablet: [768,1], //2 items between 600 and 0
				itemsMobile : [479,1] // itemsMobile disabled - inherit from itemsTablet option
			});
		}
	});
	
	jQuery(".blog-carousel").each(function () {  
		if( $owl_carousel(this).attr('id') ) {
			var blog_products = $owl_carousel(this).attr('id').replace('_blog_carousel','');
			$owl_carousel('.blog-carousel').addClass('owl-carousel');
			$owl_carousel(".blog-carousel").owlCarousel({
				navigation : true,
				pagination : false,
				items : blog_products, //10 items above 1000px browser width
				itemsDesktop : [1199,blog_products], //5 items between 1000px and 901px
				itemsDesktopSmall : [999,2], // betweem 900px and 601px
				itemsTablet: [768,2], //2 items between 600 and 0
				itemsMobile : [479,1] // itemsMobile disabled - inherit from itemsTablet option
			});
		}
	});
	
	jQuery(".brand-carousel").each(function () {  
		if( $owl_carousel(this).attr('id') ) {
			var brand_products = $owl_carousel(this).attr('id').replace('_brand_carousel','');	
			$owl_carousel('.brand-carousel').addClass('owl-carousel');
			$owl_carousel(".brand-carousel").owlCarousel({
				navigation : true,
				pagination : false,
				items : brand_products, //10 items above 1000px browser width
				itemsDesktop : [1199,brand_products], //5 items between 1000px and 901px
				itemsDesktopSmall : [979,3], // betweem 900px and 601px
				itemsTablet: [768,2], //2 items between 600 and 0
				itemsMobile : [479,1] // itemsMobile disabled - inherit from itemsTablet option
			});
		}
	});
	
	jQuery(".testimonial-carousel").each(function () {  
		if( $owl_carousel(this).attr('id') ) {
			var testimonial_items = $owl_carousel(this).attr('id').replace('_testimonial_carousel','');
			$owl_carousel('.testimonial-carousel').addClass('owl-carousel');
			$owl_carousel(".testimonial-carousel").owlCarousel({
				navigation : true,
				pagination : true,
				items : testimonial_items, //10 items above 1000px browser width
				itemsDesktop : [1199,testimonial_items], //5 items between 1000px and 901px
				itemsDesktopSmall : [999,1], // betweem 900px and 601px
				itemsTablet: [768,1], //2 items between 600 and 0
				itemsMobile : [479,1] // itemsMobile disabled - inherit from itemsTablet option
			});
		}
	});
	var upsells_products = $owl_carousel(".upsells ul.products li").length;
	if (upsells_products > 3) {
		$owl_carousel('.upsells ul.products').addClass('owl-carousel');
		$owl_carousel(".upsells ul.products").owlCarousel({
			navigation: true,
			pagination: false,
			items: 3, //10 items above 1000px browser width
			itemsDesktop: [1199, 3], //5 items between 1000px and 901px
			itemsDesktopSmall: [979, 2], // betweem 900px and 601px
			itemsTablet: [768, 2], //2 items between 600 and 0
			itemsMobile: [479, 1] // itemsMobile disabled - inherit from itemsTablet option
		});
	}
	var related_products = $owl_carousel(".related ul.products li").length;
	if (related_products > 3) {
		$owl_carousel('.related ul.products').addClass('owl-carousel');
		$owl_carousel(".related ul.products").owlCarousel({
			navigation: true,
			pagination: false,
			items: 3, //10 items above 1000px browser width
			itemsDesktop: [1199, 3], //5 items between 1000px and 901px
			itemsDesktopSmall: [979, 2], // betweem 900px and 601px
			itemsTablet: [768, 2], //2 items between 600 and 0
			itemsMobile: [479, 1] // itemsMobile disabled - inherit from itemsTablet option
		});
	}
	var crosssells_products = $owl_carousel(".cross-sells ul.products li").length;
	if (crosssells_products > 3) { 
		$owl_carousel('.cross-sells ul.products').addClass('owl-carousel');
		$owl_carousel(".cross-sells ul.products").owlCarousel({
			navigation: true,
			pagination: false,
			items: 3, //10 items above 1000px browser width
			itemsDesktop: [1199, 3], //5 items between 1000px and 901px
			itemsDesktopSmall: [979, 2], // betweem 900px and 601px
			itemsTablet: [768, 2], //2 items between 600 and 0
			itemsMobile: [479, 1] // itemsMobile disabled - inherit from itemsTablet option
		});
	}
	
	jQuery(".team-carousel").each(function () {  
		if( $owl_carousel(this).attr('id') ) {
			var team_items = $owl_carousel(this).attr('id').replace('_team_carousel','');
			$owl_carousel('.team-carousel').addClass('owl-carousel');
			$owl_carousel(".team-carousel").owlCarousel({
				navigation : false,
				pagination : true,
				items : team_items, //10 items above 1000px browser width
				itemsDesktop : [1199,team_items], //5 items between 1000px and 901px
				itemsDesktopSmall : [979,3], // betweem 900px and 601px
				itemsTablet: [768,2], //2 items between 600 and 0
				itemsMobile : [479,1] // itemsMobile disabled - inherit from itemsTablet option
			});
		}
	});
jQuery(".woo-carousel").each(function () {  
  if( $owl_carousel(this).attr('id') ) {
    var woo_items = $owl_carousel(this).attr('id').replace('_woo_carousel','');    
  var woo_products = $owl_carousel(this).find("ul.products .product").length;
    if (woo_products > woo_items) {
      $owl_carousel(this).find('ul.products').addClass('owl-carousel');
      $owl_carousel(this).find('ul.products').owlCarousel({
        navigation: true,
        pagination: false,
        items: woo_items, //10 items above 1000px browser width
        itemsDesktop: [1199, woo_items], //5 items between 1000px and 901px
        itemsDesktopSmall: [1200, 3], // betweem 900px and 601px
        itemsTablet: [767, 2], //2 items between 600 and 0
        itemsMobile: [479, 1] // itemsMobile disabled - inherit from itemsTablet option
      });
    }
  }
});  
 
/* End OWL carousel*/
});


//================== JS FOR Parellex=====================================//
jQuery(document).ready(function(){
	// Cache the Window object
	var $window;
	$window = jQuery(window);
                
   jQuery('div[data-type="background"]').each(function(){
     var $bgobj = jQuery(this); // assigning the object
                    
      jQuery(window).scroll(function() {
                    
		// Scroll the background at var speed
		// the yPos is a negative value because we're scrolling it UP!								
		var yPos = -($window.scrollTop() / $bgobj.data('speed')); 
		
		// Put together our final background position
		var coords = '50% '+ yPos + 'px';

		// Move the background
		$bgobj.css({ backgroundPosition: coords });
		
}); // window scroll Ends

 });	

}); 
/* 
 * Create HTML5 elements for IE's sake
 */

document.createElement("div");
document.createElement("section");
function isotopAutoSet() {
var $fas2 = jQuery.noConflict(); 
$fas2(function(){
    var $container = $fas2('#container .masonry');
	$fas2('#container .masonry').css("display", "block");
	$fas2('#container .loading').css("display", "none");	
    $container.isotope({
    });     
  });

var $fas = jQuery.noConflict(); 
$fas(function(){

    var $container = $fas('#box_filter');
	$fas('#container #box_filter').css("display", "block");
	$fas('#container .loading').css("display", "none");	
    $container.isotope({
    });
      var $optionSets = $fas('#blog_filter_options .option-set'),
          $optionLinks = $optionSets.find('a');

      $optionLinks.click(function(){
        var $this = $fas(this);
        // don't proceed if already selected
        if ( $this.hasClass('selected') ) {
          return false;
        }
        var $optionSet = $this.parents('.option-set');
        $optionSet.find('.selected').removeClass('selected');
        $this.addClass('selected');
  
        // make option object dynamically, i.e. { filter: '.my-filter-class' }
        var options = {},
            key = $optionSet.attr('data-option-key'),
            value = $this.attr('data-option-value');
        // parse 'false' as false boolean
        value = value === 'false' ? false : value;
        options[ key ] = value;
        if ( key === 'layoutMode' && typeof changeLayoutMode === 'function' ) {
          // changes in layout modes need extra logic
          changeLayoutMode( $this, options )
        } else {
          // otherwise, apply new options
          $container.isotope( options );
        }        
        return false;
      });

  });   
var $fas1 = jQuery.noConflict(); 
$fas1(function(){

    var $container1 = $fas1('#portfolio_filter');
	$fas('#portfolio_filter').css("display", "block");
	$fas('.loading').css("display", "none");
    $container1.isotope({
    });
      var $optionSets = $fas1('#portfolio_filter_options .option-set'),
          $optionLinks = $optionSets.find('a');

      $optionLinks.click(function(){
        var $this = $fas1(this);
        // don't proceed if already selected
        if ( $this.hasClass('selected') ) {
          return false;
        }
        var $optionSet = $this.parents('.option-set');
        $optionSet.find('.selected').removeClass('selected');
        $this.addClass('selected');
  
        // make option object dynamically, i.e. { filter: '.my-filter-class' }
        var options = {},
            key = $optionSet.attr('data-option-key'),
            value = $this.attr('data-option-value');
        // parse 'false' as false boolean
        value = value === 'false' ? false : value;
        options[ key ] = value;
        if ( key === 'layoutMode' && typeof changeLayoutMode === 'function' ) {
          // changes in layout modes need extra logic
          changeLayoutMode( $this, options )
        } else {
          // otherwise, apply new options
          $container1.isotope( options );
        }        
        return false;
      });

  }); 
}

jQuery(window).load(function(){isotopAutoSet();});
jQuery(window).resize(function(){isotopAutoSet();});


jQuery('.tagcloud').addClass('main-ul');
jQuery('#calendar_wrap').addClass('main-ul');
jQuery('.price_slider_wrapper').addClass('main-ul');
jQuery('#primary-sidebar #searchform').addClass('main-ul');
jQuery('.newsletter').addClass('main-ul');

function mobileToggleColumn() {

    if (jQuery(window).width() < 980) {

		jQuery(".site-footer .widget ul,.site-footer .widget .main-ul").css("display", "none");

        jQuery(".site-footer .widget .widget-title .mobile_togglecolumn").removeClass("clearfix");

        jQuery(".site-footer .widget .widget-title .mobile_togglecolumn").remove();

        jQuery(".site-footer .widget .widget-title").append("<span class='mobile_togglecolumn'></span>");

        jQuery(".site-footer .widget .widget-title").addClass("toggle");

        jQuery(".site-footer .widget .widget-title .mobile_togglecolumn").click(function() {

            jQuery(this).parent().toggleClass("active").parent().find("ul,.main-ul").toggle()

        })

    } else {

        jQuery(".site-footer .widget ul,.site-footer .widget .main-ul").css("display", "block");

        jQuery(".site-footer .widget .product-categories ul.children").css("display", "none");

        jQuery(".site-footer .widget .widget-title .mobile_togglecolumn").css("display", "none");

        jQuery(".site-footer .widget .widget-title").removeClass("toggle active");

        jQuery(".site-footer .widget .widget-title .mobile_togglecolumnn").remove()

    }

}
jQuery(document).ready(function(){mobileToggleColumn();});
jQuery(window).resize(function(){mobileToggleColumn();});

jQuery(window).load(function() {
jQuery('.flexslider').flexslider();
});

function navigationMenu() {
	if (jQuery(window).width() < 767){
			jQuery('#menu-menu').addClass('treeview-list');
			jQuery("#menu-menu.treeview-list").treeview({
				animated: "slow",
				collapsed: true,
				unique: true		
			});
			jQuery('#menu-menu.treeview-list a.active').parent().removeClass('expandable');
			jQuery('#menu-menu.treeview-list a.active').parent().addClass('collapsable');
			jQuery('.treeview-list .collapsable ul').css('display','block');
	}
}
jQuery(document).ready(navigationMenu);
function leftPageMenu()
{
	jQuery("#secondary .widget_pages ul").addClass('page-list');
	jQuery("#secondary .widget_pages ul.page-list").treeview({
		animated: "slow",
		collapsed: true,
		unique: true		
	});
}
jQuery(document).ready(leftPageMenu);
function leftCatMenu()
{
	 if (jQuery(window).width() < 980) {
		jQuery('.sidebar-category').addClass('treeview-list');
		jQuery(".sidebar-category.treeview-list").treeview({
			animated: "slow",
			collapsed: true,
			unique: true		
		});
		jQuery('.treeview-list a.active').parent().removeClass('expandable');
		jQuery('.treeview-list a.active').parent().addClass('collapsable');
		jQuery('.treeview-list .collapsable ul').css('display','block');
	 }
}
jQuery(document).ready(leftCatMenu);
jQuery(document).ready(function() {
		  if (jQuery(window).width() < 980) {
				jQuery('.primary-sidebar h1.widget-title').click(function () {
				if(jQuery(this).parent().hasClass('toggled-on')){									  
					jQuery(this).parent().removeClass('toggled-on');
					jQuery(this).parent().addClass('toggled-off');
				}
				else {
					jQuery(this).parent().addClass('toggled-on');
					jQuery(this).parent().removeClass('toggled-off');
				}
			});
		  }
});





jQuery(window).load(function() {
    "use strict";
    jQuery(".banner-loading").delay(2000).fadeOut("slow");

     jQuery(".container-inner").find(".wpb_wl_preview_area").each(function(i){
	jQuery(this).appendTo(jQuery(this).parent().find(".addto-cart-hover"));
     });
})

function category() {
		 if (jQuery(window).width() < 980){
				jQuery('.home-category-container .widget_product_categories .widget-title').click(function (i) { 
					var dropDown = jQuery(this).parent().find('.sidebar-category');
					jQuery(this).parent().find('.sidebar-category').not(dropDown).slideUp();	
					if (jQuery(this).hasClass('current')) { 
						jQuery(this).removeClass('current');
					} else { 
						jQuery(this).addClass('current');
					}
					dropDown.stop(false, true).slideToggle().css( { display : 'block' } );
					i.preventDefault();
		} );
		 }
}
jQuery(document).ready(function(){category();});

(function ($) {	
		   if (jQuery(window).width() < 768){
		$('.header-menu-toggle').click(function (i) { 
			var dropDown = $(this).parent().find('.header-menu');
			$(this).parent().find('.header-menu').not(dropDown).slideUp();	
			if ($(this).hasClass('current')) { 
				$(this).removeClass('current');
			} else { 
				$(this).addClass('current');
			}
			dropDown.stop(false, true).slideToggle().css( { display : 'block' } );
			i.preventDefault();
		} );
		   }
	} )(jQuery);

/* JS for move the crosssale below cart total */
function preloadFunc()
    {
        jQuery(".cross-sells").appendTo(".cart-collaterals");
    }
jQuery(document).ready(function() {
    "use strict";
    preloadFunc()
});

// JS for adding menu more link
function moreTab() {
	"use strict";
	if ((jQuery(window).width() >= 1150)) {	
		var max_elem = 8 ;
		jQuery('#site-navigation').addClass('more');
		jQuery('#site-navigation.more #menu-menu > li').first().addClass('home_first');
		var items = jQuery('#site-navigation.more #menu-menu > li');
		var surplus = items.slice(max_elem, items.length);	
		surplus.wrapAll('<li class="cat-item level-0 hiden_menu cat-parent"><ul class="children">');
		jQuery('.hiden_menu').prepend('<a href="#" class="level-0  activSub">More</a>');	
	}
	if ((jQuery(window).width() >= 767) && (jQuery(window).width() < 1150)) {	
		var max_elem = 5 ;
		jQuery('#site-navigation').addClass('more');
		jQuery('#site-navigation.more #menu-menu > li').first().addClass('home_first');
		var items = jQuery('#site-navigation.more #menu-menu > li');
		var surplus = items.slice(max_elem, items.length);	
		surplus.wrapAll('<li class="cat-item level-0 hiden_menu cat-parent"><ul class="children">');
		jQuery('.hiden_menu').prepend('<a href="#" class="level-0  activSub">More</a>');	
	}
}
jQuery(document).ready(function() {
    "use strict";
    moreTab()
});
//JS for More link in Sidebar Category block
jQuery(function($){	
"use strict"; 				
 if (jQuery(window).width() > 979) {						
	var max_elem = 11 ;
	jQuery('.home .sidebar-category .product-categories > li.cat-item').first().addClass('home_first');
	var items = jQuery('.home .sidebar-category .product-categories > li.cat-item');
	var surplus = items.slice(max_elem, items.length);		
	surplus.wrapAll('<li class="cat-item level-0 cat-parent hiden_menu "><ul class="children">');
	jQuery('.home .sidebar-category .hiden_menu').prepend('<a href="#" class="level-0 activSub">More</a>');		
  }
});	
jQuery(window).load(function() {  
    "use strict";
    // for plugin
    function hideChkboxWrapper(){ 
        jQuery('.tm-checkbox').each(function(index, el) { 
            var chk_dis = jQuery(el).find('input').css('display');
            setTimeout(function(){
                if (chk_dis == 'none') {
                    jQuery(el).hide();
                }
            },10);
        });
    }
    if(jQuery('#product_type').length > 0) { 
        jQuery('#product_type').change(function() {
            var product_type = jQuery(this).val();
            hideChkboxWrapper();
            if(product_type == 'simple'){
                jQuery('.tm-checkbox').show();
            }
        });
    };
    hideChkboxWrapper();
})