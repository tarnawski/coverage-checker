<?php

declare(strict_types=1);

namespace CoverageChecker;

use InvalidArgumentException;
use SimpleXMLElement;

readonly class Checker implements CheckerInterface
{
    public function __construct(private string $path)
    {
        if (!file_exists($path)) {
            throw new InvalidArgumentException('File not exist.');
        }
    }

    public function check(float $threshold): bool
    {
        if (0 > $threshold) {
            throw new InvalidArgumentException('Threshold is below the range.');
        }
        if (100 < $threshold) {
            throw new InvalidArgumentException('Threshold is above the range.');
        }

        return $this->coverage() >= $threshold;
    }

    public function print(string $message = self::MESSAGE, int $precision = self::PRECISION): string
    {
        return strtr($message, ['{{coverage}}' => number_format($this->coverage(), $precision)]);
    }

    private function coverage(): float
    {
        $metrics = (new SimpleXMLElement(file_get_contents($this->path)))->xpath('//metrics');
        $elements = array_sum(array_map(fn ($metric) => (int) $metric['elements'], $metrics));
        $covered = array_sum(array_map(fn ($metric) => (int) $metric['coveredelements'], $metrics));

        return 0 === $covered ? 0 : ($covered / $elements) * 100;
    }
}
