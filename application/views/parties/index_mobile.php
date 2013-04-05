<?php $this->load->view('shared/header_mobile', $this->data); ?>
<h2>Upcoming parties</h2>

<div data-role="page" id="page">
    <div data-role="header" data-position="inline" data-theme="d">
        <a href="/parties/upcoming" data-role="none">
            <img src="/application/images/logo.jpg" width="80px" data-position="inline" style="float: left; margin: 0px;"/>
        </a>
        <h1>Parties</h1>
    </div>

    <div data-role="content">
        <p>
            <?php //echo form_submit('submit', 'Find Parties Nearby', 'class="round-button" id="find-parties-nearby" onclick="searchnearby(); return false;"'); ?>
            <a href="#" onclick="searchnearby(); return false;" data-role="button" data-ajax="false">Find Parties Nearby</a>
        </p>
        <ul data-role="listview" data-divider-theme="e" data-theme="a" id="listview-1" data-filter="true">
            <?php foreach ($parties as $party){ ?>
                <?php $distance = "Unknown distance"; ?>
                <?php if(isset($party->distance) || property_exists($party, 'distance')){ ?>
                    <?php $distance = "" . round($party->distance, 3) . " KM" ?>
                <?php } ?>
                <li data-theme="c" class='sresult'>
                    <?php echo anchor($party->public_url(), $party->title . "($distance)"); ?>
                </li>
            <?php } ?>
            </ul>
        </div>
    </div>    

        <!--p><a href="<?php echo site_url('parties/new_party'); ?>">Add a new party</a></p-->

<?php $this->load->view('shared/footer_mobile', $this->data); ?>
<?php $this->load->view('shared/geo_location'); ?>