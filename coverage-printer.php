<?php

require_once dirname(__FILE__) . '/vendor/autoload.php';

$pathToFileWithCoverageReport = $argv[1];
echo (new CoverageChecker\Checker($pathToFileWithCoverageReport))->print() . PHP_EOL;
