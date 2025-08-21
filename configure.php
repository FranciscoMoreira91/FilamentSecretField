#!/usr/bin/env php
<?php

$gitName = run('git config user.name');
$authorName = ask('Author name', $gitName);

$gitEmail = run('git config user.email');
$authorEmail = ask('Author email', $gitEmail);

$usernameGuess = explode(':', run('git config remote.origin.url'))[1] ?? '';
if ($usernameGuess !== '') {
    $usernameGuess = dirname($usernameGuess);
    $usernameGuess = basename($usernameGuess);
}
$authorUsername = $authorName; //ask('Author username', $usernameGuess);

$vendorName = "Norlogic"; //ask('Vendor name', $authorUsername);
$vendorSlug = slugify($vendorName);
$vendorNamespace = str_replace('-', '', ucwords($vendorName));
//$vendorNamespace = ask('Vendor namespace', $vendorNamespace);

$currentDirectory = getcwd();
$folderName = basename($currentDirectory);

$packageName = ask('Nome do modulo no singular)', $folderName);
$packageName = strtolower($packageName);
$ShortPackageName = $packageName;
$langName = $packageName;
$modelName = ucfirst($packageName);
$packageSlug = slugify('nlmn-' . $packageName . '-module');

$packageSlugWithoutPrefix = removePrefix('filament-', $packageSlug);

$className = titleCase(ucfirst($packageName) . 'Module');
$classNameSpace = 'Nlmn' . $className;
$packageName = 'Nlmn' . ucfirst($packageName) . 'Module';
//$className = ask('Class name', $className);
$FilamentsecretfieldModuleName = ucfirst($className);
$description = "This is my package $packageSlug for Norlogic Metanoia!"; //ask('Package description', "This is my package $packageSlug for Norlogic Metanoia!");

/* $usePhpStan = confirm('Enable PhpStan?', true);
$usePint = confirm('Enable Pint?', true);
$useDependabot = confirm('Enable Dependabot?', true);
$useLaravelRay = confirm('Enable Ray?', true);
$useUpdateChangelogWorkflow = confirm('Use automatic changelog updater workflow?', true); */

$usePhpStan = false;
$usePint = false;
$useDependabot = false;
$useLaravelRay = false;
$useUpdateChangelogWorkflow = false; //confirm('Use automatic changelog updater workflow?', true);

$isTheme = false; //confirm('Is this a custom theme?');
$formsOnly = false; //! $isTheme && confirm('Is this for Forms only?');
$tablesOnly = false; //! ($isTheme || $formsOnly) && confirm('Is this for Tables only?');

writeln("\r");
writeln('------');
writeln("Author     : \e[0;36m$authorName ($authorUsername, $authorEmail)\e[0m");
writeln("Vendor     : \e[0;36m$vendorName ($vendorSlug)\e[0m");
writeln('Package    : ' . "\e[0;36m" . $packageSlug . ($description ? " <{$description}>" : '') . "\e[0m");
writeln("Namespace  : \e[0;36m$vendorNamespace\\$classNameSpace\e[0m");
writeln("Class name : \e[0;36m$className\e[0m");
writeln('---');
writeln("\e[1;37mPackages & Utilities\e[0m");
writeln('Larastan/PhpStan  : ' . ($usePhpStan ? "\e[0;32mYes" : "\e[0;31mNo") . "\e[0m");
writeln('Pint              : ' . ($usePint ? "\e[0;32mYes" : "\e[0;31mNo") . "\e[0m");
writeln('Use Dependabot    : ' . ($useDependabot ? "\e[0;32mYes" : "\e[0;31mNo") . "\e[0m");
writeln('Use Ray           : ' . ($useLaravelRay ? "\e[0;32mYes" : "\e[0;31mNo") . "\e[0m");
writeln('Auto-Changelog    : ' . ($useUpdateChangelogWorkflow ? "\e[0;32mYes" : "\e[0;31mNo") . "\e[0m");
if ($formsOnly) {
    writeln("Filament/Forms    : \e[0;32mYes\e[0m");
} elseif ($tablesOnly) {
    writeln("Filament/Tables   : \e[0;32mYes\e[0m");
} else {
    writeln("Filament/Filament : \e[0;32mYes\e[0m");
}
writeln('------');
writeln("\r");
writeln('This script will replace the above values in all relevant files in the project directory.');
writeln("\r");

if (!confirm('Modify files?', true)) {
    exit(1);
}

