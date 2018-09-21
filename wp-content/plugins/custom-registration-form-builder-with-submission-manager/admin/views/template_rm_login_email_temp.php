<?php $params= $data->params; ?>
<div class="rmagic">
    <div class="operationsbar">
        <div class="rmtitle"><?php echo _e('Email Templates', 'custom-registration-form-builder-with-submission-manager'); ?></div>
    </div>

    <!--Dialogue Box Starts-->
    <div class="rmcontent">
        <?php
        $form = new RM_PFBC_Form("login-email-temp");

        $form->configure(array(
            "prevent" => array("bootstrap", "jQuery"),
            "action" => ""
        ));
        
        $form->addElement(new Element_HTML('<div class="rmrow"><h3>'.__('Emails to User', 'custom-registration-form-builder-with-submission-manager').'</h3></div>'));
        $form->addElement(new Element_TinyMCEWP("<b>" . __('Failed Login Attempt', 'custom-registration-form-builder-with-submission-manager') . "</b>", $params['failed_login_err'],"failed_login_err", array('editor_class' => 'rm_TinyMCE', 'editor_height' => '100px'), array("longDesc" => __('The contents of the email sent to the user when their username or email is used during a failed login attempt.', 'custom-registration-form-builder-with-submission-manager'))));
        $form->addElement(new Element_HTML('<div style="display:none;">'));
        $form->addElement(new Element_TinyMCEWP("<b>" . __('One Time Password', 'custom-registration-form-builder-with-submission-manager') . "</b>",$params['otp_message'] , "otp_message", array('editor_class' => 'rm_TinyMCE', 'editor_height' => '100px'), array("longDesc" => __('The contents of the email sent to the user with OTP during 2FA and non user account based logins.', 'custom-registration-form-builder-with-submission-manager'))));
        $form->addElement(new Element_HTML('</div>'));
        
        $form->addElement(new Element_HTML('<div class="rmrow"><h3>'.__('Emails to Admin', 'custom-registration-form-builder-with-submission-manager').'</h3></div>'));
        $form->addElement(new Element_TinyMCEWP("<b>" . __('Failed Login Attempt', 'custom-registration-form-builder-with-submission-manager') . "</b>", $params['failed_login_err_admin'], "failed_login_err_admin", array('editor_class' => 'rm_TinyMCE', 'editor_height' => '100px'), array("longDesc" => __('Define the contents of the notification email sent to admin whenever RegistrationMagic detects a failed login attempt.', 'custom-registration-form-builder-with-submission-manager'))));
        $form->addElement(new Element_HTML('<div style="display:none;">'));
            $form->addElement(new Element_TinyMCEWP("<b>" . __('IP Blocked', 'custom-registration-form-builder-with-submission-manager') . "</b>", $params['ban_message_admin'], "ban_message_admin", array('editor_class' => 'rm_TinyMCE', 'editor_height' => '100px'), array("longDesc" => __('Define the contents of the notification email sent to the admin whenever RegistrationMagic blocks an IP based on security configuration set in its settings.', 'custom-registration-form-builder-with-submission-manager'))));
        $form->addElement(new Element_HTML('</div>'));
        
       
        $form->addElement(new Element_HTMLL('&#8592; &nbsp; '.__('Cancel','custom-registration-form-builder-with-submission-manager'), '?page=rm_login_sett_manage', array('class' => 'cancel')));
        $form->addElement(new Element_Button(RM_UI_Strings::get('LABEL_SAVE'), "submit", array("id" => "rm_submit_btn", "class" => "rm_btn", "name" => "submit")));
        $form->render();
        ?>
    </div>
</div>
