<?php 
//Template Name: Resources
get_header();

$hero_background_image = get_field('hero_background_image');
$hero_title = get_field('hero_title');
$type_of_resource = get_field('type_of_resource');

/** */
$html       =   '';
$data_check =   0;
$cat_data   =   array();

$cats_args = array(
    'taxonomy'  => 'categories',
    'orderby'   => 'name',
    'parent'    => 0,
    'order'     => 'ASC'
);

$cats = get_categories($cats_args);
if($cats){
    foreach($cats as $cat) {
        $sub_cats_args = array(
            'taxonomy'  => 'categories',
            'orderby'   => 'name',
            'child_of'  => $cat->term_id,
            'order'     => 'ASC',
        );

        $sub_cats = get_categories($sub_cats_args);
        if($sub_cats){
            foreach($sub_cats as $sub_cat){
                $args_sub = array(  
                    'post_type' => 'resource',
                    'post_status' => 'publish',
                    'posts_per_page' => -1, 
                    'tax_query' => array(
                        'relation' => 'AND',
                        array(
                        'taxonomy' => 'categories',
                        'field' => 'term_id',
                        'terms' => $sub_cat->term_id
                        ),
                        array(
                            'taxonomy' => 'merit_resource_type',
                            'field'    => 'term_id',
                            'terms'    => $type_of_resource,
                        )
                    )
                );
        
                $loop_sub = new WP_Query( $args_sub );
                if($loop_sub->have_posts()){
                    $data_check =   1;
                    $html   .= '<div class="links-group">';
                    $html   .=  '<h3>'.$sub_cat->name.'</h3>';
                    while ( $loop_sub->have_posts() ) :
                        $loop_sub->the_post();
                        $html   .=  '<div class="link-info">
                        <a target="_blank" href="#">'.get_the_title().'</a>'.
                        '<p>'.get_the_content().'</p></div>';
                    endwhile;
                    wp_reset_postdata();
                    $html   .= '</div>';
                }
            }
        }else{
            $args_el = array(  
                'post_type' => 'resource',
                'post_status' => 'publish',
                'posts_per_page' => -1, 
                'tax_query' => array(
                    'relation' => 'AND',
                    array(
                    'taxonomy' => 'categories',
                    'field' => 'term_id',
                    'terms' => $cat->term_id
                    ),
                    array(
                        'taxonomy' => 'merit_resource_type',
                        'field'    => 'term_id',
                        'terms'    => $type_of_resource,
                    )
                )
            );
    
            $loop_el = new WP_Query( $args_el );
            if($loop_el->have_posts()){
                $html   .= '<div class="links-group">';
                while ( $loop_el->have_posts() ) :
                    $data_check =   1;
                    $loop_el->the_post();
                    $html   .=  '<div class="link-info">
                    <a target="_blank" href="#">'.get_the_title().'</a>'.
                    get_the_content()
                    .'</div>';
                endwhile;
                wp_reset_postdata();
                $html   .= '</div>';
            }
        }

        if($data_check){
            $cat_data[]   =   '<h2>'.$cat->name.'</h2>'.$html;
            $html = '';
        }

        $data_check =   0;
    }
}

foreach($cat_data as $cn){
    $fhtml   .= '<div class="links-wrapper">';
    $fhtml   .= $cn;
    $fhtml   .= '</div>';
}

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
            <?php echo $fhtml; ?>
        </div>
    </div>
</div>
<?php include('wp-content/themes/meritpharm/snippets/call-to-action.php'); ?>
<?php get_footer(); ?>