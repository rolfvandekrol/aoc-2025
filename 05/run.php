<?php

$input = file_get_contents(__DIR__ . '/input');

function parse_input($input) {
    [$rangesInput, $idsInput] = explode("\n\n", $input, 2);

    $ranges = array_map(function ($v) {
        [$start, $end] = explode('-', $v, 2);
        return [intval($start), intval($end)];
    }, array_filter(array_map('trim', explode("\n", $rangesInput))));

    $ids = array_map(fn ($v) => intval($v), array_filter(array_map('trim', explode("\n", $idsInput))));

    return [$ranges, $ids];
}

$parsed_input = parse_input($input);

// First we normalize the ranges to make sure we have an ordered list of ranges
// that don't overlap.
function normalize_ranges($rangesInput) {
    $normalizedRanges = [];

    // For each range we figure out where to insert it in the list of
    // normalized ranges.
    foreach ($rangesInput as [$start, $end]) {
        // First we figure out which ranges are completely before the range to
        // insert.
        $before = [];

        while (count($normalizedRanges)) {
            [$nStart, $nEnd] = array_shift($normalizedRanges);

            // Add it to the before list if the new range starts after the other
            // range ends.
            if ($nEnd < $start) {
                $before[] = [$nStart, $nEnd];
                continue;
            }

            // If the new range is completely before the other range, we can
            // simply insert it in the correct spot without merging it with any
            // other range.
            if ($end < $nStart) {
                $normalizedRanges = array_merge($before, [[$start, $end], [$nStart, $nEnd]], $normalizedRanges);
                continue 2;
            }

            // Now we need to merge some ranges, we start with finding the
            // start point.
            $newStart = min($start, $nStart);

            // We simply assume and endpoint and then check if we need to
            // include more ranges in the merge procedure.
            $newEnd = max($end, $nEnd);
            while(count($normalizedRanges)) {
                [$nextStart, $nextEnd] = array_shift($normalizedRanges);

                // If the next range starts after the end of the newly
                // constructed range, we can simply insert this range at the
                // correct spot and move on.
                if ($nextStart > $newEnd) {
                    $normalizedRanges = array_merge($before, [[$newStart, $newEnd], [$nextStart, $nextEnd]], $normalizedRanges);
                    continue 3;
                }

                // Otherwise, we need to merge this range as well.
                $newEnd = max($newEnd, $nextEnd);
            }

            // If we end up here, there we no more ranges to consider for
            // merging, so we add the newly constructed range at the end.
            $normalizedRanges = array_merge($before, [[$newStart, $newEnd]]);
            continue 2;
        }

        // If we end up here, there we no more ranges to consider for
        // merging, so we add the range at the end.
        $normalizedRanges = array_merge($before, [[$start, $end]]);
    }

    return $normalizedRanges;
}

$parsed_input[0] = normalize_ranges($parsed_input[0]);

function run_1($parsed_input) {
    [$ranges, $ids] = $parsed_input;

    $cnt = 0;

    foreach ($ids as $id) {
        foreach ($ranges as [$start, $end]) {
            // Drop out if are at a range after the ID. Because the ranges are
            // ordered, we don't need to check any more ranges.
            if ($id < $start) {
                break;
            }

            if ($id <= $end) {
                $cnt++;
                break;
            }
        }
    }

    return $cnt;
}

print run_1($parsed_input) . PHP_EOL;

function run_2($parsed_input)
{
    [$rangesInput,] = $parsed_input;

    $cnt = 0;

    // Because the ranges are normalized we can simply sum up the sizes of the
    // ranges.
    foreach ($rangesInput as [$start, $end]) {
        $cnt += $end - $start + 1;
    }

    return $cnt;
}

print run_2($parsed_input) . PHP_EOL;