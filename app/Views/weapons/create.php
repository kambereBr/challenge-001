<h1>Create Weapon</h1>
<form method="post" action="/weapons/store">
    <?= $this->csrfField() ?>
    <label>Name: <input name="name"></label><br>
    <label>Type: <input name="type"></label><br>
    <label>Caliber: <input name="caliber"></label><br>
    <label>Serial Number: <input name="serial_number"></label><br>
    <label>Price: <input name="price" type="number" step="0.01"></label><br>
    <label>In Stock: <input name="in_stock" type="number" value="0"></label><br>
    <label>Status: 
        <select name="status">
            <option value="active">Active</option>
            <option value="out_of_stock">Out of Stock</option>
            <option value="discontinued">Discontinued</option>
        </select>
    </label><br>
    <label>Store: 
        <select name="store_id">
            <?php foreach ($stores as $store): ?>
                <option value="<?= $store->id ?>"><?= htmlspecialchars($store->name) ?></option>
            <?php endforeach; ?>
        </select>
    </label><br>
    <button type="submit">Save</button>
</form>