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
    $beams = [];

    $splitCount = 0;

    // We simply keep the beams as a list of beam indexes, and everytime a beam
    // encounters a splitter we split it into two beams. We can use
    // array_intersect and array_diff to find the beams that touched a splitter
    // and those that don't.
    // We ignore every second row ($i += 2), because those are irrelevant to the
    // outcome.
    for ($i = 0; $i < count($parsed_input); $i += 2) {
        if ($i === 0) {
            $beams[] = array_search('S', $parsed_input[$i]);
            continue;
        }

        $splitters = array_keys($parsed_input[$i], '^');

        $splitBeams = array_intersect($beams, $splitters);
        $splitCount += count($splitBeams);
        $keepBeams = array_diff($beams, $splitBeams);

        $beams = [];
        foreach ($keepBeams as $beam) {
            $beams[] = $beam;
        }
        foreach ($splitBeams as $beam) {
            $beams[] = $beam - 1;
            $beams[] = $beam + 1;
        }

        $beams = array_unique($beams);
        sort($beams);
    }

    return $splitCount;
}

print run_1($parsed_input) . PHP_EOL;

function run_2($parsed_input) {
    $beams = [];

    // In this scenario we still keep the beams as indexes, but in an
    // associative array, where the key is the index of the beam and the value
    // is the amount of worlds the beam corresponds with.
    for ($i = 0; $i < count($parsed_input); $i += 2) {
        if ($i === 0) {
            $beams[array_search('S', $parsed_input[$i])] = 1;
            continue;
        }

        $splitters = array_keys($parsed_input[$i], '^');

        $newBeams = [];
        foreach ($beams as $beam => $worlds) {
            if (in_array($beam, $splitters)) {
                $newBeams[$beam-1] = ($newBeams[$beam-1] ?? 0) + $worlds;
                $newBeams[$beam+1] = ($newBeams[$beam+1] ?? 0) + $worlds;
            } else {
                $newBeams[$beam] = ($newBeams[$beam] ?? 0) + $worlds;
            }
        }

        $beams = $newBeams;
    }

    return array_sum(array_values($beams));
}

print run_2($parsed_input) . PHP_EOL;