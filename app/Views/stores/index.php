<h1>Stores</h1>
<a href="/stores/create">+ New Store</a>
<a href="/weapons">View Weapons</a>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Slug</th>
        <th>Address</th>
        <th>City</th>
        <th>State/Region</th>
        <th>Country</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($stores as $store): ?>
        <tr>
            <td><?= $store->id ?></td>
            <td><?= htmlspecialchars($store->name) ?></td>
            <td><?= htmlspecialchars($store->slug) ?></td>
            <td><?= htmlspecialchars($store->address_line1) ?></td>
            <td><?= htmlspecialchars($store->city) ?></td>
            <td><?= htmlspecialchars($store->state_region) ?></td>
            <td><?= htmlspecialchars($store->country) ?></td>
            <td><?= htmlspecialchars($store->phone) ?></td>
            <td><?= htmlspecialchars($store->email) ?></td>
            <td>
                <a href="/stores/edit/<?= $store->id ?>">Edit</a>
                <form method="post" action="/stores/delete/<?= $store->id ?>" style="display:inline;">
                    <?= $this->csrfField() ?>
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>