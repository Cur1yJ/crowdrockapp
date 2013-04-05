<?php $this->load->view('shared/header_mobile', $this->data); ?>

<div data-role="page" id="page">
    <div data-role="header" data-position="inline" data-theme="d">
        <img src="/application/images/logo.jpg" width="80px" data-position="inline" style="float: left; margin: 5px;"/>
        <h1>Forgot Password</h1>
    </div>
    <div class="pageTitleBorder"></div>

    <div class="infoMessage"><?php echo $message; ?></div>
    
    <p>Please enter your email address so we can send you an email to reset your password.</p>

<?php echo form_open("auth/forgot_password", 'data-ajax="false"');?>

      <p>Email Address:<br />
      <?php echo form_input($email);?>
      </p>

      <p><?php echo form_submit('submit', 'Submit');?></p>

<?php echo form_close();?>

    </div>
<?php $this->load->view('shared/footer_mobile', $this->data); ?>
