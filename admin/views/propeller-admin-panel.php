<div class="alert propel-alert alert-fixed alert-dismissible fade m-2" role="alert">
  <div class="propel-alert-body"></div>
  
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

<div class="row p-0 m-0">
    <div class="col pt-4">
        <h3>
            Propeller <?php echo __('Settings', 'propeller-ecommerce'); ?>
            <small class="text-muted">DB version: <?php echo get_option(PROPELLER_DB_VERSION_OPTION); ?></small>
        </h3>

        <ul class="nav nav-tabs" id="propel_tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true"><?php echo __('General', 'propeller-ecommerce'); ?></a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pages-tab" data-toggle="tab" href="#pages" role="tab" aria-controls="pages" aria-selected="false"><?php echo __('Pages', 'propeller-ecommerce'); ?></a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="behavior-tab" data-toggle="tab" href="#behavior" role="tab" aria-controls="behavior" aria-selected="false"><?php echo __('Behavior', 'propeller-ecommerce'); ?></a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="translations-tab" data-toggle="tab" href="#translations" role="tab" aria-controls="translations" aria-selected="false"><?php echo __('Translations', 'propeller-ecommerce'); ?></a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="sitemap-tab" data-toggle="tab" href="#sitemap" role="tab" aria-controls="sitemap" aria-selected="false"><?php echo __('Sitemap', 'propeller-ecommerce'); ?></a>
            </li>
        </ul>

        <div class="tab-content mb-1" id="propeller_tabs">
            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                <?php require_once 'tab/propeller-admin-general.php'; ?>
            </div>
            <div class="tab-pane fade show" id="pages" role="tabpanel" aria-labelledby="pages-tab">
                <?php require_once 'tab/propeller-admin-pages.php'; ?>
            </div>
            <div class="tab-pane fade show" id="behavior" role="tabpanel" aria-labelledby="behavior-tab">
                <?php require_once 'tab/propeller-admin-behavior.php'; ?>
            </div>
            <div class="tab-pane fade show border rounded-lg p-2" id="translations" role="tabpanel" aria-labelledby="translations-tab">
                <?php require_once 'tab/propeller-admin-translations.php'; ?>
            </div>
            <div class="tab-pane fade show border rounded-lg p-2" id="sitemap" role="tabpanel" aria-labelledby="sitemap-tab">
                <?php require_once 'tab/propeller-admin-sitemap.php'; ?>
            </div>
        </div>
    </div>
</div>

<div class="row p-0 m-0">
    <div class="col pt-4">
    <form method="POST" class="propel-admin-form p-3 border rounded-lg" action="#" id="propeller_cache_form">
        <input type="hidden" name="action" value="propel_destroy_caches">
        <button type="submit" id="submit-key" class="integration-form-btn btn btn-success"><?php echo __('Clear caches', 'propeller-ecommerce'); ?></button>
    </form>
        
    </div>
</div>