if ($formsOnly) {
    safeUnlink(__DIR__ . '/src/FilamentsecretfieldModuleTheme.php');
    safeUnlink(__DIR__ . '/src/FilamentsecretfieldModulePlugin.php');

    removeComposerDeps([
        'filament/filament',
        'filament/tables',
    ], 'require');
} elseif ($tablesOnly) {
    safeUnlink(__DIR__ . '/src/FilamentsecretfieldModuleTheme.php');
    safeUnlink(__DIR__ . '/src/FilamentsecretfieldModulePlugin.php');

    removeComposerDeps([
        'filament/filament',
        'filament/forms',
    ], 'require');
} else {
    if ($isTheme) {
        safeUnlink(__DIR__ . '/src/FilamentsecretfieldModuleServiceProvider.php');
        safeUnlink(__DIR__ . '/src/FilamentsecretfieldModulePlugin.php');
        safeUnlink(__DIR__ . '/src/FilamentsecretfieldModule.php');
        removeDirectory(__DIR__ . '/bin');
        removeDirectory(__DIR__ . '/config');
        removeDirectory(__DIR__ . '/database');
        removeDirectory(__DIR__ . '/stubs');
        removeDirectory(__DIR__ . '/resources/js');
        removeDirectory(__DIR__ . '/resources/lang');
        removeDirectory(__DIR__ . '/resources/views');
        removeDirectory(__DIR__ . '/src/Commands');
        removeDirectory(__DIR__ . '/src/Facades');
        removeDirectory(__DIR__ . '/src/Testing');

        setupPackageJsonForTheme();
    } else {
        safeUnlink(__DIR__ . '/src/FilamentsecretfieldModuleTheme.php');
    }

    removeComposerDeps([
        'filament/forms',
        'filament/tables',
    ], 'require');
}
$currentDirectory = $currentDirectory . '\\packages\\' . $ShortPackageName . '-Module\\';
$files = (str_starts_with(strtoupper(PHP_OS), 'WIN') ? replaceForWindows($currentDirectory) : replaceForAllOtherOSes());
var_dump($ShortPackageName);
$files = array_filter($files, function ($file) use ($ShortPackageName) {
    return str_contains($file, "packages\\" . $ShortPackageName . '-Module');
});
foreach ($files as $file) {

    replaceInFile($file, [
        'Francisco Moreira' => $authorName,
        'norlogic-metanoia-modulos' => $authorUsername,
        'franciscomoreira@redfenix.pt' => $authorEmail,
        'Norlogic' => $vendorName,
        'norlogic' => $vendorSlug,
        'Norlogic' => $vendorNamespace,
        'NlmnFilamentsecretfieldModule' => $packageName,
        'nlmn-filamentsecretfield-module' => $packageSlug,
        'nlmn-filamentsecretfield-module_without_prefix' => $packageSlugWithoutPrefix,
        'NlmnFilamentsecretfieldModule' => $classNameSpace,
        'FilamentsecretfieldModule' => $className,
        'nlmn-filamentsecretfield-module' => $packageSlug,
        'nlmn_filamentsecretfield_module' => titleSnake($packageSlug),
        'FilamentsecretfieldModule' => $FilamentsecretfieldModuleName,
        'This is my package nlmn-filamentsecretfield-module for Norlogic Metanoia!' => $description,
    ]);

    match (true) {
        str_contains($file, determineSeparator('src/FilamentsecretfieldModule.php')) => rename($file, determineSeparator('./src/' . $className . '.php')),
        str_contains($file, determineSeparator('src/FilamentsecretfieldModuleServiceProvider.php')) => rename($file, determineSeparator('./src/' . $className . 'ServiceProvider.php')),
        str_contains($file, determineSeparator('src/FilamentsecretfieldModuleTheme.php')) => rename($file, determineSeparator('./src/' . $className . 'Theme.php')),
        str_contains($file, determineSeparator('src/FilamentsecretfieldModulePlugin.php')) => rename($file, determineSeparator('./src/' . $className . 'Plugin.php')),
        str_contains($file, determineSeparator('src/Facades/FilamentsecretfieldModule.php')) => rename($file, determineSeparator('./src/Facades/' . $className . '.php')),
        //str_contains($file, determineSeparator('src/Models/FilamentsecretfieldModule.php')) => rename($file, determineSeparator('./src/Models/' . $modelName . '.php')),
        str_contains($file, determineSeparator('src/Commands/FilamentsecretfieldModuleCommand.php')) => rename($file, determineSeparator('./src/Commands/' . $className . 'Command.php')),
        str_contains($file, determineSeparator('src/Testing/TestsFilamentsecretfieldModule.php')) => rename($file, determineSeparator('./src/Testing/Tests' . $className . '.php')),
        str_contains($file, determineSeparator('database/migrations/create_nlmn-filamentsecretfield-module_table.php.stub')) => rename($file, determineSeparator('./database/migrations/create_' . titleSnake($packageSlugWithoutPrefix) . '_table.php.stub')),
        str_contains($file, determineSeparator('config/nlmn-filamentsecretfield-module.php')) => rename($file, determineSeparator('./config/' . $packageSlugWithoutPrefix . '.php')),
        str_contains($file, determineSeparator('resources/lang/en/nlmn-filamentsecretfield-module.php')) => rename($file, determineSeparator('./resources/lang/en/' . $langName . '.php')),
        str_contains($file, determineSeparator('resources/lang/pt_PT/nlmn-filamentsecretfield-module.php')) => rename($file, determineSeparator('./resources/lang/pt_PT/' . $langName . '.php')),
        str_contains($file, 'README.md') => removeTag($file, 'delete'),
        default => [],
    };
}

