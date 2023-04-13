<?php

use Propeller\Includes\Controller\PageController;
use Propeller\Includes\Enum\PageType;
use Propeller\PropellerHelper;

?>
<svg style="display:none">
    <symbol viewBox="0 0 36 32" id="shape-header-shopping-cart"><title>Shopping cart</title> <path d="M29 32a4 4 0 0 0 2.788-6.867A1.5 1.5 0 0 0 30.333 24H11.83l-.75-4h20.711a1.5 1.5 0 0 0 1.469-1.194l2.708-13A1.5 1.5 0 0 0 34.499 4H8.08l-.52-2.776A1.5 1.5 0 0 0 6.084 0H.75A.75.75 0 0 0 0 .75v.5c0 .414.336.75.75.75h4.92l4.37 23.31A4 4 0 1 0 17 28v-.005c0-.725-.197-1.41-.536-1.995h9.072a3.96 3.96 0 0 0-.533 1.991L25 28a4 4 0 0 0 4 4zm2.385-14h-20.68L8.455 6h25.43l-2.5 12zM13 30c-1.103 0-2-.897-2-2s.897-2 2-2 2 .897 2 2-.897 2-2 2zm16 0c-1.103 0-2-.897-2-2s.897-2 2-2 2 .897 2 2-.897 2-2 2z"/></symbol>
</svg>
<div class="propeller-mini-header-buttons propeller-mini-shoping-cart dropdown">
    <a class="btn-header-shopping-cart" href="<?php echo esc_url($this->buildUrl('', PageController::get_slug(PageType::SHOPPING_CART_PAGE))); ?>" id="header-button-shoppingcart">
        <span class="cart-icon">
            <svg class="icon icon-shopping-cart">
                <use class="header-shape-shopping-cart" xlink:href="#shape-header-shopping-cart"></use>
            </svg>
            <span class="badge"><?php echo (int) $this->get_items_count(); ?></span>
        </span>
        <span class="cart-label d-none d-md-flex">
            <span class="cart-title"><?php echo __('Shopping cart', 'propeller-ecommerce'); ?></span>
            <span class="cart-total"><span class="symbol">&euro;&nbsp;</span>
            <span class="propel-mini-cart-total-price"><?php echo (int) $this->get_items_count() > 0 ? PropellerHelper::formatPrice($this->get_total_price()) : PropellerHelper::formatPrice(0,00); ?></span>
            </span>
        </span>
       
    </a>
</div>