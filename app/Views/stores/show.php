<h1>Store: <?= htmlspecialchars($store->name) ?></h1>

<p>
  <strong>Address:</strong>
  <?= htmlspecialchars($store->address_line1) ?>,<br>
  <?= htmlspecialchars($store->city) ?>, <?= htmlspecialchars($store->state_region) ?>,
  <?= htmlspecialchars($store->country) ?>
</p>
<p>
  <strong>Phone:</strong> <?= htmlspecialchars($store->phone) ?><br>
  <strong>Email:</strong> <?= htmlspecialchars($store->email) ?>
</p>
<p>
  <a class="btn" href="/stores/pdf/<?= $store->id ?>" target="_blank">Print PDF</a> 
</p>

<h2>Weapons in this Store</h2>
<?php if (count($weapons) === 0): ?>
  <p>No weapons found.</p>
<?php else: ?>
  <div class="table-search">
    <label>
      Search:
      <input type="text" class="table-filter" data-table="store-weapons-table" placeholder="Type to filter…">
    </label>
  </div>
  <table id="store-weapons-table">
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
        <td data-label="ID"><?= $w->id ?></td>
        <td data-label="Name">
          <a href="/weapons/show/<?= $w->id ?>"><?= htmlspecialchars($w->name) ?></a>
        </td>
        <td data-label="Type"><?= htmlspecialchars($w->type) ?></td>
        <td data-label="Caliber"><?= htmlspecialchars($w->caliber) ?></td>
        <td data-label="Serial Number"><?= htmlspecialchars($w->serial_number) ?></td>
        <td data-label="Price"><?= htmlspecialchars($w->price) ?></td>
        <td data-label="In Stock"><?= htmlspecialchars($w->in_stock) ?></td>
        <td data-label="Status"><?= htmlspecialchars($w->status) ?></td>
        <td data-label="Actions">
            <a href="/weapons/show/<?= $w->id ?>">View</a> | 
            <a class="btn" href="/weapons/pdf/<?= $w->id ?>" target="_blank">PDF</a> |
            <a href="/weapons/edit/<?= $w->id ?>">Edit</a> | 
            <form method="post" action="/weapons/delete/<?= $w->id ?>" style="display:inline;">
                <?= $this->csrfField() ?>
                <button type="submit">Delete</button>
            </form>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>
