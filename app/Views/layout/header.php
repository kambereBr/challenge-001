<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Tool</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<nav>
    <a href="/stores">Stores</a> |
    <a href="/weapons">Weapons</a> |
    <a href="/users">Users</a> |
    <?php if (!empty($_SESSION['user_id'])): ?>
        <a href="/logout">Logout</a>
    <?php endif; ?>
</nav>
<main>
