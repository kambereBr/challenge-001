<h1>Weapons</h1>
<a href="/weapons/create">+ New Weapon</a>
<a href="/stores">View Stores</a>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Type</th>
        <th>Caliber</th>
        <th>Serial Number</th>
        <th>Price</th>
        <th>In Stock</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($weapons as $w): ?>
        <tr>
            <td><?= $w->id ?></td>
            <td><?= htmlspecialchars($w->name) ?></td>
            <td><?= htmlspecialchars($w->type) ?></td>
            <td><?= htmlspecialchars($w->caliber) ?></td>
            <td><?= htmlspecialchars($w->serial_number) ?></td>
            <td><?= htmlspecialchars($w->price) ?></td>
            <td><?= htmlspecialchars($w->in_stock) ?></td>
            <td><?= htmlspecialchars($w->status) ?></td>
            <td>
                <a href="/weapons/edit/<?= $w->id ?>">Edit</a>
                <form method="post" action="/weapons/delete/<?= $w->id ?>" style="display:inline;">
                    <?= $this->csrfField() ?>
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>