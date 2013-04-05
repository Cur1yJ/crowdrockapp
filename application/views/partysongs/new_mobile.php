<h1>Request a Song</h1>

<?php echo form_open("partysongs/create/" . $party_song->party_id . "?client=mobile"); ?>


<?php
if ($party_song->errors != null && sizeof($party_song->errors->full_messages()) > 0) {
    foreach ($party_song->errors->full_messages() as $error_message) {
        echo $error_message;
        echo "<br/>";
    }
}
?>

<?php $this->load->view('partysongs/song_fields_mobile', $this->data); ?>

<p><?php echo form_submit('submit', 'Submit'); ?></p>

<?php echo form_close(); ?>
