<?php $this->load->view('shared/header', $this->data); ?>

<?php echo form_open("parties/search/", "method=get"); ?>

<p>
    <?php echo form_input('query', $_GET['query']); ?>
    <?php echo form_submit('submit', 'Search'); ?>
</p>

<p></p>

<?php echo form_close(); ?>

<table cellpadding="2" cellspacing="0" width="70%">
        <tr>
            <th>Name</th>
            <th>Organizer</th>
            <th>Address</th>
            <th>Distance</th>
        </tr>
    <?php foreach ($parties as $party): ?>
        <tr>
            <td><?php echo $party->title; ?></td>
            <td><?php echo $party->organizer->name; ?></td>
            <td><?php echo $party->address; ?></td>
            <td>
                <?php if(isset($party->distance) || property_exists($party, 'distance')){ ?>
                <a href="<?php echo "http://maps.google.com/maps?z=50&t=m&q=loc:$party->latitude+$party->longitude"; ?>">
                    <?php echo round($party->distance, 3) ?> KM</a>
                <?php } else{ ?>
                    Unknown
                <?php } ?>
            </td>
    </tr>
    <?php endforeach; ?>
        </table>        

<?php $this->load->view('shared/footer', $this->data); ?>
