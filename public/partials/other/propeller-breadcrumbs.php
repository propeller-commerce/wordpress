<nav aria-label="Breadcrumb" class="page-breadcrumb <?= apply_filters('propel_breadcrumb_classes', ''); ?>">
    <ol>
        <li>
            <a href="<?=  get_site_url(); ?>"><?php echo __("Home",'propeller-ecommerce'); ?></a>
        </li>
        
        <?php if ($paths && count($paths)) {
            $index = 0;            
            foreach ($paths as $path) { 
        ?>
            <li>
                <?php if ($index == count($paths) - 1) { ?>
                    <?= $path[1]; ?>
                <?php } else { ?>
                    <a href="<?= $path[0]; ?>" aria-current="page"><?= $path[1]; ?></a>
                <?php } ?>
            </li>
        <?php 
                $index++;
            } 
        }
        ?>
    </ol>
</nav>