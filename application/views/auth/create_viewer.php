<?php $this->load->view('shared/header_mobile', $this->data); ?>

<div data-role="page" id="page">
    <div data-role="header" data-position="inline" data-theme="d">
        <img src="/application/images/logo.jpg" width="80px" data-position="inline" style="float: left; margin: 5px;"/>
        <h1>Register</h1>
    </div>

    <h2>Create Account</h2>
    <p>Please enter your information below.</p>

    <div class="infoMessage"><?php echo $message; ?></div>

    <?php echo form_open("auth/create_viewer", 'data-ajax="false"'); ?>
    <p>Email:<br />
        <?php echo form_input($email); ?>
    </p>

    <p>Password:<br />
        <?php echo form_input($password); ?>
    </p>

    <p>Confirm Password:<br />
        <?php echo form_input($password_confirm); ?>
    </p>


    <p><?php echo form_submit('submit', 'Create User'); ?></p>

    <?php echo form_close(); ?>
        Already have an account? <?php echo anchor('/auth/login_mobile/', "Login") ?>
    </div>

<?php $this->load->view('shared/footer_mobile', $this->data); ?>
