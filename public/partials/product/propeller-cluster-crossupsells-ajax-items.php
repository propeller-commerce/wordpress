<?php foreach($cluster->crossupsells as $crossupsell) { ?>
    <div>
        <?= apply_filters('propel_cluster_crossupsell_card', $crossupsell, $obj); ?>
    </div>
<?php } ?>