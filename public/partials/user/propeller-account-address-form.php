<form name="edit-address-form" id="edit_address<?= $address->id; ?>" class="form-horizontal validate form-handler modal-edit-form" method="post">
    <?php if (is_numeric($address->id)) { ?>
        <input type="hidden" name="id" value="<?= $address->id; ?>">
    <?php } ?>

    <input type="hidden" name="type" value="<?= $address->type; ?>">
    <input type="hidden" name="action" value="<?php echo is_numeric($address->id) ? 'update_address' : 'add_address' ?>">
    
    <fieldset class="personal">
        <div class="row form-group">
            <div class="col-form-fields col-12">
                <div class="form-row">
                    <div class="col-12 form-group col-user-company">
                        <label class="form-label" for="company_<?= $address->id; ?>"><?php echo __('Company name', 'propeller-ecommerce'); ?></label>
                        <input type="text" required name="company" placeholder="<?php echo __('Company name', 'propeller-ecommerce'); ?>" value="<?= $address->company; ?>" class="form-control required" id="company_<?= $address->id; ?>" aria-describedby="val_company_<?= $address->id; ?>">
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
                    <div class="col-12 col-md form-group col-user-firstname">
                        <label class="form-label" for="firstName_<?= $address->id; ?>"><?php echo __('First name', 'propeller-ecommerce'); ?>*</label>
                        <input type="text" name="firstName" value="<?= $address->firstName; ?>" placeholder="<?php echo __('First name', 'propeller-ecommerce'); ?>*" class="form-control required" id="firstName_<?= $address->id; ?>">
                    </div>
                    <div class="col-12 col-md form-group col-user-middlename">
                        <label class="form-label" for="middleName_<?= $address->id; ?>"><?php echo __('Insertion (optional)', 'propeller-ecommerce'); ?></label>
                        <input type="text" name="middleName" value="<?= $address->middleName; ?>" placeholder="<?php echo __('Insertion (optional)', 'propeller-ecommerce'); ?>" class="form-control" id="middleName_<?= $address->id; ?>">
                    </div>
                    <div class="col-12 col-md form-group col-user-lastname">
                        <label class="form-label" for="lastName_<?= $address->id; ?>"><?php echo __('Last name', 'propeller-ecommerce'); ?>*</label>
                        <input type="text" name="lastName" value="<?= $address->lastName; ?>" placeholder="<?php echo __('Last name', 'propeller-ecommerce'); ?>*" class="form-control required" id="lastName_<?= $address->id; ?>">
                    </div>
                </div>  
            </div>
        </div>
        <div class="row form-group">
            <div class="col-form-fields col-12">
                <div class="form-row">
                    <div class="col-12 form-group col-user-address">
                        <label class="form-label" for="email_<?= $address->id; ?>"><?php echo __('E-mail', 'propeller-ecommerce'); ?>*</label>
                        <input type="text" name="email" value="<?= $address->email; ?>" placeholder="<?php echo __('E-mail', 'propeller-ecommerce'); ?>*" class="form-control required" id="email_<?= $address->id; ?>">
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
                        <label class="form-label" for="street_<?= $address->id; ?>"><?php echo __('Street', 'propeller-ecommerce'); ?>*</label>
                        <input type="text" name="street" value="<?= $address->street; ?>" placeholder="<?php echo __('Street', 'propeller-ecommerce'); ?>*" class="form-control required" id="street_<?= $address->id; ?>">
                    </div>
                    <div class="col-4 form-group col-user-street-number">
                        <label class="form-label" for="number_<?= $address->id; ?>"><?php echo __('Number', 'propeller-ecommerce'); ?>*</label>
                        <input type="text" name="number" value="<?= $address->number; ?>" placeholder="<?php echo __('Number', 'propeller-ecommerce'); ?>*" class="form-control required" id="number_<?= $address->id; ?>">
                    </div>
                </div>  
            </div>
        </div>
        <div class="row form-group">
            <div class="col-form-fields col-12">
                <div class="form-row">
                    <div class="col-12 form-group col-user-address_add">
                        <label class="form-label" for="number_<?= $address->id; ?>"><?php echo __('Address addition (building, unit, etc, optional)', 'propeller-ecommerce'); ?></label>
                        <input type="text" name="numberExtension" value="<?= $address->numberExtension; ?>" placeholder="<?php echo __('Address addition (building, unit, etc, optional)', 'propeller-ecommerce'); ?>" class="form-control" maxlength="7" id="numberExtension_<?= $address->id; ?>">
                    </div>
                </div>  
            </div>
        </div>
        <div class="row form-group">
            <div class="col-form-fields col-12">
                <div class="form-row">
                    <div class="col-12 form-group col-user-zipcode">
                        <label class="form-label" for="postalCode_<?= $address->id; ?>"><?php echo __('Postal code', 'propeller-ecommerce'); ?>*</label>
                        <input type="text" name="postalCode" value="<?= $address->postalCode; ?>" placeholder="<?php echo __('Postal code', 'propeller-ecommerce'); ?>*" class="form-control required" id="postalCode_<?= $address->id; ?>">
                    </div>
                </div>  
            </div>
        </div>
        <div class="row form-group">
            <div class="col-form-fields col-12">
                <div class="form-row">
                    <div class="col-12 form-group col-user-city">
                        <label class="form-label" for="city_<?= $address->id; ?>"><?php echo __('City', 'propeller-ecommerce'); ?>*</label>
                        <input type="text" name="city" value="<?= $address->city; ?>" placeholder="<?php echo __('City', 'propeller-ecommerce'); ?>*" class="form-control required" id="city_<?= $address->id; ?>">
                    </div>
                </div>  
            </div>
        </div>
        <div class="row form-group">
            <div class="col-form-fields col-12">
                <div class="form-row">
                    <div class="col-12 form-group col-user-country">
                        <label class="form-label" for="country_<?= $address->id; ?>"><?php echo __('Country', 'propeller-ecommerce'); ?>*</label>

                        <!-- <input type="text" name="country" value="<?= $address->country; ?>" class="form-control required" id="country_<?= $address->id; ?>"> -->

                        <?php 
                            $countries = include PROPELLER_PLUGIN_DIR . '/includes/Countries.php'; 
                            $selected = 'NL';

                            if (isset($address->country) && !empty($address->country))
                                $selected = $address->country;
                        ?>

                        <select id="country_<?= $address->id; ?>" name="country" class="form-control required">
                            <?php foreach ($countries as $code => $name) { ?>
                                <option value="<?= $code; ?>" <?php echo ($code == $selected ? 'selected' : ''); ?>><?php echo $name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>  
            </div>
        </div>
        <div class="row form-group">
            <div class="col-form-fields col-12">
                <div class="form-row">
                    <div class="col-12 form-group col-user-notes">
                        <label class="form-label" for="notes_<?= $address->id; ?>"><?php echo __('Remarks (optional)', 'propeller-ecommerce'); ?></label>
                        <textarea name="notes" value="<?= $address->notes; ?>" placeholder="<?php echo __('Remarks (optional)', 'propeller-ecommerce'); ?>" class="form-control" id="notes_<?= $address->id; ?>"></textarea>
                    </div>
                </div>  
            </div>
        </div>
        <div class="row form-group">
            <div class="col-form-fields col-12">
                <div class="form-row">
                    <div class="col-12 form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" name="save_delivery_address" value="Y" aria-required="true">
                            <?php if( $address->type =='delivery') { ?>
                                <span><?php echo __('Set as default delivery address', 'propeller-ecommerce'); ?></span>
                            <?php } else { ?> 
                                <span><?php echo __('Set as default billing address', 'propeller-ecommerce'); ?></span>
                            <?php } ?>
                            
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    <div class="row form-group form-group-submit propel-modal-foote">
        <div class="col-form-fields col-12">
            <div class="form-row">
                <div class="col-12">
                    <button type="submit" class="btn-modal btn-proceed w-100 justify-content-center btn-modal-address btn-modal-submit" id="submit_edit_address<?= $address->id; ?>"><?php echo __('Send', ''); ?></button>
                </div>
            </div>
        </div>
    </div>
</form>