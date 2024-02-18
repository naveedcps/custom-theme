<?php

/**
 * Sets up theme defaults and registers the various WordPress features that
 * this theme supports
 */
function meritpharm_setup() {
	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'custom-logo' );
    add_theme_support('woocommerce');
	// Registers primary navigation menu
	register_nav_menus(
		array(
		  'primary' => __( 'Primary Menu' ),
		  'secondary' => __( 'Secondary Menu' )
		)
	  );
}
add_action( 'after_setup_theme', 'meritpharm_setup' );

require get_template_directory() . '/inc/woocommerce-hooks.php';

/**
 * Enqueues scripts and styles for front-end.
 */
if ( ! function_exists( 'c_scripts_styles' )) {
  function meritpharm_scripts_styles() {
  	global $wp_styles;

  	// Adds JavaScript to pages with the comment form to support sites with threaded comments (when in use).
  	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
    	wp_enqueue_script( 'comment-reply' );
  	}
  	
	wp_enqueue_style('Lato-font', 'https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&display=swap', array(), null);

    // Loads the slick slider script (for standard sliders)
    $file = '/assets/js/vendor/slick.min.js';
    wp_enqueue_script( 'slick-script', get_template_directory_uri() . $file, array('jquery'), filemtime( get_template_directory() . $file ) );
  	
    // Loads our Global JS file
    $file = '/assets/js/global.js';
    wp_enqueue_script( 'global-script', get_template_directory_uri() . $file, array('jquery'), filemtime( get_template_directory() . $file ) );
  	
  	// Loads the master stylesheet
  	$file = '/assets/css/master.css';
  	wp_enqueue_style( 'master-style', get_stylesheet_directory_uri() . $file, array(), filemtime( get_template_directory() . $file ) );    
  }
}
add_action( 'wp_enqueue_scripts', 'meritpharm_scripts_styles' );


/**
 * Creates a nicely formatted and more specific title element text
 * for output in head of document, based on current view.
 */
function meritpharm_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'client' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'meritpharm_wp_title', 10, 2 );


/**
 * Add ACF Options Page
 */
if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Global Content',
		'menu_title'	=> 'Global Content',
		'menu_slug' 	=> 'global-content',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
	
}

/**
 * Create shortcode to output year
 */
function meritpharm_output_current_year() {
	return date('Y');
  }
add_shortcode( 'year', 'meritpharm_output_current_year' );

 /**
 * Functions for Footers.
*/
function meritpharm__footer_widgets() {
 
    register_sidebar( array(
        'name' => __( 'Footer 1' ),
        'id' => 'footer-1',
        'description' => __( 'The will appear in the footer' ),
		'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ) );
	
	register_sidebar( array(
        'name' => __( 'Footer 2' ),
        'id' => 'footer-2',
        'description' => __( 'The will appear in the footer' ),
		'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ) );

	register_sidebar( array(
        'name' => __( 'Footer 3' ),
        'id' => 'footer-3',
        'description' => __( 'The will appear in the footer' ),
		'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ) );

	register_sidebar( array(
        'name' => __( 'Footer 4' ),
        'id' => 'footer-4',
        'description' => __( 'The will appear in the footer' ),
		'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ) );

	register_sidebar( array(
        'name' => __( 'Footer 5' ),
        'id' => 'footer-5',
        'description' => __( 'The will appear in the footer' ),
		'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ) );
   
    }
 
add_action( 'widgets_init', 'meritpharm__footer_widgets' );

add_filter( 'gform_required_legend', '__return_empty_string' );

function add_optional_label($field_content, $field, $form, $entry) {
    // Check if the field is not required
    if (!rgar($field, 'isRequired')) {
        // Find the label within the field content
        preg_match('/<label[^>]*>(.*?)<\/label>/i', $field_content, $label_matches);

        if (isset($label_matches[0])) {
            $label = $label_matches[0];
            $label_with_optional = str_replace('</label>', ' <span class="optional">(Optional)</span></label>', $label);
            $field_content = str_replace($label, $label_with_optional, $field_content);
        }
    }

    return $field_content;
}
add_filter('gform_field_content', 'add_optional_label', 10, 4);

add_shortcode ('woo_cart_but', 'woo_cart_but' );

function woo_cart_but() {
	ob_start();
 
        $cart_count = WC()->cart->cart_contents_count;
        $cart_url = wc_get_cart_url(); 
  
        ?>
            <li class="cart">
                <a class=" cfw-side-cart-open-trigger" style="" aria-expanded="false" aria-controls="cfw_side_cart" tabindex="10" role="button" aria-label="View cart">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/shopping-cart.svg">
                    <div class="cfw-side-cart-quantity "></div>
                </a>
            </li>
        <?php
	        
    return ob_get_clean();
 
}

add_filter( 'woocommerce_add_to_cart_fragments', 'woo_cart_but_count' );
function woo_cart_but_count( $fragments ) {
 
    ob_start();
    
    $cart_count = WC()->cart->cart_contents_count;
    $cart_url = wc_get_cart_url();
    
    ?>
    <a class="cart-contents menu-item" href="<?php echo $cart_url; ?>">
        <img src="/wp-content/uploads/2023/08/shopping-cart-1.png">
        
	    <?php
            if ( $cart_count > 0 ) {
                ?>
                <span class="cart-contents-count"><?php echo $cart_count; ?></span>
                <?php            
            }
        ?>
    </a>
    <?php
 
    $fragments['a.cart-contents'] = ob_get_clean();
     
    return $fragments;
}