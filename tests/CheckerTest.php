<?php

declare(strict_types=1);

namespace CoverageChecker\Tests;

use CoverageChecker\Checker;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CheckerTest extends TestCase
{
    public function testCreateInstanceOfCheckerWithNotExistingFile(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('File not exist.');

        new Checker(dirname(__FILE__) . '/unknown.xml');
    }

    public function testPrintCodeCoverage(): void
    {
        $checker = new Checker(dirname(__FILE__) . '/coverage.xml');
        $output = $checker->print();

        $this->assertEquals('Code coverage is: 77.27%.', $output);
    }

    public function testPrintPrintCodeCoverageWithCustomMessageAndPrecision(): void
    {
        $checker = new Checker(dirname(__FILE__) . '/coverage.xml');
        $output = $checker->print('Code coverage equal: {{coverage}}!', 1);

        $this->assertEquals('Code coverage equal: 77.3!', $output);
    }

    public function testCheckCodeCoverageWithThresholdAboveRange(): void
    {
        $checker = new Checker(dirname(__FILE__) . '/coverage.xml');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Threshold is above the range.');

        $checker->check(120);
    }

    public function testCheckCodeCoverageWithThresholdBelowRange(): void
    {
        $checker = new Checker(dirname(__FILE__) . '/coverage.xml');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Threshold is below the range.');

        $checker->check(-5);
    }

    #[DataProvider('checkCodeCoverageWithGivenThresholdProvider')]
    public function testCheckCodeCoverageWithGivenThreshold(bool $expected, float $threshold): void
    {
        $checker = new Checker(dirname(__FILE__) . '/coverage.xml');
        $result = $checker->check($threshold);

        $this->assertEquals($expected, $result);
    }

    public static function checkCodeCoverageWithGivenThresholdProvider(): array
    {
        return [
            'code coverage greater than threshold' => [true, 50],
            'code coverage equal threshold' => [true, 77.27],
            'code coverage lower than threshold' => [false, 80],
        ];
    }
}
