<table cellpadding="2" cellspacing="0">
    <tr>
        <th>Title</th>
        <th>Artist</th>
        <th>Album</th>
        <th>Genre</th>
        <th>Time</th>
        <th></th>
    </tr>
    <?php foreach ($songs as $song): ?>
        <tr>
            <td><?php echo $song->title; ?></td>
            <td><?php echo $song->artist; ?></td>
            <td><?php echo $song->album; ?></td>
            <td><?php echo $song->genre; ?></td>
            <td><?php echo $song->time; ?></td>
            <td>
                <?php echo anchor("songs/edit/" . $song->id, 'Edit'); ?>
                <?php echo anchor("songs/delete/" . $song->id, 'Delete', array('onclick' => "return confirm('Are you sure to delete this song?')")); ?>
            </td>
        </tr>
    <?php endforeach; ?>
            </table>

            <p><a href="<?php echo site_url('songs/new_song'); ?>">Add a new song</a></p>
            