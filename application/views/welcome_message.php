<?php $this->load->view('shared/header', $this->data); ?>

<div class='mainInfo'>

    <img src="/application/images/future-crowdrock1.png" alt="crowdrock" style="vertical-align: middle;"/>
    <div style="display: inline-block; vertical-align: middle; ">
        <a class="round-button large" href="<?php echo site_url("/parties/new_party"); ?>">Create your Party</a>

        <?php echo form_open("parties/search/", "method=get"); ?>

        <p>
            <?php echo form_input('query', "", "size=13"); ?>
            <?php echo form_submit('submit', 'Find Parties', 'class=round-button'); ?>
        </p>
        <p>
            <?php echo form_submit('submit', 'Find Parties Nearby', 'class="round-button" id="find-parties-nearby" onclick="searchnearby(); return false;"'); ?>
        </p>

        <p></p>

        <?php echo form_close(); ?>
        </div>
    <div class="clear"></div>
    <?php $this->load->view('shared/steps', $this->data); ?>
    </div>
    <?php $this->load->view('shared/geo_location'); ?>
<?php $this->load->view('shared/footer', $this->data); ?>