<fieldset>
    <div class="row form-group">
        <div class="col-form-fields col-12">
            <div class="row form-row delivery-addresses-wrapper">
                <?php 
                    foreach ($delivery_addresses as $delivery_address)
                        apply_filters('propel_checkout_delivery_address', $delivery_address, $cart, $obj);
                ?>
            </div>  
            <div class="row">
        
                <?php echo apply_filters('propel_checkout_delivery_address_new', $cart, $obj); ?>
        
            </div>
        </div>
    </div>
</fieldset>