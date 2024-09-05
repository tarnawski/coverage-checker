<?php

require_once dirname(__FILE__) . '/vendor/autoload.php';

$pathToFileWithCoverageReport = $argv[1];
$codeCoverageThreshold = $argv[2] ?? 100; # Default value of minimum code coverage is 100%

$coverageChecker = new CoverageChecker\Checker($pathToFileWithCoverageReport);
$coverageChecker->check($codeCoverageThreshold) ? exit(0) : exit(1);
