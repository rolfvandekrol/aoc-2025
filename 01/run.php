<?php

$input = file_get_contents(__DIR__ . '/input');

function parse_input($input) {
	$lines = array_filter(array_map('trim', explode("\n", $input)));

    $parsed = [];

    foreach ($lines as $line) {
        $direction = substr($line, 0, 1);
        $value = substr($line, 1);

        $parsed[] = $direction === 'L' ? -$value : $value;
    }

    return $parsed;
}

$parsed_input = parse_input($input);

function run_1($parsed_input) {
    $state = 50;

    $result = 0;

    foreach ($parsed_input as $value) {
        // Simple modulo based approach sufficed here.
        $state = ($state + $value + 100) % 100;

        if ($state === 0) {
            $result++;
        }
    }

    return $result;
}

print run_1($parsed_input) . PHP_EOL;

function run_2($parsed_input) {
	$state = 50;

	$result = 0;

	foreach ($parsed_input as $value) {
        $size = abs($value);
        $direction = $value < 0 ? -1 : 1;

        // First we count the amount of full rotations and adjust the size so
        // it doesn't contain a full rotation anymore.
        $result += floor($size / 100);
        $size = $size % 100;

        // To prevent double counting of 0 when moving downward from a 0 state,
        // we set the state to 100.
        if ($direction == -1 && $size > 0 && $state == 0) {
            $state = 100;
        }

        // We calculate the new state without modulo
        $newState = $state + $size * $direction;

        // And then we do that modulo
        $state = ($newState + 100) % 100;

        // If we ended up on 0 or if the modulo changed the state we passed 0.
        if ($state === 0 || $state != $newState) {
            $result++;
        }
    }

    return $result;
}

print run_2($parsed_input) . PHP_EOL;