<?php 
//Template Name: Home
get_header(); ?>
<?php 
$hero = get_field('hero'); 
$intro = get_field('intro');
?>

<div id="hero">
    <div class="bg-gradient">
        <img src="/wp-content/uploads/2023/09/pattern.png" alt="">
    </div>
    <div class="container">
        <div class="featured-items-slider">
            <?php foreach($hero as $banner): ?>
                <div class="featured-item">
                    <div class="content">
                        <span><?=$banner['title']['overline']?></span>

                        <h2><?=$banner['title']['title']?></h2>

                        <?php if($banner['cta']): ?>
                            <a href="<?=$banner['cta']['url']?>"><?=$banner['cta']['title']?></a>
                        <?php endif; ?>
                    </div>
                    
                    <?php if($banner['image']): ?>
                        <div class="img-wrap">
                            <img src="<?=$banner['image']['url']?>">
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div id="about">
    <div class="container">
        <div class="inner" style="background-image:url('<?=$intro['image']['url']?>')">
            <div class="content">
                <span><?=$intro['title']['oveline']?></span>

                <h1><?=$intro['title']['title']?></h1>

                <?php if($intro['cta']): ?>
                    <a href="<?=$intro['cta']['url']?>"><?=$intro['cta']['title']?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php

if( have_rows('sections') ):

    while ( have_rows('sections') ) : the_row();

        if( get_row_layout() == 'popular_categories' ): 
            $title = get_sub_field('title');
            $categories = get_sub_field('categories');
            ?>
                <div id="popular-category">
                    <div class="container">
                        <h2><?=$title?></h2>

                        <?php if($categories): ?>
                            <div class="inner">
                                <?php foreach($categories as $cats): ?>
                                    <div class="category">
                                        <?php 
                                            $thumb_id = get_woocommerce_term_meta( $cats->term_id, 'thumbnail_id', true ); 
                                            $term_img = wp_get_attachment_url(  $thumb_id );
                                        ?>
                                        <img style="background: #D9D9D9" src="<?=$term_img?>">

                                        <h3><?php echo esc_html( $cats->name ); ?></h3>

                                        <a href="<?php echo esc_url( get_term_link( $cats ) ); ?>">View Products</a>
                                    </div>
                                <?php  endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php
        elseif( get_row_layout() == 'featured_content' ): 
            $background_color = get_sub_field('background_color');
            $title = get_sub_field('title');
            $products = get_sub_field('products');
            ?>
                <div id="featured-products" style="background-color: <?=$background_color?>;">
                    <div class="container">
                        <h2><?=$title?></h2>

                        <?php if($products): ?>
                            <div class="products">
                                <?php foreach($products as $product):
                                    $permalink = $product['link'];
                                    $title = $product['title'];
                                    $feature_img = $product['image']['url'];
                                    ?>
                                    <div class="product">
                                        <img src="<?=$feature_img?>">

                                        <h3><?=$title?></h3>

                                        <a href="<?=$permalink['url']?>"><?=$permalink['title']?></a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php
        elseif( get_row_layout() == 'products_list' ): 
            $title = get_sub_field('title');
            $cta = get_sub_field('cta');
            $products = get_sub_field('products');
            ?>

                <?php if (is_user_logged_in()):
                    $current_user = wp_get_current_user();
                    $user_id = $current_user->ID;
                    $customer_orders = wc_get_orders(array(
                        'customer' => $user_id,
                        'status'   => array('completed')
                    ));

                    if($customer_orders): ?>
                        <div id="trending-products">
                            <div class="container">
                                <header>
                                    <h2>Buy Again</h2>

                                    <a href="#">See All</a>
                                </header>

                                <div class="products">
                                    <?php foreach ($customer_orders as $order):
                                        $order_items = $order->get_items(); ?>

                                        <?php foreach ($order_items as $user_item):
                                            $user_product_id = $user_item->get_product_id();
                                            $user_product = wc_get_product($user_product_id); 
                                            $permalink = get_permalink( $user_product_id );
                                            $user_title = get_the_title( $user_product_id );
                                            $feature_img = get_the_post_thumbnail_url($user_product_id);
                                            ?>

                                            <div class="product">
                                                <a class="img-wrap" href="<?=$permalink?>"><img src="<?=$feature_img?>"></a>

                                                <div class="content">
                                                    <div>
                                                        <h3><a href="<?=$permalink?>"><?=$user_title?></a></h3>

                                                        <div class="price">$
                                                            <?php
                                                                echo get_post_meta( $user_product_id, '_regular_price', true);
                                                            ?>
                                                        </div>
                                                    </div>
                                                    
                                                    <a class="add-to-cart" href="<?=$permalink?>">Add to Cart</a>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>

                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <div id="trending-products">
                    <div class="container">
                        <header>
                            <h2><?=$title?></h2>
                            <?php if($cta): ?>
                                <a href="<?=$cta['url']?>"><?=$cta['title']?></a>
                            <?php endif; ?>
                        </header>
                        
                        <?php if($products): ?>
                            <div class="products">
                                <?php foreach($products as  $product): 
                                    $permalink = get_permalink( $product->ID );
                                    $product_title = get_the_title( $product->ID );
                                    $feature_img = get_the_post_thumbnail_url($product->ID);
                                    ?>

                                    <div class="product">
                                        <a class="img-wrap" href="<?=$permalink?>"><img src="<?=$feature_img?>"></a>

                                        <div class="content">
                                            <div>
                                                <h3><a href="<?=$permalink?>"><?=$product_title?></a></h3>

                                                <?php 
                                                    if(is_user_logged_in()){
                                                        echo "<div class='price'>";
                                                            $product = wc_get_product( $product->ID );
                                                            echo "$".$product->get_price();
                                                        echo "</div>";
                                                    }else{
                                                        echo "<a class='login-required' href='/my-account'>Login to see prices</a>";
                                                    } 
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php
        elseif( get_row_layout() == 'standard_content' ): 
            $content = get_sub_field('content');
            ?>
                <div id="disclaimer">
                    <div class="container">
                        <?=$content?>
                    </div>
                </div>
            <?php
        endif;

    endwhile;

endif;

?>

<?php include('wp-content/themes/meritpharm/snippets/call-to-action.php'); ?>
<?php get_footer(); ?>