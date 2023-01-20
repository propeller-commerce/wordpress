<?php

use Propeller\Includes\Enum\CrossupsellTypes;

 if(!empty($obj->product->$type)) { ?>
    <div class="row no-gutters propeller-crossup <?php echo apply_filters('propel_crossupsell_classes', ''); ?>">
        <div class="col-12">
            <h2 class="product-info-title mt-5 mb-4">
                <?php 
                    if ($type == CrossupsellTypes::ACCESSORIES) echo __('Accessories products', 'propeller-ecommerce'); 
                    else if ($type == CrossupsellTypes::ALTERNATIVES) echo __('Alternative products', 'propeller-ecommerce');
                    else if ($type == CrossupsellTypes::RELATED) echo __('Related products', 'propeller-ecommerce');
                ?>
            </h2>
            <div class="row propeller-slider-wrapper">
                <div class="col-12 slick-crossup crossupsells-slider" data-slug="<?php echo $obj->slug; ?>" data-type="<?php echo $type; ?>" data-class="<?php echo $obj->product->class ?>" id="product-<?php echo $type; ?>-slider"></div>
            </div>
        </div>
    </div> 
<?php } ?>