if (!$useDependabot) {
    safeUnlink(__DIR__ . '/.github/dependabot.yml');
    safeUnlink(__DIR__ . '/.github/workflows/dependabot-auto-merge.yml');
}

if (!$useLaravelRay) {
    removeComposerDeps(['spatie/laravel-ray'], 'require-dev');
}

if (!$usePhpStan) {
    safeUnlink(__DIR__ . '/phpstan.neon.dist');
    safeUnlink(__DIR__ . '/phpstan-baseline.neon');
    safeUnlink(__DIR__ . '/.github/workflows/phpstan.yml');

    removeComposerDeps([
        'phpstan/extension-installer',
        'phpstan/phpstan-deprecation-rules',
        'phpstan/phpstan-phpunit',
        'nunomaduro/larastan',
    ], 'require-dev');

    removeComposerDeps(['analyse'], 'scripts');
}

if (!$usePint) {
    safeUnlink(__DIR__ . '/.github/workflows/fix-php-code-style-issues.yml');
    safeUnlink(__DIR__ . '/pint.json');

    removeComposerDeps([
        'laravel/pint',
    ], 'require-dev');

    removeComposerDeps(['format'], 'scripts');
}

if (!$useUpdateChangelogWorkflow) {
    safeUnlink(__DIR__ . '/.github/workflows/update-changelog.yml');
}

//confirm('Execute `composer install`?') && run('composer install');

// delete config script file
//if (confirm('Let this script delete itself?', true)) {
unlink(__FILE__);
//}

function ask(string $question, string $default = ''): string
{
    $def = $default ? "\e[0;33m ($default)" : '';
    $answer = readline("\e[0;32m" . $question . $def . ": \e[0m");

    if (!$answer) {
        return $default;
    }

    return $answer;
}

function confirm(string $question, bool $default = false): bool
{
    $answer = ask($question, ($default ? 'Y/n' : 'y/N'));

    if (strtolower($answer) === 'y/n') {
        return $default;
    }

    return strtolower($answer) === 'y';
}

function writeln(string $line): void
{
    echo $line . PHP_EOL;
}

function run(string $command): string
{
    return trim((string) shell_exec($command));
}

function slugify(string $subject): string
{
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $subject), '-'));
}

function titleCase(string $subject): string
{
    return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $subject)));
}

function titleSnake(string $subject, string $replace = '_'): string
{
    return str_replace(['-', '_'], $replace, $subject);
}

function replaceInFile(string $file, array $replacements): void
{
    $contents = file_get_contents($file);

    file_put_contents(
        $file,
        str_replace(
            array_keys($replacements),
            array_values($replacements),
            $contents
        )
    );
}

function removePrefix(string $prefix, string $content): string
{
    if (str_starts_with($content, $prefix)) {
        return substr($content, strlen($prefix));
    }

    return $content;
}

function removeComposerDeps(array $names, string $location): void
{
    $data = json_decode(file_get_contents(__DIR__ . '/composer.json'), true);

    foreach ($data[$location] as $name => $version) {
        if (in_array($name, $names, true)) {
            unset($data[$location][$name]);
        }
    }

    file_put_contents(__DIR__ . '/composer.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
}

function removeNpmDeps(array $names, string $location): void
{
    $data = json_decode(file_get_contents(__DIR__ . '/package.json'), true);

    foreach ($data[$location] as $name => $version) {
        if (in_array($name, $names, true)) {
            unset($data[$location][$name]);
        }
    }

    file_put_contents(__DIR__ . '/package.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES |
        JSON_UNESCAPED_UNICODE));
}

