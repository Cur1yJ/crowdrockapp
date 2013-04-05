<?php $this->load->view('shared/header', $this->data); ?>

<h1><?php echo $party->title ?></h1>
<hr/>

<p>
    Songs:<br />
</p>

<table cellpadding="2" cellspacing="0">
    <tr>
        <th>Title</th>
        <th></th>
        <th></th>
    </tr>
    <?php
    foreach ($party_songs as $party_song) {
        echo "<tr>";
        echo "<td>" . $party_song->title . "</td>";

        echo "<td>";
        if ($party_song->status == PartySong::$STATE_PLAYING)
            echo "Now playing";
        else
            echo "&nbsp";
        echo "</td>";

        echo "<td>";
        echo anchor("/partysongs/up_vote/" . $party_song->id . "/token/" . $access_token . "/" . "/", "Up Vote");
        echo " ";
        echo anchor("/partysongs/down_vote/" . $party_song->id . "/token/" . $access_token . "/", "Down Vote");
        echo " ";
        echo anchor("/partysongs/play_request/" . $party_song->id . "/token/" . $access_token . "/", "Play Request");
        echo "</td>";

        echo "</tr>";
    }
    ?>
</table>

<?php $this->load->view('shared/footer', $this->data); ?>

