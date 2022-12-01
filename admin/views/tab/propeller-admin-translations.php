<?php
    $open_file = basename($translator->get_translations()[0]);

    if (isset($_REQUEST['file']) && !empty($_REQUEST['file']) && isset($_REQUEST['open_translation']) && $_REQUEST['open_translation'] == 'true')
        $open_file = $_REQUEST['file'];

    $translations = $translator->load_translation($open_file); 
?>

<div class="row p-3">
    <div class="col-sm text-left border rounded-lg m-1">
        <form method="GET" class="propel-admin-form p-3" action="<?= admin_url( 'admin.php' ) ?>" id="open_translations_form">
            <input type="hidden" name="page" value="propeller">
            <input type="hidden" name="tab" value="translations">

            <select name="file" class="form-control">
                <option value=""><?= __('Select translations', 'propeller-ecommerce'); ?></option>
                <?php foreach ($translator->get_translations() as $trn_file) { ?>
                    <?php 
                        $selected = '';
                        
                        if (isset($_REQUEST['open_translation']) && $_REQUEST['open_translation'] == 'true' &&
                            isset($_REQUEST['file']) && $_REQUEST['file'] == basename($trn_file)) {
                                $selected = 'selected="selected"';
                            }
                    ?>
                    <option value="<?= basename($trn_file); ?>" <?= $selected; ?>><?= basename($trn_file); ?></option>
                <?php } ?>
            </select>

            <div class="text-right mt-3 btn-bottom">
                <button type="submit" name="open_translation" value="true" class="btn btn-primary"><?= __('Open translations', 'propeller-ecommerce'); ?></button>
            </div>
        </form>
    </div>
    <div class="col-sm text-left border rounded-lg m-1">
        <form method="POST" class="propel-admin-form p-3" action="<?= admin_url( 'admin.php' ) ?>" id="create_translations_form">
            <input type="hidden" name="action" value="create_translations_file">
            <input type="hidden" name="page" value="propeller">
            <input type="hidden" name="tab" value="translations">

            <select name="file" class="form-control">
                <option value=""><?= __('Open template', 'propeller-ecommerce'); ?></option>

                <?php foreach ($translator->get_templates() as $temp_file) { ?>
                    <option value="<?= basename($temp_file); ?>"><?= basename($temp_file); ?></option>
                <?php } ?>
            </select>

            <select name="locale" id="select_lang" class="form-control mt-3">
                <option value=""><?= __('Select language', 'propeller-ecommerce'); ?></option>
                <?php foreach ($translator->get_available_languages() as $locale => $name) { ?>
                    <option value="<?= $locale; ?>"><?= $name; ?></option>
                <?php } ?>
            </select>

            <select name="merge" class="form-control mt-3">
                <option value=""><?= __('Merge with', 'propeller-ecommerce'); ?></option>
                <?php foreach ($translator->get_translations() as $trn_file) { ?>
                    <option value="<?= basename($trn_file); ?>"><?= basename($trn_file); ?></option>
                <?php } ?>
            </select>

            <div class="text-right mt-3 btn-bottom">
                <button type="submit" name="open_template" class="btn btn-primary"><?= __('Create translations file', 'propeller-ecommerce'); ?></button>
            </div>
        </form>
    </div>
    <div class="col-sm text-left border rounded-lg m-1">
        <form method="POST" class="propel-admin-form p-3 align-middle" action="<?= admin_url( 'admin.php' ) ?>" id="generate_translations_form">
            <input type="hidden" name="page" value="propeller">
            <input type="hidden" name="tab" value="translations">
            <input type="hidden" name="action" value="generate_translations">
            <input type="hidden" name="po_file" value="<?= isset($_REQUEST['file']) && !empty($_REQUEST['file']) ? $_REQUEST['file'] : '' ?>" />

            <div class="text-center btn-bottom mt-5">
                <button type="submit" name="generate_translations" class="btn btn-danger"><?= __('Generate translations', 'propeller-ecommerce'); ?></button>
            </div>
        </form>
    </div>
</div>

<form method="POST" class="propel-admin-form p-3 border rounded-lg" action="#" id="propel_translations_form">
    <input type="hidden" name="action" value="save_translations" />
    <input type="hidden" name="po_file" value="<?= isset($_REQUEST['file']) && !empty($_REQUEST['file']) ? $_REQUEST['file'] : $translator->get_translations()[0] ?>" />

    <div class="row mb-5">
        <div class="col-sm text-left">
            <button type="submit" id="save_translations" class="integration-form-btn btn btn-success"><?= __('Save translations', 'propeller-ecommerce'); ?></button>
        </div>
        <div class="col-sm text-right">
            <button type="button" id="scan_translations" class="integration-form-btn btn btn-info"><?= __('Scan for new translations', 'propeller-ecommerce'); ?></button>
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
                    <?php $index = 1; ?>
                    <?php foreach ($translations as $translation) { ?>
                        <tr>
                            <td class="text-center align-middle">
                                <?= $index; ?>.
                            </td>
                            <td>
                                <input type="text" class="border form-control" readonly name="original[<?= $index; ?>]" value="<?= $translation->getOriginal(); ?>" />
                            </td>
                            <td>
                                <input type="text" class="border form-control" name="translation[<?= $index; ?>]" value="<?= $translation->getTranslation(); ?>" />
                            </td>
                        </tr>
                    <?php $index++; ?>
                    <?php } ?>
                </tbody>
            </table>
    </div>
    
    <div class="row mt-1">
        <div class="col-sm text-center">
            <button type="button" id="scroll_top" class="integration-form-btn btn btn-warning"><?= __('To top', 'propeller-ecommerce'); ?></button>
        </div>
    </div>
</form>