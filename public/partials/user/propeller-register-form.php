<?php

use Propeller\Includes\Controller\FlashController;
use Propeller\Includes\Enum\AddressType;
use Propeller\Includes\Enum\UserTypes;

 ?>
 <div class="container-fluid px-0 propeller-checkout-wrapper">
    <div class="row">
        <div class="col-12 col-md-9 mr-auto">
            <form name="register" class="form-handler register-form checkout-form validate" method="post">
                <input type="hidden" name="action" value="do_register">
                <input type="hidden" name="user_type" value="<?php echo UserTypes::CONTACT; ?>">

                <?php if (FlashController::get('register_referrer')) { ?>
                    <input type="hidden" name="referrer" value="<?php echo esc_url(FlashController::flash('register_referrer')); ?>">
                <?php } ?>

                <fieldset class="personal">
                    <legend class="checkout-header">
                        <?php echo __('Your details', 'propeller-ecommerce'); ?>
                    </legend>
                    <div class="row form-group">
                        <div class="col-form-fields col-12">
                            <div class="form-row">
                                <div class="col-auto form-group form-check">
                                    <label class="btn-radio-checkbox form-check-label ">
                                        <input type="radio" class="form-check-input user-type-radio" name="parentId" data-type="<?php echo UserTypes::CONTACT; ?>" value="<?php echo PROPELLER_DEFAULT_CONTACT_PARENT; ?>" checked> <span><?php echo __('Company', 'propeller-ecommerce'); ?></span>
                                    </label>
                                </div>
                                <div class="col-auto form-group form-check">
                                    <label class="btn-radio-checkbox form-check-label ">
                                        <input type="radio" class="form-check-input user-type-radio" name="parentId" data-type="<?php echo UserTypes::CUSTOMER; ?>" value="<?php echo PROPELLER_DEFAULT_CUSTOMER_PARENT; ?>"> <span><?php echo __('Consumer', 'propeller-ecommerce'); ?></span>
                                    </label>
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-form-fields col-12">
                            <div class="form-row">
                                <div class="col-auto form-group form-check">
                                    <label class="btn-radio-checkbox form-check-label ">
                                        <input type="radio" class="form-check-input" name="gender" value="M"> <span><?php echo __('Mr.', 'propeller-ecommerce'); ?></span>
                                    </label>
                                </div>
                                <div class="col-auto form-group form-check">
                                    <label class="btn-radio-checkbox form-check-label ">
                                        <input type="radio" class="form-check-input" name="gender" value="F"> <span><?php echo __('Mrs.', 'propeller-ecommerce'); ?></span>
                                    </label>
                                </div>
                                <div class="col-auto form-group form-check">
                                    <label class="btn-radio-checkbox form-check-label ">
                                        <input type="radio" class="form-check-input" name="gender" value="U"> <span><?php echo __('Other', 'propeller-ecommerce'); ?></span>
                                    </label>
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-form-fields col-12">
                            <div class="form-row">
                                <div class="col form-group col-user-firstname">
                                    <label class="form-label" for="field_fname"><?php echo __('First name', 'propeller-ecommerce'); ?>*</label>
                                    <input type="text" name="firstName" value="" class="form-control required" id="field_fname">
                                </div>
                                <div class="col form-group col-user-middlename">
                                    <label class="form-label" for="field_mname"><?php echo __('Insertion (optional)', 'propeller-ecommerce'); ?></label>
                                    <input type="text" name="middleName" value="" class="form-control" id="field_mname">
                                </div>
                                <div class="col form-group col-user-lastname">
                                    <label class="form-label" for="field_lname"><?php echo __('Last name', 'propeller-ecommerce'); ?>*</label>
                                    <input type="text" name="lastName" value="" class="form-control required" id="field_lname">
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-form-fields col-12">
                            <div class="form-row">
                                <div class="col-12 col-md-8 form-group col-user-mail">
                                    <label class="form-label" for="field_email"><?php echo __('E-mail address', 'propeller-ecommerce'); ?>*</label>
                                    <input type="email" name="email" value="" class="form-control required email" id="field_email">
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-form-fields col-12">
                            <div class="form-row">
                                <div class="col-12 col-md-8 form-group col-user-phone">
                                    <label class="form-label" for="field_phone"><?php echo __('Phone number', 'propeller-ecommerce'); ?>*</label>
                                    <input type="text" name="phone" value="" class="form-control required" id="field_phone">
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-form-fields col-12">
                            <div class="form-row">
                                <div class="col-12 col-md-8 form-group col-user-taxnr">
                                    <label class="form-label" for="company_name"><?php echo __('Company name', 'propeller-ecommerce'); ?>*</label>
                                    <input type="text" name="company_name" value="" class="form-control required" id="company_name">
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-form-fields col-12">
                            <div class="form-row">
                                <div class="col-12 col-md-8 form-group col-user-taxnr">
                                    <label class="form-label" for="field_taxnr"><?php echo __('VAT number', 'propeller-ecommerce'); ?>*</label>
                                    <input type="text" name="taxNumber" value="" class="form-control required" id="field_taxnr">
                                </div>
                            </div>  
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <legend class="checkout-header">
                        <?php echo __('Address', 'propeller-ecommerce'); ?>
                    </legend>
                    <input type="hidden" name="invoice_address[type]" value="<?php echo AddressType::INVOICE; ?>">
                    <div class="row form-group">
                        <div class="col-form-fields col-12">
                            <div class="form-row">
                                <div class="col-12 col-md-8 form-group col-user-company">
                                    <label class="form-label" for="field_company"><?php echo __('Company name', 'propeller-ecommerce'); ?>*</label>
                                    <input type="text" name="invoice_address[company]" value="" class="form-control required" id="field_company">
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-form-fields col-12">
                            <div class="form-row">
                                <div class="col-12 col-md-8 form-group col-user-address">
                                    <label class="form-label" for="field_address"><?php echo __('Street', 'propeller-ecommerce'); ?>*</label>
                                    <input type="text" name="invoice_address[street]" value="" class="form-control required" id="field_address">
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-form-fields col-12">
                            <div class="form-row">
                                <div class="col-12 col-md-8 form-group col-user-address_number">
                                    <label class="form-label" for="field_invoice_address_number"><?php echo __('Number', 'propeller-ecommerce'); ?>*</label>
                                    <input type="number" ondrop="return false;" onpaste="return false;" onkeypress="return event.charCode>=48 && event.charCode<=57"name="invoice_address[number]" value="" class="form-control required" id="field_invoice_address_number">
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-form-fields col-12">
                            <div class="form-row">
                                <div class="col-12 col-md-8 form-group col-user-address_add">
                                    <label class="form-label" for="field_address_add"><?php echo __('Address addition (building, unit, etc, optional)', 'propeller-ecommerce'); ?></label>
                                    <input type="text" name="invoice_address[numberExtension]" value="" class="form-control" id="field_address_add">
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-form-fields col-12">
                            <div class="form-row">
                                <div class="col-12 col-md-8 form-group col-user-zipcode">
                                    <label class="form-label" for="field_zipcode"><?php echo __('Postal code', 'propeller-ecommerce'); ?>*</label>
                                    <input type="text" name="invoice_address[postalCode]" value="" class="form-control required" id="field_zipcode">
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-form-fields col-12">
                            <div class="form-row">
                                <div class="col-12 col-md-8 form-group col-user-city">
                                    <label class="form-label" for="field_city"><?php echo __('City', 'propeller-ecommerce'); ?>*</label>
                                    <input type="text" name="invoice_address[city]" value="" class="form-control required" id="field_city">
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-form-fields col-12">
                            <div class="form-row">
                                <div class="col-12 col-md-8 form-group col-user-country">
                                    <label class="form-label" for="field_country"><?php echo __('Country', 'propeller-ecommerce'); ?>*</label>

                                    <?php 
                                        $countries = include PROPELLER_PLUGIN_DIR . '/includes/Countries.php'; 
                                        $selected = 'NL';

                                        if (isset($address->country) && !empty($address->country))
                                            $selected = $address->country;
                                    ?>

                                    <select id="field_country" name="invoice_address[country]" class="form-control required">
                                        <?php foreach ($countries as $code => $name) { ?>
                                            <option value="<?php echo esc_attr($code); ?>" <?php echo ($code == $selected ? 'selected' : ''); ?>><?php echo esc_html($name); ?></option>
                                        <?php } ?>
                                    </select>



                                    <!-- <input type="text" name="invoice_address[country]" value="" class="form-control required" id="field_country"> -->
                                </div>
                            </div>  
                        </div>
                    </div>                    
                    <div class="row form-group">
                        <div class="col-form-fields col-12">
                            <div class="form-row">
                                <div class="col-12 col-md-8 form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="save_delivery_address" value="Y" checked="checked" title="Save this as delivery address">
                                        <span><?php echo __('Delivery address is the same as billing address', 'propeller-ecommerce'); ?></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="new-delivery-address">
                    <legend class="checkout-header">
                        <?php echo __('Delivery address', 'propeller-ecommerce'); ?> 
                    </legend>
                    <input type="hidden" name="delivery_address[type]" value="<?php echo AddressType::DELIVERY; ?>">
                    <div class="row form-group">
                        <div class="col-form-fields col-12">
                            <div class="form-row">
                                <div class="col-12 col-md-8 form-group col-user-company">
                                    <label class="form-label" for="field_delivery_company"><?php echo __('Company name', 'propeller-ecommerce'); ?>*</label>
                                    <input type="text" name="delivery_address[company]" value="" class="form-control required" id="field_delivery_company">
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-form-fields col-12">
                            <div class="form-row">
                                <div class="col-12 col-md-8 form-group col-user-address">
                                    <label class="form-label" for="field_delivery_address"><?php echo __('Street', 'propeller-ecommerce'); ?>*</label>
                                    <input type="text" name="delivery_address[street]" value="" class="form-control required" id="field_delivery_address">
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-form-fields col-12">
                            <div class="form-row">
                                <div class="col-12 col-md-8 form-group col-user-address_number">
                                    <label class="form-label" for="field_delivery_address_number"><?php echo __('Number', 'propeller-ecommerce'); ?></label>
                                    <input type="text" name="delivery_address[number]" value="" class="form-control" id="field_delivery_address_number">
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-form-fields col-12">
                            <div class="form-row">
                                <div class="col-12 col-md-8 form-group col-user-address_add">
                                    <label class="form-label" for="field_delivery_address_add"><?php echo __('Address addition (building, unit, etc, optional)', 'propeller-ecommerce'); ?></label>
                                    <input type="text" name="delivery_address[numberExtension]" value="" class="form-control" id="field_delivery_address_add">
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-form-fields col-12">
                            <div class="form-row">
                                <div class="col-12 col-md-8 form-group col-user-zipcode">
                                    <label class="form-label" for="field_delivery_zipcode"><?php echo __('Postal code', 'propeller-ecommerce'); ?>*</label>
                                    <input type="text" name="delivery_address[postalCode]" value="" class="form-control required" id="field_delivery_zipcode">
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-form-fields col-12">
                            <div class="form-row">
                                <div class="col-12 col-md-8 form-group col-user-city">
                                    <label class="form-label" for="field_delivery_city"><?php echo __('City', 'propeller-ecommerce'); ?>*</label>
                                    <input type="text" name="delivery_address[city]" value="" class="form-control required" id="field_delivery_city">
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-form-fields col-12">
                            <div class="form-row">
                                <div class="col-12 col-md-8 form-group col-user-country">
                                    <label class="form-label" for="field_delivery_country"><?php echo __('Country', 'propeller-ecommerce'); ?>*</label>
                                    
                                    <?php 
                                        $countries = include PROPELLER_PLUGIN_DIR . '/includes/Countries.php'; 
                                        $selected = 'NL';

                                        if (isset($address->country) && !empty($address->country))
                                            $selected = $address->country;
                                    ?>

                                    <select id="field_delivery_country" name="delivery_address[country]" class="form-control required">
                                        <?php foreach ($countries as $code => $name) { ?>
                                            <option value="<?php echo esc_attr($code); ?>" <?php echo ($code == $selected ? 'selected' : ''); ?>><?php echo esc_html($name); ?></option>
                                        <?php } ?>
                                    </select>

                                    <!-- <input type="text" name="delivery_address[country]" value="" class="form-control required" id="field_delivery_country"> -->
                                </div>
                            </div>  
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <legend class="checkout-header">
                    <?php echo __('Your password', ''); ?>
                    </legend>
                    <div class="row form-group">
                        <div class="col-form-fields col-12">
                            <div class="form-row">
                                <div class="col-12 col-md-8 form-group col-user-password">
                                    <label class="form-label" for="field_password"><?php echo __('Password', 'propeller-ecommerce'); ?>*</label>
                                    <input type="password" name="password" value="" class="form-control required" id="field_password" minlength="8">
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-form-fields col-12">
                            <div class="form-row">
                                <div class="col-12 col-md-8 form-group col-user-password">
                                    <label class="form-label" for="field_password_verify"><?php echo __('Repeat password', 'propeller-ecommerce'); ?>*</label>
                                    <input type="password" name="password_verfification" value="" class="form-control required" id="field_password_verify">
                                </div>
                            </div>  
                        </div>
                    </div>
                </fieldset>
                <div class="row form-group form-group-submit">
                    <div class="col-form-fields col-12">
                        <div class="form-row">
                            <div class="col-12 col-md-8">
                                <input type="submit" class="btn-green btn-proceed" value="<?php echo __('Send', 'propeller-ecommerce'); ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div> 
</div>