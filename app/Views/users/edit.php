<h1>Edit User</h1>
<form method="post" action="/users/update/<?= $user->id ?>">
    <?= $this->csrfField() ?>
    <label>Username: <input name="username" value="<?= htmlspecialchars($user->username) ?>"></label><br>
    <label>New Password: <input type="password" name="password"></label><br>
    <label>Role: 
        <select name="role">
            <option <?= $user->role==='store_user'?'selected':'' ?> value="store_user">Store User</option>
            <option <?= $user->role==='super_admin'?'selected':'' ?> value="super_admin">Super Admin</option>
        </select>
    </label><br>
    <label>Store: 
        <select name="store_id">
            <?php foreach ($stores as $store): ?>
                <option value="<?= $store->id ?>" <?= $user->store_id === $store->id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($store->name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label><br>
    <button type="submit">Update</button>
</form>