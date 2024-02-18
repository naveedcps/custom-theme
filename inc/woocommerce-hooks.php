<?php
// Product Brand tag
add_action( 'woocommerce_single_product_summary' , 'add_custom_text_before_product_title', 1 ); 
function add_custom_text_before_product_title() {
    global $product;
    $taxonomy = 'merit_product_brand';
    $terms = get_the_terms( $product->get_id() , $taxonomy );
    foreach ( $terms as $term ) {
        $term_link = get_term_link( $term, $taxonomy );
        if( is_wp_error( $term_link ) )
        continue;
        $image = get_field('brand_logo', $taxonomy . '_' . $term->term_id);
        if($image){
            echo '<img src="'.$image["url"].'"/>';
        }else {
            echo '<span class="brand-label">'.$term->name.'</span>';
        }
    }
}

//Remove tabs
add_filter( 'woocommerce_product_tabs', 'merit_remove_all_product_tabs', 98 );
function merit_remove_all_product_tabs( $tabs ) {
  unset( $tabs['description'] );
  unset( $tabs['reviews'] );
  unset( $tabs['additional_information'] );
  return $tabs;
}

//product SKU and NDC
add_action('woocommerce_single_product_summary', 'merit_product_sku', 5);
function merit_product_sku(){
    global $product;
    $ndn_number = get_field('ndn_number');
    ?>  
        <?php if($product->get_shipping_class()): ?>
            <div class="product-badges">
                <?php $term = get_term_by( 'slug', $product->get_shipping_class(), 'product_shipping_class' ); ?>
                    <?php if($term->name == "Ship On Ice"): ?>
                        <div class="badge">
                            <img src="/wp-content/uploads/2023/09/snow-1.svg"> <?=$term->name?>
                        </div>
                        <div class="badge">
                            <img src="/wp-content/uploads/2023/09/remove-1.svg"> Non-Returnable
                        </div>
                    <?php else: ?>
                        <div class="badge">
                            <?=$term->name?>
                        </div>
                    <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php if($product->get_sku() || $ndn_number): ?>
            <div class='sku-wrapper'>
                <?php 
                    if($product->get_sku()){
                        ?>
                            <div><strong>SKU</strong><?=$product->get_sku()?></div>
                        <?php
                    }   
                if($ndn_number): ?>
                    <div><strong>NDC</strong><?=$ndn_number?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <?php $connected_products = get_field('connected_products'); ?>
        
        <?php if($connected_products): ?>
            <div class="product-options">
                <?php 
                    global $wpdb;
                    $productID = $product->id;
                    
                    $heading_sql = "SELECT * FROM `wp_postmeta` WHERE `post_id` = $productID AND `meta_key` LIKE 'connected_products_%_attribute_name'";

                    $heading_results = $wpdb->get_results($heading_sql);

                    foreach ($heading_results as $key => $heading_result) {
                        ?>
                            <h3><?php echo $heading_result->meta_value; ?></h3>

                            <?php 

                            $product_sql = "SELECT * FROM `wp_postmeta` 
                            WHERE `post_id` = $productID 
                            AND `meta_key` LIKE 'connected_products_" . $key . "_attribute_options_%_product'";

                            $product_label_sql = "SELECT * FROM `wp_postmeta` 
                            WHERE `post_id` = $productID 
                            AND `meta_key` LIKE 'connected_products_" . $key . "_attribute_options_%label'";

                            $product_results = $wpdb->get_results($product_sql); 
                            $product_label_results = $wpdb->get_results($product_label_sql);

                            ?>
                            
                            <div class="options">
                                <?php
                                    $i = 0;
                                    foreach ($product_results as $product_result) {
                                        ?>
                                            <div class="option">
                                                <a href="<?php echo get_permalink($product_result->meta_value); ?>"><?php echo $product_label_results[$i]->meta_value; ?></a>
                                            </div>
                                        <?php
                                        $i++;
                                    }
                                ?>
                            </div>
                        <?php
                    }
                ?>
            </div>
        <?php endif; ?>

    <?php

}


//product description
add_action('woocommerce_share', 'merit_product_description');
function merit_product_description(){ ?>
    <div class='product-description'>
        <?php the_content(); ?>
    </div>
    <div class="product-variations">
        <?php 
            global $product;
    
            $attributes = $product->get_attributes();
        
            foreach ( $attributes as $attribute ) {
                
                $name = $attribute->get_name(); 
                $options = $attribute->get_options(); 
                $visibility = $attribute->get_visible();
                
                if($visibility): ?>

                    <h4><?=$name?></h4>

                    <p><?php echo implode(", ", $options); ?></p>
                <?php endif;
            }
        ?>
        
    </div>

    <?php 
}

remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

