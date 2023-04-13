<?php

use Propeller\Includes\Controller\FlashController;
use Propeller\Includes\Controller\PageController;
    use Propeller\Includes\Enum\PageType;
    use Propeller\Includes\Controller\UserController;
?>
<div class="container-fluid propeller-login-wrapper">
    <div class="row">
        <div class="col-12 mx-auto">
            <?php if ( UserController::is_logged_in() ) { ?>
                <div><?php echo __("You are already logged in",'propeller-ecommerce'); ?></div>
            <?php  } else { ?>
            <form name="login" class="form-handler login-form page-login-form" method="post">
                <input type="hidden" name="action" value="do_login">
                <?php if (FlashController::get('referrer')) { ?>
                    <input type="hidden" name="referrer" value="<?php echo esc_url(FlashController::flash('referrer')); ?>">
                <?php } ?>
                
                <fieldset class="personal">
                    <div class="row form-group">
                        <div class="col-form-fields col-12">
                            <div class="form-row">
                                <div class="col-12 col-md-8 form-group col-user-mail form-group-input">
                                    <label class="form-label" for="field_username"><?php echo __('E-mail address', 'propeller-ecommerce'); ?>*</label>
                                    <input type="email" name="user_mail" value="" placeholder="<?php echo __('E-mail address', 'propeller-ecommerce'); ?>*" class="form-control required email" id="field_username">
                                    <span class="input-user-message"></span>
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-form-fields col-12">
                            <div class="form-row">
                            <div class="col-12 col-md-8 form-group col-user-password form-group-input">
                                    <label class="form-label" for="field_password"><?php echo __('Password', 'propeller-ecommerce'); ?>*</label>
                                    <input type="password" name="user_password" value="" placeholder="<?php echo __('Password', 'propeller-ecommerce'); ?>*" class="form-control required" id="field_password" minlength="6">
                                    <span class="input-pass-message"></span>
                                </div>
                            </div>  
                        </div>
                    </div>
                    <!-- <div class="row form-group">
                        <div class="col-form-fields col-12">
                            <div class="form-row">
                                <div class="col-12 col-md-8 form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="save_password" value="Y" title="<?php echo __('Stay logged in', 'propeller-ecommerce'); ?>">
                                        <span><?php echo __('Stay logged in ', 'propeller-ecommerce'); ?></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>  -->
                </fieldset>
                <div class="row form-group form-group-submit">
                    <div class="col-form-fields col-12">
                        <div class="form-row align-items-center">
                            <div class="col-auto">
                                <input type="submit" class="btn-blue btn-proceed" value="<?php echo __('Log in', 'propeller-ecommerce'); ?>">
                            </div>
                            <div class="col">
                                <a href="<?php echo esc_url($this->buildUrl('', PageController::get_slug(PageType::FORGOT_PASSWORD_PAGE))); ?>" class="btn-forgot-password"><?php echo __('Forgot password?', 'propeller-ecommerce'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <?php } ?>
        </div>
    </div> 
</div>
<?php require $this->load_template('partials', DIRECTORY_SEPARATOR . 'other' . DIRECTORY_SEPARATOR . 'propeller-toast.php'); ?>