<?php
    use Propeller\Includes\Controller\PageController;
    use Propeller\Includes\Enum\PageType;
?>
<ul>
    <li>
        <a href="<?php echo esc_url($this->buildUrl(PageController::get_slug(PageType::MY_ACCOUNT_MOBILE_PAGE),PageController::get_slug(PageType::MY_ACCOUNT_PAGE))); ?>"><?php echo __('My account details', 'propeller-ecommerce'); ?></a>
    </li>
    <li>
        <a href="<?php echo esc_url($this->buildUrl(PageController::get_slug(PageType::MY_ACCOUNT_MOBILE_PAGE),PageController::get_slug(PageType::ADDRESSES_PAGE))); ?>"><?php echo __('My addresses', 'propeller-ecommerce'); ?></a>
    </li>
    <li>
        <a href="<?php echo esc_url($this->buildUrl(PageController::get_slug(PageType::MY_ACCOUNT_MOBILE_PAGE),PageController::get_slug(PageType::ORDERS_PAGE))); ?>"><?php echo __('My orders', 'propeller-ecommerce'); ?></a>
    </li>
    <li>
        <a href="<?php echo esc_url($this->buildUrl(PageController::get_slug(PageType::MY_ACCOUNT_MOBILE_PAGE),PageController::get_slug(PageType::QUOTATIONS_PAGE))); ?>"><?php echo __('My quotes', 'propeller-ecommerce'); ?></a>
    </li>
    <?php /*
    <li>
        <a href="<?php echo esc_url($this->buildUrl(PageController::get_slug(PageType::MY_ACCOUNT_MOBILE_PAGE), PageController::get_slug(PageType::FAVORITES_PAGE))); ?>"><?php echo __('My favorites', 'propeller-ecommerce'); ?></a>
    </li>
    <li>
        <a href="<?php echo esc_url($this->buildUrl(PageController::get_slug(PageType::MY_ACCOUNT_MOBILE_PAGE), PageController::get_slug(PageType::ORDERLIST_PAGE))); ?>"><?php echo __('My orderlist', 'propeller-ecommerce'); ?></a>
    </li>
    <li>
        <a href="<?php echo esc_url($this->buildUrl(PageController::get_slug(PageType::MY_ACCOUNT_MOBILE_PAGE), PageController::get_slug(PageType::INVOICES_PAGE))); ?>"><?php echo __('My invoices', 'propeller-ecommerce'); ?></a>
    </li> */ ?>
</ul>