function removeTag(string $file, string $tag): void
{
    $contents = file_get_contents($file);

    file_put_contents(
        $file,
        preg_replace('/<!--' . $tag . '-->.*<!--\/' . $tag . '-->/s', '', $contents) ?: $contents
    );
}

function setupPackageJsonForTheme(): void
{
    removeNpmDeps([
        'purge',
        'dev',
        'dev:scripts',
        'build',
        'build:scripts',
    ], 'scripts');

    removeNpmDeps([
        '@awcodes/filament-plugin-purge',
        'esbuild',
        'npm-run-all',
        'prettier',
        'prettier-plugin-tailwindcss',
    ], 'devDependencies');

    replaceInFile(__DIR__ . '/package.json', [
        'dev:styles' => 'dev',
        'build:styles' => 'build',
    ]);
}

function safeUnlink(string $filename): void
{
    if (file_exists($filename) && is_file($filename)) {
        unlink($filename);
    }
}

function determineSeparator(string $path): string
{
    return str_replace('/', DIRECTORY_SEPARATOR, $path);
}

function replaceForWindows($currentDirectory): array
{
    return preg_split('/\\r\\n|\\r|\\n/', run('dir /S /B * | findstr /v /i .git\ | findstr /v /i vendor | findstr /v /i ' . basename(__FILE__) . ' | findstr /r /i /M /F:/ ":author :vendor :package Norlogic nlmn-filamentsecretfield-module nlmn_filamentsecretfield_module vendor_name vendor_slug franciscomoreira@redfenix.pt"'));
}

function replaceForAllOtherOSes(): array
{
    return explode(PHP_EOL, run('grep -E -r -l -i ":author|:vendor|:package|Norlogic|nlmn-filamentsecretfield-module|nlmn_filamentsecretfield_module|vendor_name|vendor_slug|franciscomoreira@redfenix.pt" --exclude-dir=vendor ./* ./.github/* | grep -v ' . basename(__FILE__)));
}

function removeDirectory($dir): void
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != '.' && $object != '..') {
                if (filetype($dir . '/' . $object) == 'dir') {
                    removeDirectory($dir . '/' . $object);
                } else {
                    unlink($dir . '/' . $object);
                }
            }
        }
        rmdir($dir);
    }
}

$FilamentsecretfieldModulesToInclude = [
    'gitName' => $gitName,
    'authorName' => $authorName,
    'gitEmail' => $gitEmail,
    'authorEmail' => $authorEmail,
    'authorUsername' => $authorUsername,
    'vendorName' => $vendorName,
    'vendorSlug' => $vendorSlug,
    'vendorNamespace' => $vendorNamespace,
    'currentDirectory' => $currentDirectory,
    'folderName' => $folderName,
    'packageName' => $packageName,
    'ShortPackageName' => $ShortPackageName,
    'langName' => $langName,
    'modelName' => $modelName,
    'packageSlug' => $packageSlug,
    'packageSlugWithoutPrefix' => $packageSlugWithoutPrefix,
    'className' => $className,
    'classNameSpace' => $classNameSpace,
    'FilamentsecretfieldModuleName' => $FilamentsecretfieldModuleName,
    'description' => $description,
    'moduleName' => $moduleName,
];

writeVariablesToMarkdown($FilamentsecretfieldModulesToInclude, 'list.md');
$readmePath = 'README.md';
addVariablesToReadme($FilamentsecretfieldModulesToInclude, $readmePath);

function writeVariablesToMarkdown(array $FilamentsecretfieldModules, string $filename): void
{
    $markdownContent = "# Variables List\n\n";
    foreach ($FilamentsecretfieldModules as $key => $value) {
        $markdownContent .= "- **$key**: `$value`\n";
    }

    file_put_contents($filename, $markdownContent);
}

function addVariablesToReadme(array $FilamentsecretfieldModules, string $readmePath): void
{
    if (!file_exists($readmePath)) {
        echo "README.md not found at $readmePath\n";
        return;
    }

    $readmeContents = file_get_contents($readmePath);
    if ($readmeContents === false) {
        echo "Failed to read from README.md\n";
        return;
    }

    $FilamentsecretfieldModulesSection = "\n## Variables\n\n";
    foreach ($FilamentsecretfieldModules as $key => $value) {
        $FilamentsecretfieldModulesSection .= "- **$key**: `$value`\n";
    }

    $readmeContents .= $FilamentsecretfieldModulesSection;

    if (file_put_contents($readmePath, $readmeContents) === false) {
        echo "Failed to write to README.md\n";
        return;
    }

    echo "README.md updated successfully.\n";
}