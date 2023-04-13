<?php
    use Propeller\Includes\Controller\PageController;
    use Propeller\Includes\Enum\PageType;
?>
<div class="container-fluid propeller-login-wrapper">
    <div class="row">
        <div class="col-12 mx-auto">
            <form name="login" class="form-handler login-form page-login-form" method="post">
                <input type="hidden" name="action" value="forgot_password">
                <fieldset class="personal">
                    <div class="row form-group">
                        <div class="col-form-fields col-12">
                            <div class="form-row">
                                <div class="col-12 col-md-8 form-group col-user-mail">
                                    <label class="form-label" for="field_username"><?php echo __('E-mail address', 'propeller-ecommerce'); ?>*</label>
                                    <input type="text" name="user_mail" value="" placeholder="<?php echo __('E-mail address', 'propeller-ecommerce'); ?>*" class="form-control required" id="field_username">
                                    <span class="input-user-message"></span>
                                </div>
                            </div>  
                        </div>
                    </div>
                   
                </fieldset>
                <div class="row form-group form-group-submit">
                    <div class="col-form-fields col-12 col-md-8">
                        <div class="form-row align-items-center">
                            <div class="col-auto">
                                <input type="submit" class="btn-blue btn-proceed" value="<?php echo __('Send', 'propeller-ecommerce'); ?>">
                            </div>
                            <div class="col">
                                <a href="<?php echo esc_url($this->buildUrl('', PageController::get_slug(PageType::LOGIN_PAGE))); ?>" class="btn-forgot-password"><?php echo __('Cancel', 'propeller-ecommerce'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div> 
</div>
<?php require $this->load_template('partials', DIRECTORY_SEPARATOR . 'other' . DIRECTORY_SEPARATOR . 'propeller-toast.php'); ?>