//product CTA
add_action('woocommerce_after_single_product', 'merit_product_cta');
function merit_product_cta(){

    include('wp-content/themes/meritpharm/snippets/call-to-action.php');

}

//Remove Link from the prodcut images
add_filter( 'woocommerce_single_product_image_thumbnail_html', 'custom_remove_product_link' );
function custom_remove_product_link( $html ) {
  return strip_tags( $html, '<div><img>' );
}


remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10,0);

remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10,0);

add_action('woocommerce_before_main_content', 'cat_page_container');
function cat_page_container() {
        echo "<div class='container'>";
}

add_action('woocommerce_after_single_product_summary', 'cat_page_container_close');
add_action('woocommerce_share', 'cat_page_container_close');
function cat_page_container_close() {
    echo "</div>";
}

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

// change woocommerce thumbnail image size
//add_filter( 'woocommerce_get_image_size_gallery_thumbnail', 'override_woocommerce_image_size_gallery_thumbnail' );
function override_woocommerce_image_size_gallery_thumbnail( $size ) {
    return array(
        'width'  => 700,
        'height' => 500,
        'crop'   => 0,
    );
}

//Woocommerce breadcrumb edit
add_filter( 'woocommerce_breadcrumb_defaults', 'wcc_change_breadcrumb_delimiter' );
function wcc_change_breadcrumb_delimiter( $defaults ) {
$defaults['delimiter'] = ' <span>/</span> ';
return $defaults;
}

function fwp_wrapper_open() {
    if ( ! is_singular() ): ?>
        <div class="products-wrapper">
            <div class="shop-filters-wrap">
                <div class="shop-filters">
                    <?php if ( function_exists( 'facetwp_display' ) ) : ?>
                        <div class="facetwp-filters">
                            <?php $facets = FWP()->helper->get_facets();

                            foreach ( $facets as $facet ) {
                                ?>
                                <div class="facet-wrap">
                                    <?php if($facet['name'] != 'reset' && $facet['name'] != 'in_stock'): ?>
                                        <h3><?=$facet['label']?></h3>
                                    <?php endif;
                                    
                                    echo facetwp_display( 'facet', $facet['name'] ); ?>
                                </div>
                            <?php } ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="facetwp-template">
                <?php echo facetwp_display( 'selections' ); ?>

    <?php endif;
}
function fwp_wrapper_close() {
    if ( ! is_singular() ): 
        echo '
        </div><!-- end products-warpper -->
        </div><!-- end facetwp-template -->'; 
    endif;
}
add_action( 'woocommerce_before_shop_loop', 'fwp_wrapper_open', 5 );
add_action( 'woocommerce_after_shop_loop', 'fwp_wrapper_close', 15 );
add_action( 'woocommerce_no_products_found', 'fwp_wrapper_open', 5 );
add_action( 'woocommerce_no_products_found', 'fwp_wrapper_close', 15 );


add_action( 'woocommerce_before_shop_loop', 'merit_shop_hide_filters' );
function merit_shop_hide_filters(){
    ?>
        <div class="filter-toggle">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/setup-preferences-1.svg"> <span class="hide">Hide </span><span class="show">Show </span> Filters
        </div>
    <?php
}

//checkoutWC Custom header
add_action( 'cfw_before_header', function() {
	?>
        <header id="cfw-header" class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div id="cfw-logo-container">
                        <div class="cfw-logo">
                            <a title="meritpharm Home" href="<?=get_home_url()?>" class="logo">
                            </a>
                        </div>

                        <a href="<?=get_permalink( wc_get_page_id( 'shop' ) )?>">« Return to Store</a>
                    </div>
                </div>
            </div>
        </header>
	<?php
} );

//Login page extended text
add_action('woocommerce_login_form_end', 'merit_woocommerce_login_text');
function merit_woocommerce_login_text(){
    ?>
        <div class="signup_wrapper">
            <p>Don't have an account yet? <a href="/register">Sign Up</a></p>
        </div>
    <?php
}

add_action('woocommerce_after_shop_loop_item_title', 'vivazen_sku_and_ndn', 10);
function vivazen_sku_and_ndn(){
    global $product;

    if(is_user_logged_in()){
        ?>
            <?php if($product->get_sku()): ?>
                <div class='sku-wrapper'>
                    <div><strong>SKU</strong><?=$product->get_sku()?></div>
                    <?php if(get_field('ndn_number')): ?>
                        <div><strong>NDC</strong><?=get_field('ndn_number')?></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php
    }
}

add_filter( 'woocommerce_breadcrumb_defaults', 'jk_change_breadcrumb_home_text' );
function jk_change_breadcrumb_home_text( $defaults ) {
    $defaults['home'] = 'Products';
    return $defaults;
}

