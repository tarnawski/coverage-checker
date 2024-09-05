<?php

namespace CoverageChecker;

interface CheckerInterface
{
    public const MESSAGE = 'Code coverage is: {{coverage}}%.';
    public const PRECISION = 2;

    public function check(float $threshold): bool;
    public function print(string $message = self::MESSAGE, int $precision = self::PRECISION): string;
}
