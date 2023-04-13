<?php if ($back_url) { ?>
<nav class="catalog-menu d-none d-md-block">
    <div class="filter categories-nav">
        <!-- <button class="btn-filter" type="button" href="#filterForm_catalog_menu" data-toggle="collapse" aria-expanded="true" aria-controls="filterForm_catalog_menu">
            <?php echo __('Categories', 'propeller-ecommerce'); ?>
        </button> -->
        <div class="catalog-filter-content collapse show" id="filterForm_catalog_menu">
            <ul>
                <li class="main-side-subitem sub-parent">
                    <a href="<?php echo esc_url($back_url); ?>" class="w-100 has-submenu machines-back">Back</a>
                </li>
            </ul>                       
        </div>
    </div>
</nav>
<?php } ?>