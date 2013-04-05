<?php $this->load->view('shared/header', $this->data); ?>

<div class='mainInfo'>

	<h1>Edit User</h1>
	<p>Please enter the users information below.</p>
	
	<div class="infoMessage"><?php echo $message;?></div>
	
    <?php echo form_open("profile/update/".$this->uri->segment(3));?>
      <p>First Name:<br />
      <?php echo form_input($first_name);?>
      </p>
      
      <p>Last Name:<br />
      <?php echo form_input($last_name);?>
      </p>
      
      <p>Company Name:<br />
      <?php echo form_input($company);?>
      </p>
      
      <p>Email:<br />
      <?php echo form_input($email);?>
      </p>
      
      <p>Phone:<br />
      <?php echo form_input($phone1);?>-<?php echo form_input($phone2);?>-<?php echo form_input($phone3);?>
      </p>
      
      <p><?php echo form_submit('submit', 'Submit');?></p>

      
    <?php echo form_close();?>

</div>

<?php $this->load->view('shared/footer', $this->data); ?>
