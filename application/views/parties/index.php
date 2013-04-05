<table cellpadding="2" cellspacing="0">
    <tr>
        <th>My Parties</th>
        <th>Locations</th>
        <th></th>
    </tr>
    <?php foreach ($parties as $party): ?>
        <tr>
            <td><?php echo $party->title; ?></td>
            <td><?php echo (double)$party->latitude . ", " . (double)$party->longitude; ?></td>
            <td>
                <?php echo anchor("parties/show/" . $party->id, 'Email My Playlist'); ?> |
                <?php echo anchor("parties/edit/" . $party->id, 'Edit Party Title'); ?> |
                <?php //echo anchor("parties/edit_songs/" . $party->id, 'Edit Songs'); ?>
                <?php echo anchor("partysongs/song_list/" . $party->id, 'Add/Edit Songs'); ?> |
                <?php echo anchor("partysongs/show/" . $party->id, 'DJ panel'); ?> |
                <?php echo anchor("parties/delete/" . $party->id, 'Delete', array('onclick' => "return confirm('Are you sure to delete this party?')")); ?>
            </td>
        </tr>
    <?php endforeach; ?>
            </table>

            <p><a href="<?php echo site_url('parties/new_party'); ?>">Start a party</a></p>
            