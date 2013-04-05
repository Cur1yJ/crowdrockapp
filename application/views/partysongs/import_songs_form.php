<h1>Import Songs</h1>

<?php echo form_open_multipart("partysongs/import/" . $party->id); ?>

<?php echo form_upload('file'); ?>
&nbsp;&nbsp;&nbsp;
<?php echo form_submit('submit', 'Import'); ?>

<?php echo form_close(); ?>
