<h1>New Party</h1>

<?php echo form_open("parties/create"); ?>


<?php
if ($party->errors != null && sizeof($party->errors->full_messages()) > 0) {
    foreach ($party->errors->full_messages() as $error_message) {
        echo $error_message;
        echo "<br/>";
    }
}
?>

<p>Title:<br />
    <?php echo form_input('title', $party->title); ?>
</p>

<h2>Location</h2>
<p>
    Address:<br />
    <?php echo form_input('address', $party->address); ?>
</p>

<p>
    Latitude:<br />
    <?php echo form_input('latitude', $party->latitude); ?>
</p>

<p>
    Longitude:<br />
    <?php echo form_input('longitude', $party->longitude); ?>
</p>

<p><?php echo form_submit('submit', 'Submit'); ?></p>

<?php echo form_close(); ?>
