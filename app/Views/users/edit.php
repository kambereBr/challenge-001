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
    <button type="submit">Update</button>
</form>