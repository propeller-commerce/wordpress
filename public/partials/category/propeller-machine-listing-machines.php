<?php foreach ($machines as $machine) { 
    if (!count($machine->slug)) // skip products without slug, probably not translated
        continue;
?>
    <div class="propeller-list-item col-12 col-sm-6 col-xl-4">
        <?php echo apply_filters('propel_machine_card', $machine, $obj); ?>
    </div>
<?php } ?>

<?php if ($parts->itemsFound > 0) {
    foreach ($parts->items as $part) { 
?>
    <div class="propeller-list-item col-12 col-sm-6 col-xl-4">
        <?php echo apply_filters('propel_product_card', $part->product, $obj); ?>
    </div>
<?php } } ?>