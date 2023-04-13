<?php
    use Propeller\Includes\Controller\SessionController;
?>
<div class="row form-group form-group-submit">
    <div class="col-form-fields col-12">
        <div class="form-row">
            <div class="col-12 col-md-8">
                <?php if (SessionController::get(PROPELLER_ORDER_STATUS_TYPE) == 'REQUEST') { ?>
                    <button type="submit" class="btn-proceed btn-green"><?php echo __('Place a quote request', 'propeller-ecommerce'); ?></button>
                <?php } else { ?> 
                    <button type="submit" class="btn-proceed btn-green"><?php echo __('Place an order', 'propeller-ecommerce'); ?></button>
                <?php } ?>
            </div>
        </div>
    </div>
</div>