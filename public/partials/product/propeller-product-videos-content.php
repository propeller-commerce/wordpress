<div class="row">
    <div class="col-12">
        <h3><?php echo __("Videos", 'propeller-ecommerce'); ?></h3>
    </div>
    <div class="col-12">
        <?php 
            if ($product->has_videos()) { 
                foreach ($product->videos as $vid) {
        ?>
            <div class="mb-4 embed-responsive embed-responsive-16by9 video">
                <iframe class="embed-responsive-item" src="<?php echo $vid->videos[0]->uri; ?>" title="<?php echo isset($vid->alt) && count($vid->alt) && !empty($vid->alt[0]->value) ? $vid->alt[0]->value : $vid->videos[0]->uri; ?>" allowfullscreen></iframe>
            </div>
        <?php
                }
        } else { ?>
            <p><?php echo __('No videos', 'propeller-ecommerce'); ?></p>
        <?php } ?>
    </div>
</div>