<?php

use Propeller\Includes\Controller\PageController;
use Propeller\Includes\Enum\PageType;

?>
<nav aria-label="Breadcrumb" class="page-breadcrumb <?= apply_filters('propel_breadcrumb_classes', ''); ?>">
    <ol>
        <li>
            <a href="<?=  get_site_url(); ?>"><?php echo __("Home",'propeller-ecommerce'); ?></a>
        </li>
        
        <?php if ($paths && count($paths)) {
            

            if (isset($paths[0]->name) && is_array($paths[0]->name)) {
                $index = 0;

                foreach ($paths as $path) { 
                    if ($index > 0) {
            ?>
                    <li>
                        <?php if ($index == count($paths) - 1) { ?>
                            <?= $path->name[0]->value; ?>
                        <?php } else { ?>
                            <a href="<?= $this->buildUrl(PageController::get_slug(PageType::CATEGORY_PAGE), $path->slug[0]->value); ?>" aria-current="page"><?= $path->name[0]->value; ?></a>
                        <?php } ?>
                    </li>
            <?php 
                    }
                $index++;
                } 
            }
            else {
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
            }
        ?>
    </ol>
</nav>