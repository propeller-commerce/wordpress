<?php

use Propeller\Includes\Enum\AddressType;

$invoice_address = $this->get_invoice_address();

$id = rand(1, 100);

?>
<div class="propeller-checkout-wrapper">
    <div class="container-fluid px-0 checkout-header-wrapper">
        <div class="row align-items-start">
            <div class="col-12 col-sm mr-auto checkout-header">
                <h1><?php echo __('Order', 'propeller-ecommerce'); ?></h1>
            </div>
        </div>
    </div>
    <div class="container-fluid px-0">
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="checkout-wrapper-steps">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <div class="checkout-step"><?php echo __('Step 1', 'propeller-ecommerce'); ?></div>
                            <div class="checkout-title"><?php echo __('Invoice details', 'propeller-ecommerce'); ?></div>
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <div class="checkout-step-nr">1/3</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <form name="checkout" class="form-handler checkout-form validate" method="post" action="">
                                <input type="hidden" name="action" value="cart_update_address" />
                                <input type="hidden" name="step" value="<?php echo esc_attr($slug); ?>" />
                                <input type="hidden" name="next_step" value="2" />
                                <input type="hidden" name="type" value="<?php echo AddressType::INVOICE; ?>" />
                                <input type="hidden" name="icp" value="N" />

                                <fieldset class="personal">
                                    <div class="row form-group">
                                        <div class="col-form-fields col-12">
                                            <div class="form-row">
                                                <div class="col-12 col-md-8 form-group col-user-mail">
                                                    <label class="form-label" for="email_<?php echo esc_attr($id); ?>"><?php echo __('E-mail address', 'propeller-ecommerce'); ?>*</label>
                                                    <input type="email" name="email" value="<?php echo esc_attr($invoice_address->email); ?>" placeholder="<?php echo __('E-mail address', 'propeller-ecommerce'); ?>*" class="form-control required email" id="email_<?php echo esc_attr($id); ?>">
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-form-fields col-12">
                                            <div class="form-row">
                                                <div class="col-12 col-md-8 form-group col-user-password">
                                                    <label class="form-label" for="password_<?php echo esc_attr($id); ?>"><?php echo __('Password (optional)', 'propeller-ecommerce'); ?></label>
                                                    <input type="password" name="password" value="" class="form-control" placeholder="<?php echo __('Password (optional)', 'propeller-ecommerce'); ?>" id="password_<?php echo esc_attr($id); ?>">
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-form-fields col-12">
                                            <div class="form-row">
                                                <div class="col-auto form-group form-check">
                                                    <label class="btn-radio-checkbox form-check-label ">
                                                        <input type="radio" class="form-check-input" name="gender" value="M" <?php echo (string) $invoice_address->gender == 'M' ? 'checked' : ''; ?>> <span><?php echo __('Mr.', 'propeller-ecommerce'); ?></span>
                                                    </label>
                                                </div>
                                                <div class="col-auto form-group form-check">
                                                    <label class="btn-radio-checkbox form-check-label ">
                                                        <input type="radio" class="form-check-input" name="gender" value="F"<?php echo (string) $invoice_address->gender == 'F' ? 'checked' : ''; ?>> <span><?php echo __('Mrs.', 'propeller-ecommerce'); ?></span>
                                                    </label>
                                                </div>
                                                <div class="col-auto form-group form-check">
                                                    <label class="btn-radio-checkbox form-check-label ">
                                                        <input type="radio" class="form-check-input" name="gender" value="U"<?php echo (string) $invoice_address->gender == 'U' ? 'checked' : ''; ?>> <span><?php echo __('Unknown', 'propeller-ecommerce'); ?></span>
                                                    </label>
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-form-fields col-12">
                                            <div class="form-row">
                                                <div class="col-12 col-md form-group col-user-firstname">
                                                    <label class="form-label" for="firstName_<?php echo esc_attr($id); ?>"><?php echo __('First name', 'propeller-ecommerce'); ?>*</label>
                                                    <input type="text" name="firstName" value="<?php echo esc_attr($invoice_address->firstName); ?>" placeholder="<?php echo __('First name', 'propeller-ecommerce'); ?>*" class="form-control required" id="firstName_<?php echo esc_attr($id); ?>">
                                                </div>
                                                <div class="col-12 col-md form-group col-user-middlename">
                                                    <label class="form-label" for="middleName_<?php echo esc_attr($id); ?>"><?php echo __('Insertion (optional)', 'propeller-ecommerce'); ?></label>
                                                    <input type="text" name="middleName" value="<?php echo esc_attr($invoice_address->middleName); ?>" placeholder="<?php echo __('Insertion (optional)', 'propeller-ecommerce'); ?>" class="form-control" id="middleName_<?php echo esc_attr($id); ?>">
                                                </div>
                                                <div class="col-12 col-md form-group col-user-lastname">
                                                    <label class="form-label" for="lastName_<?php echo esc_attr($id); ?>"><?php echo __('Last name', 'propeller-ecommerce'); ?>*</label>
                                                    <input type="text" name="lastName" value="<?php echo esc_attr($invoice_address->lastName); ?>" placeholder="<?php echo __('Last name', 'propeller-ecommerce'); ?>*" class="form-control required" id="lastName_<?php echo esc_attr($id); ?>">
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-form-fields col-12">
                                            <div class="form-row">
                                                <div class="col-12 col-md-8 form-group col-user-phone">
                                                    <label class="form-label" for="phone_<?php echo esc_attr($id); ?>"><?php echo __('Phone number', 'propeller-ecommerce'); ?>*</label>
                                                    <input type="text" name="phone" value="<?php echo esc_attr($invoice_address->phone); ?>" placeholder="<?php echo __('Phone number', 'propeller-ecommerce'); ?>*" class="form-control required" id="phone_<?php echo esc_attr($id); ?>">
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend class="checkout-header">
                                    <?php echo __('Billing address', 'propeller-ecommerce'); ?>
                                    </legend>
                                    <div class="row form-group">
                                        <div class="col-form-fields col-12">
                                            <div class="form-row">
                                                <div class="col-12 col-md-8 form-group col-user-company">
                                                    <label class="form-label" for="company_<?php echo esc_attr($id); ?>"><?php echo __('Company name', 'propeller-ecommerce'); ?>*</label>
                                                    <input type="text" name="company" value="<?php echo esc_attr($invoice_address->company); ?>" placeholder="<?php echo __('Company name', 'propeller-ecommerce'); ?>*" class="form-control required" id="company_<?php echo esc_attr($id); ?>">
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-form-fields col-12">
                                            <div class="form-row">
                                                <div class="col-8 form-group col-user-street">
                                                    <label class="form-label" for="street_<?php echo esc_attr($id); ?>"><?php echo __('Street name', 'propeller-ecommerce'); ?>*</label>
                                                    <input type="text" name="street" value="<?php echo esc_attr($invoice_address->street); ?>" placeholder="<?php echo __('Street number', 'propeller-ecommerce'); ?>*" class="form-control required" id="street_<?php echo esc_attr($id); ?>">
                                                </div>
                                                <div class="col-4 form-group col-user-street-number">
                                                    <label class="form-label" for="number_<?php echo esc_attr($id); ?>"><?php echo __('Number', 'propeller-ecommerce'); ?>*</label>
                                                    <input type="text" name="number" value="<?php echo esc_attr($invoice_address->number); ?>" placeholder="<?php echo __('Number', 'propeller-ecommerce'); ?>*" class="form-control required" id="number_<?php echo esc_attr($id); ?>">
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-form-fields col-12">
                                            <div class="form-row">
                                                <div class="col-12 col-md-8 form-group col-user-address_add">
                                                    <label class="form-label" for="numberExtension_<?php echo esc_attr($id); ?>"><?php echo __('Address addition (building, unit, etc, optional)', 'propeller-ecommerce'); ?></label>
                                                    <input type="text" name="numberExtension" value="<?php echo esc_attr($invoice_address->numberExtension); ?>" placeholder="<?php echo __('Address addition (building, unit, etc, optional)', 'propeller-ecommerce'); ?>" class="form-control" id="numberExtension_<?php echo esc_attr($id); ?>">
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-form-fields col-12">
                                            <div class="form-row">
                                                <div class="col-12 col-md-8 form-group col-user-zipcode">
                                                    <label class="form-label" for="postalCode_<?php echo esc_attr($id); ?>"><?php echo __('Postal code', 'propeller-ecommerce'); ?>*</label>
                                                    <input type="text" name="postalCode" value="<?php echo esc_attr($invoice_address->postalCode); ?>" placeholder="<?php echo __('Postal code', 'propeller-ecommerce'); ?>*" class="form-control required" id="postalCode_<?php echo esc_attr($id); ?>">
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-form-fields col-12">
                                            <div class="form-row">
                                                <div class="col-12 col-md-8 form-group col-user-city">
                                                    <label class="form-label" for="city_<?php echo esc_attr($id); ?>"><?php echo __('City', 'propeller-ecommerce'); ?>*</label>
                                                    <input type="text" name="city" value="<?php echo esc_attr($invoice_address->city); ?>" placeholder="<?php echo __('City', 'propeller-ecommerce'); ?>*" class="form-control required" id="city_<?php echo esc_attr($id); ?>">
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-form-fields col-12">
                                            <div class="form-row">
                                                <div class="col-12 col-md-8 form-group col-user-country">
                                                    <label class="form-label" for="country_<?php echo esc_attr($address->id); ?>"><?php echo __('Country', 'propeller-ecommerce'); ?>*</label>
                                                    <?php 
                                                        $countries = include PROPELLER_PLUGIN_DIR . '/includes/Countries.php'; 
                                                        $selected = 'NL';

                                                        if (isset($invoice_address->country) && !empty($invoice_address->country))
                                                            $selected = $invoice_address->country;
                                                    ?>

                                                    <select id="country_<?php echo esc_attr($id); ?>" name="country" class="form-control required">
                                                        <?php foreach ($countries as $code => $name) { ?>
                                                            <option value="<?php echo esc_attr($code); ?>" <?php echo ($code == $selected ? 'selected' : ''); ?>><?php echo esc_html($name); ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-form-fields col-12">
                                            <div class="form-row">
                                                <div class="col-12 col-md-8 form-group col-user-taxnr">
                                                    <label class="form-label" for="taxnr"><?php echo __('VAT number', 'propeller-ecommerce'); ?>*</label>
                                                    <input type="text" name="taxnr" value="" placeholder="<?php echo __('VAT number', 'propeller-ecommerce'); ?>*" class="form-control required" id="taxnr">
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="row form-group form-group-submit">
                                    <div class="col-form-fields col-12">
                                        <div class="form-row">
                                            <div class="col-12 col-md-8">
                                                <button type="submit" class="btn-proceed"><?php echo __('Continue', 'propeller-ecommerce'); ?></button>
                                                <!--<a href="/checkout-2" class="btn-proceed"><?php echo __('Continue', 'propeller-ecommerce'); ?></a>-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="checkout-wrapper-steps">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <div class="checkout-step"><?php echo __('Step 2', 'propeller-ecommerce'); ?></div>
                            <div class="checkout-title"><?php echo __('Delivery', 'propeller-ecommerce'); ?></div>
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <div class="checkout-step-nr">2/3</div>
                        </div>
                    </div>
                </div>
                <div class="checkout-wrapper-steps">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <div class="checkout-step"><?php echo __('Step 3', 'propeller-ecommerce'); ?></div>
                            <div class="checkout-title"><?php echo __('Shipping method', 'propeller-ecommerce'); ?></div>
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <div class="checkout-step-nr">3/3</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                
                <?php include $this->partials_dir .'/cart/propeller-shopping-cart-totals.php'?>   
            </div>
        </div>
    </div>
</div>


<?php include $this->partials_dir .'/other/propeller-toast.php'?>