<?php 
//Template Name: Catalog and Brochures
get_header();
?>

<?php 
$hero_background_image = get_field('hero_background_image');
$hero_title = get_field('hero_title');
?>
<div id="hero" style="<?php if($hero_background_image){ ?>background-image: url('<?=$hero_background_image['url']?>');<?php } ?>">
    <?php if($hero_title): ?>
        <div class="container">
            <h1><?=$hero_title?></h1>
        </div>
    <?php endif; ?>
</div>

<div id="resources">
    <div class="container">
        <div class="inner">
        <?php
            $args = array(
                        'taxonomy'  => 'catalog_categories',
                        'orderby'   => 'name',
                        'parent'    => 0,
                        'order'     => 'ASC'
                    );

            $cats = get_categories($args);

            foreach($cats as $cat) {
                ?>
                    <div class="links-wrapper">
                        <h2><?php echo $cat->name; ?></h2>

                        <?php 
                            $sub_args = array(
                                'taxonomy'  => 'catalog_categories',
                                'orderby'   => 'name',
                                'child_of'  => $cat->term_id,
                                'order'     => 'ASC'
                            );
        
                            $sub_cats = get_categories($sub_args);

                            if($sub_cats){
                                foreach($sub_cats as $sub_cat){ 
                                    ?> 
                                        <div class="links-group">
                                            <h3><?php echo $sub_cat->name; ?></h3>
        
                                            <?php
                                                $args = array(  
                                                    'post_type' => 'catalog_and_brochure',
                                                    'post_status' => 'publish',
                                                    'posts_per_page' => -1, 
                                                    'tax_query' => array(
                                                        array(
                                                        'taxonomy' => 'catalog_categories',
                                                        'field' => 'term_id',
                                                        'terms' => $sub_cat->term_id
                                                        )
                                                    )
                                                );
                                        
                                                $loop = new WP_Query( $args ); 
        
                                                while ( $loop->have_posts() ) : $loop->the_post();
                                                    ?>
                                                        <div class="link-info">
                                                            <a target="_blank" href="<?php echo get_field('url'); ?>"><?php the_title(); ?></a>
        
                                                            <?php the_content(); ?>
                                                        </div>
                                                    <?php
                                                endwhile;
        
                                                wp_reset_postdata(); 
        
                                            ?> 
                                        </div> 
                                    <?php
                                }
                            } else {
                                ?>
                                    <div class="links-group">
                                        <?php
                                            $args = array(  
                                                'post_type' => 'catalog_and_brochure',
                                                'post_status' => 'publish',
                                                'posts_per_page' => -1, 
                                                'tax_query' => array(
                                                    array(
                                                    'taxonomy' => 'catalog_categories',
                                                    'field' => 'term_id',
                                                    'terms' => $cat->term_id
                                                    )
                                                )
                                            );
                                    
                                            $loop = new WP_Query( $args ); 
    
                                            while ( $loop->have_posts() ) : $loop->the_post();
                                                ?>
                                                    <div class="link-info">
                                                        <a target="_blank" href="<?php echo get_field('url'); ?>"><?php the_title(); ?></a>
    
                                                        <?php the_content(); ?>
                                                    </div>
                                                <?php
                                            endwhile;
    
                                            wp_reset_postdata(); 
    
                                        ?> 
                                    </div>
                                <?php
                            }

                        ?>
                    </div>
                <?php
            }
            ?>
            
        </div>
    </div>
</div>


<?php include('wp-content/themes/meritpharm/snippets/call-to-action.php'); ?>

<?php get_footer(); ?>