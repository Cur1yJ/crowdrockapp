

                
                <h1>Users</h1>
                <p>Below is a list of the users.</p>

                <table cellpadding="2" cellspacing="0">
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Groups</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
<?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user->first_name; ?></td>
                        <td><?php echo $user->last_name; ?></td>
                        <td><?php echo $user->email; ?></td>
                        <td>
<?php foreach ($user->groups as $group): ?>
<?php echo $group->name; ?><br />
                    <?php endforeach ?>
                                    </td>
                                    <td><?php echo ($user->active) ? anchor("auth/deactivate/" . $user->id, 'Active') : anchor("auth/activate/" . $user->id, 'Inactive'); ?></td>
                                    <td><?php echo anchor("/profile/destroy/" . $user->id, 'Delete', array('onclick' =>"return confirm('Are you sure?')")) ?></td>
                                </tr>
<?php endforeach; ?>
                            </table>

                            <p><a href="<?php echo site_url('auth/create_user'); ?>">Add a new user</a></p>
