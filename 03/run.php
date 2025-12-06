<?php

$input = file_get_contents(__DIR__ . '/input');

function parse_input($input) {
    $lines = array_filter(array_map('trim', explode("\n", $input)));

    $parsed = [];

    foreach ($lines as $line) {
        $parsed[] = array_map(fn ($v) => intval($v), str_split($line));
    }

    return $parsed;
}

$parsed_input = parse_input($input);

// To maximize the joltage of a battery pack, the basic trick is to find the
// highest digit while keeping enough digits to be able to fulfill the size
// requirement.
function max_joltage_digits($digits, $size) {
    if ($size === 0) {
        return [];
    }

    if (count($digits) === $size) {
        return $digits;
    }

    // We use array slice to find the part of the digits where we need to find
    // the highest digit, while leaving enough digits to construct the rest of
    // the number from.
    $sector = array_slice($digits, 0, 1 - $size ?: null);

    // We find the largest digit
    $digit = max($sector);

    // We get the first position of this digit to know which part we still need
    // to consider for the remaining digits.
    $pos = array_search($digit, $sector);

    // Recursion to retrieve the remaining digits
    return array_merge([$digit], max_joltage_digits(array_slice($digits, $pos + 1), $size - 1));
}

function max_joltage($digits, $size) {
    return intval(implode('', max_joltage_digits($digits, $size)));
}

function run_1($parsed_input) {
    $sum = 0;

    foreach ($parsed_input as $value) {
        $sum += max_joltage($value, 2);
    }

    return $sum;
}

print run_1($parsed_input) . PHP_EOL;

function run_2($parsed_input) {
    $sum = 0;

    foreach ($parsed_input as $value) {
        $sum += max_joltage($value, 12);
    }

    return $sum;
}

print run_2($parsed_input) . PHP_EOL;