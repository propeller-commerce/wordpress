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
        
    <div class="propel-pages-container">
        <div class="accordion-container mb-3">
                
        <?php foreach ($pages_result as $index => $page) { ?>
            <div class="ac propel-page-acc-row">
                <h2 class="ac-header">
                    <button type="button" class="ac-trigger"><?php echo esc_attr($page->page_name); ?></button>
                </h2>
                <div class="ac-panel">                    
                    <div class="propel-page-row ac-text" data-index="<?php echo intval($index); ?>">
                        <input type="hidden" name="page[<?php echo intval($index); ?>][id]" value="<?php echo intval($page->id); ?>">
                        
                        <div class="form-row">
                            <div class="col-md-7">
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="pagename_<?php echo intval($index); ?>"><?php echo __('Page name', 'propeller-ecommerce'); ?></label>
                                        <input type="text" id="pagename_<?php echo intval($index); ?>" class="border form-control" placeholder="<?php echo __('Page name', 'propeller-ecommerce'); ?>" name="page[<?php echo intval($index); ?>][page_name]" value="<?php echo esc_attr($page->page_name); ?>" required>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="pagetype_<?php echo intval($index); ?>"><?php echo __('Type', 'propeller-ecommerce'); ?></label>
                                        <select class="border form-control"  id="pagetype_<?php echo intval($index); ?>" name="page[<?php echo intval($index); ?>][page_type]">
                                            <?php foreach (PageType::getConstants() as $const => $name) { ?>
                                                <option value="<?php echo esc_attr($name); ?>" <?php echo (bool) ($page->page_type == $name) ? 'selected' : ''; ?>><?php echo esc_html($name); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="pageshortcode_<?php echo intval($index); ?>"><?php echo __('Shortcode', 'propeller-ecommerce'); ?></label>
                                        <select class="border form-control" id="pageshortcode_<?php echo intval($index); ?>" name="page[<?php echo intval($index); ?>][page_shortcode]">
                                        <?php foreach (Propeller::$fe_shortcodes as $shortcode => $method) { ?>
                                            <option value="<?php echo esc_attr($shortcode); ?>" <?php echo (bool) ($page->page_shortcode == $shortcode) ? 'selected' : ''; ?>><?php echo esc_html($shortcode); ?></option>
                                        <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-row">                            
                                    <div class="form-group col-4">
                                        <div class="form-check">
                                            <input type="checkbox" id="page_sluggable_<?php echo intval($index); ?>" class="form-check-input" title="<?php echo esc_attr($page_sluggable_hint); ?>" name="page[<?php echo intval($index); ?>][page_sluggable]" value="1" <?php echo isset($page->page_sluggable) && intval($page->page_sluggable) == 1 ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="page_sluggable_<?php echo intval($index); ?>"><?php echo __('Apply Read/Write rules', 'propeller-ecommerce'); ?></label>
                                        </div>
                                    </div>

                                    <div class="form-group col-4">
                                        <div class="form-check">
                                            <input type="checkbox" id="is_my_account_page_<?php echo intval($index); ?>" class="form-check-input" title="<?php echo esc_attr($is_my_account_page_hint); ?>" name="page[<?php echo intval($index); ?>][is_my_account_page]" value="1" <?php echo isset($page->is_my_account_page) && intval($page->is_my_account_page) == 1 ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="is_my_account_page_<?php echo intval($index); ?>"><?php echo __('Is My account page', 'propeller-ecommerce'); ?></label>
                                        </div>
                                    </div>

                                    <div class="form-group col-4">
                                        <div class="form-check">
                                            <input type="checkbox" id="account_page_is_parent_<?php echo intval($index); ?>" class="form-check-input" title="<?php echo esc_attr($account_page_is_parent_hint); ?>" name="page[<?php echo intval($index); ?>][account_page_is_parent]" value="1" <?php echo isset($page->account_page_is_parent) && intval($page->account_page_is_parent) == 1 ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="account_page_is_parent_<?php echo intval($index); ?>"><?php echo __('Child of My Account', 'propeller-ecommerce'); ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="pageslug_<?php echo intval($index); ?>"><?php echo __('Slug', 'propeller-ecommerce'); ?></label>
                                <input type="text" id="pageslug_<?php echo intval($index); ?>" class="border form-control" placeholder="<?php echo __('Slug', 'propeller-ecommerce'); ?>" name="page[<?php echo intval($index); ?>][page_slug]" value="<?php echo esc_attr($page->page_slug); ?>" required>
                            </div>
                            
                            <?php /*
                            <div class="col-md-4">
                                <div class="form-group col">
                                    <label><?php echo __('Slug(s)', 'propeller-ecommerce'); ?></label>

                                    <?php 
                                        $last_slug_id = 0; 
                                        $last_page_id = $page->id; 
                                    ?>

                                    <div class="page-slug-containers page-slugs-container-<?php echo intval($index); ?>" data-index="<?php echo intval($index); ?>" data-page_id="<?php echo esc_attr($slug->page_id); ?>">
                                        <?php foreach ($page->slugs as $slug) { ?>
                                            <div class="row page-slug-row" data-id="<?php echo esc_attr($slug->id); ?>" data-page_id="<?php echo esc_attr($slug->page_id); ?>">
                                                <input type="hidden" name="page[<?php echo intval($index); ?>][slugs][slug_id][<?php echo esc_attr($slug->id); ?>]" value="<?php echo esc_attr($slug->id); ?>">
                                                <input type="hidden" name="page[<?php echo intval($index); ?>][slugs][slug_exists][<?php echo esc_attr($slug->id); ?>]" value="1">

                                                <div class="col-3">
                                                    <select class="form-control page-slugs-languages" name="page[<?php echo intval($index); ?>][slugs][slug_lang][<?php echo esc_attr($slug->id); ?>]">
                                                    <?php foreach ($slug_langs as $lng) { ?>
                                                        <option value="<?php echo esc_attr($lng); ?>" <?php echo $lng == $slug->language ? 'selected' : ''; ?>><?php echo esc_html($lng); ?></option>
                                                    <?php } ?>
                                                    </select> 
                                                </div>
                                                <div class="col-8">
                                                    <input type="text" class="border form-control" placeholder="<?php echo __('Page slug', 'propeller-ecommerce'); ?>" name="page[<?php echo intval($index); ?>][slugs][slug][<?php echo esc_attr($slug->id); ?>]" value="<?php echo esc_attr($slug->slug); ?>">
                                                </div>
                                                <div class="col-1">
                                                    <button type="button" class="btn btn-primary propel-add-lng-btn" data-id="<?php echo esc_attr($slug->id); ?>" data-page_id="<?php echo esc_attr($slug->page_id); ?>">+</button>
                                                </div>
                                            </div>
                                            <?php $last_slug_id = $slug->id; ?>
                                        <?php } ?>

                                        <?php if (count($page->slugs) < count($slug_langs)) { ?>
                                            <?php $last_slug_id++; ?>

                                            <div class="row page-slug-row" data-id="<?php echo esc_attr($last_slug_id); ?>" data-page_id="<?php echo esc_attr($last_page_id); ?>">
                                                <input type="hidden" name="page[<?php echo intval($index); ?>][slugs][slug_id][<?php echo esc_attr($last_slug_id); ?>]" value="">
                                                <input type="hidden" name="page[<?php echo intval($index); ?>][slugs][slug_exists][<?php echo esc_attr($last_slug_id); ?>]" value="0">

                                                <div class="col-3">
                                                    <select class="form-control page-slugs-languages" name="page[<?php echo intval($index); ?>][slugs][slug_lang][<?php echo esc_attr($last_slug_id); ?>]">
                                                    <?php foreach ($slug_langs as $lng) { ?>
                                                        <option value="<?php echo esc_attr($lng); ?>"><?php echo esc_html($lng); ?></option>
                                                    <?php } ?>
                                                    </select> 
                                                </div>
                                                <div class="col-8">
                                                    <input type="text" class="border form-control" placeholder="<?php echo __('Page slug', 'propeller-ecommerce'); ?>" name="page[<?php echo intval($index); ?>][slugs][slug][<?php echo esc_attr($last_slug_id); ?>]" value="">
                                                </div>
                                                <div class="col-1">
                                                    <button type="button" class="btn btn-primary propel-add-lng-btn" data-id="<?php echo esc_attr($last_slug_id); ?>" data-page_id="<?php echo esc_attr($last_page_id); ?>">+</button>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    
                                </div>
                            </div> 
                            */ ?>

                            <div class="col-md-1 text-right">
                                <button type="button" class="delete-btn" data-id="<?php echo intval($page->id); ?>" title="<?php echo __('Delete page', 'propeller-ecommerce'); ?>">
                                    <span class="dashicons dashicons-remove"></span>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <?php $index++; ?>
        <?php } ?>

        <?php if (count($pages_result) == 0) { ?>
            <div class="ac propel-page-acc-row">
                <h2 class="ac-header">
                    <button type="button" class="ac-trigger"><?php echo __('New page', 'propeller-ecommerce'); ?></button>
                </h2>
                <div class="ac-panel">                    
                    <div class="propel-page-row ac-text" data-index="<?php echo intval($index); ?>">
                        <input type="hidden" name="page[<?php echo intval($index); ?>][id]" value="0">
                        
                        <div class="form-row">
                            <div class="col-md-7">
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="pagename_<?php echo intval($index); ?>"><?php echo __('Page name', 'propeller-ecommerce'); ?></label>
                                        <input type="text" id="pagename_<?php echo intval($index); ?>" class="border form-control" placeholder="<?php echo __('Page name', 'propeller-ecommerce'); ?>" name="page[<?php echo intval($index); ?>][page_name]" value="">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="pagetype_<?php echo intval($index); ?>"><?php echo __('Type', 'propeller-ecommerce'); ?></label>
                                        <select class="border form-control"  id="pagetype_<?php echo intval($index); ?>" name="page[<?php echo intval($index); ?>][page_type]">
                                            <?php foreach (PageType::getConstants() as $const => $name) { ?>
                                                <option value="<?php echo esc_attr($name); ?>"><?php echo esc_html($name); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="pageshortcode_<?php echo intval($index); ?>"><?php echo __('Shortcode', 'propeller-ecommerce'); ?></label>
                                        <select class="border form-control" id="pageshortcode_<?php echo intval($index); ?>" name="page[<?php echo intval($index); ?>][page_shortcode]">
                                        <?php foreach (Propeller::$fe_shortcodes as $shortcode => $method) { ?>
                                            <option value="<?php echo esc_attr($shortcode); ?>"><?php echo esc_html($shortcode); ?></option>
                                        <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-row">                            
                                    <div class="form-group col-4">
                                        <div class="form-check">
                                            <input type="checkbox" id="page_sluggable_<?php echo intval($index); ?>" class="form-check-input" title="<?php echo esc_attr($page_sluggable_hint); ?>" name="page[<?php echo intval($index); ?>][page_sluggable]" value="1">
                                            <label class="form-check-label" for="page_sluggable_<?php echo intval($index); ?>"><?php echo __('Apply Read/Write rules', 'propeller-ecommerce'); ?></label>
                                        </div>
                                    </div>

                                    <div class="form-group col-4">
                                        <div class="form-check">
                                            <input type="checkbox" id="is_my_account_page_<?php echo intval($index); ?>" class="form-check-input" title="<?php echo esc_attr($is_my_account_page_hint); ?>" name="page[<?php echo intval($index); ?>][is_my_account_page]" value="1">
                                            <label class="form-check-label" for="is_my_account_page_<?php echo intval($index); ?>"><?php echo __('Is My account page', 'propeller-ecommerce'); ?></label>
                                        </div>
                                    </div>

                                    <div class="form-group col-4">
                                        <div class="form-check">
                                            <input type="checkbox" id="account_page_is_parent_<?php echo intval($index); ?>" class="form-check-input" title="<?php echo esc_attr($account_page_is_parent_hint); ?>" name="page[<?php echo intval($index); ?>][account_page_is_parent]" value="1">
                                            <label class="form-check-label" for="account_page_is_parent_<?php echo intval($index); ?>"><?php echo __('Child of My Account', 'propeller-ecommerce'); ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="pageslug_<?php echo intval($index); ?>"><?php echo __('Slug', 'propeller-ecommerce'); ?></label>
                                <input type="text" id="pageslug_<?php echo intval($index); ?>" class="border form-control" placeholder="<?php echo __('Slug', 'propeller-ecommerce'); ?>" name="page[<?php echo intval($index); ?>][page_slug]" value="" required>
                            </div>

                            <?php /*
                            <div class="col-md-4">
                                <div class="form-group col">
                                    <label><?php echo __('Slug(s)', 'propeller-ecommerce'); ?></label>

                                    <?php 
                                        $new_slug_id = 0; 
                                        $new_page_id = 0; 
                                    ?>

                                    <div class="page-slug-containers page-slugs-container-<?php echo intval($index); ?>" data-page_id="<?php echo esc_attr($slug->page_id); ?>">
                                        <div class="row page-slug-row" data-id="<?php echo esc_attr($new_slug_id); ?>" data-page_id="<?php echo esc_attr($new_page_id); ?>">
                                            <input type="hidden" name="page[<?php echo intval($index); ?>][slugs][slug_id][<?php echo esc_attr($new_slug_id); ?>]" value="">
                                            <input type="hidden" name="page[<?php echo intval($index); ?>][slugs][slug_exists][<?php echo esc_attr($new_slug_id); ?>]" value="0">

                                            <div class="col-3">
                                                <select class="form-control page-slugs-languages" name="page[<?php echo intval($index); ?>][slugs][slug_lang][<?php echo esc_attr($new_slug_id); ?>]">
                                                <?php foreach ($slug_langs as $lng) { ?>
                                                    <option value="<?php echo esc_attr($lng); ?>"><?php echo esc_html($lng); ?></option>
                                                <?php } ?>
                                                </select> 
                                            </div>
                                            <div class="col-8">
                                                <input type="text" class="border form-control" placeholder="<?php echo __('Page slug', 'propeller-ecommerce'); ?>" name="page[<?php echo intval($index); ?>][slugs][slug][<?php echo esc_attr($new_slug_id); ?>]" value="">
                                            </div>
                                            <div class="col-1">
                                                <button type="button" class="btn btn-primary propel-add-lng-btn" data-id="<?php echo esc_attr($new_slug_id); ?>" data-page_id="<?php echo esc_attr($new_page_id); ?>">+</button>
                                            </div>
                                        </div>                                        
                                    </div>                                    
                                </div>
                            </div>
                            */ ?>

                            <div class="col-md-1 text-right">
                                <button type="button" class="delete-btn" data-id="<?php echo intval($page->id); ?>" title="<?php echo __('Delete page', 'propeller-ecommerce'); ?>">
                                    <span class="dashicons dashicons-remove"></span>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        <?php } ?>
        </div>
    </div>
    <div class="row">
        <div class="col text-left col-md-6">
            <button type="button" id="add_page_btn" class="btn btn-primary"><?php echo __('New page', 'propeller-ecommerce'); ?></button>
        </div>
        <div class="col text-right col-md-6">
            <button type="submit" id="submit-key" class="btn btn-success"><?php echo __('Save pages', 'propeller-ecommerce'); ?></button>
        </div>
    </div>
</form>

<div id="page_row_template">
    <div class="ac propel-page-acc-row">
        <h2 class="ac-header">
            <button type="button" class="ac-trigger"><?php echo __('New page', 'propeller-ecommerce'); ?></button>
        </h2>
        <div class="ac-panel">                    
            <div class="propel-page-row ac-text" data-index="{index}">
                <input type="hidden" name="page[{index}][id]" value="0">
                
                <div class="form-row">
                    <div class="col-md-7">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="pagename_{index}"><?php echo __('Page name', 'propeller-ecommerce'); ?></label>
                                <input type="text" id="pagename_{index}" class="border form-control" placeholder="<?php echo __('Page name', 'propeller-ecommerce'); ?>" name="page[{index}][page_name]" value="">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="pagetype_{index}"><?php echo __('Type', 'propeller-ecommerce'); ?></label>
                                <select class="border form-control"  id="pagetype_{index}" name="page[{index}][page_type]">
                                    <?php foreach (PageType::getConstants() as $const => $name) { ?>
                                        <option value="<?php echo esc_attr($name); ?>"><?php echo esc_html($name); ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="pageshortcode_{index}"><?php echo __('Shortcode', 'propeller-ecommerce'); ?></label>
                                <select class="border form-control" id="pageshortcode_{index}" name="page[{index}][page_shortcode]">
                                <?php foreach (Propeller::$fe_shortcodes as $shortcode => $method) { ?>
                                    <option value="<?php echo esc_attr($shortcode); ?>"><?php echo esc_html($shortcode); ?></option>
                                <?php } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-row">                            
                            <div class="form-group col-4">
                                <div class="form-check">
                                    <input type="checkbox" id="page_sluggable_{index}" class="form-check-input" title="<?php echo esc_attr($page_sluggable_hint); ?>" name="page[{index}][page_sluggable]" value="1">
                                    <label class="form-check-label" for="page_sluggable_{index}"><?php echo __('Apply Read/Write rules', 'propeller-ecommerce'); ?></label>
                                </div>
                            </div>

                            <div class="form-group col-4">
                                <div class="form-check">
                                    <input type="checkbox" id="is_my_account_page_{index}" class="form-check-input" title="<?php echo esc_attr($is_my_account_page_hint); ?>" name="page[{index}][is_my_account_page]" value="1">
                                    <label class="form-check-label" for="is_my_account_page_{index}"><?php echo __('Is My account page', 'propeller-ecommerce'); ?></label>
                                </div>
                            </div>

                            <div class="form-group col-4">
                                <div class="form-check">
                                    <input type="checkbox" id="account_page_is_parent_{index}" class="form-check-input" title="<?php echo esc_attr($account_page_is_parent_hint); ?>" name="page[{index}][account_page_is_parent]" value="1">
                                    <label class="form-check-label" for="account_page_is_parent_{index}"><?php echo __('Child of My Account', 'propeller-ecommerce'); ?></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="pageslug_{index}"><?php echo __('Slug', 'propeller-ecommerce'); ?></label>
                        <input type="text" id="pageslug_{index}" class="border form-control" placeholder="<?php echo __('Slug', 'propeller-ecommerce'); ?>" name="page[{index}][page_slug]" value="" required>
                    </div>


                    <?php /*
                    <div class="col-md-4">
                        <div class="form-group col">
                            <label><?php echo __('Slug(s)', 'propeller-ecommerce'); ?></label>

                            <?php 
                                $new_slug_id = 0; 
                                $new_page_id = 0; 
                            ?>

                            <div class="page-slug-containers page-slugs-container-{index}" data-page_id="<?php echo esc_attr($slug->page_id); ?>">
                                <div class="row page-slug-row" data-id="<?php echo esc_attr($new_slug_id); ?>" data-page_id="<?php echo esc_attr($new_page_id); ?>">
                                    <input type="hidden" name="page[{index}][slugs][slug_id][<?php echo esc_attr($new_slug_id); ?>]" value="">
                                    <input type="hidden" name="page[{index}][slugs][slug_exists][<?php echo esc_attr($new_slug_id); ?>]" value="0">

                                    <div class="col-3">
                                        <select class="form-control page-slugs-languages" name="page[{index}][slugs][slug_lang][<?php echo esc_attr($new_slug_id); ?>]">
                                        <?php foreach ($slug_langs as $lng) { ?>
                                            <option value="<?php echo esc_attr($lng); ?>"><?php echo esc_html($lng); ?></option>
                                        <?php } ?>
                                        </select> 
                                    </div>
                                    <div class="col-8">
                                        <input type="text" class="border form-control" placeholder="<?php echo __('Page slug', 'propeller-ecommerce'); ?>" name="page[{index}][slugs][slug][<?php echo esc_attr($new_slug_id); ?>]" value="">
                                    </div>
                                    <div class="col-1">
                                        <button type="button" class="btn btn-primary propel-add-lng-btn" data-id="<?php echo esc_attr($new_slug_id); ?>" data-page_id="<?php echo esc_attr($new_page_id); ?>">+</button>
                                    </div>
                                </div>                                        
                            </div>                                    
                        </div>
                    </div>
                    */ ?>

                    <div class="col-md-1 text-right">
                        <button type="button" class="delete-btn" data-id="<?php echo intval($page->id); ?>" title="<?php echo __('Delete page', 'propeller-ecommerce'); ?>">
                            <span class="dashicons dashicons-remove"></span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div id="slug_row_template">
    <div class="row page-slug-row" data-id="{slug-id}" data-page_id="{page-id}">
        <input type="hidden" name="page[{index}][slugs][slug_id][{slug-id}]" value="">
        <input type="hidden" name="page[{index}][slugs][slug_exists][{slug-id}]" value="0">

        <div class="col-3">
            <select class="form-control page-slugs-languages" name="page[{index}][slugs][slug_lang][{slug-id}]">
            <?php foreach ($slug_langs as $lng) { ?>
                <option value="<?php echo esc_attr($lng); ?>"><?php echo esc_html($lng); ?></option>
            <?php } ?>
            </select> 
        </div>
        <div class="col-8">
            <input type="text" class="border form-control" placeholder="<?php echo __('Page slug', 'propeller-ecommerce'); ?>" name="page[{index}][slugs][slug][{slug-id}]" value="">
        </div>
        <div class="col-1">
            <button type="button" class="btn btn-primary propel-add-lng-btn" data-id="{slug-id}" data-page_id="{page-id}">+</button>
        </div>
    </div>
</div>