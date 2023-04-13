<?php

use Propeller\Includes\Controller\SessionController;
use Propeller\PropellerHelper;

    $delivery_address = $this->get_delivery_address();
    $countries = include PROPELLER_PLUGIN_DIR . '/includes/Countries.php'; 

?>

<div class="propeller-checkout-wrapper">
    <svg style="display: none;">
        <symbol viewBox="0 0 22 19" id="shape-warning"><title>Warning</title><path d="m9.62 7.447.232 3.937a.422.422 0 0 0 .42.397h1.455a.422.422 0 0 0 .421-.397l.232-3.937A.422.422 0 0 0 11.959 7H10.04a.422.422 0 0 0-.421.447zm2.857 6.303a1.477 1.477 0 1 0-2.954 0 1.477 1.477 0 0 0 2.954 0zm-.015-12.657c-.648-1.123-2.274-1.125-2.924 0L1.103 15.72c-.648 1.123.163 2.531 1.461 2.531h16.871c1.296 0 2.11-1.406 1.462-2.53L12.462 1.092zM2.745 16.246l8.072-13.992a.211.211 0 0 1 .366 0l8.072 13.992a.21.21 0 0 1-.183.316H2.928a.21.21 0 0 1-.183-.316z" fill="#FFA630"/></symbol>     
    </svg>
    <div class="container-fluid px-0 checkout-header-wrapper">
        <div class="row align-items-start">
            <div class="col-12 col-sm mr-auto checkout-header">
                <h1><?php echo __('Order (Dropshipment)', ''); ?></h1>
            </div>
        </div>
    </div>
    <div class="container-fluid px-0">
        <?php if(sizeof($this->cart->items)) { ?>
            <div class="row">
                <div class="col-12 col-lg-8">
                    <div class="checkout-wrapper-steps">
                        <div class="row align-items-start">
                            <div class="col-10 col-md-3">
                                <div class="checkout-title"><?php echo __('Your details', 'propeller-ecommerce'); ?></div>
                            </div>
                            <div class="col-12 col-md-7 col-lg-6 ml-md-auto order-3 order-md-2 user-details">
                                <div class="user-fullname">
                                    <?php if ($this->cart->invoiceAddress->gender === 'M') {
                                            echo SALUTATION_M;
                                        }
                                        else if ($this->cart->invoiceAddress->gender === 'F') {
                                            echo SALUTATION_F;
                                        }
                                        else {
                                            echo SALUTATION_U;
                                        }
                                            
                                    ?>
                                    <?php echo esc_html($this->cart->invoiceAddress->firstName); ?> <?php echo esc_html($this->cart->invoiceAddress->lastName); ?>
                                </div>
                                <div class="user-addr-details">
                                    <?php echo esc_html($this->cart->invoiceAddress->company); ?><br>
                                    <?php echo esc_html($this->cart->invoiceAddress->street); ?> <?php echo esc_html($this->cart->invoiceAddress->number); ?> <?php echo esc_html($this->cart->invoiceAddress->numberExtension); ?><br>
                                    <?php echo esc_html($this->cart->invoiceAddress->postalCode); ?> <?php echo esc_html($this->cart->invoiceAddress->city); ?><br>
                                    <?php 
                                        $code = $this->cart->invoiceAddress->country;
                                       

                                        if( !$countries[$code] ) 
                                            echo esc_html($code);
                                        else 
                                            echo esc_html($countries[$code]);
                                    ?>
                                    
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="checkout-wrapper-steps">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <div class="checkout-title"><?php echo __('Delivery', 'propeller-ecommerce'); ?></div>
                            </div>
                        </div>
                        <?php if (SessionController::has(PROPELLER_DEFAULT_DELIVERY_ADDRESS_CHANGED) && SessionController::get(PROPELLER_DEFAULT_DELIVERY_ADDRESS_CHANGED) == false) {?> 
                            <div class="row">
                                <div class="col-12">
                                    <div class="shipment-info">
                                        <?php echo __("For Dropshipment, adding a new (one-time) address is mandatory", 'propeller-ecommerce'); ?>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <a class="btn-address-edit address-edit open-edit-modal-form" 
                                        data-form-id="add_delivery_address" 
                                        data-title="<?php echo __('Add address', 'propeller-ecommerce'); ?>"
                                        data-target="#add_delivery_address_modal"
                                        data-toggle="modal"
                                        role="button">
                                        <?php echo __('Add address', 'propeller-ecommerce'); ?>
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                        <!-- TODO: display address here based on PROPELLER_DEFAULT_DELIVERY_ADDRESS_CHANGED session variable -->
                        <?php if (SessionController::has(PROPELLER_DEFAULT_DELIVERY_ADDRESS_CHANGED) && SessionController::get(PROPELLER_DEFAULT_DELIVERY_ADDRESS_CHANGED) == true) {?> 
                            <div class="row">
                                <div class="col-12 user-details">
                                    <div class="user-fullname">
                                        <?php echo esc_html($this->cart->deliveryAddress->firstName); ?> <?php echo esc_html($this->cart->deliveryAddress->lastName); ?>
                                    </div>
                                    <div class="user-addr-details">
                                        <?php echo esc_html($this->cart->deliveryAddress->company); ?><br>
                                        <?php echo esc_html($this->cart->deliveryAddress->street); ?> <?php echo esc_html($this->cart->deliveryAddress->number); ?> <?php echo esc_html($this->cart->deliveryAddress->numberExtension); ?><br>
                                        <?php echo esc_html($this->cart->deliveryAddress->postalCode); ?> <?php echo esc_html($this->cart->deliveryAddress->city); ?><br>
                                        <?php 
                                            $code = $this->cart->deliveryAddress->country;
                                        
                                            if( !$countries[$code] ) 
                                                echo esc_html($code);
                                            else 
                                                echo esc_html($countries[$code]);
                                        ?>
                                    </div>
                                </div> 
                                <div class="col-12">
                                    <a class="btn-address-edit address-edit open-edit-modal-form modify-dropshipment-address" 
                                        data-form-id="add_delivery_address" 
                                        data-title="<?php echo __('Edit', 'propeller-ecommerce'); ?>"
                                        data-target="#add_delivery_address_modal"
                                        data-toggle="modal"
                                        role="button">
                                        <?php echo __('Edit', 'propeller-ecommerce'); ?>
                                    </a>
                                </div>      
                            </div>
                        <?php } ?>

                        <div class="row">
                            <?php if (SessionController::has(PROPELLER_DEFAULT_DELIVERY_ADDRESS_CHANGED) && SessionController::get(PROPELLER_DEFAULT_DELIVERY_ADDRESS_CHANGED) == false) { ?> 
                                <div class="col-12">
                                    <div class="checkout-subtitle">
                                        <?php echo __('Shipping method', 'propeller-ecommerce'); ?>
                                    </div>
                                </div>
                                <div class="col-12"> 
                                    <div class="alert alert-warning">
                                        <svg class="icon icon-warning" aria-hidden="true">
                                            <use xlink:href="#shape-warning"></use>
                                        </svg>   
                                        <div>
                                            <?php echo __("Shipping costs are calculated after adding a delivery address.", 'propeller-ecommerce'); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } else { ?> 
                                <div class="col-12">
                                    <div class="checkout-subtitle">
                                        <?php echo __('Shipping costs', 'propeller-ecommerce'); ?>
                                    </div>
                                </div>
                                <div class="col-12"> 
                                    <?php echo '<span class="symbol">&euro;&nbsp;</span><span class="propel-total-shipping">' . PropellerHelper::formatPrice($this->cart->postageData->postage) . '</span>'; ?>
                                </div>    
                            <?php } ?>
                        </div>
                        <div class="row">
                            <div class="col-12">
                            <?php if (SessionController::has(PROPELLER_DEFAULT_DELIVERY_ADDRESS_CHANGED) && SessionController::get(PROPELLER_DEFAULT_DELIVERY_ADDRESS_CHANGED) == true) {?> 
                                <form name="checkout-delivery" class="form-handler checkout-form validate" method="post">
                                    <input type="hidden" name="action" value="cart_step_2" />
                                    <input type="hidden" name="step" value="<?php echo esc_attr($slug); ?>" />
                                    <input type="hidden" name="next_step" value="3" />
                                 
                                    <input type="hidden" name="phone" value="none-provided" />

                                    <div class="row form-group form-group-submit">
                                        <div class="col-form-fields col-12">
                                            <div class="form-row">
                                                <div class="col-12 col-md-8">
                                                <input type="submit" class="btn-proceed btn-green" value="<?php echo __('Order overview & place an order', 'propeller-ecommerce'); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <?php } ?>
                            </div>
                        </div> 
                    </div>
                </div>
               
                <?php echo apply_filters('propel_shopping_cart_totals', $this->cart, $this); ?>  
               
            </div>
        <?php } else { 
            wp_redirect(home_url());
            exit;
        } ?>
    </div>

</div>
<?php include $this->partials_dir .'/checkout/propeller-dropshipment-delivery-address.php'; ?>
<?php include $this->partials_dir . '/other/propeller-toast.php'; ?>
