<?php 
$call_to_action = get_field('call_to_action', 'option');
$show_call_to_action = get_field('show_call_to_action', get_the_ID());
?>

<?php if($show_call_to_action == "show"): ?>
    <aside id="cta">
        <div class="container">
            <div class="inner" style="background-image: url('<?=$call_to_action['background_image']['url']?>'); background-color:<?=$call_to_action['background_color']?>;">
                <div class="content">
                    <?php if($call_to_action['title']['overline']): ?>
                        <span><?=$call_to_action['title']['overline']?></span>
                    <?php endif; ?>
                    
                    <?php if($call_to_action['title']['title']): ?>
                        <h2><?=$call_to_action['title']['title']?></h2>
                    <?php endif; ?>

                    <?=$call_to_action['content']?>
                    
                    <?php if($call_to_action['form']): ?>
                        <?php echo do_shortcode('[gravityform id="'.$call_to_action['form']['id'].'" title="false" description="false" ajax="true"]'); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </aside>
<?php endif; ?>