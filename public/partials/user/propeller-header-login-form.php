<?php 
    use Propeller\Includes\Controller\PageController;
    use Propeller\Includes\Enum\PageType;
?>
<div class="row propeller-login-wrapper">
    <div class="col-12">
        <form name="login" class="form-handler login-form header-login-form" method="post">
            <input type="hidden" name="action" value="do_login">

            <?php 
                if ($_SERVER['REQUEST_URI'] != PageController::get_slug(PageType::LOGIN_PAGE)) { 
                    $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            ?>
            <input type="hidden" name="referrer" value="<?php echo esc_url($current_url); ?>">
            <?php } ?>

            <fieldset class="personal">
                <div class="row form-group">
                    <div class="col-form-fields col-12">
                        <div class="form-row">
                            <div class="col-12 form-group col-user-mail">
                                <label class="form-label" for="field_username"><?php echo __('E-mail address', 'propeller-ecommerce'); ?>*</label>
                                <input type="email" name="user_mail" placeholder="<?php echo __('E-mail address', 'propeller-ecommerce'); ?>*" value="" class="form-control required email" id="field_username">
                            </div>
                        </div>  
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-form-fields col-12">
                        <div class="form-row">
                            <div class="col-12 form-group col-user-password">
                                <label class="form-label" for="field_password"><?php echo __('Password', 'propeller-ecommerce'); ?>*</label>
                                <input type="password" name="user_password" placeholder="<?php echo __('Password', 'propeller-ecommerce'); ?>*" value="" class="form-control required" id="field_password" minlength="6">
                            </div>
                        </div>  
                    </div>
                </div>
                <!-- <div class="row form-group">
                    <div class="col-form-fields col-12">
                        <div class="form-row">
                            <div class="col-12 form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" name="save_password" value="Y" title="<?php echo __('Stay logged in', 'propeller-ecommerce'); ?>">
                                    <span><?php echo __('Stay logged in', 'propeller-ecommerce'); ?></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>  -->
            </fieldset>
            <div class="row form-group form-group-submit">
                <div class="col-form-fields col-12">
                    <div class="form-row">
                        <div class="col-12">
                            <input type="submit" class="btn-green btn-proceed" value="<?php echo __('Log in', 'propeller-ecommerce'); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="row form-group form-group-submit">
            <div class="col-form-fields col-12">
                <div class="form-row">
                    <div class="col-12">
                        <a href="<?php echo esc_url($this->buildUrl('', PageController::get_slug(PageType::FORGOT_PASSWORD_PAGE))); ?>" class="btn-proceed btn-forgot-password"><?php echo __('Forgot password', 'propeller-ecommerce'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 