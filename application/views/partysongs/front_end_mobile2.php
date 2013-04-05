<?php $this->load->view('shared/header_mobile', $this->data); ?>

<div data-role="page" id="page">
    <div data-role="header" data-position="inline" data-theme="d">
        <a href="/parties/upcoming" data-role="none">
            <img src="/application/images/logo.jpg" width="80px" data-position="inline" style="float: left; margin: 0px;"/>
        </a>
        <h1><?php echo $party->title ?></h1>
    </div>

    <?php $this->load->view('shared/google_ad_mobile', $this->data); ?>

    <div data-role="content" id="song-list-container">
        <div id="flash-message">
            <?php echo $this->session->flashdata('message'); ?>
        </div>
        <ul data-role="listview" data-divider-theme="e" data-theme="a" id="listview-1" data-filter="true">

            <?php $this->load->view('partysongs/song_list_mobile', $this->data); ?>

        </ul>
        <a href="#add-song-page" data-role="button" data-mini="true">Request a Song</a>
    </div>

    <div data-role="footer" class="ui-state-persist" data-position="fixed">
        <div data-role="navbar">
            <ul class="ui-grid-b">
                <!-- li class="ui-block-a"><a href="index.html" data-role="button" data-iconpos="bottom" id="reload-button">Reload</a></li-->
                <li class="ui-block-a"><a href="<?php echo "/profile/leaderboard/" . $party->id ?>" data-role="button" data-iconpos="bottom" id="leaderboard-button" data-ajax="false">Leader Board</a></li>
                <li class="ui-block-b"><a href="<?php echo "/profile/mine" ?>" data-transition="flip" id="profile-button" data-ajax="false">Profile</a></li>
                <li class="ui-block-c"><a data-transition="flip" id="search-button">Search</a></li>
            </ul>
        </div>
    </div>

    <script type="text/javascript">
        function reload_party_list(){
            //alert("test");
            //location.reload(true);
            $.ajax({
                type: "GET",
                url: "/partysongs/reload_list/<?php echo $party->access_token; ?>",
                dataType: "script"
            });
        }

        $(function(){
            $(".ui-listview-filter.ui-bar-c").hide();

            $("#search-button").click(function(){
                $(".ui-listview-filter.ui-bar-c").toggle();
                if($(".ui-listview-filter.ui-bar-c").isVisible())
                    $(".ui-listview-filter.ui-bar-c input").focus();
            });

            $("#reload-button").click(function(){
                document.location.href = document.location.href;
                return false;
            });

            //setInterval(reload_party_list, 10000);

        });
    </script>

</div>

<div data-role="page" id="add-song-page">
    <?php $this->load->view('partysongs/new_mobile', $this->data); ?>
                <a data-rel="back" data-role="button">Back</a>
            </div>

<?php $this->load->view('shared/footer_mobile', $this->data); ?>

<div data-role="dialog" data-rel="dialog" id="info-dialog">
    <div data-role="header">
        <h1>CrowdRock</h1>
    </div>
    <div data-role="content">
        <div id="dialog-text">
            This is a test
        </div>
        <a href="#" data-role="button" data-rel="back" data-theme="b">Close</a>
    </div>
</div>

<script type="text/javascript">
    $(function(){
        if($.trim($("#flash-message").html()).length > 0){
            $("#dialog-text").html($("#flash-message").html());
            $("#flash-message").html("");
            $.mobile.changePage( $('#info-dialog'), { role: 'dialog', transition: 'pop'} );
        }
    })
</script>
