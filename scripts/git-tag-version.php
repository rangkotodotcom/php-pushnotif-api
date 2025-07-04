<?php

$versionFile = __DIR__ . '/../VERSION';
if (!file_exists($versionFile)) {
    echo "❌ VERSION file not found.\n";
    exit(1);
}

$version = trim(file_get_contents($versionFile));
$tag = "v" . $version;

exec("git tag", $existingTags);
if (in_array($tag, $existingTags)) {
    echo "⚠️  Tag {$tag} already exists.\n";
    exit(1);
}

exec("git tag {$tag}", $output, $code);

if ($code !== 0) {
    echo "❌ Failed to create git tag {$tag}\n";
    exit($code);
}

echo "✅ Created git tag: {$tag}\n";
