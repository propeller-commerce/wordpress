<?php
    use Propeller\PropellerHelper;
?>

<div class="row modal-product m-0">
    <div class="image col-2">	
        <?php if ($added_item->product->has_images()) { ?>
            <img class="img-fluid added-item-img" src="<?php echo esc_url($added_item->product->images[0]->images[0]->url); ?>" alt="<?php echo esc_attr($added_item->product->name[0]->value); ?>">
        <?php } else { ?> 
            <span class="no-image"></span>
        <?php } ?>
    </div>
    <div class="details col-10 col-md-5">  
        <div class="product-name added-item-name">
            <?php echo esc_html($added_item->product->name[0]->value); ?>
        </div>
        <div class="product-sku"><?php echo __('SKU', 'propeller-ecommerce'); ?>: 
            <span class="added-item-sku"><?php echo esc_html($added_item->product->sku); ?></span>
        </div>
    </div>
    <div class="offset-2 offset-md-0 col-10 col-md-5">
        <div class="product-price row align-items-center">
            <span class="col-6 col-md-7 col-lg-6 quantity"><?php echo __('Quantity', 'propeller-ecommerce'); ?>:&nbsp; 
                <span class="added-item-quantity"><?php echo esc_html($added_item->quantity); ?></span>
            </span>
            <div class="col-6 col-md-5 col-lg-6 d-flex justify-content-end product-item-price">
                <span class="symbol">&euro;&nbsp;</span>
                <span class="added-item-price"><?php echo PropellerHelper::formatPrice($added_item->totalPrice); ?></span>
            </div>
        </div> 
    </div>
</div>
<?php // } ?>