<h1>Weapons</h1>
<a href="/weapons/create">+ New Weapon</a>
<a href="/stores">View Stores</a>
<div class="table-search">
  <label>
    Search:
    <input type="text" class="table-filter" data-table="weapons-table" placeholder="Type to filter…">
  </label>
</div>
<table id="weapons-table">
    <thead>
        <tr>
            <th class="sortable">ID</th>
            <th class="sortable">Name</th>
            <th class="sortable">Type</th>
            <th class="sortable">Caliber</th>
            <th class="sortable">Serial Number</th>
            <th class="sortable">Price</th>
            <th class="sortable">In Stock</th>
            <th class="sortable">Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
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
    </tbody>
</table>