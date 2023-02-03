<?php

    use Propeller\Includes\Controller\PageController;
    use Propeller\Includes\Enum\OrderStatus;
    use Propeller\Includes\Enum\PageType;

?>
<div class="container-fluid px-0">
    <div class="row align-items-start justify-content-between">
        <div class="col-12 col-md-4">
            <a href="<?php echo home_url(); ?>" class="btn-continue">
                <?php echo __('Continue shopping', 'propeller-ecommerce'); ?>  
            </a>                    
        </div>
        <div class="col-12 col-md-8 d-flex justify-content-end text-sm-right">
            <div class="row">
                <div class="col-auto">
                    <a href="<?php echo esc_url($this->buildUrl('', PageController::get_slug(PageType::CHECKOUT_PAGE))); ?>" class="btn-checkout btn-outline btn-checkout-ajax" data-status="<?php echo OrderStatus::ORDER_STATUS_REQUEST; ?>">
                        <?php echo __('Request a quotation', 'propeller-ecommerce'); ?>   
                    </a>  
                </div>
                <div class="col">
                    <a href="<?php echo esc_url($this->buildUrl('', PageController::get_slug(PageType::CHECKOUT_PAGE))); ?>" class="btn-checkout btn-checkout-ajax" data-status="<?php echo OrderStatus::ORDER_STATUS_NEW; ?>">
                        <?php echo __('Continue to checkout', 'propeller-ecommerce'); ?>   
                    </a>  
                </div>
            </div>
                                
        </div>
    </div>
</div>