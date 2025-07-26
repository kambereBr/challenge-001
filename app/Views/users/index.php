<h1>Users</h1>
<a href="/users/create">+ New User</a>
<table>
    <thead>
        <tr>
            <th class="sortable">ID</th>
            <th class="sortable">Username</th>
            <th class="sortable">Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= $u->id ?></td>
                <td><?= htmlspecialchars($u->username) ?></td>
                <td><?= $u->role ?></td>
                <td>
                    <a href="/users/edit/<?= $u->id ?>">Edit</a>
                    <form method="post" action="/users/delete/<?= $u->id ?>" style="display:inline;">
                        <?= $this->csrfField() ?>
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>