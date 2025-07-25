<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Tool</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>

<body>
    <nav>
        <ul>
            <li><a href="/">Home</a></li>
            <li><a href="/stores">Stores</a></li>
            <li><a href="/weapons">Weapons</a></li>
            <li><a href="/users">Users</a></li>
            <?php if (! empty($_SESSION['user_id'])): ?>
                <li><a href="/logout">Logout</a></li>
            <?php else: ?>
                <li><a href="/login">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <main class="container">