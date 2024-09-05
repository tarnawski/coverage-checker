<?php

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}
if (file_exists(__DIR__ . '/../../autoload.php')) {
    require_once __DIR__ . '/../../autoload.php';
}

$pathToFileWithCoverageReport = $argv[1];
echo (new CoverageChecker\Checker($pathToFileWithCoverageReport))->print() . PHP_EOL;
