<div>
    <h2>Last 10 Minute Summary</h2>
    <?php echo anchor('/partysongs/update_playlist_ranking/' . $party->id, "Update playlist ranking"); ?>
    <br/><br/>

    <div class="small-widget" style="float: left">
        <div class="title">Most popular suggestion</div>
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <th>Song</th>
                <th>#rank</th>
            </tr>
            <?php
            foreach ($popular_party_songs as $party_song) {
                echo "<tr>";
                echo "<td>";
                echo $party_song->title;
                echo "</td>";
                echo "<td style='text-align: center;'>";
                echo $party_song->rank;
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>

    <div class="clear"></div>
    
    <div class="small-widget" style="float: left">
        <div class="title">Play Requests</div>
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <th>Song</th>
                <th>#votes</th>
            </tr>
            <?php
            foreach ($play_request_summary as $summary) {
//                print_r($summary);
                $party_song = PartySong::find($summary->party_song_id);
                echo "<tr>";
                echo "<td>";
                echo $party_song->title;
                echo "</td>";
                echo "<td style='text-align: center;'>";
                echo $summary->request_count;
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>

    <div class="clear"></div>
    
    <div class="small-widget" style="float: left">
        <div class="title">Up Votes</div>
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <th>Song</th>
                <th>#votes</th>
            </tr>
            <?php
            foreach ($up_vote_summary as $summary) {
//                print_r($summary);
                $party_song = PartySong::find($summary->party_song_id);
                echo "<tr>";
                echo "<td>";
                echo $party_song->title;
                echo "</td>";
                echo "<td style='text-align: center;'>";
                echo $summary->request_count;
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>

    <div class="clear"></div>
    
    <div class="small-widget" style="float: left">
        <div class="title">Down Votes</div>
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <th>Song</th>
                <th>#votes</th>
            </tr>
            <?php
            foreach ($down_vote_summary as $summary) {
//                print_r($summary);
                $party_song = PartySong::find($summary->party_song_id);
                echo "<tr>";
                echo "<td>";
                echo $party_song->title;
                echo "</td>";
                echo "<td style='text-align: center;'>";
                echo $summary->request_count;
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>

</div>