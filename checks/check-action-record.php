<?php
declare(strict_types=1);

/*
 * Checks the completeness of a review record. It does not verify that an
 * approver has authority, that evidence is accurate, or that an action is safe.
 */

if ($argc !== 2 || in_array($argv[1], ['-h', '--help'], true)) {
    fwrite(STDERR, "Usage: php checks/check-action-record.php <record.json>\n");
    exit($argc === 2 ? 0 : 2);
}

$path = $argv[1];
if (!is_file($path) || !is_readable($path)) {
    fwrite(STDERR, "Record cannot be read.\n");
    exit(2);
}

$raw = file_get_contents($path);
if ($raw === false) {
    fwrite(STDERR, "Record cannot be read.\n");
    exit(2);
}

try {
    $record = json_decode($raw, true, 32, JSON_THROW_ON_ERROR);
} catch (JsonException $exception) {
    fwrite(STDERR, "Record is not valid JSON.\n");
    exit(2);
}

if (!is_array($record) || array_is_list($record)) {
    fwrite(STDERR, "Record must be a JSON object.\n");
    exit(2);
}

$required = [
    'action', 'owner', 'scope', 'target', 'risk', 'evidence',
    'reviewer', 'approval', 'pause', 'recovery',
];
$missing = [];
foreach ($required as $field) {
    if (!array_key_exists($field, $record) || !is_string($record[$field]) || trim($record[$field]) === '') {
        $missing[] = $field;
    }
}

if ($missing !== []) {
    fwrite(STDERR, 'Missing or empty fields: ' . implode(', ', $missing) . "\n");
    exit(1);
}

fwrite(STDOUT, "Record has the required review fields. Authorization and evidence still need human verification.\n");
