<?php

$input = file_get_contents(__DIR__ . '/input');

function parse_input_1($input)
{
    $lines = array_filter(array_map('trim', explode("\n", $input)));

    $operationsLine = array_pop($lines);
    $operations = array_values(array_filter(explode(" ", $operationsLine)));

    $numbers = [];
    foreach ($lines as $line) {
        $numbers[] = array_values(array_map(fn ($v) => intval(trim($v)), array_filter(explode(" ", $line))));
    }

    return [$numbers, $operations];
}

$parsed_input_1 = parse_input_1($input);

function construct_exercises_1($numbers, $operations) {
    $exercises = [];

    foreach ($operations as $i => $operation) {
        $exercises[] = [array_map(fn ($line) => $line[$i], $numbers), $operation];
    }

    return $exercises;
}

function run_1($parsed_input) {
    $exercises = construct_exercises_1($parsed_input[0], $parsed_input[1]);

    $sum = 0;

    foreach ($exercises as [$numbers, $operation]) {
        $sum += match ($operation) {
            '+' => array_sum($numbers),
            '*' => array_product($numbers),
        };
    }

    return $sum;
}

print run_1($parsed_input_1) . PHP_EOL;

function run_2($input)
{
    $lines = array_filter(explode("\n", $input));

    $length = max(array_map(fn ($line) => strlen($line), $lines));

    $operationsLine = array_pop($lines);

    $sum = 0;

    $operation = null;
    $numbers = [];
    for ($i = 0; $i < $length; $i++) {
        if ($operation === null) {
            $operation = $operationsLine[$i];
        }

        $digits = array_filter(array_map(fn ($line) => trim($line[$i] ?? ''), $lines));

        if (empty($digits)) {
            $sum += match ($operation) {
                '+' => array_sum($numbers),
                '*' => array_product($numbers),
            };

            $operation = null;
            $numbers = [];
        } else {
            $numbers[] = intval(implode('', $digits));
        }
    }

    $sum += match ($operation) {
        '+' => array_sum($numbers),
        '*' => array_product($numbers),
    };

    return $sum;
}

print run_2($input) . PHP_EOL;