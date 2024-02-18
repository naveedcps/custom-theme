<?php
/**
 * The Header for our theme.
 */
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>" />
  <meta name="viewport" content="width=device-width" />
  
  <title><?php wp_title( '|', true, 'right' ); ?></title>

  <?php wp_head(); ?>  
</head>

<body <?php body_class(); ?>>
<?php 
$topbar_text = get_field('topbar_text', 'option');
?>
<div id="canvas">
  <header class="global">
    <div class="top-bar">
      <?=$topbar_text?>

      <div class="primary-menu-wrap">
          <?php 	
            wp_nav_menu( array( 'theme_location'=>'primary', 'container'=>'nav', 'container_class'=>'primary-nav' ) );
          ?>
      </div>
    </div>

    <div class="header-inner">
      <div class="container">
        <a class="logo" href="/">
            <img src="<?php echo esc_url( wp_get_attachment_url( get_theme_mod( 'custom_logo' ) ) ); ?>" alt="">
        </a>

        <form action="">
          <input type="search" placeholder="Search">
        </form>

        <ul class="right">
          <li class="account">
            <a href="/my-account">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
              <g clip-path="url(#clip0_802_1062)">
                <path d="M19 20.486V19.741C19 18.664 18.423 17.67 17.488 17.136L14.269 15.294" stroke="black" stroke-width="2" stroke-miterlimit="10"/>
                <path d="M9.727 15.292L6.512 17.136C5.577 17.67 5 18.664 5 19.741V20.486" stroke="black" stroke-width="2" stroke-miterlimit="10"/>
                <path d="M12 16C9.791 16 8 14.209 8 12V10C8 7.791 9.791 6 12 6C14.209 6 16 7.791 16 10V12C16 14.209 14.209 16 12 16Z" stroke="black" stroke-width="2" stroke-miterlimit="10" stroke-linecap="square"/>
                <path d="M12 23C18.0751 23 23 18.0751 23 12C23 5.92487 18.0751 1 12 1C5.92487 1 1 5.92487 1 12C1 18.0751 5.92487 23 12 23Z" stroke="black" stroke-width="2" stroke-miterlimit="10" stroke-linecap="square"/>
              </g>

              <defs>
                <clipPath id="clip0_802_1062">
                  <rect width="24" height="24" fill="white"/>
                </clipPath>
              </defs>
            </svg>
            
              <?php if(is_user_logged_in()): ?>
                My account
              <?php else : ?>
                Sign in
              <?php endif; ?>
            </a>
          </li>
          
          <?php echo do_shortcode("[woo_cart_but]"); ?>
          
        </ul>
      </div>
    </div>

    <div class="secondary-menu-wrap">
        <div class="container">
          <?php 	
            wp_nav_menu( array( 'theme_location'=>'secondary', 'container'=>'nav', 'container_class'=>'secondary-nav' ) );
          ?>

          <a class="shop-now" href="">Shop</a>
          
          <a href="#" id="mobile-menu-toggle" role="button" aria-label="Menu" aria-controls="navigation"> 
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
          </a>
        </div>
    </div>
  </header>

  <main>


