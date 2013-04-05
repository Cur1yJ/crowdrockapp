<h2>Party songs</h2>

<?php echo anchor('/partysongs/song_list/' . $party->id, "Organize Song List") ?>
<br/><br/>
<div class="small-widget" style="float: left">
    <table cellpadding="2" cellspacing="0" width="100%">
        <tr>
            <th>Title</th>
            <th>
                #request<br/>
                up/down/play
            </th>
            <th>&nbsp;</th>
        </tr>
        <?php
        foreach ($party_songs as $party_song) {
            echo "<tr>";
            echo "<td>" . $party_song->title . "</td>";

            echo "<td>";
            echo $party_song->up_vote_count . "/";
            echo $party_song->down_vote_count . "/";
            echo $party_song->play_request_count;
            echo "</td>";

            echo "<td>";
            if ($party_song->status != PartySong::$STATE_PLAYING)
                echo anchor('/partysongs/mark_as_playing/' . $party_song->id, "Mark as Playing");
            else
                echo "Now playing";
            echo "</td>";

            echo "</tr>";
        }
        ?>
    </table>
</div>