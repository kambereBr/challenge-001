<h1>Login</h1>
<form method="post" action="/login">
    <?= $this->csrfField() ?>
    <label>Username: <input name="username"></label><br>
    <label>Password: <input type="password" name="password"></label><br>
    <button type="submit">Login</button>
</form>