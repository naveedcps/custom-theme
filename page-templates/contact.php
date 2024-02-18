<?php 
//Template Name: General Template
get_header();
?>

<?php 
$hero_background_image = get_field('hero_background_image');
$hero_title = get_field('hero_title');
$hide_hero_title = get_field('hide_hero_title');
?>
<div id="hero" style="<?php if($hero_background_image){ ?>background-image: url('<?=$hero_background_image['url']?>');<?php } ?>" class="<?php if($hide_hero_title){ echo "no-hero-title"; } ?>">
    <?php if($hero_title): ?>
        <div class="container">
            <h1><?=$hero_title?></h1>
        </div>
    <?php endif; ?>
</div>
<?php 
$faq_sec_h  =   '<div id="faq"><div class="container">';
$faq_sec    =   '';
$faq_sec_f  =   '</div></div>';

    if( have_rows('sections') ):

        while ( have_rows('sections') ) : the_row();

            if( get_row_layout() == 'form' ):
                $title = get_sub_field('title');
                $content = get_sub_field('content');
                $form = get_sub_field('form');
                $right_side_content = get_sub_field('right_side_content');
                ?>
                    <div id="contact-wrapper">
                        <div class="container">
                            <header>
                                <?php if($title): ?>
                                    <h2><?=$title?></h2>
                                <?php endif; ?>

                                <?=$content?>
                            </header>

                            <div class="inner">
                                <?php if($form): ?>
                                    <div class="content">
                                        <div class="form-wrapper">
                                            <?php echo do_shortcode('[gravityform id="'.$form['id'].'" title="false" description="false" ajax="true"]'); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if($right_side_content): ?> 
                                    <div class="info">
                                        <?php foreach($right_side_content as $right_content): ?>
                                            <div>
                                                <?=$right_content['content']?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php

            elseif( get_row_layout() == 'tiles' ): 
                $title = get_sub_field('title');
                $content = get_sub_field('content');
                $tiles = get_sub_field('tiles');
                ?>
                    <div id="helpful-resources">
                        <div class="container">
                            <?php if($title): ?>
                                <h2><?=$title?></h2>
                            <?php endif; ?>

                            <?=$content?>
                            
                            <?php if($tiles): ?>
                                <div class="tiles">
                                    <?php foreach($tiles as $tile): ?>
                                        <div class="tile" style="background-color: <?=$tile['background_color']?>;">
                                            <?php if($tile['icon']): ?>
                                                <img src="<?=$tile['icon']['url']?>">
                                            <?php endif; ?>
                                            
                                            <?php if($tile['title']): ?>
                                                <h3><?=$tile['title']?></h3>
                                            <?php endif; ?>

                                            <?=$tile['content']?>
                                            
                                            <?php if($tile['cta']): ?>
                                                <a href="<?=$tile['cta']['url']?>"><?=$tile['cta']['title']?></a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php
            elseif( get_row_layout() == 'standard_content' ): 
                $section_width = get_sub_field('section_width');
                $title = get_sub_field('title');
                $content = get_sub_field('content');
                ?>
                    <div class="standard_content">
                        <div class="container">
                            <?php if($title): ?>
                                <h2><?=$title?></h2>
                            <?php endif; ?>

                            <?=$content?>
                        </div>
                    </div>
                <?php

            elseif( get_row_layout() == 'bold_headline' ): 
                $style = get_sub_field('style');
                $content = get_sub_field('content');
                ?>
                    <div class="bold_headline <?php if($style['font_color'] == "white"){ echo "white"; }elseif($style['font_color'] == "red"){ echo "red"; }elseif($style['font_color'] == "green"){ echo "green"; } ?>" style="background-color: <?=$style['background_color']?>;">
                        <div class="container">
                            <?=$content?>
                        </div>
                    </div>
                <?php

            elseif( get_row_layout() == 'faqs' ): 
                $title = get_sub_field('title');
                $faq = get_sub_field('faq');
                $faq_sec .= '<div class="faq-box">
                                <div class="left">';
                                    if($title):
                                        $faq_sec .= '<h2>' . $title . '</h2>';
                                    endif;
                                    $faq_sec .= '
                                </div>

                                <div class="accordion">
                                    <ul class="accordion-entries">';
                                        foreach($faq  as $faqs):
                                            $faq_sec .= '<li class="accordion-entry">
                                                <header>' . $faqs['question'] . '</header>
                                                <div class="accordion-body">
                                                    ' . $faqs['answer'] . '
                                                </div>
                                            </li>';
                                        endforeach;
                                        $faq_sec .= '</ul>
                                </div>
                            </div>';

            elseif( get_row_layout() == 'resources' ): 
                $links = get_sub_field('links');
                ?>
                    <div id="resources">
                        <div class="container">
                            <div class="inner">
                                <?php foreach($links as $linklist): ?>
                                    <div class="links-wrapper">
                                        <h2><?=$linklist['title']?></h2>

                                        <?php foreach($linklist['links_group'] as $linkgroup): ?>
                                            <div class="links-group">
                                                <h3><?=$linkgroup['title']?></h3>

                                                <?php foreach($linkgroup['link'] as $linkinfo): ?>
                                                    <div class="link-info">
                                                        <a href="<?=$linkinfo['link']['url']?>"><?=$linkinfo['link']['title']?></a>

                                                        <?php if($linkinfo['show_content']): 
                                                           echo $linkinfo['content'];
                                                         endif; ?>
                                                    </div>
                                                <?php endforeach; ?>

                                            </div>
                                        <?php endforeach; ?>

                                    </div>
                                <?php endforeach; ?>
                                
                            </div>
                        </div>
                    </div>
                <?php
            endif;

        endwhile;
    endif;

if($faq_sec){
    echo $faq_sec_h. $faq_sec .$faq_sec_f;
}
?>
<?php include('wp-content/themes/meritpharm/snippets/call-to-action.php'); ?>

<?php get_footer(); ?>