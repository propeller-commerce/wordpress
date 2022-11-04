<div class="col-12 col-md-6 mb-4">
    <div class="address-box">
        <div class="row">
            <div class="col-12">
                <div class="addr-title"><?php echo __('Personal details', 'propeller-ecommerce'); ?></div>
                <div class="user-addr-details">
                    <?= $obj->get_salutation($user) ?>
                    <?= $user->firstName; ?> <?= $user->lastName; ?><br>
                    <?= $user->email; ?><br>
                    <?= $user->phone; ?>
                </div>
            </div>
        </div>
    </div>
</div>