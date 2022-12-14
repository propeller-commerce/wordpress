<?php

use Propeller\Includes\Enum\PageType;
use Propeller\Propeller;

    $index = 0;

    $page_sluggable_hint = "Should this page be handled by Propeller's rewrite rules? Will be handled by Wordpress default rewrite rules if left unchecked";
    $is_my_account_page_hint = "If checked, this page will be the default \"My Account Details\" page";
    $account_page_is_parent_hint = "If checked, this page will be a child of the \"My Account Details\" page and the URL will contain the \"My Account Details\" slug as prefix";

?>

<form method="POST" class="propel-admin-form p-3 border rounded-lg" action="#" id="propel_pages_form">
    <input type="hidden" name="action" value="save_propel_pages">
    <input type="hidden" name="delete_pages" id="delete_pages" value="">
        
    <div class="row">    
        <div class="form-group col-md-2">
            <label><?= __('Page name', 'propeller-ecommerce'); ?></label>
        </div>
        <div class="form-group col-md-2">
            <label><?= __('Slug', 'propeller-ecommerce'); ?></label>
        </div>
        <div class="form-group col-md-2">
            <label><?= __('Type', 'propeller-ecommerce'); ?></label>
        </div>
        <div class="form-group col-md-2">
            <label><?= __('Shortcode', 'propeller-ecommerce'); ?></label>
        </div>
        <div class="form-group col-md-3">
            <label>&nbsp;</label>
        </div>
        
        <div class="form-group col-md-1 text-center">
            <label><?= __('Delete', 'propeller-ecommerce'); ?></label>
        </div>
    </div>

    <div class="propel-pages-container">
    <?php foreach ($pages_result as $index => $page) { ?>
        <div class="row propel-page-row" data-index="<?= $index; ?>">    
            <input type="hidden" name="page[<?= $index; ?>][id]" value="<?= $page->id; ?>">

            <div class="form-group col-md-2">
                <input type="text" class="border form-control" placeholder="<?= __('Page name', 'propeller-ecommerce'); ?>" name="page[<?= $index; ?>][page_name]" value="<?= $page->page_name; ?>" required>
            </div>
            <div class="form-group col-md-2">
                <input type="text" class="border form-control" placeholder="<?= __('Page slug', 'propeller-ecommerce'); ?>" name="page[<?= $index; ?>][page_slug]" value="<?= $page->page_slug; ?>" required>
            </div>
            <div class="form-group col-md-2">
                <select class="border form-control" name="page[<?= $index; ?>][page_type]">
                    <?php foreach (PageType::getConstants() as $const => $name) { ?>
                        <option value="<?= $name; ?>" <?= $page->page_type == $name ? 'selected' : ''; ?>><?= $name; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group col-md-2">
                <select class="border form-control" name="page[<?= $index; ?>][page_shortcode]">
                <?php foreach (Propeller::$fe_shortcodes as $shortcode => $method) { ?>
                    <option value="<?= $shortcode; ?>" <?= $page->page_shortcode == $shortcode ? 'selected' : ''; ?>><?= $shortcode; ?></option>
                <?php } ?>
                </select>
            </div>
            <div class="form-group col-md-3">
                <div class="form-check">
                    <input type="checkbox" id="page_sluggable_<?= $index; ?>" class="form-check-input" title="<?= $page_sluggable_hint; ?>" name="page[<?= $index; ?>][page_sluggable]" value="1" <?php echo isset($page->page_sluggable) && $page->page_sluggable == 1 ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="page_sluggable_<?= $index; ?>"><?= __('Apply R/W rules', 'propeller-ecommerce'); ?></label>
                </div>

                <div class="form-check">
                    <input type="checkbox" id="is_my_account_page_<?= $index; ?>" class="form-check-input" title="<?= $is_my_account_page_hint; ?>" name="page[<?= $index; ?>][is_my_account_page]" value="1" <?php echo isset($page->is_my_account_page) && $page->is_my_account_page == 1 ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="is_my_account_page_<?= $index; ?>"><?= __('Is My account page', 'propeller-ecommerce'); ?></label>
                </div>

                <div class="form-check">
                    <input type="checkbox" id="account_page_is_parent_<?= $index; ?>" class="form-check-input" title="<?= $account_page_is_parent_hint; ?>" name="page[<?= $index; ?>][account_page_is_parent]" value="1" <?php echo isset($page->account_page_is_parent) && $page->account_page_is_parent == 1 ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="account_page_is_parent_<?= $index; ?>"><?= __('Child of My Account', 'propeller-ecommerce'); ?></label>
                </div>
            </div>
            
            <div class="form-group col-md-1 text-center">
                <button type="button" class="delete-btn" data-id="<?= $page->id; ?>">
                    <span class="dashicons dashicons-remove"></span>
                </button>
            </div>
        </div>
        <?php 
                $index++;
            } 
        ?>

        <?php if (count($pages_result) == 0) { ?>
        <div class="form-group row propel-page-row" data-index="<?= $index; ?>">    
            <input type="hidden" name="page[<?= $index; ?>][id]" value="0">

            <div class="form-group col-md-2">
                <input type="text" class="border form-control" placeholder="<?= __('Page name', 'propeller-ecommerce'); ?>" name="page[<?= $index; ?>][page_name]" value="" required>
            </div>
            <div class="form-group col-md-2">
                <input type="text" class="border form-control" placeholder="<?= __('Page slug', 'propeller-ecommerce'); ?>" name="page[<?= $index; ?>][page_slug]" value="" required>
            </div>
            <div class="form-group col-md-2">
                <select class="border form-control" name="page[<?= $index; ?>][page_type]">
                    <?php foreach (PageType::getConstants() as $const => $name) { ?>
                        <option value="<?= $name; ?>"><?= $name; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group col-md-2">
                <select class="border form-control" name="page[<?= $index; ?>][page_shortcode]">
                <?php foreach (Propeller::$fe_shortcodes as $shortcode => $method) { ?>
                    <option value="<?= $shortcode; ?>"><?= $shortcode; ?></option>
                <?php } ?>
                </select>
            </div>
            <div class="form-group col-md-3">
                <div class="form-check">
                    <input type="checkbox" id="page_sluggable_<?= $index; ?>" class="form-check-input" title="<?= $page_sluggable_hint; ?>" name="page[<?= $index; ?>][page_sluggable]" value="1">
                    <label class="form-check-label" for="page_sluggable_<?= $index; ?>"><?= __('Apply R/W rules', 'propeller-ecommerce'); ?></label>
                </div>

                <div class="form-check">
                    <input type="checkbox" id="is_my_account_page_<?= $index; ?>" class="form-check-input" title="<?= $is_my_account_page_hint; ?>" name="page[<?= $index; ?>][is_my_account_page]" value="1">
                    <label class="form-check-label" for="is_my_account_page_<?= $index; ?>"><?= __('Is My account page', 'propeller-ecommerce'); ?></label>
                </div>

                <div class="form-check">
                    <input type="checkbox" id="account_page_is_parent_<?= $index; ?>" class="form-check-input" title="<?= $account_page_is_parent_hint; ?>" name="page[<?= $index; ?>][account_page_is_parent]" value="1">
                    <label class="form-check-label" for="account_page_is_parent_<?= $index; ?>"><?= __('Child of My Account', 'propeller-ecommerce'); ?></label>
                </div>
            </div>

            <div class="form-group col-md-1 text-center">
                <button type="button" class="delete-btn" data-id="<?= $page->id; ?>">
                    <span class="dashicons dashicons-remove"></span>
                </button>
            </div>
        </div>
        <?php } ?>
    </div>
    
    <div class="row">
        <div class="col text-left col-md-6">
            <button type="button" id="add_page_btn" class="btn btn-primary"><?= __('New page', 'propeller-ecommerce'); ?></button>
        </div>
        <div class="col text-right col-md-6">
            <button type="submit" id="submit-key" class="btn btn-success"><?= __('Save pages', 'propeller-ecommerce'); ?></button>
        </div>
    </div>
</form>

<div id="page_row_template">
    <div class="form-group row propel-page-row" data-index="{index}">    
        <input type="hidden" name="page[{index}][id]" value="0">

        <div class="form-group col-md-2">
            <input type="text" class="border form-control" placeholder="<?= __('Page name', 'propeller-ecommerce'); ?>" name="page[{index}][page_name]" value="" required>
        </div>
        <div class="form-group col-md-2">
            <input type="text" class="border form-control" placeholder="<?= __('Page slug', 'propeller-ecommerce'); ?>" name="page[{index}][page_slug]" value="" required>
        </div>
        <div class="form-group col-md-2">
            <select class="border form-control" name="page[{index}][page_type]">
                <?php foreach (PageType::getConstants() as $const => $name) { ?>
                    <option value="<?= $name; ?>"><?= $name; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group col-md-2">
            <select class="border form-control" name="page[{index}][page_shortcode]">
            <?php foreach (Propeller::$fe_shortcodes as $shortcode => $method) { ?>
                <option value="<?= $shortcode; ?>"><?= $shortcode; ?></option>
            <?php } ?>
            </select>
        </div>
        <div class="form-group col-md-3">
            <div class="form-check">
                <input type="checkbox" id="page_sluggable_{index}" class="form-check-input" title="<?= $page_sluggable_hint; ?>" name="page[{index}][page_sluggable]" value="1">
                <label class="form-check-label" for="page_sluggable_{index}"><?= __('Apply R/W rules', 'propeller-ecommerce'); ?></label>
            </div>

            <div class="form-check">
                <input type="checkbox" id="is_my_account_page_{index}" class="form-check-input" title="<?= $is_my_account_page_hint; ?>" name="page[{index}][is_my_account_page]" value="1">
                <label class="form-check-label" for="is_my_account_page_{index}"><?= __('Is My account page', 'propeller-ecommerce'); ?></label>
            </div>

            <div class="form-check">
                <input type="checkbox" id="account_page_is_parent_{index}" class="form-check-input" title="<?= $account_page_is_parent_hint; ?>" name="page[{index}][account_page_is_parent]" value="1">
                <label class="form-check-label" for="account_page_is_parent_{index}"><?= __('Child of My Account', 'propeller-ecommerce'); ?></label>
            </div>
        </div>

        <div class="form-group col-md-1 text-center">
            <button type="button" class="delete-btn" data-id="<?= $page->id; ?>">
                <span class="dashicons dashicons-remove"></span>
            </button>
        </div>
    </div>
</div>