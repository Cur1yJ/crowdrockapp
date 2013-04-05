<h1>New Song</h1>

<?php echo form_open("partysongs/update/" . $party_song->id); ?>


<?php
if ($party_song->errors != null && sizeof($party_song->errors->full_messages()) > 0) {
    foreach ($party_song->errors->full_messages() as $error_message) {
        echo $error_message;
        echo "<br/>";
    }
}
?>

<?php $this->load->view('partysongs/song_fields', $this->data); ?>

<p><?php echo form_submit('submit', 'Submit'); ?></p>

<?php echo form_close(); ?>
