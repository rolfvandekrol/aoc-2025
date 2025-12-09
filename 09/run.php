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

// Basically the same approach as puzzle 8, but than with a different scoring
// function (area instead of distance) and sorting the other way around.
function run_1($points) {
    $pairs = [];
    for ($i = 0; $i < count($points) - 1; $i++) {
        for ($j = $i + 1; $j < count($points); $j++) {
            [$ix, $iy] = $points[$i];
            [$jx, $jy] = $points[$j];

            $pairs[] = [$i, $j, (abs($ix - $jx) + 1) * (abs($iy - $jy) + 1), $points[$i], $points[$j]];
        }
    }

    usort($pairs, fn ($a, $b) => $b[2] <=> $a[2]);

    return $pairs[0][2];
}

print run_1($points) . PHP_EOL;

function run_2($points) {
    $c = count($points);

    // Construct the lines that compromise the green tiles.
    $lines = [];
    for ($i = 0; $i < $c; $i++) {
        $j = ($i + 1) % $c;

        if ($points[$i][0] === $points[$j][0]) {
            // x is the same, so vertical
            $d = 0;
        } else {
            // y is the same, so horizontal
            $d = 1;
        }

        $lines[] = [$i, $j, $d, $points[$i], $points[$j]];
    }

    // Make sure there are no touching lines. If there are no touching lines,
    // we know that every line has a green side and a white side. That means
    // that we only need to make sure there are no lines crossing through the
    // rectangle.
    for ($i = 0; $i < $c - 1; $i++) {
        $id = $lines[$i][2];
        // The value of the line is the coordinate of the points for the
        // direction where the coordinate is the same.
        $iv = $points[$lines[$i][0]][$id];

        for ($j = $i + 1; $j < $c; $j++) {
            $jd = $lines[$j][2];
            // The value of the line is the coordinate of the points for the
            // direction where the coordinate is the same.
            $jv = $points[$lines[$j][0]][$jd];

            // If the lines don't have the same direction they are not touching
            if ($id != $jd) {
                continue;
            }

            // There is a distance between values of the lines, so they don't
            // touch.
            if (abs($iv - $jv) > 1) {
                continue;
            }

            // We construct ranges for both lines along the coordinate that
            // differs for the points.
            $nd = $id ? 0 : 1;

            $ir0 = $points[$lines[$i][0]][$nd];
            $ir1 = $points[$lines[$i][1]][$nd];
            $ir = [min($ir0, $ir1), max($ir0, $ir1)];

            $jr0 = $points[$lines[$j][0]][$nd];
            $jr1 = $points[$lines[$j][1]][$nd];
            $jr = [min($jr0, $jr1), max($jr0, $jr1)];

            // Check if the ranges touch
            if ($ir[0] <= $jr[1] && $ir[1] >= $jr[0]) {
                var_dump([$lines[$i], [$lines[$j]]]);
                die;
            }
        }
    }

    // Find all the pairs and sort by size, so we start looking at the
    // biggest rectangle
    $pairs = [];
    for ($i = 0; $i < $c - 1; $i++) {
        for ($j = $i + 1; $j < $c; $j++) {
            [$ix, $iy] = $points[$i];
            [$jx, $jy] = $points[$j];

            $pairs[] = [$i, $j, (abs($ix - $jx) + 1) * (abs($iy - $jy) + 1), $points[$i], $points[$j]];
        }
    }

    usort($pairs, fn ($a, $b) => $b[2] <=> $a[2]);

    // We loop over the potential rectangles and return the area of the first
    // rectangle that we don't reject based on the lines.
    foreach ($pairs as $pair) {
        // Construct ranges for the rectangle
        [$ix, $iy] = $points[$pair[0]];
        [$jx, $jy] = $points[$pair[1]];

        $r = [
            [min($ix, $jx), max($ix, $jx)],
            [min($iy, $jy), max($iy, $jy)],
        ];

        // Loop over the lines. If no line rejects the rectangle (continue 2),
        // we accept the rectangle
        foreach ($lines as $line) {
            $d = $line[2];
            $nd = $d ? 0 : 1;

            // If the value of the line is not in the relevant range, or on the
            // borders of the range, it doesn't cross the rectangle.
            $dv = $points[$line[0]][$d];
            if ($dv <= $r[$d][0] || $dv >= $r[$d][1]) {
                continue;
            }

            // If the range of the line along the other direction doesn't
            // overlap the relevant range of the rectangle (or only touches on
            // the border) it doesn't cross the rectangle.
            $ndr0 = $points[$line[0]][$nd];
            $ndr1 = $points[$line[1]][$nd];
            $ndr = [min($ndr0, $ndr1), max($ndr0, $ndr1)];
            if ($ndr[0] >= $r[$nd][1] || $ndr[1] <= $r[$nd][0]) {
                continue;
            }

            // At this point we know the line crosses with our rectangle.
            continue 2;
        }

        return $pair[2];
    }
}

print run_2($points) . PHP_EOL;