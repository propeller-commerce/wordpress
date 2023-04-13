<form method="POST" class="propel-admin-form p-3 border rounded-lg" action="#" id="propel_settings_form">
    <input type="hidden" id="setting_id" name="setting_id" value="<?php echo isset($settings_result->id) ? esc_attr($settings_result->id) : 0; ?>">
    <input type="hidden" name="action" value="save_propel_settings">
            
    <div class="form-group">
        <label class="text-secondary" for="api_url"><?php echo __('API URL', 'propeller-ecommerce'); ?></label>
        <input type="text" class="border form-control" id="api_url" name="api_url" value="<?php echo isset($settings_result->api_url) ? esc_url($settings_result->api_url) : ''; ?>" required>
    </div>
    <div class="form-group">
        <label class="text-secondary" for="api_key"><?php echo __('API key', 'propeller-ecommerce'); ?></label>
        <input type="text" class="border form-control" id="api_key" name="api_key" value="<?php echo isset($settings_result->api_key) ? esc_attr($settings_result->api_key) : ''; ?>" required>
    </div> 
    <div class="form-row">
        <div class="form-group col-md-4">
            <label class="text-secondary" for="anonymous_user"><?php echo __('Anonymous user ID', 'propeller-ecommerce'); ?></label>
            <input type="text" class="border form-control" id="anonymous_user" name="anonymous_user" value="<?php echo isset($settings_result->anonymous_user) ? esc_attr($settings_result->anonymous_user) : ''; ?>" required>
        </div>
        <div class="form-group col-md-4">
            <label class="text-secondary" for="catalog_root"><?php echo __('Catalog root ID', 'propeller-ecommerce'); ?></label>
            <input type="text" class="border form-control" id="catalog_root" name="catalog_root" value="<?php echo isset($settings_result->catalog_root) ? esc_attr($settings_result->catalog_root) : ''; ?>" required>
        </div>
        <div class="form-group col-md-4">
            <label class="text-secondary" for="site_id"><?php echo __('Site ID', 'propeller-ecommerce'); ?></label>
            <input type="text" class="border form-control" id="site_id" name="site_id" value="<?php echo isset($settings_result->site_id) ? esc_attr($settings_result->site_id) : ''; ?>" required>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-4">
            <label class="text-secondary" for="contact_root"><?php echo __('Contacts root ID', 'propeller-ecommerce'); ?></label>
            <input type="text" class="border form-control" id="contact_root" name="contact_root" value="<?php echo isset($settings_result->contact_root) ? esc_attr($settings_result->contact_root) : ''; ?>" required>
        </div>
        <div class="form-group col-md-4">
            <label class="text-secondary" for="customer_root"><?php echo __('Customers root ID', 'propeller-ecommerce'); ?></label>
            <input type="text" class="border form-control" id="customer_root" name="customer_root" value="<?php echo isset($settings_result->customer_root) ? esc_attr($settings_result->customer_root) : ''; ?>" required>
        </div>
        <div class="form-group col-md-4">
            <label class="text-secondary" for="default_locale"><?php echo __('Default language', 'propeller-ecommerce'); ?></label>

            <select name="default_locale" id="default_locale" class="form-control">
                <option value=""><?php echo __('Select language', 'propeller-ecommerce'); ?></option>
                <?php $locales = include PROPELLER_PLUGIN_DIR . '/includes/Locales.php'; ?>
                <?php foreach ($locales as $loc => $locale) { ?>
                    <option value="<?php echo esc_attr($locale['wp_locale']); ?>" <?php echo (bool) ($settings_result->default_locale == $locale['wp_locale']) ? 'selected="selected"' : ''; ?>><?php echo esc_html($locale['name']) . ' (' . esc_html($locale['code']) . ')'; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label class="text-secondary" for="cc_email"><?php echo __('CC Email', 'propeller-ecommerce'); ?></label>
            <input type="text" class="border form-control" id="cc_email" name="cc_email" value="<?php echo isset($settings_result->cc_email) ? esc_attr($settings_result->cc_email) : ''; ?>">
        </div>
        <div class="form-group col-md-6">
            <label class="text-secondary" for="bcc_email"><?php echo __('BCC Email', 'propeller-ecommerce'); ?></label>
            <input type="text" class="border form-control" id="bcc_email" name="bcc_email" value="<?php echo isset($settings_result->bcc_email) ? esc_attr($settings_result->bcc_email) : ''; ?>">
        </div>
    </div>

    <div class="row">
        <div class="col text-center">
            <button type="submit" id="submit-key" class="integration-form-btn btn btn-success">Save settings</button>
        </div>
    </div>
</form>