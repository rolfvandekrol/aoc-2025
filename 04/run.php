<?php

$input = file_get_contents(__DIR__ . '/input');

function parse_input($input) {
    $lines = array_filter(array_map('trim', explode("\n", $input)));

    $parsed = [];

    foreach ($lines as $line) {
        $parsed[] = str_split($line);
    }

    return $parsed;
}

$parsed_input = parse_input($input);

function run_1($parsed_input) {
    $pos_map = [
        [-1, -1],
        [-1, 0],
        [-1, 1],
        [0, -1],
        [0, 1],
        [1, -1],
        [1, 0],
        [1, 1],
    ];

    $cnt = 0;

    for($y = 0; $y < count($parsed_input); $y++) {
        for ($x = 0; $x < count($parsed_input[$y]); $x++) {
            if ($parsed_input[$y][$x] != '@') {
                continue;
            }

            $adjCount = 0;

            foreach ($pos_map as [$yd, $xd]) {
                $ay = $y + $yd;
                $ax = $x + $xd;

                if ($ay < 0 || $ay >= count($parsed_input) || $ax < 0 || $ax >= count($parsed_input[$y]) ) {
                    continue;
                }

                if ($parsed_input[$ay][$ax] == '@') {
                    $adjCount++;
                }
            }

            if ($adjCount < 4) {
                $cnt++;
            }

        }
    }

    return $cnt;
}

print run_1($parsed_input) . PHP_EOL;

function run_2($parsed_input) {
    $pos_map = [
        [-1, -1],
        [-1, 0],
        [-1, 1],
        [0, -1],
        [0, 1],
        [1, -1],
        [1, 0],
        [1, 1],
    ];

    $cnt = 0;

    $run = true;

    while ($run) {
        $run = false;

        for($y = 0; $y < count($parsed_input); $y++) {
            for ($x = 0; $x < count($parsed_input[$y]); $x++) {
                if ($parsed_input[$y][$x] != '@') {
                    continue;
                }

                $adjCount = 0;

                foreach ($pos_map as [$yd, $xd]) {
                    $ay = $y + $yd;
                    $ax = $x + $xd;

                    if ($ay < 0 || $ay >= count($parsed_input) || $ax < 0 || $ax >= count($parsed_input[$y]) ) {
                        continue;
                    }

                    if ($parsed_input[$ay][$ax] == '@') {
                        $adjCount++;
                    }
                }

                if ($adjCount < 4) {
                    $cnt++;
                    $run = true;
                    $parsed_input[$y][$x] = '.';
                }

            }
        }
    }

    return $cnt;
}

print run_2($parsed_input) . PHP_EOL;