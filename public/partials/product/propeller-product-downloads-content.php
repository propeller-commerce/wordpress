<div class="row">
    <div class="col-12">
        <h3><?php echo __('Downloads', 'propeller-ecommerce'); ?></h3>
    </div>
    <div class="col-12">
        <?php 
            if ($product->has_documents()) { 
                foreach ($product->documents as $doc) {
        ?>
            <div class="row no-gutters product-specs">
                <div class="col col-sm-6">
                    <?php echo isset($doc->description) && count($doc->description) && !empty($doc->description[0]->value) ? esc_html($doc->description[0]->value) : esc_url($doc->documents[0]->originalUrl); ?>
                </div>
                <div class="col-6">
                    <a href="<?php echo esc_url($doc->documents[0]->originalUrl); ?>" target="_blank">
                        <?php echo __('Download PDF', 'propeller-ecommerce'); ?>
                    </a>
                </div>
            </div>
            
        <?php
                }
        } else { ?>
            <p><?php echo __('No downloads', 'propeller-ecommerce'); ?></p>
        <?php } ?>
    </div>
</div>