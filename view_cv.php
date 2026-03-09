<?php

declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';
require __DIR__ . '/includes/layout.php';

function render_links_html(string $rawLinks): string
{
    $parts = preg_split('/[\r\n,;]+/', $rawLinks) ?: [];
    $items = [];

    foreach ($parts as $part) {
        $linkText = trim($part);
        if ($linkText === '') {
            continue;
        }

        $candidate = $linkText;
        if (!preg_match('#^https?://#i', $candidate)) {
            $candidate = 'https://' . $candidate;
        }

        $isValid = filter_var($candidate, FILTER_VALIDATE_URL) !== false;
        $scheme = strtolower((string) parse_url($candidate, PHP_URL_SCHEME));

        if ($isValid && in_array($scheme, ['http', 'https'], true)) {
            $items[] = '<a href="' . h($candidate) . '" target="_blank" rel="noopener noreferrer">' . h($linkText) . '</a>';
        } else {
            $items[] = h($linkText);
        }
    }

    if (!$items) {
        return '';
    }

    return implode('<br>', $items);
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    http_response_code(400);
    exit('Invalid CV id.');
}

$stmt = db()->prepare('SELECT id, name, email, education, key_language, profile, links FROM cvs WHERE id = :id LIMIT 1');
$stmt->execute([':id' => $id]);
$cv = $stmt->fetch();

if (!$cv) {
    http_response_code(404);
    exit('CV not found.');
}

render_header('CV Details');
?>
<div class="card">
    <h1><?= h($cv['name']) ?></h1>
    <p><strong>Email:</strong> <a href="mailto:<?= h((string) $cv['email']) ?>"><?= h($cv['email']) ?></a></p>
    <p><strong>Key Programming Language:</strong> <?= h($cv['key_language']) ?></p>
    <p><strong>Education:</strong><br><?= nl2br(h($cv['education'])) ?></p>
    <p><strong>Profile:</strong><br><?= nl2br(h($cv['profile'])) ?></p>
    <p><strong>Links:</strong><br><?= render_links_html((string) $cv['links']) ?></p>
</div>
<?php render_footer(); ?>
