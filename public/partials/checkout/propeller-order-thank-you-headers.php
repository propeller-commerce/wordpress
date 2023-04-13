<?php

use Propeller\Includes\Controller\PageController;
use Propeller\Includes\Enum\PageType;

?>
<div class="container-fluid px-0 checkout-header-wrapper">
    <div class="row align-items-start">
        <div class="col-12 col-sm mr-auto checkout-header">
            <?php if($order->status == 'REQUEST') { ?>
                <h1><?php echo __('Thank you for your quote request!', 'propeller-ecommerce'); ?></h1>
            <?php } else { ?>
                <h1><?php echo __('Thank you for your order!', 'propeller-ecommerce'); ?></h1>
            <?php } ?>
        </div>
        <div class="col-12 order-details">
            <?php if($order->status == 'REQUEST') { ?>
                <div><?php echo __('Your quote request with request number', 'propeller-ecommerce'); ?> <span class="order-number"><?php echo esc_html($order->id); ?></span> <?php echo __('has been placed.', 'propeller-ecommerce'); ?></div>
                <div><?php echo __('Your quote request confirmation has been sent to', 'propeller-ecommerce'); ?> <span class="user-email"><?php echo esc_html($order->email); ?></span>.</div>
            <?php } else { ?>
                <div><?php echo __('Your order with order number', 'propeller-ecommerce'); ?> <span class="order-number"><?php echo esc_html($order->id); ?></span> <?php echo __('has been placed.', 'propeller-ecommerce'); ?></div>
                <div><?php echo __('Your order confirmation has been sent to', 'propeller-ecommerce'); ?> <span class="user-email"><?php echo esc_html($order->email); ?></span>. <?php echo __('You can also find it in', 'propeller-ecommerce'); ?> <a href="<?php echo esc_url($obj->buildUrl('', PageController::get_slug(PageType::MY_ACCOUNT_PAGE))); ?>"><?php echo __('your account.', 'propeller-ecommerce'); ?></a></div>
            <?php } ?>            
        </div>
    </div>
</div>