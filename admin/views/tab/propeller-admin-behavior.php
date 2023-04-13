<form method="POST" class="propel-admin-form p-3 border rounded-lg" action="#" id="propel_behavior_form">
    <input type="hidden" id="setting_id" name="setting_id" value="<?php echo isset($behavior_result->id) ? esc_attr($behavior_result->id) : 0; ?>">
    <input type="hidden" name="action" value="save_propel_behavior">
    
    <div class="form-row">
        <div class="form-group col-md-6">
            <input type="checkbox" class="border form-control" id="wordpress_session" name="wordpress_session" value="true" <?php echo isset($behavior_result->wordpress_session) && $behavior_result->wordpress_session == 1 ? 'checked' : ''; ?>>
            <label class="text-secondary" for="wordpress_session"><?php echo __('Enable Wordpress sessions', 'propeller-ecommerce'); ?></label>    
        </div>
        <div class="form-group col-md-6 justify-content-center align-self-center">
            <small class="text-warning"><?php echo __('Propeller and Wordpress sessions will be combined', 'propeller-ecommerce'); ?></small>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <input type="checkbox" class="border form-control" id="closed_portal" name="closed_portal" value="true" <?php echo isset($behavior_result->closed_portal) && $behavior_result->closed_portal == 1 ? 'checked' : ''; ?>>
            <label class="text-secondary" for="closed_portal"><?php echo __('Is closed portal?', 'propeller-ecommerce'); ?></label><br />
        </div>
        <div class="form-group col-md-6 justify-content-center align-self-center">
            <small class="text-warning"><?php echo __('If checked, anonymous users will land on a login page', 'propeller-ecommerce'); ?></small>
        </div>

        <div id="exclusions_container" class="form-group col" style="display: <?php echo intval($behavior_result->closed_portal) == 1 ? 'block': 'none'; ?>">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label class="text-secondary"><?php echo __('Select pages for exclusion', 'propeller-ecommerce'); ?></label>
                    <br />
                    <select multiple name="exclusions" id="exclusions" size="10" class="border form-control">
                    <?php
                        $exclusions = explode(',', $behavior_result->excluded_pages);

                        if ($pages = get_pages([])) {
                            foreach ($pages as $page) {
                        ?>
                            <option value="<?php echo esc_attr($page->ID); ?>" <?php echo (in_array($page->ID, $exclusions) ? 'selected' : ''); ?>><?php echo esc_html($page->post_title); ?></option>
                        <?php
                        } }
                    ?>
                    </select>
                </div>
                <div class="form-group col-md-6 justify-content-center align-self-center">
                    <small class="text-warning"><?php echo __('Selected pages will be available even if the user is not logged in a closed webshop', 'propeller-ecommerce'); ?></small>
                    <input type="hidden" id="excluded_pages" name="excluded_pages" value="<?php echo esc_attr($behavior_result->excluded_pages); ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <input type="checkbox" class="border form-control" id="semiclosed_portal" name="semiclosed_portal" value="true" <?php echo isset($behavior_result->semiclosed_portal) && $behavior_result->semiclosed_portal == 1 ? 'checked' : ''; ?>>
            <label class="text-secondary" for="semiclosed_portal"><?php echo __('Is semi-closed portal?', 'propeller-ecommerce'); ?></label><br />
        </div>
        <div class="form-group col-md-6 justify-content-center align-self-center">
            <small class="text-warning"><?php echo __('If checked, anonymous users will not be able to add products to cart, see prices or stock, etc.', 'propeller-ecommerce'); ?></small>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <input type="checkbox" class="border form-control" id="use_recaptcha" name="use_recaptcha" value="true" <?php echo isset($behavior_result->use_recaptcha) && intval($behavior_result->use_recaptcha) == 1 ? 'checked' : ''; ?>>
            <label class="text-secondary" for="use_recaptcha"><?php echo __('Use reCaptcha', 'propeller-ecommerce'); ?></label><br />
        </div>
        <div class="form-group col-md-6 justify-content-center align-self-center">
            <small class="text-warning"><?php echo __('Use Google reCaptcha v3 in login and registration forms', 'propeller-ecommerce'); ?></small>
        </div>

        <div id="recaptcha_settings" class="form-group col" style="display: <?php echo intval($behavior_result->use_recaptcha) == 1 ? 'block': 'none'; ?>">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label class="text-secondary" for="recaptcha_site_key"><?php echo __('Site key:', 'propeller-ecommerce'); ?></label>
                    <input type="text" class="border form-control" id="recaptcha_site_key" placeholder="reCaptcha Site key" name="recaptcha_site_key" value="<?php echo isset($behavior_result->recaptcha_site_key) ? esc_attr($behavior_result->recaptcha_site_key) : ''; ?>">
                </div>

                <div class="form-group col-md-4">
                    <label class="text-secondary" for="recaptcha_secret_key"><?php echo __('Secret key:', 'propeller-ecommerce'); ?></label>
                    <input type="text" class="border form-control" id="recaptcha_secret_key" placeholder="reCaptcha secret key" name="recaptcha_secret_key" value="<?php echo isset($behavior_result->recaptcha_secret_key) ? esc_attr($behavior_result->recaptcha_secret_key) : ''; ?>">
                </div>

                <div class="form-group col-md-4">
                    <label class="text-secondary" for="recaptcha_min_score"><?php echo __('Minimal valid score:', 'propeller-ecommerce'); ?></label>
                    <input type="text" class="border form-control" id="recaptcha_min_score" placeholder="reCaptcha minimal valid score" name="recaptcha_min_score" value="<?php echo isset($behavior_result->recaptcha_min_score) ? esc_attr($behavior_result->recaptcha_min_score) : ''; ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <input type="checkbox" class="border form-control" id="register_auto_login" name="register_auto_login" value="true" <?php echo isset($behavior_result->register_auto_login) && intval($behavior_result->register_auto_login) == 1 ? 'checked' : ''; ?>>
            <label class="text-secondary" for="register_auto_login"><?php echo __('Automatic log in after registration', 'propeller-ecommerce'); ?></label><br />
        </div>
        <div class="form-group col-md-6 justify-content-center align-self-center">
            <small class="text-warning"><?php echo __('Will log in newly registered users automatically', 'propeller-ecommerce'); ?></small>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label class="text-secondary" for="track_user_attr"><?php echo __('Track attribute for users:', 'propeller-ecommerce'); ?></label>
            <input type="text" class="border form-control" id="track_user_attr" placeholder="<?php echo __('Comma separated attribute names', 'propeller-ecommerce'); ?>" name="track_user_attr" value="<?php echo isset($behavior_result->track_user_attr) ? esc_attr($behavior_result->track_user_attr) : ''; ?>">
        </div>
        <div class="form-group col-md-6 justify-content-center align-self-center">
            <small class="text-warning"><?php echo __('Personalized content will be displayed for users based on this attribute value', 'propeller-ecommerce'); ?></small>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label class="text-secondary" for="track_product_attr"><?php echo __('Track attributes for products:', 'propeller-ecommerce'); ?></label>
            <input type="text" class="border form-control" id="track_product_attr" placeholder="<?php echo __('Comma separated attribute names', 'propeller-ecommerce'); ?>" name="track_product_attr" value="<?php echo isset($behavior_result->track_product_attr) ? esc_attr($behavior_result->track_product_attr) : ''; ?>">
        </div>
        <div class="form-group col-md-6 justify-content-center align-self-center">
            <small class="text-warning"><?php echo __('Additional info for products based on these attribute values', 'propeller-ecommerce'); ?></small>
        </div>
    </div>

    <div class="form-row">
        <?php $assets_type = isset($behavior_result->assets_type) ? intval($behavior_result->assets_type) : 1; ?>
        <div class="form-group col-md-6">
            <label class="text-secondary" for="assets_type"><?php echo __('Assets enqueuing type', 'propeller-ecommerce'); ?></label><br />
            <select class="border form-control" id="assets_type" name="assets_type">
                <option <?php selected(1, $assets_type); ?> value="1"><?php _e('Standard - Enqueue assets without modification/combination, let performance plugins handle this.'); ?></option>
                <option <?php selected(2, $assets_type); ?> value="2"><?php _e('Global Combined/Minified - Include all assets globally, this method has performance implications.'); ?></option>
            </select>
        </div>
        <div class="form-group col-md-6 justify-content-center align-self-center">
            <small class="text-warning"><?php echo __('If you choose Standard, the assets will be enqueued only where used. To minify/combine them you will need to use plugin like Autooptimize. If you choose Global/Minified then all the assets will be combined, minified and included globally even when unnecessary, therefore this has performance implications.', 'propeller-ecommerce'); ?></small>
        </div>

        <div class="form-group col-md-6">
            <input type="checkbox" class="border form-control" id="ids_in_urls" name="ids_in_urls" value="true" <?php echo isset($behavior_result->ids_in_urls) && intval($behavior_result->ids_in_urls) == 1 ? 'checked' : ''; ?>>
            <label class="text-secondary" for="ids_in_urls"><?php echo __('Add product/category ID in URL', 'propeller-ecommerce'); ?></label><br />
            <small class="text-warning"><?php echo __('(Will alter the URL behavior for products and categories by attaching the ID of the object in the URL)', 'propeller-ecommerce'); ?></small>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <input type="checkbox" class="border form-control" id="stock_check" name="stock_check" value="true" <?php echo isset($behavior_result->stock_check) && intval($behavior_result->stock_check) == 1 ? 'checked' : ''; ?>>
            <label class="text-secondary" for="stock_check"><?php echo __('Check stock?', 'propeller-ecommerce'); ?></label>
        </div>
        <div class="form-group col-md-6 justify-content-center align-self-center">
            <small class="text-warning"><?php echo __('Display a popup with guidance if ordered quantity is greater than the stock quantity', 'propeller-ecommerce'); ?></small>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <input type="checkbox" class="border form-control" id="load_specifications" name="load_specifications" value="true" <?php echo isset($behavior_result->load_specifications) && intval($behavior_result->load_specifications) == 1 ? 'checked' : ''; ?>>
            <label class="text-secondary" for="load_specifications"><?php echo __('Auto load product specifications', 'propeller-ecommerce'); ?></label>
        </div>
        <div class="form-group col-md-6 justify-content-center align-self-center">
            <small class="text-warning"><?php echo __('Will load product specifications automatically in the background in PDP', 'propeller-ecommerce'); ?></small>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <input type="checkbox" class="border form-control" id="partial_delivery" name="partial_delivery" value="true" <?php echo isset($behavior_result->partial_delivery) && intval($behavior_result->partial_delivery) == 1 ? 'checked' : ''; ?>>
            <label class="text-secondary" for="partial_delivery"><?php echo __('Use partial delivery', 'propeller-ecommerce'); ?></label>
        </div>
        <div class="form-group col-md-6 justify-content-center align-self-center">
            <small class="text-warning"><?php echo __('Will add the option to use partial delivery in the checkout process', 'propeller-ecommerce'); ?></small>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <input type="checkbox" class="border form-control" id="selectable_carriers" name="selectable_carriers" value="true" <?php echo isset($behavior_result->selectable_carriers) && intval($behavior_result->selectable_carriers) == 1 ? 'checked' : ''; ?>>
            <label class="text-secondary" for="selectable_carriers"><?php echo __('Selectable carriers', 'propeller-ecommerce'); ?></label>
        </div>
        <div class="form-group col-md-6 justify-content-center align-self-center">
            <small class="text-warning"><?php echo __('Add the option to select a carrier in the checkout process', 'propeller-ecommerce'); ?></small>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <input type="checkbox" class="border form-control" id="use_datepicker" name="use_datepicker" value="true" <?php echo isset($behavior_result->use_datepicker) && intval($behavior_result->use_datepicker) == 1 ? 'checked' : ''; ?>>
            <label class="text-secondary" for="use_datepicker"><?php echo __('Use datepicker', 'propeller-ecommerce'); ?></label>
        </div>
        <div class="form-group col-md-6 justify-content-center align-self-center">
            <small class="text-warning"><?php echo __('Show datepicker in the checkout process for chosing a desired delivery date', 'propeller-ecommerce'); ?></small>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col text-center">
            <button type="submit" id="submit-key" class="integration-form-btn btn btn-success"><?php echo __('Save behavior', 'propeller-ecommerce'); ?></button>
        </div>
    </div>
</form>