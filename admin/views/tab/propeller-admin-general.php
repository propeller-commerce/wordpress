<form method="POST" class="propel-admin-form p-3 border rounded-lg" action="#" id="propel_settings_form">
    <input type="hidden" id="setting_id" name="setting_id" value="<?php echo isset($settings_result->id) ? $settings_result->id : 0; ?>">
    <input type="hidden" name="action" value="save_propel_settings">
            
    <div class="form-group">
        <label class="text-secondary" for="api_url"><?= __('API URL', 'propeller-ecommerce'); ?></label>
        <input type="text" class="border form-control" id="api_url" name="api_url" value="<?php echo isset($settings_result->api_url) ? $settings_result->api_url : ''; ?>" required>
    </div>
    <div class="form-group">
        <label class="text-secondary" for="api_key"><?= __('API key', 'propeller-ecommerce'); ?></label>
        <input type="text" class="border form-control" id="api_key" name="api_key" value="<?php echo isset($settings_result->api_key) ? $settings_result->api_key : ''; ?>" required>
    </div> 
    <div class="form-row">
        <div class="form-group col-md-4">
            <label class="text-secondary" for="anonymous_user"><?= __('Anonymous user ID', 'propeller-ecommerce'); ?></label>
            <input type="text" class="border form-control" id="anonymous_user" name="anonymous_user" value="<?php echo isset($settings_result->anonymous_user) ? $settings_result->anonymous_user : ''; ?>" required>
        </div>
        <div class="form-group col-md-4">
            <label class="text-secondary" for="catalog_root"><?= __('Catalog root ID', 'propeller-ecommerce'); ?></label>
            <input type="text" class="border form-control" id="catalog_root" name="catalog_root" value="<?php echo isset($settings_result->catalog_root) ? $settings_result->catalog_root : ''; ?>" required>
        </div>
        <div class="form-group col-md-4">
            <label class="text-secondary" for="site_id"><?= __('Site ID', 'propeller-ecommerce'); ?></label>
            <input type="text" class="border form-control" id="site_id" name="site_id" value="<?php echo isset($settings_result->site_id) ? $settings_result->site_id : ''; ?>" required>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-4">
            <label class="text-secondary" for="contact_root"><?= __('Contacts root ID', 'propeller-ecommerce'); ?></label>
            <input type="text" class="border form-control" id="contact_root" name="contact_root" value="<?php echo isset($settings_result->contact_root) ? $settings_result->contact_root : ''; ?>" required>
        </div>
        <div class="form-group col-md-4">
            <label class="text-secondary" for="customer_root"><?= __('Customers root ID', 'propeller-ecommerce'); ?></label>
            <input type="text" class="border form-control" id="customer_root" name="customer_root" value="<?php echo isset($settings_result->customer_root) ? $settings_result->customer_root : ''; ?>" required>
        </div>
    </div>

    <div class="row">
        <div class="col text-center">
            <button type="submit" id="submit-key" class="integration-form-btn btn btn-success">Save settings</button>
        </div>
    </div>
</form>