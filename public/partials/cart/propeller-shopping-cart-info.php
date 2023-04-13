<?php if(sizeof($obj->get_items())) { ?>
    <div class="row align-items-start justify-content-between">
        <div class="col-12 col-sm-6">
            <a href="<?php echo home_url(); ?>" class="btn-continue">
            <?php echo __('Continue shopping', 'propeller-ecommerce'); ?>  
            </a>                    
        </div>
    </div> 
<?php } else { ?>
    <div class="row">
        <div class="col-12">
            <p><?php echo __('Your shopping cart is empty.', 'propeller-ecommerce'); ?></p>
        </div>
    </div>
<?php } ?>