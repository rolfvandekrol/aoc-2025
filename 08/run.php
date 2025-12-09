<?php

$input = file_get_contents(__DIR__ . '/input');

function parse_input($input) {
    $lines = array_filter(array_map('trim', explode("\n", $input)));

    $parsed = [];

    foreach ($lines as $line) {
        $parsed[] = array_map(fn($v) => intval($v), explode(',', $line));
    }

    return $parsed;
}

$points = parse_input($input);

$pairs = [];
for ($i = 0; $i < count($points) - 1; $i++) {
    for ($j = $i + 1; $j < count($points); $j++) {
        [$ix, $iy, $iz] = $points[$i];
        [$jx, $jy, $jz] = $points[$j];

        $pairs[] = [$i, $j, abs($ix - $jx)**2 + abs($iy - $jy)**2 + abs($iz - $jz)**2, $points[$i], $points[$j]];
    }
}

usort($pairs, fn ($a, $b) => $a[2] <=> $b[2]);

function run_1($points, $pairs) {
    $circuits = [];
    $circuit_points = [];
    foreach ($points as $i => $p) {
        $circuits[$i] = [$i];
        $circuit_points[$i] = $i;
    }

    foreach (array_slice($pairs, 0, 1000) as [$a, $b, $d, $ap, $bp]) {
        if ($circuit_points[$a] == $circuit_points[$b]) {
            continue;
        }

        $circuit_a = $circuit_points[$a];
        $circuit_b = $circuit_points[$b];

        foreach ($circuits[$circuit_b] as $p) {
            $circuits[$circuit_a][] = $p;
            $circuit_points[$p] = $circuit_a;
        }

        unset($circuits[$circuit_b]);
    }

    $circuit_sizes = array_map(fn ($c) => count($c), $circuits);

    rsort($circuit_sizes);

    return array_product(array_slice($circuit_sizes, 0, 3));
}

print run_1($points, $pairs) . PHP_EOL;

function run_2($points, $pairs) {
    $circuits = [];
    $circuit_points = [];
    foreach ($points as $i => $p) {
        $circuits[$i] = [$i];
        $circuit_points[$i] = $i;
    }

    foreach ($pairs as [$a, $b, $d, $ap, $bp]) {
        if ($circuit_points[$a] == $circuit_points[$b]) {
            continue;
        }

        $circuit_a = $circuit_points[$a];
        $circuit_b = $circuit_points[$b];

        foreach ($circuits[$circuit_b] as $p) {
            $circuits[$circuit_a][] = $p;
            $circuit_points[$p] = $circuit_a;
        }

        unset($circuits[$circuit_b]);

        if (count($circuits) == 1) {
            return $ap[0] * $bp[0];
        }
    }
}

print run_2($points, $pairs) . PHP_EOL;