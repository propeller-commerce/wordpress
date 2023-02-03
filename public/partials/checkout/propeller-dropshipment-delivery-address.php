<?php 
 use Propeller\Includes\Enum\AddressType;

 $rand = rand(1, 10000);
 $countries = include PROPELLER_PLUGIN_DIR . '/includes/Countries.php'; 
 
?>
<svg style="display:none">
    <symbol viewBox="0 0 14 14" id="shape-header-close"><title>Close</title> <path d="M13.656 12.212c.41.41.41 1.072 0 1.481a1.052 1.052 0 0 1-1.485 0L7 8.5l-5.207 5.193a1.052 1.052 0 0 1-1.485 0 1.045 1.045 0 0 1 0-1.481L5.517 7.02.307 1.788a1.045 1.045 0 0 1 0-1.481 1.052 1.052 0 0 1 1.485 0L7.001 5.54 12.208.348a1.052 1.052 0 0 1 1.485 0c.41.408.41 1.072 0 1.48L8.484 7.02l5.172 5.192z"/></symbol>
    <symbol viewBox="0 0 16 16" id="shape-error"><title>Error</title> <path d="M15.75 8A7.751 7.751 0 0 0 .25 8 7.75 7.75 0 0 0 8 15.75 7.75 7.75 0 0 0 15.75 8zM8 9.563a1.437 1.437 0 1 1 0 2.874 1.437 1.437 0 0 1 0-2.874zM6.635 4.395A.375.375 0 0 1 7.01 4h1.98c.215 0 .386.18.375.395l-.232 4.25A.375.375 0 0 1 8.759 9H7.24a.375.375 0 0 1-.374-.355l-.232-4.25z" fill="#E02B27"/></symbol>
    <symbol viewBox="0 0 16 12" id="shape-valid"><title>Valid</title><path d="m6.566 11.764 9.2-9.253a.808.808 0 0 0 0-1.137L14.634.236a.797.797 0 0 0-1.131 0L6 7.782 2.497 4.259a.797.797 0 0 0-1.131 0L.234 5.397a.808.808 0 0 0 0 1.137l5.2 5.23a.797.797 0 0 0 1.132 0z" fill="#54A023"/> </symbol>

