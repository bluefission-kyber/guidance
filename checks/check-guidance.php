<?php
declare(strict_types=1);

/* Structural lint only. Human disclosure and authorization review remain required. */
$root = dirname(__DIR__);
$documents = ['README.md', 'OPERATING_RULES.md', 'PUBLIC_COMMITMENTS.md'];
$errors = [];

foreach ($documents as $document) {
    $path = $root . DIRECTORY_SEPARATOR . $document;
    $content = file_get_contents($path);
    if ($content === false || trim($content) === '') {
        $errors[] = "$document is missing or empty";
        continue;
    }

    if (preg_match('~[A-Za-z]:\\\\|file://~i', $content) === 1) {
        $errors[] = "$document contains an obvious local file path";
    }

    preg_match_all('~\[[^\]]+\]\(([^)]+)\)~', $content, $matches);
    foreach ($matches[1] as $link) {
        if (preg_match('~^https?://~i', $link) === 1 || str_starts_with($link, '#')) {
            continue;
        }
        $relative = explode('#', $link, 2)[0];
        if ($relative === '' || str_starts_with($relative, '/') || str_contains($relative, '..')) {
            $errors[] = "$document has an unsafe local link: $link";
            continue;
        }
        if (!is_file($root . DIRECTORY_SEPARATOR . $relative)) {
            $errors[] = "$document has a missing local link: $link";
        }
    }
}

if ($errors !== []) {
    fwrite(STDERR, implode("\n", $errors) . "\n");
    exit(1);
}

fwrite(STDOUT, "Public guidance structure passed. Human disclosure review is still required.\n");
