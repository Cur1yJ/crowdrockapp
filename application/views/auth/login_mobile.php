<?php $this->load->view('shared/header_mobile', $this->data); ?>

<div data-role="page" id="page">
    <div data-role="header" data-position="inline" data-theme="d">
        <img src="/application/images/logo.jpg" width="80px" data-position="inline" style="float: left; margin: 5px;"/>
        <h1>Login</h1>
    </div>
    <div class="pageTitleBorder"></div>

    <div class="infoMessage"><?php echo $message; ?></div>

    <?php echo form_open("auth/login_mobile", 'data-ajax="false"'); ?>

    <p>
        <label for="identity">Email/Username:</label>
        <?php echo form_input($identity); ?>
    </p>

    <p>
        <label for="password">Password:</label>
        <?php echo form_input($password); ?>
    </p>

    <!--p>
	      <label for="remember">Remember Me:</label>
    <?php echo form_checkbox('remember', '1', FALSE); ?>
                	  </p-->


        <p>
        <?php echo form_submit('submit', 'Login'); ?>
        <!--        <span style="text-align: center; display: inline-block; width: 100%">OR</span>-->
        <?php //echo anchor('/auth/login_as_anonymous/', "<b>Continue as anonymous(No login required)</b>", array('data-role' => 'button')) ?>
        <?php echo anchor('/auth/forgot_password/', "Forgot password?") ?>
    </p>    


    <?php echo form_close(); ?>
        Don't have an account yet? <?php echo anchor('/auth/create_viewer/', "Register here") ?>.
        <br/>OR<br/>
    <table>
        <tr>
          <td>
            <fb:login-button v="2" perms="" length="long" onlogin='window.location="https://graph.facebook.com/oauth/authorize?client_id=<?php echo $this->config->item('facebook_app_id'); ?>&redirect_uri=<?php echo site_url('auth_other/fb_signin'); ?>&amp;r="+window.location.href;'></fb:login-button>
          </td>
          <td>
              <a class="twitter" href="<?php echo site_url('auth_other/twitter_signin'); ?>">
                  <?php echo img('images/twitter_login_button.gif'); ?>
              </a>
          </td>
        </tr>
    </table>

    </div>

<script src="http://connect.facebook.net/en_US/all.js"></script>
<script type="text/javascript">
    FB.init({appId: "<?php echo $this->config->item('facebook_app_id'); ?>", status: true, cookie: true, xfbml: true});
    FB.Event.subscribe('auth.sessionChange', function(response) {
      if (response.session)
      {
          // A user has logged in, and a new cookie has been saved
          //window.location.reload(true);
      }
      else
      {
          // The user has logged out, and the cookie has been cleared
      }
    });
</script>
<?php $this->load->view('shared/footer_mobile', $this->data); ?>