<?php $this->load->view('shared/header_mobile', $this->data); ?>

<?php

foreach ($party_songs as $party_song) {
    $status_class = $party_song->status == PartySong::$STATE_PLAYING ? "playing" : "not-playing";
    echo "<div class='party-song $status_class'>";
    echo anchor("/partysongs/up_vote/" . $party_song->id . "/token/" . $access_token . "/" . "/",
            "<span class='vote-button'>Up Vote</span>", "class='vote-up'");
    echo "<div class='title'>";
    echo $party_song->title;
    echo "<br/>";
    echo "<span class='vote-counts'>";
    if ($party_song->status != PartySong::$STATE_PLAYING) {
        echo anchor("/partysongs/play_request/" . $party_song->id . "/token/" . $access_token . "/" . "/",
                "Request to play", "class='round-button'");
        echo "<br/>";
    }
    echo "Vote +" . $party_song->up_vote_count;
    echo " / -" . $party_song->down_vote_count . ", ";
    echo "Req " . $party_song->play_request_count;
    echo "</span>";
    echo "</div>";
    echo anchor("/partysongs/down_vote/" . $party_song->id . "/token/" . $access_token . "/" . "/",
            "<span class='vote-button'>Down Vote</span>", "class='vote-down'");
    echo "<div class='clear'></div>";
    //echo anchor("/partysongs/up_vote/" . $party_song->id . "/token/" . $access_token . "/" . "/", "Up Vote");
    //echo " ";
    //echo anchor("/partysongs/down_vote/" . $party_song->id . "/token/" . $access_token . "/", "Down Vote");
    //echo " ";
    //echo anchor("/partysongs/play_request/" . $party_song->id . "/token/" . $access_token . "/", "Play Request");
    echo "</div>";
}
?>

<?php $this->load->view('shared/footer_mobile', $this->data); ?>

