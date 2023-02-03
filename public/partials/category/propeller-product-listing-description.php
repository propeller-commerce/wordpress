<?php if (!empty($data->description[0]->value)) { ?>    
    <div class="row mt-4">
        <div class="col-12">
            <div class="category-description">
                <?php echo esc_html($data->description[0]->value); ?>
            </div>
        </div>
    </div>
<?php } ?>