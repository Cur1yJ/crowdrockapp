<h1>Party details</h1>

<p>Title: 
    <?php echo $party->title; ?>
</p>

<p>Public url:
    <?php echo anchor($party->public_url(), $party->public_url(), array('target' => '_blank')); ?>
</p>

<?php $this->load->view('parties/email_address_form', $this->data); ?>
