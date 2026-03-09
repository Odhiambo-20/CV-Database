<?php

declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';
require __DIR__ . '/includes/layout.php';

$errors = [];
$name = '';
$email = '';
$keyLanguage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify_or_fail();

    $name = post_string('name');
    $email = post_string('email');
    $password = (string) ($_POST['password'] ?? '');
    $confirmPassword = (string) ($_POST['confirm_password'] ?? '');
    $keyLanguage = post_string('key_language');

    if ($name === '' || strlen($name) > 100) {
        $errors[] = 'Name is required and must be <= 100 characters.';
    }

    if (!validate_email($email) || strlen($email) > 255) {
        $errors[] = 'A valid email is required.';
    }

    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match.';
    }

    if ($keyLanguage === '' || strlen($keyLanguage) > 80) {
        $errors[] = 'Key programming language is required.';
    }

    if (!$errors) {
        $check = db()->prepare('SELECT id FROM cvs WHERE email = :email LIMIT 1');
        $check->execute([':email' => $email]);
        if ($check->fetch()) {
            $errors[] = 'That email address is already registered.';
        } else {
            $stmt = db()->prepare(
                'INSERT INTO cvs (name, email, password_hash, key_language, education, profile, links, created_at, updated_at)
                 VALUES (:name, :email, :password_hash, :key_language, "", "", "", NOW(), NOW())'
            );
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password_hash' => password_hash($password, PASSWORD_DEFAULT),
                ':key_language' => $keyLanguage,
            ]);

            login_user((int) db()->lastInsertId());
            redirect('/edit_cv.php?new=1');
        }
    }
}

render_header('Register');
?>
<div class="card">
    <h1>Create Account</h1>
    <?php foreach ($errors as $error): ?>
        <p class="danger"><?= h($error) ?></p>
    <?php endforeach; ?>
    <form method="post" action="/register.php" novalidate>
        <?= csrf_input() ?>
        <input type="text" name="name" maxlength="100" placeholder="Full name" value="<?= h($name) ?>" required>
        <input type="email" name="email" maxlength="255" placeholder="Email" value="<?= h($email) ?>" required>
        <input type="password" name="password" minlength="8" placeholder="Password (min 8 chars)" required>
        <input type="password" name="confirm_password" minlength="8" placeholder="Confirm password" required>
        <input type="text" name="key_language" maxlength="80" placeholder="Key programming language" value="<?= h($keyLanguage) ?>" required>
        <button type="submit">Register</button>
    </form>
</div>
<?php render_footer(); ?>
