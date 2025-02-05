</main> <!-- Main End -->
</div> <!-- Canvas End -->

<?php 
$copyright_text = get_field('copyright_text', 'options');
?>

<footer class="global">
    <div class="container">
        <div class="inner">
            <div class="info">
                <?php dynamic_sidebar( 'footer-1' ); ?>
            </div>

            <div class="menu products">
                <?php dynamic_sidebar( 'footer-2' ); ?>
            </div>

            <div class="menu resourcers">
                <?php dynamic_sidebar( 'footer-3' ); ?>
            </div>


            <div class="menu company">
                <?php dynamic_sidebar( 'footer-4' ); ?>
            </div>

            <div class="footer-bottom">
                <?php if($copyright_text): ?>
                    <div class="copyright">
                        <p><?= do_shortcode($copyright_text); ?></p>
                    </div>
                <?php endif; ?>
                
                <?php dynamic_sidebar( 'footer-5' ); ?>
            </div> 
        </div>
    </div>
    <p>Lorem ipsum dolor sit</p>
</footer>
<?php wp_footer(); ?> 

</body>
</html>