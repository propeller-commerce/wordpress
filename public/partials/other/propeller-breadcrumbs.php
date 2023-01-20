<nav aria-label="Breadcrumb" class="page-breadcrumb <?php echo apply_filters('propel_breadcrumb_classes', ''); ?>">
    <ol>
        <li>
            <a href="<?php echo  get_site_url(); ?>"><?php echo __("Home",'propeller-ecommerce'); ?></a>
        </li>
        
        <?php if ($paths && count($paths)) {
            $index = 0;            
            foreach ($paths as $path) { 
        ?>
            <li>
                <?php if ($index == count($paths) - 1) { ?>
                    <a href="<?php echo $path[0]; ?>" aria-current="page"><?php echo $path[1]; ?></a>
                <?php } else { ?>
                    <a href="<?php echo $path[0]; ?>"><?php echo $path[1]; ?></a>
                <?php } ?>
            </li>
        <?php 
                $index++;
            } 
        }
        ?>
    </ol>
</nav>