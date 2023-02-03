<?php

use Propeller\Includes\Controller\SessionController;

$switch = (SessionController::get(PROPELLER_SPECIFIC_PRICES) ? 'on' : 'off');

?>
<div class="propeller-price-toggle-wrapper">
    <div class="price-toggle price-<?php echo esc_attr($switch); ?>">
        <a class="toggle-link d-flex align-items-center justify-content-between justify-content-md-end" rel="nofollow">
            <span class="toggle">
            </span>
            <span class="toggle-label label-off"><?php echo __('Excl. VAT', 'propeller-ecommerce'); ?></span>
            <span class="toggle-label label-on"><?php echo __('Incl. VAT', 'propeller-ecommerce'); ?></span>
        </a>
    </div>
</div>