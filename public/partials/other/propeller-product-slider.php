<?php 
    wp_enqueue_style('slick_theme_css', $this->assets_url . '/css/lib/slick-theme.css', array(), null, 'all');
    wp_enqueue_style('slick_css', $this->assets_url . '/css/lib/slick.css', array(), null, 'all');
   
    wp_enqueue_script('slick', $this->assets_url . '/js/lib/slick.min.js', array( 'jquery'), null, true );
    wp_enqueue_script('slick_init', $this->assets_url . '/js/parts/crossupsell-slider.js', array( 'jquery'), null, true);
?>
<div class="row propeller-slider-wrapper">
    <?php if ($no_results) { ?>
        <h3><?php echo __('No results', 'propeller-ecommerce'); ?></h3>
    <?php } else { ?>
        <div class="col-12 propeller-slider">
            <?php require $this->load_template('partials', DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 'propeller-product-card.php'); ?>
        </div>
    <?php } ?>
</div>