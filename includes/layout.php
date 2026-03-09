<?php

declare(strict_types=1);

function render_header(string $title): void
{
    ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($title) ?> - AstonCV</title>
    <style>
        :root { color-scheme: light; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: "Segoe UI", Tahoma, sans-serif; background: #f4f6fa; color: #1f2937; }
        header { background: #083b66; color: #fff; padding: 14px 20px; }
        nav a { color: #fff; margin-right: 12px; text-decoration: none; font-weight: 600; display: inline-block; }
        nav a:hover { text-decoration: underline; }
        .logout-btn { background: transparent; color: #fff; border: none; padding: 0; margin: 0; font-weight: 600; cursor: pointer; }
        .logout-btn:hover { text-decoration: underline; }
        main { max-width: 980px; margin: 24px auto; padding: 0 16px; }
        .card { background: #fff; border-radius: 10px; padding: 16px; box-shadow: 0 1px 4px rgba(0,0,0,.08); margin-bottom: 14px; }
        form { display: grid; gap: 10px; }
        input, textarea, button { font: inherit; padding: 9px 10px; border-radius: 8px; border: 1px solid #d1d5db; }
        textarea { min-height: 110px; resize: vertical; }
        button { background: #0a5ea8; color: #fff; border: none; cursor: pointer; font-weight: 700; }
        button:hover { background: #084b86; }
        .danger { color: #9f1239; font-weight: 600; }
        .success { color: #065f46; font-weight: 600; }
        .muted { color: #4b5563; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; }
        th, td { padding: 10px; border-bottom: 1px solid #e5e7eb; text-align: left; }
        th { background: #eef2ff; }
        a { color: #0a5ea8; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        @media (max-width: 700px) { .grid-2 { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<header>
    <nav>
        <a href="/index.php">Home</a>
        <?php if (is_logged_in()): ?>
            <a href="/edit_cv.php">Edit My CV</a>
            <form method="post" action="/logout.php" style="display:inline;">
                <?= csrf_input() ?>
                <button class="logout-btn" type="submit">Logout</button>
            </form>
        <?php else: ?>
            <a href="/register.php">Register</a>
            <a href="/login.php">Login</a>
        <?php endif; ?>
    </nav>
</header>
<main>
    <?php
}

function render_footer(): void
{
    ?>
</main>
</body>
</html>
    <?php
}
