<?php foreach($cluster->$type as $crossupsell) { ?>
    <div>
        <?php echo apply_filters('propel_cluster_crossupsell_card', $crossupsell, $obj); ?>
    </div>
<?php } ?>