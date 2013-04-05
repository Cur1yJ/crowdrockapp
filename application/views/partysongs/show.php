<?php $this->load->view('shared/header', $this->data); ?>

<h2><?php echo $party->title ?></h2>

<div style="float: left;">
    <?php $this->load->view('partysongs/songs', $this->data); ?>
    <div class="clear"></div>
</div>

<div style="float: left; margin-left: 50px;">
    <?php $this->load->view('partysongs/last_minute_summary', $this->data); ?>
    <div class="clear"></div>
</div>

<div class="clear"></div>

<?php $this->load->view('shared/footer', $this->data); ?>
