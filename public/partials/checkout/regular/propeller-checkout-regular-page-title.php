<?php

    use Propeller\Includes\Controller\SessionController;

?>
<div class="container-fluid px-0 checkout-header-wrapper">
    <div class="row align-items-start">
        <div class="col-12 col-sm mr-auto checkout-header">
            <?php if (SessionController::get(PROPELLER_ORDER_STATUS_TYPE) == 'REQUEST') { ?>
                <h1><?php echo __('Quote request', 'propeller-ecommerce'); ?></h1>
            <?php } else { ?>
                <h1><?php echo __('Order', 'propeller-ecommerce'); ?></h1>
            <?php } ?>   
        </div>
    </div>
</div>