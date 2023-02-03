<div class="col-12 col-md-6 mb-4">
    <div class="address-box">
        <div class="row">
            <div class="col-12">
                <div class="user-addr-details">
                    <span><?php echo __('Password:', 'propeller-ecommerce'); ?></span>********<br>
                    <span>
                        <?php 
                            if($user->mailingList == 'Y')
                                echo __('You are subscribed to our newsletter', 'propeller-ecommerce');
                            else
                                echo __('You are not subscribed to our newsletter', 'propeller-ecommerce');
                        ?>
                    </span><br>
                </div>
            </div>
        </div>
        <div class="row address-links">
            <div class="col-12">
                <a class="address-edit open-modal-form" 
                    data-form-id="edit_address<?php echo esc_attr($user->userId); ?>"
                    data-title="<?php echo __('Password and newsletter', 'propeller-ecommerce'); ?>"
                    data-target="#change_pwd_modal"
                    data-toggle="modal"
                    role="button">
                    <?php echo __('Modify', 'propeller-ecommerce'); ?>
                </a>
            </div>
        </div>
    </div>
</div>

<?php require $this->load_template('partials', DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . 'propeller-account-change-password.php'); ?>