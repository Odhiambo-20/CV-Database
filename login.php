<?php

declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';
require __DIR__ . '/includes/layout.php';

if (is_logged_in()) {
    redirect('/edit_cv.php');
}

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify_or_fail();

    $email = post_string('email');
    $password = (string) ($_POST['password'] ?? '');

    if (!validate_email($email)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    }

    if (!$errors) {
        $stmt = db()->prepare('SELECT id, password_hash FROM cvs WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, (string) $user['password_hash'])) {
            login_user((int) $user['id']);
            redirect('/edit_cv.php');
        }

        $errors[] = 'Invalid email or password.';
    }
}

render_header('Login');
?>
<div class="card">
    <h1>Login</h1>
    <?php foreach ($errors as $error): ?>
        <p class="danger"><?= h($error) ?></p>
    <?php endforeach; ?>
    <form method="post" action="/login.php" novalidate>
        <?= csrf_input() ?>
        <input type="email" name="email" maxlength="255" placeholder="Email" value="<?= h($email) ?>" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>
<?php render_footer(); ?>
