<?php

declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';
require __DIR__ . '/includes/layout.php';

require_login();

$userId = user_id();
if ($userId === null) {
    redirect('/login.php');
}

$stmt = db()->prepare('SELECT id, name, email, education, key_language, profile, links FROM cvs WHERE id = :id LIMIT 1');
$stmt->execute([':id' => $userId]);
$cv = $stmt->fetch();

if (!$cv) {
    logout_user();
    redirect('/login.php');
}

$errors = [];
$updated = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify_or_fail();

    $name = post_string('name');
    $education = post_string('education');
    $keyLanguage = post_string('key_language');
    $profile = post_string('profile');
    $links = post_string('links');

    if ($name === '' || strlen($name) > 100) {
        $errors[] = 'Name is required and must be <= 100 characters.';
    }

    if ($keyLanguage === '' || strlen($keyLanguage) > 80) {
        $errors[] = 'Key programming language is required and must be <= 80 characters.';
    }

    if (strlen($education) > 2000 || strlen($profile) > 2000 || strlen($links) > 2000) {
        $errors[] = 'Education, profile, and links must each be <= 2000 characters.';
    }

    if (!$errors) {
        $update = db()->prepare(
            'UPDATE cvs
             SET name = :name,
                 education = :education,
                 key_language = :key_language,
                 profile = :profile,
                 links = :links,
                 updated_at = NOW()
             WHERE id = :id'
        );

        $update->execute([
            ':name' => $name,
            ':education' => $education,
            ':key_language' => $keyLanguage,
            ':profile' => $profile,
            ':links' => $links,
            ':id' => $userId,
        ]);

        $updated = true;

        $stmt->execute([':id' => $userId]);
        $cv = $stmt->fetch();
    }
}

render_header('Edit My CV');
?>
<div class="card">
    <h1>Edit My CV</h1>
    <?php if (isset($_GET['new'])): ?>
        <p class="success">Account created. You can now complete your CV details.</p>
    <?php endif; ?>
    <?php if ($updated): ?>
        <p class="success">CV updated successfully.</p>
    <?php endif; ?>
    <?php foreach ($errors as $error): ?>
        <p class="danger"><?= h($error) ?></p>
    <?php endforeach; ?>

    <form method="post" action="/edit_cv.php" novalidate>
        <?= csrf_input() ?>
        <input type="text" name="name" maxlength="100" value="<?= h($cv['name']) ?>" required>
        <input type="email" value="<?= h($cv['email']) ?>" disabled>
        <input type="text" name="key_language" maxlength="80" value="<?= h($cv['key_language']) ?>" required>
        <textarea name="education" maxlength="2000" placeholder="Education"><?= h($cv['education']) ?></textarea>
        <textarea name="profile" maxlength="2000" placeholder="Profile summary"><?= h($cv['profile']) ?></textarea>
        <textarea name="links" maxlength="2000" placeholder="URL links (GitHub, LinkedIn, portfolio)"><?= h($cv['links']) ?></textarea>
        <button type="submit">Save CV</button>
    </form>
</div>
<?php render_footer(); ?>
