<h1>Edit Song</h1>

<?php echo form_open("songs/update/" . $song->id); ?>

<?php
if ($song->errors != null && sizeof($song->errors->full_messages()) > 0) {
    foreach ($song->errors->full_messages() as $error_message) {
        echo $error_message;
        echo "<br/>";
    }
}
?>

<?php $this->load->view('songs/song_fields', $this->data); ?>

<p><?php echo form_submit('submit', 'Submit'); ?></p>

<?php echo form_close(); ?>
