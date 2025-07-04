<?php
if ($argc < 2) {
    echo "Usage: php bump-version.php [patch|minor|major]\n";
    exit(1);
}

$type = $argv[1];
$versionFile = __DIR__ . '/../VERSION';

if (!file_exists($versionFile)) {
    echo "VERSION file not found.\n";
    exit(1);
}

$version = trim(file_get_contents($versionFile));
[$major, $minor, $patch] = explode('.', $version);

switch ($type) {
    case 'patch':
        $patch++;
        break;
    case 'minor':
        $minor++;
        $patch = 0;
        break;
    case 'major':
        $major++;
        $minor = 0;
        $patch = 0;
        break;
    default:
        echo "Invalid version type: $type\n";
        exit(1);
}

$newVersion = "$major.$minor.$patch";
file_put_contents($versionFile, $newVersion);
echo "✅ Bumped version to $newVersion\n";
