<h1>Stores</h1>
<a href="/stores/create">+ New Store</a>
<a href="/weapons">View Weapons</a>
<div class="table-search">
  <label>
    Search:
    <input type="text" class="table-filter" data-table="stores-table" placeholder="Type to filter…">
  </label>
</div>
<table id="stores-table">
    <thead>
        <tr>
            <th class="sortable">ID</th>
            <th class="sortable">Name</th>
            <th class="sortable">Slug</th>
            <th class="sortable">Address</th>
            <th class="sortable">City</th>
            <th class="sortable">State/Region</th>
            <th class="sortable">Country</th>
            <th class="sortable">Phone</th>
            <th class="sortable">Email</th>
            <th class="sortable">Total Weapons</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($stores as $store): ?>
            <tr>
                <td><?= $store->id ?></td>
                <td><a href="/stores/show/<?= $store->id ?>"><?= htmlspecialchars($store->name) ?></a></td>
                <td><?= htmlspecialchars($store->slug) ?></td>
                <td><?= htmlspecialchars($store->address_line1) ?></td>
                <td><?= htmlspecialchars($store->city) ?></td>
                <td><?= htmlspecialchars($store->state_region) ?></td>
                <td><?= htmlspecialchars($store->country) ?></td>
                <td><?= htmlspecialchars($store->phone) ?></td>
                <td><?= htmlspecialchars($store->email) ?></td>
                <td><?= $totalWeapons[$store->id] ?? 0 ?></td>
                <td>
                    <a href="/stores/edit/<?= $store->id ?>">Edit</a> | 
                    <a href="/stores/show/<?= $store->id ?>">View</a> | 
                    <form method="post" action="/stores/delete/<?= $store->id ?>" style="display:inline;">
                        <?= $this->csrfField() ?>
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>