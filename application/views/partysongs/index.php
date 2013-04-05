<?php $this->load->view('partysongs/import_songs_form', $this->data); ?>
<br/><br/>

<?php echo anchor('/partysongs/show/' . $party->id, "DJ Panel") ?>
<br/>

<table cellpadding="2" cellspacing="0">
    <tr>
        <th>Title</th>
        <th>Artist</th>
        <th>Album</th>
        <th>Genre</th>
        <th>Time</th>
        <th></th>
    </tr>
    <?php foreach ($party_songs as $party_song): ?>
        <tr>
            <td><?php echo $party_song->title; ?></td>
            <td><?php echo $party_song->artist; ?></td>
            <td><?php echo $party_song->album; ?></td>
            <td><?php echo $party_song->genre; ?></td>
            <td><?php echo $party_song->time_str; ?></td>
            <td>
                <?php echo anchor("partysongs/edit/" . $party_song->id, 'Edit'); ?>
                <?php echo anchor("partysongs/delete/" . $party_song->id, 'Delete', array('onclick' => "return confirm('Are you sure to delete this song?')")); ?>
            </td>
        </tr>
    <?php endforeach; ?>
            </table>

            <p><a href="<?php echo site_url('partysongs/new_song/' . $party->id); ?>">Add a new song</a></p>
            