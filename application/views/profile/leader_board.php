<?php $this->load->view('shared/header_mobile', $this->data); ?>

<div data-role="page" id="page">
    <div data-role="header" data-position="inline" data-theme="d">
        <img src="/application/images/logo.jpg" width="80px" data-position="inline" style="float: left; margin: 5px;"/>
        <h1>CrowdRock Leaderboard</h1>
    </div>

    <b>Leader Board of <?php echo $party->title ?> </b>
    <?php if ($leader_points == null || sizeof($leader_points) == 0) {
        echo "<br/><br/>No Leader in this party yet." ?>
    <?php } else {
    ?>

<!--        <p>Email:
        <?php //echo $user->email; ?>
    </p>

    <p>Total up votes:
        <?php //echo $up_vote_count; ?>
    </p>

    <p>Total down votes:
        <?php //echo $down_vote_count; ?>
    </p>

    <p>Total earned points in this party:
        <?php //echo $total_party_points; ?>a href="#points-page" data-role="button" data-mini="true" data-inline="true">Details</a
    </p>

    <p>Total earned points in all parties:
        <?php //echo $total_earned_points; ?>
    </p>-->
    <?php } ?>

    <table cellpadding="0" cellspacing="0" width="90%">
        <tr>
            <td>Name</td>
            <td>Email</td>
            <td>Point</td>
        </tr>
        <?php
        foreach ($leader_points as $leader_point) {
            echo "<tr>";
            echo "<td>" . $leader_point->first_name . " " . $leader_point->last_name . "</td>";
            echo "<td>" . $leader_point->email . "</td>";
            echo "<td>" . $leader_point->total_points . "</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <div data-role="footer" class="ui-state-persist" data-position="fixed">
        <div data-role="navbar">
            <ul class="ui-grid-b">
                <li class="ui-block-a"><a href="<?php echo "/profile/leaderboard/" ?>" data-role="button" data-iconpos="bottom" id="reload-button" data-ajax="false">Reload</a></li>
                <li class="ui-block-b"><a href="<?php echo "/profile/mine" ?>" data-transition="flip" id="profile-button" data-ajax="false">Profile</a></li>
                <li class="ui-block-b"><a href="<?php echo "/parties/upcoming/" ?>" data-ajax="false">Parties</a></li>
            </ul>
        </div>
    </div>

        <a href="<?php echo Party::find($this->session->userdata('current_party_id'))->public_url() ?>" data-role="button" data-mini="true" data-inline="true">Back to party</a>
        
</div>

<?php $this->load->view('shared/footer_mobile', $this->data); ?>

<script type="text/javascript">
    $("#reload-button").click(function(){
        document.location.href = document.location.href;
        return false;
    });
</script>