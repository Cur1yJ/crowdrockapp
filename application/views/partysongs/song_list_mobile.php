<!-- Currently playing song-->
<?php $party_song = $currently_playing_song; ?>

<?php if($currently_playing_song != null){ ?>

<li data-theme="c" class='sresult <?php if ($party_song->status == PartySong::$STATE_PLAYING)
                    echo 'playing' ?>'>
                <div data-type="horizontal" >
                    <?php if ($party_song->status == PartySong::$STATE_PLAYING) {
 ?>
                        <img src="/application/images/playing.png" style="vertical-align: top; float: left;" width="20"/>
                       <?php } else {
 ?>
                     <a class="li-a-left" href="<?php echo "/partysongs/up_vote/" . $party_song->id . "/token/" . $access_token . "/" ?>"
                        data-role="button" data-mini="true" data-theme="g" data-inline="true" data-ajax="false">Like</a>
                       <?php } ?>
<?php if ($party_song->status != PartySong::$STATE_PLAYING) { ?>
                     <a class="li-a-right" href="<?php echo "/partysongs/down_vote/" . $party_song->id . "/token/" . $access_token . "/" ?>"
                        data-role="button" data-mini="true" data-iconpos="left" data-theme="g" data-ajax="false">Dislike</a>
<?php } ?>
                    <div class="li-des" href="#">
                        <img src="/application/images/song.jpeg" class="ui-li-thumb" width="100"/>
                        <h1><?php echo $party_song->title ?></h1>
                        <p>
<?php echo $party_song->artist ?> <br/>
                            +<?php echo $party_song->up_vote_count ?> / -<?php echo $party_song->down_vote_count ?>
                            <br/>
                            <?php
                                echo $party_song->requester_names();
                            ?>
                        </p>
                    </div>
                    <div style="clear:  both;"></div>

                </div>
            </li>
<?php } ?>
<!-- Currently playing song end -->

<!-- other songs -->
<?php foreach ($party_songs as $party_song) {
 ?>

                <li data-theme="c" class='sresult <?php if ($party_song->status == PartySong::$STATE_PLAYING)
                    echo 'playing' ?>'>
                <div data-type="horizontal" >
                    <?php if ($party_song->status == PartySong::$STATE_PLAYING) {
 ?>
                        <img src="/application/images/playing.png" style="vertical-align: top; float: left;" width="20"/>
                       <?php } else {
 ?>
                     <a class="li-a-left" href="<?php echo "/partysongs/up_vote/" . $party_song->id . "/token/" . $access_token . "/" ?>"
                        data-role="button" data-mini="true" data-theme="g" data-inline="true" data-ajax="false">Like</a>
                       <?php } ?>
<?php if ($party_song->status != PartySong::$STATE_PLAYING) { ?>
                     <a class="li-a-right" href="<?php echo "/partysongs/down_vote/" . $party_song->id . "/token/" . $access_token . "/" ?>"
                        data-role="button" data-mini="true" data-iconpos="left" data-theme="g" data-ajax="false">Dislike</a>
<?php } ?>
                    <div class="li-des" href="#">
                        <img src="/application/images/song.jpeg" class="ui-li-thumb" width="100"/>
                        <h1><?php echo $party_song->title ?></h1>
                        <p>
                        <?php echo $party_song->artist ?> <br/>
                        <span class="vote-counts">+<?php echo $party_song->up_vote_count ?> / -<?php echo $party_song->down_vote_count ?></span>
                        </p>
                    </div>
                    <div style="clear:  both;"></div>

                </div>
            </li>
<?php } ?>
