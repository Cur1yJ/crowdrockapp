<?php $this->load->view('shared/header', $this->data); ?>

<div class='mainInfo'>

    <h1>DJ Profile</h1>

    <p>First Name:
        <?php echo $user->first_name; ?>
    </p>

    <p>Last Name:
        <?php echo $user->last_name; ?>
    </p>

    <p>Company Name:
        <?php echo $user->company; ?>
    </p>

    <p>Email:
        <?php echo $user->email; ?>
    </p>

    <p>Phone:
        <?php echo $user->phone; ?>
    </p>
    <?php
        if ($this->ion_auth->user()->row()->id == $user->id)
            echo anchor('/profile/edit', "Edit Profile")
    ?>

        </div>

<?php $this->load->view('shared/footer', $this->data); ?>
