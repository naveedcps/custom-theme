/* ==========================================================================
   Document Ready
   ========================================================================== */
jQuery(document).ready(function($){

   // Mobile menu
  
  var mobileMenuToggle  = $('#mobile-menu-toggle, .secondary-menu-wrap .container > span');
  var mobileMenu        = $('.secondary-nav');
  
  mobileMenuToggle.click(function(e){
    e.preventDefault();
    
    mobileMenuToggle.toggleClass('active');
    mobileMenu.slideToggle();

    $('html').toggleClass('menu-open');
  });

  // Dropdown menus
  
  // var dropdown  = $('nav.primary-nav .menu-item-has-children > a, nav.primary-nav li.cart > a');
  var dropdown  = $('nav.primary-nav li.cart > a');
  var menu      = $('.primary-menu-container');
  
  dropdown.click(function(e){
    //e.preventDefault();

    var $this     = $(this).parent();
    var submenu   = $this.find('.sub-menu');
    var classList = submenu.attr('class');
    
    submenus.removeClass('show');
    
    if (~classList.indexOf('show')) {
      $this.removeClass('active');
      
      if( menu.css('position') == 'static' ) {
        submenus.fadeOut();
      } else {
        submenus.slideUp();
      }
    } else {
      dropdown.parent().removeClass('active');

      if( menu.css('position') == 'static' ) {
        submenus.fadeOut();
      } else {
        submenus.slideUp();
      }

      $this.addClass('active');
      submenu.addClass('show');

      if( menu.css('position') == 'static' ) {
        submenu.fadeIn();
      } else {
        submenu.slideDown();
      }
    }
  });

  $('header.global .secondary-menu-wrap .secondary-nav>ul>li>.sub-menu>li').mouseover(function() {
    $('header.global .secondary-menu-wrap .secondary-nav>ul>li>.sub-menu>li').removeClass("active");
  });

  $('header.global .secondary-menu-wrap .secondary-nav>ul>li').mouseout(function() {
    $('header.global .secondary-menu-wrap .secondary-nav>ul>li>.sub-menu>li:first-child').addClass("active");
  });
  
  $(document).mouseup(function(event){
    var $target = $('nav.primary-nav .menu-item-has-children, nav.primary-nav li.cart, nav.secondary .menu-item-has-children')
    if($target !== event.target && ! $target.has(event.target).length){
      submenus.removeClass('show');
      dropdown.parent().removeClass('active');
      
      if( menu.css('position') == 'static' ) {
        submenus.fadeOut();
      } else {
        submenus.slideUp();
      }
    }            
  });

  jQuery(window).scroll(function($) {
    if(jQuery(window).width() < 856)
     {
       if(jQuery(window).scrollTop() > 185){
         jQuery('header.global .header-inner .container form input').hide();
       }
     
       else{
         jQuery('header.global .header-inner .container form input').show();
       }
     }

     if(jQuery(window).width() < 1024)
     {
      if(jQuery(window).scrollTop() > 185){
        jQuery('header.global .header-inner').addClass('fixed');
      }
    
      else{
        jQuery('header.global .header-inner').removeClass('fixed');
      }
     }
     else{
      if(jQuery(window).scrollTop() > 143){
        jQuery('header.global .header-inner').addClass('fixed');
      }
    
      else{
        jQuery('header.global .header-inner').removeClass('fixed');
      }
     }
 });

   $( ".accordion ul li" ).each(function( index ) {
      $(this).children('header').on("click", function(){
         if($(this).parent().hasClass('active')){
            $(this).parent().removeClass('active');
            $(this).parent().children('.accordion-body').slideUp();
         }
  
         else{
            $(this).parent().children('.accordion-body').slideDown();
            $(this).parent().addClass('active');
         }

         //$(this).parent().children('.accordion-body').slideDown();
         $(this).parent().siblings().children( ".accordion-body" ).slideUp(); 
         $(this).parent().siblings().removeClass('active');
         
      });
    });
  
    $('.product-options .option').each(function() {   //add a class to all external links
        var productOption  = jQuery(this).children('a');
        console.log(productOption.text()," : ",productOption);
        var productOptionURL = productOption.get(0).href;
        var url      = window.location.href;
        console.log("productionURLS",productOptionURL);
        console.log("urltext : ",url);
        if(productOptionURL != '' && productOptionURL == url) // -1 : Not found in a allowed hosts array & not found javacript in a URL
        {
          jQuery(this).children('a').addClass('active');
        }
    });

    $(".woocommerce-product-gallery__image").click(function() {
      $('.woocommerce-product-gallery__wrapper').prepend($(this));
    });

    $(".products-wrapper .shop-filters .facetwp-filters .facet-wrap h3").click(function() {
      $(this).siblings('.facetwp-facet').slideToggle();
      $(this).toggleClass('close');
    });

   $('body').on('click', '.filter-toggle', function() {
      if($(this).children('.show').css('display') == 'none'){
         $('.filter-toggle .show').show();
         $('.filter-toggle .hide').hide();
         $('.products-wrapper .shop-filters-wrap').toggle("slide");

         if($(window).width() < 864)
          {
            $('.products-wrapper .shop-filters-wrap').hide("slide", { direction: "up" }, 1000);
          } 
          else{
            $('.products-wrapper .shop-filters-wrap').hide("slide", { direction: "left" }, 1000);
          }
      }

      else if($(this).children('.hide').css('display') == 'none'){
         $('.filter-toggle .hide').show();
         $('.filter-toggle .show').hide();
         $('.products-wrapper .shop-filters-wrap').toggle("slide");

         if($(window).width() < 864)
          {
            $('.products-wrapper .shop-filters-wrap').show("slide", { direction: "down" }, 1000);
          } 
          else{
            $('.products-wrapper .shop-filters-wrap').show("slide", { direction: "right" }, 1000);
          }
      }

      setTimeout(function() { 
         $('.products-wrapper .facetwp-template .products').toggleClass('no-filters');
      }, 400);
   });

   var FeaturedItems = $('#hero .featured-items-slider');
  
   FeaturedItems.slick({
      dots: true,
      arrows: true,
      autoplay: true,
      autoplaySpeed: 3000,
      speed: 1000,
      fade: true,
      cssEase: 'linear'
   });

   jQuery('.product .wp-element-button').each(function(index, element) {
    if (jQuery(element).attr('aria-label').includes('Read') && jQuery(element).attr('aria-label').includes('more')) {
      jQuery(element).html('View Details');
    }
  });

});


/* ==========================================================================
   Functions
   ========================================================================== */