<?php
declare(strict_types=1);

$root = dirname(__DIR__);
$rootErrors = [];
foreach (['MOD_RULES.md', 'MODULE_STANDARD.md', 'MODULE_REGISTRY.md'] as $required) {
    if (!is_file($root . '/' . $required)) {
        $rootErrors[] = "repository: required governance file '$required' is missing";
    }
}
$rules = @file_get_contents($root . '/MOD_RULES.md');
if (!is_string($rules) || !str_contains($rules, '**Contract version: 1.0**')) {
    $rootErrors[] = 'repository: MOD_RULES.md contract version is missing or unsupported';
}
$modules = glob($root . '/modules/*', GLOB_ONLYDIR) ?: [];
if ($modules === []) {
    if ($rootErrors !== []) {
        fwrite(STDERR, implode("\n", $rootErrors) . "\n");
        exit(1);
    }
    fwrite(STDOUT, "No module folders on this branch; shared repository files are valid.\n");
    exit(0);
}

$errors = $rootErrors;
$requiredDocs = [
    'README.md',
    'MODULE_RULES.md',
    'CHANGELOG.md',
    'docs/PROJECT_STATUS.md',
    'docs/ROADMAP.md',
    'docs/SESSION_HANDOFF.md',
    'docs/TESTING.md',
    'releases/LATEST.json',
];

foreach ($modules as $module) {
    $name = basename($module);
    $manifestPath = $module . '/addon.json';
    if (!is_file($manifestPath)) {
        $errors[] = "$name: addon.json is missing";
        continue;
    }

    $manifest = json_decode((string) file_get_contents($manifestPath), true);
    if (!is_array($manifest)) {
        $errors[] = "$name: addon.json is not valid JSON";
        continue;
    }

    foreach (['id', 'type', 'name', 'version', 'provider'] as $field) {
        if (!isset($manifest[$field]) || !is_string($manifest[$field]) || trim($manifest[$field]) === '') {
            $errors[] = "$name: addon.json field '$field' is required";
        }
    }
    if (!isset($manifest['version']) || !is_string($manifest['version']) ||
        preg_match('/^(0|[1-9][0-9]*)\.(0|[1-9][0-9]*)\.(0|[1-9][0-9]*)$/', $manifest['version']) !== 1) {
        $errors[] = "$name: version must be stable semantic version MAJOR.MINOR.PATCH";
    }

    foreach ($requiredDocs as $relative) {
        if (!is_file($module . '/' . $relative)) {
            $errors[] = "$name: required file '$relative' is missing";
        }
    }

    $moduleRules = @file_get_contents($module . '/MODULE_RULES.md');
    if (!is_string($moduleRules) || !str_contains($moduleRules, 'Cajun Web Pro Module Rules 1.0')) {
        $errors[] = "$name: MODULE_RULES.md does not declare rules contract 1.0";
    }

    $latestPath = $module . '/releases/LATEST.json';
    if (!is_file($latestPath)) {
        continue;
    }
    $latest = json_decode((string) file_get_contents($latestPath), true);
    if (!is_array($latest)) {
        $errors[] = "$name: releases/LATEST.json is not valid JSON";
        continue;
    }
    if (($latest['version'] ?? null) !== ($manifest['version'] ?? null)) {
        $errors[] = "$name: latest release version does not match addon.json";
    }
    $archive = isset($latest['file']) && is_string($latest['file']) ? basename($latest['file']) : '';
    $archivePath = $module . '/releases/' . $archive;
    if ($archive === '' || !is_file($archivePath)) {
        $errors[] = "$name: latest release ZIP is missing";
        continue;
    }
    $digest = hash_file('sha256', $archivePath);
    if (!isset($latest['sha256']) || !is_string($latest['sha256']) || !hash_equals($latest['sha256'], $digest)) {
        $errors[] = "$name: latest release SHA-256 does not match";
    }
}

if ($errors !== []) {
    fwrite(STDERR, implode("\n", $errors) . "\n");
    exit(1);
}

fwrite(STDOUT, 'Validated ' . count($modules) . " module folder(s).\n");

