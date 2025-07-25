<h1>Create User</h1>
<form method="post" action="/users/store">
    <?= $this->csrfField() ?>
    <label>Username: <input name="username"></label><br>
    <label>Password: <input type="password" name="password"></label><br>
    <label>Role: 
        <select name="role">
            <option value="store_user">Store User</option>
            <option value="super_admin">Super Admin</option>
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