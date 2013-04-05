<h1>Organize party songs</h1>

<?php echo form_open("parties/update_songs/" . $party->id); ?>

<p>Songs:<br />
    <?php
    foreach ($songs as $song) {
        echo form_checkbox('song_ids[]', $song->id, in_array($song->id, $party_song_ids));
        echo $song->title;
        echo "<br/>";
    }
    ?>
</p>

<p><?php echo form_submit('submit', 'Submit'); ?></p>

<?php echo form_close(); ?>
