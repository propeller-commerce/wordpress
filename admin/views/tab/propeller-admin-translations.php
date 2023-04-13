<?php
    
    $translations = [];

    if (isset($_REQUEST['file']) && !empty($_REQUEST['file']) && isset($_REQUEST['open_translation']) && $_REQUEST['open_translation'] == 'true') {
        $open_file = sanitize_text_field($_REQUEST['file']);
        $translations = $translator->load_translation($open_file); 
    }
?>

<div class="row p-3">
    <div class="col-sm text-left border rounded-lg m-1">
        <form method="GET" class="propel-admin-form w-100 p-1" action="<?php echo admin_url( 'admin.php' ) ?>" id="open_translations_form">
            <input type="hidden" name="page" value="propeller">
            <input type="hidden" name="tab" value="translations">

            <label><?php echo __('Open translations', 'propeller-ecommerce'); ?></label>
            <div class="form-row">
                <div class="form-group col-md-10">
                    <select name="file" class="form-control" id="translation_file">
                        <option value=""><?php echo __('Select translations', 'propeller-ecommerce'); ?></option>
                        <?php foreach ($translator->get_translations() as $trn_file) { ?>
                            <?php 
                                $selected = '';
                                
                                if (isset($_REQUEST['open_translation']) && $_REQUEST['open_translation'] == 'true' &&
                                    isset($_REQUEST['file']) && $_REQUEST['file'] == basename($trn_file)) {
                                        $selected = 'selected="selected"';
                                    }
                            ?>
                            <option value="<?php echo basename(esc_attr($trn_file)); ?>" <?php echo (string) $selected; ?>><?php echo basename($trn_file); ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group col-md-2">
                    <button type="submit" name="open_translation" value="true" class="btn btn-primary"><?php echo __('Open', 'propeller-ecommerce'); ?></button>
                </div>
            </div>
        </form>

        <hr />

        <form method="POST" class="propel-admin-form w-100 p-1 pr-3" action="<?php echo admin_url( 'admin.php' ) ?>" id="restore_translations_form">
            <input type="hidden" name="page" value="propeller">
            <input type="hidden" name="tab" value="translations">

            <label><?php echo __('Restore translations', 'propeller-ecommerce'); ?></label>
            <div class="form-row">
                <div class="form-group col-md-10">
                    <select name="backup_date" class="form-control" id="backup_date">
                        <option value=""><?php echo __('Restore translations', 'propeller-ecommerce'); ?></option>
                        <?php foreach ($translator->get_backups() as $bkp) { ?>
                            <option value="<?php echo wp_basename($bkp); ?>"><?php echo str_replace('+', ' ', str_replace('_', ':', wp_basename($bkp))); ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group col-md-2">
                    <button type="submit" name="restore_translation" value="true" class="btn btn-warning"><?php echo __('Restore', 'propeller-ecommerce'); ?></button>
                </div>
            </div>
        </form>
    </div>

                                

    <div class="col-sm text-left border rounded-lg m-1">
        <form method="POST" class="propel-admin-form p-1" action="<?php echo admin_url( 'admin.php' ) ?>" id="create_translations_form">
            <input type="hidden" name="action" value="create_translations_file">
            <input type="hidden" name="page" value="propeller">
            <input type="hidden" name="tab" value="translations">

            <label><?php echo __('Create translations', 'propeller-ecommerce'); ?></label>

            <select name="file" class="form-control">
                <option value=""><?php echo __('Open translations template', 'propeller-ecommerce'); ?></option>

                <?php foreach ($translator->get_templates() as $temp_file) { ?>
                    <option value="<?php echo wp_basename($temp_file); ?>"><?php echo wp_basename($temp_file); ?></option>
                <?php } ?>
            </select>

            <select name="locale" id="select_lang" class="form-control mt-3">
                <option value=""><?php echo __('Select language', 'propeller-ecommerce'); ?></option>
                <?php foreach ($translator->get_available_languages() as $loc => $locale) { ?>
                    <option value="<?php echo esc_attr($locale['wp_locale']); ?>"><?php echo esc_html($locale['name']); ?></option>
                <?php } ?>
            </select>

            <select name="merge" class="form-control mt-3">
                <option value=""><?php echo __('Merge with previous translations', 'propeller-ecommerce'); ?></option>
                <?php foreach ($translator->get_translations() as $trn_file) { ?>
                    <option value="<?php echo wp_basename($trn_file); ?>"><?php echo wp_basename($trn_file); ?></option>
                <?php } ?>
            </select>

            <div class="text-right mt-3 btn-bottom">
                <button type="submit" name="open_template" class="btn btn-primary"><?php echo __('Create translations file', 'propeller-ecommerce'); ?></button>
            </div>
        </form>
    </div>
    <div class="col-sm text-left border rounded-lg m-1">
        <form method="POST" class="propel-admin-form p-1 w-100" action="<?php echo admin_url( 'admin.php' ) ?>" id="download_translations_form">
            <input type="hidden" name="page" value="propeller">
            <input type="hidden" name="tab" value="translations">
            <input type="hidden" name="action" value="download_translations">
            
            <div class="btn-bottom mt-1 text-center">
                <button type="submit" name="download_translations" class="btn btn-primary"><?php echo __('Download translations', 'propeller-ecommerce'); ?></button>
            </div>
        </form>

        <hr />

        <form method="POST" class="propel-admin-form p-1 w-100 mb-3" action="<?php echo admin_url( 'admin.php' ) ?>" id="generate_translations_form">
            <input type="hidden" name="page" value="propeller">
            <input type="hidden" name="tab" value="translations">
            <input type="hidden" name="action" value="generate_translations">
            <input type="hidden" name="po_file" value="<?php echo isset($_REQUEST['file']) && !empty($_REQUEST['file']) ? esc_attr($_REQUEST['file']) : '' ?>" />

            <div class="text-center mt-1">
                <button type="submit" name="generate_translations" class="btn btn-danger"><?php echo __('Generate translations', 'propeller-ecommerce'); ?></button>
            </div>
        </form>
    </div>
</div>

<form method="POST" class="propel-admin-form p-3 border rounded-lg" action="#" id="propel_translations_form">
    <input type="hidden" name="action" value="save_translations" />
    <input type="hidden" name="po_file" value="<?php echo isset($_REQUEST['file']) && !empty($_REQUEST['file']) ? esc_attr($_REQUEST['file']) : ''; ?>" />

    <div class="row mb-5">
        <div class="col-sm text-left">
            <button type="submit" id="save_translations" class="integration-form-btn btn btn-success"><?php echo __('Save translations', 'propeller-ecommerce'); ?></button>
        </div>
        <div class="col-sm text-right">
            <button type="button" id="scan_translations" class="integration-form-btn btn btn-info"><?php echo __('Scan for new translations', 'propeller-ecommerce'); ?></button>
        </div>
    </div>

    <div class="row">
        <table class="table table-borderless table-hover table-sm">
            <thead>
                <tr>
                    <th class="propel-table-id">&nbsp;</th>
                    <th class="propel-col-50"><?php echo __('Original', 'propeller-ecommerce'); ?></th>
                    <th class="propel-col-50"><?php echo __('Translated', 'propeller-ecommerce'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!count($translations)) { ?>
                    <tr>
                        <td class="text-center" colspan="3">
                            <h3 class="text-danger"><?php echo __('Please open translations file', 'propeller-ecommerce'); ?></h3>
                        </td>
                    </tr>
                <?php } else { ?>
                    <?php $index = 1; ?>
                    <?php foreach ($translations as $translation) { ?>
                        <tr>
                            <td class="text-center align-middle">
                                <?php echo esc_html($index); ?>.
                            </td>
                            <td>
                                <input type="text" class="border form-control" readonly name="original[<?php echo intval($index); ?>]" value="<?php echo htmlspecialchars($translation->getOriginal()); ?>" />
                            </td>
                            <td>
                                <input type="text" class="border form-control" name="translation[<?php echo intval($index); ?>]" value="<?php echo htmlspecialchars($translation->getTranslation()); ?>" />
                            </td>
                        </tr>
                    <?php $index++; ?>
                    <?php } 
                } ?>
            </tbody>
        </table>
    </div>
    
    <div class="row mt-1">
        <div class="col-sm text-center">
            <button type="button" id="scroll_top" class="integration-form-btn btn btn-warning"><?php echo __('To top', 'propeller-ecommerce'); ?></button>
        </div>
    </div>
</form>