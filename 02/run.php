<?php

$input = file_get_contents(__DIR__ . '/input');

function parse_input($input) {
    $lines = array_filter(array_map('trim', explode(",", $input)));

    $parsed = [];

    foreach ($lines as $line) {
        $line = explode('-', $line);

        $parsed[] = [intval($line[0]), intval($line[1])];
    }

    return $parsed;
}

$parsed_input = parse_input($input);

function run_1($parsed_input) {
    $ids = [];
    foreach ($parsed_input as [$start, $end]) {
        $startSize = strlen(strval($start));
        $endSize = strlen(strval($end));

        for ($size = $startSize; $size <= $endSize; $size++) {
            if ($size % 2) {
                continue;
            }

            // The main observation here is that you can use math to check if a
            // number is repeating. If a 6-digit number is of the pattern
            // abcabc, then it is divisible by 1001. So we only need to find
            // the numbers divisible by this factor within the range.

            $sizeStart = max($start, pow(10, $size - 1));
            $sizeEnd = min($end, pow(10, $size) - 1);

            $factor = pow(10, $size / 2) + 1;

            $sizeStartFactor = ceil(floatval($sizeStart) / $factor);
            $sizeEndFactor = floor(floatval($sizeEnd) / $factor);

            if ($sizeStartFactor > $sizeEndFactor) {
                continue;
            }

            for ($i = $sizeStartFactor; $i <= $sizeEndFactor; $i++) {
                $ids[] = $factor * $i;
            }
        }
    }

    return array_sum($ids);
}

print run_1($parsed_input) . PHP_EOL;

function run_2($parsed_input) {
    $ids = [];
    foreach ($parsed_input as [$start, $end]) {
        $startSize = strlen(strval($start));
        $endSize = strlen(strval($end));

        for ($size = $startSize; $size <= $endSize; $size++) {
            $sizeStart = max($start, pow(10, $size - 1));
            $sizeEnd = min($end, pow(10, $size) - 1);

            // The same logic is in puzzle 1 applies here, but we need to
            // consider more repeating patterns. Those other repeating patterns
            // can also be expressed as a number we can divide by. If we take a
            // 6-digit number as an example again, it needs to be divisible by
            // 1001, 10101 or 111111. We can find those by looking at the proper
            // divisors of 6 (1, 2 and 3). The divisor determines the size of
            // the repeating pattern, so 1 => 111111, 2 => 10101 and 3 => 1001.

            for ($divisor = 1; $divisor < $size; $divisor++) {
                if ($size % $divisor) {
                    continue;
                }

                $factor = 0;
                for ($i = 0; $i < ($size / $divisor); $i++) {
                    $factor += 1 * pow(10, $i * $divisor);
                }

                $sizeStartFactor = ceil(floatval($sizeStart) / $factor);
                $sizeEndFactor = floor(floatval($sizeEnd) / $factor);

                if ($sizeStartFactor > $sizeEndFactor) {
                    continue;
                }

                for ($i = $sizeStartFactor; $i <= $sizeEndFactor; $i++) {
                    $ids[] = $factor * $i;
                }
            }
        }
    }

    return array_sum(array_unique($ids));
}

print run_2($parsed_input) . PHP_EOL;