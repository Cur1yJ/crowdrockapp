<?php $this->load->view('shared/header_mobile', $this->data); ?>

<div data-role="page" id="page">
    <div data-role="header" data-position="inline" data-theme="d">
        <img src="/application/images/logo.jpg" width="80px" data-position="inline" style="float: left; margin: 5px;"/>
        <h1>CrowdRock Profile</h1>
    </div>

    <p>Email:
        <?php echo $user->email; ?>
    </p>

    <p>
        Total earned points in all parties:
        <b><?php echo $total_points; ?></b>
        <br/>
        Total up votes:
        <b>+<?php echo $up_vote_count; ?></b>, 
        Total down votes:
        <b>-<?php echo $down_vote_count; ?></b>
    </p>

    <b>Requested most recent songs</b> (top 3)
    <table cellpadding="0" cellspacing="0" width="90%">
        <tr>
            <td>Song (#up/#down votes)</td>
            <td>Vote</td>
            <td>Party</td>
        </tr>
        <?php
        foreach ($most_recent_votes as $participant_request) {
            echo "<tr>";
            echo "<td>" . $participant_request->party_song->title .
                    " (+" . $participant_request->party_song->up_vote_count . "/-" .
                    $participant_request->party_song->down_vote_count . ")" . "</td>";
            echo "<td>" . $participant_request->friendly_vote_title() . "</td>";
            echo "<td>" . $participant_request->party->title . "</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <br/>

    <b>Requested top ranked songs</b> (top 3)
    <br/>
    <table cellpadding="0" cellspacing="0" width="90%">
        <tr>
            <td>Song (#up/#down votes)</td>
            <td>Vote</td>
            <td>Party</td>
        </tr>
        <?php
        foreach ($top_ranked_votes as $participant_request) {
            echo "<tr>";
            echo "<td>" . $participant_request->party_song->title .
                    " (+" . $participant_request->party_song->up_vote_count . "/-" .
                    $participant_request->party_song->down_vote_count . ")" . "</td>";
            echo "<td>" . $participant_request->friendly_vote_title() . "</td>";
            echo "<td>" . $participant_request->party->title . "</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <p>
        <a href="#points-page" data-role="button" data-mini="true" data-inline="true">Details</a>
        <a href="<?php echo Party::find($this->session->userdata('current_party_id'))->public_url() ?>" data-role="button" data-mini="true" data-inline="true">Back to party</a>
    </p>

    <div data-role="footer" class="ui-state-persist" data-position="fixed">
        <div data-role="navbar">
            <ul class="ui-grid-b">
                <li class="ui-block-a"><a href="<?php echo "/profile/viewer_profile/" . $user->id ?>" data-role="button" data-iconpos="bottom" id="reload-button" data-ajax="false">Reload</a></li>
                <li class="ui-block-b"><a href="<?php echo "/profile/mine" ?>" data-transition="flip" id="search-button" data-ajax="false">Profile</a></li>
                <li class="ui-block-b"><a href="<?php echo "/parties/upcoming/" ?>" data-ajax="false">Parties</a></li>
            </ul>
        </div>
    </div>

</div>

<div data-role="page" id="points-page">
    <div data-role="header" data-position="inline" data-theme="d">
        <img src="/application/images/logo.jpg" width="80px" data-position="inline" style="float: left; margin: 5px;"/>
        <h1>CrowdRock Points</h1>
    </div>

    <table cellpadding="0" cellspacing="0" width="95%">
        <tr>
            <td>Reason</td>
            <td>Point</td>
            <td>Accumulated on</td>
        </tr>
        <?php
        foreach ($earned_points as $point) {
            echo "<tr>";
            echo "<td>" . $point->reason . "</td>";
            echo "<td>" . $point->point . "</td>";
            echo "<td>" . $point->created_at->format('Y-m-d') . "</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <a href="#" data-role="button" data-mini="true" data-inline="true" data-rel="back">Back</a>
    <a href="<?php echo Party::find($this->session->userdata('current_party_id'))->public_url() ?>" data-role="button" data-mini="true" data-inline="true">Back to party</a>

    <div data-role="footer" class="ui-state-persist" data-position="fixed">
        <div data-role="navbar">
            <ul class="ui-grid-b">
                <li class="ui-block-a"><a href="<?php echo "/profile/mine" ?>" data-role="button" data-iconpos="bottom" id="reload-button" data-ajax="false">Reload</a></li>
                <li class="ui-block-b"><a href="<?php echo "/profile/mine" ?>" data-transition="flip" id="search-button" data-ajax="false">Profile</a></li>
                <li class="ui-block-b"><a href="<?php echo "/parties/upcoming/" ?>" data-ajax="false">Parties</a></li>
            </ul>
        </div>
    </div>

</div>

<?php $this->load->view('shared/footer_mobile', $this->data); ?>
