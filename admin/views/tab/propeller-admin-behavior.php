<form method="POST" class="propel-admin-form p-3 border rounded-lg" action="#" id="propel_behavior_form">
    <input type="hidden" id="setting_id" name="setting_id" value="<?php echo isset($behavior_result->id) ? $behavior_result->id : 0; ?>">
    <input type="hidden" name="action" value="save_behavior">
    
    <div class="form-row">
        <div class="form-group col-md-6">
            <input type="checkbox" class="border form-control" id="wordpress_session" name="wordpress_session" value="true" <?php echo isset($behavior_result->wordpress_session) && $behavior_result->wordpress_session == 1 ? 'checked' : ''; ?>>
            <label class="text-secondary" for="wordpress_session"><?= __('Enable Wordpress sessions', 'propeller-ecommerce'); ?></label><br />
            <small class="text-warning"><?= __('(Propeller and Wordpress sessions will be combined)', 'propeller-ecommerce'); ?></small>
        </div>
        <div class="form-group col-md-6">
            <input type="checkbox" class="border form-control" id="closed_portal" name="closed_portal" value="true" <?php echo isset($behavior_result->closed_portal) && $behavior_result->closed_portal == 1 ? 'checked' : ''; ?>>
            <label class="text-secondary" for="closed_portal"><?= __('Is closed portal?', 'propeller-ecommerce'); ?></label><br />
            <small class="text-warning"><?= __('(If checked, anonymous users will land on a login page)', 'propeller-ecommerce'); ?></small>
        </div>
        <div class="form-group col-md-6">
            &nbsp;
        </div>
        <div class="form-group col-md-6">
            <div id="exclusions_container" class="form-group" style="display: <?php echo $behavior_result->closed_portal == 1 ? 'block': 'none'; ?>">
                <label class="text-secondary"><?= __('Select pages for exclusion', 'propeller-ecommerce'); ?></label>
                <br />
                <select multiple name="exclusions" id="exclusions" size="10" class="border form-control">
                <?php
                    $exclusions = explode(',', $behavior_result->excluded_pages);

                    if ($pages = get_pages([])) {
                        foreach ($pages as $page) {
                    ?>
                        <option value="<?= $page->ID; ?>" <?php echo (in_array($page->ID, $exclusions) ? 'selected' : ''); ?>><?= $page->post_title; ?></option>
                    <?php
                    } }
                ?>
                </select>
                <small class="text-warning"><?= __('(Selected pages will be available even if the user is not logged in in a closed webshop)', 'propeller-ecommerce'); ?></small>
                <input type="hidden" id="excluded_pages" name="excluded_pages" value="<?php echo $behavior_result->excluded_pages; ?>">
            </div>
        </div>
        <div class="form-group col-md-6">
            <label class="text-secondary" for="track_user_attr"><?= __('Track attribute for users:', 'propeller-ecommerce'); ?></label>
            <input type="text" class="border form-control" id="track_user_attr" placeholder="Track attribute for users" name="track_user_attr" value="<?php echo isset($behavior_result->track_user_attr) ? $behavior_result->track_user_attr : ''; ?>">
            <small class="text-warning"><?= __('(Personalized content will be displayed for users based on this attribute value)', 'propeller-ecommerce'); ?></small>
        </div>

        <div class="form-group col-md-6">
            <label class="text-secondary" for="track_product_attr"><?= __('Track attributes for products:', 'propeller-ecommerce'); ?></label>
            <input type="text" class="border form-control" id="track_product_attr" placeholder="Track attributes for products" name="track_product_attr" value="<?php echo isset($behavior_result->track_product_attr) ? $behavior_result->track_product_attr : ''; ?>">
            <small class="text-warning"><?= __('(Additional info for products based on these attribute values)', 'propeller-ecommerce'); ?></small>
        </div>

        <!-- <div class="form-group col-md-6">
            <input type="checkbox" class="border form-control" id="reload_filters" placeholder="Reload filters" name="reload_filters" value="true" <?php echo isset($behavior_result->reload_filters) && $behavior_result->reload_filters == 1 ? 'checked' : ''; ?>>
            <label class="text-secondary" for="reload_filters"><?= __('Reload filters', 'propeller-ecommerce'); ?></label><br />
            <small class="text-warning"><?= __('(Reload the whole category page including filters when browsing)', 'propeller-ecommerce'); ?></small>
        </div> -->
    </div>

    <div class="row">
        <div class="col text-center">
            <button type="submit" id="submit-key" class="integration-form-btn btn btn-success"><?= __('Save behavior', 'propeller-ecommerce'); ?></button>
        </div>
    </div>
</form>