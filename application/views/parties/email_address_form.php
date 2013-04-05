<div class="small-widget">
    <div class="title">Email party link to participants</div>

    <?php echo form_open("parties/email_party_link/" . $party->id); ?>

    <?php
    if ($party->errors != null && sizeof($party->errors->full_messages()) > 0) {
        foreach ($party->errors->full_messages() as $error_message) {
            echo $error_message;
            echo "<br/>";
        }
    }
    ?>

    <p>Email addresses:<br />
        Separate by comma<br />
        <?php echo form_textarea('email_addresses', $this->input->post('email_addresses')); ?>
    </p>

    <p><?php echo form_submit('submit', 'Email party link'); ?></p>

    <?php echo form_close(); ?>
</div>