</svg>
<div id="add_delivery_address_modal" class="propeller-address-modal modal fade modal-fullscreen-sm-down" tabindex="-1" role="dialog" aria-labelledby="propel_modal_edit_title_<?php echo esc_attr($rand); ?>">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header propel-modal-header">
                <div id="propel_modal_edit_title_<?php echo esc_attr($rand); ?>" class="modal-title">
                    <span><?php echo __('Add address', 'propeller-ecommerce');?></span>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        <svg class="icon icon-close">
                            <use class="header-shape-close" xlink:href="#shape-header-close"></use>
                        </svg>
                    </span>
                </button>
            </div>
            <div class="modal-body propel-modal-body" id="propel_modal_edit_body_<?php echo esc_attr($rand); ?>">
                <form name="add-delivery-address-form" id="add_delivery_address<?php echo esc_attr($rand); ?>" class="form-horizontal validate form-handler modal-edit-form dropshipment-form" method="post">
                    <?php
                        if ($this->cart->deliveryAddress->country != 'NL') 
                            echo '<input type="hidden" name="icp" value="Y">';
                        else 
                            echo '<input type="hidden" name="icp" value="N">';
                    ?>
                    <input type="hidden" name="type" value="<?php echo AddressType::DELIVERY; ?>">
                    <input type="hidden" name="action" value="cart_update_address">
                    <input type="hidden" name="subaction" value="cart_update_delivery_address">
                    
                    <fieldset class="personal">
                        <div class="row form-group">
                            <div class="col-form-fields col-12">
                                <div class="form-row">
                                    <div class="col-auto form-group form-check">
                                        <label class="btn-radio-checkbox form-check-label ">
                                            <input type="radio" class="form-check-input" name="gender" value="M" <?php if ($this->cart->deliveryAddress->gender == 'M') echo 'checked';?>> <span><?php echo __('Mr.', 'propeller-ecommerce'); ?></span>
                                        </label>
                                    </div>
                                    <div class="col-auto form-group form-check">
                                        <label class="btn-radio-checkbox form-check-label ">
                                            <input type="radio" class="form-check-input" name="gender" value="F" <?php if ($this->cart->deliveryAddress->gender == 'F') echo 'checked';?>> <span><?php echo __('Mrs.', 'propeller-ecommerce'); ?></span>
                                        </label>
                                    </div>
                                    <div class="col-auto form-group form-check">
                                        <label class="btn-radio-checkbox form-check-label ">
                                            <input type="radio" class="form-check-input" name="gender" value="U" <?php if ($this->cart->deliveryAddress->gender == 'U') echo 'checked';?>> <span><?php echo __('Other', 'propeller-ecommerce'); ?></span>
                                        </label>
                                    </div>
                                </div>  
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-form-fields col-12">
                                <div class="form-row">
                                    <div class="col-12 col-md-5 form-group col-user-firstname">
                                        <label class="form-label" for="firstName_<?php echo esc_attr($rand); ?>"><?php echo __('First name', 'propeller-ecommerce'); ?>*</label>
                                        <input type="text" name="firstName" value="" placeholder="<?php echo __('First name', 'propeller-ecommerce'); ?>*" class="form-control required" id="firstName_<?php echo esc_attr($rand); ?>">
                                    </div>
                                    <div class="col-12 col-md-3 form-group col-user-middlename">
                                        <label class="form-label" for="middleName_<?php echo esc_attr($rand); ?>"><?php echo __('Insertion', 'propeller-ecommerce'); ?></label>
                                        <input type="text" name="middleName" value="" placeholder="<?php echo __('Insertion (optional)', 'propeller-ecommerce'); ?>" class="form-control" id="middleName_<?php echo esc_attr($rand); ?>">
                                    </div>
                                    <div class="col-12 col-md-4 form-group col-user-lastname">
                                        <label class="form-label" for="lastName_<?php echo esc_attr($rand); ?>"><?php echo __('Last name', 'propeller-ecommerce'); ?>*</label>
                                        <input type="text" name="lastName" value="" placeholder="<?php echo __('Last name', 'propeller-ecommerce'); ?>*" class="form-control required" id="lastName_<?php echo esc_attr($rand); ?>">
                                    </div>
                                </div>  
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>                      
                        <div class="row form-group">
                            <div class="col-form-fields col-12">
                                <div class="form-row">
                                    <div class="col-8 form-group col-user-street">
                                        <label class="form-label" for="street_<?php echo esc_attr($rand); ?>"><?php echo __('Street', 'propeller-ecommerce'); ?>*</label>
                                        <input type="text" name="street" value="" placeholder="<?php echo __('Street', 'propeller-ecommerce'); ?>*" class="form-control required" id="street_<?php echo esc_attr($rand); ?>">
                                    </div>
                                    <div class="col-4 form-group col-user-street-number">
                                        <label class="form-label" for="number_<?php echo esc_attr($rand); ?>"><?php echo __('Number', 'propeller-ecommerce'); ?>*</label>
                                        <input type="text" name="number" value="" placeholder="<?php echo __('Number', 'propeller-ecommerce'); ?>*" class="form-control required" id="number_<?php echo esc_attr($rand); ?>">
                                    </div>
                                </div>  
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-form-fields col-12">
                                <div class="form-row">
                                    <div class="col-12 form-group col-user-address_add">
                                        <label class="form-label" for="number_<?php echo esc_attr($rand); ?>"><?php echo __('Address addition (building, unit, etc, optional)', 'propeller-ecommerce'); ?></label>
                                        <input type="text" name="numberExtension" value="" placeholder="<?php echo __('Address addition (building, unit, etc, optional)', 'propeller-ecommerce'); ?>" class="form-control" maxlength="7" id="numberExtension_<?php echo esc_attr($rand); ?>">
                                    </div>
                                </div>  
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-form-fields col-12">
                                <div class="form-row">
                                    <div class="col-12 form-group col-user-zipcode">
                                        <label class="form-label" for="code_<?php echo esc_attr($rand); ?>"><?php echo __('Postal code', 'propeller-ecommerce'); ?>*</label>
                                        <input type="text" name="postalCode" value="" placeholder="<?php echo __('Postal code', 'propeller-ecommerce'); ?>*" class="form-control required" id="code_<?php echo esc_attr($rand); ?>">
                                    </div>
                                </div>  
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-form-fields col-12">
                                <div class="form-row">
                                    <div class="col-12 form-group col-user-city">
                                        <label class="form-label" for="city_<?php echo esc_attr($rand); ?>"><?php echo __('City', 'propeller-ecommerce'); ?>*</label>
                                        <input type="text" name="city" value="" placeholder="<?php echo __('City', 'propeller-ecommerce'); ?>*" class="form-control required" id="city_<?php echo esc_attr($rand); ?>">
                                    </div>
                                </div>  
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-form-fields col-12">
                                <div class="form-row">
                                    <div class="col-12 form-group col-user-country">
                                        <label class="form-label" for="country_<?php echo esc_attr($rand); ?>"><?php echo __('Country', 'propeller-ecommerce'); ?>*</label>

                                        <?php 
                                            $selected = 'NL';
                                        ?>

                                        <select id="country_<?php echo esc_attr($rand); ?>" name="country" class="form-control required">
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
                                    <div class="col-12 form-group col-user-address">
                                        <label class="form-label" for="email_<?php echo esc_attr($rand); ?>"><?php echo __('E-mail', 'propeller-ecommerce'); ?>*</label>
                                        <input type="email" name="email" value="" placeholder="<?php echo __('E-mail', 'propeller-ecommerce'); ?>*" class="form-control required" id="email_<?php echo esc_attr($rand); ?>">
                                    </div>
                                </div>  
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-form-fields col-12">
                                <div class="form-row">
                                    <div class="col-12 form-group col-user-address">
                                        <label class="form-label" for="telephone_<?php echo esc_attr($rand); ?>"><?php echo __('Phone number', 'propeller-ecommerce'); ?>*</label>
                                        <input type="text" name="phone" value="" placeholder="<?php echo __('Phone number', 'propeller-ecommerce'); ?>*" class="form-control required" id="telephone_<?php echo esc_attr($rand); ?>">
                                    </div>
                                </div>  
                            </div>
                        </div>
                    </fieldset>
                    <div class="row form-group form-group-submit propel-modal-foote">
                        <div class="col-form-fields col-12">
                            <div class="form-row">
                                <div class="col-auto">
                                    <button type="button" class="btn-modal btn-cancel" data-dismiss="modal"><?php echo __('Cancel', 'propeller-ecommerce'); ?></button>
                                </div>
                                <div class="col d-flex justify-content-end">
                                    <button type="submit" class="btn-modal btn-proceed btn-modal-address btn-modal-submit" id="submit_edit_address<?php echo esc_attr($rand); ?>"><?php echo __('Add address', 'propeller-ecommerce'); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>   
</div>