<?php 

if($product->has_attributes()){
    foreach ($product->get_attributes() as $attribute) {
        if (($attribute->get_type() == 'text' || 
                $attribute->get_type() == 'list' || 
                $attribute->get_type() == 'enumlist' ||
                $attribute->get_type() == 'integer' ||
                $attribute->get_type() == 'decimal') && $attribute->has_value()){ ?>
                <div class="row no-gutters product-specs">
                    <div class="col col-sm-6">
                        <?php echo esc_attr($attribute->get_description()); ?>
                    </div>
                    <div class="col-6">
                        <?php echo esc_attr($attribute->get_value()); ?>
                    </div>
                </div>
            <?php 
        }
    }
}

if ($product->attributeItems->page < $product->attributeItems->pages) {
    $next_page = $product->attributeItems->page + 1;
    $offset = $product->attributeItems->offset;
    $show_more = true;

    if ($next_page >= $product->attributeItems->pages) {
        $next_page = $product->attributeItems->pages;
        $show_more = false;
    }
        
    if ($show_more) {
    ?>
        <div class="row no-gutters mt-5 show-more-container">
            <div class="col col-sm-12 text-center">
                <a href="#" class="load-attributes" data-page="<?php echo esc_attr($next_page); ?>" data-offset="<?php echo esc_attr($offset); ?>" data-tab="specifications" data-id="<?php echo esc_attr($product->productId); ?>">
                    <?php echo __("Show more", 'propeller-ecommerce'); ?>
                </a>
            </div>
        </div>
    <?php
    }
}