add_filter( 'woocommerce_breadcrumb_home_url', 'woo_custom_breadrumb_home_url' );
function woo_custom_breadrumb_home_url() {
    return '/products/';
}


if(!is_user_logged_in()){
    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
    
    add_filter( 'woocommerce_get_price_html', 'merit_remove_price_from_pdp');
    function merit_remove_price_from_pdp( $price ) {
        return '';
    }

    add_filter( 'woocommerce_is_purchasable', '__return_false' );
}

//datasheets on product page
add_action('woocommerce_product_thumbnails', 'vivazen_product_data_sheets', 40);
function vivazen_product_data_sheets(){
    global $product;
    $data_sheets = get_field('data_sheets');
    ?>
        <?php if($data_sheets): ?>
            <div class="products-files">
                <?php foreach($data_sheets as $data_sheet): ?>
                    <a target="_blank" href="<?=$data_sheet['file']?>">View <?=$data_sheet['label']?></a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php
}

//for hiding pricing and redirections 
function merit_add_roles_on_plugin_activation() 
  {
    add_role( 'pending', 'Pending Customer', array( 'read' => true, 'level_0' => true ) );
  }
register_activation_hook( __FILE__ , 'merit_add_roles_on_plugin_activation' );


function merit_get_current_users_role() 
  {
    global $wp_roles;

    $current_user = wp_get_current_user();
    $roles = $current_user->roles;
    $role = array_shift( $roles );

    return $role;
  }
  
  
  function merit_user_can_purchase() {
    $bool = false;
    
    if ( is_user_logged_in() ) {
      $roles        = merit_get_current_users_role();
      $banned_roles = array('subscriber', 'pending');
      
      if( ! in_array($roles, $banned_roles) ) {
        $bool = true;
      }
    }
    
    return $bool;
  }
  
  
  function merit_hide_price()
  {
    // add_action( 'woocommerce_single_product_summary', 'bbloomer_print_login_to_see', 31 );
    // add_action( 'woocommerce_after_shop_loop_item', 'bbloomer_print_login_to_see', 11 );
    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
    // remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
    // remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
    add_filter( 'woocommerce_product_search_field_product_add_to_cart_html', function() { return ''; } );
    
    return '<a class="login-required" href="' . get_permalink(woocommerce_get_page_id('myaccount')) . '?referral_product=' . get_the_ID() . '">' . __('Login to see prices &amp; order', 'merit_user') . '</a>';
  }
    
  
  add_filter('woocommerce_get_price_html', 'merit_user_show_price');
 
  function merit_user_show_price($price)
  {
    if( merit_user_can_purchase() ) {
      return $price;
    }
    else {
      return merit_hide_price();
    }
  }
  
  
  add_action('wp_login', 'user_role_check', 10, 2);
  
  function user_role_check( $user_login, $user ) {
    global $wp_roles;
    
    $roles = $user->roles;
    $role = array_shift( $roles );
  
    $user_roles = $role;  
    $banned_roles = array('subscriber', 'pending');
    
    if (in_array($user_roles, $banned_roles))
    {
      wp_logout();
      wp_redirect( '/pending-customer/' );
      exit;
    }
  }
  
  
  add_filter( 'woocommerce_new_customer_data', 'merit_set_new_customer_role');
  
  function merit_set_new_customer_role($new_customer_data){
   $new_customer_data['role'] = get_option( 'default_role' );
   return $new_customer_data;
  }
  
    
  // Referer
  function merit_login_form_add_referral_field() {
    $product_id = $_GET['referral_product'];
    
    if( $product_id ) {
      echo '<input type="hidden" name="referral_product" value="' . $product_id . '" />';
    }
  }
  add_action( 'woocommerce_login_form_end', 'merit_login_form_add_referral_field' );
  add_action('woocommerce_register_form_end', 'merit_login_form_add_referral_field');
  
  //Login redirect
  function merit_login_redirect($redirect) {
    if( isset($_POST['referral_product']) ) {
      $redirect = get_permalink($_POST['referral_product']);
    }
    return $redirect;
  }
  add_filter('woocommerce_login_redirect', 'merit_login_redirect', 10, 1);
  add_filter('woocommerce_registration_redirect', 'merit_login_redirect', 10, 1);
  
  // Register link shortcode
  function merit_register_link_shortcode($atts, $content = 'Register') {
  	extract(shortcode_atts(array(
  		'url'        => '/register/',
  		'parameters' => $_GET['referral_product'] ? '?referral_product='.get_the_permalink($_GET['referral_product']) : '',
  	), $atts));
  
    return '<a href="'.$url.$parameters.'">'.$content.'</a>';
  }
  add_shortcode('register_link','merit_register_link_shortcode');