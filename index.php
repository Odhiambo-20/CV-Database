<?php

declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';
require __DIR__ . '/includes/layout.php';

$q = trim((string) ($_GET['q'] ?? ''));

$sql = 'SELECT id, name, email, key_language FROM cvs';
$params = [];

if ($q !== '') {
    $sql .= ' WHERE name LIKE :q_name OR key_language LIKE :q_lang';
    $params[':q_name'] = '%' . $q . '%';
    $params[':q_lang'] = '%' . $q . '%';
}

$sql .= ' ORDER BY name ASC';

$stmt = db()->prepare($sql);
$stmt->execute($params);
$cvs = $stmt->fetchAll();

render_header('CV List');
?>

<div class="card">
    <h1>Programmer CV Database</h1>
    <p class="muted">Search by name or key programming language.</p>
    <form method="get" action="/index.php">
        <input type="text" name="q" maxlength="100" placeholder="e.g. Alice or Python" value="<?= h($q) ?>">
        <button type="submit">Search CVs</button>
    </form>
</div>

<div class="card">
    <h2>All CVs</h2>
    <?php if (!$cvs): ?>
        <p>No CV records found.</p>
    <?php else: ?>
        <table>
            <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Key Language</th>
                <th>View</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($cvs as $cv): ?>
                <tr>
                    <td><?= h($cv['name']) ?></td>
                    <td><?= h($cv['email']) ?></td>
                    <td><?= h($cv['key_language']) ?></td>
                    <td><a href="/view_cv.php?id=<?= (int) $cv['id'] ?>">Open CV</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php render_footer(); ?>
