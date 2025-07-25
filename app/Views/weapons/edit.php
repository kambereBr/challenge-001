<h1>Edit Weapon</h1>
<form method="post" action="/weapons/update/<?= $weapon->id ?>">
    <?= $this->csrfField() ?>
    <label>Name: <input name="name" value="<?= htmlspecialchars($weapon->name) ?>"></label><br>
    <label>Type: <input name="type" value="<?= htmlspecialchars($weapon->type) ?>"></label><br>
    <label>Caliber: <input name="caliber" value="<?= htmlspecialchars($weapon->caliber) ?>"></label><br>
    <label>Serial Number: <input name="serial_number" value="<?= htmlspecialchars($weapon->serial_number) ?>"></label><br>
    <label>Price: <input name="price" type="number" step="0.01" value="<?= htmlspecialchars($weapon->price) ?>"></label><br>
    <label>In Stock: <input name="in_stock" type="number" value="<?= $weapon->in_stock ?>"></label><br>
    <label>Status: 
        <select name="status">
            <option value="active" <?= $weapon->status === 'active' ? 'selected' : '' ?>>Active</option>
            <option value="out_of_stock" <?= $weapon->status === 'out_of_stock' ? 'selected' : '' ?>>Out of Stock</option>
            <option value="discontinued" <?= $weapon->status === 'discontinued' ? 'selected' : '' ?>>Discontinued</option>
        </select>
    </label><br>
    <label>Store: 
        <select name="store_id">
            <?php foreach ($stores as $store): ?>
                <option value="<?= $store->id ?>" <?= $weapon->store_id === $store->id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($store->name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label><br>
    <button type="submit">Update</button>
</form>