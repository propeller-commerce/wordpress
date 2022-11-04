<?php
use Propeller\Includes\Controller\SessionController;
use Propeller\Includes\Controller\UserController;
use Propeller\Includes\Controller\PageController;
use Propeller\Includes\Enum\PageType;

?>
<div class="container-fluid propeller-login-wrapper">
    <div class="row">
        <div class="col-12 mx-auto">
            <form name="login" class="form-handler login-form page-login-form" method="post">
                <input type="hidden" name="action" value="reset_password">
                <fieldset class="personal">
                    <div class="row form-group">
                        <div class="col-form-fields col-12">
                            <div class="form-row">
                                <div class="col-12 col-md-8 form-group col-user-password">
                                    <label class="form-label" for="field_password"><?php echo __('New password', 'propeller-ecommerce'); ?>*</label>
                                    <input type="password" name="user_password" value="" placeholder="<?php echo __('New password', 'propeller-ecommerce'); ?>*" class="form-control required" id="field_password" minlength="8">
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-form-fields col-12">
                            <div class="form-row">
                                <div class="col-12 col-md-8 form-group col-user-password">
                                    <label class="form-label" for="field_password_verify"><?php echo __('Repeat new password*', 'propeller-ecommerce'); ?>*</label>
                                    <input type="password" name="user_password_verify" value="" placeholder="<?php echo __('Repeat new password*', 'propeller-ecommerce'); ?>*" class="form-control required" id="field_password_verify" minlength="8">
                                </div>
                            </div>  
                        </div>
                    </div>
                   
                </fieldset>
                <div class="row form-group form-group-submit">
                    <div class="col-form-fields col-12 col-md-8">
                        <div class="form-row align-items-center">
                            <div class="col-12 col-md-auto">
                                <input type="submit" class="btn-blue btn-proceed" value="<?php echo __('Set up and login', 'propeller-ecommerce'); ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div> 
</div>
<?php require $this->load_template('partials', DIRECTORY_SEPARATOR . 'other' . DIRECTORY_SEPARATOR . 'propeller-toast.php'); ?>